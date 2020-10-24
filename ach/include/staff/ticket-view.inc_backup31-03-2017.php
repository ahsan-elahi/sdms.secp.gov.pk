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
<?php 
if(isset($msg) && $msg=='Complaint created successfully'){
	//echo 'wellcome';exit;
	}
?>
<script type="application/javascript">
$(document).ready(function(){
  $("#voice_attach").click(function(){ 
	<?php 
	if($staff =  Staff::lookup($_SESSION['_staff']['ID']))
	{
	$extion_id=$staff->getExtention();

	$ticket_id=$ticket->getExtId();
if($extion_id!='')
	{	
	?>
	$.ajax({
				url: 'get_voice_name.php',
				data: {
					staff_ext:<?php echo $extion_id; ?>,
					ticket_id:<?php echo $ticket->getExtId(); ?>
				},
				type: 'POST',
				success: function (data) {
                                if(data=='novoice')
                                alert('Please Hang Up Call First and then Click on Save Button!');
                                else
			        alert('Voice Attach Sucessfully!');
				//alert(data);
				
				}
		});
	<?php } }?>	
  });
});

function get_Sub_Com(dept_id)
{
$.ajax({
	url:"get_sub_department_admin.php",
	data: "m_dept_id="+dept_id,
	success: function(msg){
	$("#show_sub_department").html(msg);
}
});
}
function set_assign_value(sub_dept_id)
{
$.ajax({
	url:"get_assign_user_list.php",
	data: "sub_dept_id="+sub_dept_id,
	success: function(msg){
		document.getElementById('assignId').value='s'+msg.trim();
}
});
}
function check_depart_update()
{
	
	var dept=$('#dept_id_new').val();
	//var status=$('#complaint_status').val();
	
	if (dept==27)
	{
		alert('change depatment name ...');
		return false;
		}
		else{
			return true;
			}	
}

function getThread_detail(thread_id){
$.ajax({
	url:"get_thread.php",
	data: "id="+thread_id,
	success: function(msg){
	$('#ba_btn_thread').html('');
	$('#ba_btn_thread').html(msg);	
	}
});
}
</script>
<style type="text/css">
.rating{
width:80px;
height:16px;
margin:0 0 20px 0;
padding:0;
list-style:none;
clear:both;
float:right;
position:relative;
background: url(images/star-matrix.gif) no-repeat 0 0;
}
.nostar {background-position:0 0}
.onestar {background-position:0 -16px}
.twostar {background-position:0 -32px}
.threestar {background-position:0 -48px}
.fourstar {background-position:0 -64px}
.fivestar {background-position:0 -80px}
ul.rating li {
cursor: pointer;
float:left;
text-indent:-999em;
}
ul.rating li span {
position:absolute;
left:0;
top:0;
width:16px;
height:16px;
text-decoration:none;
z-index: 200;
}
ul.rating li.one span {left:0}
ul.rating li.two span {left:16px;}
ul.rating li.three span {left:32px;}
ul.rating li.four span {left:48px;}
ul.rating li.five span {left:64px;}
ul.rating li span:hover {
z-index:2;
width:80px;
height:16px;
overflow:hidden;
left:0;
background: url(images/star-matrix.gif) no-repeat 0 0
}
ul.rating li.one span:hover {background-position:0 -96px;}
ul.rating li.two span:hover {background-position:0 -112px;}
ul.rating li.three span:hover {background-position:0 -128px}
ul.rating li.four span:hover {background-position:0 -144px}
ul.rating li.five span:hover {background-position:0 -160px}
h3{margin:0 0 2px 0;font-size:110%}
</style>
<!--<form action="thanks.php" method="post">
    <input type="hidden" value="<?php //echo $ticket->getExtId(); ?>" name="ticketid" />
    <input type="submit" name="attachvoice" value="Attach Voice" style="float:right" />
    </form>-->
<div class="row-fluid">
<div class="span6">
<div class="page-header" >
    <a href="tickets.php?id=<?php echo $ticket->getId(); ?>" title="Reload"><h1>Complaint <small>#
	<span class="label label-inverse" style="font-size: 25.844px;line-height: 33px;"><?php echo $ticket->getExtId(); ?></span> Details</small></h1></a>
