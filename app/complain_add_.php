<?php
require('../ach/client.inc.php');
define('SOURCE','APP'); //Ticket source.
require_once(INCLUDE_DIR.'class.ticket.php');
require_once(INCLUDE_DIR.'class.dept.php');
require_once(INCLUDE_DIR.'class.status.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');


if (isset($_REQUEST['name'])) 
{
	$_REQUEST['isquery']='0';
	$_REQUEST['topicId']='43';
	
    	$query = "SELECT * FROM sdms_staff where dept_id = '".$_REQUEST['deptId']."' AND isfocalperson='1'";
        $result = mysql_query($query);
        $row = mysql_fetch_array($result);
	    $_REQUEST["assignId"] = $row["staff_id"];
		$vars = $_REQUEST;
		
	
		$response["complain"] = array();
	if(($ticket=TICKET::create($vars, $errors, SOURCE))){
	
	
		if($vars['assignId']) { //Assign ticket to staff or team.
		$vars['note'] = 'Compaling Lodge from WebSite';
            $ticket->assign($vars['assignId'], $vars['note']);
        }
	
		 $subproduct["token_no"]=$ticket->getId();
		 $subproduct["token_pin"]=$ticket->getPincode();
		 $subproduct["username"]=$ticket->getName();
		 array_push($response["complain"], $subproduct);
	 }
	 $response["success"] = 1;
	 $response["message"] = "Complaint Created Successfully";
	 echo json_encode($response);
	 require('firebase/notification.php');
	 
}
 else {
       $response["success"] = 0;
       $response["message"] = "Required field(s) is missing";
       echo json_encode($response);
      }		 
?>