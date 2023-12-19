<?php
/*
dealer locator Canada page
*/
add_action('wp_ajax_CA_dealer_locator', 'CA_dealer_locator');
add_action('wp_ajax_nopriv_CA_dealer_locator', 'CA_dealer_locator');
function CA_dealer_locator()    
{
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
	
    $caState = $_GET['CA_state'];
    
	$filter = "MainContact/Address/Country eq 'CA'";

    if(isset( $caState) && !empty( $caState)){
		$filter .= " and MainContact/Address/State eq '". $caState."'";
	}

    $filter = ltrim($filter, 'and ');
   
    $filter = str_replace('+', '%20', urlencode($filter));
    /* prd($filter); die; */
    $curl_url = 'https://carefreeofcolorado.acumatica.com/entity/Default/22.200.001/Customer?%24expand=MainContact%2CMainContact%2FAddress%2CAttributes&%24filter='.$filter;

   
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
        CURLOPT_HTTPHEADER => $accu_headers,
    ));
    
    $response = curl_exec($curl);
    curl_close($curl);
    $response_array = json_decode($response);
    $CA_html = '<div class="row">
                    <h2>Dealers - Warranty Service</h2>
                    <div class="col-md-12 left-part-list">';
                    foreach($response_array as $response_array_value){
                        if(empty($response_array_value->Attributes)){
                            continue;
                        }
                        $customerId = $response_array_value->CustomerID->value;
						
						$filteredResults[$customerId] = $response_array_value;
						$attributesArr = [];
                        foreach($response_array_value->Attributes as $attribute){ 
                               $attributesArr[$attribute->AttributeDescription->value] = $attribute->Value->value;         
                        }    
                        if($attributesArr['Is Dealer Searchable on Website'] != 1){
							unset($filteredResults[$customerId]);
                            continue;
                        }
                    }
                    if(empty($filteredResults)){
                        $CA_html = "<h3>No Data Found</h3>";
                        wp_send_json_success(array('CA_rslt_data'=>$CA_html));
                    }
                        
                    foreach ($filteredResults as $filteredResult){
                        $CA_html.= '<div class="cmpny_name">
                                    <span></span>
                                    <span><h6><b>'.$filteredResult->MainContact->CompanyName->value.'</b></h6></span>
                                </div>
                                <div class="US-address">
                                    <p>'.$filteredResult->MainContact->Address->AddressLine1->value.'</p>
                                    <p>'.$filteredResult->MainContact->Address->City->value.', '.$filteredResult->MainContact->Address->State->value.' '.$filteredResult->MainContact->Address->PostalCode->value.'</p>';
                                    
                                        if(!empty($filteredResult->MainContact->Phone1->value)){
                                            $CA_html .='<p>Phone: '. $filteredResult->MainContact->Phone1->value.'</p>';
                                        }else{
                                            if(!empty($filteredResult->MainContact->Phone2->value)){
                                                $CA_html .= '<p>Phone: '.$filteredResult->MainContact->Phone2->value.'</p>';
                                            }else{
                                                $CA_html .= '<p>Phone: Not available. </p>';

                                            }
                                        }
                                        if(!empty($filteredResult->MainContact->Fax->value)){
                                            $CA_html .='<p>Fax: '. $filteredResult->MainContact->Fax->value.'</p>';
                                        }else{
                                            $CA_html .='<p>Fax: Not Available.</p>';

                                        }
                                        if(!empty($filteredResult->MainContact->WebSite->value)){
                                            $CA_html .='<p>Web: '. $filteredResult->MainContact->WebSite->value.'</p>';
                                        }else{
                                            $CA_html .='<p>Web: Not Available.</p>';

                                        }
                                        $CA_html .= '
                                </div>';
                                
                    }
                    $CA_html .='
                </div> 
            </div> ';
    wp_send_json_success(array('CA_rslt_data'=>$CA_html));
}