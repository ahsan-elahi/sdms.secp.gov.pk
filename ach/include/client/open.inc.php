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
.domain_type_title {
	text-align: left;
	margin-bottom: 20px;
}
</style>
<div class="container" style="background-color: white;">
  <form  method="post" action="open.php" enctype="multipart/form-data" id="wizard1" class="wizard comp_form">
    <?php csrf_token(); ?>
    <input type="hidden" name="a" value="open">
    <input type="hidden" id="assignId" name="assignId" value="0">
    <input type="hidden" id="deptId" name="deptId" value="0">
    <input type="hidden" name="topicId" value="43">
    <input type="hidden" value="0" name="isquery" >
    <!--<input name="alertuser" checked="checked" type="checkbox">-->
    <div class="wizard-header"  style="display:none;" ><!---->
      <ul class="nav nav-tabs">
        <li role="presentation" class="wizard-step-indicator"><a id="li_profile" href="#profile">Profile</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_verification" href="#verification">Verification</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_deprts" href="#deprts">Departments</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_capital_martket" href="#capital_martket">Capital Market</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_cm_verification" href="#cm_verification">Verify Third Party</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_capital_martket_type_inner" href="#capital_martket_type_inner">Capital Market Inner</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_insurance" href="#insurance">Insurance</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_i_verification" href="#i_verification">Verify Third Party</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_insurance_type_inner" href="#insurance_type_inner">Insurance Inner</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_specialized_companies" href="#specialized_companies">Specialized Companies</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_scd_verification" href="#scd_verification">Verify Third Party</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_specialized_companies_type_inner" href="#specialized_companies_type_inner">Specialized Companies Inner</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_e_services" href="#e_services">E Services</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_company_registration" href="#company_registration">Company Registration</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_company_supervision" href="#company_supervision">Company Supervision</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_finish" href="#finish">Finish</a></li>
      </ul>
    </div>
    <div class="wizard-content">
      <div id="profile" class="wizard-step">
        <div class="col-lg-8 padding-top-10">
          <div class="col-xs-12">
            <h4 style="color: #202020;font-weight: bold;">Personal Details of Complainant</h4>
          </div>
          <div  style="min-height:20px;">&nbsp;</div>
          <div class="form-group">
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Full Name<span style="color: red;font-size: 13px;">*</span> </label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" name="name" class="form-control backrnd" required  >
              <!----> 
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">CNIC/NICOP/Passport <span style="color: red;font-size: 13px;">*</span></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" class="form-control backrnd" name="nic" required >
              <!----> 
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Mobile Number<span style="color: red;font-size: 13px;">*</span></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" class="form-control backrnd" name="phone" id="phone" required>
              <!----> 
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Email </label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="email" class="form-control backrnd" name="email" id="user_email" >
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Country <span style="color: red;font-size: 13px;">*</span></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group has-feedback">
              <select id="jumpMenu" name="district" onChange="get_tehsils(this.value);" class="form-control backrnd" required >
                <!---->
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
              <label for="complaint">Postal Address<span style="color: red;font-size: 13px;">*</span></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group has-feedback">
              <textarea name="applicant_address" class="form-control"  id="applicant_address" required></textarea>
              <!----> 
            </div>
          </div>
          <div class="col-xs-12">
            <button type="button" class="wizard-next btn btn-primary float-right" onClick="set_sms_verify();" >Next</button>
          </div>
          <div class="clearfix hidden-lg"></div>
          <div style="color: rgb(32, 32, 32); font-weight: bold; padding-left: 33px; padding-top: 16px;"> Disclaimer….!!!<br>
            <span style="color:#FF0000;">Please note: If you have not taken up the subject matter complaint at the 1st line regulator level(PSX,CDC, NCCPL, MUFAP etc.) or relevant forum such as company, broker, registrar etc. You are advised to do so first. SECP will not entertain any complaint which has not followed this procedure/protocol.</span> </div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar.php');?>
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
            <div class="col-xs-12 text-center" ><!---->
              <input type="text" name="sms_vrify" id="sms_code"  class="form-control" placeholder="Enter Your Code" style="width: 40%; margin: 0 auto;"  required >
              <span class="fa fa-info-circle errspan"></span> <img class="img-responsive" src="assets/images/sms.png" height="250px" width="60%" style="margin:0 auto;height: 165px;"> </div>
            <div class="col-xs-offset-3 col-xs-6">
              <button type="button" class="btn btn-primary float-right" onClick="check_sms_verify();"  style="width: 100%;margin: 0 auto;margin-top: 40px;padding-top: 10px;">Next</button>
            </div>
          </div>
          <div class="clearfix"></div>
          <div style="color: rgb(32, 32, 32); font-weight: bold; padding-left: 33px; padding-top: 16px;"> Disclaimer….!!!<br>
            <span style="color:#FF0000;">Please note: If you have not taken up the subject matter complaint at the 1st line regulator level(PSX,CDC, NCCPL, MUFAP etc.) or relevant forum such as company, broker, registrar etc. You are advised to do so first. SECP will not entertain any complaint which has not followed this procedure/protocol.</span> </div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar_2.php');?>
        </div>
      </div>
      <div id="deprts" class="wizard-step">
        <div class="col-lg-8 padding-top-10">
          <div class="form-group">
            <div class="col-xs-12">
              <h3 style="color: #202020;font-weight: bold;">To make your relevant selection, please click on the question mark available next to each option for further guidance.</h3>
              <div class="col-lg-5 col-xs-11 padding-top-10">
                <label>
                  <input type="radio" name="m-system" value="5"  onClick="goto_types(this.value);"  />
                  <img class="img-responsive" src="assets/images/e-services.jpg"> </label>
              </div>
              <div class="col-lg-1 col-xs-1 padding-top-20"> <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal5"></i> </span> </div>
              <div class="col-lg-5 col-xs-11 padding-top-10">
                <label>
                  <input type="radio" name="m-system" value="6"  onClick="goto_types(this.value);" />
                  <img class="img-responsive" src="assets/images/company_registration.jpg"> </label>
              </div>
              <div class="col-lg-1 col-xs-1 padding-top-20"> <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal6"></i> </span> </div>
              <div class="col-lg-5 col-xs-11 padding-top-10">
                <label>
                  <input type="radio" name="m-system" value="18"  onClick="goto_types(this.value);"  />
                  <img class="img-responsive" src="assets/images/company_supervisoin.jpg"> </label>
              </div>
              <div class="col-lg-1 col-xs-1  padding-top-20"> <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal18"></i> </span> </div>
              <div class="col-lg-5 col-xs-11  padding-top-10">
                <label>
                  <input type="radio" name="m-system" value="2" onClick="goto_types(this.value);" />
                  <img class="img-responsive" src="assets/images/security.png"> </label>
              </div>
              <div class="col-lg-1 col-xs-1 padding-top-20"> <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal2"></i> </span> </div>
              <div class="col-lg-5 col-xs-11 padding-top-10">
                <label>
                  <input type="radio" name="m-system" value="3" onClick="goto_types(this.value);" />
                  <img class="img-responsive" src="assets/images/insurance.jpg"> </label>
              </div>
              <div class="col-lg-1 col-xs-1 padding-top-20"> <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal3"></i> </span> </div>
              <div class="col-lg-5 col-xs-11  padding-top-10">
                <label>
                  <input type="radio" name="m-system" value="4"  onClick="goto_types(this.value);" />
                  <img class="img-responsive" src="assets/images/specailzed.jpg"> </label>
              </div>
              <div class="col-lg-1 col-xs-1  padding-top-20"> <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal4"></i> </span> </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div style="color: rgb(32, 32, 32); font-weight: bold; padding-left: 33px; padding-top: 16px;"> 
            <!--You can also write to us on:<br><br> <span style="color:#4E9EE3;">queries@secp.gov.pk</span><br>
