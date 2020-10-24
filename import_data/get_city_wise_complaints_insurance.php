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
<th>Date of the complaint</th>
<th>Complaint ID</th>
<th>City of the complainant</th>
<th>Company Name</th>
</tr>
</thead>
<tbody>
<?php
$count = 1;
//$sql_tickets = "Select * from sdms_ticket where isquery = '0' AND dept_id='2' AND DATE(created) >='2018-07-01' AND DATE(created) <='2019-06-30' order  by ticket_id asc";
$sql_tickets = "Select * from sdms_ticket where isquery = '0' AND dept_id='3'  AND DATE(created) >='2015-01-01'  order  by ticket_id asc";
$res_tickets = mysql_query($sql_tickets);
while($row_tickets = mysql_fetch_array($res_tickets)){
	
$sql_tehsils = "Select * from sdms_agency_tehsils where AgencyTehsil_ID='".$row_tickets['AgencyTehsil_ID']."'";
$res_tehsils = mysql_query($sql_tehsils);
$row_tehsils = mysql_fetch_array($res_tehsils);


$sql_ticket_i = "select * from sdms_ticket_insurance where complaint_id = '".$row_tickets['ticketID']."'";
$res_ticket_i = mysql_query($sql_ticket_i);
$row_ticket_i = mysql_fetch_array($res_ticket_i);
?>
<tr>
<td><?php echo date('Y-m-d',strtotime($row_tickets['created'])); ?></td>
<td><?php echo $row_tickets['ticketID']; ?></td>
<td><?php echo $row_tehsils['AgencyTehsil_Name']; ?></td>
<td><?php echo $row_ticket_i['i_broker_title']; ?></td>
</tr>
<?php
$count++;
}
?>
<tbody>
</table>