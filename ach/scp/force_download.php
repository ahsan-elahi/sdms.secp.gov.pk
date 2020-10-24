<?php 
$file = explode("/", $_REQUEST['file']);
$file_name = $file[7];
$file_url = $_REQUEST['file'];
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"".$file_name."\""); 
readfile($file_url);
exit;
?>