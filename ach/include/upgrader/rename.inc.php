<?php
if(!defined('OSTSCPINC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');
?>
<div id="upgrader">
    <br>
    <h2 style="color:#FF7700;">Configuration file rename required!</h2>
    <div id="main">
            <div id="intro">
             <p>To avoid possible conflicts, please take a minute to rename configuration file as shown below.</p>
            </div>
            <h3>Solution:</h3>
            Rename file <b>include/settings.php</b> to <b>include/sdms-config.php</b> and click continue below.
            <ul>
                <li><b>CLI:</b><br><i>mv include/settings.php include/sdms-config.php</i></li>
                <li><b>FTP:</b><br> </li>
                <li><b>Cpanel:</b><br> </li>
            </ul>
            <div id="bar">
                <form method="post" action="upgrade.php">
                    <?php csrf_token(); ?>
                    <input type="hidden" name="s" value="prereq">
                    <input class="btn" type="submit" name="submit" value="Continue &raquo;">
                </form>
            </div>
    </div>
    <div class="clear"></div>
</div>
