<style>
#tSortable_paginate {
	display: none;
}
#tSortable_filter {
	display: none;
}
.box_new {
	width: 20px;
	height: 20px;
}
.assigned_new {
	background: #e1ffea;
}
.handled_new {
	background: #d8bfd8;
}
.pending_new {
	background: #fff;
}
.pending_handled_new {
	background: antiquewhite;
}
</style>
<script>
function get_Sub_Status(status_id)
{
$.ajax({
	url:"get_sub_status_listing.php",
	data: "status_id="+status_id,
	success: function(msg){
	$("#show_sub_status").html(msg);
}
});
}
function send_sms(ticket_id){
	$.ajax({
	url:"send_pincode_to_mobile.php",
	data: "ticket_id="+ticket_id,
	success: function(msg){	
	//$("#sms_verification_number").html(msg);
	}
	});
}
</script>
<?php
$sql_group_concat_max_len = "SET SESSION group_concat_max_len = 1000000";
mysql_query($sql_group_concat_max_len);

if(!defined('OSTSCPINC') || !$thisstaff || !@$thisstaff->isStaff()) die('Access Denied');
$qstr='&'; //Query string collector
if($_REQUEST['status']) { //Query string status has nothing to do with the real status used below; gets overloaded.
    $qstr.='status='.urlencode($_REQUEST['status']);
}
if($_REQUEST['cstatus']) { //Query string status has nothing to do with the real status used below; gets overloaded.
    $qstr.='complaint_status='.urlencode($_REQUEST['status']);
}
//See if this is a search
$search=($_REQUEST['a']=='search');
$searchTerm='';
//make sure the search query is 3 chars min...defaults to no query with warning message

if($search) {
  $searchTerm=$_REQUEST['query'];
  if( ($_REQUEST['query'] && strlen($_REQUEST['query'])<1) 
      || (!$_REQUEST['query'] && isset($_REQUEST['basic_search'])) ){ //Why do I care about this crap...
      $search=false; //Instead of an error page...default back to regular query..with no search.
      $errors['err']='Search term must be more than 1 chars';
      $searchTerm='';

  }

}

$showoverdue=$showanswered=false;

$staffId=0; //Nothing for now...TODO: Allow admin and manager to limit Complaints to single staff level.

$showassigned= true; //show Assigned To column - defaults to true 

//Get status we are actually going to use on the query...making sure it is clean!

$status=null;

switch(strtolower($_REQUEST['status'])){ //Status is overloaded

    case 'open':

        $status='open';

        break;

    case 'closed':

        $status='closed';

        $showassigned=true; //closed by.

        break;

	case 'lodged':

        $status='lodged';

        break;
		
	case 'deny':
        $status='deny';
        break;
		
    case 'overdue':

        $status='open';

        $showoverdue=true;

        $results_type='Overdue Complaints';

        break;

    case 'assigned':

        $status='open';

        $staffId=$thisstaff->getId();

        $results_type='My Complaints';

        break;

    case 'answered':

        $status='open';

        $showanswered=true;

        $results_type='Answered Complaints';

        break;

    default:
        if(!$search){

        $_REQUEST['status']=$status='open';

		$mark_complaint=true;

		}

}

$qwhere ='';
/* 
   STRICT DEPARTMENTS BASED PERMISSION!
   User can also see Complaints assigned to them regardless of the Complaint's dept.
*/

$depts=$thisstaff->getDepts();    
$qwhere =' WHERE ( '

        .'  ticket.staff_id ='.db_input($thisstaff->getId());

	//Ahsan FS Change
if($thisstaff->canCreateTickets()  || $thisstaff->onChairman()) {

			if(!$thisstaff->showAssignedOnly())

    $qwhere.=' OR ticket.dept_id IN ('.($depts?implode(',', db_input($depts)):0).')';

	}
else{

if($status!='lodged')
{
	$qwhere .=" OR ticket.ticket_id IN (SELECT DISTINCT(`ticket_id`) as ticket_id FROM ".TICKET_EVENT_TABLE." WHERE staff_id='".db_input($thisstaff->getId())." ' AND `state` = 'assigned') ";	
}}

/*
if(($teams=$thisstaff->getTeams()) && count(array_filter($teams)))
$qwhere.=' OR ticket.team_id IN('.implode(',', db_input(array_filter($teams))).') ';*/

$qwhere .= ' )';


if($_REQUEST['primary_stutus']!='')
{

$qstr.='&primary_stutus='.$_REQUEST['primary_stutus'];	
$qwhere .=" AND ( ";
$sql_sub_status="SELECT * FROM `sdms_status` WHERE p_id='".$_REQUEST['primary_stutus']."'";
$res_sub_status=mysql_query($sql_sub_status);
$num_sub_status = mysql_num_rows($res_sub_status);
while($row_sub_status=mysql_fetch_array($res_sub_status)){
$qwhere .=" ticket.complaint_status = '".$row_sub_status['status_id']."' OR";
}
$qwhere = substr( $qwhere, 0, -2 );
$qwhere .=" ) ";   
}



if($_REQUEST['assign_staff']!='')
{
$qwhere.=' AND (ticket.staff_id='.db_input($_REQUEST['assign_staff']).') ';
        $qstr.='&assign_staff='.urlencode($_REQUEST['assign_staff']);
}
if($_REQUEST['action']=='feedbac_received')
{
//$qwhere.=" AND ticket.dept_id IN (SELECT dept_id FROM `sdms_feedback` WHERE 1 ) ";
if($_REQUEST['experiance']==1)
$experiance = "AND experiance = '1'";
elseif($_REQUEST['experiance']==2)
$experiance = "AND experiance = '2'";
elseif($_REQUEST['experiance']==3)
$experiance = "AND experiance = '3'";
elseif($_REQUEST['experiance']=='')
$experiance = "";

$qwhere .=" AND ticket.ticket_id IN (SELECT DISTINCT(`complainant_id`) as ticket_id FROM sdms_feedback WHERE dept_id='".$_REQUEST['deptId']."' ".$experiance.") ";	
}
if($_REQUEST['action']=='reopen')
{
$qwhere .=" AND ticket.reopened != 'NULL' ";	
}
if($_REQUEST['action']=='replytouser')
{
// $qwhere .=" AND ticket.ticket_id IN (SELECT ticket_id FROM sdms_ticket_thread where title='User Reply' GROUP BY ticket_id ORDER BY ID DESC ) ";
 $qwhere .=" AND ticket.ticket_id IN (
 SELECT ticket_id FROM sdms_ticket_thread n
WHERE n.title = 'User Reply' AND n.id=(SELECT MAX(id) FROM sdms_ticket_thread WHERE ticket_id=n.ticket_id) )";

}

if($_REQUEST['action']=='updatebyagent')
{
$qwhere .=" AND ticket.ticket_id IN (
 SELECT ticket_id FROM sdms_ticket_thread n
WHERE n.thread_type = 'N' AND n.id=(SELECT MAX(id) FROM sdms_ticket_thread WHERE ticket_id=n.ticket_id) AND n.staff_id IN (SELECT staff_id FROM sdms_staff WHERE group_id = 7))";
}

if($_REQUEST['action']=='all_feedbac_received')
{
//$qwhere.=" AND ticket.dept_id IN (SELECT dept_id FROM `sdms_feedback` WHERE 1 ) ";
if($_REQUEST['experiance']==1)
$experiance = "AND experiance = '1'";
elseif($_REQUEST['experiance']==2)
$experiance = "AND experiance = '2'";
elseif($_REQUEST['experiance']==3)
$experiance = "AND experiance = '3'";
elseif($_REQUEST['experiance']=='')
$experiance = "";

$qwhere .=" AND ticket.ticket_id IN (SELECT DISTINCT(`complainant_id`) as ticket_id FROM sdms_feedback WHERE 1 ".$experiance.") ";	
}


