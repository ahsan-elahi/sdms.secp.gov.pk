<?php
if(!defined('OSTSCPINC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');
?>    
<div id="upgrader">
   <div id="main">
    <div id="intro">
        <?php
        if($upgrader && ($errors=$upgrader->getErrors())) {
            if($errors['err'])
                echo sprintf('<b><font color="red">%s</font></b>',$errors['err']);
            echo '<ul class="error">';
            unset($errors['err']);
            foreach($errors as $k => $error)
                echo sprintf('<li>%s</li>',$error);
            echo '</ul>';
        } else {
            echo '<b><font color="red">Internal error occurred - get technical help.</font></b>';
        }
        ?>
        <p><b>For detailed - please view <a href="logs.php">system logs</a> or check your email.</b></p>
        <br>
    </div>
  </div>    
  <div class="clear"></div>
</div>