OR<br> <span style="color:#4E9EE3;"> complaints@secp.gov.pk</span><br><br>--> 
            OR send us your concern through postal mail at:<br>
            <span style="color:#4E9EE3;"> Securities and Exchange Commission of Pakistan<br>
            NIC Building, 63 Jinnah Avenue, Blue Area, Islamabad <br>
            </span> </div>
          <div class="clearfix"></div>
          <div style="color: rgb(32, 32, 32); font-weight: bold; padding-left: 33px; padding-top: 16px;"> Disclaimer….!!!<br>
            <span style="color:#FF0000;">Please note: If you have not taken up the subject matter complaint at the 1st line regulator level(PSX,CDC, NCCPL, MUFAP etc.) or relevant forum such as company, broker, registrar etc. You are advised to do so first. SECP will not entertain any complaint which has not followed this procedure/protocol.</span> </div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar_3.php');?>
        </div>
      </div>
      
      <!--e-Services-->
      <div id="e_services" class="wizard-step">
        <div class="col-lg-8 padding-top-10 text-center">
          <div class="col-xs-12">
            <h2 style="text-align:left;">e-Services / Technical Issues</h2>
          </div>
          <div class="form-group" id="e_services_inner">
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Company Name:</label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group has-feedback">
              <input type="text" name="e_company_title" id="input_product_e" autocomplete="off" value="" onSelect="update_e_cro(this.value);"  class="form-control backrnd"/>
            </div>
            <div style="clear:both;"></div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Company Registration Office <span style="color: red;font-size: 13px;">*</span> </label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group" id="e_cro_title"> </div>
            <div style="clear:both;"></div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Process Name:<span style="color: red;font-size: 13px;">*</span></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <select name="e_process_name" id="e_process_name"  class="form-control backrnd">
                <option value="">&mdash; Select Process &mdash;</option>
                <?php 
			  $sql_process_names = "Select * from sdms_process_names where type = 'eservices'";
    $res_process_names = db_query($sql_process_names);
    while($row_process_names = db_fetch_array($res_process_names)){?>
                <option value="<?php echo $row_process_names['title'];?>" ><?php echo $row_process_names['title']; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Nature of Complaint:<span style="color: red;font-size: 13px;">*</span></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <select name="e_nature_of_omplaint"  class="form-control backrnd" id="e_nature_of_omplaint">
                <option value="">&mdash; Select Nature of Complaint &mdash;</option>
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
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">User ID: <!--<span style="color: red;font-size: 13px;">*</span>--></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" class="form-control backrnd" name="e_user_id" id="e_user_id">
            </div>
            <div class="col-xs-12">
              <button type="button" class="btn btn-primary float-right" onClick="eservice_check();">Next</button>
              <button type="button" class="btn btn-primary float-right" onClick="goto_dept();" style="margin-right: 8px;">Previous</button>
            </div>
          </div>
        </div>
        <div class="clearfix hidden-lg"></div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar_3.php');?>
        </div>
      </div>
      
      <!--Company Registration/Compliance-->
      <div id="company_registration" class="wizard-step">
        <div class="col-lg-8 padding-top-10 text-center">
          <div class="col-xs-12">
            <h2 class="domain_type_title">Company Registration/Compliance</h2>
          </div>
          <div class="form-group" id="company_registration_inner">
            <div style="clear:both"></div>
            <div class="col-lg-4 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Please state your status:</label>
            </div>
            <div class="col-lg-8 col-xs-12 form-group has-feedback">
              <input type="radio" size="50" name="cr_status" class="cr_status" value="Member" />
              Member
              &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
              <input type="radio" size="50" name="cr_status" class="cr_status" value="Shareholder" />
              Shareholder 
              &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
              <input type="radio" size="50" name="cr_status" class="cr_status" value="Non Shareholder" />
              Non Shareholder </div>
            <div style="clear:both"></div>
            <div id="r_company_cro">
              <div class="col-lg-6 col-xs-12 form-group">
                <label for="complaint">Registered Company Name:<span style="color: red;font-size: 13px;">*</span> </label>
              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                <input type="text" name="cr_company_title" id="input_product_r" autocomplete="off" value=""  class="form-control backrnd"/>
              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                <label for="complaint">CRO: <!--<span style="color: red;font-size: 13px;">*</span>--></label>
              </div>
              <div class="col-lg-6 col-xs-12 form-group" id="r_cro_title"> </div>
            </div>
            <div style="clear:both;"></div>
            <div id="unregistered_entity">
              <div class="col-lg-6 col-xs-12 form-group" >
                <label for="complaint">Unregistered Entity:<span style="color: red;font-size: 13px;">*</span> </label>
              </div>
              <div class="col-lg-6 col-xs-12 form-group" >
                <select name="unregistered_entity" id="unregistered_entit" class="form-control backrnd" onChange="hide_cro_company(this.value);">
                  <option value="">--Select Unregistered Entity--</option>
                  <option value="Under Incorporation">Under Incorporation</option>
                  <option value="Name Reservation">Name Reservation</option>
                  <option value="Other">Other</option>
                </select>
              </div>
            </div>
            <div id="cr_folio_no_cr_no_of_shares">
              <div class="col-lg-6 col-xs-12 form-group">
                <label for="complaint">Folio No:<!--<span style="color: red;font-size: 13px;">*</span>--></label>
              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                <input type="text" class="form-control backrnd" name="cr_folio_no" id="cr_folio_no">
              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                <label for="complaint">No of Shares: <!--<span style="color: red;font-size: 13px;">*</span>--></label>
              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                <input type="text" class="form-control backrnd" name="cr_no_of_shares" id="cr_no_of_shares">
              </div>
            </div>
            <div style="clear:both"></div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Process Name:<span style="color: red;font-size: 13px;">*</span> </label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <select name="cr_process_name"  class="form-control backrnd" id="cr_process_name">
                <option value="">--Select Process--</option>
                <?php 
            $sql_process_names = "Select * from sdms_process_names where type = 'companyregistration'";
            $res_process_names = db_query($sql_process_names);
            while($row_process_names = db_fetch_array($res_process_names)){
            ?>
                <option value="<?php echo $row_process_names['title']; ?>"><?php echo $row_process_names['title']; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="col-xs-12">
              <button type="button" class="btn btn-primary float-right" onClick="cr_check();">Next</button>
              <button type="button" class="btn btn-primary float-right" onClick="goto_dept();" style="margin-right: 8px;">Previous</button>
            </div>
          </div>
        </div>
        <div class="clearfix hidden-lg"></div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar_3.php');?>
        </div>
      </div>
      
      <!--Supervision of Listed Companies-->
      <div id="company_supervision" class="wizard-step">
        <div class="col-lg-8 padding-top-10 text-center">
          <div class="col-xs-12">
            <h2 class="domain_type_title">Supervision of Listed Companies</h2>
          </div>
          <div class="form-group" id="company_registration_inner">
            <div style="clear:both"></div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Name of Company:<span style="color: red;font-size: 13px;">*</span> </label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" name="cs_company_title" id="input_product_s" autocomplete="off" value=""  class="form-control backrnd"/>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">CDC Account No:<span style="color: red;font-size: 13px;">*</span></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" class="form-control backrnd" name="cs_cdc_ac_no" id="cs_cdc_ac_no">
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Folio No: <!--<span style="color: red;font-size: 13px;">*</span>--></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" class="form-control backrnd" name="cs_folio_no" id="cs_folio_no">
            </div>
            <div class="col-xs-12">
              <button type="button" class="btn btn-primary float-right" onClick="slc_check();">Next</button>
              <button type="button" class="btn btn-primary float-right" onClick="goto_dept();" style="margin-right: 8px;">Previous</button>
            </div>
          </div>
        </div>
        <div class="clearfix hidden-lg"></div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar_3.php');?>
        </div>
      </div>
      
      <!--Capital Market-->
      <div id="capital_martket" class="wizard-step">
        <div class="col-lg-8 padding-top-10 text-center">
          <div class="col-xs-12">
            <h2 class="domain_type_title">Capital Market</h2>
          </div>
          <div class="form-group" id="capital_martket_inner">
            <?php
            $sql_cm_tyes = "Select * from sdms_capital_markets Where 1 GROUP BY type order by `sort_number` asc";
            $res_cm_tyes = db_query($sql_cm_tyes);
            while( $row_cm_tyes = db_fetch_array($res_cm_tyes)){ ?>
            <div class="col-lg-3 col-xs-12">
              <label>
                <input type="radio" name="m-system" value="<?php echo $row_cm_tyes['type']; ?>" onClick="show_cm_verify(this.value);" />
                <!--onClick="show_wizard_cm(this.value);"--> 
                <img src="assets/images/<?php echo $row_cm_tyes['icon']; ?>"> </label>
              <p><?php echo $row_cm_tyes['type']; ?></p>
            </div>
            <?php }?>
            <div class="col-xs-12">
              <button type="button" class="btn btn-primary float-right" onClick="goto_dept();" style="margin-right: 8px;">Previous</button>
            </div>
          </div>
          <div class="clearfix"></div>
          <div style="color: rgb(32, 32, 32); font-weight: bold; padding-left: 33px; padding-top: 16px;text-align:left;"> Disclaimer….!!!<br>
            <span style="color:#FF0000;">Please note: If you have not taken up the subject matter complaint at the 1st line regulator level(PSX,CDC, NCCPL, MUFAP etc.) or relevant forum such as company, broker, registrar etc. You are advised to do so first. SECP will not entertain any complaint which has not followed this procedure/protocol.</span> </div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar_3.php');?>
        </div>
      </div>
      <!--Capital Market Types Verification-->
      <div id="cm_verification" class="wizard-step">
        <input type="hidden" name="cm_type_value" id="cm_type_value" value=""  />
        <div class="col-lg-8 padding-top-10">
          <div class="form-group">
            <div class="col-xs-12">
              <h3 style="color: #202020;"><span id="cm_type"></span> Verification</h3>
            </div>
            <div class="col-xs-12">
              <h4 style="font-weight: bold;">Have you taken up the matter with the relevant forum?</h4>
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <button type="button" class="btn btn-primary float-right" data-toggle="collapse" data-target="#show_cmfile_ver" aria-expanded="false" style="width: 100%;margin: 0 auto;margin-top: 40px;padding-top: 10px;" aria-controls="show_cmfile_ver">Yes</button>
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group"> <a href="nothirdparty.php">
              <button type="button" class="btn btn-primary float-right" style="width: 100%;margin: 0 auto;margin-top: 40px;padding-top: 10px;">No</button>
              </a> </div>
            <div id="show_cmfile_ver" class="collapse">
              <div class="col-xs-12">
                <h4 style="font-weight: bold;">Please attach the evidence/relevant document of your communication with the relevant forum.</h4>
              </div>
              <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
                <label for="complaint">Attachment:</label>
                &nbsp;(Maximum Size 2mb in Pdf OR JPG) </div>
              <div class="col-lg-6 col-xs-12 form-group has-feedback">
                <input class="file_multi6" type="file" name="cm_evidence" id="cm_evidence" value="" />
              </div>
              <div class="col-xs-12">
                <button type="button" class="btn btn-primary float-right" onClick="cm_verify();" >Next</button>
                <button type="button" class="btn btn-primary float-right" onClick="goto_types(2);" style="margin-right: 8px;">Previous</button>
              </div>
              <div class="col-xs-offset-3 col-xs-6"> </div>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar_3.php');?>
        </div>
      </div>
      <div id="capital_martket_type_inner" class="wizard-step">
        <div class="col-lg-8 padding-top-10 text-center">
          <div class="col-xs-12">
            <h2 style="text-align:left;">Capital Market -> <span id="cm_specific_type"></span></h2>
          </div>
          <div class="form-group" id="capital_martket_type_inner">
            <div id="brokers_list"></div>
            <div id="brokers_agent"></div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Folio Number <span style="color: red;font-size: 13px;">*</span></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" name="cm_folio_no" class="form-control backrnd" id="cm_folio_no" >
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">CDC A/C No <span style="color: red;font-size: 13px;">*</span></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" class="form-control backrnd" name="cm_cdc_ac_no" id="cm_cdc_ac_no" >
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">No of Shares: <span style="color: red;font-size: 13px;">*</span></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" class="form-control backrnd" name="cm_no_of_shares" id="cm_no_of_shares" >
            </div>
            <div class="col-xs-12">
              <button type="button" class="btn btn-primary float-right" onClick="cm_check();">Next</button>
              <button type="button" class="btn btn-primary float-right" onClick="goto_capital_martket();" style="margin-right: 8px;">Previous</button>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar_3.php');?>
        </div>
      </div>
      
      <!--Insurance-->
      <div id="insurance" class="wizard-step">
        <div class="form-group">
          <div class="col-lg-8 padding-top-10">
            <div class="col-xs-12">
              <h2 class="domain_type_title"> Insurance </h2>
            </div>
            <div class="form-group" id="insurence_inner">
              <?php 
            $sql_insurance_tyes = "Select * from sdms_insurance Where 1 GROUP BY type";
            $res_insurance_tyes = db_query($sql_insurance_tyes);
            while( $row_insurance_tyes = db_fetch_array($res_insurance_tyes)){
            ?>
              <div class="col-lg-3 col-xs-12">
                <label>
                  <input type="radio" name="m-system" value="<?php echo $row_insurance_tyes['type']; ?>" onClick="show_i_verify(this.value);" 
                   />
                  <!--onClick="show_wizard_insurence(this.value);"--> 
                  <img src="assets/images/<?php echo $row_insurance_tyes['icon']; ?>"> </label>
                <p><?php echo $row_insurance_tyes['type']; ?></p>
              </div>
              <?php }?>
              <div class="col-xs-12">
                <button type="button" class="btn btn-primary float-right" onClick="goto_dept();" style="margin-right: 8px;">Previous</button>
              </div>
            </div>
          </div>
          <div class="clearfix hidden-lg"></div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar_3.php');?>
        </div>
      </div>
      <!--Insurance Types Verification-->
      <div id="i_verification" class="wizard-step">
        <input type="hidden" name="i_type_value" id="i_type_value" value=""  />
        <div class="col-lg-8 padding-top-10">
          <div class="form-group">
            <div class="col-xs-12">
              <h3 style="color: #202020;"><span id="i_type"></span> Verification</h3>
            </div>
            <div class="col-xs-12 text-center">
              <h4 style="color: red;font-weight: bold;">Have you taken up the matter with <span id="i_type"></span> or relevant forum?</h4>
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <button type="button" class="btn btn-primary float-right" data-toggle="collapse" data-target="#show_ifile_ver" aria-expanded="false" style="width: 100%;margin: 0 auto;margin-top: 40px;padding-top: 10px;" aria-controls="show_ifile_ver">Yes</button>
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group"> <a href="nothirdparty.php">
              <button type="button" class="btn btn-primary float-right" style="width: 100%;margin: 0 auto;margin-top: 40px;padding-top: 10px;">No</button>
              </a> </div>
            <div id="show_ifile_ver" class="collapse">
              <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
                <label for="complaint">Attachment:</label>
                &nbsp;(Maximum Size 2mb in Pdf OR JPG) </div>
              <div class="col-lg-6 col-xs-12 form-group has-feedback">
                <input class="file_multi6" type="file" name="i_evidence" id="i_evidence" value=""   />
                
                <!--<div class="text" id="mulitplefileuploader5">Upload</div>
            <div id="error6" ></div>
            <div id="status6" ></div>
            <div id="append_fields6">
            <input class="file_multi6" type="hidden" name="evidence_7" id="evidence_7" value=""  />
            </div> --> 
                
              </div>
              <div class="col-xs-12">
                <button type="button" class="btn btn-primary float-right" onClick="i_verify();" >Next</button>
                <button type="button" class="btn btn-primary float-right" onClick="goto_types(3);" style="margin-right: 8px;">Previous</button>
              </div>
              <div class="col-xs-offset-3 col-xs-6"> </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div style="color: rgb(32, 32, 32); font-weight: bold; padding-left: 33px; padding-top: 16px;"> Disclaimer….!!!<br>
            <span style="color:#FF0000;">Please note: If you have not taken up the subject matter complaint at the 1st line regulator level(PSX,CDC, NCCPL, MUFAP etc.) or relevant forum such as company, broker, registrar etc. You are advised to do so first. SECP will not entertain any complaint which has not followed this procedure/protocol.</span> </div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar_3.php');?>
        </div>
      </div>
      <div id="insurance_type_inner" class="wizard-step">
        <div class="form-group">
          <div class="col-lg-8 padding-top-10">
            <div class="col-xs-12">
              <h2 class="domain_type_title"> Insurance -> <span id="insurance_specific_type"></span></h2>
            </div>
            <div class="form-group" id="insurence_inner">
              <div id="insurance_list"></div>
              <div id="insurance_agent"></div>
              <div class="col-lg-6 col-xs-12 form-group">
                <label for="complaint">Policy No.<span style="color: red;font-size: 13px;">*</span> </label>
              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                <input type="text" name="i_policy_no" id="i_policy_no" class="form-control backrnd" >
              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                <label for="complaint">Sum Assured<span style="color: red;font-size: 13px;">*</span></label>
              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                <input type="text" class="form-control backrnd" name="i_sum_assured" id="i_sum_assured" >
              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                <label for="complaint">Claim Amount: <!--<span style="color: red;font-size: 13px;">*</span>--></label>
              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                <input type="text" class="form-control backrnd" name="i_claim_amount" id="i_claim_amount">
              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                <label for="complaint">Folio No: <!--<span style="color: red;font-size: 13px;">*</span>--></label>
              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                <input type="text" class="form-control backrnd" name="i_folio_no" id="i_folio_no">
              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                <label for="complaint">No of Shares: <!--<span style="color: red;font-size: 13px;">*</span>--></label>
              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                <input type="text" class="form-control backrnd" name="i_no_of_shares" id="i_no_of_shares">
              </div>
              <div class="col-xs-12">
                <button type="button" class="btn btn-primary float-right" onClick="insurance_check();">Next</button>
                <button type="button" class="btn btn-primary float-right" onClick="goto_insurence();" style="margin-right: 8px;">Previous</button>
              </div>
            </div>
          </div>
          <div class="clearfix hidden-lg"></div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar_3.php');?>
        </div>
      </div>
      
      <!--AMC/Mutual Funds/Modarabah/Leasing/Investment Banking/REIT-->
      <div id="specialized_companies" class="wizard-step">
        <div class="form-group">
          <div class="col-lg-8 padding-top-10 text-center">
            <div class="col-xs-12">
              <h2 class="domain_type_title"> AMC/Mutual Funds/Modarabah/Leasing/Investment Banking/REIT </h2>
            </div>
            <div class="form-group" id="specialized_companies_inner">
              <?php 
			$sql_scd_tyes = "Select * from sdms_scd Where 1 GROUP BY type";
			$res_scd_tyes = db_query($sql_scd_tyes);
			while( $row_scd_tyes = db_fetch_array($res_scd_tyes)){
            ?>
              <div class="col-lg-3 col-xs-12">
                <label>
                  <input type="radio" name="m-system" value="<?php echo $row_scd_tyes['type']; ?>" onClick="show_scd_verify(this.value);"  />
                  <!--onClick="show_wizard_scd(this.value);"--> 
                  <img src="assets/images/<?php echo $row_scd_tyes['icon']; ?>"> </label>
                <p><?php echo $row_scd_tyes['type']; ?></p>
              </div>
              <?php }?>
              <div class="col-xs-12">
                <button type="button" class="btn btn-primary float-right" onClick="goto_dept();" style="margin-right: 8px;">Previous</button>
              </div>
            </div>
          </div>
          <div class="clearfix hidden-lg"></div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar_3.php');?>
        </div>
      </div>
      <!--AMC/Mutual Funds/Modarabah/Leasing/Investment Banking/REIT Types Verification-->
      <div id="scd_verification" class="wizard-step">
        <input type="hidden" name="scd_type_value" id="scd_type_value" value=""  />
        <div class="col-lg-8 padding-top-10">
          <div class="form-group">
            <div class="col-xs-12">
              <h3 style="color: #202020;"><span id="scd_type"></span> Verification</h3>
            </div>
            <div class="col-xs-12 text-center">
              <h4 style="color: red;font-weight: bold;">Have you taken up the matter with <span id="scd_type"></span> or relevant forum?</h4>
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <button type="button" class="btn btn-primary float-right" data-toggle="collapse" data-target="#show_scdfile_ver" aria-expanded="false" style="width: 100%;margin: 0 auto;margin-top: 40px;padding-top: 10px;" aria-controls="show_scdfile_ver">Yes</button>
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group"> <a href="nothirdparty.php">
              <button type="button" class="btn btn-primary float-right" style="width: 100%;margin: 0 auto;margin-top: 40px;padding-top: 10px;">No</button>
              </a> </div>
            <div id="show_scdfile_ver" class="collapse">
              <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
                <label for="complaint">Attachment:</label>
                &nbsp;(Maximum Size 2mb in Pdf OR JPG) </div>
              <div class="col-lg-6 col-xs-12 form-group has-feedback">
                <input class="file_multi6" type="file" name="scd_evidence" id="scd_evidence" value=""   />
                
                <!--<div class="text" id="mulitplefileuploader5">Upload</div>
            <div id="error6" ></div>
            <div id="status6" ></div>
            <div id="append_fields6">
            <input class="file_multi6" type="hidden" name="evidence_7" id="evidence_7" value=""  />
            </div> --> 
                
              </div>
              <div class="col-xs-12">
                <button type="button" class="btn btn-primary float-right" onClick="scd_verify();" >Next</button>
                <button type="button" class="btn btn-primary float-right" onClick="goto_types(4);" style="margin-right: 8px;">Previous</button>
              </div>
              <div class="col-xs-offset-3 col-xs-6"> </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div style="color: rgb(32, 32, 32); font-weight: bold; padding-left: 33px; padding-top: 16px;"> Disclaimer….!!!<br>
            <span style="color:#FF0000;">Please note: If you have not taken up the subject matter complaint at the 1st line regulator level(PSX,CDC, NCCPL, MUFAP etc.) or relevant forum such as company, broker, registrar etc. You are advised to do so first. SECP will not entertain any complaint which has not followed this procedure/protocol.</span> </div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar_3.php');?>
        </div>
      </div>
      <div id="specialized_companies_type_inner" class="wizard-step">
        <div class="form-group">
          <div class="col-lg-8 padding-top-10 text-center">
            <div class="col-xs-12">
              <h2 class="domain_type_title"> AMC/Mutual Funds/Modarabah/Leasing/Investment Banking/REIT  -> <span id="sc_specific_type"></span></h2>
            </div>
            <div class="form-group" id="specialized_companies_inner">
              <div id="scd_list"></div>
              <div id="reit_scheme"></div>
              <div id="modaraba_fund"></div>
              <div id="mutual_fund"></div>
              <div id="pension_fund"></div>
              <div class="col-lg-6 col-xs-12 form-group">
                <label for="complaint">Registration No./Folio No.<span style="color: red;font-size: 13px;">*</span></label>
              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                <input type="text" name="scd_folio_no" id="scd_folio_no" class="form-control backrnd" >
              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                <label for="complaint">CDC A/C No:<!--<span style="color: red;font-size: 13px;">*</span>--></label>
              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                <input type="text" class="form-control backrnd" name="scd_cdc_ac_no" id="scd_cdc_ac_no" >
              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                <label for="complaint">No. of Certificates: <!--<span style="color: red;font-size: 13px;">*</span>--></label>
              </div>
              <div class="col-lg-6 col-xs-12 form-group">
                <input type="text" class="form-control backrnd" name="scd_no_of_units" id="scd_no_of_units">
              </div>
              <div class="col-xs-12">
                <button type="button" class="btn btn-primary float-right" onClick="scd_check();">Next</button>
                <button type="button" class="btn btn-primary float-right" onClick="goto_specialized_companies();" style="margin-right: 8px;">Previous</button>
              </div>
            </div>
          </div>
          <div class="clearfix hidden-lg"></div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar_3.php');?>
        </div>
      </div>
      
      
      <div id="finish" class="wizard-step">
        <div class="col-lg-8 padding-top-10" style="color: black;">
          <div class="col-xs-12">
            <h2 class="domain_type_title">Complaint Information </h2>
          </div>
          <div class="form-group">
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Subject <span style="color: red;font-size: 13px;">*</span> </label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" name="subject" id="subject" class="form-control backrnd" required>
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Details</label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group has-feedback">
              <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Attachment:</label>
              &nbsp;(Maximum Size 2mb in Pdf OR JPG) </div>
            <div class="col-lg-6 col-xs-12 form-group has-feedback">
              <div class="text" id="mulitplefileuploader">Upload</div>
              <div id="error" ></div>
              <div id="status" ></div>
              <div id="append_fields">
                <input class="file_multi" type="hidden" name="evidence_1" id="evidence_1" value="" />
              </div>
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Attachment:</label>
              &nbsp;(Maximum Size 2mb in Pdf OR JPG) </div>
            <div class="col-lg-6 col-xs-12 form-group has-feedback">
              <div class="text" id="mulitplefileuploader1">Upload</div>
              <div id="error1" ></div>
              <div id="status1" ></div>
              <div id="append_fields1">
                <input class="file_multi1" type="hidden" name="evidence_2" id="evidence_2" value="" />
              </div>
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Attachment:</label>
              &nbsp;(Maximum Size 2mb in Pdf OR JPG) </div>
            <div class="col-lg-6 col-xs-12 form-group has-feedback">
              <div class="text" id="mulitplefileuploader2">Upload</div>
              <div id="error2" ></div>
              <div id="status2" ></div>
              <div id="append_fields2">
                <input class="file_multi2" type="hidden" name="evidence_3" id="evidence_3" value="" />
              </div>
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Attachment:</label>
              &nbsp;(Maximum Size 2mb in Pdf OR JPG) </div>
            <div class="col-lg-6 col-xs-12 form-group has-feedback">
              <div class="text" id="mulitplefileuploader3">Upload</div>
              <div id="error3" ></div>
              <div id="status3" ></div>
              <div id="append_fields3">
                <input class="file_multi3" type="hidden" name="evidence_4" id="evidence_4" value="" />
              </div>
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Attachment:</label>
              &nbsp;(Maximum Size 2mb in Pdf OR JPG) </div>
            <div class="col-lg-6 col-xs-12 form-group has-feedback">
              <div class="text" id="mulitplefileuploader4">Upload</div>
              <div id="error4" ></div>
              <div id="status4" ></div>
              <div id="append_fields4">
                <input class="file_multi4" type="hidden" name="evidence_5" id="evidence_5" value="" />
              </div>
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Attachment:</label>
              &nbsp;(Maximum Size 2mb in Pdf OR JPG) </div>
            <div class="col-lg-6 col-xs-12 form-group has-feedback">
              <div class="text" id="mulitplefileuploader5">Upload</div>
              <div id="error5" ></div>
              <div id="status5" ></div>
              <div id="append_fields5">
                <input class="file_multi5" type="hidden" name="evidence_6" id="evidence_6" value=""  />
              </div>
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
          <div class="col-lg-6 col-xs-12 form-group has-feedback"> <span class="captcha"><img src="captcha.php" border="0" align="left"></span>
            <input id="captcha" type="text" name="captcha" size="6" required  tabindex="14" title="Enter the text as shown in the figure" class="field">
          </div>
          <?php }?>
          <div class="col-xs-12"><!--onclick="return confirm('I do hereby solemnly affirm and declare that this statement and the accompanying complaint, documents and reports are true, correct and reliable to the best of my knowledge and belief, and nothing has been concealed therefrom.')"-->
            <button type="submit" class="btn btn-primary float-right"  id="btnSubmit">Submit</button>
            <img src="assets/images/ajax-loader.gif" class="float-right" style="width:40px;height:40px;display:none;" id="loding_form"> </div>
          <div class="clearfix hidden-lg"></div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar_4.php');?>
        </div>
      </div>
    </div>
  </form>
  <div class="padding-top-10">&nbsp;</div>
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
        <p style="line-height: 18px;"> <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Capital Markets, its intermediaries (such as CDC, NCCPL, PSX, PMEX etc.)<br>
          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Trading of shares of Listed Companies<br>
          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> PSX Brokers/Agents, PMEX Broker, Book Runner, Share Registrar etc.<br>
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
        <p style="line-height: 18px;"> <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Insurance Companies<br>
          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Insurance Brokers or Agents<br>
          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Surveyors Or<br>
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
        <p style="line-height: 18px;"> <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Asset Management Companies<br>
          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Mutual Funds<br>
          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Pension Funds<br>
          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Investment Advisors<br>
          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Real Estate Investment Trusts (REITS)<br>
          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Leasing Companies<br>
          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Investment Banks<br>
          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Microfinance Companies<br>
          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Housing Finance Companies<br>
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
        <h3 class="modal-title text-center" style="color: #ffffff;"> e-Services / Technical Issues </h3>
      </div>
      <div class="modal-body" style="color: #4a4a4a;font-size: 12px;">
        <h5 style="color: red;">Please select if:</h5>
        <p style="line-height: 18px;"> <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i> </span> You are a first time user or an existing user of e-Services and wish to report a complaint regarding any e-Services issue of a technical nature such as login issues, form/process submission difficulties etc.<br>
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
        <p style="line-height: 18px;"> <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i> Private/public unlisted companies including public sector companies<br>
          </span> <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i> Not for profit companies, please select this domain</span>
        <p style="text-align: center !important;"> OR </p>
        </p>
        <h5 style="color: red;">A complaint with the concerned registrar regarding:</h5>
        <p> <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i> Registration of information (such as filing of returns, submission of applications for seeking approvals etc.)<br>
          </span> <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i> Provision of information by registrar concerned (such as certified copies of documents, memorandum of association/articles of association. financial statements and inspection of records of companies, etc.)<br>
          </span> <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i> Non-compliance with provisions of the companies ordinance, please select this domain.<br>
          </span> </p>
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
        <p style="line-height: 18px;"> <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Transfer of shares<br>
          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Issue and payment of dividends<br>
          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Receipt of Annual and quarterly accounts or notice of shareholding meetings<br>
          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Availability of complete company information on website<br>
          <span> <i class="fa fa-arrow-right" aria-hidden="true" style="color: #03BEA3;"></i></span> Fraud /siphoning of assets in the Company by the management or its employees etc.<br>
        </p>
      </div>
    </div>
  </div>
</div>

<!-- modal section end --> 
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.14.0/jquery.validate.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script> 
<script src="assets/js/jquery.simplewizard.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script> 
<script>
$("#wizard1").submit(function(){
  var subject = $('#subject').val();
  var message = $('#message').val();
  var captcha = $('#captcha').val();
    if(subject == '')
	{
	alert("Please Fill Subject.");
	return false;
	}else if(message == ''){
	alert("Please Fill Details.");
	return false;
	}else if (captcha == '')
	{
	alert("Please enter the text as shown in the Captacha figure.");
	return false;	
	}else if(confirm('I do hereby solemnly affirm and declare that this statement and the accompanying complaint, documents and reports are true, correct and reliable to the best of my knowledge and belief, and nothing has been concealed therefrom.')){		
	$(this).find(':submit').attr('disabled','disabled');
    $('#loding_form').show();
	}
  
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
minLength: 1,
 close: function( event, ui ) {  update_e_cro(this.value); }
});

$( "#input_product_r" ).autocomplete({
source: "search_company_name.php",
minLength: 1,
 close: function( event, ui ) { 
  update_r_cro(this.value);
  $('#unregistered_entity').remove();
   }
});

$( "#input_product_s" ).autocomplete({
source: "search_company_name.php",
minLength: 1
});
 });
