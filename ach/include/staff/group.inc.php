<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');
$info=array();
$qstr='';
if($group && $_REQUEST['a']!='add'){
    $title='Update Group';
    $action='update';
    $submit_text='Save Changes';
    $info=$group->getInfo();
    $info['id']=$group->getId();
    $info['depts']=$group->getDepartments();
    $qstr.='&id='.$group->getId();
}else {
    $title='Add New Group';
    $action='create';
    $submit_text='Create Group';
    $info['isactive']=isset($info['isactive'])?$info['isactive']:1;
    $info['can_create_tickets']=isset($info['can_create_tickets'])?$info['can_create_tickets']:1;
    $qstr.='&a='.$_REQUEST['a'];
}
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
?>
<form action="groups.php?<?php echo $qstr; ?>" method="post" id="save" name="group">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="<?php echo $action; ?>">
 <input type="hidden" name="a" value="<?php echo Format::htmlchars($_REQUEST['a']); ?>">
 <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
 <div class="page-header"><h1>User<small>Group</small></h1></div> 
 <div class="row-fluid">
<!--Left section-->
<div class="span12" >
<div class="block-fluid ucard">
<div class="info">   
<ul class="rows">
<li class="heading">
<div class="isw-users"></div><?php echo $title; ?>&nbsp;<em>(Group Information: Disabled group will limit staff members access. Admins are exempted.)</em></li>


            <li>
            <div class="title" >  Name::</div>
            <div class="text"><input type="text" size="30" name="name" value="<?php echo $info['name']; ?>">
            &nbsp;<span class="error">*&nbsp;<?php echo $errors['name']; ?></span>
            </div>
            </li>
            
            <li>
            <div class="title" >Status:</div>
            <div class="text" >
                 
            
            
            <label class="checkbox inline"><input type="radio" name="isactive" value="1" <?php echo $info['isactive']?'checked="checked"':''; ?>>Active</label>
            
            <label class="checkbox inline"><input type="radio" name="isactive" value="0" <?php echo !$info['isactive']?'checked="checked"':''; ?>>Disabled</label>
            
            &nbsp;<span class="error">*&nbsp;<?php echo $errors['status']; ?></span>
            
            </div>
            </li>
            
            
            <li>
            <div class="title" ></div>
            <div class="text"  ><em><strong>Group Permissions</strong>: Applies to all group members&nbsp;</em>
            </div>
            </li>
            <li>
            <div class="title" style="width:171px;">Can <b>Create</b> Complaints</div>
            <div class="text"  style="margin-left:198px;"> 
            <label class="checkbox inline"><input type="radio" name="can_create_tickets"  value="1"   <?php echo $info['can_create_tickets']?'checked="checked"':''; ?> />Yes</label>
            &nbsp;&nbsp;
            <label class="checkbox inline"><input type="radio" name="can_create_tickets"  value="0"   <?php echo !$info['can_create_tickets']?'checked="checked"':''; ?> />No</label>
            &nbsp;&nbsp;<i>Ability to open Complaints on behalf of clients.</i>
            </div>
            </li>    
            
            <li>
            <div class="title" style="width:171px;">Can <b>Edit</b> Complaints</div>
            <div class="text"  style="margin-left:198px;">
            <label class="checkbox inline"><input type="radio" name="can_edit_tickets"  value="1"   <?php echo $info['can_edit_tickets']?'checked="checked"':''; ?> />Yes</label>
            &nbsp;&nbsp;
            <label class="checkbox inline"><input type="radio" name="can_edit_tickets"  value="0"   <?php echo !$info['can_edit_tickets']?'checked="checked"':''; ?> />No</label>
            &nbsp;&nbsp;<i>Ability to edit Complaints.</i>
            </div>
            </li>    
            
            <li>
            <div class="title" style="width:171px;">Can <b>Post Reply</b></div>
            <div class="text"  style="margin-left:198px;">
            <label class="checkbox inline"><input type="radio" name="can_post_ticket_reply"  value="1"   <?php echo $info['can_post_ticket_reply']?'checked="checked"':''; ?> />Yes</label>
            &nbsp;&nbsp;
            <label class="checkbox inline"><input type="radio" name="can_post_ticket_reply"  value="0"   <?php echo !$info['can_post_ticket_reply']?'checked="checked"':''; ?> />No</label>
            &nbsp;&nbsp;<i>Ability to post a Complaint reply.</i>
            </div>
            </li>  
            
            <li>
            <div class="title" style="width:171px;">Can <b>Close</b> Complaints</div>
            <div class="text"  style="margin-left:198px;">
            <label class="checkbox inline"><input type="radio" name="can_close_tickets"  value="1" <?php echo $info['can_close_tickets']?'checked="checked"':''; ?> />Yes</label>
            &nbsp;&nbsp;
            <label class="checkbox inline"><input type="radio" name="can_close_tickets"  value="0" <?php echo !$info['can_close_tickets']?'checked="checked"':''; ?> />No</label>
            &nbsp;&nbsp;<i>Ability to close Complaints. Staff can still post a response.</i>
            </div>
            </li>    
            
            <li>
            <div class="title" style="width:171px;">Can <b>Assign</b> Complaints</div>
            <div class="text"  style="margin-left:198px;"> 
            <label class="checkbox inline"><input type="radio" name="can_assign_tickets"  value="1" <?php echo $info['can_assign_tickets']?'checked="checked"':''; ?> />Yes</label>
            &nbsp;&nbsp;
            <label class="checkbox inline"><input type="radio" name="can_assign_tickets"  value="0" <?php echo !$info['can_assign_tickets']?'checked="checked"':''; ?> />No</label>
            &nbsp;&nbsp;<i>Ability to assign Complaints to staff members.</i>
            </div>
            </li>    
            
            
            <li>
            <div class="title" style="width:171px;">Can <b>Transfer</b> Complaints</div>
            <div class="text"  style="margin-left:198px;">
            <label class="checkbox inline"><input type="radio" name="can_transfer_tickets"  value="1" <?php echo $info['can_transfer_tickets']?'checked="checked"':''; ?> />Yes</label>
            &nbsp;&nbsp;
            <label class="checkbox inline"><input type="radio" name="can_transfer_tickets"  value="0" <?php echo !$info['can_transfer_tickets']?'checked="checked"':''; ?> />No</label>
            &nbsp;&nbsp;<i>Ability to transfer Complaints between departments.</i>
            </div>
            </li> 
            
            <li>
            <div class="title" style="width:171px;">Can <b>Delete</b> Complaints</div>
            <div class="text"  style="margin-left:198px;"> 
            <label class="checkbox inline"><input type="radio" name="can_delete_tickets"  value="1"   <?php echo $info['can_delete_tickets']?'checked="checked"':''; ?> />Yes</label>
            &nbsp;&nbsp;
            <label class="checkbox inline"><input type="radio" name="can_delete_tickets"  value="0"   <?php echo !$info['can_delete_tickets']?'checked="checked"':''; ?> />No</label>
            &nbsp;&nbsp;<i>Ability to delete Complaints (Deleted Complaints can't be recovered!)</i>
            </div>
            </li>
            
            <li>
            <div class="title" style="width:171px;">Can Ban Emails</div>
            <div class="text"  style="margin-left:198px;"> 
            <label class="checkbox inline"><input type="radio" name="can_ban_emails"  value="1" <?php echo $info['can_ban_emails']?'checked="checked"':''; ?> />Yes</label>
            &nbsp;&nbsp;
            <label class="checkbox inline"><input type="radio" name="can_ban_emails"  value="0" <?php echo !$info['can_ban_emails']?'checked="checked"':''; ?> />No</label>
            &nbsp;&nbsp;<i>Ability to add/remove emails from banlist via Complaint interface.</i>
            </div>
            </li>  
            
            <li>
            <div class="title" style="width:171px;">Can Manage Premade</div>
            <div class="text"  style="margin-left:198px;">
            <label class="checkbox inline"><input type="radio" name="can_manage_premade"  value="1" <?php echo $info['can_manage_premade']?'checked="checked"':''; ?> />Yes</label>
            &nbsp;&nbsp;
            <label class="checkbox inline"><input type="radio" name="can_manage_premade"  value="0" <?php echo !$info['can_manage_premade']?'checked="checked"':''; ?> />No</label>
            &nbsp;&nbsp;<i>Ability to add/update/disable/delete canned responses and attachments.</i>
            </div>
            </li>  
            
            <li>
            <div class="title" style="width:171px;">Can Manage FAQ</div>
            <div class="text"  style="margin-left:198px;">
            <label class="checkbox inline"><input type="radio" name="can_manage_faq"  value="1" <?php echo $info['can_manage_faq']?'checked="checked"':''; ?> />Yes</label>
            &nbsp;&nbsp;
            <label class="checkbox inline"><input type="radio" name="can_manage_faq"  value="0" <?php echo !$info['can_manage_faq']?'checked="checked"':''; ?> />No</label>
            &nbsp;&nbsp;<i>Ability to add/update/disable/delete knowledgebase categories and FAQs.</i>
            </div>
            </li>
            <li>
            <div class="title" style="width:171px;">Can View Staff Stats.</div>
            <div class="text"  style="margin-left:198px;">
            <label class="checkbox inline"><input type="radio" name="can_view_staff_stats"  value="1" <?php echo $info['can_view_staff_stats']?'checked="checked"':''; ?> />Yes</label>
            &nbsp;&nbsp;
            <label class="checkbox inline"><input type="radio" name="can_view_staff_stats"  value="0" <?php echo !$info['can_view_staff_stats']?'checked="checked"':''; ?> />No</label>
            &nbsp;&nbsp;<i>Ability to view stats of other staff members in allowed departments.</i>
            </div>
            </li>
             
            <li>
            <div class="title" style="width:171px;">Department Access</div>
            <div class="text"  style="margin-left:198px;">
             Check all departments the group members are allowed to access.&nbsp;&nbsp;&nbsp;
            <!--<a id="selectAll" href="#deptckb">Select All</a>&nbsp;&nbsp;<a id="selectNone" href="#deptckb">Select None</a>&nbsp;&nbsp;-->
            </div>
            </li>
            
            <li>
            <div class="title" style="width:171px;"></div>
            <div class="text"  style="margin-left:198px;">
            <?php
            $sql='SELECT dept_id,dept_name FROM '.DEPT_TABLE.' ORDER BY dept_name';
            if(($res=db_query($sql)) && db_num_rows($res)){
            while(list($id,$name) = db_fetch_row($res)){
            $ck=($info['depts'] && in_array($id,$info['depts']))?'checked="checked"':'';
            echo sprintf('<input type="checkbox" class="deptckb" name="depts[]" value="%d" %s>%s',$id,$ck,$name);
            }
            }
            ?>

            </div>
            </li>
            <li>
            <div class="title" style="width:171px;">Admin Notes</div>
            <div class="text"  style="margin-left:198px;">Internal notes viewable by all admins.
            </div>
            </li>  
            <li>
            <div class="title" style="width:171px;"></div>
            <div class="text"  style="margin-left:198px;"><textarea name="notes" cols="21" rows="8" style="width: 80%;"><?php echo $info['notes']; ?></textarea>
            </div>
            </li>         
            </ul></div></div></div>
            </div>
     
     <div class="row-fluid">  
<div class="span12">
    
    <div class="footer tar">
    <input  class="btn" type="submit" name="submit" value="<?php echo $submit_text; ?>">
    <input class="btn" type="reset"  name="reset"  value="Reset">
    <input  class="btn"type="button" name="cancel" value="Cancel" onclick='window.location.href="groups.php"'>
    </div>  

</div>
</div>    

</form>
   </div></div>