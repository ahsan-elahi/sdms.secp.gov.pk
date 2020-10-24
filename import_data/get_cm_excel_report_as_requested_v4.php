<?php 
//database connection details
//$connect = mysql_connect('localhost','ahsan','ubuntu');
$connect = mysql_connect('localhost','root','lastfight@secp321$');
if (!$connect) {
 die('Could not connect to MySQL: ' . mysql_error());
 }
//your database name
$cid = mysql_select_db('sdms_scep',$connect);
?>
<table border="1">
<thead>
<tr>
<th>Serial No.</th>
<th>Complaint number</th>
<th>Date of receipt of complaint</th>
<th>Complainant Name</th>
<th>Complainant’s CNIC</th>
<th>Complainant’s contact number</th>
<th>Complainant’s email</th>
<th>Complainant’s Address</th>
<th>Received from (SDMS/ post/email)</th>
<th>Subject</th>
<th>Description</th>
<th>Company/Broker’s Name</th>
<th>Status</th>
<th>Sub Status</th>
<th>Primary Category</th>
<th>Sub-Category</th>
<th>Department</th>
<th>Action Taken</th>
</tr>
</thead>
<tbody>
<?php
$count = 1;
//$sql_tickets = "Select * from sdms_ticket where isquery = '0' AND dept_id='2' AND DATE(created) >='2018-07-01' AND DATE(created) <='2019-06-30' order  by ticket_id asc";
$sql_tickets = "Select * from sdms_ticket where isquery = '0' AND dept_id='2'  order  by ticket_id asc";
$res_tickets = mysql_query($sql_tickets);
while($row_tickets = mysql_fetch_array($res_tickets)){
	
$sql_substatus = "Select * from sdms_status where status_id='".$row_tickets['complaint_status']."'";
$res_substatus = mysql_query($sql_substatus);
$row_substatus = mysql_fetch_array($res_substatus);

$sql_fetchtopic = "Select * from sdms_help_topic where topic_id='".$row_tickets['topic_id']."'";
$res_fetchtopic = mysql_query($sql_fetchtopic);
$row_fetchtopic = mysql_fetch_array($res_fetchtopic);

$sql_fetchptopic = "Select * from sdms_help_topic where topic_pid='".$row_fetchtopic['topic_pid']."'";
$res_fetchptopic = mysql_query($sql_fetchptopic);
$row_fetchptopic = mysql_fetch_array($res_fetchptopic);


$sql_ticket_cm = "select * from sdms_ticket_capital_markets where complaint_id = '".$row_tickets['ticketID']."'";
$res_ticket_cm = mysql_query($sql_ticket_cm);
$row_ticket_cm = mysql_fetch_array($res_ticket_cm);

$sql_complaint_details="Select * from sdms_ticket_thread where ticket_id='".$row_tickets['ticketID']."' ORDER By id limit 0,1";
$res_complaint_details=mysql_query($sql_complaint_details);
$row_complaint_details=mysql_fetch_array($res_complaint_details);

$sql_complaint_action_taken="Select * from sdms_ticket_thread where ticket_id='".$row_tickets['ticketID']."' ORDER By id desc limit 0,1";
$res_complaint_action_taken=mysql_query($sql_complaint_action_taken);
$row_complaint_action_taken=mysql_fetch_array($res_complaint_action_taken);

?>
<tr>
<td><?php echo $count; ?></td>
<td><?php echo $row_tickets['ticketID']; ?></td>
<td><?php echo date('Y-m-d',strtotime($row_tickets['created'])); ?></td>
<td><?php echo $row_tickets['name']; ?></td>
<td><?php echo $row_tickets['nic']; ?></td>
<td><?php echo $row_tickets['phone']; ?></td>
<td><?php echo $row_tickets['email']; ?></td>
<td><?php echo $row_tickets['applicant_address']; ?></td>
<td><?php echo $row_tickets['source']; ?> </td>
<td><?php echo $row_tickets['subject'];?></td>
<td><?php echo $row_complaint_details['body'];?></td>
<td><?php echo $row_ticket_cm['cm_broker_title'].' '.$row_ticket_cm['cm_broker_agent'];?></td>
<td><?php echo $row_tickets['status']; ?> </td>
<td><?php echo $row_substatus['status_title']; ?></td>
<td><?php echo $row_fetchptopic['topic']; ?></td>
<td><?php echo $row_fetchtopic['topic']; ?> </td>
<td> </td>
<td><?php echo $row_complaint_action_taken['body'];?></td>
</tr>
<?php
$count++;
}
?>
<tbody>
</table>