function hide_cro_company(unregistered_entity){
	if(unregistered_entity == '')
	{
	$('#cr_folio_no_cr_no_of_shares').show();
	$('#r_company_cro').show();
	}else
	{
	$('#cr_folio_no_cr_no_of_shares').hide();
	$('#r_company_cro').hide();
	} }
function update_e_cro(company_title){
	$.ajax({
	url:"get_e_cro.php",
	data: "company_title="+company_title,
	success: function(msg){
	$("#e_cro_title").html(msg);
	}
	}); }
function update_r_cro(company_title){
	$.ajax({
	url:"get_r_cro.php",
	data: "company_title="+company_title,
	success: function(msg){
	$("#r_cro_title").html(msg);
	}
	});}
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

function show_cm_verify(b_type){
$('.collapse').collapse("hide");
$('#cm_type_value').val(b_type);	
$('#cm_type').html(b_type);
$('#li_cm_verification').click();}
function show_wizard_cm(b_type){
$.ajax({
url:"get_cm_brokers.php",
data: "b_type="+b_type,
success: function(msg){	
$("#brokers_list").html(msg);
}
});	
$("#cm_specific_type").html(b_type);
$('#li_capital_martket_type_inner').click();
 }	
function show_cm_agent_list(parent){
	$.ajax({
	url:"get_cm_brokers_agents.php",
	data: "parent="+parent,
	success: function(msg){
	$("#brokers_agent").html(msg);
	
}
});	}
function goto_capital_martket(){ $('#li_capital_martket').click(); }


