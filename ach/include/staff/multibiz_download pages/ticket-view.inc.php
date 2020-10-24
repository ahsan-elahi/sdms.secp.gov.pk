<?php
//Note that ticket obj is initiated in tickets.php.
if(!defined('OSTSCPINC') || !$thisstaff || !is_object($ticket) || !$ticket->getId()) die('Invalid path');

//Make sure the staff is allowed to access the page.
if(!@$thisstaff->isStaff() || !$ticket->checkStaffAccess($thisstaff)) die('Access Denied');

//Re-use the post info on error...savekeyboards.org (Why keyboard? -> some people care about objects than users!!)
$info=($_POST && $errors)?Format::input($_POST):array();

//Auto-lock the ticket if locking is enabled.. If already locked by the user then it simply renews.
if($cfg->getLockTime() && !$ticket->acquireLock($thisstaff->getId(),$cfg->getLockTime()))
    $warn.='Unable to obtain a lock on the ticket';

//Get the goodies.
$dept  = $ticket->getDept();  //Dept
$staff = $ticket->getStaff(); //Assigned or closed by..
$team  = $ticket->getTeam();  //Assigned team.
$sla   = $ticket->getSLA();
$lock  = $ticket->getLock();  //Ticket lock obj
$id    = $ticket->getId();    //Ticket ID.

//Useful warnings and errors the user might want to know!
if($ticket->isAssigned() && (
            ($staff && $staff->getId()!=$thisstaff->getId())
         || ($team && !$team->hasMember($thisstaff))
        ))
    $warn.='&nbsp;&nbsp;<span class="Icon assignedTicket">Complaint is assigned to '.implode('/', $ticket->getAssignees()).'</span>';
if(!$errors['err'] && ($lock && $lock->getStaffId()!=$thisstaff->getId()))
    $errors['err']='This Complaint is currently locked by '.$lock->getStaffName();
if(!$errors['err'] && ($emailBanned=TicketFilter::isBanned($ticket->getEmail())))
    $errors['err']='Email is in banlist! Must be removed before any reply/response';

$unbannable=($emailBanned) ? BanList::includes($ticket->getEmail()) : false;

if($ticket->isOverdue())
    $warn.='&nbsp;&nbsp;<span class="Icon overdueComplaint">Marked overdue!</span>';

?>
    <!--<form action="thanks.php" method="post">
    <input type="hidden" value="<?php //echo $ticket->getExtId(); ?>" name="ticketid" />
    <input type="submit" name="attachvoice" value="Attach Voice" style="float:right" />
    </form>-->
<style type="text/css">
#ticket_thread #message{
    background: none repeat scroll 0 0 #C3D9FF;
}
#ticket_thread #note {
    background: none repeat scroll 0 0 #FFFFEE;
}
</style>
<div class="page-header">
    <a href="tickets.php?id=<?php echo $ticket->getId(); ?>" title="Reload"><h1>Complaint <small>#<?php echo $ticket->getExtId(); ?> Details</small></h1></a>
</div>
<!--Action Icons-->
<div class="row-fluid">
<div class="span12" style="float:right;">
	<div class="span10">
        <p align="right" style="float:right;">
        <a id="popup_print" >
        <button class="btn" type="button" ><i class="icon-print"></i> Print</button></a>    
        <?php if($thisstaff->canEditTickets()) { ?>
        <a class="action-button" href="tickets.php?id=<?php echo $ticket->getId(); ?>&a=edit">
        <button class="btn btn-info" type="button"><i class="icon-edit"></i>Edit</button></a>
        <?php } ?>
       
        <?php if($ticket->isOpen() && !$ticket->isAssigned() && $thisstaff->canAssignTickets()) {?>
         <a id="popup_action_claim" >
        <button class="btn btn-success" type="button" id="ticket-claim"><i class="icon-user"></i> Claim</button></a>                                
        <?php } ?>
        <?php if($thisstaff->canDeleteTickets()) { ?>
        <a id="popup_action_del" >
        <button class="btn btn-danger" type="button"><i class="icon-trash"></i>Delete</button></a>
        <?php }?> 
        <?php if($thisstaff->canCloseTickets()) {
        if($ticket->isOpen()) {?>
        <a id="ticket-close" class="action-button" href="#close">
        <button class="btn btn-inverse" id="btn_close" type="button"><i class="icon-remove-circle"></i>Close</button></a>                 
        <?php
        } 
        else { ?>
        
        <a id="ticket-close" class="action-button" href="#reopen">
        <button class="btn btn-inverse" id="btn_close" type="button">
        <i class="icon-undo"></i> Reopen
        </button>
        </a>
        <?php } }?>
        
        </p>     
    </div>             
        <?php if($thisstaff->canBanEmails() || ($dept && $dept->isManager($thisstaff))) { ?>
        <div class="span2" style="margin-left:3px;">
        <div class="btn-toolbar" style="margin-top:0px;" >
            <div class="btn-group">                                        
                <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle">
                <i class="icon-cog"></i> More <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <?php 
                    if($ticket->isOpen() && ($dept && $dept->isManager($thisstaff))) {
                    if($ticket->isAssigned()) { ?>
                    <li><a id="popup_unassigned" href="#release"><i class="icon-user"></i> Release (unassign) Complaint</a></li>
                    <?php
                    }
                    
                    if(!$ticket->isOverdue()) { ?>
                    <li><a id="popup_overdue" href="#overdue"><i class="icon-bell"></i> Mark as Overdue</a></li>
                    <?php
                    }
                    
                    if($ticket->isAnswered()) { ?>
                    <li><a id="popup_action_unanswered"><i class="icon-circle-arrow-left"></i> Mark as Unanswered</a></li>
                    <?php
                    } 
                    else { ?>
                    <li><a id="popup_action_answered"><i class="icon-circle-arrow-right"></i> Mark as Answered</a></li>
                    <?php
                    }
                    }
                    
                    if($thisstaff->canBanEmails()) { 
                    if(!$emailBanned) {?>
                    <li><a id="popup_banemail" href="#banemail"><i class="icon-ban-circle"></i> Ban Email (<?php echo $ticket->getEmail(); ?>)</a></li>
                    <?php 
                    } elseif($unbannable) { ?>
                    <li><a id="popup_banemail" href="#unbanemail"><i class="icon-undo"></i> Unban Email (<?php echo $ticket->getEmail(); ?>)</a></li>
                    <?php
                    }
                    }?>
               </ul>
            </div>
         </div>   
         </div>
        <?php } ?> 
    </div>
