<?php
require('client.inc.php');
define('SOURCE','Web'); //Ticket source.
if($_REQUEST['lang']=='urdu')
{
$inc='open_urdu.inc.php';    //default include.
}else{
$inc='open.inc.php';    //default include.
}

$errors=array();
if($_POST):
    $vars = $_POST;

	// $vars['deptId']=$vars['emailId']=0; //Just Making sure we don't accept crap...only topicId is expected.
    if($thisclient) {
        $vars['name']=$thisclient->getName();
        $vars['email']=$thisclient->getEmail();
    } elseif($cfg->isCaptchaEnabled()) {
        if(!$_POST['captcha'])
            $errors['captcha']='Enter text shown on the image';
        elseif(strcmp($_SESSION['captcha'],md5($_POST['captcha'])))
            $errors['captcha']='Invalid - try again!';
    }
/*if(!$errors && $cfg->allowOnlineAttachments() && $_FILES['attachments'])
        $vars['files'] = AttachmentFile::format($_FILES['attachments'], true);*/

    //Ticket::create...checks for errors..


if($vars['assignId']!=0  && ($ticket = Ticket::create($vars, $errors, SOURCE))){
	
		$sql_get_focal="Select * from sdms_staff where dept_id = '".$vars['deptId']."' AND isfocalperson='1'";
		$res_get_focal=mysql_query($sql_get_focal);
		$row_get_focal = mysql_fetch_array($res_get_focal);	
		if($row_get_focal['staff_id'] != $vars['assignId'])
		{
		$vars['assignId'] = $row_get_focal['staff_id'];
		}
		
        $msg='Support Complaint request created';
				
		if($vars['assignId']) { //Assign ticket to staff or team.
		$vars['note'] = 'Complaint Lodge from WebSite';
            $ticket->assign($vars['assignId'], $vars['note']);
        }
		
		//Send Notice to user --- if requested AND enabled!!
		$dept=$ticket->getDept();
		if(!$dept || !($tpl=$dept->getTemplate()))
		$tpl=$cfg->getDefaultTemplate();
		if(!$dept || !($email=$dept->getEmail()))
		$email =$cfg->getDefaultEmail();
			
		
		
		if($tpl && ($msg=$tpl->getNewTicketNoticeMsgTemplate()) && $email) {
		
		$message = $vars['message'];
		if($response)
		$message.="\n\n".$response->getBody();
		/*if($vars['signature']=='mine')
		$signature=$thisstaff->getSignature();
		else*/
		if($vars['signature']=='dept' && $dept && $dept->isPublic())
		$signature=$dept->getSignature();
		else
		$signature='';
		
		
		$msg = $ticket->replaceVars($msg,
		array('message' => $message, 'signature' => $signature));
		if($cfg->stripQuotedReply() && ($tag=trim($cfg->getReplySeparator())))
		$msg['body'] ="\n$tag\n\n".$msg['body'];
		$attachments =($cfg->emailAttachments() && $response)?$response->getAttachments():array();
		$email->send($ticket->getEmail(), $msg['subj'], $msg['body'], $attachments);
		

		}
		
        //Logged in...simply view the newly created Complaint.
        if($thisclient && $thisclient->isValid()) {
            if(!$cfg->showRelatedTickets())
                $_SESSION['_client']['key']= $ticket->getExtId(); //Resetting login Key to the current ticket!
            session_write_close();
            session_regenerate_id();
            @header('Location: tickets.php?id='.$ticket->getExtId());
        }
        //Thank the user and promise speedy resolution!
    if($_POST['lang']=='urdu')
	{
	    $inc='thankyou_urdu.inc.php';
	}else{
	    $inc='thankyou.inc.php';
	}
	
	}else{
        $errors['err']=$errors['err']?$errors['err']:'Unable to create a Complaint. Please correct errors below and try again!';
    }
	
	
endif;
//page
$nav->setActiveNav('new');
require(CLIENTINC_DIR.'header.inc.php');
require(CLIENTINC_DIR.$inc);
require(CLIENTINC_DIR.'footer.inc.php');
?>
