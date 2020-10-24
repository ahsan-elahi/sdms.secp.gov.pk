<div class="page-header"><h1>Alerts and Notices<small> sent to staff on Complaint "events"</small></h1></div>  
<form action="settings.php?t=alerts" method="post" id="save">
<?php csrf_token(); ?>
<input type="hidden" name="t" value="alerts" >   
<div class="row-fluid">
    <div class="span6">
    	<div class="head clearfix">
        <div class="isw-documents"></div>
        <h1>New Complaint Alert: </h1> <em> Alert sent out on new Complaints</em>
    </div>
   		<div class="block-fluid">         
            <div class="row-form clearfix">
                <div class="span2">Status:</div>
                <div class="span4">
                <input type="radio" name="ticket_alert_active"  value="1"   <?php echo $config['ticket_alert_active']?'checked':''; ?> />Enable

                <input type="radio" name="ticket_alert_active"  value="0"   <?php echo !$config['ticket_alert_active']?'checked':''; ?> />Disable

                &nbsp;&nbsp;<em><font class="error">&nbsp;<?php echo $errors['ticket_alert_active']; ?></font></em></div>
            </div>
            <div class="row-form clearfix">
                <div class="span2"><input type="checkbox" name="ticket_alert_admin" <?php echo $config['ticket_alert_admin']?'checked':''; ?>></div>
                <div class="span4">Admin Email <em>(<?php echo $cfg->getAdminEmail(); ?>)</em></div>
            </div>
            <div class="row-form clearfix">
                <div class="span2"><input type="checkbox" name="ticket_alert_dept_manager" <?php echo $config['ticket_alert_dept_manager']?'checked':''; ?>></div>
                <div class="span4">Department Manager</div>
            </div>
            <div class="row-form clearfix">
                <div class="span2"> <input type="checkbox" name="ticket_alert_dept_members" <?php echo $config['ticket_alert_dept_members']?'checked':''; ?>></div>
                <div class="span4">Department Members <em>(spammy)</em></div>
            </div>                                                    
    </div>                                                    
    </div>    
    <div class="span6">
    	<div class="head clearfix">
        <div class="isw-documents"></div>
        <h1>New Message Alert: </h1> <em> Alert sent out when a new message, from the user, is appended to an existing Complaint</em>
    </div>
    	<div class="block-fluid">         
            <div class="row-form clearfix">
                <div class="span2">Status:</div>
                <div class="span4">
                <input type="radio" name="message_alert_active"  value="1"   <?php echo $config['message_alert_active']?'checked':''; ?> />Enable

              &nbsp;&nbsp;

              <input type="radio" name="message_alert_active"  value="0"   <?php echo !$config['message_alert_active']?'checked':''; ?> />Disable
            </div>
            </div>
            <div class="row-form clearfix">
                <div class="span2"><input type="checkbox" name="message_alert_laststaff" <?php echo $config['message_alert_laststaff']?'checked':''; ?>> </div>
                <div class="span4">Last Respondent</div>
            </div>
            <div class="row-form clearfix">
                <div class="span2"><input type="checkbox" name="message_alert_assigned" <?php echo $config['message_alert_assigned']?'checked':''; ?>></div>
                <div class="span4">Assigned Staff</div>
            </div>
            <div class="row-form clearfix">
                <div class="span2"> <input type="checkbox" name="message_alert_dept_manager" <?php echo $config['message_alert_dept_manager']?'checked':''; ?>> </div>
                <div class="span4">Department Manager <em>(spammy)</em></div>
            </div>                                                    
    </div>                                                    
    </div>
</div>
<div class="row-fluid">    
    <div class="span6">
        <div class="head clearfix">
            <div class="isw-documents"></div>
            <h1>New Internal Note Alert: </h1> <em>  Alert sent out when a new internal note is posted.</em>
        </div>
        <div class="block-fluid">         
                <div class="row-form clearfix">
                    <div class="span2">Status:</div>
                    <div class="span4">
                    <input type="radio" name="note_alert_active"  value="1"   <?php echo $config['note_alert_active']?'checked':''; ?> />Enable
    
                  &nbsp;&nbsp;
    
                  <input type="radio" name="note_alert_active"  value="0"   <?php echo !$config['note_alert_active']?'checked':''; ?> />Disable
    
                  &nbsp;&nbsp;&nbsp;<font class="error">&nbsp;<?php echo $errors['note_alert_active']; ?></font></div>
                </div>
                <div class="row-form clearfix">
                    <div class="span2"><input type="checkbox" name="note_alert_laststaff" <?php echo $config['note_alert_laststaff']?'checked':''; ?>> </div>
                    <div class="span4">Last Respondent</div>
                </div>
                <div class="row-form clearfix">
                    <div class="span2"><input type="checkbox" name="note_alert_assigned" <?php echo $config['note_alert_assigned']?'checked':''; ?>> </div>
                    <div class="span4"> Assigned Staff</div>
                </div>
                <input type="hidden" name="note_alert_dept_manager" <?php echo $config['note_alert_dept_manager']?'checked':''; ?>>
                <!--<div class="row-form clearfix">
                    <div class="span2"> <input type="hidden" name="note_alert_dept_manager" <?php echo $config['note_alert_dept_manager']?'checked':''; ?>> </div>
                    <div class="span4"> Department Manager <em>(spammy)</em></div>
                </div>-->                                                    
        </div>                                                    
    </div>
    <div class="span6">
        <div class="head clearfix">
            <div class="isw-documents"></div>
            <h1>Complaint Assignment Alert: </h1> <em>Alert sent out to staff on Complaint assignment.</em>
        </div>
        <div class="block-fluid">         
                <div class="row-form clearfix">
                    <div class="span2">Status:</div>
                    <div class="span4">
                    <input name="assigned_alert_active" value="1" checked="checked" type="radio">Enable
    
                  &nbsp;&nbsp;
    
                  <input name="assigned_alert_active" value="0" type="radio">Disable
    
                   &nbsp;&nbsp;&nbsp;<font class="error">&nbsp;<?php echo $errors['assigned_alert_active']; ?></font></div>
                </div>
                <div class="row-form clearfix">
                    <div class="span2"><input type="checkbox" name="assigned_alert_staff" <?php echo $config['assigned_alert_staff']?'checked':''; ?>></div>
                    <div class="span4">Assigned Staff</div>
                </div>
                <!--<div class="row-form clearfix">
                    <div class="span2"> <input type="checkbox"name="assigned_alert_team_lead" <?php //echo $config['assigned_alert_team_lead']?'checked':''; ?>></div>
                    <div class="span4"> Team Lead <em>(On team assignment)</em></div>
                </div>
                <div class="row-form clearfix">
                    <div class="span2">  <input type="checkbox"name="assigned_alert_team_members" <?php //echo $config['assigned_alert_team_members']?'checked':''; ?>></div>
                    <div class="span4">  Team Members <em>(spammy)</em></div>
                </div>  -->                                                  
        </div>                                                    
    </div>
