<?php

if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin() || !$config) die('Access Denied');

?>
<div class="page-header">
<h1>Knowledge Base <small> Settings and Options </small></h1>
</div>  
<form action="settings.php?t=kb" method="post" id="save">
<?php csrf_token(); ?>
<input type="hidden" name="t" value="kb" >     
<div class="row-fluid">
<div class="span12">
<div class="head clearfix">
    <div class="isw-documents"></div>
    <h1>Knowledge Base Settings: </h1><em>Disabling knowledge base disables clients' interface.</em>
</div>
    <div class="block-fluid">         
     <div class="row-form clearfix">
            <div class="span3">Knowledge base status:</div>
            <div class="span9"> <input type="checkbox" name="enable_kb" value="1" <?php echo $config['enable_kb']?'checked="checked"':''; ?>>

              Enable Knowledge base&nbsp;<em>(Client interface)</em>

              &nbsp;<font class="error">&nbsp;<?php echo $errors['enable_kb']; ?></font></div>
        </div>
        <div class="row-form clearfix">
            <div class="span3">Canned Responses:</div>
            <div class="span9"><input type="checkbox" name="enable_premade" value="1" <?php echo $config['enable_premade']?'checked="checked"':''; ?> >

                Enable canned responses&nbsp;<em>(Available on Complaint reply)</em>

                &nbsp;<font class="error">&nbsp;<?php echo $errors['enable_premade']; ?></font></div>
        </div>
                                    
    </div>
    </div>
    <div class="row-fluid">

<div class="span12">
<div class="footer tar">
<input type="submit" name="submit" value="Save Changes" class="btn">
<input type="reset"  name="reset"  value="Reset Changes" class="btn">
</div>
</div>
</div>
</div>
</form>
<div class="dr"><span></span></div>
</div></div>