function show_i_verify(b_type){
$('.collapse').collapse("hide");
$('#i_type_value').val(b_type);	
$('#i_type').html(b_type);
$('#li_i_verification').click();}
function show_wizard_insurence(b_type){	
$("#insurance_agent").html('');
$.ajax({
url:"get_insurance_brokers.php",
data: "b_type="+b_type,
success: function(msg){	
$("#insurance_list").html(msg);
}
});	
$("#insurance_specific_type").html(b_type);
$('#li_insurance_type_inner').click();}
function show_insurance_agent_list(type,parent){
	$.ajax({
	url:"get_insurance_brokers_agents.php",
	data: "parent="+parent+"&type="+type,
	success: function(msg){
	$("#insurance_agent").html(msg);
}
});	}
function goto_insurence(){ $('#li_insurance').click(); }

function show_scd_verify(b_type){
$('.collapse').collapse("hide");
$('#scd_type_value').val(b_type);	
$('#scd_type').html(b_type);
$('#li_scd_verification').click();}
function show_wizard_scd(b_type){
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
$("#sc_specific_type").html(b_type);
$('#li_specialized_companies_type_inner').click();

}
});	 }
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
function goto_specialized_companies(){ $('#li_specialized_companies').click(); }

function set_sms_verify(){
var user_mobile = $('#phone').val();
/*var user_email = $('#user_email').val();
var applicant_address = $('#applicant_address').val();
if(user_email == '' && applicant_address == '')
{
alert('Please Fill either Email or Postal Address.');
//$('#applicant_address').removeAttr('required');
}*/
if(user_mobile!=''){
	$.ajax({
	url:"send_sms_to_mobile.php",
	data: "user_mobile="+user_mobile,
	success: function(msg){	
	$("#sms_verification_number").html(msg);
	}
	});
} }
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
	 
