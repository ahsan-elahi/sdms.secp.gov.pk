<?php
require('../ach/client.inc.php');


$user_mobile = $_REQUEST['user_mobile'];
$sms_code = $_REQUEST['sms_code'];

$sql_verify_sms = "Select * from `sdms_sms_verify` where `user_mobile` = '".$user_mobile."'  AND `sms_code` = '".$sms_code."'";
$res = db_query($sql_verify_sms);
if(db_num_rows($res)>0){
	$row_result = db_fetch_row($res);
	 $response["success"] = 1; 
     echo json_encode($response);
}
else{
 $response["success"] = 0;
    $response["message"] = "Invalid Mobile Number";
// echo no users JSON
    echo json_encode($response);
}
?>