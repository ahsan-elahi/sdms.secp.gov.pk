<?php
if(!defined('OSTCLIENTINC') || !$thisclient || !$ticket || !$ticket->checkClientAccess($thisclient)) die('Access Denied!');
$info=($_POST && $errors)?Format::htmlchars($_POST):array();
$dept = $ticket->getDept();
//Making sure we don't leak out internal dept names
if(!$dept || !$dept->isPublic())
$dept = $cfg->getDefaultDept();?>
<script>
function print_complaint()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("complaint_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
//myWindow.close();
}
</script>


<div class="container-fluid">
	<div class="container" style="background-color: white;color: #767271;">
			<div class="row">
				
				<div class="col-xs-12 padding-top-10">
				<a href="logout.php">
					<img class="img-responsive" src="assets/images/logout.jpg" style="float: right;">
				</a>		
					<h4 style="padding-top: 30px;">Query #<?php echo $ticket->getExtId(); ?></h4>
				</div>
				<div class=" col-lg-6 col-xs-12">
					<div class="table-responsive">
						<div class="panel panel-success" style="max-height: inherit;padding: 0px;">
						    <div class="panel-heading">Application Info</div>
						    <table class="table table-striped">
							    <tbody>
							      <tr>
							        <td style = "font-weight:bold">Full Name:</td>
							        <td><?php echo ucfirst($ticket->getName()); ?></td>
							      </tr>
							      <tr>
							        <td style = "font-weight:bold">Email Address:</td>
							        <td><?php echo Format::htmlchars($ticket->getEmail()); ?></td>
							      </tr>
							      <tr>
							        <td style = "font-weight:bold">CNIC Number:</td>
							        <td><?php echo ucfirst($ticket->getNic()); ?></td>
							      </tr>
							      <tr>
							        <td style = "font-weight:bold">Mobile Number:</td>
							        <td><?php echo $ticket->getPhoneNumber(); ?></td>
							      </tr>
							      
							      <tr>
							        <td style = "font-weight:bold">Address:</td>
							        <td><?php 
    if($ticket->getApplicant_Address()!='')
    echo $ticket->getApplicant_Address();
    else
    echo 'Null'; ?></td>
							      </tr>
							      <tr>
							        <td style = "font-weight:bold">Country:</td>
							        <td><?php 
    if($ticket->getDistrict()!='')
    echo $ticket->getDistrict();
    else
    echo 'Null'; ?></td>
							      </tr>
                                  <tr>
							        <td style = "font-weight:bold">Province:</td>
							        <td><?php 
    if($ticket->getTehsil())
    echo $ticket->getTehsil();
    else
    echo 'Null'; ?></td>
							      </tr>
                                  <tr>
							        <td style = "font-weight:bold">City:</td>
							        <td><?php 
    if($ticket->getAgencyTehsilTitle())
    echo $ticket->getAgencyTehsilTitle();
    else
    echo 'Null'; ?></td>
							      </tr>
							    </tbody>
							</table>
					  </div>
					</div>
				</div>
				<div class=" col-lg-6 col-xs-12">
					<div class="table-responsive">
						<div class="panel panel-success" style="max-height: inherit;padding: 0px;">
						    <div class="panel-heading">Query Information</div>
						    <table class="table table-striped">
							    <tbody>
							      <tr>
							        <td style = "font-weight:bold">Query Status:</td>
							        <td><?php echo ucfirst($ticket->getStatus()); ?></td>
							      </tr>
							      <tr>
							        <td style = "font-weight:bold">Department:</td>
							        <td><?php echo ucfirst($ticket->getDeptName()); ?></td>
							      </tr>
							      
							    </tbody>
							</table>
					  </div>
					</div>
				</div>
				
                <div class=" col-lg-6 col-xs-12">
					<div class="table-responsive">
						<div class="panel panel-success" style="max-height: inherit;padding: 0px;">
						    <div class="panel-heading">Query Details</div>
                                <?php
