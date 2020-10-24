<?php
require('staff.inc.php');
	$csv='';
		$csv.='"Queries List"'.',';	
		$csv.="\n";				 		 		
		$csv.='"Query ID"'.','; 
		$csv.='"Date"'.','; 
		$csv.='"Subject"'.','; 
		$csv.='"Complainant"'.','; 
		$csv.='"Handling Officer"'.',';
		$csv.='"Location"'.',';
		$csv.='"Status"'.','; 		
		$csv.='"Sub Status"'.','; 
		if($thisstaff->isAdmin())
		{
		$csv.='"Nature of Complaint"'.',';
		}
		else{
		$csv.='"# of Days"'.',';
		$csv.='"Nature of Complaint"'.',';
		}

$csv.="\n";
$total=0;
$query=$_SESSION['queries_list_session'];
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
$subject = $row['subject'];
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



/*$sql_sdms_help_topic = "Select * from sdms_help_topic where topic_id='".$row['topic_id']."'";
echo $sql_sdms_help_topic;exit;
$res_sdms_help_topic = mysql_query($sql_sdms_help_topic);
$row_sdms_help_topic = mysql_fetch_array($res_sdms_help_topic);

$csv .= '"'.$row_sdms_help_topic['topic'].'",';*/

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


$csv .= '"'.$row_fetchstatus['status_title'].'",';
if($thisstaff->isAdmin()) { 
$csv .= '"'.$row['helptopic'].'",'; 
}
else{
$csv .= '"'.$total_days.' Days'.'",'; 
$csv .= '"'.$row['helptopic'].'",'; 
}

$csv.="\n";

}
else:
$ferror='There are no Queries s here. (Leave a little early today).';  
endif;

$csv.="\n";
$file = 'queries_lisitng.csv';
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


header("Content-Description: File Transfer"); 
header("Content-Type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=" . basename($file) . ""); 

readfile ($file);
exit(); 
?>