<!--<script>window.location.replace('http://localhost/Offical_Project/sdms.secp.gov.pk/ach/scp/');</script>-->
<script>window.location.replace('http://172.16.199.184/');</script>
<?php
require('client.inc.php');
$section = 'home';
require(CLIENTINC_DIR.'header.inc.php');
?>

<div id="landing_page">
   <CENTER> <h1>Welcome to the DIRECTORATE GENERAL OF MONITORING AND EVLAUTION, <br>COMPLAINT MANAGEMENT SYSTEM, </h1></CENTER>
    <p>
        In order to streamline support requests and better serve you, M&E utilizes a support Complaint system. Every support request is assigned a unique Complaint number  which you can use to track the progress and responses online or offline. For your reference we provide complete archives and history of all your support requests. A valid email address or telephone/mobile number is required if you want to get the Complaint and PIN number . <br />(you can track your Complaint Status only by providing the Complaint and PIN Code number).
    </p>

    <div id="new_ticket">
        <h3>Open A New Complaint</h3>
        <br>
        <div>Please provide as much detail as possible so we can best assist you. To update a previously submitted Complaint, please login.</div>
        <p>
            <a href="open.php" class="green button">Open a New Complaint</a>
        </p>
    </div>

    <div id="check_status">
        <h3>Check Complaint Status</h3>
        <br>
        <div>You would need the Complaint number to track your complaint status.</div>
        <p>
            <a href="view.php" class="blue button">Check Complaint Status</a>
        </p>
    </div>
</div>
<div class="clear"></div>
<?php
if($cfg && $cfg->isKnowledgebaseEnabled()){
    //FIXME: provide ability to feature or select random FAQs ??
?>
<p>Be sure to browse our <a href="kb/index.php">Frequently Asked Questions (FAQs)</a>, before opening a Complaint.</p>
</div>
<?php
} ?>
<?php require(CLIENTINC_DIR.'footer.inc.php'); ?>
