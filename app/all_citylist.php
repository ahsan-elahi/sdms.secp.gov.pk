<?php

require_once 'conn.php';



     $result = "SELECT * FROM sdms_agency_tehsils";

    $query = mysqli_query($conn, $result);

// check for empty result

    if ($query->num_rows > 0) {

// looping through all results

// products node

        $response["provence"] = array();

        while ($row = mysqli_fetch_assoc($query)) {

// temp user array

            $subproduct["city_id"] = $row["AgencyTehsil_ID"];
            $subproduct["city_name"] = $row["AgencyTehsil_Name"];
			$subproduct["province_id"] = $row["Tehsil_ID"];
			

            array_push($response["provence"], $subproduct);

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
?>