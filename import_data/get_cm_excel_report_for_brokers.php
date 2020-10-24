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
<th>S.no</th>
<th>Securities/Commodities Broker/Listed Company</th>
<th>Broker name</th>
<th>Date of complaint</th>
<th>Name of complainant</th>
<th>Subject of Complaint</th>
<th>Status of complaint</th>
</tr>
</thead>
<tbody>
<?php

$sql_ticket_cm = "select * from sdms_ticket_capital_markets where cm_broker_title!='' AND cm_type!='' Group by complaint_id order by cm_type ASC";
$res_ticket_cm = mysql_query($sql_ticket_cm);
$i=0;
while($row_ticket_cm = mysql_fetch_array($res_ticket_cm)){

$sql_tickets = "Select * from sdms_ticket where isquery = '0' AND dept_id='2' AND ticket_id = '".$row_ticket_cm['complaint_id']."'";
$res_tickets = mysql_query($sql_tickets);
$row_tickets = mysql_fetch_array($res_tickets);


$sql_substatus = "Select * from sdms_status where status_id='".$row_tickets['complaint_status']."'";
$res_substatus = mysql_query($sql_substatus);
$row_substatus = mysql_fetch_array($res_substatus);

$sql_pstatus = "Select * from sdms_status where status_id='".$row_substatus['p_id']."'";
$res_pstatus = mysql_query($sql_pstatus);
$row_pstatus = mysql_fetch_array($res_pstatus);

if($row_tickets['created']!=''){
	$i++;
?>
<tr>
<td><?php echo $row_tickets['ticketID']; ?></td>
<td><?php echo $row_ticket_cm['cm_type'];?></td>
<td><?php echo $row_ticket_cm['cm_broker_title'];?></td>
<td><?php echo date('Y-m-d',strtotime($row_tickets['created']));?></td>
<td><?php echo $row_tickets['name']; ?></td>
<td><?php echo $row_tickets['subject'];?></td>
<td><?php echo $row_pstatus['status_title']; ?></td>
</tr>
<?php
}
}
?>
<tbody>
</table>