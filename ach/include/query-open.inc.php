<script>
/* file upload jquery code. */
$(document).ready(function()
{
		
$( "#input_product" ).autocomplete({
source: "../search_company_name.php",
minLength: 1
});

      get_tehsils(167);	
});
function complaint_type(c_type){
if(c_type==2){
$.ajax({
	url:"get_department_by_type.php",
	data: "c_type="+c_type,
	success: function(msg){
	$("#show_against_detials").html(msg);
}
});
}
}
function get_sm_brokers(b_type){
$.ajax({
	url:"get_sm_brokers.php",
	data: "b_type="+b_type,
	success: function(msg){
	$("#brokers_list").html(msg);
}
});
}
function sms_verify(){
var user_mobile = document.getElementById('mobile_number').value;
if(user_mobile!='')
{
	$.ajax({
	url:"verif_mobile_number.php",
	data: "user_mobile="+user_mobile,
	success: function(msg){	
	$("#sms_verification_number").html(msg);
	}
	});
}else{
alert('Please Enter Mobile Number First!!!');
}
}
</script>
<?php
if(!defined('OSTSCPINC') || !$thisstaff || !$thisstaff->canCreateTickets()) die('Access Denied');
$info=array();
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
?>
<script type="text/javascript">
$( document ).ready(function() {
get_Sub_Com(1);
});
function get_Sub_Com(comp_type)
{
$.ajax({
	url:"get_sub_category.php",
	data: "m_comp_type="+comp_type,
	success: function(msg){
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
function get_identify(identify_id)
{
$.ajax({
	url:"get_identify.php",
	data: "identify_id="+identify_id,
	success: function(msg){
	$("#identificaiton").html(msg);
}
});
}
$('#chkCaption').live("click", function() {
    if ($('#chkCaption').prop("checked")) {
         $('#divCaption').hide();
		 var $this = $('#district');
		 $this.removeAttr('required');
		 var $this = $('#tehsil');
		 $this.removeAttr('required');
    }
    else {
		$('#divCaption').show();
		$('#district').prop('required',true);
		$('#tehsil').prop('required',true);       
    }
});

function get_popup(id){
	get_focal_person(id);
}
function get_focal_person(dept_id){
	$.ajax({
	url:"get_focal_person.php",
	data: "dept_id="+dept_id,
	success: function(msg){
	$("#assignId").val(msg.trim());
	}
	});
}

</script>
<div class="page-header">
  <h1>Open <small>New Query</small></h1>
</div>
<form action="queries.php?a=open" method="post" id="complaint_form"  enctype="multipart/form-data">
  <?php csrf_token(); ?>
  <input type="hidden" name="do" value="create">
  <input type="hidden" name="a" value="open">
  <input type="hidden" name="topicId" value="43">
   <input type="hidden" value="1" name="isquery" >
    	 
  <div class="row-fluid">
    <div class="span6">
      <div class="block-fluid ucard">
        <div class="info">
          <ul class="rows">
            <li class="heading">
              <div class="isw-users"></div>
              Querier Details</li>
            <div id="identificaiton">
                <li>
                <div class="title">Name:</div>
                <div class="text">
                <select name="name_title" style="width:77px;" >
                <option value="Mr." selected="selected" >Mr.</option>
                <option value="Miss." >Miss.</option>
                </select>
                <input type="text" size="50" name="name" value="<?php echo $info['name']; ?>" required>
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['name']; ?></span> </div>
                </li>
                <li>
                <div class="title">CNIC/NICOP/Passport:</div>
                <div class="text"><!--id="nic"-->
                <input  type="text" name="nic" size="30" value=""  style="width:287px;" required >
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['nic']; ?></span> <!--<span>Example: 99999-9999999-9</span>--> </div>
                </li>
                <li>
                <div class="title">Mobile Number :</div>
                <div class="text">
                <input type="text" name="phone" id="mobile_number" maxlength="11" value="<?php echo $info['phone']; ?>" style="width:287px;" required>
                &nbsp;<span class="error"><b>*</b>&nbsp;<?php echo $errors['phone']; ?></span> <span>Example: 03009999999</span>
                <span onClick="sms_verify()"><img src="img/send_sms-128.png" style="width:50px;height:50px;" />&nbsp;&nbsp;<span id="sms_verification_number"></span></span> </div>
                </li>
                <div id="divCaption">
                <li>
                <div class="title">Country:</div>
                <div class="text">
                <select name="district" onChange="get_tehsils(this.value);" required id="district" style="width:301px;">
                <option value="">--Select Country--</option>
                <?php
                $info['district'] = '167';
                if($districts=Districts::getDistricts()) {
                foreach($districts as $id =>$name) {
                echo sprintf('<option value="%d" %s>%s</option>',$id, ($info['district']==$id)?'selected="selected"':'',$name);
                }
                }
                ?>
                </select>
                &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['district']; ?></font> </div>
                </li>
                <div id="show_sub_tehsils"></div>
                <div id="show_agency_tehsils"></div>
                </div>
                <li>
                <div class="title">Email:</div>
                <div class="text">
                <input type="email" size="50" name="email" id="email" class="typeahead" value="<?php echo $info['email']; ?>" autocomplete="off" autocorrect="off" autocapitalize="off"  style="width:287px;">
                <span class="error">&nbsp;<?php echo $errors['email']; ?></span><br />
                <?php 
                if($cfg->notifyONNewStaffTicket()) { ?>
                <input type="checkbox" name="alertuser" <?php echo (!$errors || $info['alertuser'])? 'checked="checked"': ''; ?>>
                Send alert to user.
                <?php } ?>
                </div>
                </li>
                <span id="sms_pin_code">
                
                </span>
                <li>
                <div class="title">Postal Address:</div>
                <div class="text">
                <textarea name="applicant_address" rows="2"  style="width:287px;" ></textarea>
                </div>
                </li>
            </div>
          </ul>
        </div>
      </div>
    </div>
    <div class="span6">
      <div class="block-fluid ucard">
        <div class="info">
          <ul class="rows">
            <li class="heading">Query Details</li>
            <li>
              <div class="title">Query Source:</div>
              <div class="text">
                <select name="source"  style="width:301px;" required>
                  <option value="Through Call"      <?php echo ($info['source']=='Through Call')?'selected="selected"':'selected="selected"'; ?>>Through Call</option>
                  <option value="At Counter"        <?php echo ($info['source']=='At Counter')?'selected="selected"':''; ?>>Walk In</option>
                  <option value="By Email"          <?php echo ($info['source']=='By Email')?'selected="selected"':''; ?>>Email</option>
                  <option value="By Post"           <?php echo ($info['source']=='By Post')?'selected="selected"':''; ?>>Post</option>
                  <option value="By Fax"            <?php echo ($info['source']=='By Fax')?'selected="selected"':''; ?>>Fax</option>
                  <option value="By Dropbox"            <?php echo ($info['source']=='By Dropbox')?'selected="selected"':''; ?>>Dropbox</option>
                  <option value="Online Query"  <?php echo ($info['source']=='Online Query')?'selected="selected"':''; ?>>Online Query</option>
                </select>
                &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['source']; ?></font> </div>
            </li>
            <li>
                        <div class="title">Priority:</div>
                        <div class="text"><select name="priorityId" style="width:301px;" required>
    <option value="0" selected >&mdash; System Default &mdash;</option>
    <?php
    if($priorities=Priority::getPriorities()) {
    foreach($priorities as $id =>$name) {
        echo sprintf('<option value="%d" %s>%s</option>',
                $id, ($info['priorityId']==$id)?'selected="selected"':'',$name);
    }
    }
    ?>
        </select>
         &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['priorityId']; ?></font></div>
                    </li>
            <li id="show_against_detials">
              <div class="title">Departments:</div>
              <div class="text">
                <select name="deptId" onChange="get_focal_person(this.value);" style="width:301px;" required>
                  <option value="">--Select Department--</option>
                  <?php
                    if($sub_depart=Dept::getDepartments()) {
                    foreach($sub_depart as $id =>$name) {
                    echo sprintf('<option value="%d" >%s</option>',$id,$name);
                    }
                    }
                    ?>
                </select>
                &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['deptId']; ?></font>
              </div>
            </li>
            <li>
              <div class="title">Title:</div>
              <div class="text">
                <input type="text" name="subject" size="60" value="<?php echo $info['subject']; ?>" required  style="width:287px;">
                &nbsp; <font class="error">*&nbsp;<?php echo $errors['subject']; ?></font> </div>
            </li>
            <li>
              <div class="title">Detail: <em>Details on the reason(s) for opening the Query.</em> <br />
                Maximum 150 Characters</div>
              <div class="text">
                <textarea name="issue" cols="21" rows="8" style="width: 289px; height: 153px;" required><?php echo $info['issue']; ?></textarea>
                <font class="error">*&nbsp;<?php echo $errors['issue']; ?></font></div>
            </li>
          	<li>
                <div class="title">Query Action:</div>
                <div class="text">
                 <input type="checkbox" name="query_rsolved" value="1" >
                  Resolved Query.
                  </div>
              </li>
            <input type="hidden" id="assignId" name="assignId" value="0">
          </ul>
        </div>
      </div>
    </div>
  </div>
 
    
  </div>
  
  
  
  
  
  <div class="row-fluid">
    <div class="span12">
      <div class="footer tar">
        <input type="submit" name="submit" value="Submit" class="btn">
        <input type="reset"  name="reset"  value="Reset" class="btn">
        <input type="button" name="cancel" value="Cancel" onclick='window.location.href="tickets.php"' class="btn">
      </div>
    </div>
  </div>
</form>
<div class="dr"><span></span></div>
</div>
<!--WorkPlace End-->
</div>
