<?php
require_once('client.inc.php');
if(!defined('INCLUDE_DIR')) die('Fatal Error');
define('CLIENTINC_DIR',INCLUDE_DIR.'client/');
define('OSTCLIENTINC',TRUE); //make includes happy

require_once(INCLUDE_DIR.'class.client.php');
require_once(INCLUDE_DIR.'class.ticket.php');
if($_POST) {
     /***********************************Mine Code****************************/
	   $result = "SELECT email,ticketpin,ticketID FROM ".TICKET_TABLE. " where ticketID='".trim($_POST['lticket'])."' AND ticketpin='".$_POST['lpincode']."' ";
	   $res=mysql_query($result);
       $row = mysql_fetch_array($res);	   
	   $_POST['lemail']=$row['email'];
	   //$pincode=substr(round($_POST['lticket']*7896/100),0,4);
	   if($_POST['lticket']==$row['ticketID'] && $_POST['lpincode']==$row['ticketpin'])
	   { 
	 /*********************************************************************/
	 
		if(($user=Client::login(trim($_POST['lticket']), trim($_POST['lemail']), null, $errors))) {
			//XXX: Ticket owner is assumed.
			@header('Location: tickets.php?task=web&id='.$user->getTicketID());
			require_once('tickets.php'); //Just in case of 'header already sent' error.
			exit;
		} elseif(!$errors['err']) {
			$errors['err'] = 'Authentication error - try again!';
		}
	 }
	 else
	 {
	   $errors['err'] = 'Authentication error - try again!';
	 }
}

$nav = new UserNav();
$nav->setActiveNav('status');
require(CLIENTINC_DIR.'header.inc.php');
require(CLIENTINC_DIR.'login.inc.php');
require(CLIENTINC_DIR.'footer.inc.php');
?>
