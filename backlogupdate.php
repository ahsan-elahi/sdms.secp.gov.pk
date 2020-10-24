<?php $servername = "localhost";
$username = "root";
$password = "lastfight@secp321$";
/*$username = "ahsan";
$password = "ubuntu";*/

$dbname = "sdms_scep";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql="SELECT * FROM `sdms_ticket` WHERE `ticket_id` < 4100";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result))
{

$depresult = mysqli_query($conn, "select * from sdms_staff where isfocalperson='1' AND dept_id='".$row['dept_id']."'");
$deprow = mysqli_fetch_assoc($depresult);
mysqli_query($conn, "INSERT INTO `sdms_ticket_event` (`ticket_id`, `staff_id`, `team_id`, `dept_id`, `topic_id`, `state`, `staff`, `annulled`, `timestamp`) VALUES ('".$row['ticket_id']."', '".$deprow['staff_id']."', '0', '".$row['dept_id']."', '".$row['topic_id']."', 'assigned', 'sdmsadmin', '0', '".$row['created']."')");

}
}
?>