</div>
<div class="row-fluid">
    <div class="span6">
        <div class="block-fluid ucard">
                        <div class="info">                                                                
                            <ul class="rows">
                                <li class="heading"><div class="isw-users"></div>Applicant Info</li>
                                <li>
                                    <div class="title">Name:</div>
                                    <div class="text"><?php echo $ticket->getName_Title().''.Format::htmlchars($ticket->getName()); ?></div>
                                </li>
                                <li>
                                    <div class="title"><?php if($ticket->getRelation_Title()!='')
                                    echo $ticket->getRelation_Title().':';
                                    else echo 'SurName:';
                                      ?></div>
                                    <div class="text"><?php if($ticket->getRelation_Name()!='')
                                    echo $ticket->getRelation_Name(); 
                                    else echo 'Null'; ?></div>
                                </li>
                                <li>
                                    <div class="title">CNIC</div>
                                    <div class="text"><?php echo Format::htmlchars($ticket->getNic()); ?></div>
                                </li>
                                <li>
                                    <div class="title">Email:</div>
                                    <div class="text">
                                    <?php
                                    echo $ticket->getEmail();
                                    if(($client=$ticket->getClient())) {
echo sprintf('&nbsp;&nbsp;<a href="tickets.php?a=search&query=%s" title="Related Complaints" data-dropdown="#action-dropdown-stats">(<b>%d</b>)</a>',urlencode($ticket->getEmail()), $client->getNumTickets());
                                    ?>
                                    <div id="action-dropdown-stats" class="action-dropdown anchor-right" style="display: none;">
                                    <ul>
                                    <?php
                                    if(($open=$client->getNumOpenTickets()))
                                    echo sprintf('<li><a href="tickets.php?a=search&status=open&query=%s"><i class="icon-folder-open-alt"></i> %d Open Complaints</a></li>',
                                    $ticket->getEmail(), $open);
                                    if(($closed=$client->getNumClosedTickets()))
                                    echo sprintf('<li><a href="tickets.php?a=search&status=closed&query=%s"><i class="icon-folder-close-alt"></i> %d Closed Complaints</a></li>',
                                    $ticket->getEmail(), $closed);
                                    ?>
                                    <li>
                                    <a href="tickets.php?a=search&query=<?php echo $ticket->getEmail(); ?>"><i class="icon-double-angle-right"></i> All Tickets</a></li>
                                    </ul>
                                    </div>
                                    <?php  } ?></div>
                                </li>
                                <li>
                                    <div class="title">Mobile:</div>
                                    <div class="text"><?php echo $ticket->getPhoneNumber(); ?></div>
                                </li>
                                <li>
                                    <div class="title">Location:</div>
                                    <div class="text"><?php if($ticket->getApplicant_Location()!='')
                                    echo $ticket->getApplicant_Location();
                                    else
                                    echo 'Null';
                                     ?></div>
                                </li> 
                                <li>
                                    <div class="title">District:</div>
                                    <div class="text"><?php 
                                    if($ticket->getDistrict()!='')
                                    echo $ticket->getDistrict();
                                    else
                                    echo 'Null'; ?></div>
                                </li>
                                <li>
                                    <div class="title">Tehsil:</div>
                                    <div class="text"><?php 
                                    if($ticket->getTehsil())
                                    echo $ticket->getTehsil();
                                    else
                                    echo 'Null'; ?></div>
                                </li>   
                                <li>
                                    <div class="title">Address:</div>
                                    <div class="text"><?php 
                                    if($ticket->getApplicant_Address()!='')
                                    echo $ticket->getApplicant_Address();
                                    else
                                    echo 'Null'; ?></div>
                                </li>
                                <li>
                                    <div class="title">Receipt:</div>
                                    <div class="text"><?php 
                                    if($ticket->getApplicant_Address()!='')
                                    echo $ticket->getApplicant_Address();
                                    else
                                    echo 'Null'; ?></div>
                                </li>
                                <li>
                                    <div class="title">Diary No:</div>
                                    <div class="text"><?php 
                                    if($ticket->getDiary_No()!='')
                                    echo $ticket->getDiary_No();
                                    else
                                    echo 'Null'; ?></div>
                                </li>
                                <li>
                                    <div class="title">Pre Complaint #</div>
                                    <div class="text"><?php 
                                    if($ticket->getPrevious_Complaint())
                                    echo $ticket->getPrevious_Complaint();
                                    else
                                    echo 'Null'; ?></div>
                                </li>
                                <li>
                                    <div class="title">Source:</div>
                                    <div class="text">
                                    <?php echo Format::htmlchars($ticket->getSource());
                                    if($ticket->getIP())
                                    echo '&nbsp;&nbsp; <span class="faded">('.$ticket->getIP().')</span>'; ?></div>
                                </li>                                                                    
                            </ul>                                                      
                        </div>                        
                </div>
    </div>
    <div class="span6">
        <div class="block-fluid ucard">
                        <div class="info">                                                                
                            <ul class="rows">
                                <li class="heading">Against Info</li>
                                <li>
                                    <div class="title">Against Person:</div>
                                    <div class="text"><?php echo $ticket->getcomp_against(); ?></div>
                                </li>
                                <li>
                                    <div class="title">Father Name:</div>
                                    <div class="text"><?php 
                                    if($ticket->getcomp_against_fname())
                                    echo $ticket->getcomp_against_fname();
                                    else
                                    echo 'Null'; ?></div>
                                </li>
                                <li>
                                    <div class="title">Type of Complaint:</div>
                                    <div class="text"><?php echo Format::htmlchars($ticket->getHelpTopic()); ?></div>
                                </li>
                                <li>
                                    <div class="title">Last Message::</div>
                                    <div class="text"><?php echo Format::db_datetime($ticket->getLastMsgDate()); ?></div>
                                </li>
                                <li>
                                    <div class="title">Last Response:</div>
                                    <div class="text"><?php 
                                    if($ticket->getLastRespDate()!='')
                                    echo Format::db_datetime($ticket->getLastRespDate());
                                    else
                                    echo 'Null'; ?></div>
                                </li>                                     
                            </ul>                                                      
                        </div>                        
                </div>
    </div>                    
    <div class="span6">
        <div class="block-fluid ucard">
                        <div class="info">                                                                
                            <ul class="rows">
                                <li class="heading">Complaint Info</li>
                                <li>
                                    <div class="title">Status:</div>
                                    <div class="text"><?php echo ucfirst($ticket->getStatus()); ?></div>
                                </li>
                                <li>
                                    <div class="title">Priority:</div>
                                    <div class="text"><?php echo $ticket->getPriority(); ?></div>
                                </li>
                                <li>
                                    <div class="title">Department:</div>
                                    <div class="text"><?php echo Format::htmlchars($ticket->getDeptName()); ?></div>
                                </li>
                                <li>
                                    <div class="title">Create Date:</div>
                                    <div class="text"><?php echo Format::db_datetime($ticket->getCreateDate()); ?></div>
                                </li>
                                <?php if($ticket->isOpen()) { ?>
                                <li>
                                <div class="title">Assigned To:</div>
                                <div class="text"> 
                                <?php
                                if($ticket->isAssigned())
                                echo Format::htmlchars(implode('/', $ticket->getAssignees()));
                                else
                                echo '<span class="faded">&mdash; Unassigned &mdash;</span>';
                                ?></div>
                                </li>
                                <?php } else { ?>
                                <li>
                                <div class="title">Closed By:</div>
                                <div class="text"><?php
                                if(($staff = $ticket->getStaff()))
                                echo Format::htmlchars($staff->getName());
                                else
                                echo '<span class="faded">&mdash; Unknown &mdash;</span>';
                  ?></div>
                                </li> 
                                <?php } ?> 
                                 <li>
                                    <div class="title">SLA Plan:</div>
                                    <div class="text">
                                    <?php echo $sla?Format::htmlchars($sla->getName()):'<span class="faded">&mdash; none &mdash;</span>'; ?></div>
                                </li>
                                <?php
                                if($ticket->isOpen()){ ?>
                                <li>
                                    <div class="title">Due Date:</div>
                                    <div class="text"><?php echo Format::db_datetime($ticket->getEstDueDate()); ?></div>
                                </li>
                                <?php
                                }else { ?>
                                <li>
                                    <div class="title">Close Date:</div>
                                    <div class="text">
                                    <?php echo Format::db_datetime($ticket->getCloseDate()); ?></div>
                                </li>
                                <?php }  ?> 
                                                                   
                            </ul>                                                      
                        </div>                        
                </div>
    </div>
