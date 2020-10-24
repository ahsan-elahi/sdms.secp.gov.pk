<?php
//Note that ticket obj is initiated in queries.php.
if(!defined('OSTSCPINC') || !$thisstaff || !is_object($query) || !$query->getId()) die('Invalid path');



//Make sure the staff is allowed to access the page.
if($_REQUEST['action']=='subdeptview')
$_REQUEST['id'] = $_REQUEST['subdeptviewid'];
elseif(!@$thisstaff->isStaff() || !$query->checkStaffAccess($thisstaff)) 
die('Access Denied');

//Re-use the post info on error...savekeyboards.org (Why keyboard? -> some people care about objects than users!!)
$info=($_POST && $errors)?Format::input($_POST):array();

//Auto-lock the ticket if locking is enabled.. If already locked by the user then it simply renews.
if($cfg->getLockTime() && !$query->acquireLock($thisstaff->getId(),$cfg->getLockTime()))
    $warn.='Unable to obtain a lock on the ticket';

//Get the goodies.
$dept  = $query->getDept();  //Dept
$staff = $query->getStaff(); //Assigned or closed by..
$team  = $query->getTeam();  //Assigned team.
$sla   = $query->getSLA();
$lock  = $query->getLock();  //Query lock obj
$id    = $query->getId();    //Query ID.

//Useful warnings and errors the user might want to know!
if($query->isAssigned() && (
            ($staff && $staff->getId()!=$thisstaff->getId())
         || ($team && !$team->hasMember($thisstaff))
        ))
    $warn.='&nbsp;&nbsp;<span class="Icon assignedTicket">Query is assigned to '.implode('/', $query->getAssignees()).'</span>';
if(!$errors['err'] && ($lock && $lock->getStaffId()!=$thisstaff->getId()))
    $errors['err']='This Query is currently locked by '.$lock->getStaffName();
if(!$errors['err'] && ($emailBanned=TicketFilter::isBanned($query->getEmail())))
    $errors['err']='Email is in banlist! Must be removed before any reply/response';

$unbannable=($emailBanned) ? BanList::includes($query->getEmail()) : false;

if($query->isOverdue())
    $warn.='&nbsp;&nbsp;<span class="Icon overdueQuery">Marked overdue!</span>';