function eservice_check(){
	var input_product_e = $('#input_product_e').val();
	var e_process_name = $('#e_process_name').val();
	var e_nature_of_omplaint = $('#e_nature_of_omplaint').val();	
	//var e_user_id = $('#e_user_id').val();
	if(input_product_e == '')
	{
	alert("Please Fill Company Name.");
	}
	else if(e_process_name == ''){
	alert("Please Fill Process Name.");
	}
	else if(e_nature_of_omplaint == '')
	{
	alert("Please Fill Nature of Complaint.");
	}/*else if(e_user_id == '')
	{
	alert("Please Fill User ID.");
	}*/else{
	$('#li_finish').click();
	} }
function cr_check(){	
	var input_product_r = $('#input_product_r').val();	
	var unregistered_entit = $('#unregistered_entit').val();
	//alert(unregistered_entit);
	//var cr_folio_no = $('#cr_folio_no').val();
	//var cr_no_of_shares = $('#cr_no_of_shares').val();
	var cr_process_name = $('#cr_process_name').val();
	
	if(!$(".cr_status").is(":checked"))
	{
	alert("Please Fill Status.");
	return false;
	}
	
	if(input_product_r != ''){
		
		/*if(cr_folio_no == '')
		{
		alert("Please Fill Folio Number.");
		return false;
		}
		else if(cr_no_of_shares == '')
		{
		alert("Please Fill No of Shares.");
		return false;
		}
		else*/ if(cr_process_name == '')
		{
		alert("Please Fill Process Name.");
		return false;
		}
		else{
		$('#li_finish').click();
		}
	}else if(unregistered_entit != '')
	{
		if(cr_process_name == '')
		{
		alert("Please Fill Process Name.");
		return false;
		}else{
		$('#li_finish').click();
		}
		
	}else if(input_product_r == '' && unregistered_entit == '')
	{		
		alert("Please Fill Company Name.");
		return false;
		
	} }