//scd_company		|| scd_company_subtotal
//scd_agent			|| scd_agent_subtotal
//scd_complainant	|| scd_complainant_subtotal
if($_REQUEST['action']=='scd_company')
{
	

	$company_type = $_REQUEST['company_type'];
	$compay = $_REQUEST['compay'];
	
if($company_type == 'Housing Finance Company')
$broker_title = "1";
else	
$broker_title = "scd_broker_title!=''";

$sql_status="SELECT count(scd_broker_title) as total,group_concat( complaint_id ) as complaint_ids 
FROM `sdms_ticket_scd` WHERE ".$broker_title." AND scd_type = '".$company_type."' AND scd_broker_title = '".$compay."' group by `scd_broker_title`  order by total desc limit 10";
$res_status=mysql_query($sql_status);
$row_status=mysql_fetch_array($res_status);
$qwhere .=" AND  ticket.ticket_id IN(".$row_status['complaint_ids'].") ";
$qstr.='&action='.$_REQUEST['action'].'&company_type='.$_REQUEST['company_type'].'&compay='.$_REQUEST['compay'].'';
}
if($_REQUEST['action']=='scd_company_subtotal')
{
	$company_type = $_REQUEST['company_type'];
	if($company_type == 'Housing Finance Company')
	$broker_title = "1";
	else	
	$broker_title = "scd_broker_title!=''";
	
	$sql_status="SELECT count(scd_broker_title) as total,group_concat( complaint_id ) as complaint_ids 
	FROM `sdms_ticket_scd` WHERE ".$broker_title." AND scd_type = '".$company_type."' group by `scd_broker_title` order by total desc limit 10";
	$res_status=mysql_query($sql_status);
	while($row_status=mysql_fetch_array($res_status)){
	$complaint_ids .= $row_status['complaint_ids'].',';
	}
	$complaint_ids = rtrim($complaint_ids,",");
	$qwhere .=" AND  ticket.ticket_id IN(".$complaint_ids.") ";
	$qstr.='&action='.$_REQUEST['action'].'&company_type='.$_REQUEST['company_type'];
}
if($_REQUEST['action']=='scd_agent')
{
	$agent_type = $_REQUEST['agent_type'];
	$agent = $_REQUEST['agent'];

$sql_status="SELECT ".$agent_type." as agent,count(".$agent_type.") as total ,group_concat( complaint_id ) as complaint_ids, group_concat( ".$agent_type." ) AS agents FROM `sdms_ticket_scd` WHERE ".$agent_type."!='' AND ".$agent_type." = '".$agent."'  group by ".$agent_type." order by total desc limit 10";

$res_status=mysql_query($sql_status);
$row_status=mysql_fetch_array($res_status);
$qwhere .=" AND  ticket.ticket_id IN(".$row_status['complaint_ids'].") ";
$qstr.='&action='.$_REQUEST['action'].'&agent_type='.$_REQUEST['agent_type'].'&agent='.$_REQUEST['agent'];
}
if($_REQUEST['action']=='scd_agent_subtotal')
{
	
	$qstr.='&action='.$_REQUEST['action'].'&agent_type='.$_REQUEST['agent_type'];
	
	$agent_type = $_REQUEST['agent_type'];
	$sql_status="SELECT ".$agent_type." as agent,count(".$agent_type.") as total ,group_concat( complaint_id ) as complaint_ids, group_concat( ".$agent_type." ) AS agents FROM `sdms_ticket_scd` WHERE ".$agent_type."!=''  group by ".$agent_type." order by total desc limit 10";

	
	$res_status=mysql_query($sql_status);
	while($row_status=mysql_fetch_array($res_status)){
	$complaint_ids .= $row_status['complaint_ids'].',';
	}
	$complaint_ids = rtrim($complaint_ids,",");
	$qwhere .=" AND  ticket.ticket_id IN(".$complaint_ids.") ";
	$qstr.='&action='.$_REQUEST['action'].'&agent_type='.$_REQUEST['agent_type'];
}
if($_REQUEST['action']=='scd_complainant')
{
	
$sql_status="SELECT  count(ticket_id) as total,group_concat( ticket_id ) as complaint_ids,`email` FROM `sdms_ticket` WHERE  email!='' AND dept_id = '4' AND isquery=0 ".$from_to_date." AND email NOT LIKE '%@novalidemail.pk' AND email = '".$_REQUEST['email']."' group by `email` order by total desc limit 0,5";
$res_status=mysql_query($sql_status);
$row_status=mysql_fetch_array($res_status);
$qwhere .=" AND  ticket.ticket_id IN(".$row_status['complaint_ids'].") ";
$qstr.='&action='.$_REQUEST['action'].'&email='.$_REQUEST['email'];
}
if($_REQUEST['action']=='scd_complainant_subtotal')
{
	$sql_status="SELECT  count(ticket_id) as total,group_concat( ticket_id ) as complaint_ids,`email` FROM `sdms_ticket` WHERE  email!='' AND dept_id = '4' AND isquery=0 ".$from_to_date." AND email NOT LIKE '%@novalidemail.pk' group by `email` order by total desc limit 0,5";
	$res_status=mysql_query($sql_status);
	while($row_status=mysql_fetch_array($res_status)){
	$complaint_ids .= $row_status['complaint_ids'].',';
	}
	$complaint_ids = rtrim($complaint_ids,",");
	$qwhere .=" AND  ticket.ticket_id IN(".$complaint_ids.") ";
	$qstr.='&action='.$_REQUEST['action'];
}

//i_company 	||  i_company_subtotal 
//i_agent   	|| i_agent_subtotal
//i_complainant || i_complainant_subtotal
if($_REQUEST['action']=='i_company')
{
	$company_type = urldecode($_REQUEST['company_type']);
	$compay = urldecode($_REQUEST['compay']);
	$broker_title = "i_broker_title!=''";

$sql_status="SELECT i_broker_title as compay,count(i_broker_title) as total ,group_concat( complaint_id ) as complaint_ids, group_concat( i_broker_title) AS companies FROM `sdms_ticket_insurance` WHERE ".$broker_title." AND i_type = '".$company_type."'  AND i_broker_title = '".$compay."' group by `i_broker_title`  order by total desc limit 10 ";
//echo $sql_status;
$res_status=mysql_query($sql_status);
$row_status=mysql_fetch_array($res_status);
$qwhere .=" AND  ticket.ticket_id IN(".$row_status['complaint_ids'].") ";
$qstr.='&action='.$_REQUEST['action'].'&company_type='.$_REQUEST['company_type'].'&compay='.$_REQUEST['compay'].'';
}
if($_REQUEST['action']=='i_company_subtotal')
{
	$company_type = urldecode($_REQUEST['company_type']);
	
	$broker_title = "i_broker_title!=''";
	
	$sql_status="SELECT count(i_broker_title) as total,group_concat( complaint_id ) as complaint_ids 
	FROM `sdms_ticket_insurance` WHERE ".$broker_title." AND i_type = '".$company_type."' group by `i_broker_title` order by total desc limit 10";
	$res_status=mysql_query($sql_status);
	while($row_status=mysql_fetch_array($res_status)){
	$complaint_ids .= $row_status['complaint_ids'].',';
	}
	$complaint_ids = rtrim($complaint_ids,",");
	$qwhere .=" AND  ticket.ticket_id IN(".$complaint_ids.") ";
	$qstr.='&action='.$_REQUEST['action'].'&company_type='.$_REQUEST['company_type'];
}
if($_REQUEST['action']=='i_agent')
{
	
	$agent_type = urldecode($_REQUEST['agent_type']);
	$agent = urldecode($_REQUEST['agent']);


$sql_status="SELECT `i_broker_agent` as agent,count(`i_broker_agent`) as total ,group_concat( complaint_id ) as complaint_ids, group_concat( `i_broker_agent` ) AS agents FROM `sdms_ticket_insurance` WHERE i_broker_agent='".$agent."' AND i_type ='".$agent_type."'   group by `i_broker_agent` order by total desc limit 10";


$res_status=mysql_query($sql_status);
$row_status=mysql_fetch_array($res_status);
$qwhere .=" AND  ticket.ticket_id IN(".$row_status['complaint_ids'].") ";
$qstr.='&action='.$_REQUEST['action'].'&agent_type='.$_REQUEST['agent_type'].'&agent='.$_REQUEST['agent'];
}
if($_REQUEST['action']=='i_agent_subtotal')
{
	$agent_type = urldecode($_REQUEST['agent_type']);
	$sql_status="SELECT `i_broker_agent` as agent,count(`i_broker_agent`) as total ,group_concat( complaint_id ) as complaint_ids, group_concat( `i_broker_agent` ) AS agents FROM `sdms_ticket_insurance` WHERE i_broker_agent!='' AND i_type ='".$agent_type."'   group by `i_broker_agent` order by total desc limit 10";

	
	$res_status=mysql_query($sql_status);
	while($row_status=mysql_fetch_array($res_status)){
	$complaint_ids .= $row_status['complaint_ids'].',';
	}
	$complaint_ids = rtrim($complaint_ids,",");
	$qwhere .=" AND  ticket.ticket_id IN(".$complaint_ids.") ";
}
if($_REQUEST['action']=='i_complainant')
{
	
$sql_status="SELECT  count(ticket_id) as total,group_concat( ticket_id ) as complaint_ids,`email` FROM `sdms_ticket` WHERE  email!='' AND dept_id = '3' AND isquery=0 ".$from_to_date." AND email NOT LIKE '%@novalidemail.pk' AND email = '".urldecode($_REQUEST['email'])."' group by `email` order by total desc limit 0,5";
$res_status=mysql_query($sql_status);
$row_status=mysql_fetch_array($res_status);
$qwhere .=" AND  ticket.ticket_id IN(".$row_status['complaint_ids'].") ";
$qstr.='&action='.$_REQUEST['action'].'&email='.$_REQUEST['email'];
}
if($_REQUEST['action']=='i_complainant_subtotal')
{
	$sql_status="SELECT  count(ticket_id) as total,group_concat( ticket_id ) as complaint_ids,`email` FROM `sdms_ticket` WHERE  email!='' AND dept_id = '3' AND isquery=0 ".$from_to_date." AND email NOT LIKE '%@novalidemail.pk' group by `email` order by total desc limit 0,5";
	$res_status=mysql_query($sql_status);
	while($row_status=mysql_fetch_array($res_status)){
	$complaint_ids .= $row_status['complaint_ids'].',';
	}
	$complaint_ids = rtrim($complaint_ids,",");
	$qwhere .=" AND  ticket.ticket_id IN(".$complaint_ids.") ";
	$qstr.='&action='.$_REQUEST['action'];
}

