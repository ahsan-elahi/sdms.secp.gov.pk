<?php
require_once 'conn.php';
$result = "Select * from sdms_companis where company_title LIKE '%".$_REQUEST['company_title']."%'  order by company_title asc";
//echo $result;
$query = mysqli_query($conn,$result);
// check for empty result
if ($query->num_rows > 0) {
// looping through all results
// products node
$response["companies"] = array();
while ($row = mysqli_fetch_assoc($query)) {
// temp user array
$subproduct["id"]				= $row["id"];
$subproduct["cro"] 				= $row["cro"];
//$subproduct["incorporation_no"] = $row["incorporation_no"];
$subproduct["company_title"] 	= preg_replace('/[^A-Za-z0-9\- ]/', '', $row["company_title"]);
//$subproduct["sector"] 			= $row["sector"];
//$subproduct["kind"] 			= $row["kind"];
//$subproduct["company_status"] 	= $row["company_status"];	
//$subproduct["status"] 			= $row["status"];

array_push($response["companies"], $subproduct); 
}
$response["success"] = 1;
// success
// echoing JSON response
echo json_encode($response);
} else {
// no products found
$response["success"] = 0;
$response["message"] = "No Company Found";
// echo no users JSON
echo json_encode($response);
}
?>