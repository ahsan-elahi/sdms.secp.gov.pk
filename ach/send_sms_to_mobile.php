<?php
require('client.inc.php');

 function get_client_ip()
 {
      $ipaddress = '';
      if (getenv('HTTP_CLIENT_IP'))
          $ipaddress = getenv('HTTP_CLIENT_IP');
      else if(getenv('HTTP_X_FORWARDED_FOR'))
          $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
      else if(getenv('HTTP_X_FORWARDED'))
          $ipaddress = getenv('HTTP_X_FORWARDED');
      else if(getenv('HTTP_FORWARDED_FOR'))
          $ipaddress = getenv('HTTP_FORWARDED_FOR');
      else if(getenv('HTTP_FORWARDED'))
          $ipaddress = getenv('HTTP_FORWARDED');
      else if(getenv('REMOTE_ADDR'))
          $ipaddress = getenv('REMOTE_ADDR');
      else
          $ipaddress = 'UNKNOWN';

      return $ipaddress;
 }
	$IP_address = get_client_ip();
	$digits = 4;
    
	$message=rand(pow(10, $digits-1), pow(10, $digits)-1);
	$message = urlencode($message);
	
	
	$phone = $_REQUEST['user_mobile'];

$sql_verify_sms = "INSERT INTO `sdms_sms_verify` (`user_mobile`, `ip_address`, `sms_code`) VALUES ('".$phone."', '".$IP_address."', '".$message."')";
db_query($sql_verify_sms);
	$message = "Your Verification Code is ".$message;
	$message = urlencode($message);
	$curl = curl_init();
	curl_setopt_array($curl, array(
	CURLOPT_RETURNTRANSFER => 1,
	/*CURLOPT_URL => 'https://connect.jazzcmt.com/sendsms_url.html?Username=03028504208&Password=123.123&From=8181&To='.$phone.'&Message='.$message.'',
	CURLOPT_USERAGENT => 'SDMS'
	));
	echo 'https://connect.jazzcmt.com/sendsms_url.html?Username=03028504208&Password=123.123&From=8181&To='.$phone.'&Message='.$message.'';*/
	CURLOPT_URL => 'http://smsctp1.eocean.us:24555/api?action=sendmessage&username=SECP&password=P@87654&recipient='.$phone.'&originator=8181&messagedata='.$message.'',
	CURLOPT_USERAGENT => 'SDMS'
	));
	echo 'http://smsctp1.eocean.us:24555/api?action=sendmessage&username=SECP&password=P@87654&recipient='.$phone.'&originator=8181&messagedata='.$message.'';
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	curl_close($curl);
		//http://119.160.92.2:7700
	//https://connect.jazzcmt.com 

	
?>