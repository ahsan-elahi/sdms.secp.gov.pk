<?php
require('staff.inc.php');
require_once(INCLUDE_DIR.'class.ticket.php');
require_once(INCLUDE_DIR.'class.dept.php');
require_once(INCLUDE_DIR.'class.status.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');
require_once(INCLUDE_DIR.'class.staff.php');
require_once(INCLUDE_DIR.'class.status.php');

////////////////atta code ////////////////////////////////////////////
if(isset($_REQUEST['id']) ){
$query22="select ticketID from sdms_ticket where ticket_id='".$_REQUEST['id']."'";
	$res22=mysql_query($query22)or die("error in query");
	$row22=mysql_fetch_assoc($res22);
	$count22=mysql_num_rows($res22);
	if($count22>0){
	$query="select * from complaint_views where complaint_id='".$row22['ticketID']."'";
	$res=mysql_query($query)or die("error in query");
	$row=mysql_fetch_assoc($res);
	$increase=$row['views']+1;
	$count=mysql_num_rows($res);
	//echo $count;exit;
	if($count>0){
	 $query2="update complaint_views set `views` = '".$increase."' where complaint_id='".$row22['ticketID']."'";
    mysql_query($query2)or die('error in updatation query');
	}else{
	 $query2="insert into complaint_views(views,complaint_id) values('".$increase."','".$row22['ticketID']."')";
    mysql_query($query2)or die('error in insertion query');
	}
}}
if($_REQUEST['basic_search']=='Search'){
	 $query22="select ticketID from sdms_ticket where ticketID='".$_REQUEST['query']."'";
	$res22=mysql_query($query22)or die("error in query");
	$row22=mysql_fetch_assoc($res22);
	$count22=mysql_num_rows($res22);
	//echo $count;exit;
	if($count22>0){
	 $query="select * from complaint_views where complaint_id='".$_REQUEST['query']."'";
	$res=mysql_query($query)or die("error in query");
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
	 $query2="update complaint_views set `views`='".$increase."' where complaint_id='".$_REQUEST['query']."'";
    mysql_query($query2)or die('error in updatation query');
	}else{
	 $query2="insert into complaint_views(views,complaint_id) values('".$increase."','".$_REQUEST['query']."')";
    mysql_query($query2)or die('error in insertion query');
	}
}

}
$page='';
$ticket=null; //clean start.
//LOCKDOWN...See if the id provided is actually valid and if the user has access.