$sql_complaint_details="Select * from sdms_ticket_thread where ticket_id='".$ticket->getId()."' ORDER By id limit 0,1";
$res_complaint_details=mysql_query($sql_complaint_details);
$row_complaint_details=mysql_fetch_array($res_complaint_details);
?>
						    <table class="table table-striped">
							    <tbody>
							      <tr>
							        <td style = "font-weight:bold">Subject:</td>
							        <td>
                                    <?php
                            if($ticket->getSubject())
                            echo $ticket->getSubject();
                            else
                            echo 'Null';
                            ?>
                                    </td>
							      </tr>
							      <tr>
							        <td style = "font-weight:bold">Details:</td>
							        <td> <?php 
                            if($row_complaint_details['body']!='')
                            echo $row_complaint_details['body'];
                            else
                            echo 'Null';?></td>
							      </tr>
							    </tbody>
							</table>
					  </div>
					</div>
				</div>
                
                
                <div class=" col-lg-12 col-xs-12">
					<div class="table-responsive">
						<div class="panel panel-success" style="max-height: inherit;padding: 0px;">
						    <div class="panel-heading">Query Processing</div>
						    <table class="table table-striped">
							    <thead>
							      <tr>
							        <th>Date</th>
							        <th>Subject</th>
							        <th>Status</th>
							        <!--<th>Action By</th>-->
							      </tr>
							    </thead>
							    <tbody>
                                                  <?php    
