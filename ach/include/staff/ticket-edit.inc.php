<?php
if(!defined('OSTSCPINC') || !$thisstaff || !$thisstaff->canEditTickets() || !$ticket) die('Access Denied');
$info=Format::htmlchars(($errors && $_POST)?$_POST:$ticket->getUpdateInfo());
?>
<script type="text/javascript">
$( document ).ready(function() {
get_popup(<?php echo $info['deptId'] ?>);

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

});

function update_e_cro(company_title){
$.ajax({
url:"../get_e_cro.php",
data: "company_title="+company_title,
success: function(msg){
$("#e_cro_title").html(msg);
}
});}
function hide_cro_company(){
  $('#cr_folio_no_cr_no_of_shares').hide();
  $('#r_company_cro').hide();
}
function update_r_cro(company_title){
$.ajax({
url:"../get_r_cro.php",
data: "company_title="+company_title,
success: function(msg){
$("#r_cro_title").html(msg);
}
});}
function get_Sub_Com(comp_type){
$.ajax({
	url:"get_sub_category.php",
	data: "m_comp_type="+comp_type,
	success: function(msg){
	//alert(msg);	
	$("#show_sub_complaint").html(msg);
}
});}
function get_tehsils(dist_id){
$.ajax({
	url:"get_tehsils.php",
	data: "district_id="+dist_id,
	success: function(msg){
	$("#show_sub_tehsils").html(msg);
}
});}
function get_agency_tehsils(tehsil_id){
$.ajax({
	url:"get_agency_tehsils.php",
	data: "tehsil_id="+tehsil_id,
	success: function(msg){
	$("#show_agency_tehsils").html(msg);
}
});}
function get_popup(id){
	get_focal_person(id);
	if(id==2)
	{
		$("#show_against_insurance").css("display", "none");
		$("#show_against_s_c").css("display", "none");
		$("#show_against_s_m").css("display", "");
		//$('#popup_action_s_m').click();
	}
	else if(id==3){
		$("#show_against_s_m").css("display", "none");
		$("#show_against_s_c").css("display", "none");
		$("#show_against_insurance").css("display", "");
		//$('#popup_action_insurance').click();
	}
	
	
	
	else if(id==4){
		$("#show_against_s_m").css("display", "none");
		$("#show_against_insurance").css("display", "none");
		$("#show_against_s_c").css("display", "");
		//$('#popup_action_specialized_companies').click();
	}
	else if(id==5){
		$("#show_against_s_m").css("display", "none");
		$("#show_against_s_c").css("display", "none");
		$("#show_against_insurance").css("display", "none");
		$('#popup_action_e_services').click();
	}
	else if(id==6){
		$("#show_against_s_m").css("display", "none");
		$("#show_against_s_c").css("display", "none");
		$("#show_against_insurance").css("display", "none");
		$('#popup_action_company_registration').click();
	}
	else if(id ==18){
		$("#show_against_s_m").css("display", "none");
		$("#show_against_s_c").css("display", "none");
		$("#show_against_insurance").css("display", "none");
		$('#popup_action_company_supervision').click();
	}else{
	$("#show_against_s_m").css("display", "none");
		$("#show_against_s_c").css("display", "none");
		$("#show_against_insurance").css("display", "none");
	}}
function get_focal_person(dept_id){
	$.ajax({
	url:"get_focal_person.php",
	data: "dept_id="+dept_id,
	success: function(msg){
	$("#assignId").val(msg.trim());
	}
	});}
