<?php
//sms code
$digits = 4;
$rand_nos = 0;
    
	$message=rand(pow(10, $digits-1), pow(10, $digits)-1);
	$rand_nos = urlencode($message);
	$message = "Your Verification Code is ".$rand_nos;
	$message = urlencode($message);
	
	$phone = $_REQUEST['user_mobile'];

	$curl = curl_init();
	curl_setopt_array($curl, array(
	CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_URL => 'http://119.160.92.2:7700/sendsms_url.html?Username=03028504208&Password=123.123&From=8181&To='.$phone.'&Message='.$message.'',
	CURLOPT_USERAGENT => 'SECP'
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	curl_close($curl); 
	
	echo $rand_nos;
?>