<?php
if(!defined('OSTCLIENTINC')) die('Access Denied!');
$info=array();
if($thisclient && $thisclient->isValid()) {
    $info=array('name'=>$thisclient->getName(),
                'email'=>$thisclient->getEmail(),
                'phone'=>$thisclient->getPhone(),
                'phone_ext'=>$thisclient->getPhoneExt());
}
$info=($_POST && $errors)?Format::htmlchars($_POST):$info;
?>
<style>
label {
	display: inline-block;
	float: left;
	font-weight: bold;
	margin-bottom: 5px;
	max-width: 100%;
}
</style>
<div class="container" style="background-color: white;">
  <form  method="post" action="open_query.php" enctype="multipart/form-data" id="wizard1" class="wizard comp_form">
    <?php csrf_token(); ?>
    <input type="hidden" name="a" value="open">
    <input type="hidden" id="assignId" name="assignId" value="0">
    <input type="hidden" id="deptId" name="deptId" value="0">
    <input type="hidden" name="topicId" value="43">
   <input type="hidden" value="1" name="isquery" >
    <div class="wizard-header" style="display:none;">
      <ul class="nav nav-tabs">
        <li role="presentation" class="wizard-step-indicator"><a id="li_profile" href="#profile">Profile</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_verification" href="#verification">Verification</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_deprts" href="#deprts">Departments</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_finish" href="#finish">Finish</a></li>
      </ul>
    </div>
    <div class="wizard-content">
      <div id="profile" class="wizard-step">
        <div class="col-lg-8 padding-top-10">
          <div class="col-xs-12">
            <h4 style="color: #202020;font-weight: bold;">Personal Details of Querier</h4>
          </div>
          <div class="form-group">
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Full Name<span style="color: red;font-size: 13px;">*</span> </label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" name="name" class="form-control backrnd" required>
            </div>
            
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">CNIC/NICOP/Passport <span style="color: red;font-size: 13px;">*</span></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" class="form-control backrnd" name="nic" required><!---->
            </div>
            
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Mobile Number<span style="color: red;font-size: 13px;">*</span></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" class="form-control backrnd" name="phone" id="phone" required><!---->
              <!--required--> 
            </div>

            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Email </label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="email" class="form-control backrnd" name="email" >
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Country <span style="color: red;font-size: 13px;">*</span></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group has-feedback">
              <select id="jumpMenu" name="district" onChange="get_tehsils(this.value);" class="form-control backrnd" required>
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
            </div>
            <div id="show_sub_tehsils"></div>
            <div id="show_agency_tehsils"></div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Postal Address <span style="color: red;font-size: 13px;">*</span></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group has-feedback">
              <textarea name="applicant_address" class="form-control" required></textarea>
            </div>
          </div>
          <div class="col-xs-12">
            <button type="button" class="wizard-next btn btn-primary float-right" onClick="set_sms_verify();" >Next</button>
          </div>
          <div class="clearfix hidden-lg"></div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar_query.php');?>
        </div>
      </div>
      <div id="verification" class="wizard-step">
        <div class="col-lg-8 padding-top-10">
          <div class="form-group">
            <div class="col-xs-12">
              <h3 style="color: #202020;">SMS Verification</h3>
            </div>
            <div class="col-xs-12 text-center">
              <h4 style="color: red;font-weight: bold;">Enter the 4 digit code received on your mobile phone.</h4>
            </div>
            <div class="col-xs-12 text-center" ><!--required-->
              <input type="text" name="sms_vrify" id="sms_code"  class="form-control" placeholder="Enter Your Code" style="width: 40%; margin: 0 auto;"   >
              <span class="fa fa-info-circle errspan"></span> <img class="img-responsive" src="assets/images/sms.png" width="60%" style="margin:0 auto;height: 165px;"> </div>
            <div class="col-xs-offset-3 col-xs-6">
              <button type="button" class="btn btn-primary float-right" onClick="check_sms_verify();"  style="width: 100%;margin: 0 auto;margin-top: 40px;padding-top: 10px;">Next</button>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar_query_2.php');?>
        </div>
      </div>
      <div id="deprts" class="wizard-step">
        <div class="col-lg-8 padding-top-10">
          <div class="form-group">
            <div class="col-xs-12">
              <h3 style="color: #202020;font-weight: bold;">To make your relevant selection, please click on the question mark available next to each option for further guidance.</h3>
               <div class="col-lg-5 col-xs-9 padding-top-10">
                <label>
                <input type="radio" name="m-system" value="5"  onClick="goto_types(this.value);"  />
                <img class="img-responsive" src="assets/images/e-services.jpg"> </label>
                </div>
                <div class="col-lg-1 col-xs-2 padding-top-20"> <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal5"></i> </span> </div>
                <div class="col-lg-5 col-xs-9 padding-top-10">
                <label>
                <input type="radio" name="m-system" value="6"  onClick="goto_types(this.value);" />
                <img class="img-responsive" src="assets/images/company_registration.jpg"> </label>
                </div>
                <div class="col-lg-1 col-xs-2 padding-top-20"> <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal6"></i> </span> </div>
              <div class="col-lg-5 col-xs-9 padding-top-10">
                <label>
                  <input type="radio" name="m-system" value="18"  onClick="goto_types(this.value);"  />
                  <img class="img-responsive" src="assets/images/company_supervisoin.jpg"> </label>
              </div>
              <div class="col-lg-1 col-xs-2  padding-top-20"> <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal18"></i> </span> </div>  
              <div class="col-lg-5 col-xs-9  padding-top-10">
                <label>
                  <input type="radio" name="m-system" value="2" onClick="goto_types(this.value);" />
                  <img class="img-responsive" src="assets/images/security.png"> </label>
              </div>
              <div class="col-lg-1 col-xs-2 padding-top-20"> <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal2"></i> </span> </div>
              
              <div class="col-lg-5 col-xs-9 padding-top-10">
                <label>
                  <input type="radio" name="m-system" value="3" onClick="goto_types(this.value);" />
                  <img class="img-responsive" src="assets/images/insurance.jpg"> </label>
              </div>
              <div class="col-lg-1 col-xs-2 padding-top-20"> <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal3"></i> </span> </div>
              
              <div class="col-lg-5 col-xs-9  padding-top-10">
                <label>
                  <input type="radio" name="m-system" value="4"  onClick="goto_types(this.value);" />
                  <img class="img-responsive" src="assets/images/specailzed.jpg"> </label>
              </div>
              <div class="col-lg-1 col-xs-2  padding-top-20"> <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal4"></i> </span> </div>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar_query_3.php');?>
        </div>
      </div>
      <div id="finish" class="wizard-step">
        <div class="col-lg-8 padding-top-10" style="color: black;">
          <div class="col-xs-12">
            <h2 style="text-align: left;">Query Information </h2>
          </div>
          <div class="form-group">
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Subject <span style="color: red;font-size: 13px;">*</span> </label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" name="subject" class="form-control backrnd" required>
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Details</label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group has-feedback">
              <textarea name="message" class="form-control" rows="5"></textarea>
            </div>
          </div>
          <?php
				if($cfg && $cfg->isCaptchaEnabled() && (!$thisclient || !$thisclient->isValid())) {
				if($_POST && $errors && !$errors['captcha'])
				$errors['captcha']='Please re-enter the text again';
				?>
				<div class="col-lg-6 col-xs-12 form-group has-feedback">
                <label>Captacha</label>
                 </div>
                  <div class="col-lg-6 col-xs-12 form-group has-feedback">
				<span class="captcha"><img src="captcha.php" border="0" align="left"></span>
				<input id="captcha" type="text" name="captcha" size="6" required  tabindex="14" title="Enter the text as shown in the figure" class="field">
				</div>
				<?php }?> 
          <div class="col-xs-12">
            <button type="submit" class="btn btn-primary float-right" onclick="return confirm('Are you sure you wish to submit your query?')">Submit</button>
                   <img src="assets/images/ajax-loader.gif" class="float-right" style="width:40px;height:40px;display:none;" id="loding_form">
              <button type="button" class="btn btn-primary float-right" onClick="goto_dept();" style="margin-right: 8px;">Previous</button>
              
          </div>
          <div class="clearfix hidden-lg"></div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar_query_4.php');?>
        </div>
      </div>
    </div>
  </form>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal2" role="dialog">
  <div class="modal-dialog"> 
    
    <!-- Modal content-->
    <div class="modal-content" style="border: 14px solid #929292">
      <div class="modal-header">
        <button type="button" class="close cus_style" data-dismiss="modal">&times;</button>
        <h2 class="modal-title text-center" style="color: #a5b287;"> Capital Markets </h2>
      </div>
      <div class="modal-body text-center" style="color: #a5b287;font-size: 12px;">
        <p style="line-height: 18px;"> If you wish to lodge a complaint related to Capital Markets, its intermediaries (such as CDC, NCCPL, PSX, PMEX etc.) <br>
          <br>
          OR<br>
          Matters related to trading of shares of Listed Companies <br>
          OR<br>
          PSX Brokers/Agents, PMEX Broker, Book Runner, Share Registrar etc. <br>
          OR<br>
          Acquirer/Company in case of takeovers <br>
          OR<br>
          pertaining to Beneficial Ownership filing <br>
          OR<br>
          Insider Trading, Market Rumors/Manipulation/Abuses etc. <br>
          OR<br>
          Unauthorized trading, Non Receipt of Account Trading Statements etc.
          please select this domain. </p>
        <p style="line-height: 18px;"> If you wish to lode a complaint related to Capital Markets, its intermediaries(such as CDC,NCCPL,PSX,PMEX etc.) <br>
          OR <br>
          Matters related to trading of shares of listed Companies <br>
          OR <br>
          PSX Brokers/Agents,PMEX Broker,Book Runner,Share Registrar etc.<br>
          OR <br>
          Acquirer/Company in case of takeouers <br>
          OR <br>
          Pertaining to Beneficial Ownership Filing <br>
          OR <br>
          Insider Trading, Marker Rumors/Manipultion/Abuses etc. <br>
          OR <br>
          Unauthorized trading, Non Receipt of Account Trading Statements etc. </p>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="myModal3" role="dialog">
  <div class="modal-dialog"> 
    
    <!-- Modal content-->
    <div class="modal-content" style="border: 14px solid #929292">
      <div class="modal-header">
        <button type="button" class="close cus_style" data-dismiss="modal">&times;</button>
        <h2 class="modal-title text-center" style="color: #a5b287;"> Insurance </h2>
      </div>
      <div class="modal-body text-center" style="color: #a5b287;font-size: 12px;">
        <p style="line-height: 18px;"> If you wish to lodge a complaint related to Insurance Companies, Insurance Brokers or Agents, Surveyors Or any other matter related to the Insurance industry <br>
          Please select this domain.<br>
        </p>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="myModal4" role="dialog">
  <div class="modal-dialog"> 
    <!-- Modal content-->
    <div class="modal-content" style="border: 14px solid #929292">
      <div class="modal-header">
        <button type="button" class="close cus_style" data-dismiss="modal">&times;</button>
        <h2 class="modal-title text-center" style="color: #a5b287;"> AMC/Mutual Funds/Modarabah/Leasing/Investment Banking/REIT </h2>
      </div>
      <div class="modal-body text-center" style="color: #a5b287;font-size: 12px;">
        <p style="line-height: 18px;"> If you wish to lodge a complaint related to Asset Management Companies, Mutual Funds, Pension Funds, Investment Advisors, Real Estate Investment Trusts (REITS), Leasing Companies, Investment Banks, Microfinance Companies, Housing Finance Companies, Discount Houses, Private Fund Management Companies, Private Equity Fund, Venture Capital Fund, Alternate Fund Modaraba Management Companies OR Modaraba.<br>
          Please select this domain.<br>
        </p>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="myModal5" role="dialog">
  <div class="modal-dialog"> 
    
    <!-- Modal content-->
    <div class="modal-content" style="border: 14px solid #929292">
      <div class="modal-header">
        <button type="button" class="close cus_style" data-dismiss="modal">&times;</button>
        <h2 class="modal-title text-center" style="color: #a5b287;"> e-Services </h2>
      </div>
      <div class="modal-body text-center" style="color: #a5b287;font-size: 12px;">
        <p style="line-height: 18px;"> If you are a first time user or an existing user of e-Services and wish to report a complaint regarding any e-Services related processes, <br>
          Please select this domain.<br>
        </p>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="myModal6" role="dialog">
  <div class="modal-dialog"> 
    <!-- Modal content-->
    <div class="modal-content" style="border: 14px solid #929292">
      <div class="modal-header">
        <button type="button" class="close cus_style" data-dismiss="modal">&times;</button>
        <h2 class="modal-title text-center" style="color: #a5b287;"> Company Registration/Compliance </h2>
      </div>
      <div class="modal-body text-center" style="color: #a5b287;font-size: 12px;">
     
      
        <p style="line-height: 18px;">
        
        If you wish to lodge a complaint related to private/public unlisted companies including public sector companies OR not for profit companies, please select this domain.
        <br>
          OR<br>
         If you wish to lodge a complaint with the concerned registrar regarding:<br>
          registration of information (such as filing of returns, submission of applications for seeking approvals etc.),<br>
          <br>
          OR<br>
          provision of information by registrar concerned (such as certified copies of documents, memorandum of association/articles of association. financial statements and inspection of records of companies, etc.)<br>
          <br>
          OR<br>
          non-compliance with provisions of the companies ordinance,<br>
          Please select this domain.<br>
        </p>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="myModal18" role="dialog">
  <div class="modal-dialog"> 
    
    <!-- Modal content-->
    <div class="modal-content" style="border: 14px solid #929292">
      <div class="modal-header">
        <button type="button" class="close cus_style" data-dismiss="modal">&times;</button>
        <h2 class="modal-title text-center" style="color: #a5b287;">Supervision of Listed Companies</h2>
      </div>
      <div class="modal-body text-center" style="color: #a5b287;font-size: 12px;">
        <p style="line-height: 18px;"> If you wish to lodge a complaint related to transfer of shares,<br>
          <br>
          OR<br>
          Matters related to issue and payment of dividends <br>
          OR<br>
          Matters related to receipt of Annual and quarterly accounts or notice of shareholding meetings <br>
          OR<br>
          Matters related to availability of complete company information on website <br>
          OR<br>
          Want to inform fraud /siphoning of assets in the Company by the management or its employees etc.<br>
          Please select this domain.<br>
        </p>
      </div>
    </div>
  </div>
