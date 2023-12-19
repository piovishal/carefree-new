<?php
include("../wp-load.php");
if(isset($_GET['manual'])){
	$current_user = wp_get_current_user();
	if (!user_can( $current_user, 'administrator' )) {
		die("Only Website owner can sync this data");
	}
}

$args = array(
	'role' => 'um_dealer',
    'meta_query' => array(
        array(
            'key' => 'customer_number',
            'value' => 0,
            'compare' => '>'
        ),
        array(
            'key' => 'account_status',
            'value' => 'approved',
            'compare' => '='
        )
    )
);

$users = get_users($args);
if(empty($users)){
	logAcumaticaAPIError("No users found to update");
	die("No users found to update");
}

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
$headers = ['Content-Type: application/json'];
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

foreach($users as $user){
	$ch = $curl;
	curl_setopt_array($ch, array(
		CURLOPT_URL => 'https://carefreeofcolorado.acumatica.com/entity/Default/22.200.001/Contact?%24expand=Activities%2CAddress%2CAttributes%2CCampaigns%2CCases%2CNotifications%2COpportunities&%24filter=BusinessAccount%20eq%20%27'.get_user_meta($user->ID, 'customer_number', true).'%27%20and%20JobTitle%20eq%20%27Website%27',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_COOKIESESSION => 1,
		CURLOPT_COOKIEJAR => $cookieJar,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
			'Accept: application/json',
			'Content-Type: application/json'
		),
	));

	$response = curl_exec($ch);
	$httpstatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$responseArr = json_decode($response, true);
	if($httpstatus != 200)
	{
		logAcumaticaAPIError($responseArr["message"]);
		die($responseArr["message"]);
	}
	
	$contacts = $responseArr[0];
	$address = $contacts['Address'];
	
	$acuUser = [
				"first_name" => $contacts['FirstName']['value'],
				"last_name" => $contacts['LastName']['value'],
				"display_name" => $contacts['DisplayName']['value'],
				"address_1" => isset($address['AddressLine1']['value']) ? $address['AddressLine1']['value'] : '',
				"address_2" => isset($address['AddressLine2']['value']) ? $address['AddressLine2']['value'] : '',
				"city" => isset($address['City']['value']) ? $address['City']['value'] : '',
				"state" => isset($address['State']['value']) ? $address['State']['value'] : '',
				"postal" => isset($address['PostalCode']['value']) ? $address['State']['value'] : '',
				"phone1" => isset($contacts['Phone1']['value']) ? $contacts['Phone1']['value'] : '',
				"country" => isset($address['Country']['value']) ? $address['Country']['value'] : '',
				"company_name" => ($contacts['CompanyName']['value']) ? $contacts['CompanyName']['value'] : '',
				"phone_number" => isset($contacts['Phone2']['value']) ? $contacts['Phone2']['value'] : '',
				"website" => isset($contacts['WebSite']['value']) ? $contacts['WebSite']['value'] : ''
			];	
	
	$wpUser = [
				"first_name" => $user->first_name,
				"last_name" => $user->last_name,
				"display_name" => $user->display_name,
				"address_1" => get_user_meta($user->ID, 'address_1', true),
				"address_2" => get_user_meta($user->ID, 'address_2', true),
				"city" => get_user_meta($user->ID, 'city', true),
				"state" => get_user_meta($user->ID, 'state', true),
				"postal" => get_user_meta($user->ID, 'postal', true),
				"phone" => get_user_meta($user->ID, 'phone', true),
				"country" => get_user_meta($user->ID, 'country', true),
				"company_name" => get_user_meta($user->ID, 'company_name', true),
				"phone_number" => get_user_meta($user->ID, 'phone_number', true),
				"website" => get_user_meta($user->ID, 'website', true)
			];
	
	$diffUser = array_diff_assoc($acuUser, $wpUser);
	
	if(empty($diffUser)){
		continue;
	}
	
	$user_detial['ID'] = $user->ID;
	$user_detial['first_name'] = $acuUser['first_name'];
	$user_detial['last_name'] = $acuUser['last_name'];
	$user_detial['display_name'] = $acuUser['display_name'];
	
	unset($acuUser['first_name'], $acuUser['last_name'], $acuUser['display_name']);
	$user_detial['meta_input'] = $acuUser;
	$userId = wp_update_user($user_detial);
	if ( is_wp_error( $userId ) ) {
		logAcumaticaAPIError("Error in updating user with id ".$user->Id);
	}
	
}
curl_close($ch);
curl_close($curl);
unlink($cookieJar) or die('Can not unlink $cookieJar');
if(isset($_GET['manual']) && $_GET['manual']){
	handleRedirection();
}