function slc_check(){
	var input_product_s = $('#input_product_s').val();
	var cs_cdc_ac_no = $('#cs_cdc_ac_no').val();	
	//var cs_folio_no = $('#cs_folio_no').val();
	if(input_product_s == '')
	{
	alert("Please Fill Company Name.");
	}
	else if(cs_cdc_ac_no == ''){
	alert("Please Fill CDC Account No.");
	}
	/*else if(cs_folio_no == '')
	{
	alert("Please Fill Folio No.");
	}*/else{
	$('#li_finish').click();
	} }

function cm_verify(){
	var cm_evidence = $('#cm_evidence').val();
	var b_type	= $('#cm_type_value').val();
	if(cm_evidence) { // returns true if the string is not empty
        show_wizard_cm(b_type);
    } else { // no file was selected
        alert("no file selected");
    }}
function cm_check(){
	var cm_broker = $('#cm_broker').val();
	var cm_agent = $('#cm_agent').val();	
	var cm_folio_no = $('#cm_folio_no').val();
	var cm_cdc_ac_no = $('#cm_cdc_ac_no').val();
	var cm_no_of_shares = $('#cm_no_of_shares').val();
	
	if(cm_broker == '')
	{
	alert("Please Fill Broker Name.");
	}
	else if(cm_agent == ''){
	alert("Please Fill Broker Agent.");
	}
	else if(cm_folio_no == '')
	{
	alert("Please Fill Folio No.");
	}else if(cm_cdc_ac_no == '')
	{
	alert("Please Fill CDC Account No.");
	}else if(cm_no_of_shares == '')
	{
	alert("Please Fill No of Shares.");
	}else{
	$('#li_finish').click();
	} 
	}
	
