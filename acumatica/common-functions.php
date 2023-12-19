<?php
function logAcumaticaAPIError($errorMessage)
{
	$error = "\n"."\n".'-------------------------------------------'."\n";
	$error .= 'Date & Time : ' . date('d-M-Y h:i a')."\n";
	$error .= 'Curl error: ' . $errorMessage."\n";
	$error .= '--------------------------------------------';
	error_log($errorMessage, 3, 'acumatica_error.log');
	
	if(isset($_GET['manual'])){
		handleRedirection(true);
	}
}

function handleRedirection($returnError=false)
{
	if($returnError){
		wp_redirect( admin_url( '/users.php?acumaticaSyncDone=0' ) );
		exit;
	}else{
		wp_redirect( admin_url( '/users.php?acumaticaSyncDone=1' ) );
		exit;
	}
}
?>