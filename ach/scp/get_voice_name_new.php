<?php
error_reporting(1);

// create a new cURL resource
$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, 'http://172.16.199.183/get_voice_name_secp.php?staff_ext='.$_REQUEST['staff_ext'].'');
curl_setopt(CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, 0);
$err     = curl_errno( $ch );
$errmsg  = curl_error( $ch );
// grab URL and pass it to the browser
$response = curl_exec($ch);
// close cURL resource, and free up system resources
curl_close($ch);
print_r($errmsg).'<br>';
print_r($response).'<br>';
//===========================================================================================//
/*$fullpath = file_get_contents('https://172.16.199.183/get_voice_name_secp.php?staff_ext='.$_REQUEST['staff_ext'].'');
echo $fullpath;*/
echo "checked";exit;
//===============================Test on New Directory=========================================//
$host1='localhost'; 
$user1='root';
$password1='lastfight@secp321$'; 
$database1='sdms_scep';

/*$user1='ahsan';
$password1='ubuntu'; 
$database1='sdms_scep';*/

$conn1=mysql_connect($host1,$user1,$password1);
mysql_select_db($database1,$conn1);
$sql1 = "UPDATE `sdms_ticket` SET `ticket_voice` = '".$fullpath."' Where ticketID='".$_REQUEST['ticket_id']."'";
$result1 = mysql_query($sql1) or die('error'.mysql_error());
?>