function i_verify(){
	var i_evidence = $('#i_evidence').val();
	var b_type	= $('#i_type_value').val();
	if(i_evidence) { // returns true if the string is not empty
        show_wizard_insurence(b_type);
    } else { // no file was selected
        alert("no file selected");
    }}	
function insurance_check(){
	var i_broker = $('#i_broker').val();
	var i_agent = $('#i_agent').val();	
	var i_policy_no = $('#i_policy_no').val();
	var i_sum_assured = $('#i_sum_assured').val();
	//var i_claim_amount = $('#i_claim_amount').val();
	//var i_folio_no = $('#i_folio_no').val();
	//var i_no_of_shares = $('#i_no_of_shares').val();
	
	if(i_broker == '')
	{
	alert("Please Fill Broker Name.");
	}
	else if(i_agent == ''){
	alert("Please Fill Broker Agent.");
	}
	else if(i_policy_no == '')
	{
	alert("Please Fill Policy No.");
	}else if(i_sum_assured == '')
	{
	alert("Please Fill Sum Assured.");
	}/*else if(i_claim_amount == '')
	{
	alert("Please Fill Claim Amount.");
	}
	else if(i_folio_no == '')
	{
	alert("Please Fill Folio No.");
	}
	else if(i_no_of_shares == '')
	{
	alert("Please Fill No of Shares.");
	}*/else{
	$('#li_finish').click();
	} 
	}	
	
