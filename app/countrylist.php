<?php
require_once 'conn.php';

$result = "SELECT * FROM sdms_districts";
$query = mysqli_query($conn,$result);
// check for empty result
if ($query->num_rows > 0) {
// looping through all results
// products node
$response["countries"] = array();
while ($row = mysqli_fetch_assoc($query)) {
// temp user array
$subproduct["country_id"] = $row["District_ID"];
$subproduct["country_name"] = $row["District"];
array_push($response["countries"], $subproduct); 
}
// success


// echoing JSON response
echo json_encode($response);
} else {
// no products found
$response["success"] = 0;
$response["message"] = "No Country Found";

// echo no users JSON
echo json_encode($response);

}
?>