<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');
$info=array();
$qstr='';
if($email && $_REQUEST['a']!='add'){
    $title='Update Email';
    $action='update';
    $submit_text='Save Changes';
    $info=$email->getInfo();
    $info['id']=$email->getId();
    if($info['mail_delete'])
        $info['postfetch']='delete';
    elseif($info['mail_archivefolder'])
        $info['postfetch']='archive';
    else
        $info['postfetch']=''; //nothing.
    if($info['userpass'])
        $passwdtxt='To change password enter new password above.';

    $qstr.='&id='.$email->getId();
}else {
    $title='Add New Email';
    $action='create';
    $submit_text='Submit';
    $info['ispublic']=isset($info['ispublic'])?$info['ispublic']:1;
    $info['ticket_auto_response']=isset($info['ticket_auto_response'])?$info['ticket_auto_response']:1;
    $info['message_auto_response']=isset($info['message_auto_response'])?$info['message_auto_response']:1;
    $qstr.='&a='.$_REQUEST['a'];
}
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
?>

<div class="page-header"><h1>Email  <small>Address </small></h1></div> 
<form action="emails.php?<?php echo $qstr; ?>" method="post" id="save">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="<?php echo $action; ?>">
 <input type="hidden" name="a" value="<?php echo Format::htmlchars($_REQUEST['a']); ?>">
 <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
 <div class="row-fluid">
 <div class="row-fluid">
 <!--Left section-->
<div class="span6">
<div class="block-fluid ucard">
<div class="info">   
<ul class="rows">
<li class="heading"><div class="isw-users"></div><?php echo $title; ?>&nbsp;&nbsp;&nbsp;<em style="font-size:8px;">(Login details are optional BUT required when IMAP/POP or SMTP are enabled.)</em></li>
<li>
                        <div class="title">Email Address</div>
                        <div class="text"><input type="text" size="35" name="email" value="<?php echo $info['email']; ?>">
                &nbsp;<span class="error"> &nbsp;<span class="error">*&nbsp;<?php echo $errors['email']; ?></span></div>
                    </li> 
                    <li>
                        <div class="title">Email Name</div>
                        <div class="text"> <input type="text" size="35" name="name" value="<?php echo $info['name']; ?>">
                &nbsp;<span class="error"> &nbsp;<span class="error">*&nbsp;<?php echo $errors['name']; ?>&nbsp;</span></div>
                    </li> 
                     <li>
                        <div class="title">Login Username</div>
                        <div class="text"><input type="text" size="35" name="userid" value="<?php echo $info['userid']; ?>">
                &nbsp;<span class="error"> &nbsp;<span class="error">&nbsp;<?php echo $errors['userid']; ?>&nbsp;</span></div>
                    </li> 
                     <li>
                        <div class="title">Login Password</div>
                        <div class="text"><input type="password" size="35" name="passwd" value="<?php echo $info['passwd']; ?>">
                &nbsp;<span class="error">&nbsp;<span class="error">&nbsp;<?php echo $errors['passwd']; ?>&nbsp;</span>
                <br><em><?php echo $passwdtxt; ?></em>
                </div>
                    </li> 
                                        
                </ul>                                                      
           </div>                        
        </div>
</div>
<!--Right section-->

<div class="span6">
    <div class="block-fluid ucard">
       <div class="info">
         <ul class="rows">
                    <li class="heading"><div class="isw-users"></div>Mail Account:<em style="font-size:11px;"> (Optional setting for fetching incoming emails.) <!--Mail fetching must be enabled with autocron active or external cron setup.--></em>&nbsp;<?php echo $errors['mail']; ?></li>
