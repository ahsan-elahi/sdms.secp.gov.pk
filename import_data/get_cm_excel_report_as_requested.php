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
<table>
<thead>
<tr>
<th>Serial No.</th>
<th>Complaint number</th>
<th>Date of receipt of complaint </th>
<th>Received from (SDMS/ post/email)</th>
<th>Complainant Name</th>
<th>CNIC</th>
<th>Mobile #</th>
<th>Email</th>
<th>Address</th>
<th>Subject</th>
<th>Company Name</th>
<th>Description</th>
<th>Status </th>
<th>Securities/Commodities Broker/Listed Company</th>
<th>Sub status</th>
<th>Primary Category</th>
<th>Sub-Category </th>
<th>Resolution Date</th>
<th>Update</th>


<!-- <th>Broker’s Name</th>
<th>Folio Number </th>
<th>CDC A/C No </th>
<th>No of Shares: </th> -->

</tr>
</thead>
<tbody>
<?php

$sql_ticket_cm = "select * from sdms_ticket_capital_markets where cm_type ='PSX' OR cm_type = 'PMEX' order by cm_type ASC";
$res_ticket_cm = mysql_query($sql_ticket_cm);
$i=0;
while($row_ticket_cm = mysql_fetch_array($res_ticket_cm)){

$sql_tickets = "Select * from sdms_ticket where isquery = '0' AND dept_id='2' AND ticket_id = '".$row_ticket_cm['complaint_id']."' AND DATE(created) >='2019-07-01' AND DATE(created) <='2020-06-30'";
$res_tickets = mysql_query($sql_tickets);
$row_tickets = mysql_fetch_array($res_tickets);

$sql_complaint_details="Select * from sdms_ticket_thread where ticket_id='".$row_ticket_cm['complaint_id']."' ORDER By id limit 0,1";
$res_complaint_details=mysql_query($sql_complaint_details);
$row_complaint_details=mysql_fetch_array($res_complaint_details);
	
$sql_fetchtopic = "Select * from sdms_help_topic where topic_id='".$row_tickets['topic_id']."'";
$res_fetchtopic = mysql_query($sql_fetchtopic);
$row_fetchtopic = mysql_fetch_array($res_fetchtopic);

$sql_fetchptopic = "Select * from sdms_help_topic where topic_pid='".$row_fetchtopic['topic_pid']."'";
$res_fetchptopic = mysql_query($sql_fetchptopic);
$row_fetchptopic = mysql_fetch_array($res_fetchptopic);


$sql_substatus = "Select * from sdms_status where status_id='".$row_tickets['complaint_status']."'";
$res_substatus = mysql_query($sql_substatus);
$row_substatus = mysql_fetch_array($res_substatus);

if($row_tickets['created']!=''){
	$i++;
?>
<tr>
<td><?php echo $i; ?></td>
<td><?php echo $row_tickets['ticketID']; ?></td>
<td><?php echo date('Y-m-d',strtotime($row_tickets['created']));?></td>
<td><?php echo $row_tickets['source'] ?></td>
<td><?php echo $row_tickets['name']; ?></td>
<td><?php echo $row_tickets['nic']; ?></td>
<td><?php echo $row_tickets['phone']; ?></td>
<td><?php echo $row_tickets['email']; ?></td>
<td><?php echo $row_tickets['applicant_address']; ?></td>
<td><?php echo $row_tickets['subject'];?></td>
<td><?php echo $row_ticket_cm['cm_broker_title'];?></td>
<td><?php echo $sql_complaint_details['body'] ?></td>

<td><?php echo $row_tickets['status'];?></td>
<td><?php echo $row_ticket_cm['cm_type'];?></td>
<td><?php echo $row_substatus['status_title']; ?></td>
<td><?php echo $row_fetchptopic['topic']; ?></td>
<td><?php echo $row_fetchtopic['topic']; ?> </td>
<td><?php if($row_tickets['status']=='closed')
{
	echo date('Y-m-d',strtotime($row_tickets['closed'])); 
}?></td>
<td><?php echo date('Y-m-d',strtotime($row_tickets['updated']));?></td>

<!-- <td><?php //echo $row_ticket_cm['cm_broker_agent'];?></td>
<td><?php //echo $row_ticket_cm['cm_folio_no'];?></td>
<td><?php //echo $row_ticket_cm['cm_cdc_ac_no'];?></td>
<td><?php //echo $row_ticket_cm['cm_no_of_shares'];?></td> -->
</tr>
<?php
}
}
?>
<tbody>
</table>