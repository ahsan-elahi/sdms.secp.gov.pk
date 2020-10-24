<?php

require_once 'conn.php';

$result = "SELECT * FROM sdms_companis";
$query = mysqli_query($conn, $result);
// check for empty result
if ($query->num_rows > 0) {
// looping through all results
// products node
    $response["companis"] = array();
    while ($row = mysqli_fetch_assoc($query)) {
// temp user array
        $subproduct["companis_id"] = $row["id"];
        $subproduct["companis_cro"] = $row["cro"];
        $subproduct["companis_incorporation_no"] = $row["incorporation_no"];
        $subproduct["companis_title"] = $row["company_title"];
        $subproduct["companis_sector"] = $row["sector"];
        $subproduct["companis_kind"] = $row["kind"];
        $subproduct["companis_company_status"] = $row["company_status"];
        $subproduct['companis_status'] = $row['status'];
        array_push($response["companis"], $subproduct);
    }
// success
// echoing JSON response
    $response["success"] = 1;
    $response["message"] = "Companis List";
    echo json_encode($response);
} else {
// no products found
    $response["success"] = 0;
    $response["message"] = "No Companis Found";

// echo no users JSON
    echo json_encode($response);
}
?>