</div>

<!-- modal section end --> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.14.0/jquery.validate.min.js"></script> 
<script src="assets/js/jquery.simplewizard.js"></script> 
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
$("#wizard1").submit(function(){
  $(this).find(':submit').attr('disabled','disabled');
  $('#loding_form').show();
});

$(function () {
$("#wizard1").simpleWizard({
cssClassStepActive: "active",
cssClassStepDone: "done",
onFinish: function() {
}
});
$( "#input_product" ).autocomplete({
source: "search_company_name.php",
minLength: 1
});

});
function get_focal_person(dept_id){
	$.ajax({
	url:"get_focal_person.php",
	data: "dept_id="+dept_id,
	success: function(msg){
	$("#assignId").val(msg.trim());
	}
	});}
function goto_types(dept_id){
get_focal_person(dept_id);
$("#deptId").val(dept_id);
goto_last();
}
function goto_last(){
$('#li_finish').click();
}
function goto_dept(){
$('#li_deprts').click();
}

function set_sms_verify(){
var user_mobile = $('#phone').val();
if(user_mobile!='')
{
	$.ajax({
	url:"send_sms_to_mobile.php",
	data: "user_mobile="+user_mobile,
	success: function(msg){	
	$("#sms_verification_number").html(msg);
	}
	});
}else{
//alert('Please Enter Mobile Number First!!!');
}}
function check_sms_verify(){
	var sms_code = $('#sms_code').val();
	var user_mobile = $('#phone').val();
	$.ajax({
	url:"verif_mobile_number.php",
	data: "&user_mobile="+user_mobile+"&sms_code="+sms_code,
	success: function(msg){
	if(msg.trim() == 1)
	{
	goto_dept();
	}else{
		alert('You Enter Wrong Pin Code');
	}
	}
	});
}
</script>
<script type="text/javascript">
function get_Sub_depart(comp_cat)
{
$.ajax({
url:"get_sub_depart.php",
data: "m_comp_cat="+comp_cat,
success: function(msg){
//alert(msg);	
$("#show_sub_departments").html(msg);
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
function get_agency_tehsils(tehsil_var_id)
{
$.ajax({
url:"get_agency_tehsils.php",
data: "agency_tehsil_id="+tehsil_var_id,
success: function(msg){
//alert(msg);
$("#show_agency_tehsils").html(msg);
}
});
}


</script> 
<script language="javascript">
$( document ).ready(function() {
      get_tehsils(167);
});
</script>
<style type="text/css">
.sub_button {
    background-color: rgba(255, 255, 255, 0.4);
    border: 1px solid rgba(122, 192, 0, 0.15);
    border-radius: 6px;
    color: #4b4b4b;
    font-family: Keffeesatz,Arial;
    font-size: 14px;
    margin-bottom: -20px;
    padding: 7px;
}

 .clear-button {
    background-color: rgba(0, 0, 0, 1);
    border: 1px solid rgba(122, 192, 0, 0.15);
    border-radius: 6px;
    color: #FFFFFF;
    font-family: Keffeesatz,Arial;
    font-size: 14px;
    margin-bottom: 16px;
    padding: 7px;
}
#ticketForm{
	margin-left:5px;
	}
</style>