</div>    
<div class="dr"><span></span></div> 
<?php
$tcount = $ticket->getThreadCount();
if($cfg->showNotesInline())
    $tcount+= $ticket->getNumNotes();
?>
<!--Thread Sections-->
<div class="row-fluid">
    <div class="span12">
        <div class="head clearfix">
            <div class="isw-list"></div>
            <h1> Complaint About <?php echo $ticket->getSubject(); ?> (<?php echo $tcount; ?>)</h1>
               <?php if(!$cfg->showNotesInline()) {?>
<a id="toggle_notes" href="#">Internal Notes (<?php echo $ticket->getNumNotes(); ?>)</a>
<?php }?>
        </div>
        <div class="headInfo" style="padding: 0px;"> 
        <div class="arrow_down"></div>
        </div>
        <?php if(!$cfg->showNotesInline()) { ?>
        <div class="block stream" id="ticket_notes">
        <?php
        /* Internal Notes */
        if($ticket->getNumNotes() && ($notes=$ticket->getNotes())) {
        foreach($notes as $note) { ?>
        <div class="item clearfix">
        <div class="image"><a href="#"><img src="img/users/aqvatarius.jpg" class="img-polaroid"/></a></div>
        <div class="info">
        <a class="name" href="#">Aqvatarius</a>
        <p class="title"><span class="icon-comment"></span> commented page <a href="#">How to do...?</a>:</p>                                    
        <div class="text">
        <p>Phasellus ut diam quis dolor mollis tristique. Suspendisse vestibulum convallis felis vitae facilisis. Praesent eu nisi vestibulum erat lacinia sollicitudin.</p>
        </div>
        <p class="actions"><a href="#"><span class="icon-comment"></span> Comment</a> <a href="#"><span class="icon-heart"></span> Like</a></p>
        </div>
        </div>                                  
        <?php } } else { echo "<p>No internal notes found.</p>"; }?>                                   
        
        </div>    
        <?php } ?> 
        <!--<div>Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp
		A2
		<span style="text-align:right!important;float:right;">A3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp
		A4</span></div>-->
        <div class="block-fluid accordion">
        <?php
        $threadTypes=array('M'=>'message','R'=>'response', 'N'=>'note', 'A'=>'affidavite', 'D'=>'department');
        /* -------- Messages & Responses & Notes (if inline)-------------*/
        $types = array('M', 'R');
        if($cfg->showNotesInline())
        $types[] = 'N';
        $types[] = 'A';
        $types[] = 'D';        
        if(($thread=$ticket->getThreadEntries($types))) {
        foreach($thread as $entry) { ?>
        <h3><?php echo Format::db_datetime($entry['created']);?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp
		<?php echo Format::htmlchars($entry['title']); ?>
		<span style="text-align:right!important;float:right;"><?php echo Format::htmlchars($entry['complaint_status']); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp
		<?php echo Format::htmlchars($entry['poster']); ?></span></h3>
        
        
        <div>
        <p><?php echo Format::display($entry['body']); ?></p>
        <?php
        if($entry['attachments'] 
        && ($tentry=$ticket->getThreadEntry($entry['id']))
        && ($links=$tentry->getAttachmentsLinks())) {?> 
        <?php echo $links; ?>  
        <?php }?>
        </div>
        <?php
        if($entry['thread_type']=='M')
        $msgId=$entry['id'];
        }
        } else {
        echo '<p>Error fetching ticket thread - get technical help.</p>';
        }?>   
        </div>
    </div>                  