<li>
                        <div class="title">Status</div>
                        <div class="text"> <label><input type="radio" name="mail_active"  value="1"   <?php echo $info['mail_active']?'checked="checked"':''; ?> /><strong>Enable</strong></label>
                &nbsp;&nbsp;
                <label><input type="radio" name="mail_active"  value="0"   <?php echo !$info['mail_active']?'checked="checked"':''; ?> />Disable</label>
                &nbsp;<span class="error"> &nbsp;<span class="error">&nbsp;<?php echo $errors['mail_active']; ?>&nbsp;</span></div>
                    </li> 


                <li>
                        <div class="title">Host</div>
                        <div class="text"><input type="text" name="mail_host" size=35 value="<?php echo $info['mail_host']; ?>">
                &nbsp;<span class="error"> &nbsp;<span class="error">&nbsp;<?php echo $errors['mail_host']; ?></span></div>
                    </li> 
                    
                     <li>
                        <div class="title">Port</div>
                        <div class="text"><input type="text" name="mail_port" size=6 value="<?php echo $info['mail_port']?$info['mail_port']:''; ?>">
                 &nbsp;<span class="error">&nbsp;<?php echo $errors['mail_port']; ?></span></div>
                    </li> 
                    
                    <li>
                        <div class="title">Protocol</div>
                        <div class="text"><select name="mail_protocol">
                    <option value='POP'>&mdash; Select Mail Protocol &mdash;</option>
                    <option value='POP' <?php echo ($info['mail_protocol']=='POP')?'selected="selected"':''; ?> >POP</option>
                    <option value='IMAP' <?php echo ($info['mail_protocol']=='IMAP')?'selected="selected"':''; ?> >IMAP</option>
                </select>
                &nbsp;<span class="error">&nbsp;<?php echo $errors['mail_protocol']; ?></span></div>
                    </li> 
        <li>
        <div class="title">Encryption</div>
                        <div class="text"><select name="mail_encryption">
                    <option value='NONE'>None</option>
                    <option value='SSL' <?php echo ($info['mail_encryption']=='SSL')?'selected="selected"':''; ?> >SSL</option>
                </select>
                &nbsp;<span class="error">&nbsp;<?php echo $errors['mail_encryption']; ?></span></div>
                    </li> 
        
       
 <li>
                        <div class="title">Fetch Frequency</div>
                        <div class="text"> <input type="text" name="mail_fetchfreq" size=4 value="<?php echo $info['mail_fetchfreq']?$info['mail_fetchfreq']:''; ?>"><br /> Delay intervals in minutes
                 &nbsp;<span class="error">&nbsp;<?php echo $errors['mail_fetchfreq']; ?></span></div>
                    </li> 
                    <li>
                     <div class="title">Emails Per Fetch</div>
                        <div class="text"> <input type="text" name="mail_fetchmax" size=4 value="<?php echo $info['mail_fetchmax']?$info['mail_fetchmax']:''; ?>"> <br />Maximum emails to process per fetch.
                 &nbsp;<span class="error">&nbsp;<?php echo $errors['mail_fetchmax']; ?></span></div>
                    </li> 
                    
 <li>
                     <div class="title">New Complaint Priority:</div>
                        <div class="text"><select name="priority_id">
                    <option value="">&mdash; Select Priority &mdash;</option>
                    <?php
                    $sql='SELECT priority_id,priority_desc FROM '.PRIORITY_TABLE.' pri ORDER by priority_urgency DESC';
                    if(($res=db_query($sql)) && db_num_rows($res)){
                        while(list($id,$name)=db_fetch_row($res)){
                            $selected=($info['priority_id'] && $id==$info['priority_id'])?'selected="selected"':'';
                            echo sprintf('<option value="%d" %s>%s</option>',$id,$selected,$name);
                        }
                    }
                    ?>
                </select>
                 &nbsp;<span class="error"><?php echo $errors['priority_id']; ?></span></div>
                    </li>             
            
            
            <li>
                     <div class="title">New Complaint Dept.</div>
                        <div class="text"><select name="dept_id">
                    <option value="">&mdash; Select Department &mdash;</option>
                    <?php
                    $sql='SELECT dept_id,dept_name FROM '.DEPT_TABLE.' dept ORDER by dept_name';
                    if(($res=db_query($sql)) && db_num_rows($res)){
                        while(list($id,$name)=db_fetch_row($res)){
                            $selected=($info['dept_id'] && $id==$info['dept_id'])?'selected="selected"':'';
                            echo sprintf('<option value="%d" %s>%s</option>',$id,$selected,$name);
                        }
                    }
                    ?>
                </select>
                 &nbsp;<span class="error"><?php echo $errors['dept_id']; ?></span></div>
                    </li>      
            
            
             <li>
                        <div class="title"> Auto-response</div>
                        <div class="text"> <input type="checkbox" name="noautoresp" value="1" <?php echo $info['noautoresp']?'checked="checked"':''; ?> >
                <strong>Disable</strong><br /> new Complaint auto-response for this email. Overwrite global and dept. settings.</div>
                    </li> 
                    
                     <li>
                        <div class="title">Fetched Emails</div>
                        <div class="text">
                        <label class="checkbox inline"><input type="radio" name="postfetch" value="archive" <?php echo ($info['postfetch']=='archive')? 'checked="checked"': ''; ?> ></label>
                 Move to: <input type="text" name="mail_archivefolder" size="20" value="<?php echo $info['mail_archivefolder']; ?>"/> folder.
                    &nbsp;<font class="error">&nbsp;<?php echo $errors['mail_folder']; ?></font>
                <label class="checkbox inline"><input type="radio" name="postfetch" value="delete" <?php echo ($info['postfetch']=='delete')? 'checked="checked"': ''; ?>></label>
                Delete fetched emails
                <label class="checkbox inline"><input type="radio" name="postfetch" value="" <?php echo (isset($info['postfetch']) && !$info['postfetch'])? 'checked="checked"': ''; ?> ></label>
                 Do nothing (Not recommended)
                 <br><em>Moving fetched emails to a backup folder is highly recommended.</em>
                 &nbsp;<span class="error"><?php echo $errors['postfetch']; ?></span>
                 </div>
                    </li> 
              </ul></div></div></div>
            
       
       
       <!--Left section-->
