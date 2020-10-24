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
function checkcategory($complaint_id,$topid_id){
echo $complaint_id.'<br>';
echo $topid_id;exit;
}
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
					
				
				}
		});
	<?php } }?>	
  });

  $('#activity_update, #submit_btn').click(function () {
    	$('#absoulte_div').css("display", "");
});

});
function get_subcategory(cat_id,s_id){
	var select_id = s_id.split("_");
	for(x=parseInt(select_id[1])+1;x<=10;x++){
			$("#"+x+"_tier").remove();
		}
	var tiers = parseInt(select_id[1]);
	$("#select_" + tiers).attr('name', 'topicId');
	var next_tiers = tiers+1;   
	//alert(next_tiers);
	$.ajax({
	url:"get_sub_category.php",
	data: "cat_id="+cat_id+"&next_tiers="+next_tiers+"",
	success: function(msg){
	if(msg.trim() != ''){
	$("#"+tiers+"_tier").after('<div class="row-form clearfix"  id="'+next_tiers+'_tier"></div>');
	$("#tiers").val(next_tiers);
	$("#"+next_tiers+"_tier").html(msg);
	}
}
});
}
function get_Sub_Status(status_id)
{
$.ajax({
	url:"get_sub_status.php",
	data: "status_id="+status_id,
	success: function(msg){
	$("#show_sub_status").html(msg);
}
});
}
function get_Sub_Status_reply(status_id)
{
$.ajax({
	url:"get_sub_status.php",
	data: "status_id="+status_id,
	success: function(msg){
	$("#show_sub_status_reply").html(msg);
}
});
}
function get_Sub_Status_note(status_id)
{
	$("#activity_update").attr('disabled',true);
	$("#loading_status").css('display', '');
	
$.ajax({
	url:"get_sub_status.php",
	data: "status_id="+status_id,
	success: function(msg){
	$("#show_sub_status_note").html(msg);
	$("#activity_update").attr('disabled',false);
	$("#loading_status").css('display', 'none');
}
});
}

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

