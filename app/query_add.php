<?php
require('../ach/client.inc.php');
define('SOURCE','APP'); //Ticket source.
require_once(INCLUDE_DIR.'class.ticket.php');
require_once(INCLUDE_DIR.'class.dept.php');
require_once(INCLUDE_DIR.'class.status.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');
$errors=array();
//csrf_token(); 
//$vars=array();
if (isset($_REQUEST['name'])) 
{	
	$_REQUEST['isquery']='1';
	$_REQUEST['topicId']='43';
	
	$result = "SELECT * FROM sdms_staff where dept_id = '".$_REQUEST['deptId']."' AND isfocalperson='1'";
        $query = mysql_query($result);
        $row = mysql_fetch_array($query);
        $_REQUEST["assignId"] = $row["staff_id"];
		//$_REQUEST["__CSRFToken__"] = "NDk1ZWI4OGUzOTgzNzRiYWM4NmI5ZjVjMDM4YjY1NTAwMzVlODE0NQ==";
     
		
		$vars = $_REQUEST;
			//exit;
		$response["query"] = array();
//if(($query=Query::create($vars, $errors, SOURCE))){
	if(($query = Ticket::create($vars, $errors, SOURCE))){
		
	if($vars['assignId']) { //Assign ticket to staff or team.
		$vars['note'] = 'Compaling Lodge from APP';
            $query->assign($vars['assignId'], $vars['note']);
        }
		
		 $subproduct["token_no"]=$query->getId();
		 $subproduct["token_pin"]=$query->getPincode();
		 $subproduct["username"]=$query->getName();
		 array_push($response["query"], $subproduct);
		 $notifiticketid=$query->getId();		
	 }
	 $response["success"] = 1;
	 $response["message"] = "Query Created Successfully";
	 echo json_encode($response);
	 require('firebase/notification_query.php');
	 
}
 else {
       $response["success"] = 0;
       $response["message"] = "Required field(s) is missing";
       echo json_encode($response);
      }		 
?>