<script>
/* file upload jquery code. */
$(document).ready(function()
{
var settings = {
	url: "upload.php",
	method: "POST",
	allowedTypes:"jpg,jpeg,png,pdf",
	fileName: "myfile",
	multiple: false,
	showDelete: true,
	 deleteCallback: function (data, pd) {
     for (var i = 0; i < data.length; i++) {
         $.post("delete.php", {op: "delete",name: data[i]},
             function (resp,textStatus, jqXHR) {
                 //Show Message	
                 alert("File Deleted");
             });
     }
     pd.statusbar.hide(); //You choice.

 },
	onSuccess:function(files,data,xhr)
	{
		
		$('#error').html('');
		$("#status").html("<font color='green'>Attachment Upload is success</font>");
		//var path= JSON.stringify(data);
		var path= JSON.parse(data);
		var path1=JSON.stringify(path).replace("\"",'');
		path1=path1.replace("\"",'');
		path1=path1.replace("\"",'');
		path1=path1.replace("\"",'');
		path1=path1.replace(":",'');
		path1=path1.replace("}",'');
		path1=path1.replace("{",'');
		var arr=path1.split('/');
		path1=arr[1];
		// $("#append_fields").html('<input class="file_multi" type="hidden" name="evidence_1" value="'+path1+'" />');
		 $("#evidence_1").val(path1);
	},
	onError: function(files,status,errMsg)
	{		
		$("#status").html("<font color='red'>Attachment Upload is Failed</font>");
	}
}
var settings1 = {
	url: "upload.php",
	method: "POST",
	allowedTypes:"jpg,jpeg,png,pdf",
	fileName: "myfile",
	multiple: false,
	onSuccess:function(files,data,xhr)
	{
		
		$('#error1').html('');
		$("#status1").html("<font color='green'>Attachment Upload is success</font>");
		//var path= JSON.stringify(data);
		var path= JSON.parse(data);
		var path1=JSON.stringify(path).replace("\"",'');
		path1=path1.replace("\"",'');
		path1=path1.replace("\"",'');
		path1=path1.replace("\"",'');
		path1=path1.replace(":",'');
		path1=path1.replace("}",'');
		path1=path1.replace("{",'');
		var arr=path1.split('/');
		path1=arr[1];
		// $("#append_fields1").html('<input class="file_multi1" type="hidden" name="technical_doc" value="'+path1+'" />');
		$("#evidence_2").val(path1);
		
		
	},
	onError: function(files,status,errMsg)
	{		
		$("#status1").html("<font color='red'>Attachment Upload is Failed</font>");
	}
}
var settings2 = {
	url: "upload.php",
	method: "POST",
	allowedTypes:"jpg,jpeg,png,pdf",
	fileName: "myfile",
	multiple: false,
	onSuccess:function(files,data,xhr)
	{
		$('#error2').html('');
		$("#status2").html("<font color='green'>Upload is success</font>");
		//var path= JSON.stringify(data);
		var path= JSON.parse(data);
		var path1=JSON.stringify(path).replace("\"",'');
		path1=path1.replace("\"",'');
		path1=path1.replace("\"",'');
		path1=path1.replace("\"",'');
		path1=path1.replace(":",'');
		path1=path1.replace("}",'');
		path1=path1.replace("{",'');
		var arr=path1.split('/');
		path1=arr[1];
		// $("#append_fields2").html('<input class="file_multi2" type="hidden" name="financial_doc" value="'+path1+'" />');
		 $("#evidence_3").val(path1);
	},
	onError: function(files,status,errMsg)
	{		
		$("#status2").html("<font color='red'>Upload is Failed</font>");
	}
}
var settings3 = {
	url: "upload.php",
	method: "POST",
	allowedTypes:"jpg,jpeg,png,pdf",
	fileName: "myfile",
	multiple: false,
	onSuccess:function(files,data,xhr)
	{
		$('#error3').html('');
		$("#status3").html("<font color='green'>Upload is success</font>");
		//var path= JSON.stringify(data);
		var path= JSON.parse(data);
		var path1=JSON.stringify(path).replace("\"",'');
		path1=path1.replace("\"",'');
		path1=path1.replace("\"",'');
		path1=path1.replace("\"",'');
		path1=path1.replace(":",'');
		path1=path1.replace("}",'');
		path1=path1.replace("{",'');
		var arr=path1.split('/');
		path1=arr[1];
		// $("#append_fields3").html('<input class="file_multi3" type="hidden" name="financial_doc" value="'+path1+'" />');
		 $("#evidence_4").val(path1);
	},
	onError: function(files,status,errMsg)
	{		
		$("#status3").html("<font color='red'>Upload is Failed</font>");
	}
}
var settings4 = {
	url: "upload.php",
	method: "POST",
	allowedTypes:"jpg,jpeg,png,pdf",
	fileName: "myfile",
	multiple: false,
	onSuccess:function(files,data,xhr)
	{
		$('#error4').html('');
		$("#status4").html("<font color='green'>Upload is success</font>");
		//var path= JSON.stringify(data);
		var path= JSON.parse(data);
		var path1=JSON.stringify(path).replace("\"",'');
		path1=path1.replace("\"",'');
		path1=path1.replace("\"",'');
		path1=path1.replace("\"",'');
		path1=path1.replace(":",'');
		path1=path1.replace("}",'');
		path1=path1.replace("{",'');
		var arr=path1.split('/');
		path1=arr[1];
		// $("#append_fields4").html('<input class="file_multi4" type="hidden" name="financial_doc" value="'+path1+'" />');
		 $("#evidence_5").val(path1);
	},
	onError: function(files,status,errMsg)
	{		
		$("#status4").html("<font color='red'>Upload is Failed</font>");
	}
}
var settings5 = {
	url: "upload.php",
	method: "POST",
	allowedTypes:"jpg,jpeg,png,pdf",
	fileName: "myfile",
	multiple: false,
	onSuccess:function(files,data,xhr)
	{
		$('#error5').html('');
		$("#status5").html("<font color='green'>Upload is success</font>");
		//var path= JSON.stringify(data);
		var path= JSON.parse(data);
		var path1=JSON.stringify(path).replace("\"",'');
		path1=path1.replace("\"",'');
		path1=path1.replace("\"",'');
		path1=path1.replace("\"",'');
		path1=path1.replace(":",'');
		path1=path1.replace("}",'');
		path1=path1.replace("{",'');
		var arr=path1.split('/');
		path1=arr[1];
		// $("#append_fields5").html('<input class="file_multi5" type="hidden" name="financial_doc" value="'+path1+'" />');
		 $("#evidence_6").val(path1);
	},
	onError: function(files,status,errMsg)
	{		
		$("#status5").html("<font color='red'>Upload is Failed</font>");
	}
}
$("#mulitplefileuploader").uploadFile(settings);
$("#mulitplefileuploader1").uploadFile(settings1);
$("#mulitplefileuploader2").uploadFile(settings2);
$("#mulitplefileuploader3").uploadFile(settings3);
$("#mulitplefileuploader4").uploadFile(settings4);
$("#mulitplefileuploader5").uploadFile(settings5);
$("#save").submit(function(e){
			var myfile=$('.file_multi').val();
			var myfile1=$('.file_multi1').val();
	     	var myfile2=$('.file_multi2').val();
			var myfile3=$('.file_multi3').val();
			var myfile4=$('.file_multi4').val();
	     	var myfile5=$('.file_multi5').val();
			
			
			/*if(myfile=='' && myfile1=='' && myfile2==''){
				e.preventDefault();
				$('#error').html('Please Upload a Document');
			}*/
		});
		
$( "#input_product" ).autocomplete({
source: "../search_company_name.php",
minLength: 1,
 close: function( event, ui ) {  update_e_cro(this.value); }
});
$( "#input_product1" ).autocomplete({
source: "../search_company_name.php",
minLength: 1,
 close: function( event, ui ) { 
 update_r_cro(this.value); 
 $('#unregistered_entity').hide();
   }
});
$( "#input_product2" ).autocomplete({
source: "../search_company_name.php",
minLength: 1
});

get_tehsils(167);	
get_agency_tehsils(56);
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
function hide_cro_company(){
  $('#cr_folio_no_cr_no_of_shares').hide();
  $('#r_company_cro').hide();
}
function update_e_cro(company_title){
	$.ajax({
	url:"../get_e_cro.php",
	data: "company_title="+company_title,
	success: function(msg){
	$("#e_cro_title").html(msg);
	}
	});
}

function update_r_cro(company_title){
	$.ajax({
	url:"../get_r_cro.php",
	data: "company_title="+company_title,
	success: function(msg){
	$("#r_cro_title").html(msg);
	}
	});}

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
function get_against_dept(against_id){
if(against_id=='Dept/Organization'){
$.ajax({
	url:"get_against_dept.php",
	data: "against_id="+against_id,
	success: function(msg){
	$("#show_against_detials").html(msg);
}
});
	}else{
		$("#show_against_detials").html('');
		}
}
function get_popup(id){
	get_focal_person(id);
	if(id==2)
	{
		$("#show_against_insurance").css("display", "none");
		$("#show_against_s_c").css("display", "none");
		$("#show_against_s_m").css("display", "");
	}
	else if(id==3){
		$("#show_against_s_m").css("display", "none");
		$("#show_against_s_c").css("display", "none");
		$("#show_against_insurance").css("display", "");
	}
	else if(id==4){
		$("#show_against_s_m").css("display", "none");
		$("#show_against_insurance").css("display", "none");
		$("#show_against_s_c").css("display", "");
	}
	else if(id==5){
		$("#show_against_s_m").css("display", "none");
		$("#show_against_s_c").css("display", "none");
		$("#show_against_insurance").css("display", "nono");
		$('#popup_action_e_services').click();
	}
	else if(id==6){
		$("#show_against_s_m").css("display", "none");
		$("#show_against_s_c").css("display", "none");
		$("#show_against_insurance").css("display", "nono");
		$('#popup_action_company_registration').click();
	}
	else if(id ==18){
		$("#show_against_s_m").css("display", "none");
		$("#show_against_s_c").css("display", "none");
		$("#show_against_insurance").css("display", "nono");
		$('#popup_action_company_supervision').click();
	}else{
	$("#show_against_s_m").css("display", "none");
		$("#show_against_s_c").css("display", "none");
		$("#show_against_insurance").css("display", "nono");
	}
}
function get_focal_person(dept_id){
	$("#complaint_submit_btn").attr('disabled',true);
	$("#loading_status").css('display', '');
	$.ajax({
	url:"get_focal_person.php",
	data: "dept_id="+dept_id,
	success: function(msg){
	$("#assignId").val(msg.trim());
	$("#complaint_submit_btn").attr('disabled',false);	
	$("#loading_status").css('display', 'none');
	}
	});
}
function show_popup_sm(b_type){
	$("#brokers_list").html('');
	$("#brokers_agent").html('');
	$.ajax({
	url:"get_cm_brokers.php",
	data: "b_type="+b_type,
	success: function(msg){	
	$("#brokers_list").html(msg);
	$('#popup_action_s_m').click();
}
});	
}
function show_cm_agent_list(parent){
	$.ajax({
	url:"get_cm_brokers_agents.php",
	data: "parent="+parent,
	success: function(msg){
	$("#brokers_agent").html(msg);
	
}
});	
}
function show_popup_insurance(b_type){
	$("#insurance_list").html('');
	$("#insurance_agent").html('');
	$.ajax({
	url:"get_insurance_brokers.php",
	data: "b_type="+b_type,
	success: function(msg){	
	$("#insurance_list").html(msg);
	$('#popup_action_insurance').click();
}
});	
}
function show_insurance_agent_list(parent){
	$.ajax({
	url:"get_insurance_brokers_agents.php",
	data: "parent="+parent,
	success: function(msg){
	$("#insurance_agent").html(msg);
}
});	
}
function show_popup_scd(b_type){
	$("#scd_list").html('');
	$("#reit_scheme").html('');
	$("#modaraba_fund").html('');
	$("#mutual_fund").html('');
	$("#pension_fund").html('');
	$.ajax({
	url:"get_scd_brokers.php",
	data: "b_type="+b_type,
	success: function(msg){	
	$("#scd_list").html(msg);
	$('#popup_action_specialized_companies').click();
}
});	
}
function show_scd_agent_list(parent){
	
	$.ajax({
	url:"get_scd_brokers_agents.php",
	data: "parent="+parent+"&action=reit_scheme",
	success: function(msg){
	$("#reit_scheme").html(msg);
}
});	

$.ajax({
	url:"get_scd_brokers_agents.php",
	data: "parent="+parent+"&action=modaraba_fund",
	success: function(msg){
	$("#modaraba_fund").html(msg);
}
});	

$.ajax({
	url:"get_scd_brokers_agents.php",
	data: "parent="+parent+"&action=mutual_fund",
	success: function(msg){
	$("#mutual_fund").html(msg);
}
});	

$.ajax({
	url:"get_scd_brokers_agents.php",
	data: "parent="+parent+"&action=pension_fund",
	success: function(msg){
	$("#pension_fund").html(msg);
}
});
}
function validateForm(){
id = $("#deptId").val();
	if(id==2){
		if($('input[id=cm_type_id]:checked').length<=0)
		{
		alert("Please Fill Type");
		return false;
		}
		/*if($("#cm_broker_title").val()=='')
		{
		alert('Please Fill Broker Title');
		return false;
		}
		if($("#cm_broker_agent").val()=='')
		{
		alert('Please Fill Agent Title');
		return false;
		}
		if($("#cm_folio_no").val()=='')
		{
		alert('Please Fill Folio No');
		return false;
		}
		if($("#cm_cdc_ac_no").val()=='')
		{
		alert('Please Fill CDC AC No');
		return false;
		}
		if($("#cm_no_of_shares").val()=='')
		{
		alert('Please Fill No of Shares');
		return false;
		}*/
	}
	else if(id==3){
		if($('input[id=i_type_id]:checked').length<=0)
		{
		alert("Please Fill Type");
		return false;
		}
		/*if($("#i_broker_title").val()=='')
		{
		alert('Please Fill Broker Title');
		return false;
		}
		if($("#i_broker_agent").val()=='')
		{
		alert('Please Fill Agent Title');
		return false;
		}
		if($("#i_policy_no").val()=='')
		{
		alert('Please Fill Policy No');
		return false;
		}
		if($("#i_sum_assured").val()=='')
		{
		alert('Please Fill Sum Assured');
		return false;
		}
		if($("#i_claim_amount").val()=='')
		{
		alert('Please Fill Claim Amount');
		return false;
		}
		if($("#i_folio_no").val()=='')
		{
		alert('Please Fill Folio No');
		return false;
		}
		if($("#i_no_of_shares").val()=='')
		{
		alert('Please Fill No of Shares');
		return false;
		}*/
	}
	else if(id==4){
		if($('input[id=scd_type_id]:checked').length<=0)
		{
		alert("Please Fill Type");
		return false;
		}
		/*if($("#scd_broker_title").val()=='')
		{
		alert('Please Fill Broker Title');
		return false;
		}
		if($("#reit_scheme_list").val()=='')
		{
		alert('Please Fill Reit Scheme');
		return false;
		}
		if($("#modaraba_fund_list").val()=='')
		{
		alert('Please Fill Modaraba Fund');
		return false;
		}
		if($("#mutual_fund_list").val()=='')
		{
		alert('Please Fill Mutual Fund');
		return false;
		}
		if($("#pension_fund_list").val()=='')
		{
		alert('Please Fill Pension Fund');
		return false;
		}
		
		
		if($("#scd_folio_no").val()=='')
		{
		alert('Please Fill Folio No');
		return false;
		}
		if($("#scd_no_of_units").val()=='')
		{
		alert('Please Fill No Of Units');
		return false;
		}
		if($("#scd_cdc_ac_no").val()=='')
		{
		alert('Please Fill CDC AS No');
		return false;
		}*/
		
			}
	else if(id==5){
		/*if($("#input_product").val()=='')
		{
		alert('Please Fill Company Name');
		return false;
		}
		if($("#e_registration_office").val()=='')
		{
		alert('Please Fill Registration Office');
		return false;
		}
		if($("#e_process_name").val()=='')
		{
		alert('Please Fill Process Name');
		return false;
		}
		if($("#e_user_id").val()=='')
		{
		alert('Please Fill User id');
		return false;
		}*/
	}
	else if(id==6){
		/*if($("#input_product2").val()=='')
		{
		alert('Please Fill Company Name');
		return false;
		}
		if($("#cs_cdc_ac_no").val()=='')
		{
		alert('Please Fill CDC Account No.');
		return false;
		}
		if($("#cs_folio_no").val()=='')
		{
		alert('Please Fill Folio No.');
		return false;
		}*/
	}
	else if(id ==18){
		return true;
	}else{
		return true;
	}
}
</script>
<div class="page-header">
  <h1>Open <small>New Complaint</small></h1>
</div>
<form action="tickets.php?a=open" method="post" id="complaint_form"  enctype="multipart/form-data" onsubmit="return validateForm()">
  <?php csrf_token(); ?>
  <input type="hidden" name="do" value="create">
  <input type="hidden" name="a" value="open">
  <input type="hidden" name="topicId" value="43">
   <input type="hidden" value="0" name="isquery" >
    	 
  <div class="row-fluid">
    <div class="span6">
      <div class="block-fluid ucard">
        <div class="info">
          <ul class="rows">
            <li class="heading">
              <div class="isw-users"></div>
              Complainant Details</li>
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
                <div class="title">CNIC/NICOP/PSPT:</div>
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
              <?php /*?><li>
                <div class="title">Alternate email Address:</div>
                <div class="text">
                  <input type="email" size="50" name="alternate_email" id="alternate_email" class="typeahead" value="<?php echo $info['alternate_email']; ?>" autocomplete="off" autocorrect="off" autocapitalize="off"   style="width:287px;">
                  <span class="error">&nbsp;<?php echo $errors['alternate_email']; ?></span><br />
                </div>
              </li><?php */?>
              <span id="sms_pin_code">
              
              </span>
              <?php /*?><li>
                <div class="title">Landline Number:</div>
                <div class="text">
                  <input type="text" name="landline_number" id="phone" maxlength="11" value="<?php echo $info['landline_number']; ?>" style="width:287px;">
                  &nbsp;<span class="error">&nbsp;<?php echo $errors['landline_number']; ?></span> <span></span> </div>
              </li><?php */?>
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
            <li class="heading">Complaint Details</li>
            <li>
              <div class="title">Compalint Source:</div>
              <div class="text">
                <select name="source"  style="width:301px;" required>
                  <option value="Through Call"      <?php echo ($info['source']=='Through Call')?'selected="selected"':'selected="selected"'; ?>>Through Call</option>
                  <option value="At Counter"        <?php echo ($info['source']=='At Counter')?'selected="selected"':''; ?>>At Counter</option>
                  <option value="By Email"          <?php echo ($info['source']=='By Email')?'selected="selected"':''; ?>>Email</option>
                  <option value="By Post"           <?php echo ($info['source']=='By Post')?'selected="selected"':''; ?>>Post</option>
                  <option value="By Fax"            <?php echo ($info['source']=='By Fax')?'selected="selected"':''; ?>>Fax</option>
                  <option value="By Dropbox"        <?php echo ($info['source']=='By Dropbox')?'selected="selected"':''; ?>>Dropbox</option>
                  <option value="SECP Chatbot"        <?php echo ($info['source']=='SECP Chatbot')?'selected="selected"':''; ?>>SECP Chatbot</option>
                  
                  <!--<option value="Online Complaint"  <?php //echo ($info['source']=='Online Complaint')?'selected="selected"':''; ?>>Online Complaint</option>-->
                </select>
                &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['source']; ?></font> </div>
            </li>
            <?php /*?>
			<li>
                        <div class="title">Comaplaint Nature:</div>
                        <div class="text"><select name="topicId">
                        <?php
    if($topics=Topic::getPublicHelpTopics()) {
    foreach($topics as $id =>$name) {
    echo sprintf('<option value="%d" %s>%s</option>',
            $id, ($info['topicId']==$id)?'selected="selected"':'', $name);
    }
    } else { ?>
    <option value="0" >General Inquiry</option>
    <?php
    } ?>
    </select>
    &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['topicId']; ?></font></div>
</li><?php */?>
<!--<li >
              <div class="title">Type:</div>
              <div class="text">
                <select name="deptId" onChange="complaint_type(this.value);"  style="width:301px;" required>
                  <option value="">--Select Comaplint Type--</option>
                  <option value="1" selected>Single</option>
                  <option value="2" >Multiple</option>
                  </select>
              </div>
            </li>-->
            <li>
                        <div class="title">Priority:</div>
                        <div class="text"><select name="priorityId" style="width:301px;" required>
    <option value="0" selected >&mdash; System Default &mdash;</option>
    <?php
	$info['priorityId']=2;
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
                <select name="deptId" id="deptId" <?php  if($_REQUEST['action']=='') {?>onChange="get_popup(this.value);" <?php }?>  style="width:301px;" required>
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
            <li id="show_against_s_m" style="display:none;">
              <div class="title">Types:</div>
              <div class="text">
			<?php 
            $sql_cm_tyes = "Select * from sdms_capital_markets Where 1 GROUP BY type";
            $res_cm_tyes = db_query($sql_cm_tyes);
            while( $row_cm_tyes = db_fetch_array($res_cm_tyes)){
            ?>
            
            <input type="radio" name="dept_type_id" id="cm_type_id" value="<?php echo $row_cm_tyes['type']; ?>" onClick="show_popup_sm(this.value);"><?php echo $row_cm_tyes['type']; ?><Br>
            <?php }?>
               
                &nbsp;<font class="error">&nbsp;<?php echo $errors['deptTypeId']; ?></font>
              </div>
            </li>
            <li id="show_against_insurance"  style="display:none;">
              <div class="title">Types:</div>
              <div class="text">
			<?php 
            $sql_insurance_tyes = "Select * from sdms_insurance Where 1 GROUP BY type";
            $res_insurance_tyes = db_query($sql_insurance_tyes);
            while( $row_insurance_tyes = db_fetch_array($res_insurance_tyes)){
            ?>
            
            <input type="radio" name="dept_type_id" id="i_type_id"  value="<?php echo $row_insurance_tyes['type']; ?>" onClick="show_popup_insurance(this.value);"><?php echo $row_insurance_tyes['type']; ?><Br>
            <?php }?>
               
                &nbsp;<font class="error">&nbsp;<?php echo $errors['deptTypeId']; ?></font>
              </div>
              
            </li>
            <li id="show_against_s_c"  style="display:none;">
              <div class="title">Types:</div>
              <div class="text">
				<?php 
                $sql_scd_tyes = "Select * from sdms_scd Where 1 GROUP BY type";
                $res_scd_tyes = db_query($sql_scd_tyes);
                while( $row_scd_tyes = db_fetch_array($res_scd_tyes)){
                ?>           
                <input type="radio" name="dept_type_id" id="scd_type_id"  value="<?php echo $row_scd_tyes['type']; ?>" onClick="show_popup_scd(this.value);"><?php echo $row_scd_tyes['type']; ?><Br>
                <?php }?>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['deptTypeId']; ?></font>
              </div>
            </li>
            <input type="hidden" id="assignId" name="assignId" value="0">
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="row-fluid">
    <div class="span6">
      <div class="block-fluid ucard">
        <div class="info">
          <ul class="rows">
            <li class="heading">Complaint Details</li>
            <li>
              <div class="title">Title:</div>
              <div class="text">
                <input type="text" name="subject" size="60" value="<?php echo $info['subject']; ?>" required  style="width:287px;">
                &nbsp; <font class="error">*&nbsp;<?php echo $errors['subject']; ?></font> </div>
            </li>
            <li>
              <div class="title">Detail: <em>Details on the reason(s) for opening the Complaint.</em> <br />
                Maximum 150 Characters</div>
              <div class="text">
                <textarea name="issue" cols="21" rows="8" style="width: 289px; height: 153px;" required><?php echo $info['issue']; ?></textarea>
                <font class="error">*&nbsp;<?php echo $errors['issue']; ?></font></div>
            </li>
            </ul>
            </div>
            </div>
            </div>
                <div class="span6">
      <div class="block-fluid ucard">
        <div class="info">
          <ul class="rows">
            <li class="heading">Attach Attachment if any: Maximum 6 Files</li>
            <li>
              <div class="title">Attachment:</div>
              <div class="text"></div>
              <div class="text" id="mulitplefileuploader">Upload</div>
              <div id="error" style="margin-left:120px;"></div>
              <div id="status" style="margin-left:120px;"></div>
              <div id="append_fields">
                <input class="file_multi" type="hidden" name="evidence_1" id="evidence_1" value="" />
              </div>
            </li>
            <li>
              <div class="title">Attachment:</div>
              <div class="text"></div>
              <div class="text" id="mulitplefileuploader1">Upload</div>
              <div id="error1" style="margin-left:120px;"></div>
              <div id="status1" style="margin-left:120px;"></div>
              <div id="append_fields1">
                <input class="file_multi1" type="hidden" name="evidence_2" id="evidence_2" value="" />
              </div>
            </li>
            <li>
              <div class="title">Attachment:</div>
              <div class="text"></div>
              <div class="text" id="mulitplefileuploader2">Upload</div>
              <div id="error2" style="margin-left:120px;"></div>
              <div id="status2" style="margin-left:120px;"></div>
              <div id="append_fields2">
                <input class="file_multi2" type="hidden" name="evidence_3" id="evidence_3" value="" />
              </div>
            </li>
            <li>
              <div class="title">Attachment:</div>
              <div class="text"></div>
              <div class="text" id="mulitplefileuploader3">Upload</div>
              <div id="error3" style="margin-left:120px;"></div>
              <div id="status3" style="margin-left:120px;"></div>
              <div id="append_fields3">
                <input class="file_multi3" type="hidden" name="evidence_4" id="evidence_4" value="" />
              </div>
            </li>
            <li>
              <div class="title">Attachment:</div>
              <div class="text"></div>
              <div class="text" id="mulitplefileuploader4">Upload</div>
              <div id="error4" style="margin-left:120px;"></div>
              <div id="status4" style="margin-left:120px;"></div>
              <div id="append_fields4">
                <input class="file_multi4" type="hidden" name="evidence_5" id="evidence_5" value="" />
              </div>
            </li>
            <li>
              <div class="title">Attachment:</div>
              <div class="text"></div>
              <div class="text" id="mulitplefileuploader5">Upload</div>
              <div id="error5" style="margin-left:120px;"></div>
              <div id="status5" style="margin-left:120px;"></div>
              <div id="append_fields5">
                <input class="file_multi5" type="hidden" name="evidence_6" id="evidence_6" value="" />
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <a id="popup_action_s_m" style="display:none;">Securities Markets</a> 
  <a id="popup_action_insurance" style="display:none;">Insurance</a> 
  <a id="popup_action_specialized_companies" style="display:none;">Specialized Companies</a> 
  <a id="popup_action_e_services" style="display:none;">e-Services</a> 
  <a id="popup_action_company_registration" style="display:none;">Company Registration</a> 
  <a id="popup_action_company_supervision" style="display:none;">Complaint Against CRO</a>
  <div class="dialog" id="b_popup_action_s_m" style="display: none;" title="Capital Markets">
    <div class="block">
    
    <div id="brokers_list"></div>
    <div id="brokers_agent"></div>
   
    <span>Please Select at least from (Folio Number) OR (CDC A/C No.) (not required)</span><br>
    <span>Folio No:</span>
    <p>
    <input type="text" size="50" name="cm_folio_no" id="cm_folio_no" class="typeahead" value="" />
    </p>
    <span>CDC A/C No:</span>
    <p>
    <input type="text" size="50" name="cm_cdc_ac_no" id="cm_cdc_ac_no" class="typeahead" value="" />
    </p>
    <span>No of Shares:</span>
    <p>
    <input type="text" size="50" name="cm_no_of_shares" id="cm_no_of_shares" class="typeahead" value="" />
    </p>
    </div>
  </div>
  <div class="dialog" id="b_popup_action_insurance" style="display: none;" title="Insurance">
  <div class="block"> 
    <div id="insurance_list"></div>
    <div id="insurance_agent"></div>
    <span>Policy No.</span>
    <p><input type="text" size="50" name="i_policy_no" id="i_policy_no" class="typeahead" value="" /></p>      
    <span>Sum Assured</span>
    <p><input type="text" size="50" name="i_sum_assured" id="i_sum_assured" class="typeahead" value="" /></p>   
    <span>Claim Amount</span>
    <p><input type="text" size="50" name="i_claim_amount" id="i_claim_amount" class="typeahead" value="" /></p>      
    <span>Folio No:</span>
    <p><input type="text" size="50" name="i_folio_no" id="i_folio_no" class="typeahead" value="" /></p>      
    <span>No of Shares:</span>
    <p><input type="text" size="50" name="i_no_of_shares" id="i_no_of_shares" class="typeahead" value="" /></p>
    </div>
  </div>
  <div class="dialog" id="b_popup_action_specialized_companies" style="display: none;" title="AMC/Mutual Funds/Modarabah/Leasing/Investment Banking/REIT">
  <div class="block">
    <div id="scd_list"></div>
    <div id="reit_scheme"></div>
    <div id="modaraba_fund"></div>
    <div id="mutual_fund"></div>
    <div id="pension_fund"></div>
    
    
    <span>Registration No./Folio No.</span>
    <p><input type="text" name="scd_folio_no" id="scd_folio_no" value="" ></p>
    <span>CDC A/C No:</span>
    <p><input type="text" name="scd_cdc_ac_no" id="scd_cdc_ac_no" value="" ></p>
    <span>No. of Units:</span>
    <p><input type="text" name="scd_no_of_units" id="scd_no_of_units" value="" ></p>
    </div>
  </div>
  <div class="dialog" id="b_popup_action_e_services" style="display: none;" title="e-Services">
  <div class="block"> 
    <span>Company Name</span>
    <p>
    <input type="text" name="e_company_title" id="input_product" autocomplete="off" value=""  />
    </p>
    
    <span>Company Registration Office*</span>
    <p id="e_cro_title"><input type="text" name="e_registration_office" id="e_registration_office" value="" ></p>
 
    <span>Process Name</span>
    <p><select name="e_process_name" id="e_process_name">
    <option value="">--Select Process--</option>
    <?php 
    $sql_process_names = "Select * from sdms_process_names  where type = 'eservices'";
    $res_process_names = db_query($sql_process_names);
    while($row_process_names = db_fetch_array($res_process_names)){
    ?>
    <option value="<?php echo $row_process_names['title']; ?>"><?php echo $row_process_names['title']; ?></option>
    <?php } ?>
    </select></p>
    <span>User ID</span>
    <p><input type="text" name="e_user_id" id="e_user_id" value="" ></p>
    </div>
  </div>
  <div class="dialog" id="b_popup_action_company_registration" style="display:none;" title="Company Registration/Compliance">
  <div class="block"> 
    <span>Please state your status:</span>
    <p>
    <input type="radio" size="50" name="cr_status" id="cr_status" class="typeahead" value="Member" />
    Member
    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
    <input type="radio" size="50" name="cr_status" id="cr_status" class="typeahead" value="Shareholder" />
    Shareholder 
    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
    <input type="radio" size="50" name="cr_status" id="cr_status" class="typeahead" value="Non Shareholder" />
    Non Shareholder
    </p>
    <div id="r_company_cro">
    <span>Name of Company:</span>
    <p>
	 <input type="text" name="cr_company_title" id="input_product1" autocomplete="off" value=""  /></p>
    <span>CRO:</span>
    <p id="r_cro_title"><input type="text" name="cr_cro" id="cr_cro" value="" ></p>
    </div>
    <div id="unregistered_entity"> 
    <span>Unregistered Entity :</span>
    <p>
    <select name="cr_unregistered_entity" id="cr_unregistered_entity" onChange="hide_cro_company();">
        <option value="">--Select Unregistered Entity--</option>
        <option value="Under Incorporation">Under Incorporation</option>
        <option value="Name Reservation">Name Reservation</option>
        <option value="Other">Other</option>   
    </select>
    </p>
    </div> 
    <div id="cr_folio_no_cr_no_of_shares">
    <span>Folio No:</span>
    <p><input type="text" name="cr_folio_no" id="cr_folio_no" value="" ></p>
    <span>No of Shares:</span>
    <p><input type="text" name="cr_no_of_shares" id="cr_no_of_shares" value="" ></p>
    </div>
    
   
    <span>Process Name :</span>
    <p>
    <select name="cr_process_name">
    <option value="">--Select Process--</option>
    <?php 
    $sql_process_names = "Select * from sdms_process_names  where type = 'companyregistration'";
    $res_process_names = db_query($sql_process_names);
    while($row_process_names = db_fetch_array($res_process_names)){
    ?>
    <option value="<?php echo $row_process_names['title']; ?>"><?php echo $row_process_names['title']; ?></option>
    <?php } ?>
    </select>
    </p>
    </div>
  </div>
  <div class="dialog" id="b_popup_action_company_supervision" style="display: none;" title="Supervision of Listed Companies">
  <div class="block"> 
    <span>Company Name :</span>
    <p>
     <input type="text" name="cs_company_title" id="input_product2" autocomplete="off" value=""  />
    </p>
    <span>CDC Account No:</span>
    <p><input type="text" name="cs_cdc_ac_no" id="cs_cdc_ac_no" value="" ></p>
    <span>Folio No:</span>
    <p><input type="text" name="cs_folio_no" id="cs_folio_no" value="" ></p>
    
    </div>
  </div>
  <div class="row-fluid">
    <div class="span12">
      <div class="footer tar">
       <img src="img/loaders/loader.gif" title="loader.gif" style="display:none;" id="loading_status" >
        <input type="submit" name="submit" value="Submit" class="btn" id="complaint_submit_btn">
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
