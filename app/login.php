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
$sql_check="Select * from sdms_app_users where email='".$_REQUEST['email']."' and mobile_no='".$_REQUEST['mobile_no']."'";
$query_check = mysql_query( $sql_check);
	if (mysql_num_rows($query_check) > 0) 
	{
	$result_update = "UPDATE `sdms_app_users` SET `firebase_id` = '".$_REQUEST['firebase_id']."',`mobile_no` = '".$_REQUEST['mobile_no']."'  WHERE `email`= '".$_REQUEST['email']."'";
	$query = mysql_query($result_update);
	$subproduct["email"]=$_REQUEST['email'];
	$subproduct["code"] = $code;
	$subproduct["message"] = $message;
	array_push($response["app_user"], $subproduct);
	$response["success"] = 1;
	}
	else
	{
	$response["success"] = 0;
	$response["message"] = "Wrong Credentials";
	}
echo json_encode($response);
}
else 
{
$response["success"] = 2;
$response["message"] = "Required field(s) is missing";
echo json_encode($response);
}		 
?>