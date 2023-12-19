<?php
include("../wp-load.php");
$requestURL = 'https://carefree.unbot.com/api/receive-config.php';
$sessionID = $_GET['session_id'];
$configID = $_GET['config_id'];

$curl = curl_init();

curl_setopt_array($curl, array(
	CURLOPT_URL => $requestURL,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => 'I_SESSION_ID='.$sessionID.'&config_id='.$configID,
	CURLOPT_HTTPHEADER => array(
		'Content-Type: application/x-www-form-urlencoded'
	),
));

$response = curl_exec($curl);

curl_close($curl);

$resArr = json_decode($response, true);
if(isset($resArr['data']['cartURL'])){
	wp_redirect($resArr['data']['cartURL']);
	exit;
}