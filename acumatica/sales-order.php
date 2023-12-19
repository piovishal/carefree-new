<?php

// include("../wp-load.php");

include("common-functions.php");
$url = 'https://carefreeofcolorado.acumatica.com/entity/auth/login';
$type = 'POST';
$postFields = ["name" => "website01",  "password" => "Testing4TheWeb!", "company" =>  "OLD - Carefree - Test"];
$headers = ['Content-Type: application/json'];
$cookieJar = tempnam('/tmp','cookie');

$curl1 = curl_init();
$curlParams = array(
	CURLOPT_URL => $url,
	CURLOPT_SSL_VERIFYHOST => 0,
	CURLOPT_SSL_VERIFYPEER => 0,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_COOKIESESSION => 1,
	CURLOPT_COOKIEJAR => $cookieJar,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => $type,
	CURLOPT_HTTPHEADER => $headers,
);

$curlParams[ CURLOPT_POSTFIELDS ] =  json_encode($postFields);

curl_setopt_array($curl1, $curlParams);

$response = curl_exec($curl1);
// prd($response);
$httpstatus = curl_getinfo($curl1, CURLINFO_HTTP_CODE);
// prd($httpstatus);

if($httpstatus != 204)
{
	logAcumaticaAPIError(json_decode($response, true)["message"]);
	die('error in login API');
}






?>