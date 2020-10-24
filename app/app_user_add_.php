<?php
require('../ach/client.inc.php');
define('SOURCE','Web'); //Ticket source.
require_once(INCLUDE_DIR.'class.ticket.php');
require_once(INCLUDE_DIR.'class.dept.php');
require_once(INCLUDE_DIR.'class.status.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');
$errors=array(); 
$response["app_user"] = array();
if (isset($_REQUEST['email'])) 
{	
$sql_check="Select * from sdms_app_users where email='".$_REQUEST['email']."'";
$query_check = mysql_query( $sql_check);
if (mysql_num_rows($query_check) > 0) {

$result_update = "UPDATE `sdms_app_users` SET `firebase_id` = '".$_REQUEST['firebase_id']."',`mobile_no` = '".$_REQUEST['mobile_no']."'  WHERE `email`= '".$_REQUEST['email']."'";
$query = mysql_query($result_update);

$response["message"] = "User Updated Successfully";

}else{
$result = "INSERT INTO `sdms_app_users` ( `email`,`mobile_no`, `firebase_id`) VALUES ( '".$_REQUEST['email']."','".$_REQUEST['mobile_no']."', '".$_REQUEST['firebase_id']."')";
$query = mysql_query($result);
$response["message"] = "User Created Successfully";
}


	$phone=$_REQUEST['mobile_no'];
	$IP_address = '';
    $digits = 4;
    $message = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
    $message = urlencode($message);
    $code = $message;

    $sql_verify_sms = "INSERT INTO `sdms_sms_verify` (`user_mobile`, `ip_address`, `sms_code`) VALUES ('" . $phone . "', '" . $IP_address . "', '" . $message . "')";
    $query = mysql_query($sql_verify_sms);
    $message = "Your Verification Code is " . $message;
    $message = urlencode($message);

    $curl = curl_init();
    $a=curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'http://119.160.92.2:7700/sendsms_url.html?Username=03028504208&Password=123.123&From=8181&To=' . $phone . '&Message=' . $message . '',
        CURLOPT_USERAGENT => 'SDMS'
    ));
    $resp = curl_exec($curl);
    curl_close($curl);



$subproduct["email"]=$_REQUEST['email'];
$subproduct["code"] = $code;
$subproduct["message"] = $message;
array_push($response["app_user"], $subproduct);
$response["success"] = 1;

echo json_encode($response);
}
else {
$response["success"] = 0;
$response["message"] = "Required field(s) is missing";
echo json_encode($response);
}		 
?>