 <?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin() || !$config) die('Access Denied');
$gmtime = Misc::gmtime();
?>
<div class="page-header">
<h1>System  <small>Settings and Preferences </small></h1>
</div>  
<form action="settings.php?t=system" method="post" id="save">
<?php csrf_token(); ?>
<input type="hidden" name="t" value="system" >           
<div class="row-fluid">

<div class="span12">
    <div class="head clearfix">
        <div class="isw-documents"></div>
        <h1>General Settings: (<em>Offline mode will disable client interface and only allow admins to login to Staff Control Panel</em>)</h1>
    </div>
    <div class="block-fluid">                        

        <div class="row-form clearfix">
            <div class="span3">Helpdesk Status:</div>
            <div class="span9"> <input type="radio" name="isonline"  value="1"   <?php echo $config['isonline']?'checked="checked"':''; ?> /><b>Online</b> (Active)
<input type="radio" name="isonline"  value="0"   <?php echo !$config['isonline']?'checked="checked"':''; ?> /><b>Offline</b> (Disabled)
&nbsp;<font class="error">&nbsp;<?php echo $config['isoffline']?'osTicket offline':''; ?></font></div>
        </div> 

        <div class="row-form clearfix">
            <div class="span3">Helpdesk URL:</div>
            <div class="span9"> <input type="text" size="40" name="helpdesk_url" value="<?php echo $config['helpdesk_url']; ?>">
&nbsp;<font class="error">*&nbsp;<?php echo $errors['helpdesk_url']; ?></font></div>
        </div>                                        

        <div class="row-form clearfix">
            <div class="span3"> Helpdesk Name/Title:</div>
            <div class="span9"><input type="text" size="40" name="helpdesk_title" value="<?php echo $config['helpdesk_title']; ?>">
&nbsp;<font class="error">*&nbsp;<?php echo $errors['helpdesk_title']; ?></font></div>
        </div>                                                               

        <div class="row-form clearfix">
            <div class="span3">Default Department:</div>
            <div class="span9">  <select name="default_dept_id">
<option value="">&mdash; Select Default Department &mdash;</option>
<?php
$sql='SELECT dept_id,dept_name FROM '.DEPT_TABLE.' WHERE ispublic=1';
if(($res=db_query($sql)) && db_num_rows($res)){
    while (list($id, $name) = db_fetch_row($res)){
        $selected = ($config['default_dept_id']==$id)?'selected="selected"':''; ?>
        <option value="<?php echo $id; ?>"<?php echo $selected; ?>><?php echo $name; ?> Dept</option>
    <?php
    }
} ?>
</select>&nbsp;<font class="error">*&nbsp;<?php echo $errors['default_dept_id']; ?></font></div>
        </div>                        

        <div class="row-form clearfix">
            <div class="span3">Default Email Templates:</div>
            <div class="span9"><select name="default_template_id">
<option value="">&mdash; Select Default Template &mdash;</option>
<?php
$sql='SELECT tpl_id,name FROM '.EMAIL_TEMPLATE_TABLE.' WHERE isactive=1 AND cfg_id='.db_input($cfg->getId()).' ORDER BY name';
if(($res=db_query($sql)) && db_num_rows($res)){
    while (list($id, $name) = db_fetch_row($res)){
        $selected = ($config['default_template_id']==$id)?'selected="selected"':''; ?>
        <option value="<?php echo $id; ?>"<?php echo $selected; ?>><?php echo $name; ?></option>
    <?php
    }
} ?>
</select>&nbsp;<font class="error">*&nbsp;<?php echo $errors['default_template_id']; ?></font></div>
        </div>                                                

        <div class="row-form clearfix">
            <div class="span3">Default Page Size:</div>
            <div class="span9"> <select name="max_page_size">
<?php
 $pagelimit=$config['max_page_size'];
