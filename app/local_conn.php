<?php $conn = mysqli_connect("localhost","root","") or die();
$db = mysqli_select_db($conn,"secp") or die();

if($db)
{
	//echo "connection successfully";
}
?>