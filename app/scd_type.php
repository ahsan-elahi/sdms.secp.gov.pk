<?php
require_once 'conn.php';
$result = "Select * from sdms_scd Where 1 GROUP BY type";
$query = mysqli_query($conn,$result);
// check for empty result
if ($query->num_rows > 0) {
// looping through all results
// products node
$response["scd_type"] = array();
while ($row = mysqli_fetch_assoc($query)) {
// temp user array
$subproduct["scd_type"] = $row["type"];
array_push($response["scd_type"], $subproduct);
}
$response["success"] = 1; 
// success
// echoing JSON response
echo json_encode($response);
} else {
// no products found
$response["success"] = 0;
$response["message"] = "No SCD Type Found";
// echo no users JSON
echo json_encode($response);
}
?>