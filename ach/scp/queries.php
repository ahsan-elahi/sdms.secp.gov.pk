<?php
require('staff.inc.php');
require_once(INCLUDE_DIR.'class.query.php');
require_once(INCLUDE_DIR.'class.dept.php');
require_once(INCLUDE_DIR.'class.status.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');
require_once(INCLUDE_DIR.'class.staff.php');
require_once(INCLUDE_DIR.'class.status.php');
require_once(INCLUDE_DIR.'class.districts.php');

$chairman= new Staff($_SESSION['_staff']['userID']);
if(!$_SESSION['counter'])
{
	if( $chairman->isFocalPerson()==1)
	{
	$_SESSION['counter'] = 1;	
	//@header("Location: .php");
	@header("Location: queries.php");
	
	?>
<!--<script>window.location.replace('admin_dashboard.php');</script>-->
    <?php 
	}
	elseif( $chairman->onChairman()==1)
	{
	$_SESSION['counter'] = 1;	
	//@header("Location: admin_dashboard.php");
	@header("Location: queries.php");
	}
}


//$chairman->onChairman()==1 ||
////////////////atta code ////////////////////////////////////////////
if(isset($_REQUEST['id']) ){
$query22="select ticketID from sdms_ticket where ticket_id='".$_REQUEST['id']."'";
	$res22=mysql_query($query22)or die("error in query1");
	$row22=mysql_fetch_assoc($res22);
	$count22=mysql_num_rows($res22);
	if($count22>0){
	$query="select * from sdms_complaint_views where complaint_id='".$row22['ticketID']."'";
	$res=mysql_query($query)or die("error in query12");
	$row=mysql_fetch_assoc($res);
	$increase=$row['views']+1;
	$count=mysql_num_rows($res);
	//echo $count;exit;
	if($count>0){
	 $query2="update sdms_complaint_views set `views` = '".$increase."' where complaint_id='".$row22['ticketID']."'";
    mysql_query($query2)or die('error in updatation query');
	}else{
	 $query2="insert into sdms_complaint_views(views,complaint_id) values('".$increase."','".$row22['ticketID']."')";
    mysql_query($query2)or die('error in insertion query');
	}
}}
if($_REQUEST['basic_search']=='Search'){
	 $query22="select ticketID from sdms_ticket where ticketID='".$_REQUEST['query']."'";
	$res22=mysql_query($query22)or die("error in query123");
	$row22=mysql_fetch_assoc($res22);
	$count22=mysql_num_rows($res22);
	//echo $count;exit;
	if($count22>0){
	 $query="select * from sdms_complaint_views where complaint_id='".$_REQUEST['query']."'";
	$res=mysql_query($query)or die("error in query1234");
	$row=mysql_fetch_assoc($res);
	$increase=$row['views']+1;
	$count=mysql_num_rows($res);
	//echo $count;exit;
	/*
	 id
	 ticket id  
	 ticket_stauts 
	 ip_address
	 mob_nos
	 query_type
	 time_stamp 
	*/
	if($count>0){
	 $query2="update sdms_complaint_views set `views`='".$increase."' where complaint_id='".$_REQUEST['query']."'";
    mysql_query($query2)or die('error in updatation query');
	}else{
	 $query2="insert into sdms_complaint_views(views,complaint_id) values('".$increase."','".$_REQUEST['query']."')";
    mysql_query($query2)or die('error in insertion query');
	}
}

}
$page='';
$query=null; //clean start.
//LOCKDOWN...See if the id provided is actually valid and if the user has access.

