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
    } /*elseif($cfg->isCaptchaEnabled()) {
        if(!$_POST['captcha'])
            $errors['captcha']='Enter text shown on the image';
        elseif(strcmp($_SESSION['captcha'],md5($_POST['captcha'])))
            $errors['captcha']='Invalid - try again!';
    }*/

/*    if(!$errors && $cfg->allowOnlineAttachments() && $_FILES['attachments'])
        $vars['files'] = AttachmentFile::format($_FILES['attachments'], true);*/

    //Ticket::create...checks for errors..
    if(($ticket = Ticket::create($vars, $errors, SOURCE))){
        $msg='Support Complaint request created';
		
		if($vars['assignId']) { //Assign ticket to staff or team.
		$vars['note'] = 'Compaling Lodge from WebSite';
            $ticket->assign($vars['assignId'], $vars['note']);
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
        $inc='thankyou.inc.php';
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
