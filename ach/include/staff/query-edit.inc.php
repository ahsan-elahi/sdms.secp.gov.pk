<?php
if(!defined('OSTSCPINC') || !$thisstaff || !$thisstaff->canEditTickets() || !$query) die('Access Denied');
$info=Format::htmlchars(($errors && $_POST)?$_POST:$query->getUpdateInfo());
?>
<script type="text/javascript">
$( document ).ready(function() {

});

function get_Sub_Com(comp_type)
{
$.ajax({
	url:"get_sub_category.php",
	data: "m_comp_type="+comp_type,
	success: function(msg){
	//alert(msg);	
	$("#show_sub_complaint").html(msg);
}
});
}

function get_tehsils(dist_id)
{
$.ajax({
	url:"get_tehsils.php",
	data: "district_id="+dist_id,
	success: function(msg){
	$("#show_sub_tehsils").html(msg);
}
});
}
function get_agency_tehsils(tehsil_id)
{
$.ajax({
	url:"get_agency_tehsils.php",
	data: "tehsil_id="+tehsil_id,
	success: function(msg){
	$("#show_agency_tehsils").html(msg);
}
});
}

</script>
<div class="page-header">
    <h1>Update Query <small>#<?php echo $query->getExtId(); ?></small></h1>
</div>
<form action="queries.php?id=<?php echo $query->getId(); ?>&a=edit" method="post" id="save"  enctype="multipart/form-data">
<?php csrf_token(); ?>
<input type="hidden" name="do" value="update">
<input type="hidden" name="a" value="edit">
<input type="hidden" name="id" value="<?php echo $query->getId(); ?>">
<div class="row-fluid">
    <div class="span6">
        <div class="block-fluid ucard">
            <div class="info">                                                                
                <ul class="rows">
                    <li class="heading">
                    <div class="isw-users"></div>User Information<em>&nbsp;&nbsp;(Make sure the email address is valid.)</em></li>
                    <li>
                        <div class="title">Name:</div>
                        <div class="text"><select name="name_title" style="width:77px;">
                        <option value="Mr" <?php if($info['name_title']=='mr'){echo 'selected="selected"';}?>>Mr.</option>
                        <option value="Miss" <?php if($info['name_title']=='miss'){echo 'selected="selected"';}?> >Miss.</option>
                        </select>
                         <input type="text" size="50" name="name" value="<?php echo $info['name']; ?>">
                               &nbsp;<span class="error">*&nbsp;<?php echo $errors['name']; ?></span>
                        </div>
                    </li>
                    
                    
                    <li>
                        <div class="title">CNIC/NICOP/Passport:</div>
                        <div class="text"><input type="text" required name="nic" value="<?php echo $info['nic']; ?>" style="width:287px;">
                               &nbsp;<span class="error">*&nbsp;<?php echo $errors['nic']; ?></span></div>
                    </li>                                
                    <li>
                        <div class="title">Mobile:</div>
                        <div class="text"><input type="text" name="phone" value="<?php echo $info['phone']; ?>" style="width:287px;">
                               &nbsp;<span class="error">&nbsp;<?php echo $errors['phone']; ?></span></div>
                    </li>
                    <li>
                        <div class="title">Email:</div>
                        <div class="text"><input type="text" size="50" name="email" id="email" class="typeahead" value="<?php echo $info['email']; ?>"
        autocomplete="off" autocorrect="off" autocapitalize="off" style="width:287px;">
        &nbsp;<span class="error">&nbsp;<?php echo $errors['email']; ?></span>
        <?php 
        if($cfg->notifyONNewStaffTicket()) { ?>
        &nbsp;&nbsp;&nbsp;<br>
        <input type="checkbox" name="alertuser" <?php echo (!$errors || $info['alertuser'])? 'checked="checked"': ''; ?>>Send alert to user.
        <?php 
        } ?></div>
                    </li>
                    <li>
                        <div class="title">Country:</div>
                        <div class="text">
                        <select name="district"  onChange="get_tehsils(this.value);" style="width:301px;">
    <?php
    if($districts=Districts::getDistricts()) {
    foreach($districts as $id =>$name) {
		 echo sprintf('<option value="%d" %s>%s</option>',$id, ($info['district']==$id)?'selected="selected"':'',$name);
    }
    }
    ?>
        </select>
    &nbsp;<font class="error">&nbsp;<?php echo $errors['district']; ?></font>
       </div>
            </li>
                    <div id="show_sub_tehsils">
                    <li>
                    <div class="title">Province:</div>
                    <div class="text">
                    <select name="tehsil" onChange="get_agency_tehsils(this.value);"  style="width:301px;">
                    <?php
                    if($sub_tehsils=Districts::getTehsils($info['district'])) {
                    foreach($sub_tehsils as $id =>$sub_name) {
                    echo sprintf('<option value="%d" %s>%s</option>',$id, ($info['tehsil']==$id)?'selected="selected"':'', $sub_name);
                    }
                    }?>
                    </select>
                    &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['tehsil']; ?></font>
                    </div>
                    </li>
                    </div>
                    <div id="show_agency_tehsils">
                    <li>
                    <div class="title">City:</div>
                    <div class="text">
                    <select name="agency_tehsils" style="width:301px;">
                    <?php
                    if($agency_tehsils=Districts::getAgencyTehsil($info['tehsil'])) {
                    foreach($agency_tehsils as $id =>$sub_name) {
                    echo sprintf('<option value="%d" %s>%s</option>',$id, ($info['agencytehsil']==$id)?'selected="selected"':'', $sub_name);
                    }
                    }?>
                    </select>
                    &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['tehsil']; ?></font>
                    </div>
                    </li>
                    </div>   
                    <li>
                        <div class="title">Address:</div>
                        <div class="text"><textarea name="applicant_address"  style="width:287px;" ><?php echo $info['applicant_address']; ?></textarea></div>
                    </li>                                                                  
                </ul>                                                      
           </div>                        
        </div>
    </div>
    <div class="span6">
        <div class="block-fluid ucard">
            <div class="info">                                                                
                <ul class="rows">
                    <li class="heading">Query Info</li>
                    <li>
                        <div class="title">Title:</div>
                        <div class="text"><input type="text" name="subject" size="60" required value="<?php echo $info['subject']; ?>"  style="width:287px;">
        &nbsp;<font class="error">*&nbsp;<?php $errors['subject']; ?></font></div>
                    </li>
                    <li>
                        <div class="title">Priority:</div>
                        <div class="text"><select name="priorityId"  style="width:301px;">
        <option value="" selected >&mdash; Select Priority &mdash;</option>
        <?php
        if($priorities=Priority::getPriorities()) {
            foreach($priorities as $id =>$name) {
                echo sprintf('<option value="%d" %s>%s</option>',
                        $id, ($info['priorityId']==$id)?'selected="selected"':'',$name);
            }
        }
        ?>
        </select>
        &nbsp;<font class="error">&nbsp;<?php echo $errors['priorityId']; ?></font></div>
                    </li>
                    <?php /*?> <li>
                    <?php $sql_sub="Select topic_pid from  sdms_help_topic where topic_id = '".$info['topicId']."'";
					$res_sub = mysql_query($sql_sub);
					$row_sub=mysql_fetch_array($res_sub);
					
					$sql_main="Select * from  sdms_help_topic where topic_id = '".$row_sub['topic_pid']."'";
					$res_main = mysql_query($sql_main);
					$row_main=mysql_fetch_array($res_main);
					?>
                    <div class="title">Main Category:</div>
					<div class="text">
                    <select name="topicId" >
					<?php
                    if($topics=Topic::getPublicHelpTopics()) {
                    foreach($topics as $id =>$name) {
                    echo sprintf('<option value="%d" %s>%s</option>',$id, ($row_main['topic_id']==$id)?'selected="selected"':'', $name);
                    }
                    } else { ?>
                    <option value="0" >General Inquiry</option>
                    <?php } ?>
                    </select>
                    &nbsp;<font class="error">&nbsp;<?php echo $errors['topicId']; ?></font></div>
					</li>
                   
                    <li>
                                            <div class="title">Department:</div>



                        <div class="text"><select name="m_deptId"  onChange="get_Sub_Com(this.value);">
    <?php
    if($depts=Dept::getDepartments()) {
    foreach($depts as $id =>$name) {
        echo sprintf('<option value="%d" %s>%s</option>',
                $id, ($info['deptId']==$id)?'selected="selected"':'',$name);
    }
    }
    ?>
    </select></div>



</li>
                    <div id="show_sub_complaint"></div><?php */?>
   <?php /*?>if($thisstaff->canDeleteTickets()) { ?>                  
                    <li>
                        <div class="title">SLA Plan:</div>
                        <div class="text"> <select name="slaId">
        <option value="0" selected="selected" >&mdash; None &mdash;</option>
        <?php
        if($slas=SLA::getSLAs()) {
            foreach($slas as $id =>$name) {
                echo sprintf('<option value="%d" %s>%s</option>',
                        $id, ($info['slaId']==$id)?'selected="selected"':'',$name);
            }
        }
        ?>
        </select>
        &nbsp;<font class="error">&nbsp;<?php echo $errors['slaId']; ?></font></div>
                    </li>
                    <?php
                    if($query->isOpen()){ ?>
                    <li>
                        <div class="title">Due Date:</div>
                        <div class="text"><input class="dp" id="duedate" name="duedate" value="<?php echo Format::htmlchars($info['duedate']); ?>" size="12" autocomplete=OFF>
        &nbsp;&nbsp;
        <?php
        $min=$hr=null;
        if($info['time'])
        list($hr, $min)=explode(':', $info['time']);
        
        echo Misc::timeDropdown($hr, $min, 'time');
        ?>
        &nbsp;<font class="error">&nbsp;<?php echo $errors['duedate']; ?>&nbsp;<?php echo $errors['time']; ?></font>
        <em>Time is based on your time zone (GMT <?php echo $thisstaff->getTZoffset(); ?>)</em>
        </div>
                    </li>
                    <?php
                    }else { ?>
                    <li>
                        <div class="title">Close Date:</div>
                        <div class="text">
                        <?php echo Format::db_datetime($query->getCloseDate()); ?></div>
                    </li>
                    <?php }  ?>  
             <?php } <?php */?>                                           
                </ul>                                                      
            </div>                        
        </div>
    </div>
    <?php /*?><div class="span6">
        <div class="block-fluid ucard">
                        <div class="info">                                                                
                            <ul class="rows">
                                <li class="heading">Against Info</li>
                                <li>
                                    <div class="title">Services</div>
                                    <div class="text">
                                        <select name="service" >
                                        <option value="" selected >&mdash; Select Services&mdash;</option>                                        
                                        <?php
                                        if($service=Service::getService()) {
                                        foreach($service as $id =>$name) {                                        
                                        echo sprintf('<option value="%s" %s>%s</option>',$id, ($info['service']==$id)?'selected="selected"':'',$name);
                                        } } ?>
                                        </select>
        							</div>
                                </li>
                                <li>
                                    <div class="title">Against Person:</div>
                                    <div class="text"><input type="text" size="50" name="comp_against" id="comp_against" class="typeahead" value="<?php echo $info['comp_against'] ?>" /></div>
                                </li>

                                <li>
                                    <div class="title">Location/ Area:</div>
                                    <div class="text"><select name="location" >
                                    <option value="" selected >&mdash; Select Location/ Area&mdash;</option>
    <?php 
    if($districts=Districts::getDistricts()) {
    foreach($districts as $id =>$name) {
		 echo sprintf('<option value="%s" %s>%s</option>',$name, ($info['location']==$name)?'selected="selected"':'',$name);
    }
    }
    ?>
        </select>
                                    
                                    </div>
                                </li>
                                <li>
                                    <div class="title">Address:</div>
                                    <div class="text"><textarea name="address" cols="21" rows="3" style="width:65%;"><?php echo $info['address']; ?></textarea></div>
                                </li>                                     
                            </ul>                                                      
                        </div>                        
                </div>
    </div><?php */?>
    <div class="span6">
        <div class="block-fluid ucard">
            <div class="info">                                                                
                <ul class="rows">
                    <li class="heading"><em><strong>Internal Note</strong>: Reason for editing the Query (required)</em></li>
                    <li>
                        <div class="title">Internal Note:</div>
                        <div class="text"> <textarea name="note" cols="21" rows="6" style="width:80%;"><?php echo $info['note']; ?></textarea>
        <font class="error">&nbsp;<?php echo $errors['note'];?></font></div>
                    </li>
                                                    
                </ul>                                                      
            </div>                        
        </div>
    </div>
</div>
 <div class="row-fluid">
 <div class="span12">
 <div class="footer tar">
<input type="submit" name="submit" value="Save" class="btn">
<input type="reset"  name="reset"  value="Reset" class="btn">
<input type="button" name="cancel" value="Cancel" onclick='window.location.href="queries.php?id=<?php echo $query->getId(); ?>"' class="btn">
</div>
 </div>
 </div>    
<div class="dr"><span></span></div> 
</div><!--WorkPlace End-->  
</div> 
