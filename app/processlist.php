<?php
require_once 'conn.php';
$result = "Select * from sdms_process_names order by type desc";
$query = mysqli_query($conn,$result);
// check for empty result
if ($query->num_rows > 0) {
// looping through all results
// products node
$response["process"] = array();
while ($row = mysqli_fetch_assoc($query)) {
// temp user array
$subproduct["id"] = $row["id"];
$subproduct["title"] = $row["title"];
$subproduct["type"] = $row["type"];
array_push($response["process"], $subproduct); 
}
// success
// echoing JSON response
echo json_encode($response);
} else {
// no products found
$response["success"] = 0;
$response["message"] = "No Process Found";
// echo no users JSON
echo json_encode($response);
}
?>