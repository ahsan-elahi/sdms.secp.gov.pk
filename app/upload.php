<?php $upload_path = '/var/www/html/ach/scp/upload/';

$upload_url = 'https://sdms.secp.gov.pk/app/'.$upload_path;

$result = array();

$file_path = basename( $_FILES['file']['name']);

if(move_uploaded_file($_FILES['file']['tmp_name'], $upload_path .$file_path)) {

    $result = array("success" => 1);
} else{

    $result = array("success" => 0);

}
echo json_encode($result, JSON_PRETTY_PRINT);
?>