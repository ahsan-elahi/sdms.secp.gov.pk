<?php
require('admin.inc.php');

//sms code
$ticket_id = $_REQUEST['ticket_id'];
$sql_verify_sms = "Select * from sdms_ticket where ticket_id ='".$ticket_id."'";
$res_query = mysql_query($sql_verify_sms);
$row_query = mysql_fetch_array($res_query);
$phone = $row_query['phone'];
$message = "Your Complaint Number is ".$ticket_id." AND  Pincode is ".$row_query['ticketpin'];	
	$message = urlencode($message);
	$curl = curl_init();
	curl_setopt_array($curl, array(
	CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_URL => 'http://119.160.92.2:7700/sendsms_url.html?Username=03028504208&Password=123.123&From=8181&To='.$phone.'&Message='.$message.'',
	CURLOPT_USERAGENT => 'SDMS'
	));
	echo 'http://119.160.92.2:7700/sendsms_url.html?Username=03028504208&Password=123.123&From=8181&To='.$phone.'&Message='.$message.'';
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	curl_close($curl); 
?>