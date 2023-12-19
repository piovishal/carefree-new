<?php 
include("../wp-load.php");
/*
["user_id"=>1, "product_id"=>1, "variation_id"=>0, "quantity"=>1, "meta"=>[]]
*/
$params = $_POST;

if(!isset($_POST)){
	die("Direct access not premitted");
}

$expectedParams = [	"session_id"=>["required"=>true], 
					"user_id"=>["required"=>true], 
					"product_id"=>["required"=>true], 
					"variation_id"=>["required"=>false], 
					"quantity"=>["required"=>false], 
					"meta"=>["required"=>false]];
					
$error = [];

foreach($expectedParams as $key=>$param){
		if($param['required'] == true){
			if(!isset($params[$key]) || empty($params[$key])){
				$error[] = $key." is required";
			}
		}
}

if(array_diff(array_keys($_POST), array_keys($expectedParams))){
	$error[] = "parameters mismatch";
}

if(!empty($error)){
	echo json_encode(["status"=>0, "message"=>"Error occured", "errors"=>$error]);
	die;
}

$quantity = (isset($params['quantity']) && $params['quantity'] > 1) ? $params['quantity'] : 1;
$variationId = (isset($params['variation_id']) && $params['variation_id'] > 0) ? $params['variation_id'] : 0;
$itemMeta = (isset($params['meta']) && count($params['meta']) > 0) ? $params['meta'] : [];

global $wpdb;
$wpdb->insert('wp_cart_configure_one', array(
    'cart_session_id' => $params['session_id'],
	'cart_user_id' => $params['user_id'],
    'cart_product_id' => $params['product_id'],
	'cart_variation_id' => $variationId,
    'cart_quantity' => $quantity,
	'cart_meta' => json_encode($itemMeta)
));

$response = ["status"=>1,"message"=>"information sent to the website successfully", "cart_url"=>wc_get_cart_url()];

echo json_encode($response);
?>