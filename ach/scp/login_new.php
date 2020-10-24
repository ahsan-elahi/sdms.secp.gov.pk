<?php
require_once('../main.inc.php');
if(!defined('INCLUDE_DIR')) die('Fatal Error. Kwaheri!');

require_once(INCLUDE_DIR.'class.staff.php');
require_once(INCLUDE_DIR.'class.csrf.php');

$dest = $_SESSION['_staff']['auth']['dest'];
$msg = $_SESSION['_staff']['auth']['msg'];
$msg = $msg?$msg:'';
if($_POST) {
    //$_SESSION['_staff']=array(); #Uncomment to disable login strikes.
    if(($user=Staff::login_new($_POST['username'], $_POST['passwd'], $errors))){
	$datetime = date('Y-m-d H:i:s');
	$date = date('Y-m-d');
    
        $dest='index.php';
        @header("Location: $dest");
        require_once('index.php'); //Just incase header is messed up.
        exit;
    }

    $msg = $errors['err']?$errors['err']:'Invalid login';
}
define("OSTSCPINC",TRUE); //Make includes happy!
include_once(INCLUDE_DIR.'staff/login_new.tpl.php');
?>