function get_focal_person(dept_id){
	$.ajax({
	url:"get_focal_person.php",
	data: "dept_id="+dept_id,
	success: function(msg){
	$("#assignId_transfer").val(msg.trim());
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
<div class="page-header" style="padding-top:20px;" >
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
       <!-- <a class="action-button" >
        <button class="btn btn-inverse" id="btn_close" type="button"><i class="icon-remove-circle"></i>Close</button></a>    -->             
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
    <li class="heading"><div class="isw-users"></div>Applicant Info </li>
    <li>
    <div class="title">Name:</div>
    <div class="text"><?php 
    echo $ticket->getName_Title().' '.Format::htmlchars($ticket->getName());
    ?></div>
    </li>
    <li>
    <div class="title">CNIC/NICOP/PSPT:</div>
    <div class="text"><?php
    if($ticket->getNic()!='')
    {
     echo Format::htmlchars($ticket->getNic());
    echo sprintf('&nbsp;&nbsp;<a href="tickets.php?a=search&query=%s" title="Related Complaints" data-dropdown="#action-dropdown-stats">(<b>%d</b>)</a>',urlencode($ticket->getNic()), $ticket->getTotalTickets($ticket->getNic()));
    }
    else echo 'Null'; ?></div>
    </li>
    <li>
    <div class="title">Mobile:</div>
    <div class="text"><?php 
    if( $ticket->getPhoneNumber()!='')
    echo $ticket->getPhoneNumber();
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
    <div class="title">Postal Address:</div>
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
                                    <div class="text">
									<?php 
                                    if($ticket->getPriority())
                                    echo $ticket->getPriority();
                                    else
                                    echo 'Null'; ?>
                                    </div>
                                </li>
                                
                                <li>
                                    <div class="title">Dept:</div>
                                    <div class="text"><?php echo Format::htmlchars($ticket->getDeptName()); ?></div>
                                </li>
                                <li>
                                    <div class="title">Lodged Date/Time:</div>
                                    <div class="text"><?php echo Format::db_datetime($ticket->getCreateDate()); ?></div>
                                </li>
                                <?php if($ticket->getReopenDate()!=''){ ?>
                                <li>
                                    <div class="title">ReOpen Date/Time:</div>
                                      <div class="text"><?php echo Format::db_datetime($ticket->getReopenDate()); ?></div>
                                </li>
                                <?php } ?>
                                <?php /*?><li>
                                    <div class="title">Due Date:</div>
                                    <div class="text">
                                    <?php echo $sla?Format::htmlchars($sla->getName()):'<span class="faded">&mdash; none &mdash;</span>'; ?></div>
                                </li><?php */?>
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
                                <?php /*?><li>
                                    <div class="title">Due Date:</div>
                                    <div class="text"><?php echo Format::db_datetime($ticket->getEstDueDate()); ?></div>
                                </li><?php */?>
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
                                    <div class="title">Complaint Category:</div>
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
      

</div>
<div class="row-fluid">
    <div class="span6">
    <div class="block-fluid ucard">
    <div class="info">                                                                
    <ul class="rows">
    <li class="heading">Department Info</li>
    <li>
    <div class="title">Department:</div>
    <div class="text"><?php echo Format::htmlchars($ticket->getDeptName()); ?></div>
    </li>
    <?php 
    if($ticket->getDeptId()==2){
    $sql_securities_markets="Select * from sdms_ticket_capital_markets where complaint_id ='".$ticket->getId()."'";
    $res_securities_markets=mysql_query($sql_securities_markets);
    $row_securities_markets=mysql_fetch_array($res_securities_markets);
    ?>
    <li>
    <div class="title">Type</div>
    <div class="text"><?php 
    if($row_securities_markets['cm_type']!='')
    echo $row_securities_markets['cm_type'];
    else
    echo 'Null';
    ?></div>
    </li>
    <?php if($row_securities_markets['cm_broker_title']!=''){ ?>
    <li>
    <div class="title">Brokers List:</div> 	 	 	 	 	
    <div class="text"><?php echo $row_securities_markets['cm_broker_title'];?></div>
    </li>
    <?php }?>
    
	<?php if($row_securities_markets['cm_broker_agent']!=''){ ?>
    <li>
    <div class="title">Agent List:</div>
    <div class="text"><?php echo $row_securities_markets['cm_broker_agent']; ?></div>
    </li>
    <?php }?>
    <li>
    <div class="title">Folio No:</div>
    <div class="text"><?php
    if($row_securities_markets['cm_folio_no']!='')
    echo $row_securities_markets['cm_folio_no'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">CDC A/C No</div>
    <div class="text"><?php 
    if($row_securities_markets['cm_cdc_ac_no']!='')
    echo $row_securities_markets['cm_cdc_ac_no'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">No of Shares</div>
    <div class="text"><?php 
    if($row_securities_markets['cm_no_of_shares']!='')
    echo $row_securities_markets['cm_no_of_shares']; 
    else
    echo 'Null';
    ?></div>
    </li>			 									  
    <?php }
    else if($ticket->getDeptId()==3){
    $sql_insurance="Select * from sdms_ticket_insurance where complaint_id ='".$ticket->getId()."'";
    $res_insurance=mysql_query($sql_insurance);
    $row_insurance=mysql_fetch_array($res_insurance);
    ?>				
    <li>
    <div class="title">Type</div>
    <div class="text"><?php
    if($row_insurance['i_type']!='')
    echo $row_insurance['i_type'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">Company List:</div> 	 	 	 	 	
    <div class="text"><?php 
    if($row_insurance['i_broker_title']!='')
    echo $row_insurance['i_broker_title'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">Broker Agent:</div>
    <div class="text"><?php 
    if($row_insurance['i_broker_agent']!='')
    echo $row_insurance['i_broker_agent'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">Policy No:</div>
    <div class="text"><?php 
    if($row_insurance['i_policy_no']!='')
    echo $row_insurance['i_policy_no'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">Sum Assured</div>
    <div class="text"><?php
    if($row_insurance['i_sum_assured']!='')
    echo $row_insurance['i_sum_assured'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">Claim Amount</div>
    <div class="text"><?php
    if($row_insurance['i_claim_amount']!='')
    echo $row_insurance['i_claim_amount'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">Folio No</div>
    <div class="text"><?php
    if($row_insurance['i_folio_no']!='')
    echo $row_insurance['i_folio_no'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">No of Shares</div>
    <div class="text"><?php
    if($row_insurance['i_no_of_shares']!='')
    echo $row_insurance['i_no_of_shares'];
    else
    echo 'Null';
    ?></div>
    </li>
    <?php }
    else if($ticket->getDeptId()==4){
    $sql_ticket_scd="Select * from sdms_ticket_scd where complaint_id ='".$ticket->getId()."'";
    $res_ticket_scd=mysql_query($sql_ticket_scd);
    $row_ticket_scd=mysql_fetch_array($res_ticket_scd);
    ?>
    <li>
    <div class="title">Type:</div>
    <div class="text"><?php
    if($row_ticket_scd['scd_type']!='')
    echo $row_ticket_scd['scd_type'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">Broker Title:</div>
    <div class="text"><?php
    if($row_ticket_scd['scd_broker_title']!='')
    echo $row_ticket_scd['scd_broker_title'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">REIT Scheme:</div>
    <div class="text"><?php
    if($row_ticket_scd['reit_scheme']!='')
    echo $row_ticket_scd['reit_scheme'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">Modaraba Fund:</div>
    <div class="text"><?php
    if($row_ticket_scd['modaraba_fund']!='')
    echo $row_ticket_scd['modaraba_fund'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">Mutual Fund:</div>
    <div class="text"><?php
    if($row_ticket_scd['mutual_fund']!='')
    echo $row_ticket_scd['mutual_fund'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">Pension Fund:</div>
    <div class="text"><?php
    if($row_ticket_scd['pension_fund']!='')
    echo $row_ticket_scd['pension_fund'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">Registration No./Folio No.</div>
    <div class="text"><?php
    if($row_ticket_scd['scd_folio_no']!='')
    echo $row_ticket_scd['scd_folio_no'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">CDC A/C No:</div>
    <div class="text"><?php
    if($row_ticket_scd['scd_cdc_ac_no']!='')
    echo $row_ticket_scd['scd_cdc_ac_no'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">No. of Units:</div>
    <div class="text"><?php
    if($row_ticket_scd['scd_no_of_units']!='')
    echo $row_ticket_scd['scd_no_of_units'];
    else
    echo 'Null';
    ?></div>
    </li>
    <?php }
    else if($ticket->getDeptId()==5){
    $sql_ticket_e_services="Select * from sdms_ticket_e_services where complaint_id ='".$ticket->getId()."'";
    $res_ticket_e_services=mysql_query($sql_ticket_e_services);
    $row_ticket_e_services=mysql_fetch_array($res_ticket_e_services);
    ?>
    <li>
    <div class="title">Company Name</div>
    <div class="text"><?php
    if($row_ticket_e_services['e_company_title']!='')
    echo $row_ticket_e_services['e_company_title'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">Company Registration Office*</div> 	 	 	 	 	
    <div class="text"><?php 
    if($row_ticket_e_services['e_registration_office']!='')
    echo $row_ticket_e_services['e_registration_office'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">Process Name</div>
    <div class="text"><?php 
    if($row_ticket_e_services['e_process_name']!='')
    echo $row_ticket_e_services['e_process_name'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">User ID</div>
    <div class="text"><?php 
    if($row_ticket_e_services['e_user_id']!='')
    echo $row_ticket_e_services['e_user_id'];
    else
    echo 'Null';
    ?></div>
    </li> 
    </ul>
    <?php }
    else if($ticket->getDeptId()==6){
    $sql_ticket_cr="Select * from sdms_ticket_cr where complaint_id ='".$ticket->getId()."'";
    $res_ticket_cr=mysql_query($sql_ticket_cr);
    $row_ticket_cr=mysql_fetch_array($res_ticket_cr);
    ?>
    <li>
    <div class="title">Status</div>
    <div class="text"><?php
    if($row_ticket_cr['cr_status']!='')
    echo $row_ticket_cr['cr_status'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">Name of Company</div>
    <div class="text"><?php
    if($row_ticket_cr['cr_company_title']!='')
    echo $row_ticket_cr['cr_company_title'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">Folio No:</div>
    <div class="text"><?php
    if($row_ticket_cr['cr_folio_no']!='')
    echo $row_ticket_cr['cr_folio_no'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">No of Shares:</div>
    <div class="text"><?php
    if($row_ticket_cr['cr_no_of_shares']!='')
    echo $row_ticket_cr['cr_no_of_shares'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">CRO:</div>
    <div class="text"><?php
    if($row_ticket_cr['cr_cro']!='')
    echo $row_ticket_cr['cr_cro'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">Process Name:</div>
    <div class="text"><?php
    if($row_ticket_cr['cr_process_name']!='')
    echo $row_ticket_cr['cr_process_name'];
    else
    echo 'Null';
    ?></div>
    </li>	 	
    
    <?php }
    else if($ticket->getDeptId()==18){
    $sql_ticket_cs="Select * from sdms_ticket_cs where complaint_id ='".$ticket->getId()."'";
    $res_ticket_cs=mysql_query($sql_ticket_cs);
    $row_ticket_cs=mysql_fetch_array($res_ticket_cs);
    ?>
    
    <li>
    <div class="title">Company Title:</div>
    <div class="text"><?php
    if($row_ticket_cs['cs_company_title']!='')
    echo $row_ticket_cs['cs_company_title'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">CDC AC NO:</div>
    <div class="text"><?php
    if($row_ticket_cs['cs_cdc_ac_no']!='')
    echo $row_ticket_cs['cs_cdc_ac_no'];
    else
    echo 'Null';
    ?></div>
    </li>
    <li>
    <div class="title">Folio No:</div>
    <div class="text"><?php
    if($row_ticket_cs['cs_folio_no']!='')
    echo $row_ticket_cs['cs_folio_no'];
    else
    echo 'Null';
    ?></div>
    </li>	
    <?php } 
	?> 
    
    </ul>                                                      
    </div>                        
    </div>
    </div>
    <div class="span6">
    <div class="block-fluid ucard">
    <div class="info">                                                                
    <ul class="rows">
    <li class="heading">Attachment </li>
    <?php 
	$sql_attachments="Select * from sdms_ticket_evidence where ticket_id ='".$ticket->getId()."'";
    $res_attachments=mysql_query($sql_attachments);
    $row_attachments=mysql_fetch_array($res_attachments);
	?>
    <?php if($row_attachments['evidence_1']!='' &&  (file_exists('upload/'.$row_attachments['evidence_1'])) ){ ?>
    <li>
    <div class="title">Attachment:</div>
    <div class="text"><a href="attachment_download.php?file=<?php echo $row_attachments['evidence_1']; ?>"><img src="img/download-btn.png" ></a></div>
    </li>
    <?php }?>
    <?php if($row_attachments['evidence_2']!='' &&  (file_exists('upload/'.$row_attachments['evidence_2'])) ){ ?>
    <li>
    <div class="title">Attachment:</div>
    <div class="text"><a href="attachment_download.php?file=<?php echo $row_attachments['evidence_2']; ?>"><img src="img/download-btn.png" ></a></div>
    </li>
    <?php }?>
         <?php if($row_attachments['evidence_3']!='' &&  (file_exists('upload/'.$row_attachments['evidence_3'])) ){ ?>
    <li>
    <div class="title">Attachment:</div>
    <div class="text"><a href="attachment_download.php?file=<?php echo $row_attachments['evidence_3']; ?>"><img src="img/download-btn.png" ></a></div>
    </li>
    <?php }?>
    <?php if($row_attachments['evidence_4']!='' &&  (file_exists('upload/'.$row_attachments['evidence_4'])) ){ ?>
    <li>
    <div class="title">Attachment:</div>
    <div class="text"><a href="attachment_download.php?file=<?php echo $row_attachments['evidence_4']; ?>"><img src="img/download-btn.png" ></a></div>
    </li>
    <?php }?>
    <?php if($row_attachments['evidence_5']!='' &&  (file_exists('upload/'.$row_attachments['evidence_5'])) ){ ?>
    <li>
    <div class="title">Attachment:</div>
    <div class="text"><a href="attachment_download.php?file=<?php echo $row_attachments['evidence_5']; ?>"><img src="img/download-btn.png" ></a></div>
    </li>
    <?php }?>
       <?php if($row_attachments['evidence_6']!='' &&  (file_exists('upload/'.$row_attachments['evidence_6'])) ){ ?>
    <li>
    <div class="title">Attachment:</div>
    <div class="text"><a href="attachment_download.php?file=<?php echo $row_attachments['evidence_6']; ?>"><img src="img/download-btn.png" ></a></div>
    </li>
    <?php }?>
    
    </ul>                                                      
    </div>                        
    </div>
    </div>
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
                            <div class="text"><?php
                            if($ticket->getSubject())
                            echo $ticket->getSubject();
                            else
                            echo 'Null';
                            ?></div>
                            </li>
                            <li>
                            <div class="title">Details</div>
                            <div class="text">
                            
                            <?php 
                            if($row_complaint_details['body']!='')
                            echo $row_complaint_details['body'];
                            else
                            echo 'Null';?>
                            
                            
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
            <h1> Complaint Log(<?php echo $tcount; ?>)</h1>
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

<div style="position:absolute;width:965px;height:530px;background-color:#000;z-index:888888888;opacity:.3;text-align:center;display:none;" id="absoulte_div"><img style="z-index:99999999;margin-top:200px;" src="img/loaders/c_loader.gif"></div>
<!--Post Reply  ,  Post Internal Note  ,  Dept. Transfer  ,  Reassign Complaint -->
<?php 
//echo $_SESSION['_staff']['ID'];
//echo $ticket->getStaffId;
//exit;
if($_SESSION['_staff']['ID'] == $ticket->getStaffId()  && $ticket->getStatus() != 'closed'){ 

if(!$ticket->getAcceptStatus() && !$thisstaff->isAdmin()){ ?>
<div class="row-fluid">

<div class="span6">
<div class="block-fluid ucard">
<div class="info">
<ul class="rows">
<li class="heading">Do you want to <b>claim</b> (self assign) this Complaint?</li>
<li>
<div class="title">Accept:</div>
<div class="text"><form action="tickets.php?id=<?php echo $ticket->getId(); ?>#accepted" method="post">
<?php csrf_token(); ?>
<input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
<input type="hidden" name="a" value="accepted">
<input type="hidden" name="title" id="title" value="accepted title"/>
<input type="hidden" name="note" value="accepted internal notes" >
<input type="hidden" name="complaint_status" value="7" >
<input type="submit" class="btn btn-large" value="Accept" />
</form></div>
</li>
</ul>
</div>
</div>
</div>

<div class="span6">
<div class="block-fluid ucard">
<div class="info">
<ul class="rows">
<li class="heading">Do you want to <b>deny</b> this Complaint?</li>
<form action="tickets.php?id=<?php echo $ticket->getId(); ?>"  method="post">
<?php csrf_token(); ?>
<input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
<input type="hidden" name="a" value="deny">
<input type="hidden" name="complaint_status" value="7" >
<input type="hidden" name="title" id="title" value="Complaint is Denied"/>
<li>
<div class="title">Details:</div>
<div class="text"><textarea name="note"></textarea></div>
</li>
<li>
<div class="title"></div>
<div class="text"><input type="submit" class="btn btn-large  btn-warning" value="Deny" /></div>
</li>
</form>
</ul>
</div>
</div>
</div>

</div>
<?php }
else{ ?>

<?php if($thisstaff->canPostReply()) { ?>

<div class="row-fluid">
<div class="span12">
<div class="head clearfix">
    <div class="isw-list"></div>
    <h1>Complaints Actions</h1>
</div>
<div class="block-fluid tabs">
<ul>
<?php if($thisstaff->canTransferTickets() && $thisstaff->isAdmin() ) { ?>  
<li><a id="transfer_tab" href="#transfer">Dept. Transfer</a></li>
<?php }else{?>
<?php if($thisstaff->canTransferTickets() && $thisstaff->checksubdepart($staff->getDeptId()) && $thisstaff->isFocalPerson() ){ ?>
<li><a id="sub_dept_transfer_tab" href="#sub_dept_transfer">Dept. Transfer</a></li>
<?php }?>

<?php if( $thisstaff->getId()=='129' ) { ?>  
<li><a id="transfer_tab" href="#transfer">Dept. Transfer</a></li>
<?php } ?>
<?php if($ticket->checkcategory($ticket->getExtId(),$ticket->getDeptId(),$ticket->getTopicId())){ ?>

<?php if($thisstaff->canAssignTickets()) { ?>
<li><a id="assign_tab" href="#assign"><?php echo $ticket->isAssigned()?'Reassign Complaint':'Assign Complaint'; ?></a></li>
<?php  } ?>
<?php if($thisstaff->canPostReply()) { ?>
<li><a id="category_tab" href="#category">Set Category</a></li>
<li><a id="reply_tab" href="#reply">Reply To User</a></li>
<li><a id="note_tab" href="#note">Update Activity</a></li>
<?php } ?>

<?php }else{ ?>
<li><a id="category_tab" href="#category">Set Category</a></li>
<?php  } ?>
<?php }?>
</ul>

<!--Transfer Ticket-->
<?php if($thisstaff->canTransferTickets() && $thisstaff->isAdmin() ) { 
?>  
<div id="transfer">
 <form id="transfer" action="tickets.php?id=<?php echo $ticket->getId(); ?>#transfer" name="transfer" method="post" enctype="multipart/form-data">
        <?php csrf_token(); ?>
        <input type="hidden" name="ticket_id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="a" value="transfer">
        <div class="block-fluid" style="margin-bottom:0px;">        
            <?php
            if($errors['transfer']) {
				?>
                <div class="row-form clearfix">
<div class="span3"></div>
<div class="span9">
<?php echo $errors['transfer']; ?>
</div>
</div>
            
            <?php
            } ?>
            <div class="row-form clearfix">
            <div class="span3">Department:</div>
            <div class="span9">  Ticket is currently in <b><?php echo $ticket->getDeptName(); ?></b> department
            <?php echo $errors['transfer']; ?>
            <select id="deptId" name="deptId" onchange="get_focal_person(this.value);">
            <option value="0" selected="selected">Select Target Department</option>
            <?php
            if($depts=Dept::getDepartments()) {
            foreach($depts as $id =>$name) {
            if($id==$ticket->getDeptId()) continue;
            echo sprintf('<option value="%d" %s>%s</option>',
            $id, ($info['deptId']==$id)?'selected="selected"':'',$name);
            }
            }
            ?>
            </select>&nbsp;<span class='error'>*&nbsp;<?php echo $errors['deptId']; ?></span>
            <input type="hidden" name="assignId" id="assignId_transfer" value="" />
            <input type="hidden" name="complaint_status" value="7" >
            </div>
            </div>
        <div class="row-form clearfix">
        <div class="span3">Comments:</div>
        <div class="span9"><textarea name="transfer_comments" id="transfer_comments"
        placeholder="Enter reasons for the transfer"
        class="richtext ifhtml no-bar" cols="80" rows="7" wrap="soft"><?php
        echo $info['transfer_comments']; ?></textarea>  
        <span class="error"><?php echo $errors['transfer_comments']; ?></span></div>
        </div>    
        </table>

        <div class="footer tar" style="margin-top: 0px;">
           <input class="btn" type="submit" value="Transfer" id="submit_btn">
           <input class="btn" type="reset" value="Reset">
</div>            
</div>
    </form>
    </div>
<?php }else{?> 
<!--Sub Dept Transfer-->
<?php if($thisstaff->canTransferTickets() && $thisstaff->checksubdepart($staff->getDeptId())  && $thisstaff->isFocalPerson()){ ?>
<div id="sub_dept_transfer">
 <form id="sub_dept_transfer" action="tickets.php?id=<?php echo $ticket->getId(); ?>#sub_dept_transfer" name="sub_dept_transfer" method="post" enctype="multipart/form-data">
        <?php csrf_token(); ?>
        <input type="hidden" name="ticket_id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="a" value="transfer">
        <div class="block-fluid" style="margin-bottom:0px;">        
            <?php
            if($errors['transfer']) {
				?>
                <div class="row-form clearfix">
<div class="span3"></div>
<div class="span9">
<?php echo $errors['transfer']; ?>
</div>
</div>
            <?php
            } ?>
            <div class="row-form clearfix">
            <div class="span3">Department:</div>
            <div class="span9">  Ticket is currently in <b><?php echo $ticket->getDeptName(); ?></b> department
            <?php echo $errors['transfer']; ?>
            <select id="deptId" name="deptId" onchange="get_focal_person(this.value);">
            <option value="0" selected="selected">Select Target Department</option>
            <?php
            if($depts=Dept::getSubCategory(true,$staff->getDeptId())) {
            foreach($depts as $id =>$name) {
            if($id==$ticket->getDeptId()) continue;
            echo sprintf('<option value="%d" %s>%s</option>',
            $id, ($info['deptId']==$id)?'selected="selected"':'',$name);
            }
            }
            ?>
            </select>&nbsp;<span class='error'>*&nbsp;<?php echo $errors['deptId']; ?></span>
            <input type="hidden" name="assignId" id="assignId_transfer" value="" />
            <input type="hidden" name="complaint_status" value="7" >
            </div>
            </div>
        <div class="row-form clearfix">
        <div class="span3">Comments:</div>
        <div class="span9"><textarea name="transfer_comments" id="transfer_comments"
        placeholder="Enter reasons for the transfer"
        class="richtext ifhtml no-bar" cols="80" rows="7" wrap="soft"><?php
        echo $info['transfer_comments']; ?></textarea>  
        <span class="error"><?php echo $errors['transfer_comments']; ?></span></div>
        </div>    
        </table>

        <div class="footer tar" style="margin-top: 0px;">
           <input class="btn" type="submit" value="Transfer" id="submit_btn">
           <input class="btn" type="reset" value="Reset">
</div>            
</div>
    </form>
    </div>
    <?php }?>
<!--Assign Complaint--> 
<?php if( $thisstaff->getId()=='129' ) { ?>
<div id="transfer">
 <form id="transfer" action="tickets.php?id=<?php echo $ticket->getId(); ?>#transfer" name="transfer" method="post" enctype="multipart/form-data">
        <?php csrf_token(); ?>
        <input type="hidden" name="ticket_id" value="<?php echo $ticket->getId(); ?>">
        <input type="hidden" name="a" value="transfer">
        <div class="block-fluid" style="margin-bottom:0px;">        
            <?php
            if($errors['transfer']) {
				?>
                <div class="row-form clearfix">
<div class="span3"></div>
<div class="span9">
<?php echo $errors['transfer']; ?>
</div>
</div>
            
            <?php
            } ?>
            <div class="row-form clearfix">
            <div class="span3">Department:</div>
            <div class="span9">  Ticket is currently in <b><?php echo $ticket->getDeptName(); ?></b> department
            <?php echo $errors['transfer']; ?>
            <select id="deptId" name="deptId" onchange="get_focal_person(this.value);">
            <option value="0" selected="selected">Select Target Department</option>
            <?php
            if($depts=Dept::getDepartments()) {
            foreach($depts as $id =>$name) {
            if($id==$ticket->getDeptId()) continue;
            echo sprintf('<option value="%d" %s>%s</option>',
            $id, ($info['deptId']==$id)?'selected="selected"':'',$name);
            }
            }
            ?>
            </select>&nbsp;<span class='error'>*&nbsp;<?php echo $errors['deptId']; ?></span>
            <input type="hidden" name="assignId" id="assignId_transfer" value="" />
            <input type="hidden" name="complaint_status" value="7" >
            </div>
            </div>
        <div class="row-form clearfix">
        <div class="span3">Comments:</div>
        <div class="span9"><textarea name="transfer_comments" id="transfer_comments"
        placeholder="Enter reasons for the transfer"
        class="richtext ifhtml no-bar" cols="80" rows="7" wrap="soft"><?php
        echo $info['transfer_comments']; ?></textarea>  
        <span class="error"><?php echo $errors['transfer_comments']; ?></span></div>
        </div>    
        </table>

        <div class="footer tar" style="margin-top: 0px;">
           <input class="btn" type="submit" value="Transfer" id="submit_btn">
           <input class="btn" type="reset" value="Reset">
</div>            
</div>
    </form>
    </div>
<?php } ?>   

<?php if($ticket->checkcategory($ticket->getExtId(),$ticket->getDeptId(),$ticket->getTopicId())){ ?>

<?php if($thisstaff->canAssignTickets()) { ?><div id="assign">
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
{
if(($users=Staff::getSubPOC($staff->getDeptId()))) {
echo '<OPTGROUP label="Sub POC ('.count($users).')">';
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
if($users=Staff::getDefaultPOC()) {
echo '<OPTGROUP label="Service Desk POC ('.count($users).')">';
$staffId=$ticket->isAssigned()?$ticket->getStaffId():0;
foreach($users as $id => $name) {
if($staffId && $staffId==$id)
continue;

$k="s$id";
echo sprintf('<option value="%s" %s>%s</option>',$k,(($info['assignId']==$k)?'selected="selected"':''),$name);
}
echo '</OPTGROUP>';
}
}
else{
if(($users=Staff::getPOC($staff->getDeptId()))) {
echo '<OPTGROUP label="POC ('.count($users).')">';
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
//query for department check
$sql_sdms_department="Select * from sdms_department where dept_id='".$ticket->getDeptId()."' ORDER By dept_id limit 0,1";
$res_sdms_department=mysql_query($sql_sdms_department);
$row_sdms_department=mysql_fetch_array($res_sdms_department);
if($row_sdms_department['islocation']=='1'){
$sql_sdms_staff="Select * from sdms_staff where staff_id='".$ticket->getStaffId()."' ORDER By staff_id limit 0,1";
$res_sdms_staff=mysql_query($sql_sdms_staff);
$row_sdms_staff=mysql_fetch_array($res_sdms_staff);
if(($users=Staff::getRegionUser($ticket->getDeptId(),$ticket->getStaffId(),$row_sdms_staff['region_id']))) {
echo '<OPTGROUP label="Region User ('.count($users).')">';
$staffId=$ticket->isAssigned()?$ticket->getStaffId():0;
foreach($users as $id => $name) {
if($staffId && $staffId==$id)
continue;

$k="s$id";
echo sprintf('<option value="%s" %s>%s</option>',$k,(($info['assignId']==$k)?'selected="selected"':''),$name);
}
echo '</OPTGROUP>';
}		

}
/*	if($users=Staff::getDefaultPOC()) {
echo '<OPTGROUP label="SDMS POC ('.count($users).')">';
$staffId=$ticket->isAssigned()?$ticket->getStaffId():0;
foreach($users as $id => $name) {
if($staffId && $staffId==$id)
continue;

$k="s$id";
echo sprintf('<option value="%s" %s>%s</option>',$k,(($info['assignId']==$k)?'selected="selected"':''),$name);
}
echo '</OPTGROUP>';
}*/
}

/*	if(($users=Staff::getAdminMembers())) {
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
}*/
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
<input type="hidden" value="<?php echo $ticket->complaint_status_title(); ?>" name="complaint_status" >
<?php /*?><div class="row-form clearfix">
<div class="span3">Complaint Status:</div>
<div class="span9">
<select name="status_pid" style="width:300px;" onChange="get_Sub_Status(this.value)" required>
<option value="">--Select Status--</option>
<?php
$sql_get_child_status='SELECT *  FROM sdms_status status WHERE status_id = '.$ticket->complaint_status_title().' ';
$res_get_child_status=db_query($sql_get_child_status);
$row_get_child_status = db_fetch_array($res_get_child_status);

if($status=Status::getParentStatus()) {
foreach($status as $id =>$name) {
echo sprintf('<option value="%d" %s>%s</option>',$id, ($row_get_child_status['p_id']==$id)?'selected="selected"':'',$name);
}
}
?>
</select>
&nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['complaint_status_title']; ?></font></div>
</div>   
<div class="row-form clearfix" id="show_sub_status">
<div class="span3">Sub Status:</div>
<div class="span9">
<select name="complaint_status" style="width:300px;" onChange="get_Sub_Status(this.value)" required>
<option value="">--Select Status--</option>
<?php 
$sql_get_child_statuses='SELECT *  FROM sdms_status status WHERE p_id = '.$row_get_child_status['p_id'].' ';
$res_get_child_statuses=db_query($sql_get_child_statuses);
while($row_get_child_statuses = db_fetch_array($res_get_child_statuses)){?>
<option value="<?php echo $row_get_child_statuses['status_id'] ?>" <?php if($ticket->complaint_status_title()==$row_get_child_statuses['status_id']){?> selected <?php } ?>><?php echo $row_get_child_statuses['status_title'] ?></option>
<?php	
}
?>
</select>
&nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['complaint_status_title']; ?></font></div>       
</div>      <?php */?> 
   
<div class="footer tar" style="margin-top: 0px;">
<input type="submit" class="btn" value="Submit" id="submit_btn" />
<input type="reset" class="btn" value="Reset" />
</div>                            
</div>
</form>
</div><?php } ?>   
<?php if($thisstaff->canPostReply()) { ?>
<!--Category Summary-->   
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
<div class="row-form clearfix"   id="1_tier">
<div class="span3">Category:</div>
<div class="span9">
<select onChange="get_subcategory(this.value,this.id);" id="select_1">
<option value="">--Select Category--</option>
<?php
if($topics=Topic::getPublicHelpTopics($ticket->getDeptId(),'0')) {
foreach($topics as $id =>$name) {
echo sprintf('<option value="%d" %s>%s</option>',
    $id, ($info['topicId']==$id)?'selected="selected"':'', $name);
}
}?>

</select></div>
</div> 
<input type="hidden" value="1" name="tiers" id="tiers">
<div id="2_tires"></div>
<div id="3_tires"></div>
<div id="4_tires"></div>

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
<input type="submit" class="btn" value="Submit" id="submit_btn" />
<input type="reset" class="btn" value="Reset" />
</div>                            
</div>
       </form>
</div> 
<!--Update Activity-->
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
<div class="span9"><input type="text" placeholder="Note title..." name="title" id="title" value="<?php echo $info['title']; ?>" required/></div>
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
<div class="span9">
<select name="status_pid" style="width:300px;" onChange="get_Sub_Status_note(this.value)" required>
<option value="">--Select Status--</option>
<?php
$sql_get_child_status='SELECT *  FROM sdms_status status WHERE status_id = '.$ticket->complaint_status_title().' ';
$res_get_child_status=db_query($sql_get_child_status);
$row_get_child_status = db_fetch_array($res_get_child_status);

if($status=Status::getParentStatus()) {
foreach($status as $id =>$name) {
echo sprintf('<option value="%d" %s>%s</option>',$id, ($row_get_child_status['p_id']==$id)?'selected="selected"':'',$name);
}
}
?>
</select>
&nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['complaint_status_title']; ?></font></div>
</div>   
<div class="row-form clearfix" id="show_sub_status_note">
<div class="span3">Sub Status:</div>
<div class="span9">
<select name="complaint_status" style="width:300px;" onChange="get_Sub_Status(this.value)" required>
<option value="">--Select Status--</option>
<?php 
$sql_get_child_statuses='SELECT *  FROM sdms_status status WHERE p_id = '.$row_get_child_status['p_id'].' ';
$res_get_child_statuses=db_query($sql_get_child_statuses);
while($row_get_child_statuses = db_fetch_array($res_get_child_statuses)){?>
<option value="<?php echo $row_get_child_statuses['status_id'] ?>" <?php if($ticket->complaint_status_title()==$row_get_child_statuses['status_id']){?> selected <?php } ?>><?php echo $row_get_child_statuses['status_title'] ?></option>
<?php	
}
?>
</select>
&nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['complaint_status_title']; ?></font></div>       
</div>
       
<div class="footer tar" style="margin-top: 0px;">
<img src="img/loaders/loader.gif" title="loader.gif" style="display:none;" id="loading_status" >
<input type="submit" class="btn" value="Submit" id="activity_update" />
<input type="reset" class="btn" value="Reset" />
</div>                            
</div>
</form>
</div>
<!--Customer Reply-->
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
<input type='hidden' value='1' name="emailreply" id="remailreply"
<?php echo ((!$info['emailreply'] && !$errors) || isset($info['emailreply']))?'checked="checked"':''; ?>> <!--Email Reply--></div>
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
<label style="display:inline;"><input type='hidden' value='1' name="append" id="append" checked="checked"> <!--Append--></label>
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
<input type="hidden" value="<?php echo $ticket->complaint_status_title(); ?>" name="complaint_status" >
<?php /*?><div class="row-form clearfix">
<div class="span3">Complaint Status:</div>
<div class="span9">
<select name="status_pid" style="width:300px;" onChange="get_Sub_Status_reply(this.value)">
<option value="">--Select Status--</option>
<?php
$sql_get_child_status='SELECT *  FROM sdms_status status WHERE status_id = '.$ticket->complaint_status_title().' ';
$res_get_child_status=db_query($sql_get_child_status);
$row_get_child_status = db_fetch_array($res_get_child_status);

if($status=Status::getParentStatus()) {
foreach($status as $id =>$name) {
echo sprintf('<option value="%d" %s>%s</option>',$id, ($row_get_child_status['p_id']==$id)?'selected="selected"':'',$name);
}
}
?>
</select>
&nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['complaint_status_title']; ?></font></div>
</div>   
<div class="row-form clearfix" id="show_sub_status_reply">
<div class="span3">Sub Status:</div>
<div class="span9">
<select name="complaint_status" style="width:300px;" onChange="get_Sub_Status(this.value)">
<option value="">--Select Status--</option>
<?php 
$sql_get_child_statuses='SELECT *  FROM sdms_status status WHERE p_id = '.$row_get_child_status['p_id'].' ';
$res_get_child_statuses=db_query($sql_get_child_statuses);
while($row_get_child_statuses = db_fetch_array($res_get_child_statuses)){?>
<option value="<?php echo $row_get_child_statuses['status_id'] ?>" <?php if($ticket->complaint_status_title()==$row_get_child_statuses['status_id']){?> selected <?php } ?>><?php echo $row_get_child_statuses['status_title'] ?></option>
<?php	
}
?>
</select>
&nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['complaint_status_title']; ?></font></div>       
</div>   <?php */?>    

<div class="footer tar" style="margin-top: 0px;">
<input type="submit" class="btn" value="Submit"  onclick="return confirm('Are you sure to submit or send?')" id="submit_btn" />
<input type="reset" class="btn" value="Reset" />
</div>            
</div>
</form>   
</div>
<?php  } ?>

<?php }else{ ?>
<!--Category Summary-->   
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
<div class="row-form clearfix"   id="1_tier">
<div class="span3">Category:</div>
<div class="span9">
<select onChange="get_subcategory(this.value,this.id);" id="select_1">
<option value="">--Select Category--</option>
<?php
if($topics=Topic::getPublicHelpTopics($ticket->getDeptId(),'0')) {
foreach($topics as $id =>$name) {
echo sprintf('<option value="%d" %s>%s</option>',
    $id, ($info['topicId']==$id)?'selected="selected"':'', $name);
}
}?>

</select></div>
</div> 
<input type="hidden" value="1" name="tiers" id="tiers">
<div id="2_tires"></div>
<div id="3_tires"></div>
<div id="4_tires"></div>

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
<input type="submit" class="btn" value="Submit" id="submit_btn" />
<input type="reset" class="btn" value="Reset" />
</div>                            
</div>
       </form>
</div> 
<?php  } ?>

<?php  }?>
</div>
</div>
</div>     
<?php 
//end the postreply
} ?>
<?php }?>
<?php }
elseif($thisstaff->getGroupId()=='7' && $ticket->getStatus() != 'closed'){ ?>
<div class="row-fluid">
<div class="span12">
<div class="head clearfix">
    <div class="isw-list"></div>
    <h1>Complaints Actions</h1>
</div>
<div class="block-fluid tabs">
<ul>
<li><a id="reply_tab" href="#reply">Reply To User</a></li>
<li><a id="note_tab" href="#note">Update Activity</a></li>
</ul>
<!--Customer Reply-->
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
<input type='hidden' value='1' name="emailreply" id="remailreply"
<?php echo ((!$info['emailreply'] && !$errors) || isset($info['emailreply']))?'checked="checked"':''; ?>> <!--Email Reply--></div>
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
<label style="display:inline;"><input type='hidden' value='1' name="append" id="append" checked="checked"> <!--Append--></label>
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
<input type="hidden" value="<?php echo $ticket->complaint_status_title(); ?>" name="complaint_status" >
<?php /*?><div class="row-form clearfix">
<div class="span3">Complaint Status:</div>
<div class="span9">
<select name="status_pid" style="width:300px;" onChange="get_Sub_Status_reply(this.value)">
<option value="">--Select Status--</option>
<?php
$sql_get_child_status='SELECT *  FROM sdms_status status WHERE status_id = '.$ticket->complaint_status_title().' ';
$res_get_child_status=db_query($sql_get_child_status);
$row_get_child_status = db_fetch_array($res_get_child_status);

if($status=Status::getParentStatus()) {
foreach($status as $id =>$name) {
echo sprintf('<option value="%d" %s>%s</option>',$id, ($row_get_child_status['p_id']==$id)?'selected="selected"':'',$name);
}
}
?>
</select>
&nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['complaint_status_title']; ?></font></div>
</div>   
<div class="row-form clearfix" id="show_sub_status_reply">
<div class="span3">Sub Status:</div>
<div class="span9">
<select name="complaint_status" style="width:300px;" onChange="get_Sub_Status(this.value)">
<option value="">--Select Status--</option>
<?php 
$sql_get_child_statuses='SELECT *  FROM sdms_status status WHERE p_id = '.$row_get_child_status['p_id'].' ';
$res_get_child_statuses=db_query($sql_get_child_statuses);
while($row_get_child_statuses = db_fetch_array($res_get_child_statuses)){?>
<option value="<?php echo $row_get_child_statuses['status_id'] ?>" <?php if($ticket->complaint_status_title()==$row_get_child_statuses['status_id']){?> selected <?php } ?>><?php echo $row_get_child_statuses['status_title'] ?></option>
<?php	
}
?>
</select>
&nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['complaint_status_title']; ?></font></div>       
</div>   <?php */?>    

<div class="footer tar" style="margin-top: 0px;">
<input type="submit" class="btn" value="Submit" id="submit_btn" />
<input type="reset" class="btn" value="Reset" />
</div>            
</div>
</form>   
</div>
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
<div class="span9"><input type="text" placeholder="Note title..." name="title" id="title" value="<?php echo $info['title']; ?>" required/></div>
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
<div class="span9">
<select name="status_pid" style="width:300px;" onChange="get_Sub_Status_note(this.value)" required>
<option value="">--Select Status--</option>
<?php
$sql_get_child_status='SELECT *  FROM sdms_status status WHERE status_id = '.$ticket->complaint_status_title().' ';
$res_get_child_status=db_query($sql_get_child_status);
$row_get_child_status = db_fetch_array($res_get_child_status);

if($status=Status::getParentStatus()) {
foreach($status as $id =>$name) {
echo sprintf('<option value="%d" %s>%s</option>',$id, ($row_get_child_status['p_id']==$id)?'selected="selected"':'',$name);
}
}
?>
</select>
&nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['complaint_status_title']; ?></font></div>
</div>   
<div class="row-form clearfix" id="show_sub_status_note">
<div class="span3">Sub Status:</div>
<div class="span9">
<select name="complaint_status" style="width:300px;" required>
<option value="">--Select Status--</option>
<?php 
$sql_get_child_statuses='SELECT *  FROM sdms_status status WHERE p_id = '.$row_get_child_status['p_id'].' ';
$res_get_child_statuses=db_query($sql_get_child_statuses);
while($row_get_child_statuses = db_fetch_array($res_get_child_statuses)){?>
<option value="<?php echo $row_get_child_statuses['status_id'] ?>" <?php if($ticket->complaint_status_title()==$row_get_child_statuses['status_id']){?> selected <?php } ?>><?php echo $row_get_child_statuses['status_title'] ?></option>
<?php	
}
?>
</select>
&nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['complaint_status_title']; ?></font></div>       
</div>
       
<div class="footer tar" style="margin-top: 0px;">
<img src="img/loaders/loader.gif" title="loader.gif" style="display:none;" id="loading_status" >
<input type="submit" class="btn" value="Submit" id="activity_update" />
<input type="reset" class="btn" value="Reset" />
</div>                            
</div>
</form>
</div>
</div>
</div>
</div>
<?php }
elseif($ticket->getStatus() == 'closed'){

$sql_feedback="Select * from sdms_feedback where complainant_id='".$ticket->getId()."' ORDER By feedback_id limit 0,1";
$res_feedback=mysql_query($sql_feedback);
$num_feedback=mysql_num_rows($res_feedback);
if($num_feedback > 0){
$row_feedback=mysql_fetch_array($res_feedback);
?>
<div class="row-fluid">
<div class="span12">
        <div class="block-fluid ucard">
                        <div class="info">                                                                
                            <ul class="rows">
                            <li class="heading">Feedback Detils created on  <?php echo  date('d M Y',strtotime($row_feedback['date']));?>
                            </li>
                            <li>
                            <div class="title">First Name:</div>
                            <div class="text"><?php 
                            if($row_feedback['fname']!='')
                            echo $row_feedback['fname'];
                            else
                            echo 'Null';?></div>
                            </li>
                            <li>
                            <div class="title">Last Name:</div>
                            <div class="text"><?php 
                            if($row_feedback['lname']!='')
                            echo $row_feedback['lname'];
                            else
                            echo 'Null';?></div>
                            </li>
                            <li>
                            <div class="title">Address:</div>
                            <div class="text"><?php 
                            if($row_feedback['address']!='')
                            echo $row_feedback['address'];
                            else
                            echo 'Null';?></div>
                            </li>
                            <li>
                            <div class="title">Contact Number :</div>
                            <div class="text"><?php 
                            if($row_feedback['contact']!='')
                            echo $row_feedback['contact'];
                            else
                            echo 'Null';?></div>
                            </li>
                            <li>
                            <div class="title">Complaint Action:</div>
                            <div class="text"><?php 
                            if($row_feedback['complaint']=='1')
                            echo 'Complaint resolved';
                            elseif($row_feedback['complaint']=='2')
                            echo 'Complaint not resolved';
                            else
							echo 'Null';?></div>
                            </li>
                            <li style="height:35px;">
                            <div class="title">Complaint Experiance:</div>
                            <div class="text"><?php 
                            if($row_feedback['experiance']=='1')
                            echo 'Dissatisfied';
                            elseif($row_feedback['experiance']=='2')
                            echo 'Neutral';
                            elseif($row_feedback['experiance']=='3')
                            echo 'Satisfied';
                            else
                            echo 'Null';?></div>
                            </li>
                            <li>
                            <div class="title">Comments:</div>
                            <div class="text"><?php 
                            if($row_feedback['massage']!='')
                            echo $row_feedback['massage'];
                            else
                            echo 'Null';?></div>
                            </li>
                            </ul>                                                      
                        </div>                        
                </div>
    </div>
</div>    
<div class="dr"><span></span></div> 


<?php } }?>  

 
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