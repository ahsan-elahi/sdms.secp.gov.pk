<?php $servername = "localhost";
$username = "root";
$password = "lastfight@secp321$";
$dbname = "sdms_scep";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//$sql = "SELECT * FROM `sdms_ticket` s, `sdms_ticket_thread` st WHERE s.`isaccepted`=1 AND TIMESTAMPDIFF(DAY,s.created,st.created)>=4 AND s.isrejected<>'1' AND s.ticket_id>=4100 AND s.ticket_id=st.ticket_id ";
$sql="SELECT * FROM `sdms_ticket` WHERE `isaccepted`=1 AND TIMESTAMPDIFF(DAY,created,NOW())>=7 AND TIMESTAMPDIFF(DAY,created,NOW())<=14 AND isrejected<>'1' AND isquery='0' AND ticket_id>=4100 AND status='open' AND ticket_id NOT IN (select ticket_id from sdms_ticket_thread where thread_type='R')";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
while($row = mysqli_fetch_assoc($result))
{
		//$row['staff_id']
$depresult = mysqli_query($conn, "select * from sdms_staff where isfocalperson='1' AND dept_id='".$row['dept_id']."'");
$deprow = mysqli_fetch_assoc($depresult);
$to = $deprow['email'];
$deppocresult = mysqli_query($conn, "select * from sdms_staff where staff_id='".$row['staff_id']."'");
$deppochrow = mysqli_fetch_assoc($deppocresult);
/*$dephodresult = mysqli_query($conn, "select * from sdms_staff where onchairman='1' AND dept_id='".$row['dept_id']."' AND username like 'hod.%'");
while($dephodrow = mysqli_fetch_assoc($dephodresult))
{
	$hodemail.=','.$dephodrow['email'];
}*/
$subject = "Service Desk Escalation Alert";
$message = "
<html>
<head>
<title>Escalation Alert SDMS</title>
</head>
<body>
<p>Dear ".$deprow['firstname'].' '.$deprow['lastname']."</p>
<p>Complaint Number ".$row['ticket_id']." assigned to ".$deppochrow['firstname'].' '.$deppochrow['lastname'].", is awaiting action/update to complainant from your end since complaint lodgment date ".date('d,M Y',strtotime($row['created'])).".  This incident is being escalated according to the Commission approved Service Desk Guideline 2017. </p>
</p>To avoid any further escalation, please provide an update to the complainant through the system.</p>
<p>&nbsp;</p>
<p>Regards,<br />
  SECP Service Desk<br />
  Toll Free: 080088008<br />
  Email:<br />
<a href='mailto:queries@secp.gov.pk' >queries@secp.gov.pk</a><br />
<a href='mailto:complaints@secp.gov.pk'>complaints@secp.gov.pk</a></p>
</body>
</html>";
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
// More headers
$headers .= 'From: Notification Alert<notification.servicedesk@secp.gov.pk>' . "\r\n";
$headers .= 'Cc: '.$deppochrow['email'].$hodemail.'' . "\r\n";
$checkresult = mysqli_query($conn,"select ticket_id from sdms_notify where ticket_id ='".$row['ticket_id']."' AND notify_days='7' ");
if (mysqli_num_rows($checkresult)==0) 
{
echo mail($to,$subject,$message,$headers);
mysqli_query($conn, "insert into sdms_notify(staff_id,notify_days,ticket_id) values('".$row['staff_id']."','7','".$row['ticket_id']."') ");
}
}
}
/****************************************************************15 Days *******************/
$sql="SELECT * FROM `sdms_ticket` WHERE `isaccepted`=1 AND TIMESTAMPDIFF(DAY,created,NOW())>=15 AND TIMESTAMPDIFF(DAY,created,NOW())<=29 AND isrejected<>'1' AND isquery='0' AND ticket_id>=4100 AND status='open' AND ticket_id NOT IN (select ticket_id from sdms_ticket_thread where thread_type='R')";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
while($row = mysqli_fetch_assoc($result))
{
		//$row['staff_id']
$depresult = mysqli_query($conn, "select * from sdms_staff where isfocalperson='1' AND dept_id='".$row['dept_id']."'");
$deprow = mysqli_fetch_assoc($depresult);
$to = $deprow['email'];
$deppocresult = mysqli_query($conn, "select * from sdms_staff where staff_id='".$row['staff_id']."'");
$deppochrow = mysqli_fetch_assoc($deppocresult);
$depadmresult = mysqli_query($conn, "select * from sdms_staff where username='sdmsadmin'");
$depadmrow = mysqli_fetch_assoc($depadmresult);
$subject = "Service Desk Escalation Alert";
$message = "
<html>
<head>
<title>Escalation Alert SDMS</title>
</head>
<body>
<p>Dear ".$deprow['firstname'].' '.$deprow['lastname']."</p>
<p>Complaint Number ".$row['ticket_id']." assigned to ".$deppochrow['firstname'].' '.$deppochrow['lastname'].", is awaiting action/update to complainant from your end since complaint lodgment date ".date('d,M Y',strtotime($row['created'])).".  This incident is being escalated according to the Commission approved Service Desk Guideline 2017. </p>
</p>To avoid any further escalation, please provide an update to the complainant through the system.</p>
<p>&nbsp;</p>
<p>Regards,<br />
  SECP Service Desk<br />
  Toll Free: 080088008<br />
  Email:<br />
<a href='mailto:queries@secp.gov.pk' >queries@secp.gov.pk</a><br />
<a href='mailto:complaints@secp.gov.pk'>complaints@secp.gov.pk</a></p>
</body>
</html>";
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
// More headers
$headers .= 'From: Notification Alert<notification.servicedesk@secp.gov.pk>' . "\r\n";
$headers .= 'Cc: '.$deppochrow['email'].$hodemail.','.$depadmrow['email']. "\r\n";
$checkresult = mysqli_query($conn,"select ticket_id from sdms_notify where ticket_id ='".$row['ticket_id']."' AND notify_days='15' ");
if (mysqli_num_rows($checkresult)==0) 
{
echo mail($to,$subject,$message,$headers);
mysqli_query($conn, "insert into sdms_notify(staff_id,notify_days,ticket_id) values('".$row['staff_id']."','15','".$row['ticket_id']."') ");
}
}
}
/*****************************30 Days ********************************************/
$sql="SELECT * FROM `sdms_ticket` WHERE `isaccepted`=1 AND TIMESTAMPDIFF(DAY,created,NOW())>=30 AND TIMESTAMPDIFF(DAY,created,NOW())<=45 AND isrejected<>'1' AND isquery='0' AND ticket_id>=4100 AND status='open' AND ticket_id NOT IN (select ticket_id from sdms_ticket_thread where thread_type='R')";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
while($row = mysqli_fetch_assoc($result))
{
		//$row['staff_id']
$depresult = mysqli_query($conn, "select * from sdms_staff where isfocalperson='1' AND dept_id='".$row['dept_id']."'");
$deprow = mysqli_fetch_assoc($depresult);
$deppocresult = mysqli_query($conn, "select * from sdms_staff where staff_id='".$row['staff_id']."'");
$deppochrow = mysqli_fetch_assoc($deppocresult);
$depadmresult = mysqli_query($conn, "select * from sdms_staff where username='sdmsadmin'");
$depadmrow = mysqli_fetch_assoc($depadmresult);
$dephodresult = mysqli_query($conn, "select * from sdms_staff where onchairman='1' AND dept_id='".$row['dept_id']."' AND username like 'hod.%'");
$hodemail='';
while($dephodrow = mysqli_fetch_assoc($dephodresult))
{
	$hodemail.=','.$dephodrow['email'];
}
$to = $hodemail;
$subject = "Service Desk Escalation Alert";
$message = "
<html>
<head>
<title>Escalation Alert SDMS</title>
</head>
<body>
<p>Dear HOD</p>
<p>Complaint Number ".$row['ticket_id']." assigned to ".$deppochrow['firstname'].' '.$deppochrow['lastname'].", is awaiting action/update to complainant from your end since complaint lodgment date ".date('d,M Y',strtotime($row['created'])).".  This incident is being escalated according to the Commission approved Service Desk Guideline 2017. </p>
</p>To avoid any further escalation, please provide an update to the complainant through the system.</p>
<p>&nbsp;</p>
<p>Regards,<br />
  SECP Service Desk<br />
  Toll Free: 080088008<br />
  Email:<br />
<a href='mailto:queries@secp.gov.pk' >queries@secp.gov.pk</a><br />
<a href='mailto:complaints@secp.gov.pk'>complaints@secp.gov.pk</a></p>
</body>
</html>";
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
// More headers
$headers .= 'From: Notification Alert<notification.servicedesk@secp.gov.pk>' . "\r\n";
$headers .= 'Cc: '.$deppochrow['email'].','.$deprow['email'].','.$depadmrow['email']. "\r\n";
$checkresult = mysqli_query($conn,"select ticket_id from sdms_notify where ticket_id ='".$row['ticket_id']."' AND notify_days='30' ");
if (mysqli_num_rows($checkresult)==0) 
{
echo mail($to,$subject,$message,$headers);
mysqli_query($conn, "insert into sdms_notify(staff_id,notify_days,ticket_id) values('".$row['staff_id']."','30','".$row['ticket_id']."') ");
}
}
}
/*************************************************45 Days *************************************************************/
$sql="SELECT * FROM `sdms_ticket` WHERE `isaccepted`=1 AND TIMESTAMPDIFF(DAY,created,NOW())>45 AND isrejected<>'1' AND ticket_id>=4100 AND status='open' AND isquery='0' AND ticket_id NOT IN (select ticket_id from sdms_ticket_thread where thread_type='R')";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
while($row = mysqli_fetch_assoc($result))
{
		//$row['staff_id']
$depcomresult = mysqli_query($conn, "select * from sdmc_commissioner where dept_id='".$row['dept_id']."'");
$depcomrow = mysqli_fetch_assoc($depcomresult);
$to = $depcomrow['email'];
$depcomresult = mysqli_query($conn, "select * from sdms_staff where email='".$row['$to']."'");
$depcomrow = mysqli_fetch_assoc($depcomresult);
$depresult = mysqli_query($conn, "select * from sdms_staff where isfocalperson='1' AND dept_id='".$row['dept_id']."'");
$deprow = mysqli_fetch_assoc($depresult);
$deppocresult = mysqli_query($conn, "select * from sdms_staff where staff_id='".$row['staff_id']."'");
$deppochrow = mysqli_fetch_assoc($deppocresult);
$dephodresult = mysqli_query($conn, "select * from sdms_staff where onchairman='1' AND dept_id='".$row['dept_id']."' AND username like 'hod.%'");
$hodemail='';
while($dephodrow = mysqli_fetch_assoc($dephodresult))
{
	$hodemail.=','.$dephodrow['email'];
}
$depadmresult = mysqli_query($conn, "select * from sdms_staff where username='sdmsadmin'");
$depadmrow = mysqli_fetch_assoc($depadmresult);
$depcomresult = mysqli_query($conn, "select * from sdmc_commissioner where dept_id='".$row['dept_id']."'");
$depcomrow = mysqli_fetch_assoc($depcomresult);
$to = $depcomrow['email'];
$subject = "Service Desk Escalation Alert";
$message = "
<html>
<head>
<title>Escalation Alert SDMS</title>
</head>
<body>
<p>Dear ".$depcomrow['firstname'].' '.$depcomrow['lastname']."</p>
<p>Complaint Number ".$row['ticket_id']." assigned to ".$deppochrow['firstname'].' '.$deppochrow['lastname'].", is awaiting action/update to complainant from your end since complaint lodgment date ".date('d,M Y',strtotime($row['created'])).".  This incident is being escalated according to the Commission approved Service Desk Guideline 2017. </p>
</p>To avoid any further escalation, please provide an update to the complainant through the system.</p>
<p>&nbsp;</p>
<p>Regards,<br />
  SECP Service Desk<br />
  Toll Free: 080088008<br />
  Email:<br />
<a href='mailto:queries@secp.gov.pk' >queries@secp.gov.pk</a><br />
<a href='mailto:complaints@secp.gov.pk'>complaints@secp.gov.pk</a></p>
</body>
</html>";
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
// More headers
$headers .= 'From: Notification Alert<notification.servicedesk@secp.gov.pk>' . "\r\n";
$headers .= 'Cc: '.$deppochrow['email'].','.$deprow['email'].','.$depadmrow['email'].$hodemail.'' . "\r\n";
$checkresult = mysqli_query($conn,"select ticket_id from sdms_notify where ticket_id ='".$row['ticket_id']."' AND notify_days='45' ");
if (mysqli_num_rows($checkresult)==0) 
{
//echo mail($to,$subject,$message,$headers);
mysqli_query($conn, "insert into sdms_notify(staff_id,notify_days,ticket_id) values('".$row['staff_id']."','45','".$row['ticket_id']."') ");
}
}
}
?>