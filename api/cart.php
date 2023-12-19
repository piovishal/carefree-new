<?php
include("../wp-load.php");
if(!isset($_GET['crt_id'])){
	die("cart id is required");
}

$cartId = $_GET['crt_id'];

$result = $wpdb->get_results( "SELECT * FROM wp_cart_configure_one where cart_session_id = '".$cartId."'" );
if ( $wpdb->last_error ) {
  die('wpdb error: ' . $wpdb->last_error);
}


if(count($result) > 0){
	global $woocommerce;
	
	echo '<pre>';
	print_r($result);
	echo '</pre>';
	
	foreach($result as $cartRequest){
		$woocommerce->cart->add_to_cart($cartRequest->cart_product_id, $cartRequest->cart_quantity, $cartRequest->cart_variation_id, [], json_decode($cartRequest->cart_meta, true));
	}
}

//header("location : ".wc_get_cart_url());
?>