if($_REQUEST['id']) {

	 if(!($ticket=Ticket::lookup($_REQUEST['id'])))

         $errors['err']='Unknown or invalid ticket ID';

    elseif(!$ticket->checkStaffAccess($thisstaff)) {

        $errors['err']='Access denied. Contact admin if you believe this is in error';

        $ticket=null; //Clear ticket obj.

    }

}
//At this stage we know the access status. we can process the post.
if($_POST && !$errors):
    if($ticket && $ticket->getId()) {
        //More coffee please.
		//'N'=>'note','A'=>Affidavite,'M'=>'message','R'=>'response'
        $errors=array();
        $lock=$ticket->getLock(); //Ticket lock if any
        $statusKeys=array('open'=>'Open','Reopen'=>'Open','Close'=>'Closed');
        switch(strtolower($_POST['a'])):
		 case 'postnote': /* Post Internal Note */
         //Make sure the staff can set desired state
            if($_POST['state']) {
                if($_POST['state']=='closed' && !$thisstaff->canCloseTickets())
                    $errors['state'] = "You don't have permission to close Complaints";
                elseif(in_array($_POST['state'], array('overdue', 'notdue', 'unassigned'))
                        && (!($dept=$ticket->getDept()) || !$dept->isManager($thisstaff)))
                    $errors['state'] = "You don't have permission to set the state";
            }
            $wasOpen = ($ticket->isOpen());
            $vars = $_POST;
            if($_FILES['attachments'])
                $vars['files'] = AttachmentFile::format($_FILES['attachments']);
            if(($note=$ticket->postNote($vars, $errors, $thisstaff))) {
				$msg='Internal note posted successfully';
                if($wasOpen && $ticket->isClosed())
                    $ticket = null; //Going back to main listing.
			 } else {
                if(!$errors['err'])
                    $errors['err'] = 'Unable to post internal note - missing or invalid data.';
                $errors['postnote'] = 'Unable to post the note. Correct the error(s) below and try again!';
            }
            break;			
	/*===================================================================================================================================================*/		
		 case 'postaffidavite': /* Post Affidavite */
		 //Make sure the staff can set desired state
            if($_POST['state']) {
                if($_POST['state']=='closed' && !$thisstaff->canCloseTickets())
                    $errors['state'] = "You don't have permission to close Complaints";
                elseif(in_array($_POST['state'], array('overdue', 'notdue', 'unassigned'))
                        && (!($dept=$ticket->getDept()) || !$dept->isManager($thisstaff)))
                    $errors['state'] = "You don't have permission to set the state";
            }
            $wasOpen = ($ticket->isOpen());
			$vars = $_POST;
			if($_FILES['attachments'])
                $vars['files'] = AttachmentFile::format($_FILES['attachments']);
            if(($affidavite=$ticket->postAffidavte($vars, $errors, $thisstaff))) {
				$msg='Affidavite is  posted successfully';
                if($wasOpen && $ticket->isClosed())
                    $ticket = null; //Going back to main listing.
		    } else {
			if(!$errors['err'])
		$errors['err'] = 'Unable to post Affidavite - missing or invalid data.';
		$errors['postnote'] = 'Unable to post the Affidavite. Correct the error(s) below and try again!';
		     }
		 break;
	/*===================================================================================================================================================*/		
		 case 'assign': /* Assign Complaint To Legal Officer/Madam/CJ etc*/
		 if(!$thisstaff->canAssignTickets())
			 $errors['err']=$errors['assign'] = 'Action Denied. You are not allowed to assign/reassign Complaints.';
		 else {
			 $id = preg_replace("/[^0-9]/", "",$_POST['assignId']);
			 $claim = (is_numeric($_POST['assignId']) && $_POST['assignId']==$thisstaff->getId());
			 if(!$_POST['assignId'] || !$id)
				 $errors['assignId'] = 'Select assignee';
			 elseif($_POST['assignId'][0]!='s' && $_POST['assignId'][0]!='t' && !$claim)
				 $errors['assignId']='Invalid assignee ID - get technical support';
			 elseif($ticket->isAssigned()) {
				 if($_POST['assignId'][0]=='s' && $id==$ticket->getStaffId())
					 $errors['assignId']='Complaint already assigned to the staff.';
				 elseif($_POST['assignId'][0]=='t' && $id==$ticket->getTeamId())
					 $errors['assignId']='Complaint already assigned to the team.';
			 }
			 if($_POST['dept_id_new']!='27' && $_POST['dept_id_new']!=''){
				 $sql_complaintstatus=' Update '.TICKET_TABLE.' SET  dept_id = '.$_POST['dept_id_new'].' , sla_id = '.$_POST['slaId'].'  where ticket_id = '.db_input($ticket->getId());
		db_query($sql_complaintstatus);
				 }
			 //Comments are not required on self-assignment (claim)
			 if($claim && !$_POST['assign_comments'])
				 $_POST['assign_comments'] = 'Complaint claimed by '.$thisstaff->getName();
			 elseif(!$_POST['assign_comments'])
				 $errors['assign_comments'] = 'Assignment comments required';
			 elseif(strlen($_POST['assign_comments'])<5)
					 $errors['assign_comments'] = 'Comment too short';
			 if(!$errors && $ticket->assign($_POST['assignId'], $_POST['assign_comments'],$_POST['complaint_status'], !$claim)) {
				 if($claim) {
					 $msg = 'Complaint is NOW assigned to you!';
				 } else {
					 $msg='Complaint assigned successfully to '.$ticket->getAssigned();
					 TicketLock::removeStaffLocks($thisstaff->getId(), $ticket->getId());
					 $ticket=null;
				 }
			 } elseif(!$errors['assign']) {
				 $errors['err'] = 'Unable to complete the Complaint assignment';
				 $errors['assign'] = 'Correct the error(s) below and try again!';
			 }
		 }
		break;	 	/*===================================================================================================================================================*/	
		case 'dept': /** Complaint Forward to Department For Department Comunication**/
			if(!$thisstaff->canPostReply())
                $errors['err'] = 'Action denied. Contact admin for access';
            else {
                if(!$_POST['response'])
                    $errors['response']='Response required';
                //Use locks to avoid double replies
                if($lock && $lock->getStaffId()!=$thisstaff->getId())
                    $errors['err']='Action Denied. Complaint is locked by someone else!';
                //Make sure the email is not banned
                if(!$errors['err'] && TicketFilter::isBanned($ticket->getEmail()))
                    $errors['err']='Email is in banlist. Must be removed to reply.';
            }
            $wasOpen =($ticket->isOpen());
            //If no error...do the do.
            $vars = $_POST;
            if(!$errors && $_FILES['attachments'])
                $vars['files'] = AttachmentFile::format($_FILES['attachments']);
				
//            if(!$errors && ($response_to_department=$ticket->postDepartment($vars, $errors)) && !$ticket->pdfExport_Department($vars['response'])) {
			  if(!$errors && ($response_to_department=$ticket->postDepartment($vars, $errors)) ) {
		        $msg='Department posted successfully';
				//$ticket->pdfExport_Department('asdfasdfasdf');
				//$ticket->pdfExport_Department($vars['response']);
				$_SESSION['respone_title']=$vars['title'];
				$_SESSION['respone_note']=$vars['response'];
				
				
                $ticket->reload();
                if($ticket->isClosed() && $wasOpen)
                    $ticket=null;
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
                    $errors['err']='Action Denied. Complaint is locked by someone else!';
                //Make sure the email is not banned
                if(!$errors['err'] && TicketFilter::isBanned($ticket->getEmail()))
                    $errors['err']='Email is in banlist. Must be removed to reply.';
            }
            $wasOpen =($ticket->isOpen());
            //If no error...do the do.
            $vars = $_POST;
            if(!$errors && $_FILES['attachments'])
                $vars['files'] = AttachmentFile::format($_FILES['attachments']);
            if(!$errors && ($response=$ticket->postReply($vars, $errors, isset($_POST['emailreply'])))) {
                $msg='Reply posted successfully';
                $ticket->reload();
                if($ticket->isClosed() && $wasOpen)
                    $ticket=null;
            } elseif(!$errors['err']) {
                $errors['err']='Unable to post the reply. Correct the errors below and try again!';
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
                //Check target dept.
                if(!$_POST['deptId'])
                    $errors['deptId'] = 'Select department';
                elseif($_POST['deptId']==$ticket->getDeptId())
                    $errors['deptId'] = 'Complaint already in the department';
                elseif(!($dept=Dept::lookup($_POST['deptId'])))
                    $errors['deptId'] = 'Unknown or invalid department';
                //Transfer message - required.
                if(!$_POST['transfer_comments'])
                    $errors['transfer_comments'] = 'Transfer comments required';
                elseif(strlen($_POST['transfer_comments'])<5)
                    $errors['transfer_comments'] = 'Transfer comments too short!';
                //If no errors - them attempt the transfer.
                if(!$errors && $ticket->transfer($_POST['deptId'], $_POST['transfer_comments'])) {
                    $msg = 'Complaint transferred successfully to '.$ticket->getDeptName();
                    //Check to make sure the staff still has access to the ticket
                    if(!$ticket->checkStaffAccess($thisstaff))
                        $ticket=null;
                } elseif(!$errors['transfer']) {
                    $errors['err'] = 'Unable to complete the Complaint transfer';
                    $errors['transfer']='Correct the error(s) below and try again!';
                }
            }
            break;	  			
	//=========================================================================================================//			
        case 'edit':
        case 'update':
            if(!$ticket || !$thisstaff->canEditTickets())

                $errors['err']='Perm. Denied. You are not allowed to edit Complaints';

            elseif($ticket->update($_POST,$errors)) {

                $msg='Complaint updated successfully';

                $_REQUEST['a'] = null; //Clear edit action - going back to view.

                //Check to make sure the staff STILL has access post-update (e.g dept change).

                if(!$ticket->checkStaffAccess($thisstaff))

                    $ticket=null;

            } elseif(!$errors['err']) {

                $errors['err']='Unable to update the Complaint. Correct the errors below and try again!';

            }

            break;
        case 'process':
            switch(strtolower($_POST['do'])):
                case 'close':
                    if(!$thisstaff->canCloseTickets()) {

                        $errors['err'] = 'Perm. Denied. You are not allowed to close Complaints.';

                    } elseif($ticket->isClosed()) {

                        $errors['err'] = 'Complaint is already closed!';

                    } elseif($ticket->close()) {

                        $msg='Complaint #'.$ticket->getExtId().' status set to CLOSED';

                        //Log internal note

                        if($_POST['ticket_status_notes'])

                            $note = $_POST['ticket_status_notes'];

                        else

                            $note='Complaint closed (without comments)';


                        $ticket->logNote('Complaint Closed', $note,$ticket->getStatus(), $thisstaff);



                        //Going back to main listing.

                        TicketLock::removeStaffLocks($thisstaff->getId(), $ticket->getId());

                        $page=$ticket=null;



                    } else {

                        $errors['err']='Problems closing the Complaint. Try again';

                    }

                    break;
                case 'reopen':
                    //if staff can close or create tickets ...then assume they can reopen.

                    if(!$thisstaff->canCloseTickets() && !$thisstaff->canCreateTickets()) {

                        $errors['err']='Perm. Denied. You are not allowed to reopen Complaints.';

                    } elseif($ticket->isOpen()) {

                        $errors['err'] = 'Complaint is already open!';

                    } elseif($ticket->reopen()) {

                        $msg='Complaint REOPENED';



                        if($_POST['ticket_status_notes'])

                            $note = $_POST['ticket_status_notes'];

                        else

                            $note='Complaint reopened (without comments)';



                        $ticket->logNote('Complaint Reopened', $note,$ticket->getStatus(), $thisstaff);



                    } else {

                        $errors['err']='Problems reopening the Complaint. Try again';

                    }

                    break;
                case 'release':
                    if(!$ticket->isAssigned() || !($assigned=$ticket->getAssigned())) {

                        $errors['err'] = 'Complaint is not assigned!';

                    } elseif($ticket->release()) {

                        $msg='Complaint released (unassigned) from '.$assigned;

                        $ticket->logActivity('Complaint unassigned',$msg.' by '.$thisstaff->getName());

                    } else {

                        $errors['err'] = 'Problems releasing the Complaint. Try again';

                    }

                    break;
                case 'claim':
                    if(!$thisstaff->canAssignTickets()) {

                        $errors['err'] = 'Perm. Denied. You are not allowed to assign/claim Complaints.';

                    } elseif(!$ticket->isOpen()) {

                        $errors['err'] = 'Only open Complaints can be assigned';

                    } elseif($ticket->isAssigned()) {

                        $errors['err'] = 'Complaint is already assigned to '.$ticket->getAssigned();

                    } elseif($ticket->assignToStaff($thisstaff->getId(), ('Complaint claimed by '.$thisstaff->getName()), false)) {

                        $msg = 'Complaint is now assigned to you!';

                    } else {

                        $errors['err'] = 'Problems assigning the Complaint. Try again';

                    }

                    break;
                case 'overdue':
                    $dept = $ticket->getDept();

                    if(!$dept || !$dept->isManager($thisstaff)) {

                        $errors['err']='Perm. Denied. You are not allowed to flag tickets overdue';

                    } elseif($ticket->markOverdue()) {

                        $msg='Complaint flagged as overdue';

                        $ticket->logActivity('Complaint Marked Overdue',($msg.' by '.$thisstaff->getName()));

                    } else {

                        $errors['err']='Problems marking the the Complaint overdue. Try again';

                    }

                    break;
                case 'answered':
                    $dept = $ticket->getDept();

                    if(!$dept || !$dept->isManager($thisstaff)) {

                        $errors['err']='Perm. Denied. You are not allowed to flag Complaints';

                    } elseif($ticket->markAnswered()) {

                        $msg='Complaint flagged as answered';

                        $ticket->logActivity('Complaint Marked Answered',($msg.' by '.$thisstaff->getName()));

                    } else {

                        $errors['err']='Problems marking the the Complaint answered. Try again';

                    }

                    break;
                case 'unanswered':
                    $dept = $ticket->getDept();

                    if(!$dept || !$dept->isManager($thisstaff)) {

                        $errors['err']='Perm. Denied. You are not allowed to flag Complaints';

                    } elseif($ticket->markUnAnswered()) {

                        $msg='Ticket flagged as unanswered';

                        $ticket->logActivity('Complaint Marked Unanswered',($msg.' by '.$thisstaff->getName()));

                    } else {

                        $errors['err']='Problems marking the the Complaint unanswered. Try again';

                    }

                    break;
                case 'banemail':
                    if(!$thisstaff->canBanEmails()) {

                        $errors['err']='Perm. Denied. You are not allowed to ban emails';

                    } elseif(BanList::includes($ticket->getEmail())) {

                        $errors['err']='Email already in banlist';

                    } elseif(Banlist::add($ticket->getEmail(),$thisstaff->getName())) {

                        $msg='Email ('.$ticket->getEmail().') added to banlist';

                    } else {

                        $errors['err']='Unable to add the email to banlist';

                    }

                    break;
                case 'unbanemail':
                    if(!$thisstaff->canBanEmails()) {

                        $errors['err'] = 'Perm. Denied. You are not allowed to remove emails from banlist.';

                    } elseif(Banlist::remove($ticket->getEmail())) {

                        $msg = 'Email removed from banlist';

                    } elseif(!BanList::includes($ticket->getEmail())) {

                        $warn = 'Email is not in the banlist';

                    } else {

                        $errors['err']='Unable to remove the email from banlist. Try again.';

                    }

                    break;
                case 'delete': // Dude what are you trying to hide? bad customer support??
                    if(!$thisstaff->canDeleteTickets()) {

                        $errors['err']='Perm. Denied. You are not allowed to DELETE Complaints!!';

                    } elseif($ticket->delete()) {

                        $msg='Complaint #'.$ticket->getNumber().' deleted successfully';

                        //Log a debug note

                        $ost->logDebug('Complaint #'.$ticket->getNumber().' deleted',

                                sprintf('Complaint #%s deleted by %s',

                                    $ticket->getNumber(), $thisstaff->getName())

                                );

                        $ticket=null; //clear the object.

                    } else {

                        $errors['err']='Problems deleting the Complaint. Try again';

                    }

                    break;
                default:
                    $errors['err']='You must select action to perform';
            endswitch;
            break;
        default:
            $errors['err']='Unknown action';
        endswitch;
        if($ticket && is_object($ticket))
            $ticket->reload();//Reload ticket info following post processing
     }elseif($_POST['a']) {
        switch($_POST['a']) {
            case 'mass_process':
                if(!$thisstaff->canManageTickets())
                    $errors['err']='You do not have permission to mass manage Complaints. Contact admin for such access';
                elseif(!$_POST['tids'] || !is_array($_POST['tids']))
                    $errors['err']='No Complaints selected. You must select at least one Complaint.';
                else {
                    $count=count($_POST['tids']);
                    $i = 0;
                    switch(strtolower($_POST['do'])) {
					case 'reopen':
						if($thisstaff->canCloseTickets() || $thisstaff->canCreateTickets()) {
					
							$note='Complaint reopened by '.$thisstaff->getName();
					
							foreach($_POST['tids'] as $k=>$v) {
					
								if(($t=Ticket::lookup($v)) && $t->isClosed() && @$t->reopen()) {
					
									$i++;
					
									$t->logNote('Complaint Reopened', $note, $thisstaff);
					
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
						
								$note='Complaint closed without response by '.$thisstaff->getName();
						
								foreach($_POST['tids'] as $k=>$v) {
						
									if(($t=Ticket::lookup($v)) && $t->isOpen() && @$t->close()) {
						
										$i++;
						
										$t->logNote('Complaint Closed', $note, $thisstaff);
						
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
						
						$note='Complaint flagged as overdue by '.$thisstaff->getName();
					
						foreach($_POST['tids'] as $k=>$v) {
					
							if(($t=Ticket::lookup($v)) && !$t->isOverdue() && $t->markOverdue()) {
					
								$i++;
					
								$t->logNote('Complaint Marked Overdue', $note, $thisstaff);
					
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
					
								if(($t=Ticket::lookup($v)) && @$t->delete()) $i++;
					
							}
					
					
					
							//Log a warning
					
							if($i) {
					
								$log = sprintf('%s (%s) just deleted %d Complaint(s)',
					
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
						$ticket=null;			
					if(!$thisstaff || !$thisstaff->canCreateTickets()) {			
					$errors['err']='You do not have permission to create Complaints. Contact admin for such access';			
					} else {			
					$vars = $_POST;			
					if($_FILES['attachments'])			
					$vars['files'] = AttachmentFile::format($_FILES['attachments']);			
					if(($ticket=Ticket::open($vars, $errors))) {			
					$msg='Complaint created successfully';						
					$_REQUEST['a']=null;			
					if(!$ticket->checkStaffAccess($thisstaff) || $ticket->isClosed())			
					$ticket=null;				
					} elseif(!$errors['err']) {	
					$errors['err']='Unable to create the Complaint. Correct the error(s) and try again';			
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
//Navigation
$nav->setTabActive('tickets');
if($thisstaff->canCreateTickets() && !$thisstaff->isAdmin()) {
	
    $nav->addSubMenu(array('desc'=>'New Complaints',
						   'title'=>'New Complaints',
                           'href'=>'tickets.php?a=open',
                           'iconclass'=>'newTicket',
						   'class'=>'ibw-new_Complaint'),
                        	($_REQUEST['a']=='open'));
							
	 $nav->addSubMenu(array('desc'=>'All Comaplaints',
                            'title'=>'All Complaints',
                            'href'=>'tickets-all.php',
                            'iconclass'=>'Ticket',
							'class'=>'ibw-Open_Complaints'),
                        (!$_REQUEST['status'] || $_REQUEST['status']=='open'));					
}
$inc = 'tickets.inc.php';
require_once(STAFFINC_DIR.'header.inc.php');
require_once(STAFFINC_DIR.$inc);
require_once(STAFFINC_DIR.'footer.inc.php');
?>