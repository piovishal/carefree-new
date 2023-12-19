<?php 
include("../wp-load.php");

if(empty($_POST)){
	die("Invalid session");
}

$requestResponse = $_POST;
$sessionID = $requestResponse['I_SESSION_ID'];

global $wpdb;
$configID = $requestResponse['config_id'];

$postFields = ["grant_type" => "client_credentia",  "client_id" =>  "restApiClient", "client_secret" =>"489451b9-a5b7-4483-9e4b-0af387a969e0"];

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://carefreeofcolorado-dev.configurators.com/auth/realms/dev/protocol/openid-connect/token',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'grant_type=client_credentials&client_id=restApiClient&client_secret=489451b9-a5b7-4483-9e4b-0af387a969e0',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded'
  ),
));

$response = curl_exec($curl);
$httpstatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
if($httpstatus != 200){ 
    $response = json_decode($response, true);
    echo "error type is ".$response['error']."<br>";
    echo "Description :- ".$response['error_description']."<br>";
    die('error in login API');
}
$loginToken = json_decode($response)->access_token;

$auth = [
    "Authorization"=> "Bearer ".$loginToken
];

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://carefreeofcolorado-dev.configurators.com/spr/c1/rest/api/configurations/'.$configID,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$loginToken
      ),
  ));
  $response = curl_exec($curl);
  curl_close($curl);
$productID = customProductsAdd(json_decode($response,true));
if($productID){        
		wp_send_json_success(array('message'=>'sucessfully product created and added in cart','cartURL'=>wc_get_cart_url().'?sid='.$sessionID.'&cid='.$configID.'&pid='.$productID));
}
		
function customProductsAdd(array $productArray){
    // prd(wp_get_current_user());
    if(!empty($productArray)){
        $product = new WC_Product_Simple();
        // prd($productArray['name']);
        $product->set_name( $productArray['configurations'][0]['name'] ); // product title
        if(isset($productArray['configurations'][0]['productSlug']) && !empty($productArray['configurations'][0]['productSlug'])){

            $product->set_slug($productArray['productSlug']);
        }
    
        $product->set_regular_price(  $productArray['configurations'][0]['grossPrice'] ); // in current shop currency
        if(isset($productArray['configurations'][0]['productDescription']) && !empty($productArray['configurations'][0]['productDescription'])){
            $product->set_short_description($productArray['productDescription']);
        }
                    $excludeAttributesArr= array(
                        'product',
                        'Contrasting Weatherguard Labor Adder Hrs/Pc',
                        'Contrasting Weatherguard Labor Adder Pcs/Hr',
                        'Orientation Autometrix Value',
                        'Canopy Cut Extension Value',
                        'LED Holder Smartpart'
                    );

        $html = '<table class="table" style="width:100%;">
                    <thead style="text-align:left;"><tr><th>Attribute</th><th>Value</th></tr></thead>';
                    foreach ($productArray['configurations'][0]['inputs'] as $key => $inputValues) {
                        if(!in_array($inputValues['label'],$excludeAttributesArr)){
                            $html .= '<tr>';
                            $html .= '<td>' . $inputValues['label'] . '</td>';
                            if (!empty($inputValues['values'])) {
                              $html .= '<td>' . $inputValues['values'][0]['label'] . '</td>';
                            } else {
                              $html .= '<td></td>';
                            }
                            $html .= '</tr>';
                        }
                      }
        $html .= '</table>';              
        $product->set_description( $html );
    
        $product->set_category_ids([221]);
        $product->save();
        return $productID = $product->get_id();
        
        
        
    }else{
        wp_send_json_success(array('message'=>'product is not found'));

    }
}

/* 
$post = json_encode($_REQUEST);
file_put_contents(
    'receive-config',
    PHP_EOL . '>>> ( Date:' . date("Y:m:d H:i:s") . ' )' . $post,
    FILE_APPEND
); */
?>