function scd_verify(){
	var scd_evidence = $('#scd_evidence').val();
	var b_type	= $('#scd_type_value').val();
	if(scd_evidence) { // returns true if the string is not empty
        show_wizard_scd(b_type);
    } else { // no file was selected
        alert("no file selected");
    }}		
function scd_check(){
	var scd_broker = $('#scd_broker').val();
	var reit = $('#reit').val();	
	var modaraba = $('#modaraba').val();	
	var mutual = $('#mutual').val();	
	var pension = $('#pension').val();	
	var scd_folio_no = $('#scd_folio_no').val();
	/*var scd_cdc_ac_no = $('#scd_cdc_ac_no').val();
	var scd_no_of_units = $('#scd_no_of_units').val();*/
	
	if(scd_broker == '')
	{
	alert("Please Fill Company Name.");
	}
	else if(reit == ''){
	alert("Please Fill REIT Scheme.");
	}else if(modaraba == ''){
	alert("Please Fill Modaraba Scheme.");
	}else if(mutual == ''){
	alert("Please Fill Mutual Scheme.");
	}else if(pension == ''){
	alert("Please Fill Pension Scheme.");
	}else if(scd_folio_no == ''){
	alert("Please Fill Folio No.");
	}/*else if(scd_cdc_ac_no == ''){
	alert("Please Fill CDC Account No.");
	}else if(scd_no_of_units == ''){
	alert("Please Fill No. of Certificates.");
	}*/else{
	$('#li_finish').click();
	}  }	
	
function goto_last(){
$('#li_finish').click();
}
function goto_dept(){
$('#li_deprts').click();
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
var f_settings = {
	url: "upload.php",
	method: "POST",
	allowedTypes:"jpg,jpeg,png,pdf",
	fileName: "myfile",
	multiple: false,
	onSuccess:function(files,data,xhr)
	{
		
		$('#error').html('');
		$("#status").html("<font color='green'>Upload is success</font>");
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
		path1=arr[2];
		// $("#append_fields").html('<input class="file_multi" type="hidden" name="evidence_1" value="'+path1+'" />');
		 $("#evidence_1").val(path1);
	},
	onError: function(files,status,errMsg)
	{		
		$("#status").html("<font color='red'>Upload is Failed</font>");
	}
}
var f_settings1 = {
	url: "upload.php",
	method: "POST",
	allowedTypes:"jpg,jpeg,png,pdf",
	fileName: "myfile",
	multiple: false,
	onSuccess:function(files,data,xhr)
	{
		
		$('#error1').html('');
		$("#status1").html("<font color='green'>Upload is success</font>");
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
		path1=arr[2];
		// $("#append_fields1").html('<input class="file_multi1" type="hidden" name="technical_doc" value="'+path1+'" />');
		$("#evidence_2").val(path1);
		
		
	},
	onError: function(files,status,errMsg)
	{		
		$("#status1").html("<font color='red'>Upload is Failed</font>");
	}
}
var f_settings2 = {
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
		path1=arr[2];
		// $("#append_fields2").html('<input class="file_multi2" type="hidden" name="financial_doc" value="'+path1+'" />');
		 $("#evidence_3").val(path1);
	},
	onError: function(files,status,errMsg)
	{		
		$("#status2").html("<font color='red'>Upload is Failed</font>");
	}
}
var f_settings3 = {
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
		path1=arr[2];
		// $("#append_fields3").html('<input class="file_multi3" type="hidden" name="financial_doc" value="'+path1+'" />');
		 $("#evidence_4").val(path1);
	},
	onError: function(files,status,errMsg)
	{		
		$("#status3").html("<font color='red'>Upload is Failed</font>");
	}
}
var f_settings4 = {
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
		path1=arr[2];
		// $("#append_fields4").html('<input class="file_multi4" type="hidden" name="financial_doc" value="'+path1+'" />');
		 $("#evidence_5").val(path1);
	},
	onError: function(files,status,errMsg)
	{		
		$("#status4").html("<font color='red'>Upload is Failed</font>");
	}
}
var f_settings5 = {
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
		path1=arr[2];
		// $("#append_fields5").html('<input class="file_multi5" type="hidden" name="financial_doc" value="'+path1+'" />');
		 $("#evidence_6").val(path1);
	},
	onError: function(files,status,errMsg)
	{		
		$("#status5").html("<font color='red'>Upload is Failed</font>");
	}
}
var f_settings6 = {
	url: "upload.php",
	method: "POST",
	allowedTypes:"jpg,jpeg,png,pdf",
	fileName: "myfile",
	multiple: false,
	formData: null,
	returnType: null,
	formData: {},
	onSuccess:function(files,data,xhr)
	{
		$('#error6').html('');
		$("#status6").html("<font color='green'>Upload is success</font>");
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
		path1=arr[2];
		// $("#append_fields5").html('<input class="file_multi5" type="hidden" name="financial_doc" value="'+path1+'" />');
		 $("#evidence_7").val(path1);
	},
	onError: function(files,status,errMsg)
	{		
		$("#status6").html("<font color='red'>Upload is Failed</font>");
	}
}
$("#mulitplefileuploader").uploadFile(f_settings);
$("#mulitplefileuploader1").uploadFile(f_settings1);
$("#mulitplefileuploader2").uploadFile(f_settings2);
$("#mulitplefileuploader3").uploadFile(f_settings3);
$("#mulitplefileuploader4").uploadFile(f_settings4);
$("#mulitplefileuploader5").uploadFile(f_settings5);
$("#mulitplefileuploader6").uploadFile(f_settings6);

$("#save").submit(function(e){
	var myfile=$('.file_multi').val();
	var myfile1=$('.file_multi1').val();
	var myfile2=$('.file_multi2').val();
	var myfile3=$('.file_multi3').val();
	var myfile4=$('.file_multi4').val();
	var myfile5=$('.file_multi5').val();	
	var myfile6=$('.file_multi6').val();	
			/*if(myfile=='' && myfile1=='' &
			& myfile2==''){
				e.preventDefault();
				$('#error').html('Please Upload a Document');
			}*/
		});

});
</script>