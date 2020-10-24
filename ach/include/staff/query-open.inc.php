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
		});
/* file upload jquery code. */
$(document).ready(function()
{
		
$( "#input_product" ).autocomplete({
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
	$("#query_submit_btn").attr('disabled',true);
	$("#loading_status").css('display', '');
	$.ajax({
	url:"get_focal_person.php",
	data: "dept_id="+dept_id,
	success: function(msg){
	$("#assignId").val(msg.trim());
	$("#query_submit_btn").attr('disabled',false);	
	$("#loading_status").css('display', 'none');
	}
	});

	$.ajax({
	url:"get_category_by_deptID.php",
	data: "dept_id="+dept_id,
	success: function(msg){
	$("#1_tiers").html(msg);
	$("#query_submit_btn").attr('disabled',false);	
	$("#loading_status").css('display', 'none');
	$("#1_tiers").css('display', '');
	}
	});

	if(dept_id ==1)
	{
		$("#is_query_li").css('display', '');	
	}else{
		//$("#is_query_checkbox").prop("checked", false);
		$('#is_query_checkbox').attr('checked', false);
		$("#is_query_li").css('display', 'none');
	}


}
function get_subcategory(cat_id,s_id){
	var select_id = s_id.split("_");
	for(x=parseInt(select_id[1])+1;x<=10;x++){
	$("#"+x+"_tiers").remove();
	}
	var tiers = parseInt(select_id[1]);
	$("#select_" + tiers).attr('name', 'topicId');
	var next_tiers = tiers+1;   
	//alert(next_tiers);
	$.ajax({
	url:"get_sub_category.php",
	data: "cat_id="+cat_id+"&next_tiers="+next_tiers+"&isnature=1",
	success: function(msg){
	if(msg.trim() != ''){
		var message = msg.replace("span3", "title");
		var new_message = message.replace("span9", "text");

	$("#"+tiers+"_tiers").after('<li id="'+next_tiers+'_tiers" ></li>');
	$("#tiers").val(next_tiers);
	$("#"+next_tiers+"_tiers").html(new_message);
	}
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
                <input type="text" size="50" name="name" value="<?php echo $info['name']; ?>" required >
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['name']; ?></span> </div>
                </li>
                <li>
                <div class="title">CNIC/NICOP/PSPT:</div>
                <div class="text"><!--id="nic"-->
                <input  type="text" name="nic" size="30" value="9999999999999"  style="width:287px;" required autocomplete="9999999999999" >
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['nic']; ?></span> <!--<span>Example: 99999-9999999-9</span>--> </div>
                </li>
                <li>
                <div class="title">Mobile Number :</div>
                <div class="text">
                <input type="text" name="phone" id="mobile_number" maxlength="11" value="03000000000" style="width:287px;" required autocomplete="03009999999">
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
                <input type="email" size="50" name="email" id="email" class="typeahead" value="<?php echo $info['email']; ?>" autocomplete="off" autocapitalize="off"  style="width:287px;">
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
                <textarea name="applicant_address" rows="2"  style="width:287px;">Information Not Available</textarea>
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
                  <option value="At Counter"        <?php echo ($info['source']=='At Counter')?'selected="selected"':''; ?>>At Counter</option>
                  <option value="By Email"          <?php echo ($info['source']=='By Email')?'selected="selected"':''; ?>>Email</option>
                  <option value="By Post"           <?php echo ($info['source']=='By Post')?'selected="selected"':''; ?>>Post</option>
                  <option value="By Fax"            <?php echo ($info['source']=='By Fax')?'selected="selected"':''; ?>>Fax</option>
                  <option value="By Dropbox"            <?php echo ($info['source']=='By Dropbox')?'selected="selected"':''; ?>>Dropbox</option>
                  <option value="SECP Chatbot"        <?php echo ($info['source']=='SECP Chatbot')?'selected="selected"':''; ?>>SECP Chatbot</option>
                </select>
                &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['source']; ?></font> </div>
            </li>
            <li>
                        <div class="title">Priority:</div>
                        <div class="text"><select name="priorityId" style="width:301px;" required>
    <option value="0" selected >&mdash; System Default &mdash;</option>
    <?php
	$info['priorityId']=2;
    if($priorities=Priority::getPriorities()) {
    foreach($priorities as $id =>$name) {
        echo sprintf('<option value="%d" %s>%s</option>',$id, ($info['priorityId']==$id)?'selected="selected"':'',$name);
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
                <input type="text"  name="subject" size="60" value="<?php echo $info['subject']; ?>" required  style="width:287px;">
                &nbsp; <font class="error">*&nbsp;<?php echo $errors['subject']; ?></font> </div>
            </li>
            <li>
              <div class="title">Detail: <em>Details on the reason(s) for opening the Query.</em> <br />
                Maximum 150 Characters</div>
              <div class="text">
                <textarea name="issue" cols="21" rows="8" style="width: 289px; height: 153px;" required><?php echo $info['issue']; ?></textarea>
                <font class="error">*&nbsp;<?php echo $errors['issue']; ?></font></div>
            </li>
			<li style="display: none;" id="is_query_li">
                <div class="title">Query Action:</div>
                <div class="text">
                 <input type="checkbox" name="query_rsolved" value="1" id="is_query_checkbox">
                  Resolved Query.
                  </div>
              </li> 
            <li style="display: none;" id="1_tiers">
                <div class="title">Category:</div>
                <div class="text"></div>
			</li>
			
			<input type="hidden" value="1" name="tiers" id="tiers">
			<li id="2_tiers" style="display: none;"></li>
			<li id="3_tiers" style="display: none;"></li>
			<li id="4_tiers" style="display: none;"></li>
		

            <input type="hidden" id="assignId" name="assignId" value="0">
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
 
    
  </div>
  
  
  
  
  
  <div class="row-fluid">
    <div class="span12">
      <div class="footer tar">
      <img src="img/loaders/loader.gif" title="loader.gif" style="display:none;" id="loading_status" >
        <input type="submit" name="submit" value="Submit" class="btn" id="query_submit_btn">
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