if($ticket->getThreadCount() && ($thread=$ticket->getClientThread())) {
    $threadType=array('M' => 'message', 'R' => 'response');
    foreach($thread as $entry) {
        //Making sure internal notes are not displayed due to backend MISTAKES!
        if(!$threadType[$entry['thread_type']]) continue;
        $poster = $entry['poster'];
        if($entry['thread_type']=='R' && ($cfg->hideStaffName() || !$entry['staff_id']))
        $poster = ' ';
        ?>
							      <tr class="flip">
							        <td> <span><i class="fa fa-arrow-right icon" aria-hidden="true"></i></span> <?php echo Format::db_datetime($entry['created']); ?> </td>
							        <td><?php echo $entry['title']; ?></td>
							        <td><?php 

		 $sql_fetchstatus = "Select * from sdms_status where status_id='".$entry['complaint_status']."'";
		  $res_fetchstatus = mysql_query($sql_fetchstatus);
		  $row_fetchstatus = mysql_fetch_array($res_fetchstatus);
		  
		  $sql_fetchpstatus = "Select * from sdms_status where status_id='".$row_fetchstatus['p_id']."'";
		  $res_fetchpstatus = mysql_query($sql_fetchpstatus);
		  $row_fetchpstatus = mysql_fetch_array($res_fetchpstatus);
		  
		  echo $row_fetchpstatus['status_title'];?></td>
							        <!--<td><?php //echo $poster; ?></td>-->
							      </tr>
							      <tr class="panel_row" style="display: none;">
							      	<td colspan="4">
							      		<?php echo Format::display($entry['body']); ?><br>
                                        <?php
            if($entry['attachments']
                    && ($tentry=$ticket->getThreadEntry($entry['id']))
                    && ($links=$tentry->getAttachmentsLinks())) { ?>
              <b>Attachments:</b><?php echo $links; ?>
            <?php
            } ?>
							      	</td>
							      </tr>
                                     <?php
    }
}
?>
							    </tbody>
							</table>
					  </div>
					</div>
				</div>
                <?php /*?><div class="col-lg-12 col-xs-12">
					<div class="table-responsive">
						<div class="panel panel-success" style="max-height: inherit;padding: 0px;">
						    <div class="panel-heading">Query Activity</div>
						    <table class="table table-striped">
							    <tbody>
							      <tr>
							        <td style = "font-weight:bold">Details:</td>
							        <td><?php    
if($ticket->getThreadCount() && ($thread=$ticket->getClientThread())) {
    $threadType=array('M' => 'message', 'R' => 'response');
    foreach($thread as $entry) {
        if(!$threadType[$entry['thread_type']]) continue;
        $poster = $entry['poster'];
        if($entry['thread_type']=='R' && ($cfg->hideStaffName() || !$entry['staff_id']))
        $poster = ' ';
        ?>
        <table class="<?php echo $threadType[$entry['thread_type']]; ?>" cellspacing="0" cellpadding="1" width="800" border="0">
            <tr>
            </tr>
            <tr><td><?php echo Format::display($entry['body']); ?></td></tr>
            <?php
            if($entry['attachments']
                    && ($tentry=$ticket->getThreadEntry($entry['id']))
                    && ($links=$tentry->getAttachmentsLinks())) { ?>
                <tr>
                <td width="170"><b>Attachments:</b></td>
                <td class="info" align="left"><?php echo $links; ?></td>
                </tr>
            <?php
            } ?>
        </table>
    <?php
    }
}
?></td>
							      </tr>
							    </tbody>
							</table>
					  </div>
					</div>
				</div><?php */?>
                
                <?php if(!$ticket->isClosed()) { ?>	
				<div class=" col-lg-12 col-xs-12">
					<div class="table-responsive">
						<div class="panel panel-success" style="max-height: inherit;padding: 0px;">
						    <div class="panel-heading">Customer Reply</div>
                 <form id="reply" action="tickets.php?id=<?php echo $ticket->getExtId(); ?>&task=web#reply" name="reply" method="post" enctype="multipart/form-data">
				<?php csrf_token(); ?>
                <input type="hidden" name="id" value="<?php echo $ticket->getExtId(); ?>">
                <input type="hidden" name="a" value="reply">
                
                <input name="title" value="User Reply" type="hidden">
                <input type="hidden" name="complaint_status" value="<?php echo $ticket->complaint_status_title(); ?>" >
                
                <table class="table table-striped">
                <!--<thead>
                <tr>
                <th>Firstname</th>
                <th>Lastname</th>
                </tr>
                </thead>-->
                <tbody>
                <tr>
                <td style = "font-weight:bold">Message:</td>
                <td>
                <div class="col-xs-12">
                
                <textarea class="form-control" name="message" id="message"  rows="5" cols="5"  wrap="soft"  style="margin-top: 10px;"><?php echo $info['message']; ?></textarea>
                </div>
                </td>
                </tr>
                <?php if($cfg->allowOnlineAttachments()) { ?>
                <tr>
                <td style="font-weight:bold;">Attachments:</td>
                <td>
                <div class="col-xs-9">
                <input  class="form-control multifile" type="file" name="attachments[]" size="30" value="" />
                </div>	
                </td>
                </tr>
                <?php }?>
                </tbody>
                <tfoot>
                <tr>
                <td colspan="2" style="text-align:right;">
                <input type="image" name="submit" src="images/post.png" value="Reply">
                <input type="image" src="images/resat.png" value="Reset">
                <input type="image" src="images/Cancel.png" value="Cancel" onClick="history.go(-1)">
                </td>
                </tr>
                </tfoot>
                </table>
                </form>                            
					  </div>
					</div>
				</div>
                <?php } ?>
<?php if($errors['err']) { ?>
<!--<div id="msg_error"><?php //echo $errors['err']; ?></div>-->
<?php }elseif($msg) { ?>
<!--<div id="msg_notice"><?php //echo $msg; ?></div>-->
<?php }elseif($warn) { ?>
<!--<div id="msg_warning"><?php //echo $warn; ?></div>-->
<?php } ?>

			</div>	
	</div>
</div>
<script> 
$(document).ready(function(){
    $(".flip").on('click', function(){
	    	var i = $(this).find('i.icon');
		    	i.toggleClass('downArrow');
		    	// .html('<i class="fa fa-arrow-down" aria-hidden="true" style = "color:red"></i>');
		    	var row = $(this).next().closest('tr.panel_row');
		    	if(i.hasClass('downArrow'))
		    	{
		    		i.removeClass('fa-arrow-right');
		    		i.addClass('fa-arrow-down').css('color', 'red');
		    	}else{
		    		i.removeClass('fa-arrow-down').removeAttr('style');
		    		i.addClass('fa-arrow-right');
		    	}
		        row.slideToggle("fast");
    });
});
</script>