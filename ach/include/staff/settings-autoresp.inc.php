<div class="page-header"><h1>Autoresponder <small> Settings</small></h1></div>  
<form action="settings.php?t=autoresp" method="post" id="save">
<?php csrf_token(); ?>
<input type="hidden" name="t" value="autoresp" >     
<div class="row-fluid">
    <div class="span12">
    <div class="head clearfix">
        <div class="isw-documents"></div>
        <h1>Autoresponder Setting: </h1> <em>Global setting - can be disabled at department or email level.</em>
    </div>
    <div class="block-fluid">         
         <div class="row-form clearfix">
                <div class="span3">New Complaint:</div>
                <div class="span3"><input type="radio" name="ticket_autoresponder"  value="1"   <?php echo $config['ticket_autoresponder']?'checked="checked"':''; ?> /><b>Enable</b>

                <input type="radio" name="ticket_autoresponder"  value="0"   <?php echo !$config['ticket_autoresponder']?'checked="checked"':''; ?> />Disable</div>
                <div class="span6">  <em>(Autoresponse includes the Complaint ID required to check status of the Complaint)</em></div>
            </div>
            <div class="row-form clearfix">
                <div class="span3">New Complaint by staff:</div>
                <div class="span3"><input type="radio" name="ticket_notice_active"  value="1"   <?php echo $config['ticket_notice_active']?'checked="checked"':''; ?> /><b>Enable</b>

                <input type="radio" name="ticket_notice_active"  value="0"   <?php echo !$config['ticket_notice_active']?'checked="checked"':''; ?> />Disable</div>
                <div class="span6"> <em>(Notice sent when staff creates a Complaint on behalf of the user (Staff can overwrite))</em></div>
            </div>
            <div class="row-form clearfix">
                <div class="span3">New Message:</div>
                <div class="span3"><input type="radio" name="message_autoresponder"  value="1"   <?php echo $config['message_autoresponder']?'checked="checked"':''; ?> /><b>Enable</b>

                <input type="radio" name="message_autoresponder"  value="0"   <?php echo !$config['message_autoresponder']?'checked="checked"':''; ?> />Disable</div>
                <div class="span6"><em>(Confirmation notice sent when a new message is appended to an existing Complaint)</em></div>
            </div>
            <div class="row-form clearfix">
                <div class="span3">Overlimit notice:</div>
                <div class="span3"><input type="radio" name="overlimit_notice_active"  value="1"   <?php echo $config['overlimit_notice_active']?'checked="checked"':''; ?> /><b>Enable</b>

                <input type="radio" name="overlimit_notice_active"  value="0"   <?php echo !$config['overlimit_notice_active']?'checked="checked"':''; ?> />Disable
</div>
                <div class="span6"><em>(Complaint denied notice sent to user on limit violation. Admin gets alerts on ALL denials by default)</em></div>
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