if($_REQUEST['id']) {
	
	 if(!($query=Query::lookup($_REQUEST['id'])))
         $errors['err']='Unknown or invalid query ID';
		 
    elseif(!$query->checkStaffAccess($thisstaff)) {

        $errors['err']='Access denied. Contact admin if you believe this is in error';

        $query=null; //Clear query obj.

    }

}
if($_REQUEST['subdeptviewid']) {

	 if(!($query=Query::lookup($_REQUEST['subdeptviewid'])))
         $errors['err']='Unknown or invalid ticket ID';

}
//At this stage we know the access status. we can process the post.
if($_POST && !$errors):
    if($query && $query->getId()) {
        //More coffee please.
		//'N'=>'note','A'=>Affidavite,'M'=>'message','R'=>'response'
        $errors=array();
        $lock=$query->getLock(); //Query lock if any
        $statusKeys=array('open'=>'Open','Reopen'=>'Open','Close'=>'Closed');
        switch(strtolower($_POST['a'])):
		 case 'postnote': /* Post Internal Note */
         //Make sure the staff can set desired state
            if($_POST['state']) {
                if($_POST['state']=='closed' && !$thisstaff->canCloseTickets())
                    $errors['state'] = "You don't have permission to close Complaints";
                elseif(in_array($_POST['state'], array('overdue', 'notdue', 'unassigned'))
                        && (!($dept=$query->getDept()) || !$dept->isManager($thisstaff)))
                    $errors['state'] = "You don't have permission to set the state";
            }
            $wasOpen = ($query->isOpen());
            $vars = $_POST;
            if($_FILES['attachments'])
                $vars['files'] = AttachmentFile::format($_FILES['attachments']);

            if($_POST['status_pid']=='2' || $_POST['status_pid']=='5'){
				$sql_checking_status = "SELECT status_id FROM `sdms_status` where status_id = '".$_POST['complaint_status']."' AND `p_id`=2 OR `p_id`=5";
				$res_checking_status = mysql_query($sql_checking_status);
				if(mysql_num_rows($res_checking_status)>0)
				{
				if(($note=$query->postNote($vars, $errors, $thisstaff))) {
				$msg='Internal note posted successfully';
				$query->close();

				$msg .='. Query #'.$query->getExtId().' status set to CLOSED ';
				$note = $_POST['note'];
				$query->logNote('Query Closed.', $note,$query->complaint_status_title(), $thisstaff);

                if($wasOpen && $query->isClosed())
                $query = null; //Going back to main listing.
			 } else {
                if(!$errors['err'])
                    $errors['err'] = 'Unable to post internal note - missing or invalid data.';
                $errors['postnote'] = 'Unable to post the note. Correct the error(s) below and try again!';
            }
				}else{
				$errors['err'] = 'Unable to post internal note - missing or invalid data.';
                $errors['postnote'] = 'Unable to post the note. Correct the error(s) below and try again!';
				}
			
			
			} 
			else{
			if(($note=$query->postNote($vars, $errors, $thisstaff))) {
				$msg='Internal note posted successfully';
                if($wasOpen && $query->isClosed())
                    $query = null; //Going back to main listing.
			 } else {
                if(!$errors['err'])
                    $errors['err'] = 'Unable to post internal note - missing or invalid data.';
                $errors['postnote'] = 'Unable to post the note. Correct the error(s) below and try again!';
            }
				}
            break;			
	/*===================================================================================================================================================*/		
		 case 'postaffidavite': /* Post Affidavite */
		 //Make sure the staff can set desired state
            if($_POST['state']) {
                if($_POST['state']=='closed' && !$thisstaff->canCloseTickets())
                    $errors['state'] = "You don't have permission to close Complaints";
                elseif(in_array($_POST['state'], array('overdue', 'notdue', 'unassigned'))
                        && (!($dept=$query->getDept()) || !$dept->isManager($thisstaff)))
                    $errors['state'] = "You don't have permission to set the state";
            }
            $wasOpen = ($query->isOpen());
			$vars = $_POST;
			if($_FILES['attachments'])
                $vars['files'] = AttachmentFile::format($_FILES['attachments']);
            if(($affidavite=$query->postAffidavte($vars, $errors, $thisstaff))) {
				$msg='Affidavite is  posted successfully';
                if($wasOpen && $query->isClosed())
                    $query = null; //Going back to main listing.
		    } else {
			if(!$errors['err'])
		$errors['err'] = 'Unable to post Affidavite - missing or invalid data.';
		$errors['postnote'] = 'Unable to post the Affidavite. Correct the error(s) below and try again!';
		     }
		 break;
	/*===================================================================================================================================================*/		
		 case 'assign': /* Assign Query To Legal Officer/Madam/CJ etc*/
		 if(!$thisstaff->canAssignTickets())
			 $errors['err']=$errors['assign'] = 'Action Denied. You are not allowed to assign/reassign Complaints.';
		 else {
			 $id = preg_replace("/[^0-9]/", "",$_POST['assignId']);
			 $claim = (is_numeric($_POST['assignId']) && $_POST['assignId']==$thisstaff->getId());
			 if(!$_POST['assignId'] || !$id)
				 $errors['assignId'] = 'Select assignee';
			 elseif($_POST['assignId'][0]!='s' && $_POST['assignId'][0]!='t' && !$claim)
				 $errors['assignId']='Invalid assignee ID - get technical support';
			 elseif($query->isAssigned()) {
				 if($_POST['assignId'][0]=='s' && $id==$query->getStaffId())
					 $errors['assignId']='Query already assigned to the staff.';
				 elseif($_POST['assignId'][0]=='t' && $id==$query->getTeamId())
					 $errors['assignId']='Query already assigned to the team.';
			 }
/*			 if($_POST['dept_id_new']!='27' && $_POST['dept_id_new']!=''){
				 $sql_complaintstatus=' Update '.TICKET_TABLE.' SET  dept_id = '.$_POST['dept_id_new'].' , sla_id = '.$_POST['slaId'].'  where ticket_id = '.db_input($query->getId());
		db_query($sql_complaintstatus);
				 }*/
			 //Comments are not required on self-assignment (claim)
			 if($claim && !$_POST['assign_comments'])
				 $_POST['assign_comments'] = 'Query claimed by '.$thisstaff->getName();
			 elseif(!$_POST['assign_comments'])
				 $errors['assign_comments'] = 'Assignment comments required';
			 elseif(strlen($_POST['assign_comments'])<5)
					 $errors['assign_comments'] = 'Comment too short';
			 if(!$errors && $query->assign($_POST['assignId'], $_POST['assign_comments'],$_POST['complaint_status'], !$claim)) {
				  if($_POST['assignId']=='s129')
				 {
					$transfer_dept  = "UPDATE `sdms_ticket` SET `dept_id`='1' Where `ticket_id`='".$_POST['id']."'";
					mysql_query($transfer_dept);
				  }
				 if($claim) {
					 $msg  = 'Query is NOW assigned to you!';
				 } else {
					 $msg  ='Query assigned successfully to '.$query->getAssigned();
					 TicketLock::removeStaffLocks($thisstaff->getId(), $query->getId());
					 $query=null;
				 }
				 
				if($_POST['status_pid']=='2' || $_POST['status_pid']=='5')
				{
					$query->close();
					$msg .='. Query #'.$query->getExtId().' status set to CLOSED ';
					$note = $_POST['assign_comments'];
					$query->logNote('Query Closed', $note,$query->complaint_status_title(), $thisstaff);
				} 
			 } elseif(!$errors['assign']) {
				 $errors['err'] = 'Unable to complete the Query assignment';
				 $errors['assign'] = 'Correct the error(s) below and try again!';
			 }
		 }
		break;	 	/*===================================================================================================================================================*/	
		case 'dept': /** Query Forward to Department For Department Comunication**/
			if(!$thisstaff->canPostReply())
                $errors['err'] = 'Action denied. Contact admin for access';
            else {
                if(!$_POST['response'])
                    $errors['response']='Response required';
                //Use locks to avoid double replies
                if($lock && $lock->getStaffId()!=$thisstaff->getId())
                    $errors['err']='Action Denied. Query is locked by someone else!';
                //Make sure the email is not banned
                if(!$errors['err'] && TicketFilter::isBanned($query->getEmail()))
                    $errors['err']='Email is in banlist. Must be removed to reply.';
            }
            $wasOpen =($query->isOpen());
            //If no error...do the do.
            $vars = $_POST;
            if(!$errors && $_FILES['attachments'])
                $vars['files'] = AttachmentFile::format($_FILES['attachments']);
				
//            if(!$errors && ($response_to_department=$query->postDepartment($vars, $errors)) && !$query->pdfExport_Department($vars['response'])) {
			  if(!$errors && ($response_to_department=$query->postDepartment($vars, $errors)) ) {
		        $msg='Department posted successfully';
				//$query->pdfExport_Department('asdfasdfasdf');
				//$query->pdfExport_Department($vars['response']);
				$_SESSION['respone_title']=$vars['title'];
				$_SESSION['respone_note']=$vars['response'];
				
				
                $query->reload();
                if($query->isClosed() && $wasOpen)
                    $query=null;
            } elseif(!$errors['err']) {
                $errors['err']='Unable to post the Department. Correct the errors below and try again!';
            }
            break;
	/*===================================================================================================================================================*/
		case 'reply':
            if(!$thisstaff->canPostReply())
                $errors['err'] = 'Action denied. Contact admin for access';
            else {
                if(!$_POST['response'])
                    $errors['response']='Response required';
                //Use locks to avoid double replies
                if($lock && $lock->getStaffId()!=$thisstaff->getId())
                    $errors['err']='Action Denied. Query is locked by someone else!';
                //Make sure the email is not banned
                if(!$errors['err'] && TicketFilter::isBanned($query->getEmail()))
                    $errors['err']='Email is in banlist. Must be removed to reply.';
            }
            $wasOpen =($query->isOpen());
            //If no error...do the do.
            $vars = $_POST;
            if(!$errors && $_FILES['attachments'])
                $vars['files'] = AttachmentFile::format($_FILES['attachments']);
            if(!$errors && ($response=$query->postReply($vars, $errors, isset($_POST['emailreply'])))) {
                $msg='Reply posted successfully';
			if($_POST['status_pid']=='2' || $_POST['status_pid']=='5')
				{
					$query->close();
					$msg .='. Query #'.$query->getExtId().' status set to CLOSED ';
					$note = $_POST['response'];
					$query->logNote('Query Closed', $note,$query->complaint_status_title(), $thisstaff);
				} 	
                $query->reload();
                if($query->isClosed() && $wasOpen)
                    $query=null;
            } elseif(!$errors['err']) {
                $errors['err']='Unable to post the reply. Correct the errors below and try again!';
            }
            break;
	/*===================================================================================================================================================*/
		case 'accepted':
		$vars = $_POST;
		if(($query=Query::accepted($vars, $errors))) {			
			$msg='Query Accepted successfully';
			$query->reload();		
		} elseif(!$errors['err']) {	
		$errors['err']='Unable to accept the Query. Correct the error(s) and try again';			
		}		
            break;
		case 'deny':
		$vars = $_POST;
		if(($query=Query::deny($vars, $errors))) {			
			$msg='Query Deny successfully';
			$query=null; 
		} elseif(!$errors['err']) {	
		$errors['err']='Unable to deny the Query. Correct the error(s) and try again';			
		}		
            break;	
		case 'category':
		$vars = $_POST;
		if(($query=Query::set_category($vars, $errors))) {			
			$msg='Category Added Successfully';
			$query->reload();		
		} elseif(!$errors['err']) {	
		$errors['err']='Unable to accept the Query. Correct the error(s) and try again';			
		}		
            break;
	/*===================================================================================================================================================*/		
	//==========================================================================================================//
	/*Extra*/	
			case 'transfer': /** Transfer ticket For Department Comunication**/
            //Check permission
            if(!$thisstaff->canTransferTickets())
                $errors['err']=$errors['transfer'] = 'Action Denied. You are not allowed to transfer Complaints.';
            else {
				
				$sql_check_focal_person = "SELECT * FROM `sdms_staff` where dept_id = '".$_POST['deptId']."' AND isfocalperson = '1'";
				$res_check_focal_person = mysql_query($sql_check_focal_person);
				$ow_check_focal_person = mysql_fetch_array($res_check_focal_person);
				
                //Check target dept.
                if(!$_POST['deptId'])
                    $errors['deptId'] = 'Select department';
                elseif($_POST['deptId']==$query->getDeptId())
                    $errors['deptId'] = 'Query already in the department';
                elseif(!($dept=Dept::lookup($_POST['deptId'])))
                    $errors['deptId'] = 'Unknown or invalid department';
					
				if($ow_check_focal_person['staff_id'] != $_POST['assignId'] )
                    $errors['focal_persone'] = 'Focal Person Not Selected';	
                //Transfer message - required.
                if(!$_POST['transfer_comments'])
                    $errors['transfer_comments'] = 'Transfer comments required';
                elseif(strlen($_POST['transfer_comments'])<5)
                    $errors['transfer_comments'] = 'Transfer comments too short!';
                //If no errors - them attempt the transfer.
                if(!$errors && $query->transfer($_POST['deptId'], $_POST['transfer_comments'])) {
					 $query->assign($_POST['assignId'], $_POST['transfer_comments'],$_POST['complaint_status'], !$claim);
					 
                    $msg = 'Query transferred successfully to '.$query->getDeptName();
                    //Check to make sure the staff still has access to the ticket
                    if(!$query->checkStaffAccess($thisstaff))
                        $query=null;
                } elseif(!$errors['transfer']) {
                    $errors['err'] = 'Unable to complete the Query transfer';
                    $errors['transfer']='Correct the error(s) below and try again!';
                }
            }
            break;	  			
	//=========================================================================================================//			
        case 'edit':
        case 'update':
            if(!$query || !$thisstaff->canEditTickets())
                $errors['err']='Perm. Denied. You are not allowed to edit Complaints';
            elseif($query->update($_POST,$errors)) {
                $msg='Query updated successfully';
                $_REQUEST['a'] = null; //Clear edit action - going back to view.
                //Check to make sure the staff STILL has access post-update (e.g dept change).
                if(!$query->checkStaffAccess($thisstaff))
                    $query=null;
            } elseif(!$errors['err']) {
                $errors['err']='Unable to update the Query. Correct the errors below and try again!';
            }
            break;
        case 'process':
            switch(strtolower($_POST['do'])):
                case 'close':
                    if(!$thisstaff->canCloseTickets()) {

                        $errors['err'] = 'Perm. Denied. You are not allowed to close Complaints.';

                    } elseif($query->isClosed()) {

                        $errors['err'] = 'Query is already closed!';

                    } elseif($query->close()) {

                        $msg='Query #'.$query->getExtId().' status set to CLOSED';

                        //Log internal note

                        if($_POST['ticket_status_notes'])
                            $note = $_POST['ticket_status_notes'];
                        else
                            $note='Query closed (without comments)';
						
					
                        $query->logNote('Query Closed', $note,$query->complaint_status_title(), $thisstaff);

                        //Going back to main listing.
                        TicketLock::removeStaffLocks($thisstaff->getId(), $query->getId());
                        $page=$query=null;
                    } else {
                        $errors['err']='Problems closing the Query. Try again';

                    }

                    break;
                case 'reopen':
                    //if staff can close or create queries ...then assume they can reopen.

                   if(!$thisstaff->canCloseTickets() && !$thisstaff->canCreateTickets()) {
                       $errors['err']='Perm. Denied. You are not allowed to reopen Complaints.';
                    } elseif($query->isOpen()) {
                        $errors['err'] = 'Query is already open!';
                    } elseif($query->reopen()) {
                        $msg='Query REOPENED';

                        if($_POST['ticket_status_notes'])
                            $note = $_POST['ticket_status_notes'];
                        else
                            $note='Query reopened (without comments)';

         $query->logNote('Query Reopened', $note,'6', $thisstaff);
                    } else {
                        $errors['err']='Problems reopening the Query. Try again';
                    }

                    break;
                case 'release':
                    if(!$query->isAssigned() || !($assigned=$query->getAssigned())) {

                        $errors['err'] = 'Query is not assigned!';

                    } elseif($query->release()) {

                        $msg='Query released (unassigned) from '.$assigned;

                        $query->logActivity('Query unassigned',$msg.' by '.$thisstaff->getName());

                    } else {

                        $errors['err'] = 'Problems releasing the Query. Try again';

                    }

                    break;
                case 'claim':
                    if(!$thisstaff->canAssignTickets()) {

                        $errors['err'] = 'Perm. Denied. You are not allowed to assign/claim Complaints.';

                    } elseif(!$query->isOpen()) {

                        $errors['err'] = 'Only open Complaints can be assigned';

                    } elseif($query->isAssigned()) {

                        $errors['err'] = 'Query is already assigned to '.$query->getAssigned();

                    } elseif($query->assignToStaff($thisstaff->getId(), ('Query claimed by '.$thisstaff->getName()), false)) {

                        $msg = 'Query is now assigned to you!';

                    } else {

                        $errors['err'] = 'Problems assigning the Query. Try again';

                    }

                    break;
                case 'overdue':
                    $dept = $query->getDept();

                    if(!$dept || !$dept->isManager($thisstaff)) {

                        $errors['err']='Perm. Denied. You are not allowed to flag queries overdue';

                    } elseif($query->markOverdue()) {

                        $msg='Query flagged as overdue';

                        $query->logActivity('Query Marked Overdue',($msg.' by '.$thisstaff->getName()));

                    } else {

                        $errors['err']='Problems marking the the Query overdue. Try again';

                    }

                    break;
                case 'answered':
                    $dept = $query->getDept();

                    if(!$dept || !$dept->isManager($thisstaff)) {

                        $errors['err']='Perm. Denied. You are not allowed to flag Complaints';

                    } elseif($query->markAnswered()) {

                        $msg='Query flagged as answered';

                        $query->logActivity('Query Marked Answered',($msg.' by '.$thisstaff->getName()));

                    } else {

                        $errors['err']='Problems marking the the Query answered. Try again';

                    }

                    break;
                case 'unanswered':
                    $dept = $query->getDept();

                    if(!$dept || !$dept->isManager($thisstaff)) {

                        $errors['err']='Perm. Denied. You are not allowed to flag Complaints';

                    } elseif($query->markUnAnswered()) {

                        $msg='Query flagged as unanswered';

                        $query->logActivity('Query Marked Unanswered',($msg.' by '.$thisstaff->getName()));

                    } else {

                        $errors['err']='Problems marking the the Query unanswered. Try again';

                    }

                    break;
                case 'banemail':
                    if(!$thisstaff->canBanEmails()) {

                        $errors['err']='Perm. Denied. You are not allowed to ban emails';

                    } elseif(BanList::includes($query->getEmail())) {

                        $errors['err']='Email already in banlist';

                    } elseif(Banlist::add($query->getEmail(),$thisstaff->getName())) {

                        $msg='Email ('.$query->getEmail().') added to banlist';

                    } else {

                        $errors['err']='Unable to add the email to banlist';

                    }

                    break;
                case 'unbanemail':
                    if(!$thisstaff->canBanEmails()) {

                        $errors['err'] = 'Perm. Denied. You are not allowed to remove emails from banlist.';

                    } elseif(Banlist::remove($query->getEmail())) {

                        $msg = 'Email removed from banlist';

                    } elseif(!BanList::includes($query->getEmail())) {

                        $warn = 'Email is not in the banlist';

                    } else {

                        $errors['err']='Unable to remove the email from banlist. Try again.';

                    }

                    break;
                case 'delete': // Dude what are you trying to hide? bad customer support??
                    if(!$thisstaff->canDeleteTickets()) {

                        $errors['err']='Perm. Denied. You are not allowed to DELETE Complaints!!';

                    } elseif($query->delete()) {

                        $msg='Query #'.$query->getNumber().' deleted successfully';

                        //Log a debug note

                        $ost->logDebug('Query #'.$query->getNumber().' deleted',
                                sprintf('Query #%s deleted by %s',
                                    $query->getNumber(), $thisstaff->getName())
                                );
                        $query=null; //clear the object.
                    } else {
                        $errors['err']='Problems deleting the Query. Try again';
                    }

                    break;
                default:
                    $errors['err']='You must select action to perform';
            endswitch;
            break;
        default:
            $errors['err']='Unknown action';
        endswitch;
        if($query && is_object($query))
            $query->reload();//Reload ticket info following post processing
     }elseif($_POST['a']) {
        switch($_POST['a']) {
            case 'mass_process':
                if(!$thisstaff->canManageTickets())
                    $errors['err']='You do not have permission to mass manage Complaints. Contact admin for such access';
                elseif(!$_POST['tids'] || !is_array($_POST['tids']))
                    $errors['err']='No Complaints selected. You must select at least one Query.';
                else {
                    $count=count($_POST['tids']);
                    $i = 0;
                    switch(strtolower($_POST['do'])) {
					case 'reopen':
						if($thisstaff->canCloseTickets() || $thisstaff->canCreateTickets()) {
					
							$note='Query reopened by '.$thisstaff->getName();
					
							foreach($_POST['tids'] as $k=>$v) {
					
								if(($t=Query::lookup($v)) && $t->isClosed() && @$t->reopen()) {
					
									$i++;
					
									$t->logNote('Query Reopened', $note, $thisstaff);
					
								}
					
							}
					
					
					
							if($i==$count)
					
								$msg = "Selected Complaints ($i) reopened successfully";
					
							elseif($i)
					
								$warn = "$i of $count selected Complaints reopened";
					
							else
					
								$errors['err'] = 'Unable to reopen selected Complaints';
					
						} else {
					
							$errors['err'] = 'You do not have permission to reopen Complaints';
					
						}
						break;
					
					case 'close':
							if($thisstaff->canCloseTickets()) {
						
								$note='Query closed without response by '.$thisstaff->getName();
						
								foreach($_POST['tids'] as $k=>$v) {
						
									if(($t=Query::lookup($v)) && $t->isOpen() && @$t->close()) {
						
										$i++;
						
										$t->logNote('Query Closed', $note, $thisstaff);
						
									}
						
								}
								if($i==$count)
									$msg ="Selected Complaints ($i) closed succesfully";
								elseif($i)
									$warn = "$i of $count selected Complaints closed";
								else
									$errors['err'] = 'Unable to close selected Complaints';
							} else {
								$errors['err'] = 'You do not have permission to close Complaints';
							}
							break;					
					case 'mark_overdue':
						$note='Query flagged as overdue by '.$thisstaff->getName();
						foreach($_POST['tids'] as $k=>$v) {
						if(($t=Query::lookup($v)) && !$t->isOverdue() && $t->markOverdue()) {
								$i++;
								$t->logNote('Query Marked Overdue', $note, $thisstaff);
							}
						}
						if($i==$count)
							$msg = "Selected Complaints ($i) marked overdue";
						elseif($i)
							$warn = "$i of $count selected Complaints marked overdue";
						else
							$errors['err'] = 'Unable to flag selected Complaints as overdue';
						break;
					case 'delete':
						if($thisstaff->canDeleteTickets()) {
							foreach($_POST['tids'] as $k=>$v) {
								if(($t=Query::lookup($v)) && @$t->delete()) $i++;
							}
					
					
					
							//Log a warning
					
							if($i) {
					
								$log = sprintf('%s (%s) just deleted %d Query(s)',
					
										$thisstaff->getName(), $thisstaff->getUserName(), $i);
					
								$ost->logWarning('Complaints deleted', $log, false);
					
					
					
							}
					
					
					
							if($i==$count)
					
								$msg = "Selected Complaints ($i) deleted successfully";
					
							elseif($i)
					
								$warn = "$i of $count selected Complaints deleted";
					
							else
					
								$errors['err'] = 'Unable to delete selected Complaints';
					
						} else {
					
							$errors['err'] = 'You do not have permission to delete Complaints';
					
						}
					
						break;
					
					default:
					
						$errors['err']='Unknown or unsupported action - get technical help';
					
					}
					
					}
					
					break;
				    case 'open':
					$query=null;		
					if(!$thisstaff || !$thisstaff->canCreateTickets()) {			
					$errors['err']='You do not have permission to create Complaints. Contact admin for such access';			
					} else {			
					
					$vars = $_POST;	
					//if($_FILES['attachments'])			
					//$vars['files'] = AttachmentFile::format($_FILES['attachments']);		
					if(($query = Query::open($vars, $errors))) {			
					$msg='Query created successfully';
					$new_query = 'Query created successfully';	
					if($vars['deptId'] == 1 && $vars['query_rsolved']==1)
					{
					//echo $query->getStaffId();exit;
					$query->close_resolved_query();
					$msg .='. Query #'.$query->getExtId().' status set to CLOSED ';
					$note = 'Query Closed';
					$query->logNote('Query Closed', $note,'40', $thisstaff);
					} 		
					$_REQUEST['a']=null;			
					// || $query->isClosed()
					if(!$query->checkStaffAccess($thisstaff))			
					$query=null;				
					} elseif(!$errors['err']) {	
					$errors['err']='Unable to create the Query. Correct the error(s) and try again';			
					}			
					}
					break;
        }
    }
    if(!$errors)
        $thisstaff ->resetStats(); //We'll need to reflect any changes just made!
	endif;
