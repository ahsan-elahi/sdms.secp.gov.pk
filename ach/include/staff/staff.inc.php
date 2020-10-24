<script type="text/javascript">
$( document ).ready(function() {
});
function get_staff_locaiotn(locaiotn_type)
{
			
$.ajax({
	url:"get_staff_locaiton.php",
	data: "locaiotn_type="+locaiotn_type,
	success: function(msg){
	$("#show_staff_locations").html(msg);
}
});
}
function get_staff_parents(locaiotn_id)
{
			
$.ajax({
	url:"get_staff_locaiton.php",
	data: "locaiotn_id="+locaiotn_id,
	success: function(msg){
	$("#show_staff_locations").html(msg);
}
});
}
function getparentstaff(staff_location)
{	
$.ajax({
	url:"get_parent_staff_list.php",
	data: "staff_location="+staff_location,
	success: function(msg){
	$("#show_staff_parents").html(msg);
}
});
}
function get_Sub_Com(comp_type)
{
$.ajax({
	url:"get_sub_department.php",
	data: "m_comp_type="+comp_type,
	success: function(msg){
	$("#show_sub_department").html(msg);
}
});
}

function check_islocation(dept_id)
{
	$('#region_section').html('');
	$.ajax({
url:"check_dept_region.php",
data: "dept_id="+dept_id,
success: function(msg){
	$('#region_section').html(msg);
	}
});
}
/*
*/
function OnChangeCheckbox (checkbox) {
	
if (checkbox.checked) {
var e = document.getElementById("dept_id");
var strUser = e.options[e.selectedIndex].value;
$.ajax({
url:"check_focal_person.php",
data: "dept_id="+strUser,
success: function(msg){
var staff_check = msg.trim();
if(staff_check==0){		
}else{
alert(staff_check);
//$('#focal_person').html('');
//$('#isfocal_test').prop('checked', false);
//checkbox.checked = false; 
}
}
});
}
else {
//alert ("The check box is not checked.");
}
}
</script>
<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');

$info=array();
$qstr='';
if($staff && $_REQUEST['a']!='add'){
    //Editing Department.
    $title='Update Staff';
    $action='update';
    $submit_text='Save Changes';
    $passwd_text='To reset the password enter a new one below';
    $info=$staff->getInfo();
    $info['id']=$staff->getId();
	
    $info['teams'] = $staff->getTeams();
    $qstr.='&id='.$staff->getId();
}else {
    $title='Add New Staff';
    $action='create';
    $submit_text='Add Staff';
    $passwd_text='Temp. password required &nbsp;<span class="error">&nbsp;*</span>';
    //Some defaults for new staff.
    $info['change_passwd']=1;
    $info['isactive']=1;
    $info['isvisible']=1;
    $info['isadmin']=0; 
    $qstr.='&a=add';
}
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
?>
<div class="page-header"><h1>Staff <small>Account</small></h1></div> 
<form action="staff.php?<?php echo $qstr; ?>" method="post" id="save" autocomplete="off">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="<?php echo $action; ?>">
 <input type="hidden" name="a" value="<?php echo Format::htmlchars($_REQUEST['a']); ?>">
 <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
  <!--Upper section-->