<div class="span6" style="float:left; margin-left:1px; margin-top:-555px;">
<div class="block-fluid ucard">
<div class="info">   
<ul class="rows">
<li class="heading"><div class="isw-users"></div>SMTP Settings &nbsp;<em style="font-size:8px;">:<!-- When enabled the <b>email account</b> will use SMTP server instead of internal PHP mail() function for outgoing emails.--><?php echo $errors['smtp']; ?></em></li>
       
       <li>
                        <div class="title">Status</div>
                        <div class="text"><label><input type="radio" name="smtp_active"  value="1"   <?php echo $info['smtp_active']?'checked':''; ?> />Enable</label>
                <label><input type="radio" name="smtp_active"  value="0"   <?php echo !$info['smtp_active']?'checked':''; ?> />Disable</label>
                &nbsp;<span class="error">&nbsp;<?php echo $errors['smtp_active']; ?> </span></div>
                    </li>   
       
        
        
         <li>
                        <div class="title">SMTP Host</div>
                        <div class="text"><input type="text" name="smtp_host" size=35 value="<?php echo $info['smtp_host']; ?>">
               
                &nbsp;<span class="error">&nbsp;<?php echo $errors['smtp_host']; ?> </span></div>
                    </li> 
       
       <li>
                        <div class="title">SMTP Port</div>
                        <div class="text"><input type="text" name="smtp_port" size=6 value="<?php echo $info['smtp_port']?$info['smtp_port']:''; ?>">
               
                &nbsp;<span class="error">&nbsp;<?php echo $errors['smtp_port']; ?> </span></div>
                    </li> 
                    
                    
                     <li>
                        <div class="title">Authentication Required?</div>
                        <div class="text"><label><input type="radio" name="smtp_auth"  value="1"
                    <?php echo $info['smtp_auth']?'checked':''; ?> />Yes</label>
                 <label><input type="radio" name="smtp_auth"  value="0"
                    <?php echo !$info['smtp_auth']?'checked':''; ?> />NO</label>
               
                &nbsp;<span class="error">&nbsp;<?php echo $errors['smtp_auth']; ?> </span></div>
                    </li> 
                    
                    <li>
                        <div class="title">Allow Header Spoofing?</div>
                        <div class="text"> <input type="checkbox" name="smtp_spoofing" value="1" <?php echo $info['smtp_spoofing'] ?'checked="checked"':''; ?>><br />
               
                Allow email header spoofing <em>(only applies to emails being sent through this account)</em></div>
                    </li> 
         <li>
                        <div class="title">Internal Notes</div>
                        <div class="text"> Admin's notes.<br />
               
               <span class="error">&nbsp;<?php echo $errors['notes']; ?></span></em></div>
                    </li> 
                     <li>
                        <div class="title"></div>
                        <div class="text"> <textarea name="notes" cols="21" rows="5" style="width: 60%;"><?php echo $info['notes']; ?></textarea></div>
                    </li> 
                    </ul></div></div></div></div></div></div></div>
        
        
 
<p style="text-align:center;">
    <input type="submit" class="btn" name="submit" value="<?php echo $submit_text; ?>">
    <input type="reset" class="btn" name="reset"  value="Reset">
    <input type="button" class="btn" name="cancel" value="Cancel" onclick='window.location.href="emails.php"'>
</p>
</form>
