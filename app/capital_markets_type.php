<?php
require_once 'conn.php';
$result = "Select * from sdms_capital_markets Where 1 GROUP BY type order by `sort_number` asc";
$query = mysqli_query($conn,$result);
// check for empty result
if ($query->num_rows > 0) {
// looping through all results
// products node
$response["capital_markets_type"] = array();
while ($row = mysqli_fetch_assoc($query)) {
// temp user array
$subproduct["cm_type"] = $row["type"];
array_push($response["capital_markets_type"], $subproduct);
}
$response["success"] = 1; 
// success
// echoing JSON response
echo json_encode($response);
} else {
// no products found
$response["success"] = 0;
$response["message"] = "No Capital Markets Type Found";
// echo no users JSON
echo json_encode($response);
}
?>