function show_popup_sm(b_type,company_title){
	$("#brokers_list").html('');
	//$("#brokers_agent").html('');
	$.ajax({
	url:"get_cm_brokers.php",
	data: "b_type="+b_type,
	data: "b_type="+b_type+"&company_title="+company_title,
	success: function(msg){	
	$("#brokers_list").html(msg);
	$('#popup_action_s_m').click();
}
});	}
function show_cm_agent_list(parent){
	$.ajax({
	url:"get_cm_brokers_agents.php",
	data: "parent="+parent,
	success: function(msg){
	$("#brokers_agent").html(msg);
	
}
});	}
function show_popup_insurance(b_type,company_title){
	$("#insurance_list").html('');
	//$("#insurance_agent").html('');
	$.ajax({
	url:"get_insurance_brokers.php",
	data: "b_type="+b_type+"&company_title="+company_title,
	success: function(msg){	
	$("#insurance_list").html(msg);
	$('#popup_action_insurance').click();
}
});	}
function show_insurance_agent_list(parent){
	$.ajax({
	url:"get_insurance_brokers_agents.php",
	data: "parent="+parent,
	success: function(msg){
	$("#insurance_agent").html(msg);
}
});	}
function show_popup_scd(b_type,company_title){
	$("#scd_list").html('');
	//$("#reit_scheme").html('');
	//$("#modaraba_fund").html('');
	//$("#mutual_fund").html('');
	//$("#pension_fund").html('');
	$.ajax({
	url:"get_scd_brokers.php",
	data: "b_type="+b_type+"&company_title="+company_title,
	success: function(msg){	
	$("#scd_list").html(msg);
	$('#popup_action_specialized_companies').click();
}
});	}
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
});}
</script>
<div class="page-header">
    <h1>Update Complaint <small>#<?php echo $ticket->getExtId(); ?></small></h1>
</div>
<form action="tickets.php?id=<?php echo $ticket->getId(); ?>&a=edit" method="post" id="save"  enctype="multipart/form-data">
<?php csrf_token(); ?>
<input type="hidden" name="do" value="update">
<input type="hidden" name="a" value="edit">
<input type="hidden" name="id" value="<?php echo $ticket->getId(); ?>">
 <input type="hidden" value="0" name="isquery" >
 <input type="hidden" name="deptId" value="<?php echo $info['deptId']; ?>" >
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
                    <li class="heading">Complaint Info</li>
                    <li>
                        <div class="title">Title:</div>
                        <div class="text"><input type="text" name="subject" size="60" readonly required value="<?php echo $info['subject']; ?>">
        &nbsp;<font class="error">*&nbsp;<?php $errors['subject']; ?></font></div>
                    </li>
                    <li>
                        <div class="title">Priority:</div>
                        <div class="text"><select name="priorityId">
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
                    <li>
                    <div class="title">Department:</div>
                    <div class="text"><select name="" disabled>
                    <?php
                    if($depts=Dept::getDepartments()) {
                    foreach($depts as $id =>$name) {
                    echo sprintf('<option value="%d" %s>%s</option>',
                    $id, ($info['deptId']==$id)?'selected="selected"':'',$name);
                    }
                    }
                    ?>
                    </select>
                    </div>
                    </li>
