<?php
require_once 'conn.php';
$result = "Select * from sdms_insurance Where 1 GROUP BY type";
$query = mysqli_query($conn,$result);
// check for empty result
if ($query->num_rows > 0) {
// looping through all results
// products node
$response["insurance_type"] = array();
while ($row = mysqli_fetch_assoc($query)) {
// temp user array
$subproduct["i_type"] = $row["type"];
array_push($response["insurance_type"], $subproduct);
}
$response["success"] = 1; 
// success
// echoing JSON response
echo json_encode($response);
} else {
// no products found
$response["success"] = 0;
$response["message"] = "No Insurance Type Found";
// echo no users JSON
echo json_encode($response);
}
?>