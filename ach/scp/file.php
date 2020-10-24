<?php
require('staff.inc.php');
require_once(INCLUDE_DIR.'class.file.php');
$h=trim($_GET['h']);
//basic checks
if(!$h  || strlen($h)!=64  //32*2
        || !($file=AttachmentFile::lookup(substr($h,0,32))) //first 32 is the file hash.
        || strcasecmp(substr($h,-32),md5($file->getId().session_id().$file->getHash()))) //next 32 is file id + session hash.
    die('Unknown or invalid file. #'.Format::htmlchars($_GET['h']));

$file->download();
?>