<?php
if($info['deptId']==2){
$sql_securities_markets="Select * from sdms_ticket_capital_markets where complaint_id ='".$ticket->getId()."'";
$res_securities_markets=mysql_query($sql_securities_markets);
$row_securities_markets=mysql_fetch_array($res_securities_markets);

$type = $row_securities_markets['cm_type'];
$cm_broker_title = $row_securities_markets['cm_broker_title']; 
$cm_broker_agent = $row_securities_markets['cm_broker_agent']; 
$cm_folio_no = $row_securities_markets['cm_folio_no'];
$cm_cdc_ac_no =  $row_securities_markets['cm_cdc_ac_no'];
$cm_no_of_shares = $row_securities_markets['cm_no_of_shares'];  }
else if($info['deptId']==3){
$sql_insurance="Select * from sdms_ticket_insurance where complaint_id ='".$ticket->getId()."'";
$res_insurance=mysql_query($sql_insurance);
$row_insurance=mysql_fetch_array($res_insurance);

$type = $row_insurance['i_type'];
$i_broker_title = $row_insurance['i_broker_title'];
$i_broker_agent =$row_insurance['i_broker_agent'];
$i_policy_no =  $row_insurance['i_policy_no'];
$i_sum_assured = $row_insurance['i_sum_assured'];
$i_claim_amount =  $row_insurance['i_claim_amount'];
$i_folio_no = $row_insurance['i_folio_no'];
$i_no_of_shares = $row_insurance['i_no_of_shares']; }
else if($info['deptId']==4){
$sql_ticket_scd="Select * from sdms_ticket_scd where complaint_id ='".$ticket->getId()."'";
$res_ticket_scd=mysql_query($sql_ticket_scd);
$row_ticket_scd=mysql_fetch_array($res_ticket_scd);

$type = $row_ticket_scd['scd_type'];
$scd_broker_title =  $row_ticket_scd['scd_broker_title'];
$reit_scheme = $row_ticket_scd['reit_scheme'];
$modaraba_fund =  $row_ticket_scd['modaraba_fund'];
$mutual_fund = $row_ticket_scd['mutual_fund'];
$pension_fund = $row_ticket_scd['pension_fund'];
$folio_no = $row_ticket_scd['scd_folio_no'];
$cdc_ac_no =  $row_ticket_scd['scd_cdc_ac_no'];
$no_of_units =  $row_ticket_scd['scd_no_of_units']; }
else if($info['deptId']==5){
$sql_ticket_e_services="Select * from sdms_ticket_e_services where complaint_id ='".$ticket->getId()."'";
$res_ticket_e_services=mysql_query($sql_ticket_e_services);
$row_ticket_e_services=mysql_fetch_array($res_ticket_e_services);

$company_title =  $row_ticket_e_services['e_company_title'];
$registration_office = $row_ticket_e_services['e_registration_office'];
$process_name =  $row_ticket_e_services['e_process_name'];
$user_id =  $row_ticket_e_services['e_user_id']; }
else if($info['deptId']==6){
$sql_ticket_cr="Select * from sdms_ticket_cr where complaint_id ='".$ticket->getId()."'";
$res_ticket_cr=mysql_query($sql_ticket_cr);
$row_ticket_cr=mysql_fetch_array($res_ticket_cr);

$status =  $row_ticket_cr['cr_status'];
$company_title =  $row_ticket_cr['cr_company_title'];
$cro =  $row_ticket_cr['cr_cro'];

$cr_unregistered_entity = $row_ticket_cr['cr_unregistered_entity'];

$folio_no = $row_ticket_cr['cr_folio_no'];
$no_of_shares =  $row_ticket_cr['cr_no_of_shares'];
$process_name = $row_ticket_cr['cr_process_name'];
}
else if($info['deptId']==18){
$sql_ticket_cs="Select * from sdms_ticket_cs where complaint_id ='".$ticket->getId()."'";
$res_ticket_cs=mysql_query($sql_ticket_cs);
$row_ticket_cs=mysql_fetch_array($res_ticket_cs);

$company_title =  $row_ticket_cs['cs_company_title'];
$cdc_ac_no = $row_ticket_cs['cs_cdc_ac_no'];
$folio_no = $row_ticket_cs['cs_folio_no'];
}	
?>
<li id="show_against_s_m" style="display:none;">
<div class="title">Types:</div>
<div class="text">
<?php 
$sql_cm_tyes = "Select * from sdms_capital_markets Where 1 GROUP BY type";
$res_cm_tyes = db_query($sql_cm_tyes);
while( $row_cm_tyes = db_fetch_array($res_cm_tyes)){
?>
<input type="radio" name="cm_type" value="<?php echo $row_cm_tyes['type']; ?>" <?php if($type == $row_cm_tyes['type']){ ?> checked <?php }?> onClick="show_popup_sm(this.value,'<?php echo $cm_broker_title; ?>');"><?php echo $row_cm_tyes['type']; ?>
<Br>
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
<input type="radio" name="i_type" value="<?php echo $row_insurance_tyes['type']; ?>" <?php if($type == $row_insurance_tyes['type']){ ?> checked <?php }?>  onClick="show_popup_insurance(this.value,'<?php echo urlencode($i_broker_title); ?>');"><?php echo $row_insurance_tyes['type']; ?><Br>
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
<input type="radio" name="scd_type" value="<?php echo $row_scd_tyes['type']; ?>" <?php 
  if ( $type == $row_scd_tyes['type']  ){ ?> checked <?php } ?>
  onClick="show_popup_scd(this.value,'<?php echo $scd_broker_title; ?>');"><?php echo $row_scd_tyes['type']; ?><Br>
<?php }?>
&nbsp;<font class="error">&nbsp;<?php echo $errors['deptTypeId']; ?></font>
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
<li class="heading"><em><strong>Internal Note</strong>: Reason for editing the Complaint (required)</em></li>
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
 <a id="popup_action_s_m" style="display:none;">Securities Markets</a> 
  <a id="popup_action_insurance" style="display:none;">Insurance</a> 
  <a id="popup_action_specialized_companies" style="display:none;">Specialized Companies</a> 
  <a id="popup_action_e_services" style="display:none;">e-Services</a> 
  <a id="popup_action_company_registration" style="display:none;">Company Registration</a> 
  <a id="popup_action_company_supervision" style="display:none;">Complaint Against CRO</a>
  <div class="dialog" id="b_popup_action_s_m" style="display: none;" title="Capital Markets">
    <div class="block">
    <div id="brokers_list"></div>
    <div id="brokers_agent">
    <?php 
	$sql="SELECT * FROM sdms_capital_markets WHERE parent='".$cm_broker_title."' AND child_agent!=''  group by child_agent ";
