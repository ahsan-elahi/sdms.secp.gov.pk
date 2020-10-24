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
        <input type="hidden" name="lang" value="urdu" > 
    <div class="wizard-header" style="display:none;" >
      <ul class="nav nav-tabs">
        <li role="presentation" class="wizard-step-indicator"><a id="li_profile" href="#profile">Profile</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_verification" href="#verification">Verification</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_deprts" href="#deprts">Departments</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_finish" href="#finish">Finish</a></li>
      </ul>
    </div>
    <div class="wizard-content">
      <div id="profile" class="wizard-step">
        <?php include('../includes/right_side_bar_urdu_query.php');?>
        <div class="clearfix hidden-lg"></div>
        <div class="col-lg-8 padding-top-10">
          <div class="col-xs-12">
            <h3 style="color: #202020;font-weight: bold;float: right;">سوال کنندہ کی ذاتی تفصیلات</h3>
          </div>
          <div class="form-group">
            <div class="col-lg-offset-2 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
              <input type="text" name="name" class="form-control backrnd" required>
            </div>
            <div class="col-lg-5 col-xs-12 form-group">
              <label for="complaint" class="float-right" style="padding-top: 20px;"><span style="color: red;font-size: 13px;">*</span>سوال کنندہ کا نام </label>
            </div>
            
            <div class="col-lg-offset-2 col-lg-5 col-xs-12 form-group">
              <input type="text" class="form-control backrnd"  name="nic" required>
            </div>
            <div class="col-lg-5 col-xs-12 form-group">
              <label for="complaint" class="float-right"><span style="color: red;font-size: 13px;">*</span>کمپیوٹرائزڈ قومی شناختی کارڈ / NICOP / پاسپورٹ</label>
            </div>
            
            <div class="col-lg-offset-2 col-lg-5 col-xs-12 form-group">
              <input type="text" class="form-control backrnd"  name="phone" id="phone" required >
            </div>
            <div class="col-lg-5 col-xs-12 form-group">
              <label for="complaint" class="float-right"><span style="color: red;font-size: 13px;">*</span>موبائل فون کانمبر</label>
            </div>
            
            
            <div class="col-lg-offset-2 col-lg-5 col-xs-12 form-group">
              <input type="email" class="form-control backrnd" name="email">
            </div>
            <div class="col-lg-5 col-xs-12 form-group">
              <label for="complaint" class="float-right">ای میل</label>
            </div>
            <div class="col-lg-offset-2 col-lg-5 col-xs-12 form-group has-feedback">
              <select id="jumpMenu" name="district" onChange="get_tehsils(this.value);" class="form-control backrnd">
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
            <div class="col-lg-5 col-xs-12 padding-top-10 form-group">
              <label for="complaint" class="float-right"> <span style="color: red;font-size: 13px;">*</span>ملک</label>
            </div>
            <div id="show_sub_tehsils"></div>
            <div id="show_agency_tehsils"></div>
            <div class="col-lg-offset-2 col-lg-5 col-xs-12 form-group has-feedback">
              <textarea name="applicant_address" class="form-control" required></textarea>
            </div>
            <div class="col-lg-5 col-xs-12 padding-top-10 form-group">
              <label for="complaint" class="float-right" ><span style="color: red;font-size: 13px;">*</span>ڈاک کا پتا</label>
            </div>
          </div>
          <div class="col-xs-12">
            <button type="button" class="wizard-next btn btn-primary"  onClick="set_sms_verify();" >اگلا مرحلہ</button>
          </div>
          <div class="clearfix hidden-lg"></div>
        </div>
      </div>
      <div id="verification" class="wizard-step">
        <?php include('../includes/right_side_bar_urdu_query_2.php');?>
        <div class="clearfix hidden-lg"></div>
        <div class="col-lg-8 padding-top-10">
          <div class="form-group">
            <div class="col-xs-12">
              <h3 style="color: #202020; float: right;">توثیق بذریعہ ایس ایم ایس</h3>
            </div>
            <div class="col-xs-12 text-center">
              <h4 style="color: red;font-weight: bold;">اپنے موبائل پر موصول ہونے والا چار ہندسوں  کا کوڈ درج کریں </h4>
            </div>
            <div class="col-xs-12 text-center"><!---->
              <input type="text" name="sms_vrify" id="sms_code"  class="form-control" placeholder="Enter Your Code" style="width: 40%; margin: 0 auto;" required>
              <span class="fa fa-info-circle errspan"></span> <img class="img-responsive" src="assets/images/sms.png" width="60%" style="margin:0 auto;height: 165px;"> </div>
            <div class="col-xs-offset-3 col-xs-6">
              <button type="button" class="btn btn-primary float-right" onClick="check_sms_verify();"  style="width: 100%;margin: 0 auto;margin-top: 40px;padding-top: 10px;">اگلا مرحلہ</button>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
      <div id="deprts" class="wizard-step">
        <?php include('../includes/right_side_bar_urdu_query_3.php');?>
        <div class="clearfix hidden-lg"></div>
        <div class="col-lg-8 padding-top-10">
          <div class="form-group">
            <div class="col-xs-12">
              <h3 style="color: #202020;font-weight: bold;float: right;text-align: right;">متعلقہ درجہ کا انتخاب کرنے سے پہلے، براہِ مہربانی ہر درجہ کے سامنے دئے گئے سوالیہ نشان ’’؟‘‘ پر کلک کریں۔</h3>
            </div>
            <div class="col-lg-5 col-xs-9 padding-top-10">
            <label>
            <input type="radio" name="m-system" value="6"  onClick="goto_types(this.value);" />
            <img class="img-responsive" src="assets/images/mnge/company_registration.jpg"> </label>
            </div>
            <div class="col-lg-1 col-xs-2 padding-top-20"> <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal6"></i> </span> </div>
            
            <div class="col-lg-5 col-xs-9 padding-top-10">
            <label>
            <input type="radio" name="m-system" value="5"  onClick="goto_types(this.value);"  />
            <img class="img-responsive" src="assets/images/mnge/e-services.jpg"> </label>
            </div>
            <div class="col-lg-1 col-xs-2 padding-top-20"> <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal5"></i> </span> </div>
            
            <div class="col-lg-5 col-xs-9  padding-top-10">
            <label>
            <input type="radio" name="m-system" value="2" onClick="goto_types(this.value);" />
            <img class="img-responsive" src="assets/images/mnge/security.png"> </label>
            </div>
            <div class="col-lg-1 col-xs-2 padding-top-20"> 
            <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal2"></i> </span> </div>
            
            <div class="col-lg-5 col-xs-9 padding-top-10">
            <label>
            <input type="radio" name="m-system" value="18"  onClick="goto_types(this.value);"  />
            <img class="img-responsive" src="assets/images/mnge/company_supervisoin.jpg"> </label>
            </div>
            <div class="col-lg-1 col-xs-2 padding-top-20"> <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal18"></i> </span> </div>
            
            <div class="col-lg-5 col-xs-9 padding-top-10">
            <label>
            <input type="radio" name="m-system" value="4"  onClick="goto_types(this.value);" />
            <img class="img-responsive" src="assets/images/mnge/specailzed.jpg"> </label>
            </div>
            <div class="col-lg-1 col-xs-2 padding-top-20"> <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal4"></i> </span> </div>
            
            <div class="col-lg-5 col-xs-9 padding-top-10">
            <label>
            <input type="radio" name="m-system" value="3" onClick="goto_types(this.value);" />
            <img class="img-responsive" src="assets/images/mnge/insurance.jpg"> </label>
            </div>
            <div class="col-lg-1 col-xs-2 padding-top-20"> <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal3"></i> </span> </div>
            
            
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
      <div id="finish" class="wizard-step">
        <?php include('../includes/right_side_bar_urdu_query_4.php');?>
        <div class="clearfix hidden-lg"></div>
        <div class="col-lg-8 padding-top-10" style="color: black;">
          <div class="col-xs-12">
            <h2 style="float: right;color: black;"> سوال کی تفصیلات </h2>
          </div>
          <div class="form-group">
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
              <input type="text" name="subject" class="form-control backrnd" required>
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
              <label for="complaint" class="float-right"><span style="color: red;font-size: 13px;">*</span>سرخی </label>
            </div>
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group has-feedback">
              <textarea name="message" class="form-control" rows="5"></textarea>
            </div>
            <div class="col-lg-4 col-xs-12 padding-top-10 form-group">
              <label for="complaint" class="float-right">تفصیل</label>
            </div>
            
          </div>
          <div class="col-xs-12">
            <button type="submit" class="btn btn-primary float-right" onclick="return confirm('کیا آپکو یقین ہے کے آپ یہ سوال درج کروانا چاھتے ہیں؟')">ختم</button>
            <img src="assets/images/ajax-loader.gif" class="float-right" style="width:40px;height:40px;display:none;" id="loding_form">
            <button type="button" class="btn btn-primary float-right" onClick="goto_dept();" style="margin-right: 8px;">پچھلا مرحلہ</button>
          </div>
          <div class="clearfix hidden-lg"></div>
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
       <div class="modal-header" style="padding: 7px;">
        <button type="button" class="close cus_style" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-header" style="padding: 7px;background-color: #aec834;">
        <h3 class="modal-title text-center" style="color: #ffffff;">Capital Markets</h3>
      </div>
      
      <div class="modal-body" style="color: #4a4a4a;font-size: 12px;">
        <h5 style="color: red">Please select if you wish to lodge a complaint related to:</h5>
        <p style="line-height: 18px;"> 

         <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Capital Markets, its intermediaries (such as CDC, NCCPL, PSX, PMEX etc.)<br>

        <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Trading of shares of Listed Companies<br>

        <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span>  PSX Brokers/Agents, PMEX Broker, Book Runner, Share Registrar etc.<br>

        <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Acquirer/Company in case of takeovers<br>

        <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Beneficial Ownership filing<br>

        <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Insider Trading, Market Rumors/Manipulation/Abuses etc.<br>

        <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Unauthorized trading, Non Receipt of Account Trading Statements etc.<br>

      
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="myModal3" role="dialog">
  <div class="modal-dialog"> 
    
    <!-- Modal content-->
    <div class="modal-content" style="border: 14px solid #929292">
      <div class="modal-header" style="padding: 7px;">
        <button type="button" class="close cus_style" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-header" style="padding: 7px;background-color: #aec834;">
        <h3 class="modal-title text-center" style="color: #ffffff;">Insurance</h3>
      </div>
      <div class="modal-body" style="color: #4a4a4a;font-size: 12px;">
      <h5 style="color: red;">Please select if you wish to lodge a complaint related to:</h5>
        <p style="line-height: 18px;">

            <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Insurance Companies<br>

            <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Insurance Brokers or Agents<br>

           <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span>  Surveyors Or<br>

            <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> any other matter related to the Insurance industry<br>
        </p>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="myModal4" role="dialog">
  <div class="modal-dialog"> 
    <!-- Modal content-->
    <div class="modal-content" style="border: 14px solid #929292">
      <div class="modal-header" style="padding: 7px;">
        <button type="button" class="close cus_style" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-header" style="padding: 7px;background-color: #aec834;">
        <h4 class="modal-title text-center" style="color: #ffffff;">AMC/Mutual Funds/Modarabah/Leasing/Investment Banking/REIT</h4>
      </div>
      <div class="modal-body" style="color: #4a4a4a;font-size: 12px;">
      <h5 style="color: red;"> Please select if you wish to lodge a complaint related to: </h5>
        <p style="line-height: 18px;"> 

            <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Asset Management Companies<br>

            <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Mutual Funds<br>

            <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Pension Funds<br>

            <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Investment Advisors<br>

            <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Real Estate Investment Trusts (REITS)<br>

            <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Leasing Companies<br>

            <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span>  Investment Banks<br>

           <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span>  Microfinance Companies<br>

            <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span>  Housing Finance Companies<br>

            <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Discount Houses<br>

           <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Private Fund Management Companies<br>

            <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Private Equity Fund<br>

            <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Venture Capital Fund<br>

            <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Alternate Fund<br>

            <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Modarabah Management Companies OR Modarabah<br>
        </p>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="myModal5" role="dialog">
  <div class="modal-dialog"> 
    
    <!-- Modal content-->
    <div class="modal-content" style="border: 14px solid #929292">
      <div class="modal-header" style="padding: 7px;">
        <button type="button" class="close cus_style" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-header" style="background-color: #aec834;padding: 7px;">
        <h3 class="modal-title text-center" style="color: #ffffff;"> e-Services </h3>
      </div>
      <div class="modal-body" style="color: #4a4a4a;font-size: 12px;">
        <h5 style="color: red;">Please select if:</h5>
        <p style="line-height: 18px;"> 
