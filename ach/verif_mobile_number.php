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
$user_mobile = $_REQUEST['user_mobile'];
$sms_code = $_REQUEST['sms_code'];

$sql_verify_sms = "Select * from `sdms_sms_verify` where `user_mobile` = '".$user_mobile."' AND  `ip_address` =  '".$IP_address."' AND `sms_code` = '".$sms_code."'";
$res = db_query($sql_verify_sms);
if(db_num_rows($res)>0){$row_result = db_fetch_row($res);echo '1';}else{echo '0';}?>