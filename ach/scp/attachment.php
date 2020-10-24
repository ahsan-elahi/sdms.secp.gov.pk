<?php
require('staff.inc.php');
require_once(INCLUDE_DIR.'class.attachment.php');

//Basic checks
if(!$thisstaff || !$_GET['id'] || !$_GET['h'] 
        || !($attachment=Attachment::lookup($_GET['id'])) 
        || !($file=$attachment->getFile()))
    die('Unknown attachment!');

//Validate session access hash - we want to make sure the link is FRESH! and the user has access to the parent ticket!!
$vhash=md5($attachment->getFileId().session_id().$file->getHash());
if(strcasecmp(trim($_GET['h']),$vhash) || !($ticket=$attachment->getTicket()) || !$ticket->checkStaffAccess($thisstaff)) die('Access Denied');

//Download the file..
$file->download();
?>
