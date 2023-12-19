<?php 

add_action('wp_ajax_fetch_quote_inquiry_details', 'fetch_quote_inquiry_details');
add_action('wp_ajax_nopriv_fetch_quote_inquiry_details', 'fetch_quote_inquiry_details');
function fetch_quote_inquiry_details(){
    global $accu_headers;
    include("../acumatica/common-functions.php");
    $url = 'https://carefreeofcolorado.acumatica.com/entity/auth/login';
    $type = 'POST';
    // Get the OptionTree object
    $optiontree = get_option('option_tree');
    $name = $optiontree['accu_name'];
    $password = $optiontree['accu_password'];
    $company = $optiontree['accu_company'];

    $postFields = ["name" => $name,  "password" =>  $password, "company" =>  $company];
    /* $headers = ['Content-Type: application/json']; */
    $cookieJar = tempnam('/tmp','cookie');
    $curl = curl_init();
    $curlParams = array(
        CURLOPT_URL => $url,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIESESSION => 1,
        CURLOPT_COOKIEJAR => $cookieJar,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $type,
        CURLOPT_HTTPHEADER => $accu_headers,
    );
    $curlParams[ CURLOPT_POSTFIELDS ] =  json_encode($postFields);
    curl_setopt_array($curl, $curlParams);
    $response = curl_exec($curl);
    $httpstatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if($httpstatus != 204){ 
        logAcumaticaAPIError(json_decode($response, true)["message"]);
        die('error in login API');
    }
    $filter = '';
    $current_user = wp_get_current_user();
    $current_username = $current_user->display_name;
    $current_user_cust_no = get_user_meta(get_current_user_id(), "customer_number", true);
    $to_date = $_GET['to_date']; 
    $from_date = $_GET['from_date']; 

    $filter = "OrderType eq 'QT'";
    if(!$current_user_cust_no){
        $filter .= ' and CustomerID eq '.$current_user_cust_no .' or ';
    }
    if(!empty($from_date)){
        $filter .= " and Date gt datetimeoffset'".$from_date."'";
    }
    if(!empty($from_date)){
        $filter .= " and Date lt datetimeoffset'".$to_date."'";
    }

    $filter = ltrim($filter, 'and ');
    $filter = str_replace('+', '%20', urlencode($filter));
    $curl_url = 'https://carefreeofcolorado.acumatica.com/entity/Default/22.200.001/SalesOrder?%24expand=Details%2CShipments&%24filter='.$filter;
     curl_setopt_array($curl, array(
     CURLOPT_URL => $curl_url,
     CURLOPT_SSL_VERIFYHOST => 0,
     CURLOPT_SSL_VERIFYPEER => 0,
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_COOKIEJAR => $cookieJar,
     CURLOPT_ENCODING => '',
     CURLOPT_TIMEOUT => 0,
     CURLOPT_FOLLOWLOCATION => true,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => 'GET',
     CURLOPT_HTTPHEADER => $accu_headers,
     ));
     $response = curl_exec($curl);
     curl_close($curl);
     $response_array = json_decode($response);
     unlink($cookieJar) or die('Can not unlink $cookieJar');
     if(empty($response_array)){
        $quote_inquery_tbl = '<p>No quotes found for the search criteria.</p>';
        wp_send_json_success(array('quote_inquiry_data'=>$quote_inquery_tbl));
    }
    $quote_inquery_tbl ='
    <p>'.count($response_array).' Quotes(s) found. Click on view button to view detial.</p>
    <div class="ordr-inq-wrapper">
        <table class="ordr-inq-table" border="1">
        <thead>
        <tr>    
            <th>Sno</th>
            <th scope="col">Quote#</th>
            <th scope="col">Quote Date</th>
            <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>';
    $counter = 1;
    foreach($response_array as $response_array_value){
        $quote_inquery_tbl .='<tr>
                                <td>'.$counter.'</td>
                                <td>'.$response_array_value->OrderNbr->value.'</td>
                                <td>'.date('jS M, Y', strtotime($response_array_value->Date->value)). PHP_EOL.'</td>
                                <td><button class="btn" data-toggle="modal" data-target="#'.$response_array_value->OrderNbr->value.'" style="background-color: #fe710b;color: white;">View</button></td>
                             </tr>';
                             $quote_inquery_modal .= '
                             <!-- Modal -->
                             <div class="modal fade OrderEnquiryModal" id="'.$response_array_value->OrderNbr->value.'" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                             <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                 <div class="modal-content id="printableArea"">
                                     <div class="modal-header">
                                         <h5 class="modal-title" id="exampleModalLabel">Quote Detail</h5>
                                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                             <span aria-hidden="true">&times;</span>
                                         </button>
                                     </div>
                                     <div class="modal-body">
                                         <div class="row align-items-center mb-3">
                                             <div class="col-md-5 mb-2 mb-md-0">
                                                 <div>
                                                     <b>Quote Number:</b> '.$response_array_value->OrderNbr->value.'
                                                 </div>
                                             </div>
                                             <div class="col-md-5">
                                                 <div>
                                                     <b>Quote Date:</b> '.date('Y-m-d', strtotime($response_array_value->Date->value)). PHP_EOL.'
                                                 </div>
                                                 <div>
                                                     <b>Required Date:</b> '.date('Y-m-d', strtotime($response_array_value->RequestedOn->value)). PHP_EOL.'
                                                 </div>
                                             </div>
                                         </div>
                                         <div class="product-info-wrapper">
                                             <h2>Product Detail</h2>
                                             <table class="product-info-table" border="1">
                                                 <thead>
                                                     <tr>
                                                         <th scope="col">Product Number</th>
                                                         <th scope="col">Product Description</th>
                                                         <th scope="col">Order Qty</th>
                                                         <th scope="col">Ship Qty</th>
                                                     </tr>
                                                 </thead>
                                                 <tbody>';
                                                     foreach($response_array_value->Details as $detail_value){
                                                     $quote_inquery_modal.=' <tr>
                                                         <td>'.$detail_value->InventoryID->value.'</td>
                                                         <td>'.$detail_value->LineDescription->value.'</td>
                                                         <td>'.$detail_value->OrderQty->value.'</td>
                                                         <td>'.$detail_value->QtyOnShipments->value.'</td>';
                                                    
                                                     }
                                                     $quote_inquery_modal.=' </tbody>
                                             </table>
                                         </div>
                                     </div>
                                     <div class="modal-footer justify-content-between">
                                         <button type="button" class="btn btn-secondary btn-orange" onclick="printDiv(\'print-'.$response_array_value->OrderNbr->value.'\')">Print</button>
                                         <button type="button" class="btn btn-primary btn-orange" data-dismiss="modal">Exit</button>
                                     </div>
                                 </div>
                             </div>
                         </div>';
                         $print_area.='<div id="print-'.$response_array_value->OrderNbr->value.'" style="display:none;">
                         <h1>Carefree of Colorado Order '.$response_array_value->OrderNbr->value.'</h1>
                         <div class="row align-items-center mb-3">
                                             <div class="col-md-5 mb-2 mb-md-0">
                                                 <div>
                                                     <b>Quote Number:</b> '.$response_array_value->OrderNbr->value.'
                                                 </div>
                                             </div>
                                             <div class="col-md-5">
                                                 <div>
                                                     <b>Quote Date:</b> '.date('Y-m-d', strtotime($response_array_value->Date->value)). PHP_EOL.'
                                                 </div>
                                                 <div>
                                                     <b>Required Date:</b> '.date('Y-m-d', strtotime($response_array_value->RequestedOn->value)). PHP_EOL.'
                                                 </div>
                                             </div>
                                         </div>
                                         <div class="product-info-wrapper">
                                             <table class="product-info-table" border="1">
                                                 <thead>
                                                     <tr>
                                                         <th scope="col">Product Number</th>
                                                         <th scope="col">Product Description</th>
                                                         <th scope="col">Order Qty</th>
                                                         <th scope="col">Ship Qty</th>
                                                     </tr>
                                                 </thead>
                                                 <tbody>';
                                                     foreach($response_array_value->Details as $detail_value){
                                                     $print_area.=' <tr>
                                                         <td>'.$detail_value->InventoryID->value.'</td>
                                                         <td>'.$detail_value->LineDescription->value.'</td>
                                                         <td>'.$detail_value->OrderQty->value.'</td>
                                                         <td>'.$detail_value->QtyOnShipments->value.'</td>';
                                                    
                                                     }
                                                     $print_area.=' </tbody>
                                             </table>
                                         </div>
                                    </div>
                                 </div>';
                                 $counter++;
    }                                    
               $quote_inquery_tbl.= '</tbody>
                        </table></div>';
        wp_send_json_success(array('quote_inquiry_data'=>$quote_inquery_tbl.$quote_inquery_modal.$print_area));
}