</div>
</div>
<?php if($msg=='Complaint created successfully' && $ticket->getSource()=='Through Call'){?>
<div class="span6" style="text-align:right">
<input type="button" value="Save and Attach"  class="wrapper green btn" id="voice_attach"/></li>
</div>
<?php }?>
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
        <a class="action-button" >
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
<?php if($thisstaff->canCreateTickets() || $thisstaff->isAdmin()){  ?>
<div class="span6">
<div class="block-fluid ucard">
<div class="info">                                                                
<ul class="rows">
<li class="heading"><div class="isw-users"></div>Applicant Info </li>
<li>
<div class="title">Name:</div>
<div class="text"><?php 
echo $ticket->getName_Title().' '.Format::htmlchars($ticket->getName());
?></div>
</li>
<li>
<div class="title">CNIC</div>
<div class="text"><?php
if($ticket->getNic()!='')
{
 echo Format::htmlchars($ticket->getNic());
echo sprintf('&nbsp;&nbsp;<a href="tickets.php?a=search&query=%s" title="Related Complaints" data-dropdown="#action-dropdown-stats">(<b>%d</b>)</a>',urlencode($ticket->getNic()), $ticket->getTotalTickets($ticket->getNic()));
}
else echo 'Null'; ?></div>
</li>
<li>
<div class="title">Email:</div>
<div class="text">
<?php
if ($ticket->getEmail()=='')
echo "Null";
echo $ticket->getEmail();?></div>
</li>
<li>
<div class="title">Mobile:</div>
<div class="text"><?php 
if( $ticket->getPhoneNumber()!='')
echo $ticket->getPhoneNumber();
else echo 'Null'; ?></div>
</li>
<li>
<div class="title">Country:</div>
<div class="text"><?php 
if($ticket->getDistrict()!='')
echo $ticket->getDistrict();
else
echo 'Null'; ?></div>
</li>
<li>
<div class="title">Province:</div>
<div class="text"><?php 
if($ticket->getTehsil())
echo $ticket->getTehsil();
else
echo 'Null'; ?></div>
</li> 
<li>
<div class="title">City:</div>
<div class="text"><?php 
if($ticket->getAgencyTehsilTitle())
echo $ticket->getAgencyTehsilTitle();
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
                                                            
</ul>                                                      
</div>                        
</div>
</div>
<?php }else{ ?>
<div class="span6">
<div class="block-fluid ucard">
<div class="info">                                                                
<ul class="rows">
<li class="heading"><div class="isw-users"></div>Complaint Details</li>

<li>
<div class="title">Country:</div>
<div class="text"><?php 
if($ticket->getDistrict()!='')
echo $ticket->getDistrict();
else
echo 'Null'; ?></div>
</li>
<li>
<div class="title">Province:</div>
<div class="text"><?php 
if($ticket->getTehsil())
echo $ticket->getTehsil();
else
echo 'Null'; ?></div>
</li> 
<li>
<div class="title">City:</div>
<div class="text"><?php 
if($ticket->getAgencyTehsilTitle())
echo $ticket->getAgencyTehsilTitle();
else
echo 'Null'; ?></div>
</li> 
<li>
<div class="title">Subject:</div>
<div class="text"><?php echo $ticket->getSubject();?></div>
</li>
                                                             
</ul>                                                      
</div>                        
</div>
</div>
<?php }?>                     
<div class="span6">
        <div class="block-fluid ucard">
                        <div class="info">                                                                
                            <ul class="rows">
                                <li class="heading">Complaint Info</li>
                                <li>
                                    <div class="title">Status:</div>
                                    <div class="text"><?php echo $ticket->complaint_status(); ?></div>
                                </li>
                                <li>
                                    <div class="title">Priority:</div>
                                    <div class="text"><?php echo $ticket->getPriority(); ?></div>
                                </li>
                                
                                <li>
                                    <div class="title">Agaisnt Dept:</div>
                                    <div class="text"><?php echo Format::htmlchars($ticket->getDeptName()); ?></div>
                                </li>
                                <li>
                                    <div class="title">Create Date:</div>
                                    <div class="text"><?php echo Format::db_datetime($ticket->getCreateDate()); ?></div>
                                </li>
                                <li>
                                    <div class="title">Due Date:</div>
                                    <div class="text">
                                    <?php echo $sla?Format::htmlchars($sla->getName()):'<span class="faded">&mdash; none &mdash;</span>'; ?></div>
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
                                <?php if($thisstaff->canDeleteTickets()) { ?>
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
                                
                                
                                 <?php }?>  
                                  
                                 <li>
                                    <div class="title">Complaint Nature:</div>
                                    <div class="text"><?php  
									$sql_get_topics='SELECT ht.topic_id, ht.topic as name '
            .' FROM '.TOPIC_TABLE. ' ht '
            .' WHERE ht.topic_id="'.$ticket->getTopicId().'" ';
			$res_get_topic=mysql_query($sql_get_topics);
			$row_topic=mysql_fetch_array($res_get_topic);
									echo $row_topic['name'];
									?>&nbsp;</div>
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
<?php 
if($ticket->getDeptId()==2)
{
$sql_securities_markets="Select * from sdms_securities_markets where complaint_id ='".$ticket->getId()."'";
$res_securities_markets=mysql_query($sql_securities_markets);
$row_securities_markets=mysql_fetch_array($res_securities_markets);
?>
<div class="row-fluid">
<div class="span6">
<div class="block-fluid ucard">
			<div class="info">                                                                
				<ul class="rows">
					<li class="heading">Securities Markets</li>
					<li>
						<div class="title">Company/Broker Name</div>
						<div class="text"><?php echo $row_securities_markets['company_id']; ?></div>
					</li>
					<li>
						<div class="title">Share Registrar</div> 	 	 	 	 	
						<div class="text"><?php echo $row_securities_markets['share_registrar']; ?></div>
					</li>
					<li>
						<div class="title">Underwriter</div>
						<div class="text"><?php echo $row_securities_markets['underwriter']; ?></div>
					</li>
					<li>
						<div class="title">Debt Security Trustee</div>
						<div class="text"><?php echo $row_securities_markets['debt_security_trustee']; ?></div>
					</li>
					<li>
						<div class="title">Folio No</div>
						<div class="text"><?php echo $row_securities_markets['folio_no']; ?></div>
					</li>
					<li>
						<div class="title">No of Shares</div>
						<div class="text"><?php echo $row_securities_markets['no_of_shares']; ?></div>
					</li>
					<li>
						<div class="title">CDC A/C No</div>
						<div class="text"><?php echo $row_securities_markets['cdc_ac_no']; ?></div>
					</li>
				 
										  
				</ul>                                                      
			</div>                        
	</div>
</div>
</div>
<?php 
}
else if($ticket->getDeptId()==3){
$sql_insurance="Select * from sdms_insurance where complaint_id ='".$ticket->getId()."'";
$res_insurance=mysql_query($sql_insurance);
$row_insurance=mysql_fetch_array($res_insurance);
?>
<div class="row-fluid">
<div class="span6">
<div class="block-fluid ucard">
			<div class="info">                                                                
				<ul class="rows">   	 	 	 	 	 	 	
					<li class="heading">Insurance</li>
					<li>
						<div class="title">Name of Insurer</div>
						<div class="text"><?php echo $row_insurance['company_id']; ?></div>
					</li>
					<li>
						<div class="title">Name of Surveyor</div> 	 	 	 	 	
						<div class="text"><?php echo $row_insurance['surveyor_title']; ?></div>
					</li>
					<li>
						<div class="title">Name of Broker</div>
						<div class="text"><?php echo $row_insurance['broker_title']; ?></div>
					</li>
					<li>
						<div class="title">Name of Agent</div>
						<div class="text"><?php echo $row_insurance['agent_title']; ?></div>
					</li>
					<li>
						<div class="title">Name of TPA</div>
						<div class="text"><?php echo $row_insurance['tpa_title']; ?></div>
					</li>
					<li>
						<div class="title">Policy No</div>
						<div class="text"><?php echo $row_insurance['policy_no']; ?></div>
					</li>
					<li>
						<div class="title">Sum Assured</div>
						<div class="text"><?php echo $row_insurance['sum_assured']; ?></div>
					</li>
                    <li>
						<div class="title">Claim Amount</div>
						<div class="text"><?php echo $row_insurance['claim_amount']; ?></div>
					</li>
                    <li>
						<div class="title">Folio No</div>
						<div class="text"><?php echo $row_insurance['folio_no']; ?></div>
					</li>
                    <li>
						<div class="title">No of Shares</div>
						<div class="text"><?php echo $row_insurance['no_of_shares']; ?></div>
					</li>
				  	 	
										  
				</ul>                                                      
			</div>                        
	</div>
</div>
</div>
<?php 
}
else if($ticket->getDeptId()==4){

}
else if($ticket->getDeptId()==5){

}
else if($ticket->getDeptId()==6){

}
else if($ticket->getDeptId()==7){
}
 ?>   

