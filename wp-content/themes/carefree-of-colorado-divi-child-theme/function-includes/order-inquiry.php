<?php 

add_action('wp_ajax_fetch_sale_order_details', 'fetch_sale_order_details');
add_action('wp_ajax_nopriv_fetch_sale_order_details', 'fetch_sale_order_details');
function fetch_sale_order_details(){
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
    if($httpstatus != 204)
    { 
        logAcumaticaAPIError(json_decode($response, true)["message"]);
        die('error in login API');
    }
    $filter = '';
    $current_user = wp_get_current_user();
    $current_username = $current_user->display_name;
    $current_user_cust_no = get_user_meta(get_current_user_id(), "customer_number", true);
    $to_date = $_GET['to_date']; 
    $from_date = $_GET['from_date']; 
    $all_data = $_GET['all_data']; 
    if($_GET['order_status']=='Any Status'){
        $order_status = "";   
    }else{
        $order_status = "and Status eq '".$_GET['order_status']."'";
    }
    if($_GET['filter_type']=='Order Number'){
        $filter_type = 'OrderNbr eq '; 
    }elseif($_GET['filter_type'] == 'Invoice Number'){
        $filter_type = 'InvoiceNbr eq ';  
    }else{
        $filter_type = ' eq ';
    }

    $filter = "OrderType eq 'SO'";
    if($current_user_cust_no){
        $filter .= " and CustomerID eq '".$current_user_cust_no ."'";
    }
    if(!empty($from_date)){
        $filter .= " and Date gt datetimeoffset'".$from_date."'";
    }
    if(!empty($from_date)){
        $filter .= " and Date lt datetimeoffset'".$to_date."'";
    }
    if(isset($_GET['filter_value']) && !empty($_GET['filter_value'])){
        $filter .= " and ".$filter_type."'".$_GET['filter_value']."'";
    }
    $filter .= $order_status;
    $filter = ltrim($filter, 'and ');
    $filter = str_replace('+', '%20', urlencode($filter));
    $curl_url = 'https://carefreeofcolorado.acumatica.com/entity/Default/22.200.001/SalesOrder?%24expand=Details%2CShipments%2CShipToAddress%2CBillToAddress&%24filter='.$filter;

    curl_setopt_array($curl, array(
    CURLOPT_URL => $curl_url,
    CURLOPT_SSL_VERIFYHOST => 0,
	CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEJAR => $cookieJar,
    CURLOPT_ENCODING => '',
    CURLOPT_TIMEOUT => 0,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => $accu_headers,
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    $response_array = json_decode($response);
    unlink($cookieJar) or die('Can not unlink $cookieJar');
    if(empty($response_array)){
        $order_inquery_tbl = '
                              <p>No order found for the search criteria..</p>';
        wp_send_json_success(array('order_inquiry_data'=>$order_inquery_tbl,"download_link" =>"No"));
    }
    $order_inquery_tbl ='
                        <p>'.count($response_array).' order(s) found. Click on an order to view detial.</p>
                        <div class="ordr-inq-wrapper">
                        <table class="ordr-inq-table" border="1">
                        <thead>
                        <tr>    
                            <th scope="col">Sno.</th>
                            <th scope="col">Order#</th>
                            <th scope="col">Address</th>
                            <th scope="col">Ship-to PO</th>
                            <th scope="col">Bill-to PO</th>
                            <th scope="col">Order Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $counter = 1;
    foreach($response_array as $response_array_value){
        $order_inquery_tbl .='<tr>
                                <td>'.$counter.'</td> 
                                <td>'.$response_array_value->OrderNbr->value.'</td>
                                <td>'.
                                    $response_array_value->ShipToAddress->AddressLine1->value.',<br> ';
                                    if(!empty($response_array_value->ShipToAddress->AddressLine2->value)){
                                        $order_inquery_tbl .= $response_array_value->ShipToAddress->AddressLine2->value.', ';
                                    }
                                    $order_inquery_tbl.=$response_array_value->ShipToAddress->City->value.', '.$response_array_value->ShipToAddress->State->value.', '.$response_array_value->ShipToAddress->Country->value.', '.$response_array_value->ShipToAddress->PostalCode->value.'
                                </td>
                                <td>'.$response_array_value->ExternalRef->value.'</td>
                                <td>'.$response_array_value->CustomerOrder->value.'</td>
                                <td>'.date('jS M, Y', strtotime($response_array_value->Date->value)). PHP_EOL.'</td>
                                <td>'.$response_array_value->Status->value.'</td>
                                <td><button class="btn" data-toggle="modal" data-target="#'.$response_array_value->OrderNbr->value.'" style="background-color: #fe710b;color: white;">View</button></td>
                             </tr>';
                             $order_inquery_modal .= '
                             <!-- Modal -->
                             <div class="modal fade OrderEnquiryModal" id="'.$response_array_value->OrderNbr->value.'" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                             <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                 <div class="modal-content">
                                     <div class="modal-header">
                                         <h5 class="modal-title" id="exampleModalLabel">Order Detail</h5>
                                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                             <span aria-hidden="true">&times;</span>
                                         </button>
                                     </div>
                                     <div class="modal-body">
                                         <div class="row align-items-center mb-3">
                                             <div class="col-md-5 mb-2 mb-md-0">
                                                 <div>
                                                     <b>Order Number:</b> '.$response_array_value->OrderNbr->value.'
                                                 </div>
                                                 <div>
                                                     <b>Ship-to PO:</b> '.$response_array_value->ExternalRef->value.'
                                                 </div>
                                                 <div>
                                                     <b>Bill-to PO:</b> '.$response_array_value->CustomerOrder->value.'
                                                 </div>
                                             </div>
                                             <div class="col-md-5">
                                                 <div>
                                                     <b>Order Date:</b> '.date('Y-m-d', strtotime($response_array_value->Date->value)). PHP_EOL.'
                                                 </div>
                                                 <div>
                                                     <b>Required Date:</b> '.date('Y-m-d', strtotime($response_array_value->RequestedOn->value)). PHP_EOL.'
                                                 </div>
                                                 <div>
                                                     <b>Ship Date:</b> '.date('Y-m-d', strtotime($response_array_value->RequestedOn->value)). PHP_EOL.'
                                                 </div>
                                                 <div>
                                                     <b>Order Status:</b> '.$response_array_value->Status->value.'
                                                 </div>
                                             </div>
                                         </div>
                                         <div class="row mb-3">
                                             <div class="col-md-5 mb-2 mb-md-0">
                                                 <b>Ship-to:</b> <br>'.$response_array_value->ShipToAddress->AddressLine1->value.',<br> ';
                                                 if(!empty($response_array_value->ShipToAddress->AddressLine2->value)){
                                                    $order_inquery_modal .= $response_array_value->ShipToAddress->AddressLine2->value  .',';
                                                 }
                                                 $order_inquery_modal.=$response_array_value->ShipToAddress->City->value.', '.$response_array_value->ShipToAddress->State->value.', '.$response_array_value->ShipToAddress->Country->value.', '.$response_array_value->ShipToAddress->PostalCode->value.' 
                                             </div>
                                             <div class="col-md-5">
                                                 <b>Sold-to:</b> <br>'.$response_array_value->BillToAddress->AddressLine1->value.',<br> ';
                                                 if(!empty($response_array_value->BillToAddress->AddressLine2->value)){
                                                    $order_inquery_modal .= $response_array_value->BillToAddress->AddressLine2->value  .', ';
                                                 }
                                                 $order_inquery_modal.=$response_array_value->BillToAddress->City->value.', '.$response_array_value->BillToAddress->State->value.', '.$response_array_value->BillToAddress->Country->value.', '.$response_array_value->BillToAddress->PostalCode->value.' 
                                             </div>
                                         </div><br>';
                                         if(!empty($response_array_value->Details)){
                                            $dteail_count = 1;
                                            $order_inquery_modal.='<div class="product-info-wrapper">
                                            <h2><b>Product Details</b></h2>
                                             <table class="product-info-table mb-0" border="1">
                                                 <thead>
                                                     <tr>
                                                         <th scope="col">Sno</th>
                                                         <th scope="col">Product Number</th>
                                                         <th scope="col">Product Description</th>
                                                         <th scope="col">Order Qty</th>
                                                         <th scope="col">Ship Qty</th>
                                                         <th scope="col">Expected Ship Date</th>
                                                     </tr>
                                                 </thead>
                                                 <tbody>';
                                                     foreach($response_array_value->Details as $detail_value){
                                                     $order_inquery_modal.=' <tr>
                                                         <td>'.$dteail_count.'</td>
                                                         <td>'.$detail_value->InventoryID->value.'</td>
                                                         <td>'.$detail_value->LineDescription->value.'</td>
                                                         <td>'.$detail_value->OrderQty->value.'</td>
                                                         <td>'.$detail_value->QtyOnShipments->value.'</td>
                                                         <td>'.date('Y-m-d', strtotime($detail_value->ShipOn->value)).
                                                            PHP_EOL.'</td>
                                                         </tr>';
                                                         $dteail_count++;
                                                     }
                                                     $order_inquery_modal.=' </tbody>
                                             </table>
                                         </div><br><br>';

                                         }
                                        
                                         if(!empty($response_array_value->Shipments)){
                                            $ship_count = 1;
                                            $order_inquery_modal.= '
                                            <div class="product-info-wrapper">
                                            <h2><b>Shipment Details</b></h2>
                                            <table class="product-info-table" border="1">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Sno</th>
                                                        <th scope="col">Shipment Date</th>
                                                        <th scope="col">Shipped Quantity</th>
                                                        <th scope="col">Shipped Weight</th>
                                                    </tr>
                                                </thead>
                                                <tbody>';
                                                    foreach($response_array_value->Shipments as $shipment_value){
                                                   $order_inquery_modal.=' <tr>
                                                            <td>'.$ship_count.'</td>
                                                            <td>'.date('Y-m-d', strtotime($shipment_value->ShipmentDate->value)). PHP_EOL.'</td>
                                                            <td>'.$shipment_value->ShippedQty->value.'</td>
                                                            <td>'.$shipment_value->ShippedWeight->value.'</td>
                                                        </tr>';
                                                        $ship_count++;
                                                   
                                                    }
                                                    $order_inquery_modal.=' </tbody>
                                            </table>
                                        </div>';
                                         }



                                     $order_inquery_modal .=' 
                                     </div>
                                     <div class="modal-footer justify-content-between">
                                         <button type="button" class="btn btn-secondary btn-orange" onclick="printDiv(\'print-'.$response_array_value->OrderNbr->value.'\')" >Print</button>
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
                                                     <b>Order Number:</b> '.$response_array_value->OrderNbr->value.'
                                                 </div>
                                                 <div>
                                                     <b>Ship-to PO:</b> '.$response_array_value->ExternalRef->value.'
                                                 </div>
                                                 <div>
                                                     <b>Bill-to PO:</b> '.$response_array_value->CustomerOrder->value.'
                                                 </div>
                                             </div>
                                             <div class="col-md-5">
                                                 <div>
                                                     <b>Order Date:</b> '.date('Y-m-d', strtotime($response_array_value->Date->value)). PHP_EOL.'
                                                 </div>
                                                 <div>
                                                     <b>Required Date:</b> '.date('Y-m-d', strtotime($response_array_value->RequestedOn->value)). PHP_EOL.'
                                                 </div>
                                                 <div>
                                                     <b>Ship Date:</b> '.date('Y-m-d', strtotime($response_array_value->RequestedOn->value)). PHP_EOL.'
                                                 </div>
                                                 <div>
                                                     <b>Order Status:</b> '.$response_array_value->Status->value.'
                                                 </div>
                                             </div>
                                         </div>
                                         <div class="row mb-3">
                                             <div class="col-md-5 mb-2 mb-md-0">
                                                 <b>Ship-to:</b> <br>'.$response_array_value->ShipToAddress->AddressLine1->value.',<br> ';
                                                 if(!empty($response_array_value->ShipToAddress->AddressLine2->value)){
                                                    $print_area.= $response_array_value->ShipToAddress->AddressLine2->value  .',';
                                                 }
                                                 $print_area.=$response_array_value->ShipToAddress->City->value.', '.$response_array_value->ShipToAddress->State->value.', '.$response_array_value->ShipToAddress->Country->value.', '.$response_array_value->ShipToAddress->PostalCode->value.' 
                                             </div>
                                             <div class="col-md-5">
                                                 <b>Sold-to:</b> <br>'.$response_array_value->BillToAddress->AddressLine1->value.',<br> ';
                                                 if(!empty($response_array_value->BillToAddress->AddressLine2->value)){
                                                    $print_area.= $response_array_value->BillToAddress->AddressLine2->value  .', ';
                                                 }
                                                 $print_area.=$response_array_value->BillToAddress->City->value.', '.$response_array_value->BillToAddress->State->value.', '.$response_array_value->BillToAddress->Country->value.', '.$response_array_value->BillToAddress->PostalCode->value.' 
                                             </div>
                                         </div><br>';
                                         if(!empty($response_array_value->Details)){
                                            $dteail_count = 1;
                                            $print_area.='<div class="product-info-wrapper">
                                            <h2><b>Product Details</b></h2>
                                             <table class="product-info-table mb-0" border="1">
                                                 <thead>
                                                     <tr>
                                                         <th scope="col">Sno</th>
                                                         <th scope="col">Product Number</th>
                                                         <th scope="col">Product Description</th>
                                                         <th scope="col">Order Qty</th>
                                                         <th scope="col">Ship Qty</th>
                                                         <th scope="col">Expected Ship Date</th>
                                                     </tr>
                                                 </thead>
                                                 <tbody>';
                                                     foreach($response_array_value->Details as $detail_value){
                                                     $print_area.=' <tr>
                                                         <td>'.$dteail_count.'</td>
                                                         <td>'.$detail_value->InventoryID->value.'</td>
                                                         <td>'.$detail_value->LineDescription->value.'</td>
                                                         <td>'.$detail_value->OrderQty->value.'</td>
                                                         <td>'.$detail_value->QtyOnShipments->value.'</td>
                                                         <td>'.date('Y-m-d', strtotime($detail_value->ShipOn->value)).
                                                            PHP_EOL.'</td>
                                                         </tr>';
                                                         $dteail_count++;
                                                     }
                                                     $print_area.=' </tbody>
                                             </table>
                                         </div><br><br>';

                                         }
                                         if(!empty($response_array_value->Shipments)){
                                            $ship_count = 1;
                                            $print_area.='
                                            <div class="product-info-wrapper">
                                            <h2><b>Shipment Details</b></h2>
                                            <table class="product-info-table" border="1">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Sno</th>
                                                        <th scope="col">Shipment Date</th>
                                                        <th scope="col">Shipped Quantity</th>
                                                        <th scope="col">Shipped Weight</th>
                                                    </tr>
                                                </thead>
                                                <tbody>';
                                                    foreach($response_array_value->Shipments as $shipment_value){
                                                   $print_area.=' <tr>
                                                            <td>'.$ship_count.'</td>
                                                            <td>'.date('Y-m-d', strtotime($shipment_value->ShipmentDate->value)). PHP_EOL.'</td>
                                                            <td>'.$shipment_value->ShippedQty->value.'</td>
                                                            <td>'.$shipment_value->ShippedWeight->value.'</td>
                                                        </tr>';
                                                        $ship_count++;
                                                   
                                                    }
                                                    $print_area.=' </tbody>
                                            </table>
                                        </div>';
                                         }
                                         $print_area.='</div>
                                   </div>';
                                   $counter++;
    }                                          
               $order_inquery_tbl.= '</tbody>
                        </table></div>';
        wp_send_json_success(array('order_inquiry_data'=>$order_inquery_tbl.$order_inquery_modal.$print_area,"download_link" =>"Yes"));
                    

}