<div class="row-fluid">
    <div class="span6">
        <div class="block-fluid ucard">
            <div class="info">   
                <ul class="rows">
                    <li class="heading">
                    <div class="isw-users"></div><?php echo $title; ?>&nbsp;&nbsp;&nbsp;<em>(User Information.)</em></li>
                    <li>
                    <div class="title"> Username:</div>
                    <div class="text"><input type="text" size="30" name="username" value="<?php echo $info['username']; ?>">
                    &nbsp;<span class="error">*&nbsp;<?php echo $errors['username']; ?></span>
                    </div>
                    </li> 
                    <li>
                    <div class="title">First Name:</div>
                    <div class="text"> <input type="text" size="30" name="firstname" value="<?php echo $info['firstname']; ?>">
                    &nbsp;<span class="error">*&nbsp;<?php echo $errors['firstname']; ?></span>
                    </div>
                    </li>
                    <li>
                    <div class="title"> Last Name:</div>
                    <div class="text"><input type="text" size="30" name="lastname" value="<?php echo $info['lastname']; ?>">
                    &nbsp;<span class="error">*&nbsp;<?php echo $errors['lastname']; ?></span>
                    </div>
                    </li>
                    <li>
                    <div class="title">  Email Address:</div>
                    <div class="text"><input type="text" size="30" name="email" value="<?php echo $info['email']; ?>">
                    &nbsp;<span class="error">*&nbsp;<?php echo $errors['email']; ?></span>
                    </div>
                    </li> 
                    <li>
                    <div class="title"> Phone Number:</div>
                    <div class="text"><input type="text" size="18" name="phone" value="<?php echo $info['phone']; ?>">
                    &nbsp;<span class="error">&nbsp;<?php echo $errors['phone']; ?></span><br />
                    
                    </div>
                    </li> 
                    <li>
                    <div class="title"> Extention</div>
                    <div class="text"> <input type="text" size="5" name="extention" value="<?php echo $info['extention']; ?>">
                    &nbsp;<span class="error">&nbsp;<?php echo $errors['extention']; ?></span>
                    </div>
                    </li>
                    <li>
                    <div class="title">  Mobile Number:</div>
                    <div class="text"><input type="text" size="18" name="mobile" value="<?php echo $info['mobile']; ?>">
                    &nbsp;<span class="error">&nbsp;<?php echo $errors['mobile']; ?></span>
                    </div>
                    </li>
                 </ul>
            </div>        
       </div>
    </div>
    <div class="span6">
        <div class="block-fluid ucard">
            <div class="info">   
                <ul class="rows">
                    <li class="heading">
                    <div class="isw-users"></div>Account Password&nbsp;&nbsp;&nbsp;<em>(<?php echo $passwd_text; ?> )</em></li> 
                    <li>
                    <div class="title"> Password:</div>
                    <div class="text"> <input type="password" size="18" name="passwd1" value="<?php echo $info['passwd1']; ?>">
                    &nbsp;<span class="error">&nbsp;<?php echo $errors['passwd1']; ?></span>
                    </div>
                    </li> 
                    <li>
                    <div class="title"> Confirm Password:</div>
                    <div class="text"> <input type="password" size="18" name="passwd2" value="<?php echo $info['passwd2']; ?>">
                    &nbsp;<span class="error">&nbsp;<?php echo $errors['passwd2']; ?></span>
                    </div>
                    </li> 
                    <li>
                    <div class="title" style="width:153px;">Forced Password Change:</div>
                    <div class="text" style="margin-left:165px;"><input type="checkbox" name="change_passwd" value="0" <?php echo $info['change_passwd']?'checked="checked"':''; ?>>
                    <strong>Force</strong> password change on next login.
                    </div>
                    </li> 
                    <li>
                    <div class="title"><em><strong>Staff's Signature</strong></em></div>
                    <div class="text"> Optional signature used on outgoing emails.&nbsp;<span class="error">&nbsp;<?php echo $errors['signature']; ?></span>
                    </div>
                    </li> 
                    <li>
                    <div class="title"></div>
                    <div class="text"> <textarea name="signature" cols="15" rows="4" style="width: 90%;"><?php echo $info['signature']; ?></textarea><br />  <br><em>Signature is made available as a choice, on ticket reply.</em>
                    </div>
                    </li> 
                </ul>
            </div>
        </div>
    </div>