</div>
<?php
$sql_complaint_details="Select * from sdms_ticket_thread where ticket_id='".$ticket->getId()."' ORDER By id limit 0,1";
$res_complaint_details=mysql_query($sql_complaint_details);
$row_complaint_details=mysql_fetch_array($res_complaint_details);
?>
<div class="row-fluid">
<div class="span12">
        <div class="block-fluid ucard">
                        <div class="info">                                                                
                            <ul class="rows">
                                <li class="heading">Complaint Detils 
                                <?php 
if($thisstaff->isAdmin()){  ?>
                                <a  class="action-button" style="z-index:9999999999;float:right;">
        <button class="btn btn-info btn_thread" style="padding: 0 10px;"   onclick="getThread_detail(<?php echo $row_complaint_details['id']; ?>);" type="button">
		<i class="icon-edit"></i></button></a>
        <?php }?>
        </li>
       <li>
                                    <div class="title">Subject:</div>
                                    <div class="text"><?php echo $ticket->getSubject();?></div>
                                </li>
                                <li>
                                    <div class="title">Details</div>
                                    <div class="text">
                                    
									<?php echo $row_complaint_details['body'];?>
                                    
        
                                    &nbsp;</div>
                                </li>
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
<?php 
$first_thread=1;
$second_thread=1;

