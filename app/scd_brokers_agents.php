<?php
require_once 'conn.php';
$result = "SELECT * FROM sdms_insurance WHERE parent='".$_REQUEST['parent']."' AND child_agent!=''  group by child_agent";
$query = mysqli_query($conn,$result);
// check for empty result
if ($query->num_rows > 0) {
// looping through all results
// products node
$response["insurance_agent"] = array();
while ($row = mysqli_fetch_assoc($query)) {
// temp user array
$subproduct["i_broker_agent"] = $row["child_agent"];
array_push($response["insurance_agent"], array_map('utf8_encode',$subproduct));
}
$response["success"] = 1;
// success
// echoing JSON response
echo json_encode($response);
} else {
// no products found
$response["success"] = 0;
$response["message"] = "No Insurance Agent Found";
// echo no users JSON
echo json_encode($response);
}
?>