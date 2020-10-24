<?php
require_once(INCLUDE_DIR.'class.attachment.php');
//Basic checks
if(!$thisclient 
        || !$_GET['id'] 
        || !$_GET['h']
        || !($attachment=Attachment::lookup($_GET['id']))
        || !($file=$attachment->getFile()))
    die('Unknown attachment!');
	
//Validate session access hash - we want to make sure the link is FRESH! and the user has access to the parent ticket!!
$vhash=md5($attachment->getFileId().session_id().$file->getHash());
if(strcasecmp(trim($_GET['h']),$vhash) 
        || !($ticket=$attachment->getTicket()) 
        || !$ticket->checkClientAccess($thisclient)) 
    die('Unknown or invalid attachment');
//Download the file..
$file->download();
?>