</div>
<div class="row-fluid">    
    <div class="span6">
        <div class="head clearfix">
            <div class="isw-documents"></div>
            <h1>Complaint Transfer Alert: </h1> <em>Alert sent out to staff of the target department on Complaint transfer.</em>
        </div>
        <div class="block-fluid">         
                <div class="row-form clearfix">
                    <div class="span2">Status:</div>
                    <div class="span4">
                   <input type="radio" name="transfer_alert_active"  value="1"   <?php echo $config['transfer_alert_active']?'checked':''; ?> />Enable

              <input type="radio" name="transfer_alert_active"  value="0"   <?php echo !$config['transfer_alert_active']?'checked':''; ?> />Disable

              &nbsp;&nbsp;&nbsp;<font class="error">&nbsp;<?php echo $errors['alert_alert_active']; ?></font></div>
                </div>
                <div class="row-form clearfix">
                    <div class="span2"><input type="checkbox" name="transfer_alert_assigned" <?php echo $config['transfer_alert_assigned']?'checked':''; ?>> </div>
                    <div class="span4">Assigned Staff/Team</div>
                </div>
                <div class="row-form clearfix">
                    <div class="span2"><input type="checkbox" name="transfer_alert_dept_manager" <?php echo $config['transfer_alert_dept_manager']?'checked':''; ?>> </div>
                    <div class="span4">Department Manager</div>
                </div>
                <div class="row-form clearfix">
                    <div class="span2">  <input type="checkbox" name="transfer_alert_dept_members" <?php echo $config['transfer_alert_dept_members']?'checked':''; ?>></div>
                    <div class="span4">Department Members <em>(spammy)</em></div>
                </div>                                                    
        </div>                                                    
        </div>
    <div class="span6">
        <div class="head clearfix">
            <div class="isw-documents"></div>
            <h1>Overdue Complaint Alert: </h1> <em> Alert sent out when a Complaint becomes overdue - admin email gets an alert by default.</em>
        </div>
        <div class="block-fluid">         
                <div class="row-form clearfix">
                    <div class="span2">Status:</div>
                    <div class="span4">
                    <input type="radio" name="overdue_alert_active"  value="1"   <?php echo $config['overdue_alert_active']?'checked':''; ?> />Enable

              <input type="radio" name="overdue_alert_active"  value="0"   <?php echo !$config['overdue_alert_active']?'checked':''; ?> />Disable

              &nbsp;&nbsp;&nbsp;<font class="error">&nbsp;<?php echo $errors['overdue_alert_active']; ?></font></div>
                </div>
                <div class="row-form clearfix">
                    <div class="span2"><input type="checkbox" name="overdue_alert_assigned" <?php echo $config['overdue_alert_assigned']?'checked':''; ?>> </div>
                    <div class="span4">Assigned Staff/Team</div>
                </div>
                <div class="row-form clearfix">
                    <div class="span2"> <input type="checkbox" name="overdue_alert_dept_manager" <?php echo $config['overdue_alert_dept_manager']?'checked':''; ?>> </div>
                    <div class="span4">Department Manager</div>
                </div>
                <div class="row-form clearfix">
                    <div class="span2"> <input type="checkbox" name="overdue_alert_dept_members" <?php echo $config['overdue_alert_dept_members']?'checked':''; ?>> </div>
                    <div class="span4">Department Members <em>(spammy)</em></div>
                </div>                                                    
        </div>                                                    
        </div>
</div>
<div class="row-fluid">        
    <div class="span6">
        <div class="head clearfix">
            <div class="isw-documents"></div>
            <h1>System Alerts: </h1> <em>Enabled by default. Errors are sent to system admin email (<?php echo $cfg->getAdminEmail(); ?>)</em>
        </div>
        <div class="block-fluid">         
                
                <div class="row-form clearfix">
                    <div class="span2"> <input type="checkbox" name="send_sys_errors" checked="checked" disabled="disabled"> </div>
                    <div class="span4">System Errors</div>
                </div>
                <div class="row-form clearfix">
                    <div class="span2"><input type="checkbox" name="send_sql_errors" <?php echo $config['send_sql_errors']?'checked':''; ?>></div>
                    <div class="span4"> SQL errors</div>
                </div>
                <div class="row-form clearfix">
                    <div class="span2"> <input type="checkbox" name="send_login_errors" <?php echo $config['send_login_errors']?'checked':''; ?>></div>
                    <div class="span4">Excessive Login attempts</div>
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
</div>
</form>
<div class="dr"><span></span></div>
</div></div>
