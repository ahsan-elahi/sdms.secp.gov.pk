<?php //session_start();
error_reporting(0);
$host='localhost'; // MySQL host
$user='ahsan';
$password='ubuntu'; 
/*
$user='root';
$password='lastfight@secp321$';    */ 
$database='sdms_scep';

$conn=mysql_connect($host,$user,$password);
mysql_select_db($database,$conn);





?>