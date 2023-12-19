<?php
include("common-functions.php");
global $accu_headers;
 // Get the OptionTree object
 $optiontree = get_option('option_tree');
 $name = $optiontree['accu_name'];
 $password = $optiontree['accu_password'];
 $company = $optiontree['accu_company'];

$url = 'https://carefreeofcolorado.acumatica.com/entity/auth/login';
$type = 'POST';
$postFields = ["name" => $name,  "password" =>  $password, "company" =>  $company];
$headers = $accu_headers;


function makeLoginRequest($url, $type, $postFields, $headers)
{
	$curl = curl_init();
	$curlParams = array(
		CURLOPT_URL => $url,
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => $type,
		CURLOPT_HTTPHEADER => $headers,
	);
	
	$curlParams[ CURLOPT_POSTFIELDS ] =  json_encode($postFields);

	curl_setopt_array($curl, $curlParams);

	$response = curl_exec($curl);
	echo $httpstatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
	if($httpstatus != 200 || $httpstatus != 204)
	{
		logAcumaticaAPIError(json_decode($response, true)["message"]);
		return false;
	}
	curl_close($curl);
	
}


/*function makeRequest($url, $type, $postFields, $headers)
{
	$curl = curl_init();
	$curlParams = array(
		CURLOPT_URL => $url,
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => $type,
		CURLOPT_HTTPHEADER => $headers,
	);
	
	$curlParams[ CURLOPT_POSTFIELDS ] =  json_encode($postFields);

	curl_setopt_array($curl, $curlParams);

	$response = curl_exec($curl);
	echo $httpstatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
	if($httpstatus != 200 || $httpstatus != 204)
	{
		logAcumaticaAPIError(json_decode($response, true)["message"]);
		return false;
	}
	curl_close($curl);
	return $response;
}
*/


makeLoginRequest($url, $type, $postFields, $headers);