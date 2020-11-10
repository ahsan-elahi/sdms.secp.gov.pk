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
<th>Query Number</th>
<th>Email of the citizen</th>
<th>Subject</th>
<th>Contact no.</th>
<th>Status</th>
<th>Source</th>
</tr>
</thead>
<tbody>
<?php
$count = 1;
//$sql_tickets = "Select * from sdms_ticket where isquery = '0' AND dept_id='2' AND DATE(created) >='2018-07-01' AND DATE(created) <='2019-06-30' order  by ticket_id asc";
$sql_tickets = "Select * from sdms_ticket where isquery = '1'  AND DATE(created) >='2020-10-01'  AND DATE(created) <='2020-10-31' AND source = 'SECP Chatbot'  order  by ticket_id asc";
$res_tickets = mysql_query($sql_tickets);
while($row_tickets = mysql_fetch_array($res_tickets)){
	

$sql_substatus = "Select * from sdms_status where status_id='".$row_tickets['complaint_status']."'";
$res_substatus = mysql_query($sql_substatus);
$row_substatus = mysql_fetch_array($res_substatus);
?>
<tr>
<td><?php echo $row_tickets['ticketID']; ?></td>
<td><?php echo $row_tickets['email']; ?></td>
<td><?php echo $row_tickets['subject']; ?></td>
<td><?php echo $row_tickets['phone']; ?></td>
<td><?php echo $row_substatus['status_title']; ?></td>
<td><?php echo $row_tickets['source']; ?></td>
</tr>
<?php
$count++;
}
?>
<tbody>
</table>