function validate_user_details($user_detail) {
	$errors = array();

	if ( (int)$user_detail['ID'] < 1) {
		$errors[] = 'Invalid user ID.';
	}

	$user_detail['first_name'] = sanitize_text_field($user_detail['first_name']);
	$user_detail['last_name'] = sanitize_text_field($user_detail['last_name']);
	if (empty($user_detail['first_name']) || empty($user_detail['last_name'])) {
		$errors[] = 'First name and last name are required.';
	}

	$user_detail['display_name'] = sanitize_text_field($user_detail['display_name']);
	if (empty($user_detail['display_name'])) {
		$errors[] = 'Display name is required.';
	}

	$user_detail['meta_input']['company_name'] = sanitize_text_field($user_detail['meta_input']['company_name']);
	if(empty($user_detail['meta_input']['company_name'])){
		$errors[] = 'Company Name is Required.';
	}
	
	$user_detail['meta_input']['website'] = esc_url_raw($user_detail['meta_input']['website']);

	// Validate website
	if (empty($user_detail['meta_input']['website'])) {
		$errors[] = 'website URL is required';
	}

	if (!empty($errors)) {
		return $errors;
	}

	return true;
}



/*
[
		"address_1" => $address['AddressLine1']['value'],
		"address_2" => $address['AddressLine2']['value'],
		"city" => $address['City']['value'],
		"state" => $address['State']['value'],
		"postal" => $address['PostalCode']['value'],
		"phone" => isset($customer['MainContact']['Phone1']['value']) ? $customer['MainContact']['Phone1']['value'] : '',
		"country" => $address['Country']['value'],
		"company_name" => $customer['MainContact']['CompanyName']['value'],
		"phone_number" => isset($customer['MainContact']['Phone1']['value']) ? $customer['MainContact']['Phone1']['value'] : '',
		"website" => isset($customer['MainContact']['WebSite']['value']) ? $customer['MainContact']['WebSite']['value'] : '',
		'billing_first_name' => isset($customer['MainContact']['FirstName']['value']) ? $customer['MainContact']['FirstName']['value'] : '',
		'billing_last_name' => isset($customer['MainContact']['LastName']['value']) ? $customer['MainContact']['LastName']['value'] : '',
		"billing_address_1" => $address['AddressLine1']['value'],
		"billing_address_2" => $address['AddressLine2']['value'],
		"billing_city" => $address['City']['value'],
		"billing_state" => $address['State']['value'],
		"billing_postal" => $address['PostalCode']['value'],
		"billing_phone" => isset($customer['MainContact']['Phone1']['value']) ? $customer['MainContact']['Phone1']['value'] : '',
		"billing_country" => $address['Country']['value'],
		'shipping_first_name' => isset($customer['MainContact']['FirstName']['value']) ? $customer['MainContact']['FirstName']['value'] : '',
		'shipping_last_name' => isset($customer['MainContact']['LastName']['value']) ? $customer['MainContact']['LastName']['value'] : '',
		"shipping_address_1" => $address['AddressLine1']['value'],
		"shipping_address_2" => $address['AddressLine2']['value'],
		"shipping_city" => $address['City']['value'],
		"shipping_state" => $address['State']['value'],
		"shipping_postal" => $address['PostalCode']['value'],
		"shipping_phone" => isset($customer['MainContact']['Phone1']['value']) ? $customer['MainContact']['Phone1']['value'] : '',
		"shipping_country" => $address['Country']['value']
	];
*/