</div>        
<!--Down section-->
<div class="row-fluid">
<div class="span6">
    <div class="block-fluid ucard">
       <div class="info">
         <ul class="rows">
            <li class="heading">
            <div class="isw-users"></div>Account Status & Settings<em style="font-size:9px;"> Dept. and assigned group controls access permissions</em>
            </li>  
            <li>
            <div class="title"> Account Type:</div>
             <div class="text">
             <label class="checkbox inline"> <input type="radio"  name="isadmin" value="1" <?php echo $info['isadmin']?'checked="checked"':''; ?> /> Admin</label>
             <label class="checkbox inline"><input type="radio"  name="isadmin" value="0" <?php echo !$info['isadmin']?'checked="checked"':''; ?>/> Staff</label>&nbsp;<span class="error">&nbsp;<?php echo $errors['isadmin']; ?></span>
            </div>
            </li>                
            <li>
            <div class="title">  Account Status:</div>
            <div class="text">
             <label class="checkbox inline"> <input type="radio"  name="isactive" value="1" <?php echo $info['isactive']?'checked="checked"':''; ?> /> Active</label>
             <label class="checkbox inline"><input type="radio"  name="isactive" value="0" <?php echo !$info['isactive']?'checked="checked"':''; ?>/> Locked</label>&nbsp;<span class="error">&nbsp;<?php echo $errors['isactive']; ?></span>
            </div>
            </li>               
            <li>
            <div class="title">  Assigned Group:</div>
            <div class="text"><select name="group_id" id="group_id">
            <option value="0">&mdash; Select Group &mdash;</option>
            <?php
            $sql='SELECT group_id, group_name, group_enabled as isactive FROM '.GROUP_TABLE.' ORDER BY group_name';
            if(($res=db_query($sql)) && db_num_rows($res)){
            while(list($id,$name,$isactive)=db_fetch_row($res)){
            $sel=($info['group_id']==$id)?'selected="selected"':'';
            echo sprintf('<option value="%d" %s>%s %s</option>',$id,$sel,$name,($isactive?'':' (Disabled)'));
            }
            }
            ?>
            </select>
            &nbsp;<span class="error">*&nbsp;<?php echo $errors['group_id']; ?></span>
            </div>
            </li>
			<?php /*?><?php if($staff && $_REQUEST['a']!='add'){
            $sql="Select * from sdms_staff_location where Location_ID = '".$info['Location_ID']."'";
            $res=db_query($sql);
            $row_result=db_fetch_row($res);
            ?>
            <li>
                <div class="title">Dept/Agency/FR:</div>
                <div class="text">
                <select name="agencies_id" onchange="get_staff_locaiotn(this.value)">
                <option value="0">&mdash; Select Locaiton Type &mdash;</option>    
                <option value="1" <?php if($row_result[2]=='1')echo 'selected="selected"'; ?> >Agencies</option>
                <option value="2" <?php if($row_result[2]=='2')echo 'selected="selected"'; ?>>FR</option>
                <option value="3" <?php if($row_result[2]=='3')echo 'selected="selected"'; ?>>Deparment</option>
                </select></div>
            </li>
            <?php }else{?>
            <li>
                <div class="title">Dept/Agency/FR:</div>
                <div class="text">
                <select name="agencies_id" onchange="get_staff_locaiotn(this.value)">
                <option value="0">&mdash; Select Locaiton Type &mdash;</option>    
                <option value="1" >Agencies</option>
                <option value="2">FR</option>
                <option value="3">Deparment</option>
                </select></div>
            </li>
            <?php }?>
            <div id="show_staff_locations">
            <?php if($staff && $_REQUEST['a']!='add'){ ?>
            <li>
            <div class="title">Staff Location:</div>
            <div class="text">
            <select name="location_id">
            <?php
            if($staff_locations=Staff::getStaffLocation($row_result[2])) {
            foreach($staff_locations as $id =>$sub_name) {
            $k="$id";
            echo sprintf('<option value="%d" %s>%s</option>',$id,(($info['Location_ID']==$k)?'selected="selected"':''), $sub_name);
            }
            }?>
            </select>
            &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['tehsil']; ?></font>
            </div>
            </li>
			<?php }?>
            </div>            
            <li id="show_staff_parents"> 
                <div class="title">Staff Members:</div>
                <div class="text">
                <select id="staff_pid" name="staff_pid" style="width:300px;">
        <option value="0" selected="selected">&mdash; Select Staff Member &mdash;</option>
        <?php
		$sid=$tid=0;
        if(($admin_users=Staff::getAdminMembers())) {
        echo '<OPTGROUP label="Supervisor FATA Administrator ('.count($admin_users).')">';
        foreach($admin_users as $id => $name) {
        if($staffId && $staffId==$id)
        continue;
        $k="$id";
        echo sprintf('<option value="%s" %s>%s</option>',$k,(($info['staff_pid']==$k)?'selected="selected"':''),$name);
        }
        echo '</OPTGROUP>';
        }
		
		
		//show last assign user
		
		//Childs under this user
		if(($users=Staff::getFocalMembers())) {
        echo '<OPTGROUP label="Agencies Members ('.count($users).')">';
        foreach($users as $id => $name) {
        if($staffId && $staffId==$id)
        continue;
        $k="$id";
        echo sprintf('<option value="%s" %s>%s</option>',$k,(($info['staff_pid']==$k)?'selected="selected"':''),$name);
        }
        echo '</OPTGROUP>';
        }
        ?>
        </select></div>
            </li>
           <?php */?>
            <li>                    
            <div class="title">Department Types:<?php 
			$sql_pdept="Select * from ".DEPT_TABLE." where 1";

            $res_pdept=db_query($sql_pdept);
            $row_result_pdept=db_fetch_row($res_pdept);
			 ?></div>
            <div class="text">
            <select name="dept_id" id="dept_id" onChange="check_islocation(this.value)">
            <option value="">--Select Department--</option>
            <?php
            if($depts=Dept::getuserDepartments()){
            foreach($depts as $id =>$name) {
            echo sprintf('<option value="%d" %s >%s</option>',$id, ($info['dept_id']==$id)?'selected="selected"':'',$name);
            }
            }
            ?>
            </select>
            </div>
            </li>
            <li id="region_section">                    
           
            </li>
            
            <li>
            <div class="title">Focal Person:</div>
            <div class="text"><input type="checkbox" name="isfocal" value="1" <?php echo $info['isfocalperson']?'checked="checked"':''; ?> onclick="OnChangeCheckbox(this);" id="isfocal_test">
            Set as Grievance Redressal Officer
            &nbsp;<font class="error">&nbsp;<?php echo $errors['isfocalperson']; ?></font>
            </div>
            </li>
            <li>
            <div class="title">Higher Management:</div>
            <div class="text"> <input type="checkbox" name="onchairman" value="1" <?php echo $info['onchairman']?'checked="checked"':''; ?>>
            Staff on Higher Management Access. (<i>Reports</i>)
            </div>
            </li>
            </ul>
            </div>
            </div>
            </div>
            <div class="span6">
    <div class="block-fluid ucard">
       <div class="info">
         <ul class="rows">
            <li class="heading">
            <div class="isw-users"></div>Account Status & Settings<em style="font-size:9px;"> Dept. and assigned group controls access permissions</em>
            </li>
            
            
            <?php /*?><li>
            <div class="title">Staff's Time Zone:</div>
            <div class="text"><select name="timezone_id" id="timezone_id">
            <option value="0">&mdash; Select Time Zone &mdash;</option>
            <?php
            $sql='SELECT id, offset,timezone FROM '.TIMEZONE_TABLE.' ORDER BY id';
            if(($res=db_query($sql)) && db_num_rows($res)){
            while(list($id,$offset, $tz)=db_fetch_row($res)){
            $sel=($info['timezone_id']==$id)?'selected="selected"':'';
            echo sprintf('<option value="%d" %s>GMT %s - %s</option>',$id,$sel,$offset,$tz);
            }
            }
            ?>
            </select>
            &nbsp;<span class="error">*&nbsp;<?php echo $errors['timezone_id']; ?></span>
            </div>
            </li>
			<li>
            <div class="title">Daylight Saving:</div>
            <div class="text"> <input type="checkbox" name="daylight_saving" value="1" <?php echo $info['daylight_saving']?'checked="checked"':''; ?>>
            <em>(Current Time: <strong><?php echo Format::date($cfg->getDateTimeFormat(),Misc::gmtime(),$info['tz_offset'],$info['daylight_saving']); ?></strong>)</em
            ></div>
            </li>  
			<li>
            <div class="title">  Limited Access:</div>
            <div class="text"><input type="checkbox" name="assigned_only" value="1" <?php echo $info['assigned_only']?'checked="checked"':''; ?>>Limit ticket access to ONLY assigned tickets.
            </div>
            </li>     
            <li>
            <div class="title">Directory Listing:</div>
            <div class="text"> <input type="checkbox" name="isvisible" value="1" <?php echo $info['isvisible']?'checked="checked"':''; ?>>Show the user on staff's directory
            </div>
            </li>       
            <li>
            <div class="title">  Vacation Mode:</div>
            <div class="text"> <input type="checkbox" name="onvacation" value="1" <?php echo $info['onvacation']?'checked="checked"':''; ?>>
            Staff on vacation mode. (<i>No ticket assignment or alerts</i>)
            </div>
            </li><?php */?> 
            <?php /*?><?php
            //List team assignments.
            $sql='SELECT team.team_id, team.name, isenabled FROM '.TEAM_TABLE.' team  ORDER BY team.name';
            if(($res=db_query($sql)) && db_num_rows($res)){ ?>
            <li>
            <div class="title">
            Assigned Teams:
            </div>
             <div class="text">
            Staff will have access to tickets assigned to a team they belong to regardless of the ticket's department. 
            </div> 
            </li>
            <li>
            <div class="title">
            
            </div>
             <div class="text">
            <?php
            while(list($id,$name,$isactive)=db_fetch_row($res)){
            $checked=($info['teams'] && in_array($id,$info['teams']))?'checked="checked"':'';
            echo sprintf('<input type="checkbox" name="teams[]" value="%d" %s>%s %s',
            $id,$checked,$name,($isactive?'':' (Disabled)'));
            }?>
            
            </div> 
            </li> 
             <?php } ?>  <?php */?>            
            <li>
            <div class="title">Admin Notes:</div>
            <div class="text">Internal notes viewable by all admins.&nbsp
            </div>
            </li>                
            <li>
            <div class="title"></div>
            <div class="text"> <textarea name="notes" cols="28" rows="7" style="width: 80%;"><?php echo $info['notes']; ?></textarea>
            </div>
            </li> 
            </ul>
       </div>
    </div>
</div>
</div>

<div class="row-fluid">  
<div class="span12">
    
    <div class="footer tar">
    <input type="submit" name="submit" value="<?php echo $submit_text; ?>" class="btn">
    <input type="reset"  name="reset"  value="Reset" class="btn">
    <input type="button" name="cancel" value="Cancel" onclick='window.location.href="staff.php"' class="btn">
    </div>  

</div>
</div>
</form>
<div class="dr"><span></span></div>
</div></div>