<span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i> </span>  You are a first time user or an existing user of e-Services and wish to report a complaint regarding any e-Services issue of a technical nature such as login issues, form/process submission difficulties etc.<br>
        </p>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="myModal6" role="dialog">
  <div class="modal-dialog"> 
    <!-- Modal content-->
    <div class="modal-content" style="border: 14px solid #929292">
      <div class="modal-header" style="padding: 7px;">
        <button type="button" class="close cus_style" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-header" style="background-color: #aec834;padding: 7px;">
        <h2 class="modal-title text-center" style="color: #ffffff;"> Company Registration/Compliance </h2>
      </div>
      <div class="modal-body" style="color: #4a4a4a;font-size: 12px;">
     
      <h5 style="color: red;">Please select if you wish to lodge a complaint related to:</h5>
        <p style="line-height: 18px;">
          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i> Private/public unlisted companies including public sector companies<br></span>

          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i> Not for profit companies, please select this domain</span>
           <p style="text-align: center !important;">  OR </p> 
          </p>
          <h5 style="color: red;">A complaint with the concerned registrar regarding:</h5>
          <p>
          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i> Registration of information (such as filing of returns, submission of applications for seeking approvals etc.)<br></span>

          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i>  Provision of information by registrar concerned (such as certified copies of documents, memorandum of association/articles of association. financial statements and inspection of records of companies, etc.)<br></span>

          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i>  Non-compliance with provisions of the companies ordinance, please select this domain.<br></span>
        </p>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="myModal18" role="dialog">
  <div class="modal-dialog"> 
    
    <!-- Modal content-->
    <div class="modal-content" style="border: 14px solid #929292">
      <div class="modal-header" style="padding: 7px;">
        <button type="button" class="close cus_style" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-header" style="padding: 7px;background-color: #aec834;">
        <h3 class="modal-title text-center" style="color: #ffffff;">Supervision of Listed Companies</h3>
      </div>
      <div class="modal-body" style="color: #4a4a4a;font-size: 12px;">
      <h5 style="color: red;">Please select if you wish to lodge a complaint related to:</h5>
        <p style="line-height: 18px;"> 

        <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Transfer of shares<br>
        <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span>  Issue and payment of dividends<br>
        <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span>  Receipt of Annual and quarterly accounts or notice of shareholding meetings<br>
        <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Availability of complete company information on website<br>
        <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span>  Fraud /siphoning of assets in the Company by the management or its employees etc.<br>

 
         </p>
      </div>
    </div>
  </div>
</div>
<!-- modal section end --> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.14.0/jquery.validate.min.js"></script> 
<script src="assets/js/jquery.simplewizard.js"></script> 
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
}});
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
$('#li_finish').click();}
function goto_dept(){
$('#li_deprts').click();}
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
data: "district_id="+dist_id+"&lang=urdu",
success: function(msg){
$("#show_sub_tehsils").html(msg);
}
});
}
function get_agency_tehsils(tehsil_var_id)
{
$.ajax({
url:"get_agency_tehsils.php",
data: "agency_tehsil_id="+tehsil_var_id+"&lang=urdu",
success: function(msg){
//alert(msg);
$("#show_agency_tehsils").html(msg);
}
});
}
$(document).ready(function() {
      get_tehsils(167);
});
</script>
