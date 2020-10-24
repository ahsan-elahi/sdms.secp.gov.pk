<?php

//$upload_path = 'uploads/';
//$upload_url = 'http://'.$server_ip.'/AndroidPdfUpload/'.$upload_path;

$upload_path = 'app/uploads/';

$upload_url = 'http://projects.multibizservices.com/sdms.secp.gov.pk/'.$upload_path;

if($_SERVER['REQUEST_METHOD']=='POST'){

	  if(isset($_POST['name']) and isset($_FILES['pdf']['name'])){

	    $name = $_POST['name'];

        $fileinfo = pathinfo($_FILES['pdf']['name']);

		$extension = $fileinfo['extension'];

	

		$file_path = $upload_path .$_POST['name']. '.'. $extension;

		try{

			 move_uploaded_file($_FILES['pdf']['tmp_name'],$file_path);

		}catch(Exception $e){

            $response['error']=true;

            $response['message']=$e->getMessage();

        } 

	  }

	

}

?>