/*... Quick stats ...*/
$stats= $thisstaff->getTicketsStats();
$nav->setTabActive('queries');
$inc = 'queries.inc.php';
if($query) {
    $ost->setPageTitle('Query #'.$query->getNumber());
    $nav->setActiveSubMenu(-1);
    $inc = 'query-view.inc.php';
    if($_REQUEST['a']=='edit' && $thisstaff->canEditTickets())
        $inc = 'query-edit.inc.php';
	elseif($_REQUEST['a'] == 'print' && !$query->pdfExport($_REQUEST['psize'], $_REQUEST['notes']))
        $errors['err'] = 'Internal error: Unable to export the query to PDF for print.';
	elseif($_REQUEST['a'] == 'edit_thread' && !$query->updatethread($_REQUEST['sub'],$_REQUEST['comments'],$_REQUEST['t_id']))
        $errors['err'] = 'Internal error: Unable to Edit the Thread.';
		if($_REQUEST['a'] == 'edit_thread')
		$msg='Thread successfully updated.';	
} else {
    $inc = 'queries.inc.php';
    if($_REQUEST['a']=='open' && $thisstaff->canCreateTickets())
          $inc = 'query-open.inc.php';
	elseif($_REQUEST['a']=='opencomplaint' && $thisstaff->canCreateTickets())
          $inc = 'ticket-open_query.inc.php';	  
	elseif($thisstaff->canCreateTickets() && !$thisstaff->isAdmin())
	$inc = 'queries-all.inc.php';
	elseif($_REQUEST['action'] == 'subdept')
	$inc = 'queries-subdept.inc.php';	
	   elseif($_REQUEST['a'] == 'export') {
        require_once(INCLUDE_DIR.'class.export.php');
        $ts = strftime('%Y%m%d');
        if (!($token=$_REQUEST['h']))
            $errors['err'] = 'Query token required';
        elseif (!($query=$_SESSION['search_'.$token]))
            $errors['err'] = 'Query token not found';
        elseif (!Export::saveTickets($query, "queries-$ts.csv", 'csv'))
            $errors['err'] = 'Internal error: Unable to dump query results';
    }
    //Clear active submenu on search with no status
    if($_REQUEST['a']=='search' && !$_REQUEST['status'])
        $nav->setActiveSubMenu(-1);
    //set refresh rate if the user has it configured
    if(!$_POST && !$_REQUEST['a'] && ($min=$thisstaff->getRefreshRate()))
        $ost->addExtraHeader('<meta http-equiv="refresh" content="'.($min*60).'" />');
}
require_once(STAFFINC_DIR.'header.inc.php');
require_once(STAFFINC_DIR.$inc);
require_once(STAFFINC_DIR.'footer.inc.php');
?>