//cm_company 	||  cm_company_subtotal 
//cm_agent   	|| cm_agent_subtotal
//i_complainant || i_complainant_subtotal
if($_REQUEST['action']=='cm_company')
{
	$company_type = urldecode($_REQUEST['company_type']);
	$compay = urldecode($_REQUEST['compay']);
	$broker_title = "cm_broker_title!=''";

$sql_status="SELECT cm_broker_title as compay,count(cm_broker_title) as total ,group_concat( complaint_id ) as complaint_ids, group_concat( cm_broker_title) AS companies FROM `sdms_ticket_capital_markets` WHERE ".$broker_title." AND cm_type = '".$company_type."'  AND cm_broker_title = '".$compay."' group by `cm_broker_title`  order by total desc limit 10 ";

//echo $sql_status;
$res_status=mysql_query($sql_status);
$row_status=mysql_fetch_array($res_status);
$qwhere .=" AND  ticket.ticket_id IN(".$row_status['complaint_ids'].") ";
$qstr.='&action='.$_REQUEST['action'].'&company_type='.$_REQUEST['company_type'].'&compay='.$_REQUEST['compay'].'';
}
if($_REQUEST['action']=='cm_company_subtotal')
{
	$company_type = urldecode($_REQUEST['company_type']);
	
	$broker_title = "cm_broker_title!=''";
	
	$company_type = $_REQUEST['company_type'];
	$sql_status="SELECT count(cm_broker_title) as total,group_concat( complaint_id ) as complaint_ids 
	FROM `sdms_ticket_capital_markets` WHERE ".$broker_title." AND cm_type = '".$company_type."' group by `cm_broker_title` order by total desc limit 10";
	$res_status=mysql_query($sql_status);
	while($row_status=mysql_fetch_array($res_status)){
	$complaint_ids .= $row_status['complaint_ids'].',';
	}
	$complaint_ids = rtrim($complaint_ids,",");
	$qwhere .=" AND  ticket.ticket_id IN(".$complaint_ids.") ";
	$qstr.='&action='.$_REQUEST['action'].'&company_type='.$_REQUEST['company_type'];
}
if($_REQUEST['action']=='cm_agent')
{
	
	$agent_type = $_REQUEST['agent_type'];
	$agent = urldecode($_REQUEST['agent']);


$sql_status="SELECT `cm_broker_agent` as agent,count(`cm_broker_agent`) as total ,group_concat( complaint_id ) as complaint_ids, group_concat( `cm_broker_agent` ) AS agents FROM `sdms_ticket_capital_markets` WHERE cm_broker_agent='".$agent."' AND cm_type ='".$agent_type."'   group by `cm_broker_agent` order by total desc limit 10";


$res_status=mysql_query($sql_status);
$row_status=mysql_fetch_array($res_status);
$qwhere .=" AND  ticket.ticket_id IN(".$row_status['complaint_ids'].") ";
$qstr.='&action='.$_REQUEST['action'].'&agent_type='.$_REQUEST['agent_type'].'&agent='.$_REQUEST['agent'];
}
if($_REQUEST['action']=='cm_agent_subtotal')
{
	$agent_type = urldecode($_REQUEST['agent_type']);
	$sql_status="SELECT `cm_broker_agent` as agent,count(`cm_broker_agent`) as total ,group_concat( complaint_id ) as complaint_ids, group_concat( `cm_broker_agent` ) AS agents FROM `sdms_ticket_capital_markets` WHERE cm_broker_agent!='' AND cm_type ='".$agent_type."'   group by `cm_broker_agent` order by total desc limit 10";

	
	$res_status=mysql_query($sql_status);
	while($row_status=mysql_fetch_array($res_status)){
	$complaint_ids .= $row_status['complaint_ids'].',';
	}
	$complaint_ids = rtrim($complaint_ids,",");
	$qwhere .=" AND  ticket.ticket_id IN(".$complaint_ids.") ";
	$qstr.='&action='.$_REQUEST['action'].'&agent_type='.$_REQUEST['agent_type'];

}
if($_REQUEST['action']=='cm_complainant')
{
	
$sql_status="SELECT  count(ticket_id) as total,group_concat( ticket_id ) as complaint_ids,`email` FROM `sdms_ticket` WHERE  email!='' AND dept_id = '2' AND isquery=0 ".$from_to_date." AND email NOT LIKE '%@novalidemail.pk' AND email = '".urldecode($_REQUEST['email'])."' group by `email` order by total desc limit 0,5";
$res_status=mysql_query($sql_status);
$row_status=mysql_fetch_array($res_status);
$qwhere .=" AND  ticket.ticket_id IN(".$row_status['complaint_ids'].") ";
$qstr.='&action='.$_REQUEST['action'].'&email='.$_REQUEST['email'];
}
if($_REQUEST['action']=='cm_complainant_subtotal')
{
	$sql_status="SELECT  count(ticket_id) as total,group_concat( ticket_id ) as complaint_ids,`email` FROM `sdms_ticket` WHERE  email!='' AND dept_id = '2' AND isquery=0 ".$from_to_date." AND email NOT LIKE '%@novalidemail.pk' group by `email` order by total desc limit 0,5";
	$res_status=mysql_query($sql_status);
	while($row_status=mysql_fetch_array($res_status)){
	$complaint_ids .= $row_status['complaint_ids'].',';
	}
	$complaint_ids = rtrim($complaint_ids,",");
	$qwhere .=" AND  ticket.ticket_id IN(".$complaint_ids.") ";
	$qstr.='&action='.$_REQUEST['action'];
}

//cr_company 	||  cr_company_subtotal 
//cr_agent   	|| cr_agent_subtotal
//cr_complainant || cr_complainant_subtotal
if($_REQUEST['action']=='cr_company')
{
	$compay = urldecode($_REQUEST['compay']);
	$broker_title = "cr_company_title!=''";
			
$sql_status="SELECT cr_company_title as compay,count(cr_company_title) as total ,group_concat( complaint_id ) as complaint_ids, group_concat( cr_company_title) AS companies FROM `sdms_ticket_cr` WHERE ".$broker_title."  AND cr_company_title = '".$compay."' group by `cr_company_title`  order by total desc limit 10 ";
$res_status=mysql_query($sql_status);
$row_status=mysql_fetch_array($res_status);
$qwhere .=" AND  ticket.ticket_id IN(".$row_status['complaint_ids'].") ";
$qstr.='&action='.$_REQUEST['action'].'&compay='.$_REQUEST['compay'].'';
}
if($_REQUEST['action']=='cr_company_subtotal')
{
	
	$broker_title = "cr_company_title!=''";
	
	$sql_status="SELECT count(cr_company_title) as total,group_concat( complaint_id ) as complaint_ids 
	FROM `sdms_ticket_cr` WHERE ".$broker_title." group by `cr_company_title` order by total desc limit 10";
	$res_status=mysql_query($sql_status);
	while($row_status=mysql_fetch_array($res_status)){
	$complaint_ids .= $row_status['complaint_ids'].',';
	}
	$complaint_ids = rtrim($complaint_ids,",");
	$qwhere .=" AND  ticket.ticket_id IN(".$complaint_ids.") ";
	$qstr.='&action='.$_REQUEST['action'];
}
if($_REQUEST['action']=='cr_agent')
{
	
	$agent = urldecode($_REQUEST['agent']);

$sql_status="SELECT `cr_cro` as agent,count(`cr_cro`) as total ,group_concat( complaint_id ) as complaint_ids, group_concat( `cr_cro` ) AS agents FROM `sdms_ticket_cr` WHERE cr_cro='".$agent."'  group by `cr_cro` order by total desc limit 10";

$res_status=mysql_query($sql_status);
$row_status=mysql_fetch_array($res_status);
$qwhere .=" AND  ticket.ticket_id IN(".$row_status['complaint_ids'].") ";
$qstr.='&action='.$_REQUEST['action'].'&agent='.$_REQUEST['agent'];
}
if($_REQUEST['action']=='cr_agent_subtotal')
{
	$sql_status="SELECT `cr_cro` as agent,count(`cr_cro`) as total ,group_concat( complaint_id ) as complaint_ids, group_concat( `cr_cro` ) AS agents FROM `sdms_ticket_cr` WHERE cr_cro!=''  group by `cr_cro` order by total desc limit 10";

	$res_status=mysql_query($sql_status);
	while($row_status=mysql_fetch_array($res_status)){
	$complaint_ids .= $row_status['complaint_ids'].',';
	}
	$complaint_ids = rtrim($complaint_ids,",");
	$qwhere .=" AND  ticket.ticket_id IN(".$complaint_ids.") ";
	$qstr.='&action='.$_REQUEST['action'];

}
if($_REQUEST['action']=='cr_complainant')
{
	
$sql_status="SELECT  count(ticket_id) as total,group_concat( ticket_id ) as complaint_ids,`email` FROM `sdms_ticket` WHERE  email!='' AND dept_id = '6' AND isquery=0 ".$from_to_date." AND email NOT LIKE '%@novalidemail.pk' AND email = '".urldecode($_REQUEST['email'])."' group by `email` order by total desc limit 0,5";
$res_status=mysql_query($sql_status);
$row_status=mysql_fetch_array($res_status);
$qwhere .=" AND  ticket.ticket_id IN(".$row_status['complaint_ids'].") ";
$qstr.='&action='.$_REQUEST['action'].'&email='.$_REQUEST['email'];

}
if($_REQUEST['action']=='cr_complainant_subtotal')
{
	$sql_status="SELECT  count(ticket_id) as total,group_concat( ticket_id ) as complaint_ids,`email` FROM `sdms_ticket` WHERE  email!='' AND dept_id = '6' AND isquery=0 ".$from_to_date." AND email NOT LIKE '%@novalidemail.pk' group by `email` order by total desc limit 0,5";
	$res_status=mysql_query($sql_status);
	while($row_status=mysql_fetch_array($res_status)){
	$complaint_ids .= $row_status['complaint_ids'].',';
	}
	$complaint_ids = rtrim($complaint_ids,",");
	$qwhere .=" AND  ticket.ticket_id IN(".$complaint_ids.") ";
	$qstr.='&action='.$_REQUEST['action'];
}



