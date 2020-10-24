<?php 
require_once 'conn.php';
define('FIREBASE_API_KEY', 'AIzaSyCgUYwSg6P1wEQrzoD21e41cXTo_ccNyBs'); 
error_reporting(0);
ini_set('display_errors', 'On');

require_once __DIR__ . '/firebase.php';
require_once __DIR__ . '/push.php';

$firebase = new Firebase();
$push = new Push();

// optional payload
$payload = array();
$payload['team'] = '';
$payload['score'] = '';

$json = '';
$response = '';


//if(isset($_POST['submit'])){
/*Image uploaded*/
/*if ($_FILES['user_pic']['name']==''){
	$img_src = "Empty";	
}
else{
$target_path = "img/notifi/"; 
$rand=rand(00000,999999);
$target_path = $target_path . basename($rand.$_FILES['user_pic']['name']);
$img_src =  basename($rand.$_FILES['user_pic']['name']);
$tmp = $_FILES['user_pic']['tmp_name'];
move_uploaded_file($tmp,$target_path);
}*/
/*Upload Audio*/
/*if ($_FILES['user_audio']['name']==''){
 $audio_src="Empty";	
}
else{
$target_path = "img/notifi_audio/"; 
$rand=rand(00000,999999);
$target_path = $target_path . basename($rand.$_FILES['user_audio']['name']);
$audio_src =  basename($rand.$_FILES['user_audio']['name']);
$tmp = $_FILES['user_audio']['tmp_name'];
move_uploaded_file($tmp,$target_path);
}*/
/*Upload Video*/
/*if ($_FILES['user_video']['name']==''){
$video_src="Empty";	
}
else{
$target_path = "img/notifi_video/"; 
$rand=rand(00000,999999);
$target_path = $target_path . basename($rand.$_FILES['user_video']['name']);
$video_src =  basename($rand.$_FILES['user_video']['name']);
$tmp = $_FILES['user_video']['tmp_name'];
move_uploaded_file($tmp,$target_path);
}*/
$push_type = isset($_REQUEST['push_type']) ? $_REQUEST['push_type'] : '';

// whether to include to image or not
$include_image = isset($_REQUEST['include_image']) ? TRUE : FALSE;

	//$img_path = 'http://175.107.63.54/ccpo/img/notifi/';
	//$audio_path = 'http://175.107.63.54/ccpo/img/notifi_audio/';
	//$video_path = 'http://175.107.63.54/ccpo/img/notifi_video/';
	
	$_REQUEST['date']=date('Y-m-d');
	
	$message = "Dear Sir/Miss, <br>";
	$message .= 'Your query '.$notifiticketid.' has reached us and is currently under review. You should expect an update in this regard shortly.<br>';
	$message .= "Thank you for your patience.<br>Regards,<br>SECP Service Desk<br>UAN: 080088008";
	
	
	$_REQUEST['notifi_title']='New Query Registered (SDMS SECP)';
	
	$_REQUEST['notifi_descp']=$message;
	$notifi_title=$_REQUEST['notifi_title'];
	//$notifi_shrt_descp=$_REQUEST['notifi_shrt_descp'];
	$notifi_descp=$_REQUEST['notifi_descp'];
	$date=date('Y-m-d',strtotime($_REQUEST['date']));


/*if ($_FILES['user_pic']['name']==''){
	$push->setImage($img_src);}
	else{ $push->setImage($img_path.$img_src);}
	
if ($_FILES['user_video']['name']==''){
$push->setVideo($video_src);	
} else{$push->setVideo($video_path.$video_src);}

if ($_FILES['user_audio']['name']==''){
    $push->setAudio($audio_src);}
	else{$push->setAudio($audio_path.$audio_src);}*/
	


//print_r($json);`
//Insert query of the admin user account
/*$res_user_firebase=$conn->query("SELECT * from sdms_app_users where status=1");
while($row_user_firebase=$res_user_firebase->fetch_assoc()){ */
$res_user = mysqli_query($conn, "SELECT email from sdms_ticket where ticket_id='".$notifiticketid."'");
$row_user = mysqli_fetch_assoc($res_user);

$res_user_firebase = mysqli_query($conn, "SELECT * from sdms_app_users where email='".$row_user['email']."'");
 while ($row_user_firebase = mysqli_fetch_assoc($res_user_firebase)) {
//print_r($row_user_firebase).'<br>';
$regId = isset($row_user_firebase['firebase_id']) ? $row_user_firebase['firebase_id'] : '';
$user_email =  $row_user_firebase['email'];

$sql="INSERT INTO `secp_notification`(`user_email`,`notifi_title`,`notifi_descp`,`notifi_send_date`, `notifi_created`) VALUES ('".$user_email."','".$_REQUEST['notifi_title']."','".$_REQUEST['notifi_descp']."','$date',NOW())";
$res_notificaiton = mysqli_query($conn, $sql);


//$res_notificaiton = $conn->query($sql);
$last_id =  mysqli_insert_id($conn);
$push->setTitle($notifi_title);
$push->setMessage($notifi_descp);
$push->setType("New Query");
$push->setdate($date);
$push->setIsBackground(FALSE);
$push->setPayload($payload);
$push->notification_id($last_id);
$json = $push->getPush();
//echo $regId.'<br>';
//print_r($json).'<br><br>';
$response = $firebase->send($regId, $json);
//print_r($json).'<br>';
}

//echo $sql; exit;
if($res_notificaiton){
	//echo '1';
?>
<script>
//window.location.replace("rescue_notification.php?msg=succss");
</script>
<?php	
	}
else{
	//echo '0';
?>
<script>
//window.location.replace("rescue_notification.php?msg=fail");
</script>

<?php

}
//}