for ($i = 5; $i <= 50; $i += 5) {
    ?>
    <option <?php echo $config['max_page_size']==$i?'selected="selected"':''; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
    <?php
} ?>
</select></div>
        </div>
        
        <div class="row-form clearfix">
            <div class="span3">Default Log Level:</div>
            <div class="span9"><select name="log_level">
<option value=0 <?php echo $config['log_level'] == 0 ? 'selected="selected"':''; ?>>None (Disable Logger)</option>
<option value=3 <?php echo $config['log_level'] == 3 ? 'selected="selected"':''; ?>> DEBUG</option>
<option value=2 <?php echo $config['log_level'] == 2 ? 'selected="selected"':''; ?>> WARN</option>
<option value=1 <?php echo $config['log_level'] == 1 ? 'selected="selected"':''; ?>> ERROR</option>
</select>
<font class="error">&nbsp;<?php echo $errors['log_level']; ?></font></div>
        </div>
        
        <div class="row-form clearfix">
            <div class="span3">Purge Logs:</div>
            <div class="span9"><select name="log_graceperiod">
<option value=0 selected>Never Purge Logs</option>
<?php
for ($i = 1; $i <=12; $i++) {
    ?>
    <option <?php echo $config['log_graceperiod']==$i?'selected="selected"':''; ?> value="<?php echo $i; ?>">
        After&nbsp;<?php echo $i; ?>&nbsp;<?php echo ($i>1)?'Months':'Month'; ?></option>
    <?php
} ?>
</select></div>
        </div>
          
        <div class="row-form clearfix">
            <div class="span3">Password Reset Policy:</div>
            <div class="span9"> <select name="passwd_reset_period">
<option value="0"> &mdash; None &mdash;</option>
<?php
for ($i = 1; $i <= 12; $i++) {
    echo sprintf('<option value="%d" %s>%s%s</option>',
            $i,(($config['passwd_reset_period']==$i)?'selected="selected"':''), $i>1?"Every $i ":'', $i>1?' Months':'Monthly');
}
?>
</select>
&nbsp;<font class="error">&nbsp;<?php echo $errors['passwd_reset_period']; ?></font></div>
        </div>
          
        <div class="row-form clearfix">
            <div class="span3">Bind Staff Session to IP:</div>
            <div class="span9"><input type="checkbox" name="staff_ip_binding" <?php echo $config['staff_ip_binding']?'checked="checked"':''; ?>>
<em>(binds staff session to originating IP address upon login)</em></div>
        </div> 
         
        <div class="row-form clearfix">
            <div class="span3">Staff Excessive Logins:</div>
            <div class="span9"><select name="staff_max_logins">
<?php
for ($i = 1; $i <= 10; $i++) {
    echo sprintf('<option value="%d" %s>%d</option>', $i,(($config['staff_max_logins']==$i)?'selected="selected"':''), $i);
}
?>
</select> failed login attempt(s) allowed before a
<select name="staff_login_timeout">
<?php
for ($i = 1; $i <= 10; $i++) {
    echo sprintf('<option value="%d" %s>%d</option>', $i,(($config['staff_login_timeout']==$i)?'selected="selected"':''), $i);
}
?>
</select> minute lock-out is enforced.</div>
        </div> 
         
        <div class="row-form clearfix">
            <div class="span3">Staff Session Timeout:</div>
            <div class="span9"><input type="text" name="staff_session_timeout" size=6 value="<?php echo $config['staff_session_timeout']; ?>">
Maximum idle time in minutes before a staff member must log in again (enter 0 to disable).</div>
        </div>
          
        <div class="row-form clearfix">
            <div class="span3">Client Excessive Logins:</div>
            <div class="span9"><select name="client_max_logins">
<?php
for ($i = 1; $i <= 10; $i++) {
    echo sprintf('<option value="%d" %s>%d</option>', $i,(($config['client_max_logins']==$i)?'selected="selected"':''), $i);
}

