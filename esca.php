<?php
$servername = "localhost";
$username = "root";
$password = "lastfight@secp321$";
$dbname = "sdms_scep";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$checkdate=date('Y-m-d', strtotime(date('Y-m-d').' -3 Weekday'));
$sql = "SELECT * FROM `sdms_ticket` WHERE `isaccepted`=0 AND DATE(created)='".$checkdate."'  AND isrejected<>'1' AND isquery='0' AND ticket_id>=4100 AND status='open'"; 
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
while($row = mysqli_fetch_assoc($result))
{
		//$row['staff_id']
$depresult = mysqli_query($conn, "select * from sdms_staff where username='sdmsadmin'");
$deprow = mysqli_fetch_assoc($depresult);		
$to =$deprow['email'];
$deppocresult = mysqli_query($conn, "select * from sdms_staff where staff_id='".$row['staff_id']."' AND isactive='1'");
$deppochrow = mysqli_fetch_assoc($deppocresult);
$dephodresult = mysqli_query($conn, "select * from sdms_staff where onchairman='1' AND dept_id='".$row['dept_id']."' AND username like 'hod.%' AND isactive='1'");
$hodemail='';
while($dephodrow = mysqli_fetch_assoc($dephodresult))
{
	$hodemail.=','.$dephodrow['email'];
}
//echo $hodemail;
$subject = "Service Desk Escalation Alert";
$message = "
<html>
<head>
<title>Escalation Alert SDMS</title>
</head>
<body>
<p>Dear ".$deprow['firstname'].' '.$deprow['lastname']."</p>
<p>Complaint Number ".$row['ticket_id']." assigned to ".$deppochrow['firstname'].' '.$deppochrow['lastname'].", is awaiting action/update to complainant from your end since complaint lodgment date ".date('d,M Y',strtotime($row['created'])).".  This incident is being escalated according to the Commission approved Service Desk Guideline 2019. </p>
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
$checkresult = mysqli_query($conn,"select ticket_id from sdms_notify where ticket_id ='".$row['ticket_id']."' AND notify_days='3' ");
if (mysqli_num_rows($checkresult)==0) 
{
echo mail($to,$subject,$message,$headers);
mysqli_query($conn, "insert into sdms_notify(staff_id,notify_days,ticket_id) values('".$row['staff_id']."','3','".$row['ticket_id']."') ");
}
echo '<br>';
		/*$h=substr($row["2"],1);
		$tid=explode('-',$h);
		echo $tid[0];
		echo "<br>";
		$depresult = mysqli_query($conn, "select dept_id from sdms_department where dept_name like '%".trim($row["3"])."%'");
		$deprow = mysqli_fetch_assoc($depresult);
		$catresult = mysqli_query($conn, "select topic_id from sdms_help_topic where topic like '".$row["5"]."%' AND dept_id='".$deprow["dept_id"]."' AND isnature='0'");
		$catrow = mysqli_fetch_assoc($catresult);
		if($catrow["topic_id"]=='')
		$cat=43;
		else
		$cat=$catrow["topic_id"];*/
		
}
}
?>