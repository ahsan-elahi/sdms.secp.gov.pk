<?php
require('client.inc.php');
//Check token: Make sure the user actually clicked on the link to logout.
if(!$_GET['auth'] || !$ost->validateLinkToken($_GET['auth']))
   @header('Location: index.php');

$_SESSION['_client']=array();
session_unset();
session_destroy();
header('Location: ../index.php');
require('index.php');
?>