?>
</select> failed login attempt(s) allowed before a
<select name="client_login_timeout">
<?php
for ($i = 1; $i <= 10; $i++) {
    echo sprintf('<option value="%d" %s>%d</option>', $i,(($config['client_login_timeout']==$i)?'selected="selected"':''), $i);
}
?>
</select> minute lock-out is enforced. </div>
        </div>
          
        <div class="row-form clearfix">
            <div class="span3">Client Session Timeout:</div>
            <div class="span9"><input type="text" name="client_session_timeout" size=6 value="<?php echo $config['client_session_timeout']; ?>">
&nbsp;Maximum idle time in minutes before a client must log in again (enter 0 to disable).</div>
        </div>                       
                                 
    </div>

</div>
</div>
<div class="row-fluid">  
<div class="span12">
    <div class="head clearfix">
        <div class="isw-documents"></div>
        <h1> Date and Time Options:  (<em>Please refer to <a href="http://php.net/date" target="_blank">PHP Manual</a> for supported parameters.</em>)</h1>
    </div>
    
    <div class="block-fluid">                        

        <div class="row-form clearfix">
            <div class="span3">Time Format:</div>
            <div class="span9"><input type="text" name="time_format" value="<?php echo $config['time_format']; ?>">
&nbsp;<font class="error">*&nbsp;<?php echo $errors['time_format']; ?></font>
<em><?php echo Format::date($config['time_format'], $gmtime, $config['tz_offset'], $config['enable_daylight_saving']); ?></em></div> 
        </div>
        
        <div class="row-form clearfix">
            <div class="span3">Date Format:</div>
            <div class="span9"><input type="text" name="date_format" value="<?php echo $config['date_format']; ?>">
    &nbsp;<font class="error">*&nbsp;<?php echo $errors['date_format']; ?></font>
    <em><?php echo Format::date($config['date_format'], $gmtime, $config['tz_offset'], $config['enable_daylight_saving']); ?></em></div> 
        </div>
        
        <div class="row-form clearfix">
            <div class="span3">Date &amp; Time Format:</div>
            <div class="span9"><input type="text" name="datetime_format" value="<?php echo $config['datetime_format']; ?>">
    &nbsp;<font class="error">*&nbsp;<?php echo $errors['datetime_format']; ?></font>
    <em><?php echo Format::date($config['datetime_format'], $gmtime, $config['tz_offset'], $config['enable_daylight_saving']); ?></em></div> 
        </div>
        
        <div class="row-form clearfix">
            <div class="span3">Day, Date &amp; Time Format:</div>
            <div class="span9"><input type="text" name="daydatetime_format" value="<?php echo $config['daydatetime_format']; ?>">
    &nbsp;<font class="error">*&nbsp;<?php echo $errors['daydatetime_format']; ?></font>
    <em><?php echo Format::date($config['daydatetime_format'], $gmtime, $config['tz_offset'], $config['enable_daylight_saving']); ?></em></div> 
        </div>
        
        <div class="row-form clearfix">
            <div class="span3">Default Time Zone:</div>
            <div class="span9"><select name="default_timezone_id">
<option value="">&mdash; Select Default Time Zone &mdash;</option>
<?php
$sql='SELECT id, offset,timezone FROM '.TIMEZONE_TABLE.' ORDER BY id';
if(($res=db_query($sql)) && db_num_rows($res)){
    while(list($id, $offset, $tz)=db_fetch_row($res)){
        $sel=($config['default_timezone_id']==$id)?'selected="selected"':'';
        echo sprintf('<option value="%d" %s>GMT %s - %s</option>', $id, $sel, $offset, $tz);
    }
}
?>
</select>
&nbsp;<font class="error">*&nbsp;<?php echo $errors['default_timezone_id']; ?></font></div> 
        </div>	
        
        <div class="row-form clearfix">
            <div class="span3">Daylight Saving:</div>
            <div class="span9"><input type="checkbox" name="enable_daylight_saving" <?php echo $config['enable_daylight_saving'] ? 'checked="checked"': ''; ?>>Observe daylight savings</div> 
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