$thread_a=0;
$thread_b=3;
 ?>
<div class="row-fluid">
    <div class="span12">
        <div class="head clearfix">
            <div class="isw-list"></div>
            <h1> Complaint Processing(<?php echo $tcount; ?>)</h1>
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
        <div id="inbox" class="block-fluid" style="margin-bottom:0px;">
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
        <thead>
            <tr>
            <th width="20%">Date</th>
            <th width="50%">Subject</th>
            <th width="15%">Status</th>
            <th width="20%">Action By</th>                                                                    
            </tr>
            </thead>
        </table>
        </div>
        <div class="block-fluid accordion">
        <?php
        $threadTypes=array('M'=>'message','R'=>'response', 'N'=>'note', 'A'=>'affidavite', 'D'=>'department','T'=>'accepted','C'=>'category');
        /*-------- Messages & Responses & Notes (if inline)-------------*/
        $types = array('M', 'R','T','C');
        if($cfg->showNotesInline())
        $types[] = 'N';
        $types[] = 'A';
        $types[] = 'D'; 
		       
        if(($thread=$ticket->getThreadEntries($types))) {
if($ticket->getSource()!='Web'){
        foreach($thread as $entry) {
		 
		if($first_thread==2){
		?>
        <h3>
        <table width="100%">
        <tr>
        <td width="20%"><?php echo Format::db_datetime($entry['created']);?></th>
        <td width="50%"><?php echo Format::htmlchars($entry['title']); ?></th>
        <td width="15%"><?php 
		$sql_status="Select * from sdms_status where status_id='".$entry['complaint_status']."'";
		$res_status=mysql_query($sql_status);
		$row_status=mysql_fetch_array($res_status);
		echo Format::htmlchars($row_status['status_title']); ?></th>
         <td width="20%"><?php echo Format::htmlchars($entry['poster']);?></th> 
            </tr></table>
		</h3>
        <div style="min-height:50px;">
        <p style="height:auto;"><?php echo Format::display($entry['body']); ?></p>
        <?php
        if($entry['attachments'] 
        && ($tentry=$ticket->getThreadEntry($entry['id']))
        && ($links=$tentry->getAttachmentsLinks())) {?> 
        <?php echo $links; ?>  
        <?php }?>
        </div>
        <?php
		}
		
		if($entry['thread_type']=='M')
        $msgId=$entry['id'];
        $first_thread++;
			
		}
		
		
		 foreach($thread as $entry) {
			 
		if($second_thread==3){
		?>
        <h3>
        <table width="100%">
        <tr>
        <td width="20%"><?php echo Format::db_datetime($entry['created']);?></th>
            <td width="50%"><?php echo Format::htmlchars($entry['title']); ?></th>
            <td width="15%"><?php 
		$sql_status="Select * from sdms_status where status_id='".$entry['complaint_status']."'";
		$res_status=mysql_query($sql_status);
		$row_status=mysql_fetch_array($res_status);
		echo Format::htmlchars($row_status['status_title']); ?></th>
         <td width="20%"><?php echo Format::htmlchars($entry['poster']);?></th> 
            </tr></table>
		</h3>
        <div style="min-height:50px;">
        <p style="height:auto;"><?php echo Format::display($entry['body']); ?></p>
        <?php
        if($entry['attachments'] 
        && ($tentry=$ticket->getThreadEntry($entry['id']))
        && ($links=$tentry->getAttachmentsLinks())) {?> 
        <?php echo $links; ?>  
        <?php }?>
        </div>
        <?php
		}
		
		if($entry['thread_type']=='M')
        $msgId=$entry['id'];
        $second_thread++;
		}
}else
{
	foreach($thread as $entry) {
				 
			if($first_thread==2){
			?>
			<h3>
			<table width="100%">
			<tr>
			<td width="20%"><?php echo Format::db_datetime($entry['created']);?></th>
				<td width="50%"><?php echo Format::htmlchars($entry['title']); ?></th>
				<td width="15%"><?php 
			$sql_status="Select * from sdms_status where status_id='".$entry['complaint_status']."'";
			$res_status=mysql_query($sql_status);
			$row_status=mysql_fetch_array($res_status);
			echo Format::htmlchars($row_status['status_title']); ?></th>
			 <td width="20%"><?php echo Format::htmlchars($entry['poster']);?></th> 
				</tr></table>
			</h3>
			<div style="min-height:50px;">
			<p style="height:auto;"><?php echo Format::display($entry['body']); ?></p>
			<?php
			if($entry['attachments'] 
			&& ($tentry=$ticket->getThreadEntry($entry['id']))
			&& ($links=$tentry->getAttachmentsLinks())) {?> 
			<?php echo $links; ?>  
			<?php }?>
			</div>
			<?php
			}
			if($entry['thread_type']=='M')
			$msgId=$entry['id'];
			$first_thread++;
			}		
	foreach($thread as $entry) {
	
	if($second_thread==3){
	?>
	<h3>
	<table width="100%">
	<tr>
	<td width="20%"><?php echo Format::db_datetime($entry['created']);?></th>
	<td width="50%"><?php echo Format::htmlchars($entry['title']); ?></th>
	<td width="15%"><?php 
	$sql_status="Select * from sdms_status where status_id='".$entry['complaint_status']."'";
	$res_status=mysql_query($sql_status);
	$row_status=mysql_fetch_array($res_status);
	echo Format::htmlchars($row_status['status_title']); ?></th>
	<td width="20%"><?php echo Format::htmlchars($entry['poster']);?></th> 
	</tr></table>
	</h3>
	<div style="min-height:50px;">
	<p style="height:auto;"><?php echo Format::display($entry['body']); ?></p>
	<?php
	if($entry['attachments'] 
	&& ($tentry=$ticket->getThreadEntry($entry['id']))
	&& ($links=$tentry->getAttachmentsLinks())) {?> 
	<?php echo $links; ?>  
	<?php }?>
	</div>
	<?php
	}
	if($entry['thread_type']=='M')
	$msgId=$entry['id'];
	$second_thread++;}
	}
	foreach($thread as $entry) {
	
	if($thread_a==$thread_b){
	?>
	<h3>
	<table width="100%">
	<tr>
	<td width="20%"><?php echo Format::db_datetime($entry['created']);?></th>
	<td width="50%"><?php echo Format::htmlchars($entry['title']); ?></th>
	<td width="15%"><?php 
	$sql_status="Select * from sdms_status where status_id='".$entry['complaint_status']."'";
	$res_status=mysql_query($sql_status);
	$row_status=mysql_fetch_array($res_status);
	echo Format::htmlchars($row_status['status_title']); ?></th>
	<td width="20%"><?php echo Format::htmlchars($entry['poster']);?></th> 
	</tr></table>
	</h3>
	<div style="min-height:50px;">
	<p style="height:auto;"><?php echo Format::display($entry['body']); ?></p>
	<?php
	if($entry['attachments'] 
	&& ($tentry=$ticket->getThreadEntry($entry['id']))
	&& ($links=$tentry->getAttachmentsLinks())) {?> 
	<?php echo $links; ?>  
	<?php }?>
	</div>
	<?php
	$thread_a=2;
	}
	if($entry['thread_type']=='M')
	$msgId=$entry['id'];
	$thread_a++;}
        } else {
        echo '<p>Error fetching ticket thread - get technical help.</p>';
        }?>   
        </div>
    </div>                 
