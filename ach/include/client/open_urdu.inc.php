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
  <form  method="post" action="open.php" enctype="multipart/form-data" id="wizard1" class="wizard comp_form">
    <?php csrf_token(); ?>
    <input type="hidden" name="a" value="open">
    <input type="hidden" id="assignId" name="assignId" value="0">
    <input type="hidden" id="deptId" name="deptId" value="0">
    <input type="hidden" name="topicId" value="43">
    <input type="hidden" name="lang" value="urdu" > 
    <div class="wizard-header" style="display:none;"><!---->
      <ul class="nav nav-tabs">
        <li role="presentation" class="wizard-step-indicator"><a id="li_profile" href="#profile">Profile</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_verification" href="#verification">Verification</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_deprts" href="#deprts">Departments</a></li>
        
        <li role="presentation" class="wizard-step-indicator"><a id="li_capital_martket" href="#capital_martket">Capital Market</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_capital_martket_type_inner" href="#capital_martket_type_inner">Capital Market Inner</a></li>
           
        <li role="presentation" class="wizard-step-indicator"><a id="li_insurance" href="#insurance">Insurance</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_insurance_type_inner" href="#insurance_type_inner">Insurance Inner</a></li>
        
        <li role="presentation" class="wizard-step-indicator"><a id="li_specialized_companies" href="#specialized_companies">Specialized Companies</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_specialized_companies_type_inner" href="#specialized_companies_type_inner">Specialized Companies Inner</a></li>
     
        <li role="presentation" class="wizard-step-indicator"><a id="li_e_services" href="#e_services">E Services</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_company_registration" href="#company_registration">Company Registration</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_company_supervision" href="#company_supervision">Company Supervision</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_finish" href="#finish">Finish</a></li>
      </ul>
    </div>
    <div class="wizard-content">
      <div id="profile" class="wizard-step">
        <?php include('../includes/right_side_bar_urdu.php');?>
        <div class="clearfix hidden-lg"></div>
        <div class="col-lg-8 padding-top-10">
          <div class="col-xs-12">
            <h3 style="color: #202020;font-weight: bold;float: right;">شکایت کنندہ کی ذاتی تفصیلات</h3>
          </div>
          <div class="form-group">
            <div class="col-lg-offset-2 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
              <input type="text" name="name" class="form-control backrnd" required> <!----> 
            </div>
            <div class="col-lg-5 col-xs-12 form-group">
              <label for="complaint" class="float-right" style="padding-top: 20px;"><span style="color: red;font-size: 13px;">*</span>شکایت کنندہ کا نام </label>
            </div>
            
            <div class="col-lg-offset-2 col-lg-5 col-xs-12 form-group">
              <input type="text" class="form-control backrnd"  name="nic" required> <!----> 
            </div>
            <div class="col-lg-5 col-xs-12 form-group">
              <label for="complaint" class="float-right"><span style="color: red;font-size: 13px;">*</span>کمپیوٹرائزڈ قومی شناختی کارڈ / NICOP / پاسپورٹ</label>
            </div>
            
            <div class="col-lg-offset-2 col-lg-5 col-xs-12 form-group">
              <input type="text" class="form-control backrnd"  name="phone" id="phone" required> <!----> 
             
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
              <select id="jumpMenu" name="district" onChange="get_tehsils(this.value);" class="form-control backrnd" required> <!----> 
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
              <textarea name="applicant_address" class="form-control" required></textarea> <!----> 
            </div>
            <div class="col-lg-5 col-xs-12 padding-top-10 form-group">
              <label for="complaint" class="float-right"><span style="color: red;font-size: 13px;">*</span>ڈاک کا پتا</label>
            </div>
          </div>
          <div class="col-xs-12"><!---->
            <button type="button" class="wizard-next btn btn-primary"  onClick="set_sms_verify();">اگلا مرحلہ</button>
          </div>
          <div class="clearfix hidden-lg"></div>
        </div>
      </div>
      <div id="verification" class="wizard-step">
        <?php include('../includes/right_side_bar_urdu_2.php');?>
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
              <span class="fa fa-info-circle errspan"></span> <img class="img-responsive" src="assets/images/sms.png" height="250px" width="60%" style="margin:0 auto;height: 165px;"> </div>
            <div class="col-xs-offset-3 col-xs-6">
              <button type="button" class="btn btn-primary float-right" onClick="check_sms_verify();"  style="width: 100%;margin: 0 auto;margin-top: 40px;padding-top: 10px;">اگلا مرحلہ</button>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
      <div id="deprts" class="wizard-step">
        <?php include('../includes/right_side_bar_urdu_3.php');?>
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
          <div class="clearfix"></div>
          
          <div style="color: rgb(32, 32, 32); font-weight: bold; padding-right: 33px; padding-top: 16px;float:right">:آپ ہمے مندرجہ ذیل ای میل پر بھی لکھ سکتے ہیں <br><br> <span style="color:#4E9EE3;float:right;padding-right: 33px;">queries@secp.gov.pk</span><br>یا<br> <span style="color:#4E9EE3;float:right;padding-right: 33px;"> complaints@secp.gov.pk</span><br><br>:یا بذریہ ڈاک مندرجہ ذیل پتے پر بھیج سکتے ہیں<br> <span style="color:#4E9EE3;float:right;padding-right: 33px;">سیکیوریٹیز  اینڈ  ایکسچینج  کمیشن  اف  پاکستان <br>این ای سی   بلڈنگ , 63 جناح ایونیو , بلیو ایریا , اسلام آباد<br></span>
