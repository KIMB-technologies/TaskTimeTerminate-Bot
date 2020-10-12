<?php
// error reporting in dev
error_reporting( !empty($_ENV['DEVMODE']) && $_ENV['DEVMODE'] == 'true' ? E_ALL : 0 );

// token to protect access
if( is_file('/code/data/WEBHOOK_REGISTERED') ){
	// load token
	$token = trim(file_get_contents('/code/data/WEBHOOK_REGISTERED'));
	if($token !== false && strlen($token) === 100){

		// check token
		if( !empty($_GET['token']) && is_string($_GET['token']) && $_GET['token'] === $token ){
			// load telegram API
			require_once('/code/bot/handleHook.php');
		}
		else{
			header('Content-Type: text/plain; charset=utf-8');
			echo "Invalid Token!";
		}
	}
	else{
		header('Content-Type: text/plain; charset=utf-8');
		echo "No Token!";
	}
}
else{
	header('Content-Type: text/plain; charset=utf-8');
	echo "No Webhook!";
}
?>