if(($res=db_query($sql)) && db_num_rows($res)) {
?>
<span>Agent List:</span>
<p>
<select name="cm_broker_agent">
<option value="">&mdash; Select Brokers Agents &mdash;</option>
<?php
while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['child_agent'];?>" <?php if($cm_broker_agent == $row['child_agent']){ ?> selected <?php } ?>><?php echo $row['child_agent']; ?></option>
<?php 
}
?>
</select>
</p>
<?php } ?>
	
    </div>   
    <span>Please Select at least from (Folio Number) OR (CDC A/C No.) (not required)</span><br>
    <span>Folio No:</span>
    <p>
    <input type="text" size="50" name="cm_folio_no" id="cm_folio_no" class="typeahead" value="<?php echo $cm_folio_no; ?>" />
    </p>
    <span>CDC A/C No:</span>
    <p>
    <input type="text" size="50" name="cm_cdc_ac_no" id="cm_cdc_ac_no" class="typeahead" value="<?php echo $cm_cdc_ac_no ?>" />
    </p>
    <span>No of Shares:</span>
    <p>
    <input type="text" size="50" name="cm_no_of_shares" id="cm_no_of_shares" class="typeahead" value="<?php echo $cm_no_of_shares; ?>" />
    </p>
    </div>
  </div>
  <div class="dialog" id="b_popup_action_insurance" style="display: none;" title="Insurance">
  <div class="block"> 
    <div id="insurance_list"></div>
    <div id="insurance_agent">
    <?php 
	$sql="SELECT * FROM sdms_insurance WHERE parent='".$i_broker_title."' AND child_agent!=''  group by child_agent ";
if(($res=db_query($sql)) && db_num_rows($res)) {
?>
<span>Agent List:</span>
<p>
<select name="i_broker_agent">
<option value="">&mdash; Select Brokers Agents &mdash;</option>
<?php
while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['child_agent'];?>" <?php if($i_broker_agent == $row['child_agent']){ ?> selected <?php } ?>><?php echo $row['child_agent']; ?></option>
<?php 
}
?>
</select>
</p>
<?php } ?>
    </div>
    <span>Policy No.</span>
    <p><input type="text" size="50" name="i_policy_no" id="i_policy_no" class="typeahead" value="<?php echo $i_policy_no; ?>" /></p>      
    <span>Sum Assured</span>
    <p><input type="text" size="50" name="i_sum_assured" id="i_sum_assured" class="typeahead" value="<?php echo $i_sum_assured; ?>" /></p>   
    <span>Claim Amount</span>
    <p><input type="text" size="50" name="i_claim_amount" id="i_claim_amount" class="typeahead" value="<?php echo $i_claim_amount; ?>" /></p>      
    <span>Folio No:</span>
    <p><input type="text" size="50" name="i_folio_no" id="i_folio_no" class="typeahead" value="<?php echo $i_folio_no; ?>" /></p>      
    <span>No of Shares:</span>
    <p><input type="text" size="50" name="i_no_of_shares" id="i_no_of_shares" class="typeahead" value="<?php echo $i_no_of_shares; ?>" /></p>
    </div>
  </div>
  <div class="dialog" id="b_popup_action_specialized_companies" style="display: none;" title="AMC/Mutual Funds/Modarabah/Leasing/Investment Banking/REIT">
  <div class="block">
    <div id="scd_list"></div>
    <div id="reit_scheme">
	<?php
