<?php 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://172.16.199.183/get_voice_name_secp.php?staff_ext='.$_REQUEST['staff_ext'].'');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

$host1='localhost'; 
$user1='root';
$password1='lastfight@secp321$'; 
$database1='sdms_scep';

$conn1=mysql_connect($host1,$user1,$password1);
mysql_select_db($database1,$conn1);
$sql1 = "UPDATE `sdms_ticket` SET `ticket_voice` = '".$response."' Where ticketID='".$_REQUEST['ticket_id']."'";
$result1 = mysql_query($sql1) or die('error'.mysql_error());
?>
