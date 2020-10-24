<?php
//require_once 'conn.php';
require('../ach/client.inc.php');
//$result = "SELECT * FROM sdms_insurance WHERE type='".$_REQUEST['b_type']."' AND parent!='' group by parent";
$result = "SELECT * FROM sdms_insurance WHERE type='".$_REQUEST['b_type']."' AND parent!='' ";
//$query = mysqli_query($conn,$result);
// check for empty result
//if ($query->num_rows > 0) {
	
	if(($res=db_query($result)) && db_num_rows($res)) {
// looping through all results
// products node
$response["insurance_broker"] = array();
//while ($row = mysqli_fetch_assoc($query)) {
	while($row = db_fetch_array($res)) {
// temp user array
$subproduct["i_broker_title"] = $row["parent"];
//array_push($response["insurance_broker"], array_map('utf8_encode',$subproduct)); 
array_push($response["insurance_broker"], $subproduct); 
}
$response["success"] = 1;
// success
// echoing JSON response
echo json_encode($response);
} else {
// no products found
$response["success"] = 0;
$response["message"] = "No Insurance Broker Found";
// echo no users JSON
echo json_encode($response);
}
?>