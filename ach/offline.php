<?php
require_once('client.inc.php');
if(is_object($ost) && $ost->isSystemOnline()) {
    @header('Location: index.php'); //Redirect if the system is online.
    include('index.php');
    exit;
}
$nav=null;
require(CLIENTINC_DIR.'header.inc.php');
?>

<div id="landing_page">
    <h1>Support Complaint System Offline</h1>
    <p>Thank you for your interest in contacting us.</p>
    <p>Our helpdesk is offline at the moment, please check back at a later time.</p>
</div>
<?php require(CLIENTINC_DIR.'footer.inc.php'); ?>