<?php
require_once 'conn.php';
$dept_id= $_REQUEST['dept_id'];
if (isset($dept_id)) {
    $result = "SELECT * FROM sdms_staff where dept_id = '".$_REQUEST['dept_id']."' AND isfocalperson='1'";
    $query = mysqli_query($conn, $result);
// check for empty result
    if ($query->num_rows > 0) {
// looping through all results
// products node
        $response["focalperson"] = array();
       $row = mysqli_fetch_assoc($query);
// temp user array
            $subproduct["assignId"] = $row["staff_id"];
            array_push($response["focalperson"], $subproduct);
        
// success
// echoing JSON response
        $response["success"] = 1;
        $response["message"] = "Focal Person";
        echo json_encode($response);
    } else {
// no products found
        $response["success"] = 0;
        $response["message"] = "No Focal Person Found";

// echo no users JSON
        echo json_encode($response);
    }
}
?>