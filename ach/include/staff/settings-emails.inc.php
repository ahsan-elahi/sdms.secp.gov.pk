<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin() || !$config) die('Access Denied');
?>
<div class="page-header">
<h1>Email <small>Settings and Options </small></h1>
</div>  
<form action="settings.php?t=emails" method="post" id="save">
<?php csrf_token(); ?>
<input type="hidden" name="t" value="emails" >      
<div class="row-fluid">
<div class="span12">
<div class="head clearfix">
    <div class="isw-documents"></div>
    <h1>Email Settings: </h1><em>Note that some of the global settings can be overwritten at department/email level.</em>
</div>
    <div class="block-fluid">                        
    
        <div class="row-form clearfix">
            <div class="span3">Default System Email:</div>
            <div class="span9"> <select name="default_email_id">
                    <option value=0 disabled>Select One</option>
                    <?php
                    $sql='SELECT email_id,email,name FROM '.EMAIL_TABLE;
                    if(($res=db_query($sql)) && db_num_rows($res)){
                        while (list($id,$email,$name) = db_fetch_row($res)){
                            $email=$name?"$name &lt;$email&gt;":$email;
                            ?>
                            <option value="<?php echo $id; ?>"<?php echo ($config['default_email_id']==$id)?'selected="selected"':''; ?>><?php echo $email; ?></option>
                        <?php
                        }
                    } ?>
                 </select>
                 &nbsp;<font class="error">*&nbsp;<?php echo $errors['default_email_id']; ?></font></div>
        </div>
        <div class="row-form clearfix">
            <div class="span3">Default Alert Email:</div>
            <div class="span9"><select name="alert_email_id">
                    <option value="0" selected="selected">Use Default System Email (above)</option>
                    <?php
                    $sql='SELECT email_id,email,name FROM '.EMAIL_TABLE.' WHERE email_id != '.db_input($config['default_email_id']);
                    if(($res=db_query($sql)) && db_num_rows($res)){
                        while (list($id,$email,$name) = db_fetch_row($res)){
                            $email=$name?"$name &lt;$email&gt;":$email;
                            ?>
                            <option value="<?php echo $id; ?>"<?php echo ($config['alert_email_id']==$id)?'selected="selected"':''; ?>><?php echo $email; ?></option>
                        <?php
                        }
                    } ?>
                 </select>
                 &nbsp;<font class="error">*&nbsp;<?php echo $errors['alert_email_id']; ?></font></div>
        </div>
        <div class="row-form clearfix">
            <div class="span3">Admin's Email Address:</div>
            <div class="span9"><input type="text" size=40 name="admin_email" value="<?php echo $config['admin_email']; ?>">
                    &nbsp;<font class="error">*&nbsp;<?php echo $errors['admin_email']; ?></font>
                &nbsp;&nbsp;<em>(System administrator's email)</em> </div>
        </div>                             
    </div>

</div>
</div>
<div class="row-fluid">
<div class="span12">
    <div class="head clearfix">
        <div class="isw-documents"></div>
        <h1> Incoming Emails:</h1><em>For mail fetcher (polling) to work you must set an external cron job or enable auto-cron polling</em>
    </div>

    <div class="block-fluid">  
    <div class="row-form clearfix">
            <div class="span3">Email Polling</div>
            <div class="span9"><input type="checkbox" name="enable_mail_polling" value=1 <?php echo $config['enable_mail_polling']? 'checked="checked"': ''; ?>  > Enable POP/IMAP polling
                 &nbsp;&nbsp;
                 <input type="checkbox" name="enable_auto_cron" <?php echo $config['enable_auto_cron']?'checked="checked"':''; ?>>
                 Poll on auto-cron <em>(Poll based on staff activity - NOT recommended)</em></div>
        </div>
        <div class="row-form clearfix">
            <div class="span3">Strip Quoted Reply:</div>
            <div class="span9"><input type="checkbox" name="strip_quoted_reply" <?php echo $config['strip_quoted_reply'] ? 'checked="checked"':''; ?>>
                <em>(depends on the reply separator tag set below)</em>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['strip_quoted_reply']; ?></font></div>
        </div>
        <div class="row-form clearfix">
            <div class="span3">Reply Separator Tag:</div>
            <div class="span9"><input type="text" name="reply_separator" value="<?php echo $config['reply_separator']; ?>">
                &nbsp;<font class="error">&nbsp;<?php echo $errors['reply_separator']; ?></font></div>
        </div>                    
    </div>
</div>
</div>
<div class="row-fluid">
<div class="span12">
    <div class="head clearfix">
        <div class="isw-documents"></div>
        <h1> Outgoing Emails:</h1><em>Default email only applies to outgoing emails without SMTP setting.</em>
    </div>

    <div class="block-fluid">  
            <div class="row-form clearfix">
            <div class="span3">Default Outgoing Email:</div>
            <div class="span9"><select name="default_smtp_id">
                    <option value=0 selected="selected">None: Use PHP mail function</option>
                    <?php
                    $sql='SELECT email_id,email,name,smtp_host FROM '.EMAIL_TABLE.' WHERE smtp_active=1';

                    if(($res=db_query($sql)) && db_num_rows($res)) {
                        while (list($id,$email,$name,$host) = db_fetch_row($res)){
                            $email=$name?"$name &lt;$email&gt;":$email;
                            ?>
                            <option value="<?php echo $id; ?>"<?php echo ($config['default_smtp_id']==$id)?'selected="selected"':''; ?>><?php echo $email; ?></option>
                        <?php
                        }
                    } ?>
                 </select>&nbsp;&nbsp;<font class="error">&nbsp;<?php echo $errors['default_smtp_id']; ?></font></div>
        </div>                
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
</form>
<div class="dr"><span></span></div>
</div></div>
