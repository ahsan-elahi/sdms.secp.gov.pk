<?php
if(!defined('OSTCLIENTINC') || !$thisclient || !$ticket || !$ticket->checkClientAccess($thisclient)) die('Access Denied!');
$info=($_POST && $errors)?Format::htmlchars($_POST):array();
$dept = $ticket->getDept();
//Making sure we don't leak out internal dept names
if(!$dept || !$dept->isPublic())
$dept = $cfg->getDefaultDept();?>
<table width="800" cellpadding="1" cellspacing="0" border="0" id="ticketInfo">
    <tr>
        <td colspan="2" width="100%">
            <h1>
                Complaint #<?php echo $ticket->getExtId(); ?> &nbsp;
                <a href="view.php?id=<?php echo $ticket->getExtId(); ?>" title="Reload"><span class="Icon refresh">&nbsp;</span></a>
            </h1>
        </td>
    </tr>
    <tr>
        <td width="50%">
            <h3>
                Personal Info: &nbsp;
            </h3>
        </td>
         <td width="50%">
            <h3>
                Complaint Info: &nbsp;
            </h3>
        </td>
    </tr> 
    <tr>
        <td width="50%">   
           <table class="infoTable" cellspacing="1" cellpadding="3" width="100%" border="0">
               <tr>
                   <th align="left" >Name:</th>
                   <td><?php echo ucfirst($ticket->getName()); ?></td>
               </tr>
			   <tr>
                   <th align="left">CNIC:</th>
                   <td><?php echo ucfirst($ticket->getNic()); ?></td>
               </tr>
                <tr>
                    <th align="left" >Gender:</th>
                    <td><?php echo $ticket->getGender(); ?></td>
                </tr>
                <tr>
                    <th align="left">Address:</th>
                    <td><?php echo $ticket->getUserAddress(); ?></td>
                </tr>
               <tr>
                   <th align="left">Email:</th>
                   <td><?php echo Format::htmlchars($ticket->getEmail()); ?></td>
               </tr>
               <tr>
                   <th align="left">Phone:</th>
                   <td><?php echo $ticket->getPhoneNumber(); ?></td>
               </tr>
                <tr>
                    <th align="left">Location:</th>
                    <td><?php echo $ticket->getlocation(); ?></td>
                </tr>                
            </table> 
       </td>
       <td width="50%">
           <table class="infoTable" cellspacing="1" cellpadding="3" width="100%" border="0">
                <tr>
                    <th align="left"> Complaint Status:</th>
                    <td><?php echo ucfirst($ticket->getStatus()); ?></td>
                </tr>
                <tr>
                    <th align="left"> Priority:</th>
                    <td><?php echo $ticket->getPriority(); ?></td>
                </tr>
                <tr>
                    <th align="left">Department:</th>
                    <td><?php echo Format::htmlchars($dept->getName()); ?></td>
                </tr>
                <tr>
                    <th align="left">Create Date:</th>
                    <td><?php echo Format::db_datetime($ticket->getCreateDate()); ?></td>
                </tr>
              <tr>
                    <th align="left">Type of Complaint:</th>
                    <td><?php echo Format::htmlchars($ticket->getHelpTopic()); ?></td>
                </tr>
                 <tr>
                    <th align="left">Complaint Against Person:</th>
                    <td><?php echo $ticket->getcomp_against(); ?></td>
                </tr>
                <tr>
                    <th align="left">Complaintent Address:</th>
                    <td><?php echo $ticket->getaddress(); ?></td>
                </tr>
                
                
                
                
           </table>
       </td>
    </tr>
</table>
<br>
<hr />
<h3>Complaint Details: &nbsp;            </h3>
<b>Subject:</b>&nbsp; &nbsp; <?php echo Format::htmlchars($ticket->getSubject()); ?>
<br>
<br />
<span class="Icon thread"><b>Complaint Details:</b></span>
<div id="ticketThread">
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
        <table class="<?php echo $threadType[$entry['thread_type']]; ?>" cellspacing="0" cellpadding="1" width="800" border="0">
        
            <tr>
           <!-- <td><?php /*echo Format::db_datetime($entry['created']); ?> &nbsp;&nbsp;<span><?php echo $poster;*/ ?></span></td>-->
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
?>
</div>