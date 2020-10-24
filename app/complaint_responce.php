<?php
require('../ach/client.inc.php');
define('SOURCE','Web'); //Ticket source.
require_once(INCLUDE_DIR.'class.query.php');
require_once(INCLUDE_DIR.'class.dept.php');
require_once(INCLUDE_DIR.'class.status.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');
//$errors=array();
$response["query_responce"] = array();
$vars=array();
if (isset($_REQUEST['complaint_no'])) 
{	
		
	$result = "SELECT * FROM sdms_ticket where ticketID = '".$_REQUEST['query_no']."'";
	$query = mysql_query($result);
	$row = mysql_fetch_array($query);
	
	
	$vars['id'] = $_REQUEST['complaint_no'];
	$vars['message'] = $_REQUEST['message'];
	$vars['complaint_status'] =  $row["complaint_status"];
	$vars['files'] = AttachmentFile::format($_FILES['attachments'], true);
	$vars['title'] = 'User Reply';
	$query=Query::lookupByExtId($vars['id']);
	//$query=Query::lookupByExtId($_REQUEST['id']);
	$query->postMessage($vars, 'Web');
	
	
	//array_push($response["query"], $subproduct);
		 
	 $response["success"] = 1;
	 $response["file"] = $_FILES["attachments"]["name"];
	 $response["message"] = "Message Posted Successfully";
	 echo json_encode($response);
}
 else {
       $response["success"] = 0;
       $response["message"] = "Required field(s) is missing";
       echo json_encode($response);
      }		 
?>