</div>
<!--Post Reply  ,  Post Internal Note  ,  Dept. Transfer  ,  Reassign Complaint -->
<div class="row-fluid">
<div class="span12">
<div class="head clearfix">
    <div class="isw-list"></div>
    <h1>Complaints Actions</h1>
</div>
<div class="block-fluid tabs">
    <ul>
        <li><a id="note_tab" href="#note">Legal Summary</a></li>
        <li><a id="affidavite_tab" href="#affidavite">Affidavit</a></li>
        <?php
        //  if($thisstaff->canTransferTickets()) { ?>
        <!--<li><a id="transfer_tab" href="#transfer">Dept. Transfer</a></li>-->
        <?php
        // }                               
        if($thisstaff->canAssignTickets()) { ?>
        <li><a id="assign_tab" href="#assign"><?php echo $ticket->isAssigned()?'Reassign Complaint':'Assign Complaint'; ?></a></li>
        <?php  } ?>
        
        <?php
        //if($thisstaff->canTransferDepartment()) { ?>
        <li><a id="dept_tab" href="#dept">Department Com</a></li>
        
        <?php if($thisstaff->canPostReply()) { ?>
        <li><a id="reply_tab" href="#reply">Compalint Reply</a></li>
        <?php } ?>
    </ul>
     
    <!--Legal Summary-->
    <?php  if($thisstaff->canPostReply()) { ?>                    
    <div id="note">                                    
    <form id="note" action="tickets.php?id=<?php echo $ticket->getId(); ?>#note" name="note" method="post" enctype="multipart/form-data">
                    <?php csrf_token(); ?>
                    <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
                    <input type="hidden" name="locktime" value="<?php echo $cfg->getLockTime(); ?>">
                    <input type="hidden" name="a" value="postnote">
                    <div class="block-fluid" style="margin-bottom:0px;">                        
    <?php if($errors['postnote']) {?>
    <div class="row-form clearfix">
        <div class="span3">&nbsp;</div>
        <div class="span9"><?php echo $errors['postnote']; ?></div>
    </div>
     <?php  } ?>
   <div class="row-form clearfix">
        <div class="span3">Legal Summary Title:</div>
        <div class="span9"><input type="text" placeholder="Note title..." name="title" id="title" value="<?php echo $info['title']; ?>"/></div>
    </div> 
   <div class="row-form clearfix">
        <div class="span3">Legal Summary:</div>
        <div class="span9"><textarea placeholder="Note details..." name="note" id="internal_note" ><?php echo $info['note']; ?></textarea></div>
    </div> 
     <?php
     if($cfg->allowAttachments()) { ?>
    <div class="row-form clearfix">
        <div class="span3">Attachments:</div>
        <div class="span9" >                                       
            <div class="canned_attachments"></div>
            <div class="uploads"></div>
            <div class="file_input">
             <input type="file" class="multifile" name="attachments[]" value="" />
            </div>
        </div>
    </div>
     <?php }  ?>  
     <div class="row-form clearfix">
                <div class="span3">Complaint Status:</div>
                <div class="span9"><select name="complaint_status" style="width:300px;">
            <option value="" selected >&mdash; Select Status &mdash;</option>
               <option value="Pending" >In process </option>
                <option value="Disposal" >Disposal</option>   
            </select>
            &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['source']; ?></font></div>
                </div>   
    <div class="footer tar" style="margin-top: 0px;">
        <input type="submit" class="btn" value="Post Note" />
        <input type="reset" class="btn" value="Reset" />
    </div>                            
</div>
               </form>
    </div>   
    <?php } ?>
    <!--Affidavit-->
    <?php  if($thisstaff->canPostReply()) { ?>
     <div id="affidavite">
    <form id="affidavite" action="tickets.php?id=<?php echo $ticket->getId(); ?>#affidavite" name="affidavite" method="post" enctype="multipart/form-data">
    <?php csrf_token(); ?>
    <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
    <input type="hidden" name="locktime" value="<?php echo $cfg->getLockTime(); ?>">
    <input type="hidden" name="a" value="postaffidavite">
    <div class="block-fluid" style="margin-bottom:0px;">                        
    <?php if($errors['postaffidavite']) {?>
    <div class="row-form clearfix">
    <div class="span3">&nbsp;</div>
    <div class="span9"><?php echo $errors['postaffidavite']; ?></div>
    </div>
    <?php  } ?>
    <div class="row-form clearfix">
    <div class="span3">Afidavite Title:</div>
    <div class="span9"><input type="text" placeholder="Afidavite Title..." name="affidavite" id="affidavite" value="<?php echo $info['affidavite']; ?>"/></div>
    </div> 
    <div class="row-form clearfix">
    <div class="span3">Affidavite Note:</div>
    <div class="span9"><textarea placeholder="Affidavite details..." name="affidavite" id="affidavite_note" ><?php echo $info['note']; ?></textarea></div>
    </div>                            
    <?php if($cfg->allowAttachments()) { ?>
    <div class="row-form clearfix">
    <div class="span3">Attachments:</div>
    <div class="span9">        
            <div class="canned_attachments"></div>
            <div class="uploads"></div>
            <div class="file_input">
            <input type="file" class="multifile" name="attachments[]" value="" />
            </div>
</div>
    </div>
    <?php }  ?>  
    <div class="row-form clearfix">
                <div class="span3">Complaint Status:</div>
                <div class="span9"><select name="complaint_status" style="width:300px;">
            <option value="" selected >&mdash; Select Status &mdash;</option>
                     <option value="Pending" >In process </option>
                <option value="Disposal" >Disposal</option>   
            </select>
            &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['source']; ?></font></div>
                </div>                               
    <div class="footer tar" style="margin-top: 0px;">
    <input type="submit" class="btn" value="Post Affidavte" />
    <input type="reset" class="btn" value="Reset" />
    </div>                            
    </div>
    </form>
  </div>   
    <?php } ?>
    <!--Assign Complaint-->                     
    <?php if($thisstaff->canAssignTickets()) { ?>
    <div id="assign">
    <form id="assign" action="tickets.php?id=<?php echo $ticket->getId(); ?>#assign" name="assign" method="post" enctype="multipart/form-data">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="a" value="assign">
        <div class="block-fluid" style="margin-bottom:0px;">                        
        <?php if($errors['assign']) {?>
        <div class="row-form clearfix">
        <div class="span3">&nbsp;</div>
        <div class="span9"><?php echo $errors['assign']; ?></div>
        </div>
        <?php  } ?> 
        <div class="row-form clearfix">
        <div class="span3">Assign To:</div>
        <div class="span9"><?php
        if($ticket->isAssigned() && $ticket->isOpen()) {
        echo sprintf('<span class="faded">Complaint is currently assigned to <b>%s</b></span>',
        $ticket->getAssignee());
        } else {
        echo '<span class="faded">Assigning a closed Complaint will <b>reopen</b> it!</span>';
        }
        ?>
        <br>
        <select id="assignId" name="assignId" style="width:300px;">
        <option value="0" selected="selected">&mdash; Select Staff Member OR a Team &mdash;</option>
        <?php
        if($ticket->isOpen() && !$ticket->isAssigned())
        echo sprintf('<option value="%d">Claim Complaint (comments optional)</option>', $thisstaff->getId());
        
        $sid=$tid=0;
        if(($users=Staff::getAvailableStaffMembers())) {
        echo '<OPTGROUP label="Staff Members ('.count($users).')">';
        $staffId=$ticket->isAssigned()?$ticket->getStaffId():0;
        foreach($users as $id => $name) {
        if($staffId && $staffId==$id)
        continue;
        
        $k="s$id";
        echo sprintf('<option value="%s" %s>%s</option>',
        $k,(($info['assignId']==$k)?'selected="selected"':''),$name);
        }
        echo '</OPTGROUP>';
        }
        
        if(($teams=Team::getActiveTeams())) {
        echo '<OPTGROUP label="Teams ('.count($teams).')">';
        $teamId=(!$sid && $ticket->isAssigned())?$ticket->getTeamId():0;
        foreach($teams as $id => $name) {
        if($teamId && $teamId==$id)
        continue;
        
        $k="t$id";
        echo sprintf('<option value="%s" %s>%s</option>',
        $k,(($info['assignId']==$k)?'selected="selected"':''),$name);
        }
        echo '</OPTGROUP>';
        }
        ?>
        </select>&nbsp;<span class='error'>*&nbsp;<?php echo $errors['assignId']; ?></span></div>
        </div>                            
        <div class="row-form clearfix">
        <div class="span3">Comments:</div>
        <div class="span9"><span>Enter reasons for the assignment or instructions for assignee.</span>
        <span class="error">*&nbsp;<?php echo $errors['assign_comments']; ?></span><br>
        <textarea placeholder="Affidavite details..." name="assign_comments" id="assign_comments" ><?php echo $info['assign_comments']; ?></textarea></div>
        </div>   
                                       
        <div class="footer tar" style="margin-top: 0px;">
        <input type="submit" class="btn" value="<?php echo $ticket->isAssigned()?'Reassign':'Assign'; ?>" />
        <input type="reset" class="btn" value="Reset" />
        </div>                            
        </div>
        </form>
    </div>  
	<?php } ?>                          
    
    <!--Depat Compunication-->
    <?php  if($thisstaff->canPostReply()) { ?>  
    <div id="dept">
        <form id="dept" action="tickets.php?id=<?php echo $ticket->getId(); ?>#dept" name="dept" method="post" enctype="multipart/form-data">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="msgId" value="<?php echo $msgId; ?>">
        <input type="hidden" name="a" value="dept">
            <div class="block-fluid" style="margin-bottom:0px;">                        
                <div class="row-form clearfix">
                <div class="span3">TO:</div>
                <div class="span9"><?php
            echo sprintf('<span class="faded">Complaint is currently in <b>%s</b> department.</span>', $ticket->getDeptName());
            ?></div>
                </div>
                <?php if($errors['response']) {?>
                <div class="row-form clearfix">
                <div class="span3">&nbsp;</div>
                <div class="span9"><?php echo $errors['response']; ?>&nbsp;</div>
                </div>
                <?php } ?>
                <div class="row-form clearfix">
                <div class="span3">Response:</div>
                <div class="span9"><?php
            if(($cannedResponses=Canned::responsesByDeptId($ticket->getDeptId()))) {?>
            <select id="cannedResp" name="cannedResp" style="width:400px;">
                <option value="0" selected="selected">Select a canned response</option>
                <?php
                foreach($cannedResponses as $id =>$title) {
                    echo sprintf('<option value="%d">%s</option>',$id,$title);
                }
                ?>
            </select>&nbsp;&nbsp;&nbsp;<label style="display:inline;"><input type='checkbox' value='1' name="append" id="append" checked="checked"> Append</label>
            <?php
            }?>
            <textarea name="response" id="response" cols="50" rows="9" wrap="soft"><?php echo $info['response']; ?></textarea></div>
                </div>    
                <!--<div class="row-form clearfix">
                <div class="span3">Department Reply:</div>
                <div class="span9"><input type="checkbox" name="reply_dept" id="reply_dept" value="dept_reply_done" >Department Reply</div>
                </div>-->
                <?php if($cfg->allowAttachments()) { ?>
                <div class="row-form clearfix">
                <div class="span3">Attachments:</div>
                <div class="span9"> 
                            <div class="canned_attachments"></div>
            <div class="uploads"></div>
            <div class="file_input">
             <input type="file" class="multifile" name="attachments[]" value="" />
            </div>

                 </div>
                </div>
                <?php }  ?>
                <div class="row-form clearfix">
                <div class="span3">Complaint Status:</div>
                <div class="span9"><select name="complaint_status" style="width:300px;">
            <option value="" selected >&mdash; Select Status &mdash;</option>
                <option value="Pending" >In process </option>
                <option value="Disposal" >Disposal</option>   
            </select>
            &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['source']; ?></font></div>
                </div> 
                <div class="footer tar" style="margin-top: 0px;">
                <input type="submit" class="btn" value="Post/Print" />
                <input type="reset" class="btn" value="Reset" />
                </div>                                           
            </div>
        </form>                                  
    </div>
    <?php } ?>
    <!--Complaint Reply-->
	<?php  if($thisstaff->canPostReply()) { ?>
    <div id="reply">
        <form id="reply" action="tickets.php?id=<?php echo $ticket->getId(); ?>#reply" name="reply" method="post" enctype="multipart/form-data">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="msgId" value="<?php echo $msgId; ?>">
        <input type="hidden" name="a" value="reply">
            <div class="block-fluid" style="margin-bottom:0px;">
                <div class="row-form clearfix">
                <div class="span3">TO:</div>
                <div class="span9"> <?php
                $to = $ticket->getEmail();
                if(($name=$ticket->getName()) && !strpos($name,'@'))
                $to =sprintf('%s <em>&lt;%s&gt;</em>', $name, $ticket->getEmail());
                echo $to;
                ?>
                &nbsp;&nbsp;&nbsp;
                <input type='checkbox' value='1' name="emailreply" id="remailreply"
                <?php echo ((!$info['emailreply'] && !$errors) || isset($info['emailreply']))?'checked="checked"':''; ?>> Email Reply</div>
                </div>
                <?php if($errors['response']) {?>
                <div class="row-form clearfix">
                <div class="span3">&nbsp;</div>
                <div class="span9"><?php echo $errors['response']; ?>&nbsp;</div>
                </div>
                <?php }?>
                <div class="row-form clearfix">
                <div class="span3">Response:</div>
                <div class="span9"><?php
                if(($cannedResponses=Canned::responsesByDeptId($ticket->getDeptId()))) {?>
                <select id="cannedResp" name="cannedResp" style="width:400px;">
                <option value="0" selected="selected">Select a canned response</option>
                <?php
                foreach($cannedResponses as $id =>$title) {
                echo sprintf('<option value="%d">%s</option>',$id,$title);
                }
                ?>
                </select>
                &nbsp;&nbsp;&nbsp;
                <label style="display:inline;"><input type='checkbox' value='1' name="append" id="append" checked="checked"> Append</label>
                <?php
                }?>
                <textarea name="response" id="response" cols="50" rows="9" wrap="soft"><?php echo $info['response']; ?></textarea></div>
                </div>
                <?php
                if($cfg->allowAttachments()) { ?>
                <div class="row-form clearfix">
                <div class="span3">Attachments:</div>
                <div class="span9">
                    <div class="canned_attachments"></div>
            <div class="uploads"></div>
            <div class="file_input">
             <input type="file" class="multifile" name="attachments[]" value="" />
            </div>
</div>
                </div>   
                <?php }?>
                <!--<div class="row-form clearfix">
                <div class="span3">Signature:</div>
                <div class="span9"><?php
                //$info['signature']=$info['signature']?$info['signature']:$thisstaff->getDefaultSignatureType();
                ?>
                <input type="radio" name="signature" value="none" checked="checked"> None
                <?php
                //if($thisstaff->getSignature()) {?>
                <input type="radio" name="signature" value="mine"
                <?php //echo ($info['signature']=='mine')?'checked="checked"':''; ?>> My signature
                <?php
                //} ?>
                <?php
                //if($dept && $dept->canAppendSignature()) { ?>
                <input type="radio" name="signature" value="dept"
                <?php //echo ($info['signature']=='dept')?'checked="checked"':''; ?>>
                Dept. Signature (<?php //echo Format::htmlchars($dept->getName()); ?>)
                <?php
                //} ?></div>
                </div> -->
                <?php
                //if($ticket->isClosed() || $thisstaff->canCloseTickets()) { ?>
                <!--<div class="row-form clearfix">
                <div class="span3">Complaint Open / Close:</div>
                <div class="span9"> <?php
                //$statusChecked=isset($info['reply_ticket_status'])?'checked="checked"':'';
                //if($ticket->isClosed()) { ?>
                <input type="checkbox" name="reply_ticket_status" id="reply_ticket_status" value="Open"
                <?php //echo $statusChecked; ?>> Reopen on Reply
                <?php
                //} elseif($thisstaff->canCloseTickets()) { ?>
                <input type="checkbox" name="reply_ticket_status" id="reply_ticket_status" value="Closed"
                <?php //echo $statusChecked; ?>> Close on Reply
                <?php
                //} ?></div>
                </div> -->
                <?php // } ?>
                <div class="row-form clearfix">
                <div class="span3">Complaint Status:</div>
                <div class="span9"><select name="complaint_status" style="width:300px;">
                <option value="" selected >&mdash; Select Status &mdash;</option>                
                <option value="Pending" >In process </option>
                <option value="Disposal" >Disposal</option>                </select>
                &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['source']; ?></font></div>
                </div>  
                <div class="footer tar" style="margin-top: 0px;">
                    <input type="submit" class="btn" value="Post Reply" />
                    <input type="reset" class="btn" value="Reset" />
                </div>            
            </div>
        </form>   
    </div>
    <?php  } ?>     
</div>
</div>
</div>       
<div class="dr"><span></span></div>   

<!-- popup form for print -->
<div class="dialog" id="ba_popup_print" title="Complaint Print">
    <form action="tickets.php?id=<?php echo $ticket->getId(); ?>" method="post" id="print_form_abc">
        <?php csrf_token(); ?>
        <input type="hidden" name="a" value="print">
        <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
            <label for="notes">Print Notes:</label>
            <input type="checkbox" id="notes" name="notes" value="1"> Print <b>Internal</b> Notes/Comments
            <label for="psize">Paper Size:</label>
            <select id="psize" name="psize">
                <option value="">&mdash; Select Print Paper Size &mdash;</option>
                <?php
                  $options=array('Letter', 'Legal', 'A4', 'A3');
                  $psize =$_SESSION['PAPER_SIZE']?$_SESSION['PAPER_SIZE']:$thisstaff->getDefaultPaperSize();
                  foreach($options as $v) {
                      echo sprintf('<option value="%s" %s>%s</option>',
                                $v,($psize==$v)?'selected="selected"':'', $v);
                  }
                ?>
            </select>
        <input type="submit" value="Print">
    </form>
</div>
<!-- Popup form for close/Reopen-->
<div style="display:none;" class="dialog" id="ba_btn_close" title="<?php echo sprintf('%s Complaint #%s', ($ticket->isClosed()?'Reopen':'Close'), $ticket->getNumber()); ?>">
    <?php echo sprintf('Are you sure you want to <b>%s</b> this Complaint?', $ticket->isClosed()?'REOPEN':'CLOSE'); ?>
    <form action="tickets.php?id=<?php echo $ticket->getId(); ?>" method="post" id="status-form" name="status-form">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="a" value="process">
        <input type="hidden" name="do" value="<?php echo $ticket->isClosed()?'reopen':'close'; ?>">
        <fieldset>
            <em>Reasons for status change (internal note). Optional but highly recommended.</em><br>
            <textarea name="ticket_status_notes" id="ticket_status_notes" cols="50" rows="5" wrap="soft"><?php echo $info['ticket_status_notes']; ?></textarea>
        </fieldset>
            <span class="buttons" style="float:right">
                <input type="submit" style="display:none;" value="<?php echo $ticket->isClosed()?'Reopen':'Close'; ?>" id="close-reopen-form">
            </span>
         </p>
    </form>
</div>
<!-- Popup form for delete -->
<div class="dialog" id="b_popup_action_del" style="display: none;" title="Please Confirm">   
    <p>
        <font color="red"><strong>Are you sure you want to DELETE this Complaint?</strong></font>
        <br><br>Deleted Complaints CANNOT be recovered, including any associated attachments.
    </p>
    <div>Please confirm to continue.</div>
    <form action="tickets.php?id=<?php echo $ticket->getId(); ?>" method="post" id="confirm_form" name="confirm_form">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="a" value="process">
        <input type="hidden" name="do" id="action" value="delete">        
    </form>
</div>
<!-- Popup form for Overdue -->
<div class="dialog" id="ba_popup_overdue" style="display: none;" title="Please Confirm">   
     <p>
        Are you sure want to flag the Complaint as <font color="red"><b>overdue</b></font>?
    </p>'
    <div>Please confirm to continue.</div>
    <form action="tickets.php?id=<?php echo $ticket->getId(); ?>" method="post" id="confirm_form" name="confirm_form">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="a" value="process">
        <input type="hidden" name="do" id="action" value="delete">        
    </form>
</div>
<!-- Popup form for unassigned -->
<div class="dialog" id="ba_popup_unassigned" style="display: none;" title="Please Confirm">   
     <p>
        Are you sure want to <b>unassign</b> Complaint from <b><?php echo $ticket->getAssigned(); ?></b>?
    </p>'
    <div>Please confirm to continue.</div>
    <form action="tickets.php?id=<?php echo $ticket->getId(); ?>" method="post" id="confirm_form" name="confirm_form">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="a" value="process">
        <input type="hidden" name="do" id="action" value="delete">        
    </form>
</div>
<!-- Popup form for Ban -->
<div class="dialog" id="ba_popup_banemail" style="display: none;" title="Please Confirm">   
    <p>
        Are you sure want to <b>ban</b> <?php //echo $ticket->getEmail(); ?>? <br><br>
        New Complaints from the email address will be auto-rejected.
    </p>'
    <div>Please confirm to continue.</div>
    <form action="tickets.php?id=<?php echo $ticket->getId(); ?>" method="post" id="confirm_form" name="confirm_form">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="a" value="process">
        <input type="hidden" name="do" id="action" value="delete">        
    </form>
</div>


<!-- Popup form for claim -->
<div class="dialog" id="b_popup_action_claim" style="display: none;" title="Please Confirm">
        Are you sure want to <b>claim</b> (self assign) this Complaint?
    <!--    
    
    <p class="confirm-action" style="display:none;" id="overdue-confirm">
        Are you sure want to flag the Complaint as <font color="red"><b>overdue</b></font>?
    </p>
    <p class="confirm-action" style="display:none;" id="banemail-confirm">
        Are you sure want to <b>ban</b> <?php //echo $ticket->getEmail(); ?>? <br><br>
        New Complaints from the email address will be auto-rejected.
    </p>
    <p class="confirm-action" style="display:none;" id="unbanemail-confirm">
        Are you sure want to <b>remove</b> <?php //echo $ticket->getEmail(); ?> from ban list?
    </p>
    <p class="confirm-action" style="display:none;" id="release-confirm">
        Are you sure want to <b>unassign</b> Complaint from <b><?php //echo $ticket->getAssigned(); ?></b>?
    </p>-->
   
    <div>Please confirm to continue.</div>
    <form action="tickets.php?id=<?php echo $ticket->getId(); ?>" method="post" id="confirm-form" name="confirm-form">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="a" value="process">
        <input type="hidden" name="do" id="action" value="claim">        
    </form>
</div>

<!-- Popup form for answered -->
<div class="dialog" id="b_popup_action_answered" style="display: none;" title="Please Confirm">
        Are you sure want to flag the Complaint as <b>answered</b>?     
    <div>Please confirm to continue.</div>
    <form action="tickets.php?id=<?php echo $ticket->getId(); ?>" method="post" id="confirm_answer_form" name="confirm_answer_form">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="a" value="process">
        <input type="hidden" name="do" id="action" value="answered">        
    </form>
</div>

<!-- Popup form for unanswered -->
<div class="dialog" id="b_popup_action_unanswered" style="display: none;" title="Please Confirm">
    
    <p>
        Are you sure want to flag the Complaint as <b>unanswered</b>?
    </p>
   <!-- <p class="confirm-action" style="display:none;" id="overdue-confirm">
        Are you sure want to flag the Complaint as <font color="red"><b>overdue</b></font>?
    </p>
    <p class="confirm-action" style="display:none;" id="banemail-confirm">
        Are you sure want to <b>ban</b> <?php //echo $ticket->getEmail(); ?>? <br><br>
        New Complaints from the email address will be auto-rejected.
    </p>
    <p class="confirm-action" style="display:none;" id="unbanemail-confirm">
        Are you sure want to <b>remove</b> <?php //echo $ticket->getEmail(); ?> from ban list?
    </p>
    <p class="confirm-action" style="display:none;" id="release-confirm">
        Are you sure want to <b>unassign</b> Complaint from <b><?php //echo $ticket->getAssigned(); ?></b>?
    </p>-->
   
    <div>Please confirm to continue.</div>
    <form action="tickets.php?id=<?php echo $ticket->getId(); ?>" method="post" id="confirm-form" name="confirm-form">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="a" value="process">
        <input type="hidden" name="do" id="action" value="unanswered">        
    </form>
</div>



<script type="text/javascript" src="./js_old/js_old/ticket.js"></script>
</div><!--WorkPlace End-->  
</div>   