$sql="SELECT * FROM sdms_scd WHERE parent='".$scd_broker_title."' AND reit_scheme!=''  group by reit_scheme ";
if(($res=db_query($sql)) && db_num_rows($res)) { ?>
<span>REIT Scheme:</span>
<p>
<select name="reit_scheme">
<option value="">&mdash; Select REIT Scheme &mdash;</option>
<?php while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['reit_scheme'];?>" <?php if($reit_scheme == $row['reit_scheme']){ ?> selected <?php } ?> ><?php echo $row['reit_scheme']; ?></option>
<?php } ?>
</select></p>
<?php } ?>
	 </div>
    <div id="modaraba_fund">
    <?php
    $sql="SELECT * FROM sdms_scd WHERE parent='".$scd_broker_title."' AND modaraba_fund!=''  group by modaraba_fund ";
if(($res=db_query($sql)) && db_num_rows($res)) { ?>
<span>Modaraba Fund:</span>
<p><select name="modaraba_fund">
<option value="">&mdash; Select Modaraba Fund &mdash;</option>
<?php while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['modaraba_fund'];?>" <?php if($modaraba_fund == $row['modaraba_fund']){ ?> selected <?php } ?>><?php echo $row['modaraba_fund']; ?></option>
<?php } ?>
</select></p>
<?php } ?>
    </div>
    <div id="mutual_fund">
    <?php 
	$sql="SELECT * FROM sdms_scd WHERE parent='".$scd_broker_title."' AND mutual_fund!=''  group by mutual_fund ";
if(($res=db_query($sql)) && db_num_rows($res)) { ?>
<span>Mutual Fund :</span>
<p>
<select name="mutual_fund">
<option value="">&mdash; Select Mutual Fund &mdash;</option>
<?php while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['mutual_fund'];?>" <?php if($mutual_fund == $row['mutual_fund']){ ?> selected <?php } ?> ><?php echo $row['mutual_fund']; ?></option>
<?php } ?>
</select></p>
	<?php } ?>
    </div>
    <div id="pension_fund">
    <?php 
	$sql="SELECT * FROM sdms_scd WHERE parent='".$scd_broker_title."' AND pension_fund!=''  group by pension_fund ";