if($_REQUEST['r_type']!='')
{
	$from_date = $_REQUEST['from_date'];
	$to_date = $_REQUEST['to_date'];
	
	if($_REQUEST['r_type']=='1to15days')
	{//&r_type=1to15days&from_date='.$today_date.'&to_date='.$days_1to15.'';
	$qstr.='&r_type='.$_REQUEST['r_type'].'&from_date='.$_REQUEST['from_date'].'&to_date='.$_REQUEST['to_date'].'';
	$qwhere .=" AND DATE(ticket.created) <=  '".$from_date."' AND DATE(ticket.created) > '".$to_date."'";
	}
	
	if($_REQUEST['r_type']=='16to30days')
	{
		$qstr.='&r_type='.$_REQUEST['r_type'].'&from_date='.$_REQUEST['from_date'].'&to_date='.$_REQUEST['to_date'].'';	
		$qwhere .=" AND DATE(ticket.created) <=  '".$from_date."' AND DATE(ticket.created) > '".$to_date."'";
	}
	if($_REQUEST['r_type']=='31to45days')
	{
		$qstr.='&r_type='.$_REQUEST['r_type'].'&from_date='.$_REQUEST['from_date'].'&to_date='.$_REQUEST['to_date'].'';	
		$qwhere .=" AND DATE(ticket.created) <=  '".$from_date."' AND DATE(ticket.created) > '".$to_date."'";
	}
	if($_REQUEST['r_type']=='45daysplus')
	{
		$qstr.='&r_type='.$_REQUEST['r_type'].'&from_date='.$_REQUEST['from_date'];	
		$qwhere .=" AND DATE(ticket.created) <=  '".$from_date."'";
	}
}


//STATUS
if($mark_complaint) {
	if(!$thisstaff->isAdmin())
	$qwhere .=' AND ticket.dept_id = '.$thisstaff->getDeptId().'	AND  ticket.isaccepted=1 ';   
	//echo $qwhere;exit;
//    $qwhere.=' AND ticket.staff_id != '.$thisstaff->getId().' AND ticket.status='.db_input(strtolower($status));    

}
elseif($status) {
if($status=='lodged')
{
	    $qwhere.=' AND ticket.isaccepted=0';    
}
elseif($status=='deny')
{
	    $qwhere.=' AND ticket.isrejected = 1 ';    
}else{
if($_REQUEST['action'] == 'report' ){
	    $qwhere.=' AND ticket.status='.db_input(strtolower($status));    

}else {
	    $qwhere.=' AND ticket.status='.db_input(strtolower($status)).' AND  ticket.isaccepted=1';    
}
}
}

//Queues: Overloaded sub-statuses  - you've got to just have faith!
if($staffId && ($staffId==$thisstaff->getId())) { //My Complaints
    $results_type='Assigned Complaints';
    $qwhere.=' AND ticket.staff_id='.db_input($staffId);
    $showassigned=false; //My tickets...already assigned to the staff.
}elseif($showoverdue) { //overdue
    $qwhere.=' AND isoverdue=1 ';
}elseif($showanswered) { ////Answered
    $qwhere.=' AND isanswered=1 ';
}elseif(!strcasecmp($status, 'open') && !$search) { //Open queue (on search OPEN means all open Complaints - regardless of state).
    //Showing answered Complaints on open queue??
    if(!$cfg->showAnsweredTickets()) 
       // $qwhere.=' AND isanswered=0 ';
    /* Showing assigned tickets on open queue? 
       Don't confuse it with show assigned To column -> F'it it's confusing - just trust me!
     */

    if(!($cfg->showAssignedTickets() || $thisstaff->showAssignedTickets())) {
        $qwhere.=' AND ticket.staff_id=0 '; //XXX: NOT factoring in team assignments - only staff assignments.
        $showassigned=false; //Not showing Assigned To column since assigned tickets are not part of open queue
    }
}
  $qwhere.=' AND ticket.isquery = 0 ';  
  if(!$thisstaff->isAdmin())
  $qwhere .=' AND ticket.dept_id = '.$thisstaff->getDeptId(); 

//Search?? Somebody...get me some coffee 

