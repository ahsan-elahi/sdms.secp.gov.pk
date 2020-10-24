<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin() || !$config) die('Access Denied');
if(!($maxfileuploads=ini_get('max_file_uploads')))
    $maxfileuploads=DEFAULT_MAX_FILE_UPLOADS;
?>

<div class="page-header">
<h1>Complaint   <small>Settings and Options </small></h1>
</div>  
<form action="settings.php?t=tickets" method="post" id="save">
<?php csrf_token(); ?>
<input type="hidden" name="t" value="tickets" >          
<div class="row-fluid">
<div class="span12">
<div class="head clearfix">
    <div class="isw-documents"></div>
    <h1>Complaint Settings: <em>(Global Complaint settings and options.)</em></h1>
</div>
    <div class="block-fluid">                        
    
        <div class="row-form clearfix">
            <div class="span3">Complaint IDs:</div>
            <div class="span9"> <input type="radio" name="random_ticket_ids"  value="0" <?php echo !$config['random_ticket_ids']?'checked="checked"':''; ?> />
                Sequential
                <input type="radio" name="random_ticket_ids"  value="1" <?php echo $config['random_ticket_ids']?'checked="checked"':''; ?> />
                Random  <em>(highly recommended)</em></div>
        </div> 
    
        <div class="row-form clearfix">
            <div class="span3">Default SLA:</div>
            <div class="span9">  <select name="default_sla_id">
                    <option value="0">&mdash; None &mdash;</option>
                    <?php
                    if($slas=SLA::getSLAs()) {
                        foreach($slas as $id => $name) {
                            echo sprintf('<option value="%d" %s>%s</option>',
                                    $id,
                                    ($config['default_sla_id'] && $id==$config['default_sla_id'])?'selected="selected"':'',
                                    $name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['default_sla_id']; ?></span></div>
        </div>                                        
    
        <div class="row-form clearfix">
            <div class="span3">Default Priority:</div>
            <div class="span9"><select name="default_priority_id">
                    <?php
                    $priorities= db_query('SELECT priority_id,priority_desc FROM '.TICKET_PRIORITY_TABLE);
                    while (list($id,$tag) = db_fetch_row($priorities)){ ?>
                        <option value="<?php echo $id; ?>"<?php echo ($config['default_priority_id']==$id)?'selected':''; ?>><?php echo $tag; ?></option>
                    <?php
                    } ?>
                </select>
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['default_priority_id']; ?></span></div>
        </div>                                                               
    
        <div class="row-form clearfix">
            <div class="span3">Maximum <b>Open</b> Complaints:</div>
            <div class="span9"><input type="text" name="max_open_tickets" size=4 value="<?php echo $config['max_open_tickets']; ?>">
                per email/user. <em>(Helps with spam and email flood control - enter 0 for unlimited)</em></div>
        </div>
             
         <div class="row-form clearfix">
            <div class="span3">Complaint Auto-lock Time:</div>
            <div class="span9"><input type="text" name="autolock_minutes" size=4 value="<?php echo $config['autolock_minutes']; ?>">
                <font class="error"><?php echo $errors['autolock_minutes']; ?></font>
                <em>(Minutes to lock a complaint on activity - enter 0 to disable locking)</em></div>
        </div> 
        <div class="row-form clearfix">
            <div class="span3">Web Complaints Priority:</div>
            <div class="span9"><input type="checkbox" name="allow_priority_change" value="1" <?php echo $config['allow_priority_change'] ?'checked="checked"':''; ?>>
                        <em>(Allow user to overwrite/set priority)</em></div>
        </div> 
        <div class="row-form clearfix">
            <div class="span3">Emailed Complaints Priority:</div>
            <div class="span9"> <input type="checkbox" name="use_email_priority" value="1" <?php echo $config['use_email_priority'] ?'checked="checked"':''; ?> >
                        <em>(Use email priority when available)</em></div>
        </div> 
        <div class="row-form clearfix">
            <div class="span3">Show Related Complaints:</div>
            <div class="span9"><input type="checkbox" name="show_related_tickets" value="1" <?php echo $config['show_related_tickets'] ?'checked="checked"':''; ?> >
                <em>(Show all related Complaints on user login - otherwise access is restricted to one Complaint view per login)</em></div>
        </div> 
        <div class="row-form clearfix">
            <div class="span3">Show Notes Inline:</div>
            <div class="span9"> <input type="checkbox" name="show_notes_inline" value="1" <?php echo $config['show_notes_inline'] ?'checked="checked"':''; ?> >
                <em>(Show internal notes  inline)</em></div>
        </div> 
        <div class="row-form clearfix">
            <div class="span3">Clickable URLs:</div>
            <div class="span9"> <input type="checkbox" name="clickable_urls" <?php echo $config['clickable_urls']?'checked="checked"':''; ?>>
               <em>(converts URLs in Complaint thread to clickable links)</em></div>
        </div> 
         <div class="row-form clearfix">
            <div class="span3">Human Verification:</div>
            <div class="span9"><input type="checkbox" name="enable_captcha" <?php echo $config['enable_captcha']?'checked="checked"':''; ?>>
                Enable CAPTCHA on new web Complaint.<em>(requires GDLib)</em> &nbsp;<font class="error">&nbsp;<?php echo $errors['enable_captcha']; ?></font></div>
        </div> 
         <div class="row-form clearfix">
            <div class="span3">Reopened Complaints:</div>
            <div class="span9"><input type="checkbox" name="auto_assign_reopened_tickets" <?php echo $config['auto_assign_reopened_tickets']?'checked="checked"':''; ?>>
                Auto-assign reopened Complaints to the last available respondent.</div>
        </div> 
         <div class="row-form clearfix">
            <div class="span3">Assigned Tickets:</div>
            <div class="span9"> <input type="checkbox" name="show_assigned_tickets" <?php echo $config['show_assigned_tickets']?'checked="checked"':''; ?>>
                Show assigned Complaints on open queue.</div>
        </div> 
         <div class="row-form clearfix">
            <div class="span3">Answered Complaints:</div>
            <div class="span9"><input type="checkbox" name="show_answered_tickets" <?php echo $config['show_answered_tickets']?'checked="checked"':''; ?>>
                Show answered Complaints on open queue.</div>
        </div> 
         <div class="row-form clearfix">
            <div class="span3">Ticket Activity Log:</div>
            <div class="span9"> <input type="checkbox" name="log_ticket_activity" <?php echo $config['log_ticket_activity']?'checked="checked"':''; ?>>
                Log Complaint activity as internal notes.</div>
        </div> 
        <div class="row-form clearfix">
            <div class="span3">Staff Identity Masking:</div>
            <div class="span9"><input type="checkbox" name="hide_staff_name" <?php echo $config['hide_staff_name']?'checked="checked"':''; ?>>
                Hide staff's name on responses.</div>
        </div> 
        
        
                               
    
    
                                 
    </div>

</div>
</div>

<div class="row-fluid">
<div class="span12">
    <div class="head clearfix">
        <div class="isw-documents"></div>
        <h1> Attachments:  (<em>Size and max. uploads setting mainly apply to web Complaints.</em>)</h1>
    </div>

    <div class="block-fluid">                        
    <div class="row-form clearfix">
                <div class="span3">Allow Attachments:</div>
                <div class="span9"><input type="checkbox" name="allow_attachments" <?php echo $config['allow_attachments']?'checked="checked"':''; ?>><b>Allow Attachments</b>
                &nbsp; <em>(Global Setting)</em>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['allow_attachments']; ?></font></div>
            </div> 
            <div class="row-form clearfix">
                <div class="span3">Emailed/API Attachments:</div>
                <div class="span9"> <input type="checkbox" name="allow_email_attachments" <?php echo $config['allow_email_attachments']?'checked="checked"':''; ?>> Accept emailed/API attachments.
                    &nbsp;<font class="error">&nbsp;<?php echo $errors['allow_email_attachments']; ?></font></div>
            </div> 
            <div class="row-form clearfix">
                <div class="span3">Online/Web Attachments:</div>
                <div class="span9"> <input type="checkbox" name="allow_online_attachments" <?php echo $config['allow_online_attachments']?'checked="checked"':''; ?> >
                    Allow web upload &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="allow_online_attachments_onlogin" <?php echo $config['allow_online_attachments_onlogin'] ?'checked="checked"':''; ?> >
                    Limit to authenticated users only. <em>(User must be logged in to upload files)</em>
                    <font class="error">&nbsp;<?php echo $errors['allow_online_attachments']; ?></font></div>
            </div>
            <div class="row-form clearfix">
                <div class="span3">Max. User File Uploads:</div>
                <div class="span9"> <select name="max_user_file_uploads">
                    <?php
                    for($i = 1; $i <=$maxfileuploads; $i++) {
                        ?>
                        <option <?php echo $config['max_user_file_uploads']==$i?'selected="selected"':''; ?> value="<?php echo $i; ?>">
                            <?php echo $i; ?>&nbsp;<?php echo ($i>1)?'files':'file'; ?></option>
                        <?php
                    } ?>
                </select>
                <em>(Number of files the user is allowed to upload simultaneously)</em>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['max_user_file_uploads']; ?></font></div>
            </div>
            <div class="row-form clearfix">
                <div class="span3">Max. Staff File Uploads:</div>
                <div class="span9"><select name="max_staff_file_uploads">
                    <?php
                    for($i = 1; $i <=$maxfileuploads; $i++) {
                        ?>
                        <option <?php echo $config['max_staff_file_uploads']==$i?'selected="selected"':''; ?> value="<?php echo $i; ?>">
                            <?php echo $i; ?>&nbsp;<?php echo ($i>1)?'files':'file'; ?></option>
                        <?php
                    } ?>
                </select>
                <em>(Number of files the staff is allowed to upload simultaneously)</em>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['max_staff_file_uploads']; ?></font></div>
            </div>            
            <div class="row-form clearfix">
                <div class="span3">Maximum File Size:</div>
                <div class="span9"><input type="text" name="max_file_size" value="<?php echo $config['max_file_size']; ?>"> in bytes.
                    <em>(System Max. <?php echo Format::file_size(ini_get('upload_max_filesize')); ?>)</em>
                    <font class="error">&nbsp;<?php echo $errors['max_file_size']; ?></font></div>
            </div>
            <div class="row-form clearfix">
                <div class="span3">Complaint Response Files:</div>
                <div class="span9"><input type="checkbox" name="email_attachments" <?php echo $config['email_attachments']?'checked="checked"':''; ?> >Email attachments to the user</div>
            </div>                   
    </div>
</div>
</div>

<div class="row-fluid">
<div class="span12">
    <div class="head clearfix">
        <div class="isw-documents"></div>
        <h1> Accepted File Types: <em>(Limit the type of files users are allowed to submit.)</em></h1>
    </div>

    <div class="block-fluid">  
            <div class="row-form clearfix">
                <div class="span12"><em>Enter allowed file extensions separated by a comma. e.g .doc, .pdf. To accept all files enter wildcard <b><i>.*</i></b>&nbsp;i.e dotStar (NOT Recommended).</em><br>
                <textarea name="allowed_filetypes" cols="21" rows="4" style="width: 65%;" wrap="hard" ><?php echo $config['allowed_filetypes']; ?></textarea></div>
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
