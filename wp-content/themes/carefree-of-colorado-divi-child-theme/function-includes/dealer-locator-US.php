<?php
function getLatLong($address)
{   
	$key = "AIzaSyB-XRhR-FuDDrkTbY2Ze6R2TrMcxqHmj2Q";
	$url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.str_replace(' ', '+', $address).'&key='.$key;
	$geocurl = curl_init();
    curl_setopt($geocurl, CURLOPT_URL, $url);
    curl_setopt($geocurl, CURLOPT_HEADER,0); //Change this to a 1 to return headers
    curl_setopt($geocurl, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
    curl_setopt($geocurl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($geocurl, CURLOPT_RETURNTRANSFER, 1);

    $geocode = curl_exec($geocurl);
	$output= json_decode($geocode);
	if(is_array($output->results) && !empty($output)){
		return ["lat"=>number_format($output->results[0]->geometry->location->lat,6),
			"lng" =>number_format($output->results[0]->geometry->location->lng,6) ];
	}
	return [];
}

function distance($lat1, $lon1, $lat2, $lon2, $unit='') { 
  $theta = $lon1 - $lon2; 
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
  $dist = acos($dist); 
  $dist = rad2deg($dist); 
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344); 
  } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }
}


add_action('wp_ajax_US_dealer_locator', 'US_dealer_locator');
add_action('wp_ajax_nopriv_US_dealer_locator', 'US_dealer_locator');
function US_dealer_locator(){
    global $accu_headers;
    include("../acumatica/common-functions.php");

    // Get the OptionTree object
    $optiontree = get_option('option_tree');
    $name = $optiontree['accu_name'];
    $password = $optiontree['accu_password'];
    $company = $optiontree['accu_company'];
    // Get the value of the authorised_store field
    $authorisedStore = $optiontree['authorised_store'];
    // Get the value of the rvda_member field
    $rvdaMember = $optiontree['rvda_member'];
    // Get the value of the certified_technician field
    $certifiedTechnician = $optiontree['certified_technician'];
    // Get the value of the rv_center field
    $rvPersonnel = $optiontree['rv_center'];
    // Get the value of the go_rving_member field
    $gorvingMember = $optiontree['go_rving_member'];
    // Get the value of the mobile_store field
    $mobileStore = $optiontree['mobile_store'];
    $url = 'https://carefreeofcolorado.acumatica.com/entity/auth/login';
    $type = 'POST';
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
    $US_zipcode = $_GET['US_zipcode'];
    $US_city = $_GET['US_city'];
    $US_state = $_GET['US_state'];
    $US_aprox_range = $_GET['US_aprox_range'];

    $certificateArr['US_authorised_store'] = isset($_GET['US_authorised_store']) && !empty($_GET['US_authorised_store']) ? $_GET['US_authorised_store'] : "";
    $certificateArr['US_rdva_member'] = isset($_GET['US_rdva_member']) && !empty($_GET['US_rdva_member']) ? $_GET['US_rdva_member'] : "";
    $certificateArr['US_crtified_technician'] = isset($_GET['US_crtified_technician']) && !empty($_GET['US_crtified_technician']) ? $_GET['US_crtified_technician'] : "";
    $certificateArr['US_go_ring'] = isset($_GET['US_go_ring']) && !empty($_GET['US_go_ring']) ? $_GET['US_go_ring'] : "";
    $certificateArr['US_mobile_service'] = isset($_GET['US_mobile_service']) && !empty($_GET['US_mobile_service']) ? $_GET['US_mobile_service'] : "";
    $certificateArr['US_crtified_personnel'] = isset($_GET['US_crtified_personnel']) && !empty($_GET['US_crtified_personnel']) ? $_GET['US_crtified_personnel'] : "";

	$appliedCertFilters = [];
	foreach($certificateArr as $key=>$val){
		if(!empty($val)){
			$appliedCertFilters[] = $key;
		}
	}	
						
	$attributeQues = [ 'US_authorised_store' => 'Dealer Carefree Certified Store?',
						'US_rdva_member' => 'Dealer RVDA member?',
						'US_crtified_technician' => 'Dealer is Certified Technician?',
						'US_go_ring' => 'Dealer is Go RVing member?',
						'US_mobile_service' =>'Dealer provides mobile service?',
						'US_crtified_personnel'=>'Dealer has Certified Personnel?'
						];

    $filter = "MainContact/Address/Country eq 'US'";
    $getLatLngRslt = array();
    if(isset($US_zipcode) && !empty($US_zipcode)){
        $filter .= " and MainContact/Address/PostalCode eq '".$US_zipcode."'";
        $getLatLngRslt = getLatLong($US_zipcode);
    }else{
        if(isset($US_city) && !empty($US_city)){
            $filter .= " and MainContact/Address/City eq '".$US_city."'";
        }
        if(isset($US_state) && !empty($US_state)){
            $filter .= " and MainContact/Address/State eq '".$US_state."'";
        }
        if(isset($US_state) && !empty($US_state) && isset($US_city) && !empty($US_city) ){
            $getLatLngRslt = getLatLong($US_city." ".$US_state);
        }
    }
    if(isset($US_aprox_range) && !empty($US_aprox_range)){
        $US_aprox_range = str_replace(" miles", "", $US_aprox_range);
    }
    /* add cordinates regarding country USS */
    /* if(empty($getLatLngRslt)){
        $getLatLngRslt['lat'] = 40.505947207435526;
        $getLatLngRslt['lng'] = -105.25430733706509;
    } */

    $filter = ltrim($filter, 'and ');
    $filter = str_replace('+', '%20', urlencode($filter));
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
    
    $UN_html = '<div class="locations-box-section row">
                    <h2 class="serviceTitle">DEALERS - WARRANTY SERVICE</h2>
                    <div class="col-md-6 left-part-list">';
					
                    foreach($response_array as $response_array_value){
                        
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

						$keep = 0;
						foreach($appliedCertFilters as $val){
							$keep = 1;
							if($attributesArr[$attributeQues[$val]] == 1){
								$keep = 2;
								break;
							}
						}
						
						if($keep == 1){
							unset($filteredResults[$customerId]);
						}
                        
                    }
                    if(empty($filteredResults)){
                        $UN_html = "<h3>No Data Found</h3>";
                        wp_send_json_success(array('US_rslt_data'=>$UN_html,'rslt_count' => 0));
                    }
                    /* $searchResultCount = count($filteredResults); */
                    $loc = array();
                    $locUpdatedCordinates = array();
                    $locCounter = 0;
                    foreach ($filteredResults as $cusId => $filteredResult){
						 $address = $filteredResult->MainContact->CompanyName->value.', '.$filteredResult->MainContact->Address->City->value;
						$loc[] = getLatLong($address);
						 $distanceInMiles= distance($loc[$locCounter]['lat'], $loc[$locCounter]['lng'], $getLatLngRslt['lat'], $getLatLngRslt['lng']);
                        if(isset($US_aprox_range) && !empty($US_aprox_range)){
                            // echo $US_aprox_range;
                            if($distanceInMiles <= $US_aprox_range){
                                // echo "1";
                                $distanceInMilesUpdated = ' ~'. round($distanceInMiles).' MILES';
                                $locUpdatedCordinates[] = getLatLong($address);
                            }else{
                                $locCounter ++;
                                continue;
                            }
                        }else{
                           /*  $distanceInMilesUpdated = $distanceInMiles; */
                            $locUpdatedCordinates[] = getLatLong($address);   
                        }
                        $UN_html.= '<div class="cmpny_name">
                                    <span class="LocationIcon"><i class="ico-pin"></i></span>
                                    <span class="LocationTitle"><h2>'.$filteredResult->MainContact->CompanyName->value.' '.$distanceInMilesUpdated.'</h2></span>
                                </div>
                                <div class="US-address">
                                    <p class="FirstAdd">'.$filteredResult->MainContact->Address->AddressLine1->value.'</p>
                                    <p class="secAdd">'.$filteredResult->MainContact->Address->City->value.', '.$filteredResult->MainContact->Address->State->value.' '.$filteredResult->MainContact->Address->PostalCode->value.'</p>';
                                    if(!empty($filteredResult->MainContact->Phone1->value)){
                                        $UN_html .='<p><span>Phone:</span> '. $filteredResult->MainContact->Phone1->value.'</p>';
                                    }else{
                                        if(!empty($filteredResult->MainContact->Phone2->value)){
                                            $UN_html .= '<p><span>Phone:</span> '.$filteredResult->MainContact->Phone2->value.'</p>';
                                        }else{
                                            $UN_html .= '<p><span>Phone:</span> Not Available.</p>';
                                        }
                                    }
                                    if(!empty($filteredResult->MainContact->Fax->value)){
                                        $UN_html .='<p><span>Fax:</span> '. $filteredResult->MainContact->Fax->value.'</p>';
                                    }else{
                                        $UN_html .= '<p><span>Fax:</span> Not Available.</p>';
                                    }
                                    if(!empty($filteredResult->MainContact->WebSite->value)){
                                        $UN_html .='<p><span>Web:</span> '. $filteredResult->MainContact->WebSite->value.'</p>';
                                    }else{
                                        $UN_html .= '<p><span>Web:</span> Not Available.</p>';
                                    }
                                    $UN_html .= '
                                </div>
                                <div class="US-certification pb-5">';
                                    foreach($filteredResult->Attributes as $attribute){ 
                                        if($attribute->AttributeDescription->value =='Dealer has Certified Personnel?'){
                                            if($attribute->Value->value == "1"){
                                                $US_certified_image = $rvPersonnel;
                                                $UN_html .= '<span><img src="'.$US_certified_image.'" width="20px" height="20px">Dealer Certified</span>&nbsp;';
                                            }
                                        }
                                        if($attribute->AttributeDescription->value =='Dealer is Certified Technician?'){
                                            if($attribute->Value->value == "1"){
                                                $US_certified_image = $certifiedTechnician;
                                                $UN_html .= '<span><img src="'.$US_certified_image.'" width="20px" height="20px">Certified Technician</span>&nbsp;';
                                            }
                                        }
                                        if($attribute->AttributeDescription->value =='Dealer Carefree Certified Store?'){
                                            if($attribute->Value->value == "1"){
                                                $US_certified_image = $authorisedStore;
                                                $UN_html .= '<span><img src="'.$US_certified_image.'" width="20px" height="20px">Dealer Authorized Store</span>&nbsp;';
                                            }
                                        }
                                        if($attribute->AttributeDescription->value =='Dealer is Go RVing member?'){
                                            if($attribute->Value->value == "1"){
                                                $US_certified_image = $gorvingMember;
                                                $UN_html .= '<span><img src="'.$US_certified_image.'" width="20px" height="20px">Go RVing member</span>&nbsp;';
                                            }
                                        }
                                        if($attribute->AttributeDescription->value =='Dealer provides mobile service?'){
                                            if($attribute->Value->value == "1"){
                                                $US_certified_image = $mobileStore;
                                                $UN_html .= '<span><img src="'.$US_certified_image.'" width="20px" height="20px">mobile service</span>&nbsp;';
                                            }
                                        }
                                        if($attribute->AttributeDescription->value =='Dealer RVDA member?'){
                                            if($attribute->Value->value == "1"){
                                                $US_certified_image = $rvdaMember;
                                                $UN_html .= '<span><img src="'.$US_certified_image.'" width="20px" height="20px">RVDA member</span>&nbsp;';
                                            }
                                        }
                                    }
                                        $UN_html .='
                                </div><br>  
                                    ';
                                    $locCounter++;
                    }
                    $searchResultCount = count($locUpdatedCordinates);
                    $UN_html .='
                </div> 
                <div class="col-md-6">
                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-XRhR-FuDDrkTbY2Ze6R2TrMcxqHmj2Q&callback=initializeMap"></script>
                        <div id="map" style="height: 499px; width: 416px;"></div>
                    <script>
                        function initializeMap() {
                            var locations = '.json_encode($locUpdatedCordinates).';
                            var map = new google.maps.Map(document.getElementById("map"), {
                                center: { lat: 37.255221, lng:  -95.742083 },
                                zoom: 3 // Adjust the zoom level as needed
                            });

                            for (var i = 0; i < locations.length; i++) {
                                var marker = new google.maps.Marker({
                                    position: new google.maps.LatLng(locations[i].lat, locations[i].lng),
                                    map: map,
                                    icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png" // Red marker icon
                                });
                            }
                        }
                        window.initializeMap = initializeMap;
                    </script>
                </div>
            </div> ';
    wp_send_json_success(array('US_rslt_data'=>$UN_html,'rslt_count' => $searchResultCount));
}