$deep_search = false;
if($search):
    $qstr.='&a='.urlencode($_REQUEST['a']);
    $qstr.='&t='.urlencode($_REQUEST['t']);
    //query
    if($searchTerm){
        $qstr.='&query='.urlencode($searchTerm);
        $queryterm=db_real_escape($searchTerm,false); //escape the term ONLY...no quotes.
        if(is_numeric($searchTerm)){
            $qwhere.=" AND ticket.ticketID =  '".$queryterm."'";
        }elseif(strpos($searchTerm,'@') && Validator::is_email($searchTerm)){ //pulling all tricks!
            # XXX: What about searching for email addresses in the body of
            #      the thread message
            $qwhere.=" AND ticket.email='$queryterm'";
        }else{//Deep search!
            //This sucks..mass scan! search anything that moves! 
            $deep_search=true;
            if($_REQUEST['stype'] && $_REQUEST['stype']=='FT') { //Using full text on big fields.
                $qwhere.=" AND ( ticket.email LIKE '%$queryterm%'".
                            " OR ticket.name LIKE '%$queryterm%'".
                            " OR ticket.nic LIKE '%$queryterm%'".
                            " OR ticket.subject LIKE '%$queryterm%'".
                            " OR thread.title LIKE '%$queryterm%'".
                            " OR MATCH(thread.body)   AGAINST('$queryterm')".
                            ' ) ';
            }else{

                $qwhere.=" AND ( ticket.email LIKE '%$queryterm%'".
                            " OR ticket.name LIKE '%$queryterm%'".
                            " OR ticket.nic LIKE '%$queryterm%'".
                            " OR ticket.subject LIKE '%$queryterm%'".
                            " OR thread.body LIKE '%$queryterm%'".
                            " OR thread.title LIKE '%$queryterm%'".
                            ' ) ';

            }

        }

    }

    //Respondent Name

    if($_REQUEST['respondent']) {

        //This is dept based search..perm taken care above..put the sucker in.

        $qwhere.=" AND ticket.name LIKE '%".$_REQUEST['respondent']."%'";

        $qstr.='&comp_against='.urlencode($_REQUEST['respondent']);

    }
	

	//Applicant Father's Name:

    if($_REQUEST['f_name']) {

        //This is dept based search..perm taken care above..put the sucker in.

        $qwhere.=' AND ticket.relation_name='.db_input($_REQUEST['f_name']);

        $qstr.='&relation_name='.urlencode($_REQUEST['f_name']);

    }

	

	//Applicant CNIC:

    if($_REQUEST['cnic']) {

        //This is dept based search..perm taken care above..put the sucker in.

        $qwhere.=' AND ticket.nic='.db_input($_REQUEST['cnic']);

        $qstr.='&nic='.urlencode($_REQUEST['cnic']);

    }

	

	//Applicant Cell Nos:

    if($_REQUEST['cell_nos']) {

        //This is dept based search..perm taken care above..put the sucker in.

        $qwhere.=' AND ticket.phone='.db_input($_REQUEST['cell_nos']);

        $qstr.='&phone='.urlencode($_REQUEST['cell_nos']);

    }

	

	//department

    if($_REQUEST['deptId'] && in_array($_REQUEST['deptId'],$thisstaff->getDepts())) {

        //This is dept based search..perm taken care above..put the sucker in.

        $qwhere.=' AND ticket.dept_id='.db_input($_REQUEST['deptId']);

        $qstr.='&deptId='.urlencode($_REQUEST['deptId']);

    }

    //Help topic

    if($_REQUEST['topicId']) {

        $qwhere.=' AND ticket.topic_id='.db_input($_REQUEST['topicId']);

        $qstr.='&topicId='.urlencode($_REQUEST['topicId']);

    }

	

	////////////complaint status

	 if($_REQUEST['cstatus']) {

        $qwhere.=' AND ticket.complaint_status='.db_input($_REQUEST['cstatus']);

        $qstr.='&cstatus='.urlencode($_REQUEST['cstatus']);

    }

     
   

    //Assignee
    if(isset($_REQUEST['assignee']) && strcasecmp($_REQUEST['status'], 'closed'))  {
        $id=preg_replace("/[^0-9]/", "", $_REQUEST['assignee']);
        $assignee = $_REQUEST['assignee'];
        $qstr.='&assignee='.urlencode($_REQUEST['assignee']);
        $qwhere.= ' AND ( 
                ( ticket.status="open" ';                
        if($assignee[0]=='t')
            $qwhere.='  AND ticket.team_id='.db_input($id);
        elseif($assignee[0]=='s')
            $qwhere.='  AND ticket.staff_id='.db_input($id);
        elseif(is_numeric($id))
            $qwhere.='  AND ticket.staff_id='.db_input($id);   

       $qwhere.=' ) ';


        if($_REQUEST['staffId'] && !$_REQUEST['status']) { //Assigned TO + Closed By

            $qwhere.= ' OR (ticket.staff_id='.db_input($_REQUEST['staffId']). ' AND ticket.status="closed") ';

            $qstr.='&staffId='.urlencode($_REQUEST['staffId']);

        }elseif(isset($_REQUEST['staffId'])) {
            $qwhere.= ' OR ticket.status="closed" ';
            $qstr.='&staffId='.urlencode($_REQUEST['staffId']);

        }

            

        $qwhere.= ' ) ';

    
	}

	 elseif($_REQUEST['staffId']) {

        $qwhere.=' AND (ticket.staff_id='.db_input($_REQUEST['staffId']).' AND ticket.status="closed") ';
        $qstr.='&staffId='.urlencode($_REQUEST['staffId']);

    }

    //dates

    $startTime  =($_REQUEST['startDate'] && (strlen($_REQUEST['startDate'])>=8))?strtotime($_REQUEST['startDate']):0;

    $endTime    =($_REQUEST['endDate'] && (strlen($_REQUEST['endDate'])>=8))?strtotime($_REQUEST['endDate']):0;

    if( ($startTime && $startTime>time()) or ($startTime>$endTime && $endTime>0)){

        $errors['err']='Entered date span is invalid. Selection ignored.';

        $startTime=$endTime=0;

    }else{

if($startTime == $endTime && $startTime !=0 ){
	
	$qwhere.=' AND DATE(ticket.created) >= "'.date('Y-m-d',strtotime($_REQUEST['startDate'])).'"';

            $qstr.='&startDate='.urlencode($_REQUEST['startDate']);
			
	$qwhere.=' AND DATE(ticket.created) <= "'.date('Y-m-d',strtotime($_REQUEST['endDate'])).'"';

            $qstr.='&endDate='.urlencode($_REQUEST['endDate']);
	
	}else{
	

        //Have fun with dates.

        if($startTime){

            $qwhere.=' AND ticket.created>=FROM_UNIXTIME('.$startTime.')';

            $qstr.='&startDate='.urlencode($_REQUEST['startDate']);

                        

        }

        if($endTime){

            $qwhere.=' AND ticket.created<=FROM_UNIXTIME('.$endTime.')';

            $qstr.='&endDate='.urlencode($_REQUEST['endDate']);

        }

	}
   }

endif;

$sortOptions=array('date'=>'ticket.created','ID'=>'ticketID','pri'=>'priority_urgency','name'=>'ticket.name',

                   'subj'=>'ticket.subject','status'=>'ticket.status','assignee'=>'assigned','staff'=>'staff',

                   'dept'=>'dept_name');

$orderWays=array('DESC'=>'DESC','ASC'=>'ASC');

//Sorting options...

$queue = isset($_REQUEST['status'])?strtolower($_REQUEST['status']):$status;

$order_by=$order=null;

if($_REQUEST['sort'] && $sortOptions[$_REQUEST['sort']])

    $order_by =$sortOptions[$_REQUEST['sort']];

elseif($sortOptions[$_SESSION[$queue.'_tickets']['sort']]) {

    $_REQUEST['sort'] = $_SESSION[$queue.'_tickets']['sort'];

    $_REQUEST['order'] = $_SESSION[$queue.'_tickets']['order'];

    $order_by = $sortOptions[$_SESSION[$queue.'_tickets']['sort']];

    $order = $_SESSION[$queue.'_tickets']['order'];

}

if($_REQUEST['order'] && $orderWays[strtoupper($_REQUEST['order'])])

    $order=$orderWays[strtoupper($_REQUEST['order'])];

//Save sort order for sticky sorting.

if($_REQUEST['sort'] && $queue) {

    $_SESSION[$queue.'_tickets']['sort'] = $_REQUEST['sort'];

    $_SESSION[$queue.'_tickets']['order'] = $_REQUEST['order'];

}

//Set default sort by columns.

if(!$order_by ) {

    if($showanswered) 

        $order_by='ticket.lastresponse, ticket.created'; //No priority sorting for answered tickets.

    elseif(!strcasecmp($status,'closed'))

        $order_by='ticket.closed, ticket.created'; //No priority sorting for closed tickets.

    elseif($showoverdue) //priority> duedate > age in ASC order.

        $order_by='priority_urgency ASC, ISNULL(duedate) ASC, duedate ASC, effective_date ASC, ticket.created';

    else //XXX: Add due date here?? No - 

        $order_by='priority_urgency ASC, effective_date DESC, ticket.created';

}

$order=$order?$order:'DESC';

if($order_by && strpos($order_by,',') && $order)

    $order_by=preg_replace('/(?<!ASC|DESC),/', " $order,", $order_by);

$sort=$_REQUEST['sort']?strtolower($_REQUEST['sort']):'urgency'; //Urgency is not on display table.

$x=$sort.'_sort';

$$x=' class="'.strtolower($order).'" ';

if($_GET['limit'])

    $qstr.='&limit='.urlencode($_GET['limit']);

$qselect ='SELECT DISTINCT ticket.ticket_id,lock_id,ticket.complaint_status,ticket_voice,ticketID,ticket.dept_id,dist.*,ticket.district,ticket.staff_id,ticket.closed,ticket.team_id,ticket.ishandel_reassign '

         .' ,ticket.subject,ticket.name,ticket.email,dept_name,reopened '

         .' ,ticket.status,ticket.source,isoverdue,isanswered,ticket.created,pri.*';

$qfrom=' FROM '.TICKET_TABLE.' ticket '.
       ' LEFT JOIN '.DEPT_TABLE.' dept ON ticket.dept_id=dept.dept_id '.
	   ' LEFT JOIN '.DISTRICTS_TABLE.' dist ON ticket.district=dist.District_ID ';
$sjoin='';
if($search && $deep_search) {
    $sjoin=' LEFT JOIN '.TICKET_THREAD_TABLE.' thread ON (ticket.ticket_id=thread.ticket_id )';
}
$qgroup=' GROUP BY ticket.ticket_id';

//get ticket count based on the query so far..

$total=db_count("SELECT count(DISTINCT ticket.ticket_id) $qfrom $sjoin $qwhere");

//pagenate

$pagelimit=($_GET['limit'] && is_numeric($_GET['limit']))?$_GET['limit']:PAGE_LIMIT;

$page=($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;

$pageNav=new Pagenate($total,$page,$pagelimit);

$pageNav->setURL('report_tickets.php',$qstr.'&sort='.urlencode($_REQUEST['sort']).'&order='.urlencode($_REQUEST['order']));

//ADD attachment,priorities, lock and other crap

$qselect.=' ,count(attach.attach_id) as attachments '

         .' ,count(DISTINCT thread.id) as thread_count '

         .' ,IF(ticket.duedate IS NULL,IF(sla.id IS NULL, NULL, DATE_ADD(ticket.created, INTERVAL sla.grace_period HOUR)), ticket.duedate) as duedate '

         .' ,IF(ticket.reopened is NULL,IF(ticket.lastmessage is NULL,ticket.created,ticket.lastmessage),ticket.reopened) as effective_date '

         .' ,CONCAT_WS(" ", staff.firstname, staff.lastname) as staff, team.name as team '

         .' ,IF(staff.staff_id IS NULL,team.name,CONCAT_WS(" ", staff.lastname, staff.firstname)) as assigned '

         .' ,IF(ptopic.topic_pid IS NULL, topic.topic, CONCAT_WS(" / ", ptopic.topic, topic.topic)) as helptopic ';

$qfrom.=' LEFT JOIN '.TICKET_PRIORITY_TABLE.' pri ON (ticket.priority_id=pri.priority_id) '

       .' LEFT JOIN '.TICKET_LOCK_TABLE.' tlock ON (ticket.ticket_id=tlock.ticket_id AND tlock.expire>NOW() 

               AND tlock.staff_id!='.db_input($thisstaff->getId()).') '

       .' LEFT JOIN '.TICKET_ATTACHMENT_TABLE.' attach ON (ticket.ticket_id=attach.ticket_id) '

       .' LEFT JOIN '.TICKET_THREAD_TABLE.' thread ON ( ticket.ticket_id=thread.ticket_id) '

       .' LEFT JOIN '.STAFF_TABLE.' staff ON (ticket.staff_id=staff.staff_id) '

       .' LEFT JOIN '.TEAM_TABLE.' team ON (ticket.team_id=team.team_id) '

       .' LEFT JOIN '.SLA_TABLE.' sla ON (ticket.sla_id=sla.id AND sla.isactive=1) '

       .' LEFT JOIN '.TOPIC_TABLE.' topic ON (ticket.topic_id=topic.topic_id) '

	   
	  // .' INNER JOIN '.STATUS_TABLE.' stat ON (ticket.complaint_status=stat.status_id) ' 
       .' LEFT JOIN '.TOPIC_TABLE.' ptopic ON (ptopic.topic_id=topic.topic_pid) ';
//$query="$qselect $qfrom $qwhere $qgroup ORDER BY $order_by $order LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();

$seccion_query = "$qselect $qfrom $qwhere $qgroup ORDER BY ticket.ticket_id DESC";
$_SESSION['complaitns_list_session'] = $seccion_query;
$query="$qselect $qfrom $qwhere $qgroup ORDER BY ticket.ticket_id DESC LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
if($_REQUEST['ahsan']=='show')
{
	echo $query;
}
$hash = md5($query);

$_SESSION['search_'.$hash] = $query;

$res = db_query($query);

$showing=db_num_rows($res)?$pageNav->showing():"";

if(!$results_type)

    $results_type = ucfirst($status).' Complaints';

if($search)

    $results_type.= ' (Search Results)';

$negorder=$order=='DESC'?'ASC':'DESC'; //Negate the sorting..

//YOU BREAK IT YOU FIX IT.

?>
<div class="page-header">
  <h1>Complaints <small></small></h1>
</div>
<div class="row-fluid">
  <div class="span4" style="float:left">
    <form action="tickets.php" method="get">
      <?php  //csrf_token(); ?>
      <input type="hidden" name="a" value="search">
      <div class="dataTables_filter" id="">
        <input type="text" id="basic-ticket-search" name="query" size=30 value="<?php //echo Format::htmlchars($_REQUEST['query']); ?>"

        autocomplete="off" autocorrect="off" autocapitalize="off" class="mine_input">
        <input type="submit" name="basic_search" class="btn" value="Search">
      </div>
    </form>
  </div>
  <div class="span2" style="float:right">
    <p align="right" style="float:right;" >
      <button class="btn" type="button" id="popup_4"><i class="icon-search"></i>Advanced Search</button>
    </p>
  </div>
  <div class="span2" style="float:right;">
   <!-- <p align="right" style="float:right;"> <a href="complaints_lisitng.csv">-->
     <p align="right" style="float:right;"> <a href="export_complaints.php">
      <button class="btn" type="button" ><i class="icon-print"></i> Export</button>
      </a> </p>
  </div>
</div>
<div class="row-fluid">
  <div class="span12" style="float:right;">
    <a href="tickets.php?action=assigned_complaints"><p align="right" style="float:right;padding-left:20px;"><span class="box_new assigned_new">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Assigned Complaint</p></a>
    
    <a href="tickets.php?action=handled_complaints"><p align="right" style="float:right;padding-left:20px;"><span class="box_new handled_new">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Handled Complaint</p></a>
    
    <a href="tickets.php?action=pending_complaints"><p align="right" style="float:right;padding-left:20px;"><span class="box_new pending_new">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Pending Complaint</p></a>
    
    <?php if($thisstaff->isFocalPerson())
{ ?>
    <a href="tickets.php?action=pending_handled_complaints"><p align="right" style="float:right;padding-left:20px;"><span class="box_new pending_handled_new">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Pending Handled Complaint</p></a>
 <?php } ?>   
  </div>
</div>
<form action="tickets.php" method="POST" name='tickets' id="status-form">
  <?php csrf_token(); ?>
  <input type="hidden" name="a" value="mass_process" >
  <input type="hidden" name="do" id="action" value="" >
  <input type="hidden" name="status" value="<?php echo Format::htmlchars($_REQUEST['status']); ?>" >
  <div class="row-fluid">
    <div class="span12">
      <div class="head clearfix">
        <div class="isw-grid"></div>
        <h1><?php echo $results_type; ?></h1>
      </div>
      <div class="block-fluid table-sorting clearfix">
        <table cellpadding="0" cellspacing="0" width="100%" class="table"  >
          <thead>
            <tr>
              <?php if($thisstaff->canManageTickets()) { ?>
              <th><input type="checkbox" name="checkall"/></th>
              <?php } ?>
              <th width="15%">Complaint ID</th>
              <?php //$csv.='"Complaint ID"'.','; ?>
              <th width="10%">Date</th>
              <?php //$csv.='"Date"'.','; ?>
              <th width="14%">Subject</th>
              <?php //$csv.='"Subject"'.','; ?>
              <th width="14%">Submitted By</th>
              <?php //$csv.='"Submitted By"'.','; ?>
              <th width="14%">Assigned to</th>
              <?php //$csv.='"Assigned to"'.','; ?>
              <!--<th width="14%">Location</th>-->
              <?php //$csv.='"Location"'.','; ?>
              <th width="19%">Sub Status</th>
              <th width="19%">Status</th>
              <?php //$csv.='"Status"'.','; ?>
              <?php  
                if($thisstaff->isAdmin())
                {?>
              <th width="14%">Voice</th>
              <?php //$csv.='"Voice"'.','; ?>
              <?php }
                else{?>
              <th width="14%"># of Days</th>
              <?php //$csv.='"# of Days"'.','; ?>
              <?php }?>
            </tr>
          </thead>
          <tbody role="alert" aria-live="polite" aria-relevant="all">
            <?php
//$csv.="\n";
        $class = "row1";

        $total=0;

        if($res && ($num=db_num_rows($res))):

			$ids=($errors && $_POST['tids'] && is_array($_POST['tids']))?$_POST['tids']:null;

            $color = 1;

            while ($row = db_fetch_array($res)) {

                if(($color%2) == 0)

                $bg = 'odd';
                else
                $bg = 'even';

                $tag=$row['staff_id']?'assigned':'openticket';

                $flag=null;

                if($row['lock_id'])

                    $flag='locked';

                elseif($row['isoverdue'])
                    $flag='overdue';
                $lc='';
                if($showassigned) {

                    if($row['staff_id'])

                        $lc=sprintf('<span class="Icon staffAssigned">%s</span>',Format::truncate($row['staff'],40));

                    elseif($row['team_id'])

                        $lc=sprintf('<span class="Icon teamAssigned">%s</span>',Format::truncate($row['team'],40));

                    else

                        $lc=' ';

                }else{

                    $lc=Format::truncate($row['dept_name'],40);

                }

                $tid=$row['ticketID'];

                $subject = Format::truncate($row['subject'],40);

                $threadcount=$row['thread_count'];

                if(!strcasecmp($row['status'],'open') && !$row['isanswered'] && !$row['lock_id']) {

                    $tid=sprintf('<b>%s</b>',$tid);

                }
if($row['reopened']!=''){ 
$date1 =  $row['reopened'];
}
else {
$date1 = $row['created'];
}

if($row['status'] == 'closed' )
{
$date2 = $row['closed'];
}else{
$date2 = date('Y-m-d H:i:s');
}
/*echo $row['ticket_id'].'<br>';
echo $date1.'<br>';
echo $date2.'<br>';exit;*/


$total_days = round(abs(strtotime($date2)-strtotime($date1))/86400);


$sql_checking_activity = "Select * from sdms_ticket_thread where ticket_id = '".$row['ticket_id']."' AND (thread_type = 'N' OR thread_type = 'R')  AND title 
NOT LIKE 'Complaint Assigned to%'";
$res_checking_activity = mysql_query($sql_checking_activity);
$num_checking_activity =  mysql_num_rows($res_checking_activity);

if($row['ishandel_reassign']=='1')
{
$alret='style="background-color: antiquewhite !important;"';//Pending Handled Complaint
}
elseif($num_checking_activity>0 && $thisstaff->getId()==$row['staff_id'])
{
$alret='style="background-color: #d8bfd8 !important;"';//Handled Complaint
}elseif($thisstaff->getId()!=$row['staff_id']){
$alret='style="background-color: #e1ffea !important;"';//Assigned Complaint
}else
{
$alret='';
}
 // $csv .='<br>';
?>
            <tr id="<?php echo $row['ticket_id']; ?>" >
              <?php if($thisstaff->canManageTickets()) {                               
                $sel=false;
                if($ids && in_array($row['ticket_id'], $ids))
                    $sel=true;
                ?>
              <td <?php echo $alret; ?> ><input type="checkbox" name="tids[]" value="<?php echo $row['ticket_id']; ?>"/></td>
              <?php } ?>
              <td <?php echo $alret; ?> title="<?php echo $row['email']; ?>" nowrap><a class="Icon <?php echo strtolower($row['source']); ?>Ticket ticketPreview" title="Preview Complaint" href="tickets.php?id=<?php echo $row['ticket_id']; ?>"><?php echo $tid; ?></a>
                <?php  if($thisstaff->isAdmin() || $thisstaff->getGroupId()=='7' ) {?>
                <span onClick="send_sms(<?php echo $row['ticket_id']; ?>)"><img id="send_sms_image" src="img/send_sms-128.png" style="width:30px;height:30px;" />&nbsp;&nbsp;</span>
                <?php }?>
                <?php //$csv .= '"' . $row['ticket_id'] . '",'; ?></td>
              <td nowrap <?php echo $alret; ?>><?php echo Format::db_date($row['created']); ?>
                <?php //$csv .= '"' . Format::db_date($row['created']); ?>
                <?php if($row['reopened']!=''){ ?>
                <br>
                <span class="label label-important">Reopen: <?php echo Format::db_date($row['reopened']); ?></span>
                <?php //$csv .=  '(Reopen:'. Format::db_date($row['created']).')' ; ?>
                <?php } 
				//$csv .=  '",';
				?></td>
              <td <?php echo $alret; ?>><?php if($thisstaff->canCreateTickets() && !$thisstaff->isAdmin()) {?>
                <?php echo $subject; ?>
                <?php }else{?>
                <a <?php if($flag) { ?> class="Icon <?php echo $flag; ?>Ticket" title="<?php echo ucfirst($flag); ?> Complaint " <?php } ?> href="tickets.php?id=<?php echo $row['ticket_id']; ?>"><?php echo $subject; ?>
                <?php //$csv .= '"' .$subject. '",'; ?>
                </a> &nbsp; <?php echo ($threadcount>1)?" <small>($threadcount)</small>&nbsp;":''?> <?php echo $row['attachments']?"<span class='Icon file'>&nbsp;</span>":''; ?></td>
              <?php }?>
              <td nowrap <?php echo $alret; ?>>&nbsp;
                <?php 
		  if($row['name']==''){
			  echo 'Anonymous'.'<br>'.'('.$row['source'].')';
			 //$csv .= '"' .'Anonymous:'. $row['source'] . '",'; 
			  }else{
		  echo Format::truncate($row['name'],22,strpos($row['name'],'@')).'<br>'.'('.$row['source'].')';
		   //$csv .= '"' . Format::truncate($row['name'],22,strpos($row['name'],'@')).' ('. $row['source'].')'. '",'; 
		  } ?>
                &nbsp;</td>
              <td><?php echo $row['staff'];
			  //$csv .= '"'.$row['staff'].'",'; 
			   ?></td>
              <!--<td><?php 
			//  echo $row['District'];
			    //$csv .= '"'.$row['District'].'",'; 
			   ?></td>-->
              <?php /*?><td style="background-color:<?php echo $row['priority_color']; ?>;"><?php if($thisstaff->isAdmin()) {?>
                <select name="priority_id" onChange="return update_priority(<?php echo $row['ticket_id']; ?>,this.value);">
                  <option value="">--Select Priority--</option>
                  <?php 
            $sql_priorities = "Select * from sdms_ticket_priority where 1";
            $res_priorities = mysql_query($sql_priorities);
            while($row_priorities = mysql_fetch_array($res_priorities)){?>
                  <option value="<?php echo $row_priorities['priority_id']; ?>" <?php if($row_priorities['priority_id']==$row['priority_id']){ ?> selected <?php } ?>><?php echo $row_priorities['priority_desc']; ?></option>
                  <?php 	}	  ?>
                </select>
                <?php }else{?>
                <?php echo $row['priority_desc']; ?>
                <?php }?></td><?php */?>
              <td <?php echo $alret; ?>><?php 

		 $sql_fetchstatus = "Select * from sdms_status where status_id='".$row['complaint_status']."'";
		  $res_fetchstatus = mysql_query($sql_fetchstatus);
		  $row_fetchstatus = mysql_fetch_array($res_fetchstatus);
		  
		  $sql_fetchpstatus = "Select * from sdms_status where status_id='".$row_fetchstatus['p_id']."'";
		  $res_fetchpstatus = mysql_query($sql_fetchpstatus);
		  $row_fetchpstatus = mysql_fetch_array($res_fetchpstatus);
		  
		  echo $row_fetchstatus['status_title'];
		  //$csv .= '"'.$row_fetchpstatus['status_title']; 
		  
		  //$csv .= '",';
		  ?>
                <?php //echo $row['status']; ?></td>
                <td><?php echo $row_fetchpstatus['status_title']; 
				if($row['status'] == 'closed' )
		  {
		  //$csv .= '('.date('d/m/Y',strtotime($row['closed'])).')'; 
		  echo '<br>'.date('d/m/Y',strtotime($row['closed']));
		  }?>  </td>
              <?php  
	if($thisstaff->isAdmin())
	{?>
              <td nowrap <?php echo $alret; ?>>&nbsp;
                <?php if($row['ticket_voice']!=''){ ?>
                <a href="<?php echo $row['ticket_voice']; ?>" target="_blank"><img src="img/play.png" /></a> 
                
                <!-- <a href="force_download.php?file=<?php // echo $row['ticket_voice'];?>"  ><img src="img/download.png" /></a> 
                --> 
                <!--<a href="<?php //echo $row['ticket_voice']; ?>" download="<?php //echo $row['ticket_id'];?>.wav" ><img src="img/download.png" /></a>-->
                
                <?php }?></td>
              <?php }else{ 	?>
              <td nowrap <?php echo $alret; ?>>&nbsp;<?php echo $total_days .' Days';
			  //$csv .= '"'.$total_days.' Days'.'",'; 
			  
			   ?></td>
              <?php }?>
            </tr>
            <?php
        $color ++;
      //$csv .='<br>';
	     //$csv.="\n";
	    } //end of while.

    else: //not tickets found!! set fetch error.

        $ferror='There are no Complaint s here. (Leave a little early today).';  

    endif; ?>
          </tbody>
          <?php if($num>0){  ?>
          <tfoot>
            <tr>
              <td colspan="4"><?php echo '<div align="left" style="width:40%;float:left;">'.$showing .'&nbsp;'.'&nbsp;'.'&nbsp;'.'</div>';?></td>
              <td colspan="5" align="right"><?php echo '<div class="dataTables_paginate paging_full_numbers">Page:'.$pageNav->getPageLinks().'</div>'; ?></td>
            </tr>
          </tfoot>
          <?php }?>
        </table>
      </div>
    </div>
  </div>
  

 

		<?php /*?>$csv='';
		$csv.='"Complaints List"'.',';	
		$csv.="\n";				 		 		
		$csv.='"Complaint ID"'.','; 
		$csv.='"Date"'.','; 
		$csv.='"Subject"'.','; 
		$csv.='"Submitted By"'.','; 
		$csv.='"Assigned to"'.','; 
		$csv.='"Location"'.',';
		$csv.='"Status"'.','; 		
		if($thisstaff->isAdmin())
		{
		$csv.='"Voice"'.','; 
		}
		else{
		$csv.='"# of Days"'.','; 
		}

$csv.="\n";

$total=0;
$query="$qselect $qfrom $qwhere $qgroup ORDER BY ticket.ticket_id DESC";
$res = db_query($query);
if($res && ($num=db_num_rows($res))):
$ids=($errors && $_POST['tids'] && is_array($_POST['tids']))?$_POST['tids']:null;
while ($row = db_fetch_array($res)) {
$tag=$row['staff_id']?'assigned':'openticket';
$flag=null;
if($row['lock_id'])
$flag='locked';
elseif($row['isoverdue'])
$flag='overdue';
$lc='';
if($showassigned) {
if($row['staff_id'])
$lc=sprintf('<span class="Icon staffAssigned">%s</span>',Format::truncate($row['staff'],40));
elseif($row['team_id'])
$lc=sprintf('<span class="Icon teamAssigned">%s</span>',Format::truncate($row['team'],40));
else
$lc=' ';
}else{
$lc=Format::truncate($row['dept_name'],40);
}
$tid=$row['ticketID'];
$subject = Format::truncate($row['subject'],40);
$threadcount=$row['thread_count'];
if(!strcasecmp($row['status'],'open') && !$row['isanswered'] && !$row['lock_id']) {
$tid=sprintf('<b>%s</b>',$tid);
}
$date1 = $row['created'];
if($row['status'] == 'closed' )
{
$date2 = $row['closed'];
}else{
$date2 = date('Y-m-d H:i:s');
}
$total_days = round(abs(strtotime($date2)-strtotime($date1))/86400);
$sql_checking_activity = "Select * from sdms_ticket_thread where ticket_id = '".$row['ticket_id']."' AND (thread_type = 'N' OR thread_type = 'R')  AND title 
NOT LIKE 'Complaint Assigned to%'";
$res_checking_activity = mysql_query($sql_checking_activity);
$num_checking_activity =  mysql_num_rows($res_checking_activity);
if($num_checking_activity>0 && $thisstaff->getId()==$row['staff_id'])
{
$alret='style="background-color: #d8bfd8 !important;"';
}elseif($thisstaff->getId()!=$row['staff_id']){
$alret='style="background-color: #e1ffea !important;"';
}else
{
$alret='';
}
 
		$csv .= '"' . $row['ticket_id'] . '",';
		
		$csv .= '"' . Format::db_date($row['created']); 
		if($row['reopened']!=''){ $csv .=  '(Reopen:'. Format::db_date($row['created']).')' ; } 
		$csv .=  '",';
		$csv .= '"' .$subject. '",'; 
if($row['name']==''){
$csv .= '"' .'Anonymous:'. $row['source'] . '",'; 
}else{
$csv .= '"' . Format::truncate($row['name'],22,strpos($row['name'],'@')).' ('. $row['source'].')'. '",'; 
}
$csv .= '"'.$row['staff'].'",'; 

$csv .= '"'.$row['District'].'",'; 


$sql_fetchstatus = "Select * from sdms_status where status_id='".$row['complaint_status']."'";
$res_fetchstatus = mysql_query($sql_fetchstatus);
$row_fetchstatus = mysql_fetch_array($res_fetchstatus);

$sql_fetchpstatus = "Select * from sdms_status where status_id='".$row_fetchstatus['p_id']."'";
$res_fetchpstatus = mysql_query($sql_fetchpstatus);
$row_fetchpstatus = mysql_fetch_array($res_fetchpstatus);

$csv .= '"'.$row_fetchpstatus['status_title']; 
if($row['status'] == 'closed' )
{
$csv .= '('.date('d/m/Y',strtotime($row['closed'])).')'; 
}
$csv .= '",';

if($thisstaff->isAdmin()) { if($row['ticket_voice']!=''){} }
else{
$csv .= '"'.$total_days.' Days'.'",'; 
}

$csv.="\n";

}
else:
$ferror='There are no Complaint s here. (Leave a little early today).';  
endif;

$csv.="\n";
$file = 'complaints_lisitng.csv';
if (!$handle = fopen($file, 'w')) 
{
echo "Cannot open file ($filename)";
exit;                    
}
if (fwrite($handle, $csv) === FALSE) 
{
echo "Cannot write to file ($filename)";
exit;
}
fclose($handle);
  <?php */?>
  
  <div class="row-fluid">
    <div class="span6" style="width:96.718%;">
      <?php if($num>0){   ?>
      <p style="text-align:center;" id="actions">
        <?php

                if($thisstaff->canManageTickets()) {

                $status=$_REQUEST['status']?$_REQUEST['status']:$status;

                switch (strtolower($status)) {

                case 'closed': ?>
        <input class="btn btn-primary" type="submit" name="reopen" value="Reopen" id="close" >
        <?php

                break;

            case 'open':

            case 'answered':

            case 'assigned':

               ?>
        
        <!--<input class="btn btn-warning" type="submit" name="mark_overdue" value="Overdue"  id="overdue" />                                     

                <input class="btn btn-inverse" type="submit" name="close" value="Close" id="close_a" /> -->
        
        <?php

                break;

                case 'overdue':

                ?>
        
        <!--<input class="btn btn-inverse" type="submit" name="close" value="Close" id="close_a" />-->
        
        <?php

                break;

                default: //search??

                ?>
        
        <!--                <input class="btn btn-inverse" type="submit" name="close" value="Close" id="close_a" >

                <input class="btn btn-primary" type="submit" name="reopen" value="Reopen" id="close_a">-->
        
        <?php

                }

                if($thisstaff->canDeleteTickets()) { ?>
        
        <!--<input class="btn btn-danger" type="submit" name="delete" value="Delete" id="">--> 
        
        <!--Export Btn Created-->
        
        <?php //echo '<a class="btn " href="?a=export&h='.$hash.'&status='.$_REQUEST['status'] .'">';?>
        <!--Export-->
        <?php //echo '</a>'; ?>
      </p>
      <?php } }?>
      <?php }?>
    </div>
  </div>
  <div class="dr"><span></span></div>
</form>
<div style="display:none;" class="dialog_show" id="ba_btn_close_a" title="Please Confirm" >
  <p > Are you sure want to <b>close</b> selected open Complaints? </p>
  <p class="confirm-action" style="display:none;" id="reopen-confirm"> Are you sure want to <b>reopen</b> selected closed Complaints? </p>
  <div>Please confirm to continue.</div>
  <hr style="margin-top:1em"/>
  <p class="full-width"> <span class="buttons" style="float:left">
    <input type="button" value="No, Cancel" class="close">
    </span> <span class="buttons" style="float:right">
    <input type="button" value="Yes, Do it!" class="confirm">
    </span> </p>
  <div class="clear"></div>
</div>

<!--<div style="display:none;" class="dialog_show" id="confirm-action">

        <h3>Please Confirm</h3>

        <a class="close" href="">&times;</a>

        <hr/>

        <p class="confirm-action" style="display:none;" id="close-confirm">

            Are you sure want to <b>close</b> selected open Complaints?

        </p>

        <p class="confirm-action" style="display:none;" id="reopen-confirm">

            Are you sure want to <b>reopen</b> selected closed Complaints?

        </p>

        <p class="confirm-action" style="display:none;" id="mark_overdue-confirm">

            Are you sure want to flag the selected Complaints as <font color="red"><b>overdue</b></font>?

        </p>

        <p class="confirm-action" style="display:none;" id="delete-confirm">

            <font color="red"><strong>Are you sure you want to DELETE selected Complaints?</strong></font>

            <br><br>Deleted Complaints CANNOT be recovered, including any associated attachments.

        </p>

        <div>Please confirm to continue.</div>

        <hr style="margin-top:1em"/>

        <p class="full-width">

            <span class="buttons" style="float:left">

                <input type="button" value="No, Cancel" class="close">

            </span>

            <span class="buttons" style="float:right">

                <input type="button" value="Yes, Do it!" class="confirm">

            </span>

         </p>

        <div class="clear"></div>

    </div>-->

<div class="dialog" id="b_popup_4" style="display: none;" title="Advanced Complaint Search">
  <form action="tickets.php" method="get" id="search" name="search">
    <input type="hidden" name="a" value="search">
    <div class="block">
      <ul class="rows">
        <li>
          <div class="title">Keyword:</div>
          <div class="text">
            <input type="input" id="query" name="query" size="20">
            <em>Optional</em></div>
        </li>
        <li>
          <div class="title">Complainant Name:</div>
          <div class="text">
            <input type="input" id="respondent" name="respondent" size="20">
          </div>
        </li>
        <?php /*?><li>

                    <div class="title">Applicant Father's Name:</div>

                    <div class="text"><input type="input" id="f_name" name="f_name" size="20"></div>

                </li><?php */?>
        <li>
          <div class="title">CNIC/NICOP/Passport :</div>
          <div class="text">
            <input type="input" id="cnic" name="cnic" size="20">
          </div>
        </li>
        <li>
          <div class="title">Complainant’s Cell:</div>
          <div class="text">
            <input type="input" id="cell_nos" name="cell_nos" size="20">
          </div>
        </li>
        <li>
          <div class="title">Complaint Status:</div>
          <div class="text">
            <select name="cstatus"  id="status" style="width:300px;" onChange="get_Sub_Status(this.value)" >
              <option value="" selected >&mdash; Select Status &mdash;</option>
              <?php

        if($status=Status::getAllStatus()) {

            foreach($status as $id =>$name) {

                echo sprintf('<option value="%d" %s>%s</option>',

                        $id, ($info['complaint_status']==$id)?'selected="selected"':'',$name);

            }

        }

        ?>
            </select>
          </div>
        </li>
        <div id="show_sub_status"></div>
        <?php if($thisstaff->isFocalPerson() || $thisstaff->isAdmin()) {?>
        <li>
          <div class="title">Assigned To:</div>
          <div class="text">
            <select id="assignee" name="assign_staff">
              <option value="">&mdash; Anyone &mdash;</option>
              <option value="0">&mdash; Unassigned &mdash;</option>
              <option value="<?php echo $thisstaff->getId(); ?>">Me</option>
              <?php 
					if($thisstaff->isFocalPerson())
{
if(($users=Staff::getSubPOC($thisstaff->getDeptId()))) {
echo '<OPTGROUP label="Sub POC ('.count($users).')">';
foreach($users as $id => $name) {
echo sprintf('<option value="%s" >%s</option>',$id,$name);
}
echo '</OPTGROUP>';
}
}
else{  if(($users=Staff::getStaffMembers())) {
                        echo '<OPTGROUP label="Staff Members ('.count($users).')">';
                        foreach($users as $id => $name) {
                            $k="$id";
                            echo sprintf('<option value="%s">%s</option>', $k, $name);
                        }
                        echo '</OPTGROUP>';
                    }
					
					}
                  
                    ?>
            </select>
          </div>
        </li>
        <?php } ?>
        <li>
          <div class="title">Closed By:</div>
          <div class="text">
            <select id="staffId" name="staffId">
              <option value="0">&mdash; Anyone &mdash;</option>
              <option value="<?php echo $thisstaff->getId(); ?>">Me</option>
              <?php

                    if(($users=Staff::getStaffMembers())) {

                        foreach($users as $id => $name)

                            echo sprintf('<option value="%d">%s</option>', $id, $name);

                    }

                    ?>
            </select>
          </div>
        </li>
        <fieldset class="date_range">
          
          <!--<li>

                    <div class="title" style="width:100px;">Date Range:</div>

                    <div class="text"  style="width:100px;"><input class="dp" id="Datepicker" type="input" size="20" name="startDate"></div>

            

                    <div class="title"  style="width:30px;float:left;left:353px;">TO:</div>

                    <div class="text"  style="width:20px;float:left;margin:0px;"><input style="float:left;" class="dp" id="Datepicker1"  type="input" size="20" name="endDate"></div>

                </li> -->
          
          <li>
            <div class="title" >Date Range:</div>
            <div class="text"  >
              <input class="dp" id="Datepicker" type="input" size="20" name="startDate">
            </div>
          </li>
          <li>
            <div class="title">TO:</div>
            <div class="text">
              <input class="dp" id="Datepicker1"  type="input" size="20" name="endDate">
            </div>
          </li>
        </fieldset>
      </ul>
      <div class="dr"><span></span></div>
    </div>
  </form>
</div>
</div>
<!--WorkPlace End-->

</div>
