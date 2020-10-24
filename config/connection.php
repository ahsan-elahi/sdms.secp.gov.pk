<?php 
	$host = "localhost";
	$user = "root";
	$password = "lastfight@secp321$";
	$db       = "sdms_scep";
	$conn     = mysqli_connect($host,$user,$password,$db);

	if (mysqli_connect_errno())
	  {
	  echo "Failed to connect to database: " . mysqli_connect_error();
	  }

?>