if(($res=db_query($sql)) && db_num_rows($res)) { ?>
<span>Pension Fund:</span>
<p>
<select name="pension_fund">
<option value="">&mdash; Select Pension Fund &mdash;</option>
<?php while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['pension_fund'];?>"  <?php if($pension_fund == $row['pension_fund']){ ?> selected <?php } ?>><?php echo $row['pension_fund']; ?></option>
<?php } ?>
</select></p>
<?php } ?>
    </div>
    
    <span>Registration No./Folio No.</span>
    <p><input type="text" name="scd_folio_no" id="scd_folio_no" value="<?php echo $folio_no; ?>" ></p>
    <span>CDC A/C No:</span>
    <p><input type="text" name="scd_cdc_ac_no" id="scd_cdc_ac_no" value="<?php echo $cdc_ac_no; ?>" ></p>
    <span>No. of Units:</span>
    <p><input type="text" name="scd_no_of_units" id="scd_no_of_units" value="<?php echo $no_of_units; ?>" ></p>
    </div>
  </div>
  <div class="dialog" id="b_popup_action_e_services" style="display: none;" title="e-Services">
  <div class="block"> 
    <span>Company Name</span>
    <p><input type="text" name="e_company_title" id="input_product" autocomplete="off" value="<?php echo $company_title; ?>"  /></p>
    <span>Company Registration Office*</span>
    <p id="e_cro_title"><input type="text" name="e_registration_office" value="<?php echo $registration_office; ?>" ></p>
    <span>Process Name</span>
    <p><select name="e_process_name">
    <option value="">--Select Process--</option>
    <?php 
    $sql_process_names = "Select * from sdms_process_names  where type = 'eservices'";
    $res_process_names = db_query($sql_process_names);
    while($row_process_names = db_fetch_array($res_process_names)){
    ?>
    <option value="<?php echo $row_process_names['title']; ?>" <?php if($process_name == $row_process_names['title']){ ?> selected <?php } ?>><?php echo $row_process_names['title']; ?></option>
    <?php } ?>
    </select></p>
    <span>User ID</span>
    <p><input type="text" name="e_user_id" value="<?php echo $user_id; ?>" ></p>
    </div>
  </div>
  <div class="dialog" id="b_popup_action_company_registration" style="display:none;" title="Company Registration/Compliance">
  <div class="block"> 
    <span>Please state your status:</span>
    <p>
    <input type="radio" size="50" name="cr_status" id="cr_status" <?php if ( $status == 'Member'  ){ ?> checked <?php } ?> class="typeahead" value="Member" />
    Member
    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
    <input type="radio" size="50" name="cr_status" id="cr_status" <?php if ( $status == 'Shareholder'  ){ ?> checked <?php } ?> class="typeahead" value="Shareholder" />
    Shareholder 
    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
    <input type="radio" size="50" name="cr_status" id="cr_status" <?php if ( $status == 'Non Shareholder'  ){ ?> checked <?php } ?> class="typeahead" value="Non Shareholder" />
    Non Shareholder
    </p>
    <div id="r_company_cro">
    <span>Name of Company:</span>
    <p>
	 <input type="text" name="cr_company_title" id="input_product1" autocomplete="off" value="<?php echo $company_title; ?>"  /></p>
      <span>CRO:</span>
    <p id="r_cro_title"><input type="text" name="cr_cro" id="cr_cro" value="<?php echo $cro; ?>" ></p>
    </div>
    <div id="unregistered_entity"> 
    <span>Unregistered Entity :</span>
    <p>
    <select name="cr_unregistered_entity" onChange="hide_cro_company();">
        <option value="">--Select Unregistered Entity--</option>
        <option value="Under Incorporation"  <?php if ( $cr_unregistered_entity == 'Under Incorporation'  ){ ?> selected <?php } ?> >Under Incorporation</option>
        <option value="Name Reservation"  <?php if ( $cr_unregistered_entity == 'Name Reservation'  ){ ?> selected <?php } ?> >Name Reservation</option>
        <option value="Other"  <?php if ( $cr_unregistered_entity == 'Other'  ){ ?> selected <?php } ?> >Other</option>   
    </select>
    </p>
    </div> 
    <div id="cr_folio_no_cr_no_of_shares">
    <span>Folio No:</span>
    <p><input type="text" name="cr_folio_no" id="cr_folio_no" value="<?php echo $folio_no; ?>" ></p>
    <span>No of Shares:</span>
    <p><input type="text" name="cr_no_of_shares" id="cr_no_of_shares" value="<?php echo $no_of_shares; ?>" ></p>
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
    <option value="<?php echo $row_process_names['title']; ?>" <?php if ( $process_name == $row_process_names['title']  ){ ?> selected <?php } ?>><?php echo $row_process_names['title']; ?></option>
    <?php } ?>
    </select>
    </p>
    </div>
  </div>
  <div class="dialog" id="b_popup_action_company_supervision" style="display: none;" title="Supervision of Listed Companies">
  <div class="block"> 
    <span>Company Name :</span>
    <p>
     <input type="text" name="cs_company_title" id="input_product2" autocomplete="off" value="<?php echo $company_title; ?>"  />
    </p>
    <span>CDC Account No:</span>
    <p><input type="text" name="cs_cdc_ac_no" id="cs_cdc_ac_no" value="<?php echo $cdc_ac_no; ?>" ></p>
    <span>Folio No:</span>
    <p><input type="text" name="cs_folio_no" id="cs_folio_no" value="<?php echo $folio_no; ?>" ></p>
    
    </div>
  </div>  
 <div class="row-fluid">
 <div class="span12">
 <div class="footer tar">
<input type="submit" name="submit" value="Save" class="btn">
<input type="reset"  name="reset"  value="Reset" class="btn">
<input type="button" name="cancel" value="Cancel" onclick='window.location.href="tickets.php?id=<?php echo $ticket->getId(); ?>"' class="btn">
</div>
 </div>
 </div>    
<div class="dr"><span></span></div> 
</div><!--WorkPlace End-->  
</div> 