</div>
<?php //accept status ?>


<!--Post Reply  ,  Post Internal Note  ,  Dept. Transfer  ,  Reassign Complaint -->
<?php 
//echo $_SESSION['_staff']['ID'];
//echo $ticket->getStaffId;
//exit;
if($_SESSION['_staff']['ID'] == $ticket->getStaffId()){ 
if(!$ticket->getAcceptStatus())
{ ?>
<div class="row-fluid">
<div class="span12">
<div class="head clearfix">
<div class="isw-brush"></div>
<h1> Do you want to <b>claim</b> (self assign) this Complaint?</h1>
</div>
<div class="block">
<div class="row-form clearfix">                            
<div class="span3"></div>
<div class="span9">
<p>    
<form action="tickets.php?id=<?php echo $ticket->getId(); ?>#accepted" method="post">
<?php csrf_token(); ?>
<input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
<input type="hidden" name="a" value="accepted">
<input type="hidden" name="title" id="title" value="accepted title"/>
<input type="hidden" name="note" value="accepted internal notes" >
<input type="hidden" name="complaint_status" value="1" >
<input type="submit" class="btn btn-large" value="Accept" />
</form>

<form action="" method="post">
<input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
<input type="hidden" name="a" value="deny">
<input type="hidden" name="title" id="title" value="deny title"/>
<input type="hidden" name="note" value="deny internal notes" >
<input type="hidden" name="complaint_status" value="1" >
<input type="submit" class="btn btn-large  btn-warning" value="Deny" />
</form>
</p>              
</div>                            
</div>    
</div>
</div>
</div>
<?php }else{ if($thisstaff->canPostReply()) { ?>
<div class="row-fluid">
<div class="span12">
<div class="head clearfix">
    <div class="isw-list"></div>
    <h1>Complaints Actions</h1>
</div>
<div class="block-fluid tabs">
    <ul>
    	<?php //if($thisstaff->canPostReply()) { ?>
        <!--<li><a id="reply_tab" href="#reply">Post Reply</a></li>-->        
		<?php //} ?>
        
         <?php if($thisstaff->isFocalPerson()) { ?>
         <li><a id="category_tab" href="#category">Set Category</a></li>
          <?php }?>
        <?php if($thisstaff->canPostReply()) { ?>
        <li><a id="note_tab" href="#note">Update Complaint</a></li>
        <!--<li><a id="affidavite_tab" href="#affidavite">Affidavit</a></li>-->
        <?php }?>
        <?php
         // if($thisstaff->canTransferTickets()) { ?>
        <!--<li><a id="transfer_tab" href="#transfer">Dept. Transfer</a></li>-->
        <?php  //if($thisstaff->canTransferTickets()) {  ?>
        <!--<li><a id="dept_tab" href="#dept">Dept. Transfer</a></li>-->
        <?php //}?>
        <?php
         //}                               
       if($thisstaff->canAssignTickets()) { ?>
        <li><a id="assign_tab" href="#assign"><?php echo $ticket->isAssigned()?'Reassign Complaint':'Assign Complaint'; ?></a></li>
        <?php  } ?>
    </ul>
    <!--Complaint Reply-->
	<?php /*?> if($thisstaff->canPostReply()) { ?>
    <div id="reply">
        <form id="reply" action="tickets.php?id=<?php echo $ticket->getId(); ?>#reply" name="reply" method="post" enctype="multipart/form-data">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="msgId" value="<?php echo $msgId; ?>">
        <input type="hidden" name="a" value="reply">
        <input type="hidden" value="Post Reply" name="title"  />
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
                <div class="row-form clearfix">
                <div class="span3">Complaint Status:</div>
                <div class="span9"><select name="complaint_status" style="width:300px;">
        <?php
        if($status=Status::getStatus()) {
            foreach($status as $id =>$name) {
                echo sprintf('<option value="%d" %s>%s</option>',
                        $id, ($ticket->complaint_status_title()==$id)?'selected="selected"':'',$name);
            }
        }
        ?>
        </select>
            &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['complaint_status_title']; ?></font></div>
                </div>  
                <div class="footer tar" style="margin-top: 0px;">
                    <input type="submit" class="btn" value="Post Reply" />
                    <input type="reset" class="btn" value="Reset" />
                </div>            
            </div>
        </form>   
    </div>
    <?php  }<?php */?> 
    <!--Category Summary-->   
    <?php  if($thisstaff->isFocalPerson()) { ?>                    
    <div id="category">                                    
    <form id="category" action="tickets.php?id=<?php echo $ticket->getId(); ?>#category" name="category" method="post" enctype="multipart/form-data">
	<?php csrf_token(); ?>
    <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
    <input type="hidden" name="a" value="category">
    <div class="block-fluid" style="margin-bottom:0px;">                        
    <?php if($errors['category']) {?>
    <div class="row-form clearfix">
        <div class="span3">&nbsp;</div>
        <div class="span9"><?php echo $errors['category']; ?></div>
    </div>
     <?php  } ?>
   <div class="row-form clearfix">
        <div class="span3"> Category:</div>
        <div class="span9"><select name="topicId">
                        <?php
    if($topics=Topic::getPublicHelpTopics()) {
    foreach($topics as $id =>$name) {
    echo sprintf('<option value="%d" %s>%s</option>',
            $id, ($info['topicId']==$id)?'selected="selected"':'', $name);
    }
    } else { ?>
    <option value="0" >General Inquiry</option>
    <?php
    } ?>
    </select></div>
    </div> 
     <?php /*?><div class="row-form clearfix">
                <div class="span3">Complaint Status:</div>
                <div class="span9"><select name="complaint_status" style="width:300px;">
        <?php
        if($status=Status::getStatus($thisstaff->isAdmin())) {
            foreach($status as $id =>$name) {
                echo sprintf('<option value="%d" %s>%s</option>',
                        $id, ($ticket->complaint_status_title()==$id)?'selected="selected"':'',$name);
            }
        }
        ?>
        </select>
            &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['complaint_status_title']; ?></font></div>
                </div><?php */?>   
    <div class="footer tar" style="margin-top: 0px;">
        <input type="submit" class="btn" value="Add Category" />
        <input type="reset" class="btn" value="Reset" />
    </div>                            
</div>
               </form>
    </div>   
    <?php } ?>
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
        <div class="span3"> Title:</div>
        <div class="span9"><input type="text" placeholder="Note title..." name="title" id="title" value="<?php echo $info['title']; ?>"/></div>
    </div> 
   <div class="row-form clearfix">
        <div class="span3">Internal Note:</div>
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
        <?php
        if($status=Status::getStatus($thisstaff->isAdmin())) {
            foreach($status as $id =>$name) {
                echo sprintf('<option value="%d" %s>%s</option>',
                        $id, ($ticket->complaint_status_title()==$id)?'selected="selected"':'',$name);
            }
        }
        ?>
        </select>
            &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['complaint_status_title']; ?></font></div>
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
    <?php  /*if($thisstaff->canPostReply()) { ?>
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
    <div class="span9"><input type="text" placeholder="Afidavite Title..." name="title" id="title" value="<?php echo $info['affidavite']; ?>"/></div>
    </div> 
    <div class="row-form clearfix">
    <div class="span3">Affidavite Note:</div>
    <div class="span9"><textarea placeholder="Affidavite details..." name="note" id="note" ><?php echo $info['note']; ?></textarea></div>
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
        <?php
        if($status=Status::getStatus()) {
            foreach($status as $id =>$name) {
                echo sprintf('<option value="%d" %s>%s</option>',
                        $id, ($ticket->complaint_status_title()==$id)?'selected="selected"':'',$name);
            }
        }
        ?>
        </select>
            &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['complaint_status_title']; ?></font></div>
                </div>                               
    <div class="footer tar" style="margin-top: 0px;">
    <input type="submit" class="btn" value="Post Affidavte" />
    <input type="reset" class="btn" value="Reset" />
    </div>                            
    </div>
    </form>
  </div>   
    <?php }*/ ?>
    <!--Depat Compunication-->
     <?php /*?> if($thisstaff->canTransferTickets()) { ?>  
    <div id="dept">
        <form id="dept" action="tickets.php?id=<?php echo $ticket->getId(); ?>#dept" name="dept" method="post" enctype="multipart/form-data">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="msgId" value="<?php echo $msgId; ?>">
        <input type="hidden" name="a" value="dept">
         <input type="hidden" value="Dept Transfer" name="title"  />
            <div class="block-fluid" style="margin-bottom:0px;">  
            <!--<div class="row-form clearfix">
        <div class="span3">Title:</div>
        <div class="span9"><input type="text" placeholder="Department Title..." name="title" id="title" value="<?php //echo $info['affidavite']; ?>"/></div>
        </div>-->                      
                <div class="row-form clearfix">
                <div class="span3">Department:</div>
                <div class="span9"><?php
            echo sprintf('<span class="faded">Complaint is currently in <b>%s</b> department.</span>', $ticket->getDeptName());
            ?>  <br>
                    <select id="deptId" name="deptId">
                        <option value="0" selected="selected">&mdash; Select Target Department &mdash;</option>
                        <?php
                        if($depts=Dept::getDepartments()) {
                            foreach($depts as $id =>$name) {
                                if($id==$ticket->getDeptId()) continue;
                                echo sprintf('<option value="%d" %s>%s</option>',$id, ($info['deptId']==$id)?'selected="selected"':'',$name);
                            }
                        }
                        ?>
                    </select>&nbsp;<span class='error'>*&nbsp;<?php echo $errors['deptId']; ?></div>
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
        <?php
        if($status=Status::getStatus()) {
            foreach($status as $id =>$name) {
                echo sprintf('<option value="%d" %s>%s</option>',
                        $id, ($ticket->complaint_status_title()==$id)?'selected="selected"':'',$name);
            }
        }
        ?>
        </select>
            &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['complaint_status_title']; ?></font></div>
                </div> 
                <div class="footer tar" style="margin-top: 0px;">
                <input type="submit" class="btn" value="Print" />
                <input type="reset" class="btn" value="Reset" />
                </div>                                           
            </div>
        </form>                                  
    </div>
    <?php } <?php */?>
    <!--Assign Complaint-->                     
    <?php if($thisstaff->canAssignTickets()) { ?>
    <div id="assign">
    <form id="assign" action="tickets.php?id=<?php echo $ticket->getId(); ?>#assign" onsubmit="return check_depart_update()" name="assign" method="post" enctype="multipart/form-data">
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
        <?php if($thisstaff->isAdmin()){ ?> 
         <?php if(isset($_REQUEST['id'])){
            $sql_depart="Select * from sdms_department where dept_id = '".$ticket->getDeptId()."'";
			$res_depart=db_query($sql_depart) or die('error');
            $row_result=db_fetch_row($res_depart);
            ?>
            <div class="row-form clearfix">
        <div class="span3">Department Type:</div>
        <div class="span9">
        <select name="m_deptId"  onChange="get_Sub_Com(this.value);" style="width:300px;">
    <?php
    if($depts=Dept::getDepartments()) {
    foreach($depts as $id =>$name) {
		echo sprintf('<option value="%d" %s>%s</option>',$id,(($row_result[1]==$id)?'selected="selected"':''), $name);
    }
    }
    ?>
    </select></div>
        </div>
            <?php } ?>
        <div id="show_sub_department" class="row-form clearfix">
           <div class="span3">Departments:</div>
        <div class="span9">
             <select name="dept_id_new" id="dept_id_new" style="width:300px;" onchange="set_assign_value(this.value);">
                    <option value="" >&mdash; Select Sub-Department &mdash;</option>
                    <?php
        if($sub_depart=Dept::getSubPublicCategory($row_result[1])) {
        foreach($sub_depart as $id =>$name) {
        echo sprintf('<option value="%d" %s>%s</option>',$id, ($row_result[0]==$id)?'selected="selected"':'',$name);
        }
        }
        ?>
                    </select>
        &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['dept_id']; ?></font>
    </div> 
        </div>
        <div class="row-form clearfix">
        <div class="span3">SLA Plan:</div>
        <div class="span9"><select name="slaId"  style="width:300px;">
        <option value="0" selected="selected" >&mdash; None &mdash;</option>
        <?php
        if($slas=SLA::getSLAs()) {
        foreach($slas as $id =>$name) {
        echo sprintf('<option value="%d" %s>%s</option>', $id, ($sla->getId()==$id)?'selected="selected"':'',$name);
        }
        }
        ?>
        </select></div>
        </div>
        <input type="hidden" name="assignId" id="assignId" value="" />
        
        <?php }
		else{
			?>
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
        <option value="0" selected="selected">&mdash; Select Staff Member &mdash;</option>
        <?php
        if($ticket->isOpen() && !$ticket->isAssigned())
        echo sprintf('<option value="%d">Claim Complaint (comments optional)</option>', $thisstaff->getId());
        $sid=$tid=0;
		//Childs under this user
		if($staff->isFocalPerson())
		if(($users=Staff::getAdminMembers())) {
        echo '<OPTGROUP label="Agencies Members ('.count($users).')">';
        $staffId=$ticket->isAssigned()?$ticket->getStaffId():0;
        foreach($users as $id => $name) {
        if($staffId && $staffId==$id)
        continue;
        
        $k="s$id";
        echo sprintf('<option value="%s" %s>%s</option>',$k,(($info['assignId']==$k)?'selected="selected"':''),$name);
        }
        echo '</OPTGROUP>';
        }
		//show last assign user
		if(($admin_users=Staff::getAssignByMember($ticket->getId()))) {
        echo '<OPTGROUP label="Marked By('.count($admin_users).')">';
        $staffId=$ticket->isAssigned()?$ticket->getStaffId():0;
        foreach($admin_users as $id => $name) {
        if($staffId && $staffId==$id)
        continue;
        
        $k="s$id";
        echo sprintf('<option value="%s" %s>%s</option>',
        $k,(($info['assignId']==$k)?'selected="selected"':''),$name);
        }
        echo '</OPTGROUP>';
        }
		//Childs under this user
		if(($users=Staff::getParentMembers($staff->getid()))) {
        echo '<OPTGROUP label="Agencies Members ('.count($users).')">';
        $staffId=$ticket->isAssigned()?$ticket->getStaffId():0;
        foreach($users as $id => $name) {
        if($staffId && $staffId==$id)
        continue;
        $k="s$id";
        echo sprintf('<option value="%s" %s>%s</option>',$k,(($info['assignId']==$k)?'selected="selected"':''),$name);
        }
        echo '</OPTGROUP>';
        }
        ?>
        </select>
        &nbsp;<span class='error'>*&nbsp;<?php echo $errors['assignId']; ?></span></div>
        </div>
  <?php }?>
        
        <div class="row-form clearfix">
        <div class="span3">Comments:</div>
        <div class="span9"><span>Enter reasons for the assignment or instructions for assignee.</span>
        <span class="error">*&nbsp;<?php echo $errors['assign_comments']; ?></span><br>
        <textarea placeholder="Assigning details..." name="assign_comments" id="assign_comments" required ><?php echo $info['assign_comments']; ?></textarea></div>
        </div>   
         <div class="row-form clearfix">
                <div class="span3">Complaint Status:</div>
                <div class="span9"><select name="complaint_status" id="" style="width:300px;">
        <?php
        if($status=Status::getStatus($thisstaff->isAdmin())) {
            foreach($status as $id =>$name) {
				echo sprintf('<option value="%d" %s>%s</option>',$id, ($ticket->complaint_status_title()==$id)?'selected="selected"':'',$name);
				}
        }
        ?>
        </select>
            &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['complaint_status_title']; ?></font></div>
                </div>                              
        <div class="footer tar" style="margin-top: 0px;">
        <input type="submit" class="btn" value="<?php echo $ticket->isAssigned()?'Reassign':'Assign'; ?>" />
        <input type="reset" class="btn" value="Reset" />
        </div>                            
        </div>
        </form>
    </div>  
	<?php } ?>   
  
</div>
</div>
</div>     
<?php } }?>
<?php }?>  
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
<div style="display:none;" class="dialog" id="ba_btn_thread" title="Complaint # <?php echo $ticket->getExtId(); ?> Discription">
</div>
<script type="text/javascript" src="js_old/ticket.js"></script>
</div><!--WorkPlace End-->  
</div>