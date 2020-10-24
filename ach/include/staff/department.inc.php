<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');
$info=array();
$qstr='';
if($dept && $_REQUEST['a']!='add') {
    //Editing Department.
    $title='Update Department';
    $action='update';
    $submit_text='Save Changes';
    $info=$dept->getInfo();
    $info['id']=$dept->getId();
    $info['groups'] = $dept->getAllowedGroups();

    $qstr.='&id='.$dept->getId();
} else {
    $title='Add New Department';
    $action='create';
    $submit_text='Create Dept';
    $info['ispublic']=isset($info['ispublic'])?$info['ispublic']:1;
	$info['ticket_auto_response']=isset($info['ticket_auto_response'])?$info['ticket_auto_response']:1;
    $info['message_auto_response']=isset($info['message_auto_response'])?$info['message_auto_response']:1;
    $qstr.='&a='.$_REQUEST['a'];
}
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
?>
<form action="departments.php?<?php echo $qstr; ?>" method="post" id="save">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="<?php echo $action; ?>">
 <input type="hidden" name="a" value="<?php echo Format::htmlchars($_REQUEST['a']); ?>">
 <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
  <div class="page-header"><h1>Departments<small>Add</small></h1></div> 
  <div class="row-fluid">
    <!--Left section-->
    <div class="span12" >
        <div class="block-fluid ucard">
            <div class="info">   
            <ul class="rows">
            <li class="heading">
            <div class="isw-users"></div><?php echo $title; ?>&nbsp;<em>(Department Information)</em></li>            
                        <li>
                        <div class="title" >Name:</div>
                        <div class="text"> 
                        <input type="text" size="30" name="name" value="<?php echo $info['name']; ?>">
	           			&nbsp;<span class="error">*&nbsp;<?php echo $errors['name']; ?></span>
                        </div>
                        </li>                        
                        <li>
                        <div class="title" >Type:</div>
                        <div class="text" >                       
                        <input type="radio" name="ispublic" value="1" <?php echo $info['ispublic']?'checked="checked"':''; ?>><strong>Public</strong>
                <input type="radio" name="ispublic" value="0" <?php echo !$info['ispublic']?'checked="checked"':''; ?>><strong>Private</strong> (Internal)
                &nbsp;<span class="error">*&nbsp;</span>
                        
                        </div>
                        </li>   
                        <li>
                        <div class="title">Location Mode:</div>
                        <div class="text">
                        <input type="checkbox" name="islocation" value="1"  <?php echo $info['islocation']?'checked="checked"':''; ?> >Location Mode
               			&nbsp;<span class="error">*&nbsp;<?php echo $errors['islocation']; ?></span>
                        </div>
                        </li>               
                        <li>
                        <div class="title" >Parent Department:</div>
                        <div class="text" >                       
                        <select name="dept_p_id">
                    <option value="">&mdash; Select Parent Department &mdash;</option>
                    <?php
                    $sql='SELECT dept_id, dept_name FROM '.DEPT_TABLE
                        .' WHERE dept_p_id=0 '
                        .' ORDER by dept_name';
                    if(($res=db_query($sql)) && db_num_rows($res)) {
                        while(list($id, $name)=db_fetch_row($res)) {
                  echo sprintf('<option value="%d" %s>%s</option>',$id, (($info['id'] && $id==$info['dept_p_id'])?'selected="selected"':'') ,$name);
                        }
                    }
                    ?>
                </select> (<em>optional</em>)
                &nbsp;<span class="error">&nbsp;<?php echo $errors['dept_p_id']; ?></span>
                        
                        </div>
                        </li>
                        <li>
                        <div class="title" >Email:</div>
                        <div class="text"  ><select name="email_id">
                    <option value="0">&mdash; Select Department Email &mdash;</option>
                    <?php
                    $sql='SELECT email_id,email,name FROM '.EMAIL_TABLE.' email ORDER by name';
                    if(($res=db_query($sql)) && db_num_rows($res)){
                        while(list($id,$email,$name)=db_fetch_row($res)){
                            $selected=($info['email_id'] && $id==$info['email_id'])?'selected="selected"':'';
                            if($name)
                                $email=Format::htmlchars("$name <$email>");
                            echo sprintf('<option value="%d" %s>%s</option>',$id,$selected,$email);
                        }
                    }
                    ?>
                </select>
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['email_id']; ?></span></div>
                        </li>                        
                        <li>
                        <div class="title">Template:</div>
                        <div class="text"  > <select name="tpl_id">
                    <option value="0">&mdash; System default &mdash;</option>
                    <?php
                    $sql='SELECT tpl_id,name FROM '.EMAIL_TEMPLATE_TABLE.' tpl WHERE isactive=1 ORDER by name';
                    if(($res=db_query($sql)) && db_num_rows($res)){
                        while(list($id,$name)=db_fetch_row($res)){
                            $selected=($info['tpl_id'] && $id==$info['tpl_id'])?'selected="selected"':'';
                            echo sprintf('<option value="%d" %s>%s</option>',$id,$selected,$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['tpl_id']; ?></span>
                        </div>
                        </li>                                              
                        <li>
                        <div class="title">SLA:</div>
                        <div class="text">
                         <select name="sla_id">
                    <option value="0">&mdash; System default &mdash;</option>
                    <?php
                    if($slas=SLA::getSLAs()) {
                        foreach($slas as $id =>$name) {
                            echo sprintf('<option value="%d" %s>%s</option>',
                                    $id, ($info['sla_id']==$id)?'selected="selected"':'',$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['sla_id']; ?></span>
                        </div>
                        </li>
                         
                                                
                        <?php if($dept && $dept->getNumUsers()){ ?>                  
                        <li>
                        <div class="title" style="width:171px;">Manager</div>
                        <div class="text"  style="margin-left:198px;">
                        <select name="manager_id">
                    <option value="0">&mdash; None &mdash;</option>
                    <option value="0" disabled="disabled">Select Department Manager (Optional)</option>
                    <?php
                    $sql='SELECT staff_id,CONCAT_WS(", ",lastname, firstname) as name '
                        .' FROM '.STAFF_TABLE.' staff '
                        .' ORDER by name';
                    if(($res=db_query($sql)) && db_num_rows($res)){
                        while(list($id,$name)=db_fetch_row($res)){
                            $selected=($info['manager_id'] && $id==$info['manager_id'])?'selected="selected"':'';
                            echo sprintf('<option value="%d" %s>%s</option>',$id,$selected,$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<span class="error">&nbsp;<?php echo $errors['manager_id']; ?></span>
                        </div>
                        </li> 
                        <?php }?>                          
                        <?php /*?><li>
                        <div class="title">Group Membership:</div>
                        <div class="text" >
                        <input type="checkbox" name="group_membership" value="0" <?php echo $info['group_membership']?'checked="checked"':''; ?> >
                Extend membership to groups with access. <i>(Alerts and  notices will include groups)</i>
                        </div>
                        </li><?php */?>
                        
                     
                        </ul>
             </div>
        </div>
    </div>
    </div>
   <?php /*?> <div class="row-fluid">
    <div class="span6" >
        <div class="block-fluid ucard">
            <div class="info">   
            <ul class="rows">
            <li class="heading">
            <div class="isw-users"></div>Auto Response Settings</li>     
                   <li>
                        <div class="title" >Auto Response Settings</div>
                        <div class="text"> <em>(Overwrite global auto-response settings for Complaints routed to the Dept)</em>
                        </div>
                        </li>
                        <li>
                        <div class="title" >New Complaint:</div>
                        <div class="text"> 
                        <input type="checkbox" name="ticket_auto_response" value="0" <?php echo !$info['ticket_auto_response']?'checked="checked"':''; ?> >																																									  		                <strong>Disable</strong> new Complaint auto-response for this Dept.
                        </div>
                        </li>                                                
                        <li>
                        <div class="title" >New Message:</div>
                        <div class="text" >                       
                        <input type="checkbox" name="message_auto_response" value="0" <?php echo !$info['message_auto_response']?'checked="checked"':''; ?> >
                    <strong>Disable</strong> new message auto-response for this Dept.
                        
                        </div>
                        </li>
                        <li>
                        <div class="title" > Auto Response Email:</div>
                        <div class="text" >                       
                        <select name="autoresp_email_id">
                    <option value=""  disabled="disabled">Select Outgoing  Email</option>
                    <option value="0">&mdash; Department Email (Above) &mdash;</option>
                    <?php
                    $sql='SELECT email_id,email,name FROM '.EMAIL_TABLE.' email ORDER by name';
                    if(($res=db_query($sql)) && db_num_rows($res)){
                        while(list($id,$email,$name)=db_fetch_row($res)){
                            $selected=($info['email_id'] && $id==$info['email_id'])?'selected="selected"':'';
                            if($name)
                                $email=Format::htmlchars("$name <$email>");
                            echo sprintf('<option value="%d" %s>%s</option>',$id,$selected,$email);
                        }
                    }
                    ?>
                </select>
                &nbsp;<span class="error">&nbsp;<?php echo $errors['autoresp_email_id']; ?></span>
                        
                        </div>
                        </li>
                                                                                          
                        </ul>
             </div>
        </div>
    </div>
    <div class="span6" >
        <div class="block-fluid ucard">
            <div class="info">   
            <ul class="rows">
            <li class="heading">
            <div class="isw-users"></div>Department Access<em>(Check all groups allowed to access this department)</em></li>            
                        <li>
                        <div class="title"></div>
                        <div class="text" style="margin-left:20px;"><em>Department manager and primary members will always have access independent of group selection or assignment.</em></div>
                        </li>                                                
                       <?php
         $sql='SELECT group_id, group_name, count(staff.staff_id) as members '
             .' FROM '.GROUP_TABLE.' grp '
             .' LEFT JOIN '.STAFF_TABLE. ' staff USING(group_id) '
             .' GROUP by grp.group_id '
             .' ORDER BY group_name';
         if(($res=db_query($sql)) && db_num_rows($res)){
            while(list($id, $name, $members) = db_fetch_row($res)) {
                if($members>0) 
                    $members=sprintf('<a href="staff.php?a=filter&gid=%d">%d</a>', $id, $members);
                $ck=($info['groups'] && in_array($id,$info['groups']))?'checked="checked"':'';
                echo sprintf('<li>
				<div class="title">&nbsp;</div>
				<div class="text" style="margin-left:20px;">
				<input type="checkbox" name="groups[]" value="%d" %s>%s(%s)</div></li>',
                        $id, $ck, $name, $members);
            }
         }
        ?>
                                                                                          
                        </ul>
             </div>
        </div>
    </div>
    </div><?php */?>
     <div class="row-fluid">
    <div class="span12" >
        <div class="block-fluid ucard">
            <div class="info">   
            <ul class="rows">
            <li class="heading">
            <div class="isw-users"></div>Department Signature<em>(Optional signature used on outgoing emails.)</em></li>            
                        <li>
                        <div class="title">Signature<span class="error">&nbsp;<?php echo $errors['signature']; ?></span></div>
                        <div class="text"> <textarea name="signature" cols="21" rows="5" style="width: 60%;"><?php echo $info['signature']; ?></textarea>
                <br><em>Signature is made available as a choice, for public departments, on Complaint reply.</em>
                        </div>
                        </li>             </ul>
             </div>
        </div>
    </div>
  </div>
  <div class="row-fluid">  
<div class="span12">
    
    <div class="footer tar">
    <input  class="btn" type="submit" name="submit" value="<?php echo $submit_text; ?>">
    <input class="btn" type="reset"  name="reset"  value="Reset">
    <input  class="btn"type="button" name="cancel" value="Cancel" onclick='window.location.href="departments.php"'>
    </div>  

</div>
</div>
</form>
   </div></div>