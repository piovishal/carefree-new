<?php 
add_action('wp_ajax_fetch_part_number_details', 'fetch_part_number_details');
add_action('wp_ajax_nopriv_fetch_part_number_details', 'fetch_part_number_details');
function fetch_part_number_details(){
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
        CURLOPT_HTTPHEADER => $accu_headers ,
    );

    $curlParams[ CURLOPT_POSTFIELDS ] =  json_encode($postFields);
    curl_setopt_array($curl, $curlParams);
    $response = curl_exec($curl);
    $httpstatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if($httpstatus != 204){ 
        logAcumaticaAPIError(json_decode($response, true)["message"]);
        die('error in login API');
    }
    $pn_search = $_GET['PN_search'];
    $pn_search_by_desc = $_GET['PN_search_by_desc'];
    $filter = '';
    if(isset($pn_search) && !empty($pn_search)){
        $filter = "InventoryID eq '".$pn_search."'";
    }
    if(isset($pn_search_by_desc) && !empty($pn_search_by_desc)){
        $filter .= " and substringof('".$pn_search_by_desc."',Description)";
    }
    $filter = ltrim($filter, 'and ');
    $filter = str_replace('+', '%20', urlencode($filter));

    // Set the page number and page size
    $page_number = 1;
    $page_size = 10;
    $curl_url = 'https://carefreeofcolorado.acumatica.com/entity/Default/22.200.001/StockItem/?%24filter='.$filter.'&%24select=Description%2CInventoryID';// Add the pagination parameters to the URL

    curl_setopt_array($curl, array(
        CURLOPT_URL => $curl_url,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => $cookieJar,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => $accu_headers ,
    ));
    
    $response = curl_exec($curl);
    curl_close($curl);
    $response_array = json_decode($response);
    // prd(count($response_array));
    if(empty($response_array)){
        
        $quote_inquery_tbl = '
                              <div class="part-rslt-seperator"></div>
                              <p>No items were found.</p><br>
                              <p class="note-for-part-nbr">NOTE: If you were searching for a custom fabric replacement part number and didn\'t find a match, you may enter the part number in the box below and click the \'Add to Cart\' button.</p>';
        wp_send_json_error(array('PN_rslt_data'=>$quote_inquery_tbl));
    }
    // Get the total number of pages
    $total_pages = ceil(count($response_array) / $page_size);

    // Display the pagination links
    $quote_inquery_tbl ='<div class="part-rslt-seperator"></div><script>jQuery("#ordr-inq-table").DataTable();</script>
                <p>'.count($response_array).' products found, displaying results.</p>
                <div class="ordr-inq-wrapper">
                <table class="ordr-inq-table" id="ordr-inq-table" border="1">
                <thead>
                <tr>    
                    <th scope="col">Quantity</th>
                    <th scope="col">Part Number</th>
                    <th scope="col">Description</th>
                    <th scope="col">Favorites</th>
                </tr>
                </thead>
                <tbody>';
        foreach($response_array as $response_array_value ){
            $product_id = wc_get_product_id_by_sku($response_array_value->InventoryID->value);
            $quote_inquery_tbl .='<tr>
                                    <td>';
                                    if(!empty($product_id)){
                                        $quote_inquery_tbl .=' <input type="number" name="quantity[]" value="1" min="1">'.do_shortcode('[add_to_cart id="'. $product_id.'" quantity="1" show_price="FALSE"]');
                                    }else{
                                        $quote_inquery_tbl .='please <a href='.home_url('/about-us/about-us-contact/').'>contact carefree </a> for this product.';
                                    }
                                    $quote_inquery_tbl .= '</td>
                                    <td>'.$response_array_value->InventoryID->value.'</td>
                                    <td>'.$response_array_value->Description->value.'</td>
                                    <td>';
                                    if(!empty($product_id)){
                                        $quote_inquery_tbl .=' 
                                        <div class="fav_ad_btn"><button class="add-to-fav_list" data-product-id="' . $product_id . '">ADD</button></div>';
                                    }
                                    $quote_inquery_tbl .='</td></tr>';
        }
    $quote_inquery_tbl.= '</tbody>
    </table></div>';
    wp_send_json_success(array('PN_rslt_data'=>$quote_inquery_tbl));
}


add_action('wp_ajax_Pn_add_to_list', 'Pn_add_to_list');
add_action('wp_ajax_nopriv_Pn_add_to_list', 'Pn_add_to_list');

function Pn_add_to_list(){
    global $wpdb;
    $product_id = $_GET['PN_product_id'];
     /* Get the product object using the product ID */
     $product_name = get_the_title($product_id);
     $product_sku = get_post_meta($product_id, '_sku', true);
     $favorite_table_name = $wpdb->prefix.'favorite_product_list';    
    /* check if already product exist or not if exist then it will update else it is inserted */
    $existing_product = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM  $favorite_table_name WHERE product_id = %d", $product_id)
    );
    $favProduct = array(
        'product_id' => $product_id,
        'product_name' => $product_name,
        'part_number' => $product_sku, 
    );
    if ($existing_product) {
        /* If the product exists, update the row */
        wp_send_json_success(array('PN_rslt_data'=>"product already added."));
    } else {
        /* If the product does not exist, insert a new row */
        $wpdb->insert(
            $favorite_table_name,
            $favProduct,
        );
        wp_send_json_success(array('PN_rslt_data'=>"<b>product added.</b>"));
    }
     
}