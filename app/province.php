<?php

require_once 'conn.php';
$country= $_REQUEST['country'];
if (isset($country)) {
    $result = "SELECT * FROM sdms_tehsils where District_ID= $country";
    $query = mysqli_query($conn, $result);
// check for empty result
    if ($query->num_rows > 0) {
// looping through all results
// products node
        $response["country"] = array();
        while ($row = mysqli_fetch_assoc($query)) {
// temp user array
            $subproduct["province_id"] = $row["Tehsil_ID"];
            $subproduct["province_name"] = $row["Tehsil_Name"];
            array_push($response["country"], $subproduct);
        }
// success
// echoing JSON response
        $response["success"] = 1;
        $response["message"] = "Country List";
        echo json_encode($response);
    } else {
// no products found
        $response["success"] = 0;
        $response["message"] = "No Country Found";

// echo no users JSON
        echo json_encode($response);
    }
}
?>