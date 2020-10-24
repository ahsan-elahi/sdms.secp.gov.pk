<?php
/*********************************************************************
    tickets.php
    Main client/user interface.
    Note that we are using external ID. The real (local) ids are hidden from user.
    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2013 osTicket
    http://www.osticket.com
    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.
    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/

require('secure.inc.php');
if(!is_object($thisclient) || !$thisclient->isValid()) die('Access denied'); //Double check again.

require_once(INCLUDE_DIR.'class.ticket.php');

$ticket=null;
if($_REQUEST['id'] && $_REQUEST['task']=='web') 
{

	////////////////atta code//////////////////
	 $query22="select ticketID,status,isquery from sdms_ticket where ticketID ='".$_REQUEST['id']."'";
	$res22=mysql_query($query22)or die("error in query");
	$row22=mysql_fetch_assoc($res22);
	
	if($row22['isquery']==1)
	@header('Location: queries.php?task=web&id='.$_REQUEST['id']);
	
	$count22=mysql_num_rows($res22);
	$type="WEB";
	$phone_nos="";		
	$ip_address=$_SERVER['REMOTE_ADDR'];
	$query2="insert into sdms_complaint_views(views,complaint_id,status,ip_address,phone_nos,type) 
	values('".$increase."','".$row22['ticketID']."','".$row22['status']."','".$ip_address."','".$phone_nos."','".$type."')";
	mysql_query($query2)or die('error in insertion query');

    if(!($ticket=Ticket::lookupByExtId($_REQUEST['id']))) {

        $errors['err']='Unknown or invalid Complaint ID.';

    }elseif(!$ticket->checkClientAccess($thisclient)) {

        $errors['err']='Unknown or invalid Complaint ID.'; //Using generic message on purpose!

        $ticket=null;

    }

}
//Process post...depends on $ticket object above.
if($_POST && is_object($ticket) && $ticket->getId()):
    $errors=array();
    switch(strtolower($_POST['a'])){
    case 'reply':
        if(!$ticket->checkClientAccess($thisclient)) //double check perm again!
            $errors['err']='Access Denied. Possibly invalid Complaint ID';
        if(!$_POST['message'])
            $errors['message']='Message required';
        if(!$errors) {
            //Everything checked out...do the magic.
            $vars = array('message'=>$_POST['message']);
            if($cfg->allowOnlineAttachments() && $_FILES['attachments'])
              $vars['files'] = AttachmentFile::format($_FILES['attachments'], true);
			  $vars['complaint_status'] = $_POST['complaint_status'];
			  $vars['title'] = $_POST['title'];
			  
            if(($msgid=$ticket->postMessage($vars, 'Web'))) {
                $msg='Message Posted Successfully';
            } else {
                $errors['err']='Unable to post the message. Try again';
            }



        } elseif(!$errors['err']) {

            $errors['err']='Error(s) occurred. Please try again';

        }

        break;

    default:

        $errors['err']='Unknown action';

    }

    $ticket->reload();

endif;

$nav->setActiveNav('tickets');

if($ticket && $ticket->checkClientAccess($thisclient)) {

    $inc='view.inc.php';

} elseif($cfg->showRelatedTickets() && $thisclient->getNumTickets()) {
	$inc='tickets.inc.php';
} 
else if( $_SESSION['_client']['token'])
{
$inc='tickets.inc.php';
}
else {

    $nav->setActiveNav('new');

    $inc='open.inc.php';

}
// $inc='tickets.inc.php';

include(CLIENTINC_DIR.'header.inc.php');

include(CLIENTINC_DIR.$inc);

include(CLIENTINC_DIR.'footer.inc.php');

?>

