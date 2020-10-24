<?php
require('../ach/client.inc.php');
define('SOURCE','Web'); //query source.
require_once(INCLUDE_DIR.'class.query.php');
require_once(INCLUDE_DIR.'class.dept.php');
require_once(INCLUDE_DIR.'class.status.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');
//$errors=array();
//$vars=array();

$result = "SELECT * FROM sdms_ticket where ticketID = '".$_REQUEST['ticketID']."'  AND ticketpin = '".$_REQUEST['ticketpin']."' AND isquery='1'";
//echo $result;
$query = mysql_query($result);
$num = mysql_num_rows($query);        
if ($num > 0) 
{
	//$query_id = $_REQUEST['ticketID'];
	//$query_pin = $_REQUEST['ticketpin'];

		$response["query"] = array();
		$subproduct = array();
		$response["query_thread"] = array();
		$subproduct_thread = array();
if($query=Query::lookup($_REQUEST['ticketID'])){
	
		//Application Info
		 $subproduct["query_no"]=$query->getId();
		 $subproduct["name"]=$query->getName();
		 $subproduct["email"]=$query->getEmail();
		 $subproduct["nic"]=$query->getNic();
		 $subproduct["phone"]=$query->getPhoneNumber();
		 $subproduct["applicant_address"]=$query->getApplicant_Address();
		 $subproduct["country"]=$query->getDistrict();
		 $subproduct["province"]=$query->getTehsil();
		 $subproduct["city"]=$query->getAgencyTehsilTitle();
		 //Query Information
		 $subproduct["status"]=ucfirst($query->getStatus());
		 $subproduct["department"]=$query->getDeptName();
		 
		 
		 //Query  Details
		$sql_complaint_details="Select * from sdms_ticket_thread where ticket_id='".$query->getId()."' ORDER By id limit 0,1";
		$res_complaint_details=mysql_query($sql_complaint_details);
		$row_complaint_details=mysql_fetch_array($res_complaint_details);
		$subproduct["subject"] = $query->getSubject();
		$subproduct["details"] = $row_complaint_details['body'];
		
		array_push($response["query"], $subproduct);		
		
		//Query Processing
		if($query->getThreadCount() && ($thread=$query->getClientThread())) {
		$threadType=array('M' => 'message', 'R' => 'response');
		foreach($thread as $entry) {
		//Making sure internal notes are not displayed due to backend MISTAKES!
		if(!$threadType[$entry['thread_type']]) continue;
		$poster = $entry['poster'];
		if($entry['thread_type']=='R' && ($cfg->hideStaffName() || !$entry['staff_id']))
		$poster = ' ';
		$subproduct_thread["created"]= Format::db_datetime($entry['created']); 
		$subproduct_thread["title"]= $entry['title'];
		
		$sql_fetchstatus = "Select * from sdms_status where status_id='".$entry['complaint_status']."'";
		$res_fetchstatus = mysql_query($sql_fetchstatus);
		$row_fetchstatus = mysql_fetch_array($res_fetchstatus);
		
		$sql_fetchpstatus = "Select * from sdms_status where status_id='".$row_fetchstatus['p_id']."'";
		$res_fetchpstatus = mysql_query($sql_fetchpstatus);
		$row_fetchpstatus = mysql_fetch_array($res_fetchpstatus);
		
			$subproduct_thread["status_title"]= $row_fetchpstatus['status_title'];
			$subproduct_thread["body"]= Format::display($entry['body']); 
		
		if($entry['attachments']
		&& ($tentry=$query->getThreadEntry($entry['id']))
		&& ($links=$tentry->getAttachmentsLinks())) {
			$subproduct_thread["links"]= '<br>'.$links; 
		} 
		else
		{
			$subproduct_thread["links"]= ""; 
		}
		array_push($response["query_thread"], $subproduct_thread);		
		}
}		  


		
	 
	 }
	 $response["success"] = 1;
	 $response["message"] = "Query View Successfully";
	 echo json_encode($response);
}
 else {
       $response["success"] = 0;
       $response["message"] = "No Record Found";
       echo json_encode($response);
      }		 
?>