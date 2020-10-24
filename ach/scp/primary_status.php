<?php

require('staff.inc.php');

require_once(INCLUDE_DIR.'class.ticket.php');
require_once(INCLUDE_DIR.'class.dept.php');
require_once(INCLUDE_DIR.'class.status.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');
require_once(INCLUDE_DIR.'class.staff.php');
require_once(INCLUDE_DIR.'class.status.php');
require_once(INCLUDE_DIR.'class.districts.php');

$chairman= new Staff($_SESSION['_staff']['userID']);
if(!$_SESSION['counter'])
{
	if( $chairman->isFocalPerson()==1)
	{
	$_SESSION['counter'] = 1;	
	@header("Location: admin_dashboard.php");
	?>
<!--<script>window.location.replace('admin_dashboard.php');</script>-->
    <?php 
	}
	elseif( $chairman->onChairman()==1)
	{
	$_SESSION['counter'] = 1;	
	@header("Location: admin_dashboard.php");
	}
}
//$chairman->onChairman()==1 ||
////////////////atta code ////////////////////////////////////////////
if(isset($_REQUEST['id']) ){
$query22="select ticketID from sdms_ticket where ticket_id='".$_REQUEST['id']."'";
	$res22=mysql_query($query22)or die("error in query1");
	$row22=mysql_fetch_assoc($res22);
	$count22=mysql_num_rows($res22);
	if($count22>0){
	$query="select * from sdms_complaint_views where complaint_id='".$row22['ticketID']."'";
	$res=mysql_query($query)or die("error in query12");
	$row=mysql_fetch_assoc($res);
	$increase=$row['views']+1;
	$count=mysql_num_rows($res);
	//echo $count;exit;
	if($count>0){
	 $query2="update sdms_complaint_views set `views` = '".$increase."' where complaint_id='".$row22['ticketID']."'";
    mysql_query($query2)or die('error in updatation query');
	}else{
	 $query2="insert into sdms_complaint_views(views,complaint_id) values('".$increase."','".$row22['ticketID']."')";
    mysql_query($query2)or die('error in insertion query');
	}
}}
if($_REQUEST['basic_search']=='Search'){
	 $query22="select ticketID from sdms_ticket where ticketID='".$_REQUEST['query']."'";
	$res22=mysql_query($query22)or die("error in query123");
	$row22=mysql_fetch_assoc($res22);
	$count22=mysql_num_rows($res22);
	//echo $count;exit;
	if($count22>0){
	 $query="select * from sdms_complaint_views where complaint_id='".$_REQUEST['query']."'";
	$res=mysql_query($query)or die("error in query1234");
	$row=mysql_fetch_assoc($res);
	$increase=$row['views']+1;
	$count=mysql_num_rows($res);
	//echo $count;exit;
	/*
	 id
	 ticket id  
	 ticket_stauts 
	 ip_address
	 mob_nos
	 query_type
	 time_stamp 
	*/
	if($count>0){
	 $query2="update sdms_complaint_views set `views`='".$increase."' where complaint_id='".$_REQUEST['query']."'";
    mysql_query($query2)or die('error in updatation query');
	}else{
	 $query2="insert into sdms_complaint_views(views,complaint_id) values('".$increase."','".$_REQUEST['query']."')";
    mysql_query($query2)or die('error in insertion query');
	}
}

}
$page='';
$ticket=null; //clean start.
//LOCKDOWN...See if the id provided is actually valid and if the user has access.

if($_REQUEST['id']) {

	 if(!($ticket=Ticket::lookup($_REQUEST['id'])))

         $errors['err']='Unknown or invalid ticket ID';

    elseif(!$ticket->checkStaffAccess($thisstaff)) {

        $errors['err']='Access denied. Contact admin if you believe this is in error';

        $ticket=null; //Clear ticket obj.

    }

}

/*... Quick stats ...*/
$stats= $thisstaff->getTicketsStats();
//Navigation
$nav->setTabActive('primary_status');
$inc = 'tickets.inc.php';
if($ticket) {
    $ost->setPageTitle('Complaints #'.$ticket->getNumber());
    $nav->setActiveSubMenu(-1);
    $inc = 'ticket-view.inc.php';
    if($_REQUEST['a']=='edit' && $thisstaff->canEditTickets())
        $inc = 'ticket-edit.inc.php';
	elseif($_REQUEST['a'] == 'print' && !$ticket->pdfExport($_REQUEST['psize'], $_REQUEST['notes']))
        $errors['err'] = 'Internal error: Unable to export the ticket to PDF for print.';
	elseif($_REQUEST['a'] == 'edit_thread' && !$ticket->updatethread($_REQUEST['sub'],$_REQUEST['comments'],$_REQUEST['t_id']))
        $errors['err'] = 'Internal error: Unable to Edit the Thread.';
		if($_REQUEST['a'] == 'edit_thread')
		$msg='Thread successfully updated.';	
} else {
    $inc = 'primary_status.inc.php';
}
require_once(STAFFINC_DIR.'header.inc.php');
require_once(STAFFINC_DIR.$inc);
require_once(STAFFINC_DIR.'footer.inc.php');
?>