</div>
        </div>
        
        
      </div>
      
      <div id="capital_martket" class="wizard-step">
        <?php include('../includes/right_side_bar_urdu_3.php');?>
        <div class="clearfix hidden-lg"></div>
        <div class="col-lg-8 padding-top-10 text-center">
          <div class="col-xs-12">
            <h3 style="color: #202020;font-weight: bold;float: right;">سرمایہ مارکیٹ</h3>
          </div>
          <div class="form-group" id="capital_martket_inner">
            <?php 
            $sql_cm_tyes = "Select * from sdms_capital_markets Where 1 GROUP BY type order by `sort_number` asc ";
            $res_cm_tyes = db_query($sql_cm_tyes);
            while( $row_cm_tyes = db_fetch_array($res_cm_tyes)){ ?>
            <div class="col-lg-3 col-xs-12" style="float:right;">
              <label>
                <input type="radio" name="m-system" value="<?php echo $row_cm_tyes['type']; ?>" 
                onClick="show_wizard_cm(this.value,'<?php echo $row_cm_tyes['type_urdu']; ?>');" />
                <img src="assets/images/<?php echo $row_cm_tyes['icon']; ?>"> </label>
              <p><?php echo $row_cm_tyes['type_urdu']; ?></p>
            </div>
            <?php }?>
            <div class="col-xs-12">
              <button type="button" class="btn btn-primary float-left" onClick="goto_dept();" style="margin-right: 8px;">پچھلا مرحلہ</button>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
      <div id="capital_martket_type_inner" class="wizard-step">
        <?php include('../includes/right_side_bar_urdu_3.php');?>
        <div class="clearfix hidden-lg"></div>
        <div class="col-lg-8 padding-top-10 text-center">
          <div class="col-xs-12">
            <h3 style="color: #202020;font-weight: bold;float: right;" >سرمایہ مارکیٹ -> <span id="cm_specific_type"></span></h3>
          </div>
          <div class="form-group" id="capital_martket_inner">
          <div id="brokers_list"></div>
  <div id="brokers_agent"></div>
  <div class="col-lg-12 col-xs-12 form-group">
    <!--<label for="complaint" class="float-right" >براہ مہربانی کسی ایک کا انتخاب کیجئے(فولیو نمبر) یا ( سی ڈی سی اکاؤنٹ نمبر)</label>-->
     <label for="complaint" class="float-right" >(بر اہ مہربانی کسی ایک کا انتخاب کیجئے (فولیو نمبر) یا (سی ڈی سی اکاؤنٹ نمبر</label>
    
  </div>
  <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
    <input type="text" name="cm_folio_no" class="form-control backrnd" >
  </div>
  <div class="col-lg-4 col-xs-12 form-group" style="padding-top: 20px;">
    <label for="complaint" class="float-right" > فولیو نمبر</label>
  </div>
  <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
    <input type="text" class="form-control backrnd" name="cm_cdc_ac_no" >
  </div>
  <div class="col-lg-4 col-xs-12 form-group" style="padding-top: 20px;">
    <label for="complaint" class="float-right" > سی ڈی سی اکاؤنٹ نمبر</label>
  </div>
  <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
    <input type="text" class="form-control backrnd" name="cm_no_of_shares">
  </div>
  <div class="col-lg-4 col-xs-12 form-group"  style="padding-top: 20px;">
    <label for="complaint" class="float-right" >حصص کی تعداد </label>
  </div>
  <div class="col-xs-12">
    <button type="button" class="btn btn-primary float-left" onClick="goto_last();">اگلا مرحلہ</button>
     <button type="button" class="btn btn-primary float-left" onClick="goto_capital_martket();" style="margin-left: 8px;">پچھلا مرحلہ</button>
  </div> 
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
      
      <div id="insurance" class="wizard-step">
        <?php include('../includes/right_side_bar_urdu_3.php');?>
        <div class="clearfix hidden-lg"></div>
        <div class="col-lg-8 padding-top-10">
          <div class="col-xs-12">
            <h3 style="color: #202020;font-weight: bold;float: right;">بیمہ</h3>
          </div>
          <div class="form-group" id="insurence_inner">
            <?php 
            $sql_insurance_tyes = "Select * from sdms_insurance Where 1 GROUP BY type order by `sort_number` asc ";
            $res_insurance_tyes = db_query($sql_insurance_tyes);
            while( $row_insurance_tyes = db_fetch_array($res_insurance_tyes)){
            ?>
            <div class="col-lg-3 col-xs-12" style="float:right;">
              <label>
                <input type="radio" name="m-system" value="<?php echo $row_insurance_tyes['type']; ?>" onClick="show_wizard_insurence(this.value,'<?php echo $row_insurance_tyes['type_urdu']; ?>');" />
                <img src="assets/images/<?php echo $row_insurance_tyes['icon']; ?>"> </label>
              <p><?php echo $row_insurance_tyes['type_urdu']; ?></p>
            </div>
            <?php }?>
            <div class="col-xs-12">
              <button type="button" class="btn btn-primary float-left" onClick="goto_dept();" style="margin-right: 8px;">پچھلا مرحلہ</button>
            </div>
          </div>
        </div>
      </div>
      <div id="insurance_type_inner" class="wizard-step">
        <?php include('../includes/right_side_bar_urdu_3.php');?>
        <div class="clearfix hidden-lg"></div>
        <div class="col-lg-8 padding-top-10">
          <div class="col-xs-12">
            <h3 style="color: #202020;font-weight: bold;float: right;">بیمہ -> <span id="insurance_specific_type"></span></h3>
          </div>
          <div class="form-group" id="insurence_inner">
           <div id="insurance_list"></div>
  <div id="insurance_agent"></div>
  <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
    <input type="text" name="i_policy_no" class="form-control backrnd" >
  </div>
  <div class="col-lg-4 col-xs-12 form-group">
    <label for="complaint" class="float-right" ><span style="color: red;font-size: 13px;">*</span>پالیسی نمبر </label>
  </div>
  <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
    <input type="text" name="i_sum_assured" class="form-control backrnd" >
  </div>
  <div class="col-lg-4 col-xs-12 form-group">
    <label for="complaint" class="float-right" ><span style="color: red;font-size: 13px;">*</span>بیمہ شدہ رقم </label>
  </div>
  <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" >
    <input type="text" name="i_claim_amount" class="form-control backrnd" >
  </div>
  <div class="col-lg-4 col-xs-12 form-group">
    <label for="complaint" class="float-right" >زیر دعوی رقم </label>
  </div>
  <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
    <input type="text" name="i_folio_no" class="form-control backrnd" >
  </div>
  <div class="col-lg-4 col-xs-12 form-group">
    <label for="complaint" class="float-right" >فولیو نمبر </label>
  </div>
  <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
    <input type="text" name="i_no_of_shares" class="form-control backrnd" >
  </div>
  <div class="col-lg-4 col-xs-12 form-group">
    <label for="complaint" class="float-right" >حصص کی تعداد </label>
  </div>
  <div class="col-xs-12">

    <button type="button" class="btn btn-primary float-left" onClick="goto_last();">اگلا مرحلہ</button>
      <button type="button" class="btn btn-primary float-left" onClick="goto_insurence();" style="margin-left: 8px;">پچھلا مرحلہ</button>
  </div>
          </div>
        </div>
      </div>
      
      <div id="specialized_companies" class="wizard-step">
        <?php include('../includes/right_side_bar_urdu_3.php');?>
        <div class="clearfix hidden-lg"></div>
        <div class="col-lg-8 padding-top-10 text-center">
          <div class="col-xs-12">
            <h3 style="color: #202020;font-weight: bold;float: right;">اے ایم سی/میوچل فنڈز/مضاربہ /لیزنگ/سرمایہ کاری بینکنگ/ریٹ</h3>
          </div>
          <div class="form-group" id="specialized_companies_inner">
            <?php 
			$sql_scd_tyes = "Select * from sdms_scd Where 1 GROUP BY type order by `sort_number` asc ";
			$res_scd_tyes = db_query($sql_scd_tyes);
			while( $row_scd_tyes = db_fetch_array($res_scd_tyes)){
            ?>
            <div class="col-lg-3 col-xs-12" style="float:right;">
              <label>
                <input type="radio" name="m-system" value="<?php echo $row_scd_tyes['type']; ?>" onClick="show_wizard_scd(this.value,'<?php echo $row_scd_tyes['type_urdu']; ?>');" />
                <img src="assets/images/<?php echo $row_scd_tyes['icon']; ?>"> </label>
              <p><?php echo $row_scd_tyes['type_urdu']; ?></p>
            </div>
            <?php }?>
            <div class="col-xs-12">
              <button type="button" class="btn btn-primary float-left" onClick="goto_dept();" style="margin-right: 8px;">پچھلا مرحلہ</button>
            </div>
          </div>
        </div>
      </div>
      <div id="specialized_companies_type_inner" class="wizard-step">
        <?php include('../includes/right_side_bar_urdu_3.php');?>
        <div class="clearfix hidden-lg"></div>
        <div class="col-lg-8 padding-top-10 text-center">
          <div class="col-xs-12">
            <h3 style="color: #202020;font-weight: bold;float: right;">اے ایم سی/میوچل فنڈز/مضاربہ /لیزنگ/سرمایہ کاری بینکنگ/ریٹ <span id="sc_specific_type"></span></h3>
          </div>
          <div class="form-group" id="specialized_companies_inner">
           <div id="scd_list"></div>
  <div id="reit_scheme"></div>
  <div id="modaraba_fund"></div>
  <div id="mutual_fund"></div>
  <div id="pension_fund"></div>
  <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
    <input type="text" name="scd_folio_no" class="form-control backrnd" >
  </div>
  <div class="col-lg-4 col-xs-12 form-group">
    <label for="complaint" class="float-right" >رجسٹریشن نمبر/فولیو نمبر </label>
  </div>
  <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
    <input type="text" name="scd_cdc_ac_no" class="form-control backrnd" >
  </div>
  <div class="col-lg-4 col-xs-12 form-group">
    <label for="complaint" class="float-right" >سی ڈی سی اکاؤنٹ نمبر </label>
  </div>
  <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
    <input type="text" name="scd_no_of_units" class="form-control backrnd" >
  </div>
  <div class="col-lg-4 col-xs-12 form-group">
    <label for="complaint" class="float-right" >یونٹس کی تعداد  </label>
  </div>
  <div class="col-xs-12">
    <button type="button" class="btn btn-primary float-left" onClick="goto_last();">اگلا مرحلہ</button>
        <button type="button" class="btn btn-primary  float-left" onClick="goto_specialized_companies();" style="margin-left: 8px;">پچھلا مرحلہ</button>
  </div>
          </div>
        </div>
      </div>
      
      
      <div id="e_services" class="wizard-step">
        <?php include('../includes/right_side_bar_urdu_3.php');?>
        <div class="clearfix hidden-lg"></div>
        <div class="col-lg-8 padding-top-10 text-center">
          <div class="col-xs-12">
            <h3 style="color: #202020;font-weight: bold;float: right;">ای سروسز / تکنیکی مسائل</h3>
          </div>
          <div class="form-group" id="e_services_inner">
            <div style="clear:both"></div>
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
              <input type="text" name="e_company_title" id="input_product_e" autocomplete="off" value="" onSelect="update_e_cro(this.value);"  class="form-control backrnd"/>
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
              <label for="complaint" class="float-right" style="padding-top: 20px;">کمپنی کا نام</label>
            </div>
            <div style="clear:both"></div>
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" id="e_cro_title">
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
              <label for="complaint" class="float-right" ><span style="color: red;font-size: 13px;">*</span>کمپنی رجسٹریشن دفتر </label>
            </div>
            <div style="clear:both"></div>
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
              <select name="e_company_title"  class="form-control backrnd">
                <option value="">&mdash; پراسیس کا نام منتخب کریں &mdash;</option>
                <?php 
$sql_process_names = "Select * from sdms_process_names where type = 'eservices'";
$res_process_names = db_query($sql_process_names);
while($row_process_names = db_fetch_array($res_process_names)){?>
                <option value="<?php echo $row_process_names['title'];?>" ><?php echo $row_process_names['title']; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
              <label for="complaint" class="float-right" style="padding-top: 20px;"><span style="color: red;font-size: 13px;">*</span>پراسیس کا نام</label>
            </div>
            
            <div style="clear:both"></div>
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
              <select name="e_nature_of_omplaint"  class="form-control backrnd">
                <option value="">&mdash; Select  &mdash;</option>
                <option value="Attachment">Attachment</option>
                <option value="Challan related ">Challan related </option>
                <option value="Dropdown Menu">Dropdown Menu</option>
                <option value="Manage Company Users ">Manage Company Users </option>
                <option value="Online payment">Online payment</option>
                <option value="Signing Issues">Signing Issues</option>
                <option value="Unable to continue">Unable to continue</option>
                <option value="Inactive/Invalid">Inactive/Invalid </option>
                <option value="Unable to generate">Unable to generate</option>
                <option value="Forgot password">Forgot password</option>
                <option value="PIN not received">PIN not received</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
              <label for="complaint" class="float-right" style="padding-top: 20px;">شکایت کی نوعیت</label>
            </div>
            <div style="clear:both"></div>
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
              <input type="text" name="e_user_id" class="form-control backrnd" >
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
              <label for="complaint" class="float-right" >یوزر آئی ڈی </label>
            </div>
            <div class="col-xs-12">
              <button type="button" class="btn btn-primary float-left" onClick="goto_last();">اگلا مرحلہ</button>
              <button type="button" class="btn btn-primary float-left" onClick="goto_dept();" style="margin-left: 8px;">پچھلا مرحلہ</button>
            </div>
          </div>
        </div>
      </div>
      <div id="company_registration" class="wizard-step">
        <?php include('../includes/right_side_bar_urdu_3.php');?>
        <div class="clearfix hidden-lg"></div>
        <div class="col-lg-8 padding-top-10 text-center">
          <div class="col-xs-12">
            <h3 style="color: #202020;font-weight: bold;float: right;">کمپنی رجسٹریشن/کمپلائنس</h3>
          </div>
          <div class="form-group" id="company_registration_inner">
            <div style="clear:both"></div>
            <div class="col-lg-offset-1 col-lg-8 col-xs-12 form-group" style="padding-top: 20px;"> رکن
              <input type="radio" size="50" name="cr_status" id="cr_status" value="Member" />
              &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
              <input type="radio" size="50" name="cr_status" id="cr_status" value="Shareholder" />
              حصص دار 
              &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
              <input type="radio" size="50" name="cr_status" id="cr_status" value="Non Shareholder" />
              غیر حصص دار
              &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </div>
            <div class="col-lg-3 col-xs-12 form-group">
              <label for="complaint" class="float-right" style="padding-top: 20px;">اپنی حیثیت کا انتخاب کریں</label>
            </div>
            <div style="clear:both"></div>
            <div id="r_company_cro">  
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
              <input type="text" name="cr_company_title" id="input_product_r" autocomplete="off" value=""  class="form-control backrnd"/>
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
              <label for="complaint" class="float-right" style="padding-top: 20px;"><span style="color: red;font-size: 13px;">*</span>رجسٹرشدہ کمپنی کانام </label>
            </div>
            <div style="clear:both"></div>
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" id="r_cro_title">
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
              <label for="complaint" class="float-right" >سی آر او </label>
            </div>
            </div>
            <div style="clear:both"></div>
            <div id="unregistered_entity">
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;"  onChange="hide_cro_company();">
                <select name="unregistered_entity" class="form-control backrnd">
            <option value="">--Select Unregistered Entity--</option>
            <option value="Under Incorporation">Under Incorporation</option>
            <option value="Name Reservation">Name Reservation</option>
            <option value="Other">Other</option>   
            </select>
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
              <label for="complaint" class="float-right" style="padding-top: 20px;"><span style="color: red;font-size: 13px;">*</span>غیر رجسٹرڈ شدہ ادارہ </label>
            </div>
            </div>
            <div style="clear:both"></div>
              <div id="cr_folio_no_cr_no_of_shares">
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
              <input type="text" name="cr_folio_no" class="form-control backrnd" >
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
              <label for="complaint" class="float-right" ><span style="color: red;font-size: 13px;">*</span>فولیو نمبر </label>
            </div>
            
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
              <input type="text" name="cr_no_of_shares" class="form-control backrnd" >
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
              <label for="complaint" class="float-right" >حصص کی تعداد </label>
            </div>
            </div>
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
              <select name="cr_process_name"  class="form-control backrnd">
                <option value="">--پراسیس کا انتخاب کریں--</option>
                <?php 
             $sql_process_names = "Select * from sdms_process_names where type = 'companyregistration'";
            $res_process_names = db_query($sql_process_names);
            while($row_process_names = db_fetch_array($res_process_names)){
            ?>
                <option value="<?php echo $row_process_names['title']; ?>"><?php echo $row_process_names['title']; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
              <label for="complaint" class="float-right" style="padding-top: 20px;"><span style="color: red;font-size: 13px;">*</span>پراسیس کا نام</label>
            </div>
            
            <div style="clear:both"></div>
            <div class="col-xs-12">
              <button type="button" class="btn btn-primary float-left" onClick="goto_last();">اگلا مرحلہ</button>
              <button type="button" class="btn btn-primary float-left" onClick="goto_dept();" style="margin-left: 8px;">پچھلا مرحلہ</button>
            </div>
          </div>
        </div>
      </div>
      <div id="company_supervision" class="wizard-step">
        <?php include('../includes/right_side_bar_urdu_4.php');?>
        <div class="clearfix hidden-lg"></div>
        <div class="col-lg-8 padding-top-10 text-center">
          <div class="col-xs-12">
            <h3 style="color: #202020;font-weight: bold;float: right;">لسٹڈ کمپنیوں کی نگرانی</h3>
          </div>
          <div class="form-group" id="company_supervision_inner">
            <div style="clear:both"></div>
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
             <input type="text" name="cs_company_title" id="input_product_s" autocomplete="off" value=""  class="form-control backrnd"/>
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
              <label for="complaint" class="float-right" style="padding-top: 20px;"><span style="color: red;font-size: 13px;">*</span>کمپنی کا نام</label>
            </div>
            <div style="clear:both"></div>
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
              <input type="text" name="cs_cdc_ac_no" class="form-control backrnd" >
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
              <label for="complaint" class="float-right" ><span style="color: red;font-size: 13px;">*</span>سی ڈی سی اکاؤنٹ نمبر </label>
            </div>
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
              <input type="text" name="cs_folio_no" class="form-control backrnd" >
            </div>
            <div class="col-lg-4 col-xs-12 form-group">
              <label for="complaint" class="float-right" >فولیو نمبر </label>
            </div>
            <div class="col-xs-12">
              <button type="button" class="btn btn-primary float-left" onClick="goto_last();">اگلا مرحلہ</button>
              <button type="button" class="btn btn-primary float-left" onClick="goto_dept();" style="margin-left: 8px;">پچھلا مرحلہ</button>
            </div>
          </div>
        </div>
      </div>
      
      <div id="finish" class="wizard-step">
        <?php include('../includes/right_side_bar_urdu_4.php');?>
        <div class="clearfix hidden-lg"></div>
        <div class="col-lg-8 padding-top-10" style="color: black;">
          <div class="col-xs-12">
            <h2 style="float: right;color: black;"> شکایات کی تفصیلات </h2>
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
            <div class="col-lg-12 col-xs-12 padding-top-10 form-group">
              <label for="complaint" class="float-right">(اٹیچمنٹ : (پی ڈی ایف یا جے پی جی فارمیٹ میں زیا دہ سے زیادہ  دو ایم بی تک</label>
            </div>
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group has-feedback">
                 <div class="text" id="mulitplefileuploader">فائل کا انتخاب کریں</div>
              <div id="error" ></div>
              <div id="status" ></div>
              <div id="append_fields">
                <input class="file_multi" type="hidden" name="evidence_1" id="evidence_1" value="" />
              </div>
            </div>
            
            <div class="col-lg-12 col-xs-12 padding-top-10 form-group">
              <label for="complaint" class="float-right">(اٹیچمنٹ : (پی ڈی ایف یا جے پی جی فارمیٹ میں زیا دہ سے زیادہ  دو ایم بی تک</label>
            </div>
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group has-feedback">
                 <div class="text" id="mulitplefileuploader1">فائل کا انتخاب کریں</div>
              <div id="error1" ></div>
              <div id="status1" ></div>
              <div id="append_fields1">
                <input class="file_multi1" type="hidden" name="evidence_2" id="evidence_2" value="" />
              </div>
            </div>
            
            <div class="col-lg-12 col-xs-12 padding-top-10 form-group">
              <label for="complaint" class="float-right">(اٹیچمنٹ : (پی ڈی ایف یا جے پی جی فارمیٹ میں زیا دہ سے زیادہ  دو ایم بی تک</label>
            </div>
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group has-feedback">
                 <div class="text" id="mulitplefileuploader2">فائل کا انتخاب کریں</div>
              <div id="error2" ></div>
              <div id="status2" ></div>
              <div id="append_fields2">
                <input class="file_multi2" type="hidden" name="evidence_3" id="evidence_3" value="" />
              </div>
            </div>
            
            <div class="col-lg-12 col-xs-12 padding-top-10 form-group">
              <label for="complaint" class="float-right">(اٹیچمنٹ : (پی ڈی ایف یا جے پی جی فارمیٹ میں زیا دہ سے زیادہ  دو ایم بی تک</label>
            </div>
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group has-feedback">
                 <div class="text" id="mulitplefileuploader3">فائل کا انتخاب کریں</div>
              <div id="error3" ></div>
              <div id="status3" ></div>
              <div id="append_fields3">
                <input class="file_multi3" type="hidden" name="evidence_4" id="evidence_4" value="" />
              </div>
            </div>
            
            <div class="col-lg-12 col-xs-12 padding-top-10 form-group">
              <label for="complaint" class="float-right">(اٹیچمنٹ : (پی ڈی ایف یا جے پی جی فارمیٹ میں زیا دہ سے زیادہ  دو ایم بی تک</label>
            </div>
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group has-feedback">
                 <div class="text" id="mulitplefileuploader4">فائل کا انتخاب کریں</div>
              <div id="error4" ></div>
              <div id="status4" ></div>
              <div id="append_fields4">
                <input class="file_multi4" type="hidden" name="evidence_5" id="evidence_5" value="" />
              </div>
            </div>
            
            <div class="col-lg-12 col-xs-12 padding-top-10 form-group">
              <label for="complaint" class="float-right">(اٹیچمنٹ : (پی ڈی ایف یا جے پی جی فارمیٹ میں زیا دہ سے زیادہ  دو ایم بی تک</label>
            </div>
            <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group has-feedback">
                 <div class="text" id="mulitplefileuploader5">فائل کا انتخاب کریں</div>
              <div id="error5" ></div>
              <div id="status5" ></div>
              <div id="append_fields5">
                <input class="file_multi5" type="hidden" name="evidence_6" id="evidence_6" value="" />
              </div>
            </div>
            
          </div>
          <div class="col-xs-12">
            <button type="submit" class="btn btn-primary float-left float-right" onclick="return confirm('میں بذریعہ ہذٰا حلفیہ اعلان اور اقرار کرتا ہوں کہ بیان ہذٰا بہمراہ  شکایت، دستاویزات اور رپورٹس  میرے علم و یقین کے مطابق حقیقی، درست اور مستند ہیں اور ان میں کوئی بھی امر پوشیدہ نہ رکھا گیا ہے۔')">ختم</button>
             <img src="assets/images/ajax-loader.gif" class="float-right" style="width:40px;height:40px;display:none;" id="loding_form">
          </div>
          <div class="clearfix hidden-lg"></div>
        </div>
      </div>
    </div>
  </form>
  <div class="padding-top-10">&nbsp;</div>
</div>
<!-- Modal -->
<?php /*?><div class="modal fade" id="myModal2" role="dialog">
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
</div><?php */?>
<div class="modal fade" id="myModal2" role="dialog">
  <div class="modal-dialog"> 
    
    <!-- Modal content-->
    <div class="modal-content" style="border: 14px solid #929292">
       <div class="modal-header" style="padding: 7px;">
        <button type="button" class="close cus_style" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-header" style="padding: 7px;background-color: #aec834;">
        <h3 class="modal-title text-center" style="color: #ffffff;">کیپٹل مارکیٹیں</h3>
      </div>
      
      <div class="modal-body" style="color: #4a4a4a;font-size: 12px;">
        <h5 style="color: red">براہ مہربانی اگرآپ درج ذیل میں سے کسی  کے بارے میں  شکایت درج کرنا چاہتے ہیں تو اس آپشن کا انتخاب کریں </h5>
        <p style="line-height: 18px;text-align: right;"> 

         <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;text-align: right;"></i></span> کیپٹل مارکیٹیں ،اس کے متوسلین(انٹرمیڈئیریز) ( جیسا کہ سی ڈی سی،این سی سی پی ایل،پی ایس ایکس،ای ایم ای ایکس وغیرہ )<br>

        <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span> لسٹڈ کمپنیوں کے حصص  کی تجارت<br>

        <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span> پی ایس ایکس بروکروں/ایجنٹوں،پی این ای ایکس بروکر ،بک رنر،شئیر رجسٹرار وغیرہ <br>

        <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span> ٹیک اوورز کی صورت میں حاصل کنندہ /کمپنی <br>

        <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span> سود مند ملکیت کی فائلنگ<br>

        <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;text-align: right;"></i></span> گھر کے بھیدی کی تجارت،مارکیٹ میں افواہوں /مارکیٹ میں  ساز باز /مارکیٹ کا ناجائز استعمال وغیرہ<br>

        <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span> بلا اجازت تجارت،اکاؤنٹ ٹریڈنگ سٹیٹمنٹ وغیرہ کا موصول نہ ہونا <br>
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
        <h3 class="modal-title text-center" style="color: #ffffff;">بیمہ</h3>
      </div>
      <div class="modal-body" style="color: #4a4a4a;font-size: 12px;">
      <h5 style="color: red;">براہ مہربانی اگر آپ درج ذیل میں سے کسی  کے بارے میں  شکایت درج کرنا چاہتے ہیں تو اس آپشن کا انتخاب کریں</h5>
        <p style="line-height: 18px;text-align: right;">

            <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span> بیمہ کمپنیاں<br>

            <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span> بیمہ بروکر یا ایجنٹ<br>

           <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span> مساحت کار(سروئیر) یا<br>

            <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span> •  بیمہ کی صنعت سے متعلقہ کوئی بھی دیگر معاملہ<br>
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
        <h4 class="modal-title text-center" style="color: #ffffff;">اے ایم سی/میوچل فنڈز/مضاربہ /لیزنگ/سرمایہ کاری بینکنگ/ریٹ</h4>
      </div>
      <div class="modal-body" style="color: #4a4a4a;font-size: 12px;">
      <h5 style="color: red;"> براہ مہربانی اگر آپ درج ذیل میں سے کسی  کے بارے میں  شکایت درج کرنا چاہتے ہیں تو اس آپشن کا انتخاب کریں  </h5>
        <p style="line-height: 18px;text-align: right;"> 

            <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span> ایسٹ مینجمنٹ کمپنیاں<br>

            <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span> میوچل فنڈز<br>

            <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span> پنشن فنڈز<br>

            <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span> مشیران سرمایہ کاری<br>

            <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span>  رئیل سٹیٹ انویسمنٹ ٹرسٹ(ریٹس)<br>

            <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span>  لیزنگ کمپنیاں<br>

            <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span>  سرمایہ کاری بینک<br>

           <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span>   مائیکرو فنانس کمپنیاں <br>

            <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span>  ہاؤسنگ فنانس کمپنیاں <br>

            <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span>  ڈسکاؤنٹ ہاؤسز<br>

           <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span>  نجی فنڈ مینجمنٹ کمپنیاں<br>

            <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span>  نجی ایکویٹی فنڈ<br>

            <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span>  وینچر کیپٹل فنڈ<br>

            <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span>  متبادل فنڈ<br>

            <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span>  مضاربہ مینجمنٹ کمپنیاں یا مضاربے<br>
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
        <h3 class="modal-title text-center" style="color: #ffffff;"> ای سروسز </h3>
      </div>
      <div class="modal-body" style="color: #4a4a4a;font-size: 12px;">
        <h5 style="color: red;text-align: right;">براہ مہربانی  درج ذیل کا انتخاب کریں  اگر</h5>
        <p style="line-height: 18px;text-align: right;">
        <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span> 
 آپ پہلی دفعہ  ہماری ای خدمات  استعمال کر رہے ہیں یا پہلے سے ای خدمات استعمال کرتے ہیں اور ای سروسز کے حوالے سے کسی تکنیکی نوعیت جیسا کہ لاگ ان کے مسائل ،فارم جمع کروانے/پراسیس کروانے میں دشواریوں وغیرہ کے بارے میں شکایت درج کروانا چاہتے ہیں
        </p>
      </div>
    </div>
  </div>
</div>
<!-- modal 6 -->
<div class="modal fade" id="myModal6" role="dialog">
  <div class="modal-dialog"> 
    <!-- Modal content-->
    <div class="modal-content" style="border: 14px solid #929292">
      <div class="modal-header" style="padding: 7px;">
        <button type="button" class="close cus_style" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-header" style="background-color: #aec834;padding: 7px;">
        <h2 class="modal-title text-center" style="color: #ffffff;"> کمپنی رجسٹریشن /نفاذ </h2>
      </div>
      <div class="modal-body" style="color: #4a4a4a;font-size: 12px;">
     
      <h5 style="color: red;text-align: right;">براہ مہربانی اگر آپ درج ذیل میں سے کسی  کے بارے میں  شکایت درج کرنا چاہتے ہیں تو اس آپشن کا انتخاب کریں </h5>
        <p style="line-height: 18px;text-align: right;">
          <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i> • پرائیویٹ /پبلک ان لسٹڈ کمپنیاں بشمول پبلک سیکٹر کمپنیاں<br></span>

          <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i> • غیر منافع بخش کمپنیاں ،براہ مہربانی اس ڈومیئن کا انتخاب کریں </span>
           <p style="text-align: center !important;">  یا </p> 
          </p>
          <h5 style="color: red;">متعلقہ رجسٹرار کے ساتھ درج ذیل کے بارے میں شکایت </h5>
          <p>
          <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i> • معلامات کی رجسٹریشن (جیسا کہ گوشوارے جمع کروانا ،منظوریاں وغیرہ حاصل کرنے کے لئے درخواستیں جمع کروانا)<br></span>

          <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i>  •  متعلقہ رجسٹرار سے معلومات کا حصول( جیسا کہ دستاویزات کی مصدقہ نقول،یادداشت تاسیس/تاسیس شرکت،مالیاتی گوشوارےاور کمپنیوں کے ریکارڈ کا معائنہ وغیرہ)<br></span>

          <span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i>  کمپنیز آرڈیننس کے احکامات کی   عدم تعمیل کی صورت میں اس ڈومئین کا انتخاب کریں<br></span>
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
        <h3 class="modal-title text-center" style="color: #ffffff;">لسٹڈ کمپنیوں کی نگرانی</h3>
      </div>
      <div class="modal-body" style="color: #4a4a4a;font-size: 12px;">
      <h5 style="color: red; text-align: right;">براہ مہربانی اگر آپ درج ذیل میں سے کسی  کے بارے میں  شکایت درج کرنا چاہتے ہیں تو اس آپشن کا انتخاب کریں </h5>
        <p style="line-height: 18px;text-align: right;"> 

         حصص کا انتقال<span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span><br>
         حصہ منقسمہ  کا اجرا اور ادائیگی<span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;"></i></span><br>
         سالانہ  اور  سہ ماہی گوشواروں یا  حصص داروں کے اجلاس کے نوٹس کی موصولی<span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span><br>
         ویب سائٹ پر کمپنی کی  مکمل معلومات کی دستیابی<span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span><br>
        کمپنی میں مینجمنٹ کی جانب سے یا اس کے ملازمین کی جانب سےدھوکہ دہی /اثاثوں کی منتقلی<span> <i class="fa fa-arrow-left" aria-hidden="true" style="color: #03BEA3;float: right;"></i></span> <br>

 
         </p>
      </div>
    </div>
  </div>
</div>
<!-- modal section end --> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.14.0/jquery.validate.min.js"></script> 
<script src="assets/js/jquery.simplewizard.js"></script> 
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
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

$( "#input_product_e" ).autocomplete({
source: "search_company_name.php",
minLength: 1
});

$( "#input_product_e" ).autocomplete({
source: "search_company_name.php",
minLength: 1,
 close: function( event, ui ) {  update_e_cro(this.value); }
});

$( "#input_product_r" ).autocomplete({
source: "search_company_name.php",
minLength: 1,
 close: function( event, ui ) {  
 update_r_cro(this.value);
   $('#unregistered_entity').hide();
  }
});

$( "#input_product_s" ).autocomplete({
source: "search_company_name.php",
minLength: 1
});

});
function hide_cro_company(){
  $('#cr_folio_no_cr_no_of_shares').hide();
  $('#r_company_cro').hide();
}

function update_e_cro(company_title){
	$.ajax({
	url:"get_e_cro.php",
	data: "company_title="+company_title,
	success: function(msg){
	$("#e_cro_title").html(msg);
	}
	});
	

}

function update_r_cro(company_title){
	$.ajax({
	url:"get_r_cro.php",
	data: "company_title="+company_title,
	success: function(msg){
	$("#r_cro_title").html(msg);
	}
	});
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
function goto_types(dept_id){
get_focal_person(dept_id);
$("#deptId").val(dept_id);
if(dept_id == 2){
$('#li_capital_martket').click();
}else if(dept_id == 3){
$('#li_insurance').click();
}else if(dept_id == 4){
$('#li_specialized_companies').click();
}else if(dept_id == 5){
$('#li_e_services').click();
}else if(dept_id == 6){
$('#li_company_registration').click();
}else if(dept_id == 18){
$('#li_company_supervision').click();
}
}
function goto_last(){
$('#li_finish').click();}
function goto_dept(){
$('#li_deprts').click();}

function show_wizard_cm(b_type,b_type_urdu){
$.post("get_cm_brokers.php",
{
b_type: b_type,
b_type_urdu: b_type_urdu,
lang:"urdu"
},
function(data, status){
$("#brokers_list").html(data);
$('#popup_action_s_m').click();
});
$("#cm_specific_type").html(b_type_urdu);
$('#li_capital_martket_type_inner').click();
}	
function show_cm_agent_list(parent){
	$.ajax({
	url:"get_cm_brokers_agents.php",
	data: "parent="+parent+"&lang=urdu",
	success: function(msg){
	$("#brokers_agent").html(msg);
	
}
});	}
function goto_capital_martket(){ $('#li_capital_martket').click(); }

function show_wizard_insurence(b_type,b_type_urdu){	
$.post("get_insurance_brokers.php",
{
b_type: b_type,
b_type_urdu: b_type_urdu,
lang:"urdu"
},
function(data, status){
$("#insurance_list").html(data);
});

$("#insurance_specific_type").html(b_type_urdu);
$('#li_insurance_type_inner').click();}


function show_insurance_agent_list(parent){
	$.ajax({
	url:"get_insurance_brokers_agents.php",
	data: "parent="+parent+"&lang=urdu",
	success: function(msg){
	$("#insurance_agent").html(msg);
}
});	}
function goto_insurence(){ $('#li_insurance').click(); }

function show_wizard_scd(b_type,b_type_urdu){
	$("#scd_list").html('');
	$("#reit_scheme").html('');
	$("#modaraba_fund").html('');
	$("#mutual_fund").html('');
	$("#pension_fund").html('');
	
	$.post("get_scd_brokers.php",
{
b_type: b_type,
b_type_urdu: b_type_urdu,
lang:"urdu"
},
function(data, status){
	$("#scd_list").html(data);
//$("#sc_specific_type").html(b_type_urdu);
$('#li_specialized_companies_type_inner').click();
});
 }
function show_scd_agent_list(parent){
	
	$.ajax({
	url:"get_scd_brokers_agents.php",
	data: "parent="+parent+"&action=reit_scheme"+"&lang=urdu",
	success: function(msg){
	$("#reit_scheme").html(msg);
}
});	

$.ajax({
	url:"get_scd_brokers_agents.php",
	data: "parent="+parent+"&action=modaraba_fund"+"&lang=urdu",
	success: function(msg){
	$("#modaraba_fund").html(msg);
}
});	

$.ajax({
	url:"get_scd_brokers_agents.php",
	data: "parent="+parent+"&action=mutual_fund"+"&lang=urdu",
	success: function(msg){
	$("#mutual_fund").html(msg);
}
});	

$.ajax({
	url:"get_scd_brokers_agents.php",
	data: "parent="+parent+"&action=pension_fund"+"&lang=urdu",
	success: function(msg){
	$("#pension_fund").html(msg);
}
});}
function goto_specialized_companies(){ $('#li_specialized_companies').click(); }
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
alert('Please Enter Mobile Number First!!!');
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


</script> 
<script language="javascript">
$(document).ready(function() {
      get_tehsils(167);
});
</script>
<link href="assets/css/uploadfilemulti.css" rel="stylesheet"> 
<script src="scp/js/jquery.fileuploadmulti.min.js"></script>
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
	onSuccess:function(files,data,xhr)
	{
		
		$('#error').html('');
		$("#status").html("<font color='green'>Attachment uploaded successfully.</font>");
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
		$("#status").html("<font color='red'>Attachment  is Failed</font>");
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
		$("#status1").html("<font color='green'>Attachment uploaded successfully.</font>");
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
		$("#status1").html("<font color='red'>Attachment  is Failed</font>");
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
		$("#status2").html("<font color='green'>Attachment uploaded successfully.</font>");
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
		$("#status2").html("<font color='red'>Attachment  is Failed</font>");
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
		$("#status3").html("<font color='green'>Attachment uploaded successfully.</font>");
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
		$("#status3").html("<font color='red'>Attachment  is Failed</font>");
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


		$("#status4").html("<font color='green'>Attachment uploaded successfully.</font>");
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
		$("#status4").html("<font color='red'>Attachment  is Failed</font>");
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
		$("#status5").html("<font color='green'>Attachment uploaded successfully.</font>");
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
		$("#status5").html("<font color='red'>Attachment  is Failed</font>");
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
				$('#error').html('Please فائل کا انتخاب کریں a Document');
			}*/
		});

});

</script>
