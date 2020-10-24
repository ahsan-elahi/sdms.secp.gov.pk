<?php
require_once 'conn.php';

if ($_REQUEST['user_mobile'] != '') {
    $IP_address = '';

    $phone = $_REQUEST['user_mobile'];
    $digits = 4;
    $message = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
    $message = urlencode($message);
    $code = $message;

    $sql_verify_sms = "INSERT INTO `sdms_sms_verify` (`user_mobile`, `ip_address`, `sms_code`) VALUES ('" . $phone . "', '" . $IP_address . "', '" . $message . "')";
    $query = mysqli_query($conn, $sql_verify_sms);
    $message = "Your Verification Code is " . $message;
    $message = urlencode($message);

    $curl = curl_init();
    $a=curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'http://119.160.92.2:7700/sendsms_url.html?Username=03028504208&Password=123.123&From=8181&To=' . $phone . '&Message=' . $code . '',
        CURLOPT_USERAGENT => 'SDMS'
    ));
    echo 'http://119.160.92.2:7700/sendsms_url.html?Username=03028504208&Password=123.123&From=8181&To=' . $phone . '&Message=' . $code . '';
        die;
    //echo 'http://119.160.92.2:7700/sendsms_url.html?Username=03028504208&Password=123.123&From=8181&To='.$phone.'&Message='.$message.'';
    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    curl_close($curl);
// products node
    $response["verf_mesg"] = array();
// temp user array
    $subproduct["message"] = $message;
    $subproduct["code"] = $code;
    array_push($response["verf_mesg"], $subproduct);
// success
// echoing JSON response
    echo json_encode($response);
} else {
// no products found
    $response["success"] = 0;
    $response["message"] = "No Mobile Number Found";
// echo no users JSON
    echo json_encode($response);
}
?>