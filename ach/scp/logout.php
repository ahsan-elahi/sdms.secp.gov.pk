<?php
require('staff.inc.php');
//Check token: Make sure the user actually clicked on the link to logout.
if(!$_GET['auth'] || !$ost->validateLinkToken($_GET['auth']))
    @header('Location: index.php');

$ost->logDebug('Staff logout',
        sprintf("%s logged out [%s]", 
            $thisstaff->getUserName(), $_SERVER['REMOTE_ADDR'])); //Debug.
$sql_logout_query="";

$_SESSION['_staff']=array();
session_unset();
session_destroy();
@header('Location: login.php');
require('login.php');
?>