?>
<?php 
if(isset($msg) && $msg=='Query created successfully'){
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

	$query_id=$query->getExtId();
if($extion_id!='')
	{	
	?>
	$.ajax({
				url: 'get_voice_name.php',
				data: {
					staff_ext:<?php echo $extion_id; ?>,
					ticket_id:<?php echo $query->getExtId(); ?>
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
	data: "cat_id="+cat_id+"&next_tiers="+next_tiers+"&isnature=1",
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
	$("#submit_btn").attr('disabled',true);
	$("#loading_status").css('display', '');
	$.ajax({
	url:"get_focal_person.php",
	data: "dept_id="+dept_id,
	success: function(msg){
	$("#assignId_transfer").val(msg.trim());
	$("#submit_btn").attr('disabled',false);	
	$("#loading_status").css('display', 'none');
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
<div class="row-fluid">
<div class="span6">
<div class="page-header" style="padding-top:20px;" >
    <a href="queries.php?id=<?php echo $query->getId(); ?>" title="Reload"><h1>Query <small>#
	<span class="label label-inverse" style="font-size: 25.844px;line-height: 33px;"><?php echo $query->getExtId(); ?></span> Details</small></h1></a>
</div>
</div>
<?php 
if($new_query=='Query created successfully' && $query->getSource()=='Through Call'){?>
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
        <?php if($thisstaff->isAdmin() || $thisstaff->getGroupId()==7) { ?>
         <a class="action-button" href="queries.php?query_id=<?php echo $query->getId(); ?>&a=opencomplaint">
         <button class="btn btn-info" type="button"><i class="icon-edit"></i>Convert to Complaint</button></a>   
        <?php } ?>
		<?php if($thisstaff->canEditTickets()) { ?>
        <a class="action-button" href="queries.php?id=<?php echo $query->getId(); ?>&a=edit">
        <button class="btn btn-info" type="button"><i class="icon-edit"></i>Edit</button></a>
        <?php } ?>
       
        <?php if($query->isOpen() && !$query->isAssigned() && $thisstaff->canAssignTickets()) {?>
         <a id="popup_action_claim" >
        <button class="btn btn-success" type="button" id="ticket-claim"><i class="icon-user"></i> Claim</button></a>                                
        <?php } ?>
        <?php if($thisstaff->canDeleteTickets()) { ?>
        <a id="popup_action_del" >
        <button class="btn btn-danger" type="button"><i class="icon-trash"></i>Delete</button></a>
        <?php }?> 
        <?php if($thisstaff->canCloseTickets()) {
        if($query->isOpen()) {?>
       <!-- <a class="action-button" >
        <button class="btn btn-inverse" id="btn_close" type="button"><i class="icon-remove-circle"></i>Close</button></a>     -->            
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
                    if($query->isOpen() && ($dept && $dept->isManager($thisstaff))) {
                    if($query->isAssigned()) { ?>
                    <li><a id="popup_unassigned" href="#release"><i class="icon-user"></i> Release (unassign) Query</a></li>
                    <?php
                    }
                    
                    if(!$query->isOverdue()) { ?>
                    <li><a id="popup_overdue" href="#overdue"><i class="icon-bell"></i> Mark as Overdue</a></li>
                    <?php
                    }
                    
                    if($query->isAnswered()) { ?>
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
                    <li><a id="popup_banemail" href="#banemail"><i class="icon-ban-circle"></i> Ban Email (<?php echo $query->getEmail(); ?>)</a></li>
                    <?php 
                    } elseif($unbannable) { ?>
                    <li><a id="popup_banemail" href="#unbanemail"><i class="icon-undo"></i> Unban Email (<?php echo $query->getEmail(); ?>)</a></li>
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
    echo $query->getName_Title().' '.Format::htmlchars($query->getName());
    ?></div>
    </li>
    <li>
    <div class="title">CNIC/NICOP/PSPT:</div>
    <div class="text"><?php
    if($query->getNic()!='')
    {
     echo Format::htmlchars($query->getNic());
    echo sprintf('&nbsp;&nbsp;<a href="queries.php?a=search&query=%s" title="Related Query" data-dropdown="#action-dropdown-stats">(<b>%d</b>)</a>',urlencode($query->getNic()), $query->getTotalTickets($query->getNic()));
    }
    else echo 'Null'; ?></div>
    </li>
    <li>
    <div class="title">Email:</div>
    <div class="text">
    <?php
    if ($query->getEmail()=='')
    echo "Null";
    echo $query->getEmail();?></div>
    </li>
    <li>
    <div class="title">Mobile:</div>
    <div class="text"><?php 
    if( $query->getPhoneNumber()!='')
    echo $query->getPhoneNumber();
    else echo 'Null'; ?></div>
    </li>
    <li>
    <div class="title">Country:</div>
    <div class="text"><?php 
    if($query->getDistrict()!='')
    echo $query->getDistrict();
    else
    echo 'Null'; ?></div>
    </li>
    <li>
    <div class="title">Province:</div>
    <div class="text"><?php 
    if($query->getTehsil())
    echo $query->getTehsil();
    else
    echo 'Null'; ?></div>
    </li> 
    <li>
    <div class="title">City:</div>
    <div class="text"><?php 
    if($query->getAgencyTehsilTitle())
    echo $query->getAgencyTehsilTitle();
    else
    echo 'Null'; ?></div>
    </li>   
    <li>
    <div class="title">Address:</div>
    <div class="text"><?php 
    if($query->getApplicant_Address()!='')
    echo $query->getApplicant_Address();
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
                                <li class="heading">Query Info</li>
                                <li>
                                    <div class="title">Status:</div>
                                    <div class="text"><?php echo $query->complaint_status(); ?></div>
                                </li>
                                <li>
                                    <div class="title">Priority:</div>
                                    <div class="text"><?php 
    if($query->getPriority())
    echo $query->getPriority();
    else
    echo 'Null'; ?></div>
                                </li>
                                
                                <li>
                                    <div class="title">Agaisnt Dept:</div>
                                    <div class="text"><?php echo Format::htmlchars($query->getDeptName()); ?></div>
                                </li>
                                <li>
                                    <div class="title">Lodged Date/Time:</div>
                                    <div class="text"><?php echo Format::db_datetime($query->getCreateDate()); ?></div>
                                </li>
                                <?php if($query->getReopenDate()!=''){ ?>
                                <li>
                                    <div class="title">ReOpen Date/Time:</div>
                                      <div class="text"><?php echo Format::db_datetime($query->getReopenDate()); ?></div>
                                </li>
                                <?php } ?>
                                <?php /*?><li>
                                    <div class="title">Due Date:</div>
                                    <div class="text">
                                    <?php echo $sla?Format::htmlchars($sla->getName()):'<span class="faded">&mdash; none &mdash;</span>'; ?></div>
                                </li><?php */?>
                                <?php if($query->isOpen()) { ?>
                                <li>
                                <div class="title">Assigned To:</div>
                                <div class="text"> 
                                <?php
                                if($query->isAssigned())
                                echo Format::htmlchars(implode('/', $query->getAssignees()));
                                else
                                echo '<span class="faded">&mdash; Unassigned &mdash;</span>';
                                ?></div>
                                </li>
                                <?php } else { ?>
                                <li>
                                <div class="title">Closed By:</div>
                                <div class="text"><?php
                                if(($staff = $query->getStaff()))
                                echo Format::htmlchars($staff->getName());
                                else
                                echo '<span class="faded">&mdash; Unknown &mdash;</span>';
                  ?></div>
                                </li> 
                                <?php } ?> 
                                <?php if($thisstaff->canDeleteTickets()) { ?>
                                <?php
                                if($query->isOpen()){ ?>
                                <?php /*?><li>
                                    <div class="title">Due Date:</div>
                                    <div class="text"><?php echo Format::db_datetime($query->getEstDueDate()); ?></div>
                                </li><?php */?>
                                <?php
                                }else { ?>
                                <li>
                                    <div class="title">Close Date:</div>
                                    <div class="text">
                                    <?php echo Format::db_datetime($query->getCloseDate()); ?></div>
                                </li>
                                <?php }  ?> 
                                
                                
                                 <?php }?>  
                                  
                                 <li>
                                    <div class="title">Query Category:</div>
                                    <div class="text"><?php  
                                    $sql_get_topics='SELECT ht.topic_id, ht.topic as name '
                                    .' FROM '.TOPIC_TABLE. ' ht '
                                    .' WHERE ht.topic_id="'.$query->getTopicId().'" ';
                                    $res_get_topic=mysql_query($sql_get_topics);
                                    $row_topic=mysql_fetch_array($res_get_topic);
                                    echo $row_topic['name'];
                                    ?>&nbsp;</div>
                                </li>
                                  <li>
                                    <div class="title">Source:</div>
                                    <div class="text">
                                    <?php echo Format::htmlchars($query->getSource());
                                    if($query->getIP())
                                    echo '&nbsp;&nbsp; <span class="faded">('.$query->getIP().')</span>'; ?></div>
                                </li>                                 
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
	$sql_attachments="Select * from sdms_ticket_evidence where ticket_id ='".$query->getId()."'";
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
$sql_complaint_details="Select * from sdms_ticket_thread where ticket_id='".$query->getId()."' ORDER By id limit 0,1";
$res_complaint_details=mysql_query($sql_complaint_details);
$row_complaint_details=mysql_fetch_array($res_complaint_details);
?>
<div class="row-fluid">
<div class="span12">
        <div class="block-fluid ucard">
                        <div class="info">                                                                
                            <ul class="rows">
                            <li class="heading">Query Details 
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
                            if($query->getSubject())
                            echo $query->getSubject();
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
$tcount = $query->getThreadCount();
if($cfg->showNotesInline())
    $tcount+= $query->getNumNotes();
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
            <h1> Query Log(<?php echo $tcount; ?>)</h1>
               <?php if(!$cfg->showNotesInline()) {?>
<a id="toggle_notes" href="#">Internal Notes (<?php echo $query->getNumNotes(); ?>)</a>
<?php }?>
        </div>
        <div class="headInfo" style="padding: 0px;"> 
        <div class="arrow_down"></div>
        </div>
        <?php if(!$cfg->showNotesInline()) { ?>
        <div class="block stream" id="ticket_notes">
        <?php
        /* Internal Notes */
        if($query->getNumNotes() && ($notes=$query->getNotes())) {
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
		       
        if(($thread=$query->getThreadEntries($types))) {
if($query->getSource()!='Web'){
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
        && ($tentry=$query->getThreadEntry($entry['id']))
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
        && ($tentry=$query->getThreadEntry($entry['id']))
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
			&& ($tentry=$query->getThreadEntry($entry['id']))
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
	&& ($tentry=$query->getThreadEntry($entry['id']))
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
	&& ($tentry=$query->getThreadEntry($entry['id']))
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
<!--Post Reply  ,  Post Internal Note  ,  Dept. Transfer  ,  Reassign Query -->
<?php 
//echo $_SESSION['_staff']['ID'];
//echo $query->getStaffId;
//exit;
if($_SESSION['_staff']['ID'] == $query->getStaffId()  && $query->getStatus() != 'closed'){
if(!$query->getAcceptStatus() && !$thisstaff->isAdmin())
{ ?>
<div class="row-fluid">

<div class="span6">
<div class="block-fluid ucard">
<div class="info">
<ul class="rows">
<li class="heading">Do you want to <b>claim</b> (self assign) this Query?</li>
<li>
<div class="title">Accept:</div>
<div class="text"><form action="queries.php?id=<?php echo $query->getId(); ?>#accepted" method="post">
<?php csrf_token(); ?>
<input type="hidden" name="id" value="<?php echo $query->getId(); ?>">
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
<li class="heading">Do you want to <b>deny</b> this Query?</li>
<form action="queries.php?id=<?php echo $query->getId(); ?>"  method="post">
<?php csrf_token(); ?>
<input type="hidden" name="id" value="<?php echo $query->getId(); ?>">
<input type="hidden" name="a" value="deny">
<input type="hidden" name="complaint_status" value="7" >
<input type="hidden" name="title" id="title" value="Query is Denied"/>
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
<?php }else{ if($thisstaff->canPostReply()) { ?>
<div class="row-fluid">
<div class="span12">
<div class="head clearfix">
    <div class="isw-list"></div>
    <h1>Query Actions</h1>
</div>
<div class="block-fluid tabs">
<ul>
<?php if($thisstaff->canTransferTickets() && $thisstaff->isAdmin()) { ?>  
<li><a id="transfer_tab" href="#transfer">Dept. Transfer</a></li>
<?php }else{?>
<?php if($thisstaff->canTransferTickets() && $thisstaff->checksubdepart($staff->getDeptId())  && $thisstaff->isFocalPerson() ){ ?>
<li><a id="sub_dept_transfer_tab" href="#sub_dept_transfer">Dept. Transfer</a></li>
<?php }?>
<?php if( $thisstaff->getId()=='129' ) { ?>  
<li><a id="transfer_tab" href="#transfer">Dept. Transfer</a></li>
<?php } ?>
<?php if($thisstaff->canAssignTickets()) { ?>
<li><a id="assign_tab" href="#assign"><?php echo $query->isAssigned()?'Reassign Query':'Assign Query'; ?></a></li>
<?php  } ?>
<?php if($thisstaff->canPostReply()) { ?>
<li><a id="category_tab" href="#category">Set Category</a></li>
<li><a id="reply_tab" href="#reply"> Reply To User</a></li>
<li><a id="note_tab" href="#note">Update Activity</a></li>
<?php } }?>
</ul>
<!--Transfer Query-->
<?php if($thisstaff->canTransferTickets() && $thisstaff->isAdmin()) { ?>  
<div id="transfer">
 <form id="transfer" action="queries.php?id=<?php echo $query->getId(); ?>#transfer" name="transfer" method="post" enctype="multipart/form-data">
        <?php csrf_token(); ?>
        <input type="hidden" name="ticket_id" value="<?php echo $query->getId(); ?>">
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
            <div class="span9">  Query is currently in <b><?php echo $query->getDeptName(); ?></b> department
            <?php echo $errors['transfer']; ?>
            <select id="deptId" name="deptId" onchange="get_focal_person(this.value);">
            <option value="0" selected="selected">Select Target Department</option>
            <?php
            if($depts=Dept::getDepartments()) {
            foreach($depts as $id =>$name) {
            if($id==$query->getDeptId()) continue;
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
         <img src="img/loaders/loader.gif" title="loader.gif" style="display:none;" id="loading_status" >
           <input class="btn" type="submit" value="Transfer"  id="submit_btn">
           <input class="btn" type="reset" value="Reset">
</div>            
</div>
    </form>
    </div>
<?php }else{ ?> 
<!--Sub Dept Transfer-->
<?php if($thisstaff->canTransferTickets() && $thisstaff->checksubdepart($staff->getDeptId())  && $thisstaff->isFocalPerson() ){ ?>
<div id="sub_dept_transfer">
 <form id="sub_dept_transfer" action="queries.php?id=<?php echo $query->getId(); ?>#sub_dept_transfer" name="sub_dept_transfer" method="post" enctype="multipart/form-data">
        <?php csrf_token(); ?>
        <input type="hidden" name="ticket_id" value="<?php echo $query->getId(); ?>">
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
            <div class="span9">  Query is currently in <b><?php echo $query->getDeptName(); ?></b> department
            <?php echo $errors['transfer']; ?>
            <select id="deptId" name="deptId" onchange="get_focal_person(this.value);">
            <option value="0" selected="selected">Select Target Department</option>
            <?php
            if($depts=Dept::getSubCategory(true,$staff->getDeptId())) {
            foreach($depts as $id =>$name) {
            if($id==$query->getDeptId()) continue;
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
         <img src="img/loaders/loader.gif" title="loader.gif" style="display:none;" id="loading_status" >
           <input class="btn" type="submit"  id="submit_btn" value="Transfer">
           <input class="btn" type="reset" value="Reset">
</div>            
</div>
    </form>
    </div>
<?php }?>
<!--Assign Query--> 
<?php if( $thisstaff->getId()=='129' ) { ?>
<div id="transfer">
 <form id="transfer" action="queries.php?id=<?php echo $query->getId(); ?>#transfer" name="transfer" method="post" enctype="multipart/form-data">
        <?php csrf_token(); ?>
        <input type="hidden" name="ticket_id" value="<?php echo $query->getId(); ?>">
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
            <div class="span9">  Query is currently in <b><?php echo $query->getDeptName(); ?></b> department
            <?php echo $errors['transfer']; ?>
            <select id="deptId" name="deptId" onchange="get_focal_person(this.value);">
            <option value="0" selected="selected">Select Target Department</option>
            <?php
            if($depts=Dept::getDepartments()) {
            foreach($depts as $id =>$name) {
            if($id==$query->getDeptId()) continue;
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
         <img src="img/loaders/loader.gif" title="loader.gif" style="display:none;" id="loading_status" >
           <input class="btn" type="submit"  id="submit_btn" value="Transfer">
           <input class="btn" type="reset" value="Reset">
</div>            
</div>
    </form>
    </div>
<?php } ?>                        
<?php if($thisstaff->canAssignTickets()) { ?>
<div id="assign">
<form id="assign" action="queries.php?id=<?php echo $query->getId(); ?>#assign" onsubmit="return check_depart_update()" name="assign" method="post" enctype="multipart/form-data">
<?php csrf_token(); ?>
<input type="hidden" name="id" value="<?php echo $query->getId(); ?>">
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
$sql_depart="Select * from sdms_department where dept_id = '".$query->getDeptId()."'";
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
if($query->isAssigned() && $query->isOpen()) {
echo sprintf('<span class="faded">Query is currently assigned to <b>%s</b></span>',
$query->getAssignee());
} else {
echo '<span class="faded">Assigning a closed Query will <b>reopen</b> it!</span>';
}
?>
<br>
<select id="assignId" name="assignId" style="width:300px;">
<option value="0" selected="selected">&mdash; Select Staff Member &mdash;</option>
<?php
if($query->isOpen() && !$query->isAssigned())
echo sprintf('<option value="%d">Claim Query (comments optional)</option>', $thisstaff->getId());
$sid=$tid=0;
//Childs under this user
if($staff->isFocalPerson())
{
if(($users=Staff::getSubPOC($staff->getDeptId()))) {
echo '<OPTGROUP label="Sub POC ('.count($users).')">';
$staffId=$query->isAssigned()?$query->getStaffId():0;
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
echo '<OPTGROUP label="SDMS POC ('.count($users).')">';
$staffId=$query->isAssigned()?$query->getStaffId():0;
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
$staffId=$query->isAssigned()?$query->getStaffId():0;
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
$sql_sdms_department="Select * from sdms_department where dept_id='".$query->getDeptId()."' ORDER By dept_id limit 0,1";
$res_sdms_department=mysql_query($sql_sdms_department);
$row_sdms_department=mysql_fetch_array($res_sdms_department);
if($row_sdms_department['islocation']=='1'){
$sql_sdms_staff="Select * from sdms_staff where staff_id='".$query->getStaffId()."' ORDER By staff_id limit 0,1";
$res_sdms_staff=mysql_query($sql_sdms_staff);
$row_sdms_staff=mysql_fetch_array($res_sdms_staff);
if(($users=Staff::getRegionUser($query->getDeptId(),$query->getStaffId(),$row_sdms_staff['region_id']))) {
echo '<OPTGROUP label="Region User ('.count($users).')">';
$staffId=$query->isAssigned()?$query->getStaffId():0;
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
$staffId=$query->isAssigned()?$query->getStaffId():0;
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
$staffId=$query->isAssigned()?$query->getStaffId():0;
foreach($users as $id => $name) {
if($staffId && $staffId==$id)
continue;

$k="s$id";
echo sprintf('<option value="%s" %s>%s</option>',$k,(($info['assignId']==$k)?'selected="selected"':''),$name);
}
echo '</OPTGROUP>';
}
//show last assign user
if(($admin_users=Staff::getAssignByMember($query->getId()))) {
echo '<OPTGROUP label="Marked By('.count($admin_users).')">';
$staffId=$query->isAssigned()?$query->getStaffId():0;
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
$staffId=$query->isAssigned()?$query->getStaffId():0;
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
<input type="hidden" value="<?php echo $query->complaint_status_title(); ?>" name="complaint_status" >
<?php /*?><div class="row-form clearfix">
<div class="span3">Query Status:</div>
<div class="span9">
<select name="status_pid" style="width:300px;" onChange="get_Sub_Status(this.value)" required>
<option value="">--Select Status--</option>
<?php
$sql_get_child_status='SELECT *  FROM sdms_status status WHERE status_id = '.$query->complaint_status_title().' ';
$res_get_child_status=db_query($sql_get_child_status);
$row_get_child_status = db_fetch_array($res_get_child_status);

if($status=Status::getParentStatus_Query()) {
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
<option value="<?php echo $row_get_child_statuses['status_id'] ?>" <?php if($query->complaint_status_title()==$row_get_child_statuses['status_id']){?> selected <?php } ?>><?php echo $row_get_child_statuses['status_title'] ?></option>
<?php	
}
?>
</select>
&nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['complaint_status_title']; ?></font></div>       
</div>       
   <?php */?>
<div class="footer tar" style="margin-top: 0px;">
<input type="submit" id="submit5" class="btn" value="Submit" />
<input type="reset" class="btn" value="Reset" />
</div>                            
</div>
</form>
</div>  
<?php } ?>   
<?php if($thisstaff->canPostReply()) { ?>
<!--Category Summary-->   
<div id="category">                                    
<form id="category" action="queries.php?id=<?php echo $query->getId(); ?>#category" name="category" method="post" enctype="multipart/form-data">
<?php csrf_token(); ?>
<input type="hidden" name="id" value="<?php echo $query->getId(); ?>">
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
if($topics=Topic::getPublicHelpTopics($query->getDeptId(),'1')) {
foreach($topics as $id =>$name) {
echo sprintf('<option value="%d" %s>%s</option>',
    $id, ($info['topicId']==$id)?'selected="selected"':'', $name);
}
}
?>
</select></div>
</div> 
<input type="hidden" value="1" name="tiers" id="tiers">
<div id="2_tires"></div>
<div id="3_tires"></div>
<div id="4_tires"></div>

<?php /*?><div class="row-form clearfix">
        <div class="span3">Query Status:</div>
        <div class="span9"><select name="complaint_status" style="width:300px;">
<?php
if($status=Status::getStatus($thisstaff->isAdmin())) {
    foreach($status as $id =>$name) {
        echo sprintf('<option value="%d" %s>%s</option>',
                $id, ($query->complaint_status_title()==$id)?'selected="selected"':'',$name);
    }
}
?>
</select>
    &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['complaint_status_title']; ?></font></div>
        </div><?php */?>   
<div class="footer tar" style="margin-top: 0px;">
<input type="submit"  id="submit_btn" class="btn" value="Submit" />
<input type="reset" class="btn" value="Reset" />
</div>                            
</div>
       </form>
</div> 
<!--Update Activity-->
<div id="note">                                    
<form id="note" action="queries.php?id=<?php echo $query->getId(); ?>#note" name="note" method="post" enctype="multipart/form-data">
<?php csrf_token(); ?>
<input type="hidden" name="id" value="<?php echo $query->getId(); ?>">
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
<div class="span3">Query Status:</div>
<div class="span9">
<select name="status_pid" style="width:300px;" onChange="get_Sub_Status_note(this.value)" required>
<option value="">--Select Status--</option>
<?php
$sql_get_child_status='SELECT *  FROM sdms_status status WHERE status_id = '.$query->complaint_status_title().' ';
$res_get_child_status=db_query($sql_get_child_status);
$row_get_child_status = db_fetch_array($res_get_child_status);

if($status=Status::getParentStatus_Query()) {
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
<option value="<?php echo $row_get_child_statuses['status_id'] ?>" <?php if($query->complaint_status_title()==$row_get_child_statuses['status_id']){?> selected <?php } ?>><?php echo $row_get_child_statuses['status_title'] ?></option>
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
<form id="reply" action="queries.php?id=<?php echo $query->getId(); ?>#reply" name="reply" method="post" enctype="multipart/form-data">
<?php csrf_token(); ?>
<input type="hidden" name="id" value="<?php echo $query->getId(); ?>">
<input type="hidden" name="msgId" value="<?php echo $msgId; ?>">
<input type="hidden" name="a" value="reply">
<input type="hidden" value="Post Reply" name="title"  />
<div class="block-fluid" style="margin-bottom:0px;">
<div class="row-form clearfix">
<div class="span3">TO:</div>
<div class="span9"> <?php
$to = $query->getEmail();
if(($name=$query->getName()) && !strpos($name,'@'))
$to =sprintf('%s <em>&lt;%s&gt;</em>', $name, $query->getEmail());
echo $to;
?>
&nbsp;&nbsp;&nbsp;
<input type='hidden' value='1' name="emailreply" id="remailreply"
<?php echo ((!$info['emailreply'] && !$errors) || isset($info['emailreply']))?'checked="checked"':''; ?>><!-- Email Reply--></div>
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
if(($cannedResponses=Canned::responsesByDeptId($query->getDeptId()))) {?>
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
<?php /*?><div class="row-form clearfix">
<div class="span3">Query Status:</div>
<div class="span9">
<select name="status_pid" style="width:300px;" onChange="get_Sub_Status_reply(this.value)">
<option value="">--Select Status--</option>
<?php
$sql_get_child_status='SELECT *  FROM sdms_status status WHERE status_id = '.$query->complaint_status_title().' ';
$res_get_child_status=db_query($sql_get_child_status);
$row_get_child_status = db_fetch_array($res_get_child_status);

if($status=Status::getParentStatus_Query()) {
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
<option value="<?php echo $row_get_child_statuses['status_id'] ?>" <?php if($query->complaint_status_title()==$row_get_child_statuses['status_id']){?> selected <?php } ?>><?php echo $row_get_child_statuses['status_title'] ?></option>
<?php	
}
?>
</select>
&nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['complaint_status_title']; ?></font></div>       
</div>    <?php */?>   
<input type="hidden" value="<?php echo $query->complaint_status_title(); ?>" name="complaint_status" >
<div class="footer tar" style="margin-top: 0px;">
<input type="submit"  id="submit_btn"  class="btn" value="Submit" />
<input type="reset" class="btn" value="Reset" />
</div>            
</div>
</form>   
</div>
<?php  } }?>
</div>
</div>
</div>     
<?php } }?>
<?php }
elseif($thisstaff->getGroupId()=='7' && $query->getStatus() != 'closed'){ ?>
<div class="row-fluid">
<div class="span12">
<div class="head clearfix">
    <div class="isw-list"></div>
    <h1>Queries Actions</h1>
</div>
<div class="block-fluid tabs">
<ul>
<li><a id="reply_tab" href="#reply">Reply To User</a></li>
<li><a id="note_tab" href="#note">Update Activity</a></li>
</ul>
<!--Customer Reply-->
<div id="reply">
<form id="reply" action="tickets.php?id=<?php echo $query->getId(); ?>#reply" name="reply" method="post" enctype="multipart/form-data">
<?php csrf_token(); ?>
<input type="hidden" name="id" value="<?php echo $query->getId(); ?>">
<input type="hidden" name="msgId" value="<?php echo $msgId; ?>">
<input type="hidden" name="a" value="reply">
<input type="hidden" value="Post Reply" name="title"  />
<div class="block-fluid" style="margin-bottom:0px;">
<div class="row-form clearfix">
<div class="span3">TO:</div>
<div class="span9"> <?php
$to = $query->getEmail();
if(($name=$query->getName()) && !strpos($name,'@'))
$to =sprintf('%s <em>&lt;%s&gt;</em>', $name, $query->getEmail());
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
if(($cannedResponses=Canned::responsesByDeptId($query->getDeptId()))) {?>
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
<input type="hidden" value="<?php echo $query->complaint_status_title(); ?>" name="complaint_status" >
<?php /*?><div class="row-form clearfix">
<div class="span3">Complaint Status:</div>
<div class="span9">
<select name="status_pid" style="width:300px;" onChange="get_Sub_Status_reply(this.value)">
<option value="">--Select Status--</option>
<?php
$sql_get_child_status='SELECT *  FROM sdms_status status WHERE status_id = '.$query->complaint_status_title().' ';
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
<option value="<?php echo $row_get_child_statuses['status_id'] ?>" <?php if($query->complaint_status_title()==$row_get_child_statuses['status_id']){?> selected <?php } ?>><?php echo $row_get_child_statuses['status_title'] ?></option>
<?php	
}
?>
</select>
&nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['complaint_status_title']; ?></font></div>       
</div>   <?php */?>    

<div class="footer tar" style="margin-top: 0px;">
<input type="submit" id="submit_btn" class="btn" value="Submit"  onclick="return confirm('Are you sure to submit or send?')" />
<input type="reset" class="btn" value="Reset" />
</div>            
</div>
</form>   
</div>
<div id="note">                                    
<form id="note" action="queries.php?id=<?php echo $query->getId(); ?>#note" name="note" method="post" enctype="multipart/form-data">
<?php csrf_token(); ?>
<input type="hidden" name="id" value="<?php echo $query->getId(); ?>">
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

<?php 
$sql_get_child_status='SELECT *  FROM sdms_status status WHERE status_id = '.$query->complaint_status_title().' ';
$res_get_child_status=db_query($sql_get_child_status);
$row_get_child_status = db_fetch_array($res_get_child_status);

?>
<!-- <input type="hidden" name="status_pid" value="<?php //echo $row_get_child_status['p_id']; ?>"> -->
<input type="hidden" name="complaint_status" value="<?php echo $query->complaint_status_title(); ?>">

<!-- <div class="row-form clearfix">
<div class="span3">Query Status:</div>
<div class="span9">
<select name="status_pid" style="width:300px;" onChange="get_Sub_Status_note(this.value)" required>
<option value="">--Select Status--</option>
<?php
//$sql_get_child_status='SELECT *  FROM sdms_status status WHERE status_id = '.$query->complaint_status_title().' ';
//$res_get_child_status=db_query($sql_get_child_status);
//$row_get_child_status = db_fetch_array($res_get_child_status);

//if($status=Status::getParentStatus_Query()) {
//foreach($status as $id =>$name) {
//echo sprintf('<option value="%d" %s>%s</option>',$id, ($row_get_child_status['p_id']==$id)?'selected="selected"':'',$name);
//}
//}
?>
</select>
&nbsp;<font class="error"><b>*</b>&nbsp;<?php //echo $errors['complaint_status_title']; ?></font></div>
</div>   
<div class="row-form clearfix" id="show_sub_status_note">
<div class="span3">Sub Status:</div>
<div class="span9">
<select name="complaint_status" style="width:300px;" required>
<option value="">--Select Status--</option>
<?php 
//$sql_get_child_statuses='SELECT *  FROM sdms_status status WHERE p_id = '.$row_get_child_status['p_id'].' ';
//$res_get_child_statuses=db_query($sql_get_child_statuses);
//while($row_get_child_statuses = db_fetch_array($res_get_child_statuses)){?>
<option value="<?php //echo $row_get_child_statuses['status_id'] ?>" <?php //if($query->complaint_status_title()==$row_get_child_statuses['status_id']){?> selected <?php //} ?>><?php //echo $row_get_child_statuses['status_title'] ?></option>
<?php	
//}
?>
</select>
&nbsp;<font class="error"><b>*</b>&nbsp;<?php //echo $errors['complaint_status_title']; ?></font></div>       
</div>
 -->       
<div class="footer tar" style="margin-top: 0px;">
<img src="img/loaders/loader.gif" title="loader.gif" style="display:none;" id="loading_status" >
<input type="submit" id="activity_update" class="btn" value="Submit" />
<input type="reset" class="btn" value="Reset" />
</div>                            
</div>
</form>
</div>
</div>
</div>
</div>
<?php }
elseif($query->getStatus() == 'closed'){
$sql_feedback="Select * from sdms_feedback where complainant_id='".$query->getId()."' ORDER By feedback_id limit 0,1";
$res_feedback=mysql_query($sql_feedback);
$row_feedback=mysql_fetch_array($res_feedback);
$num_feedback=mysql_num_rows($res_feedback);
if($num_feedback > 0){
?>
<div class="row-fluid">
<div class="span12">
        <div class="block-fluid ucard">
                        <div class="info">                                                                
                            <ul class="rows">
                            <li class="heading">Feedback Details created on  <?php echo  date('d M Y',strtotime($row_feedback['date']));?>
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
<div class="dialog" id="ba_popup_print" title="Query Print">
    <form action="queries.php?id=<?php echo $query->getId(); ?>" method="post" id="print_form_abc">
        <?php csrf_token(); ?>
        <input type="hidden" name="a" value="print">
        <input type="hidden" name="id" value="<?php echo $query->getId(); ?>">
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
<div style="display:none;" class="dialog" id="ba_btn_close" title="<?php echo sprintf('%s Query #%s', ($query->isClosed()?'Reopen':'Close'), $query->getNumber()); ?>">
    <?php echo sprintf('Are you sure you want to <b>%s</b> this Query?', $query->isClosed()?'REOPEN':'CLOSE'); ?>
    <form action="queries.php?id=<?php echo $query->getId(); ?>" method="post" id="status-form" name="status-form">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $query->getId(); ?>">
        <input type="hidden" name="a" value="process">
        <input type="hidden" name="do" value="<?php echo $query->isClosed()?'reopen':'close'; ?>">
        <fieldset>
            <em>Reasons for status change (internal note). Optional but highly recommended.</em><br>
            <textarea name="ticket_status_notes" id="ticket_status_notes" cols="50" rows="5" wrap="soft"><?php echo $info['ticket_status_notes']; ?></textarea>
        </fieldset>
            <span class="buttons" style="float:right">
                <input type="submit" style="display:none;" value="<?php echo $query->isClosed()?'Reopen':'Close'; ?>" id="close-reopen-form">
            </span>
    
    </form>
</div>
<!-- Popup form for delete -->
<div class="dialog" id="b_popup_action_del" style="display: none;" title="Please Confirm">   
    <p>
        <font color="red"><strong>Are you sure you want to DELETE this Query?</strong></font>
        <br><br>Deleted Query CANNOT be recovered, including any associated attachments.
    </p>
    <div>Please confirm to continue.</div>
    <form action="queries.php?id=<?php echo $query->getId(); ?>" method="post" id="confirm_form" name="confirm_form">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $query->getId(); ?>">
        <input type="hidden" name="a" value="process">
        <input type="hidden" name="do" id="action" value="delete">        
    </form>
</div>
<!-- Popup form for Overdue -->
<div class="dialog" id="ba_popup_overdue" style="display: none;" title="Please Confirm">   
     <p>
        Are you sure want to flag the Query as <font color="red"><b>overdue</b></font>?
    </p>'
    <div>Please confirm to continue.</div>
    <form action="queries.php?id=<?php echo $query->getId(); ?>" method="post" id="confirm_form" name="confirm_form">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $query->getId(); ?>">
        <input type="hidden" name="a" value="process">
        <input type="hidden" name="do" id="action" value="delete">        
    </form>
</div>
<!-- Popup form for unassigned -->
<div class="dialog" id="ba_popup_unassigned" style="display: none;" title="Please Confirm">   
     <p>
        Are you sure want to <b>unassign</b> Query from <b><?php echo $query->getAssigned(); ?></b>?
    </p>'
    <div>Please confirm to continue.</div>
    <form action="queries.php?id=<?php echo $query->getId(); ?>" method="post" id="confirm_form" name="confirm_form">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $query->getId(); ?>">
        <input type="hidden" name="a" value="process">
        <input type="hidden" name="do" id="action" value="delete">        
    </form>
</div>
<!-- Popup form for Ban -->
<div class="dialog" id="ba_popup_banemail" style="display: none;" title="Please Confirm">   
    <p>
        Are you sure want to <b>ban</b> <?php //echo $query->getEmail(); ?>? <br><br>
        New Query from the email address will be auto-rejected.
    </p>'
    <div>Please confirm to continue.</div>
    <form action="queries.php?id=<?php echo $query->getId(); ?>" method="post" id="confirm_form" name="confirm_form">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $query->getId(); ?>">
        <input type="hidden" name="a" value="process">
        <input type="hidden" name="do" id="action" value="banemail">        
    </form>
</div>
<!-- Popup form for claim -->
<div class="dialog" id="b_popup_action_claim" style="display: none;" title="Please Confirm">
        Are you sure want to <b>claim</b> (self assign) this Query?
    <!--    
    
    <p class="confirm-action" style="display:none;" id="overdue-confirm">
        Are you sure want to flag the Query as <font color="red"><b>overdue</b></font>?
    </p>
    <p class="confirm-action" style="display:none;" id="banemail-confirm">
        Are you sure want to <b>ban</b> <?php //echo $query->getEmail(); ?>? <br><br>
        New Query from the email address will be auto-rejected.
    </p>
    <p class="confirm-action" style="display:none;" id="unbanemail-confirm">
        Are you sure want to <b>remove</b> <?php //echo $query->getEmail(); ?> from ban list?
    </p>
    <p class="confirm-action" style="display:none;" id="release-confirm">
        Are you sure want to <b>unassign</b> Query from <b><?php //echo $query->getAssigned(); ?></b>?
    </p>-->
   
    <div>Please confirm to continue.</div>
    <form action="queries.php?id=<?php echo $query->getId(); ?>" method="post" id="confirm-form" name="confirm-form">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $query->getId(); ?>">
        <input type="hidden" name="a" value="process">
        <input type="hidden" name="do" id="action" value="claim">        
    </form>
</div>
<!-- Popup form for answered -->
<div class="dialog" id="b_popup_action_answered" style="display: none;" title="Please Confirm">
        Are you sure want to flag the Query as <b>answered</b>?     
    <div>Please confirm to continue.</div>
    <form action="queries.php?id=<?php echo $query->getId(); ?>" method="post" id="confirm_answer_form" name="confirm_answer_form">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $query->getId(); ?>">
        <input type="hidden" name="a" value="process">
        <input type="hidden" name="do" id="action" value="answered">        
    </form>
</div>
<!-- Popup form for unanswered -->
<div class="dialog" id="b_popup_action_unanswered" style="display: none;" title="Please Confirm">
    
    <p>
        Are you sure want to flag the Query as <b>unanswered</b>?
    </p>
   <!-- <p class="confirm-action" style="display:none;" id="overdue-confirm">
        Are you sure want to flag the Query as <font color="red"><b>overdue</b></font>?
    </p>
    <p class="confirm-action" style="display:none;" id="banemail-confirm">
        Are you sure want to <b>ban</b> <?php //echo $query->getEmail(); ?>? <br><br>
        New Query from the email address will be auto-rejected.
    </p>
    <p class="confirm-action" style="display:none;" id="unbanemail-confirm">
        Are you sure want to <b>remove</b> <?php //echo $query->getEmail(); ?> from ban list?
    </p>
    <p class="confirm-action" style="display:none;" id="release-confirm">
        Are you sure want to <b>unassign</b> Query from <b><?php //echo $query->getAssigned(); ?></b>?
    </p>-->
    <div>Please confirm to continue.</div>
    <form action="queries.php?id=<?php echo $query->getId(); ?>" method="post" id="confirm-form" name="confirm-form">
        <?php csrf_token(); ?>
        <input type="hidden" name="id" value="<?php echo $query->getId(); ?>">
        <input type="hidden" name="a" value="process">
        <input type="hidden" name="do" id="action" value="unanswered">        
    </form>
</div>
<div style="display:none;" class="dialog" id="ba_btn_thread" title="Query # <?php echo $query->getExtId(); ?> Discription">
</div>
<script type="text/javascript" src="js_old/ticket.js"></script>
</div><!--WorkPlace End-->  
</div>