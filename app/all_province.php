<?php
require_once 'conn.php';
$result = "SELECT * FROM sdms_tehsils";
$query = mysqli_query($conn,$result);
if ($query->num_rows > 0) {
$response["provinces"] = array();
while ($row = mysqli_fetch_assoc($query)) {

$subproduct["province_id"] = $row["Tehsil_ID"];
//$subproduct["province_name"] =$row["Tehsil_Name"];
//$subproduct["province_name"] 	=  $row["Tehsil_Name"];
$subproduct["country_id"] = $row["District_ID"];
$subproduct["province_name"] = preg_replace('/[^A-Za-z0-9\- ]/', '', $row["Tehsil_Name"]);
array_push($response["provinces"],$subproduct); 

}

$response["success"] = 1;
echo json_encode($response);
} else {
$response["success"] = 0;
$response["message"] = "No Provice Found";
echo json_encode($response);
}
?>