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
      <?php  if($_REQUEST['action']=='query') {?>
   <input type="hidden" value="1" name="isquery" >
 <?php }else{?>
   <input type="hidden" value="0" name="isquery" >
 <?php }?>
    <div class="wizard-header"  style="display:none;" ><!---->
      <ul class="nav nav-tabs">
        <li role="presentation" class="wizard-step-indicator"><a id="li_profile" href="#profile">Profile</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_verification" href="#verification">Verification</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_deprts" href="#deprts">Departments</a></li>
        
        <li role="presentation" class="wizard-step-indicator"><a id="li_capital_martket" href="#capital_martket">Capital Market</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_capital_martket_type_inner" href="#capital_martket_type_inner">Capital Market Inner</a></li>
        
        <li role="presentation" class="wizard-step-indicator"><a id="li_insurance" href="#insurance">Insurance</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_insurance_type_inner" href="#insurance_type_inner">Insurance</a></li>
        
        <li role="presentation" class="wizard-step-indicator"><a id="li_specialized_companies" href="#specialized_companies">Specialized Companies</a></li>
        <li role="presentation" class="wizard-step-indicator"><a id="li_specialized_companies_type_inner" href="#specialized_companies_type_inner">Specialized Companies</a></li>
        
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
              <input type="text" name="name" class="form-control backrnd"  ><!--required-->
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Mobile Number<span style="color: red;font-size: 13px;">*</span></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" class="form-control backrnd" name="phone" id="phone" ><!--required-->
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">CNIC/NICOP/Passport <span style="color: red;font-size: 13px;">*</span></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" class="form-control backrnd" name="nic" ><!--required-->
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Email </label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="email" class="form-control backrnd" name="email" >
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Alternate Email</label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" class="form-control backrnd" name="alternate_email">
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Landline Number</label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" class="form-control backrnd" name="landline_number">
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Country <span style="color: red;font-size: 13px;">*</span></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group has-feedback">
              <select id="jumpMenu" name="district" onChange="get_tehsils(this.value);" class="form-control backrnd" ><!--required-->
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
              <textarea name="applicant_address" class="form-control" ></textarea><!--required-->
            </div>
          </div>
          <div class="col-xs-12">
            <button type="button" class="wizard-next btn btn-primary float-right" onClick="set_sms_verify();" >Next</button>
          </div>
          <div class="clearfix hidden-lg"></div>
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
            <div class="col-xs-12 text-center" ><!--required-->
              <input type="text" name="sms_vrify" id="sms_code"  class="form-control" placeholder="Enter Your Code" style="width: 40%; margin: 0 auto;"   >
              <span class="fa fa-info-circle errspan"></span> <img class="img-responsive" src="assets/images/sms.png" height="250px" width="60%" style="margin:0 auto;height: 165px;"> </div>
            <div class="col-xs-offset-3 col-xs-6">
              <button type="button" class="btn btn-primary float-right" onClick="check_sms_verify();"  style="width: 100%;margin: 0 auto;margin-top: 40px;padding-top: 10px;">Next</button>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar.php');?>
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
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar.php');?>
        </div>
      </div>
      
      <div id="capital_martket" class="wizard-step">
        <div class="col-lg-8 padding-top-10 text-center">
          <div class="col-xs-12">
            <h2 style="text-align: left;">Capital Market</h2>
          </div>
          <div class="form-group" id="capital_martket_inner">
            <?php 
            $sql_cm_tyes = "Select * from sdms_capital_markets Where 1 GROUP BY type";
            $res_cm_tyes = db_query($sql_cm_tyes);
            while( $row_cm_tyes = db_fetch_array($res_cm_tyes)){ ?>
            <div class="col-lg-3 col-xs-12">
              <label>
                <input type="radio" name="m-system" value="<?php echo $row_cm_tyes['type']; ?>" onClick="show_wizard_cm(this.value);" />
                <img src="assets/images/<?php echo $row_cm_tyes['icon']; ?>"> </label>
              <p><?php echo $row_cm_tyes['type']; ?></p>
            </div>
            <?php }?>
            <div class="col-xs-12">
              <button type="button" class="btn btn-primary float-right" onClick="goto_dept();" style="margin-right: 8px;">Previous</button>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar.php');?>
        </div>
      </div>
      <div id="capital_martket_type_inner" class="wizard-step">
        <div class="col-lg-8 padding-top-10 text-center">
          <div class="col-xs-12">
            <h2 style="text-align: left;" >Capital Market -> <span id="cm_specific_type"></span></h2>
          </div>
          <div class="form-group" id="capital_martket_type_inner">
                <div id="brokers_list"></div>
                <div id="brokers_agent"></div>
                <div class="col-lg-12 col-xs-12 form-group">
                <label for="complaint">Please Select at least from (Folio Number) OR (CDC A/C No.)</label>
                </div>
                <div class="col-lg-6 col-xs-12 form-group">
                <label for="complaint">Folio Number<span style="color: red;font-size: 13px;">*</span> </label>
                </div>
                <div class="col-lg-6 col-xs-12 form-group">
                <input type="text" name="cm_folio_no" class="form-control backrnd" >
                </div>
                <div class="col-lg-6 col-xs-12 form-group">
                <label for="complaint">CDC A/C No<span style="color: red;font-size: 13px;">*</span></label>
                </div>
                <div class="col-lg-6 col-xs-12 form-group">
                <input type="text" class="form-control backrnd" name="cm_cdc_ac_no" >
                </div>
                <div class="col-lg-6 col-xs-12 form-group">
                <label for="complaint">No of Shares: <!--<span style="color: red;font-size: 13px;">*</span>--></label>
                </div>
                <div class="col-lg-6 col-xs-12 form-group">
                <input type="text" class="form-control backrnd" name="cm_no_of_shares">
                </div>
                <div class="col-xs-12">
                <button type="button" class="btn btn-primary float-right" onClick="goto_last();">Next</button>
                <button type="button" class="btn btn-primary float-right" onClick="goto_capital_martket();" style="margin-right: 8px;">Previous</button>
                </div>
          </div>
          <div class="clearfix"></div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar.php');?>
        </div>
      </div>
      
      <div id="insurance" class="wizard-step">
        <div class="form-group">
          <div class="col-lg-8 padding-top-10">
            <div class="col-xs-12">
              <h2 style="text-align: left;"> Insurance </h2>
            </div>
            <div class="form-group" id="insurence_inner">
              <?php 
            $sql_insurance_tyes = "Select * from sdms_insurance Where 1 GROUP BY type";
            $res_insurance_tyes = db_query($sql_insurance_tyes);
            while( $row_insurance_tyes = db_fetch_array($res_insurance_tyes)){
            ?>
              <div class="col-lg-3 col-xs-12">
                <label>
                  <input type="radio" name="m-system" value="<?php echo $row_insurance_tyes['type']; ?>" onClick="show_wizard_insurence(this.value);" />
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
          <?php include('../includes/right_side_bar.php');?>
        </div>
      </div>
      <div id="insurance_type_inner" class="wizard-step">
        <div class="form-group">
          <div class="col-lg-8 padding-top-10">
            <div class="col-xs-12">
              <h2 style="text-align: left;"> Insurance -> <span id="insurance_specific_type"></span></h2>
            </div>
            <div class="form-group" id="insurence_inner">
         <div id="insurance_list"></div>
  <div id="insurance_agent"></div>
  <div class="col-lg-6 col-xs-12 form-group">
    <label for="complaint">Policy No.<span style="color: red;font-size: 13px;">*</span> </label>
  </div>
  <div class="col-lg-6 col-xs-12 form-group">
    <input type="text" name="i_policy_no" class="form-control backrnd" >
  </div>
  <div class="col-lg-6 col-xs-12 form-group">
    <label for="complaint">Sum Assured<span style="color: red;font-size: 13px;">*</span></label>
  </div>
  <div class="col-lg-6 col-xs-12 form-group">
    <input type="text" class="form-control backrnd" name="i_sum_assured" >
  </div>
  <div class="col-lg-6 col-xs-12 form-group">
    <label for="complaint">Claim Amount: <!--<span style="color: red;font-size: 13px;">*</span>--></label>
  </div>
  <div class="col-lg-6 col-xs-12 form-group">
    <input type="text" class="form-control backrnd" name="i_claim_amount">
  </div>
  <div class="col-lg-6 col-xs-12 form-group">
    <label for="complaint">Folio No: <!--<span style="color: red;font-size: 13px;">*</span>--></label>
  </div>
  <div class="col-lg-6 col-xs-12 form-group">
    <input type="text" class="form-control backrnd" name="i_folio_no">
  </div>
  <div class="col-lg-6 col-xs-12 form-group">
    <label for="complaint">No of Shares: <!--<span style="color: red;font-size: 13px;">*</span>--></label>
  </div>
  <div class="col-lg-6 col-xs-12 form-group">
    <input type="text" class="form-control backrnd" name="i_no_of_shares">
  </div>
  <div class="col-xs-12">
    <button type="button" class="btn btn-primary float-right" onClick="goto_last();">Next</button>
    
                <button type="button" class="btn btn-primary float-right" onClick="goto_insurence();" style="margin-right: 8px;">Previous</button>
  </div>
            </div>
          </div>
          <div class="clearfix hidden-lg"></div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar.php');?>
        </div>
      </div>
      
      <div id="specialized_companies" class="wizard-step">
        <div class="form-group">
          <div class="col-lg-8 padding-top-10 text-center">
            <div class="col-xs-12">
              <h2 style="text-align: left;"> AMC/Mutual Funds/Modarabah/Leasing/Investment Banking/REIT </h2>
            </div>
            <div class="form-group" id="specialized_companies_inner">
              <?php 
			$sql_scd_tyes = "Select * from sdms_scd Where 1 GROUP BY type";
			$res_scd_tyes = db_query($sql_scd_tyes);
			while( $row_scd_tyes = db_fetch_array($res_scd_tyes)){
            ?>
              <div class="col-lg-3 col-xs-12">
                <label>
                  <input type="radio" name="m-system" value="<?php echo $row_scd_tyes['type']; ?>" onClick="show_wizard_scd(this.value);" />
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
          <?php include('../includes/right_side_bar.php');?>
        </div>
      </div>
      <div id="specialized_companies_type_inner" class="wizard-step">
        <div class="form-group">
          <div class="col-lg-8 padding-top-10 text-center">
            <div class="col-xs-12">
              <h2 style="text-align: left;"> AMC/Mutual Funds/Modarabah/Leasing/Investment Banking/REIT  -> <span id="sc_specific_type"></span></h2>
            </div>
            <div class="form-group" id="specialized_companies_inner">
            <div id="scd_list"></div>
  <div id="reit_scheme"></div>
  <div id="modaraba_fund"></div>
  <div id="mutual_fund"></div>
  <div id="pension_fund"></div>
  <div class="col-lg-6 col-xs-12 form-group">
    <label for="complaint">Registration No./Folio No.<!--<span style="color: red;font-size: 13px;">*</span> --></label>
  </div>
  <div class="col-lg-6 col-xs-12 form-group">
    <input type="text" name="scd_folio_no" class="form-control backrnd" >
  </div>
  <div class="col-lg-6 col-xs-12 form-group">
    <label for="complaint">CDC A/C No:<!--<span style="color: red;font-size: 13px;">*</span>--></label>
  </div>
  <div class="col-lg-6 col-xs-12 form-group">
    <input type="text" class="form-control backrnd" name="scd_cdc_ac_no" >
  </div>
  <div class="col-lg-6 col-xs-12 form-group">
    <label for="complaint">No. of Certificates: <!--<span style="color: red;font-size: 13px;">*</span>--></label>
  </div>
  <div class="col-lg-6 col-xs-12 form-group">
    <input type="text" class="form-control backrnd" name="scd_no_of_units">
  </div>
  <div class="col-xs-12">
    <button type="button" class="btn btn-primary float-right" onClick="goto_last();">Next</button>
    <button type="button" class="btn btn-primary float-right" onClick="goto_specialized_companies();" style="margin-right: 8px;">Previous</button>
  </div>  
            </div>
          </div>
          <div class="clearfix hidden-lg"></div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar.php');?>
        </div>
      </div>
      
      <div id="e_services" class="wizard-step">
        <div class="col-lg-8 padding-top-10 text-center">
          <div class="col-xs-12">
            <h2 style="text-align:left;">e-Services</h2>
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
            <div class="col-lg-6 col-xs-12 form-group" id="e_cro_title">
            </div>
            <div style="clear:both;"></div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Process Name:<span style="color: red;font-size: 13px;">*</span></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <select name="e_company_title"  class="form-control backrnd">
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
              <label for="complaint">Nature of Complaint:</label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <select name="e_nature_of_omplaint"  class="form-control backrnd">
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
              <input type="text" class="form-control backrnd" name="e_user_id">
            </div>
            <div class="col-xs-12">
              <button type="button" class="btn btn-primary float-right" onClick="goto_last();">Next</button>
              <button type="button" class="btn btn-primary float-right" onClick="goto_dept();" style="margin-right: 8px;">Previous</button>
            </div>
          </div>
        </div>
        <div class="clearfix hidden-lg"></div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar.php');?>
        </div>
      </div>
      <div id="company_registration" class="wizard-step">
        <div class="col-lg-8 padding-top-10 text-center">
          <div class="col-xs-12">
            <h2 style="text-align: left;">Company Registration/Compliance</h2>
          </div>
          <div class="form-group" id="company_registration_inner">
            <div style="clear:both"></div>
            <div class="col-lg-4 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Please state your status:</label>
            </div>
            <div class="col-lg-8 col-xs-12 form-group has-feedback">
              <input type="radio" size="50" name="cr_status" id="cr_status" value="Member" />
              Member
              &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
              <input type="radio" size="50" name="cr_status" id="cr_status" value="Shareholder" />
              Shareholder 
              &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
              <input type="radio" size="50" name="cr_status" id="cr_status" value="Non Shareholder" />
              Non Shareholder </div>
            <div style="clear:both"></div>
            
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Registered Company Name:<span style="color: red;font-size: 13px;">*</span> </label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
            <input type="text" name="cr_company_title" id="input_product_r" autocomplete="off" value=""  class="form-control backrnd"/>
            </div>
            
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">CRO: <!--<span style="color: red;font-size: 13px;">*</span>--></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group" id="r_cro_title">
            </div>
            <div style="clear:both;"></div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Unregistered Entity:<span style="color: red;font-size: 13px;">*</span> </label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
            <select name="unregistered_entity" class="form-control backrnd">
            <option value="">--Select Unregistered Entity--</option>
            <option value="Under Incorporation">Under Incorporation</option>
            <option value="Name Reservation">Name Reservation</option>
            <option value="Other">Other</option>   
            </select>
            </div>
            
           
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Folio No:<span style="color: red;font-size: 13px;">*</span></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" class="form-control backrnd" name="cr_folio_no">
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">No of Shares: <!--<span style="color: red;font-size: 13px;">*</span>--></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" class="form-control backrnd" name="cr_no_of_shares">
            </div>
            
            <div style="clear:both"></div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Process Name:<span style="color: red;font-size: 13px;">*</span> </label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <select name="cr_process_name"  class="form-control backrnd">
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
              <button type="button" class="btn btn-primary float-right" onClick="goto_last();">Next</button>
              <button type="button" class="btn btn-primary float-right" onClick="goto_dept();" style="margin-right: 8px;">Previous</button>
            </div>
          </div>
        </div>
        <div class="clearfix hidden-lg"></div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar.php');?>
        </div>
      </div>
      <div id="company_supervision" class="wizard-step">
        <div class="col-lg-8 padding-top-10 text-center">
          <div class="col-xs-12">
            <h2 style="text-align: left;">Supervision of Listed Companies</h2>
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
              <input type="text" class="form-control backrnd" name="cs_cdc_ac_no">
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <label for="complaint">Folio No: <!--<span style="color: red;font-size: 13px;">*</span>--></label>
            </div>
            <div class="col-lg-6 col-xs-12 form-group">
              <input type="text" class="form-control backrnd" name="cs_folio_no">
            </div>
            <div class="col-xs-12">
              <button type="button" class="btn btn-primary float-right" onClick="goto_last();">Next</button>
              <button type="button" class="btn btn-primary float-right" onClick="goto_dept();" style="margin-right: 8px;">Previous</button>
            </div>
          </div>
        </div>
        <div class="clearfix hidden-lg"></div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar.php');?>
        </div>
      </div>
      <div id="finish" class="wizard-step">
        <div class="col-lg-8 padding-top-10" style="color: black;">
          <div class="col-xs-12">
            <h2 style="text-align: left;">Complaint Information </h2>
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
            
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Attachment:</label>&nbsp;(Maximum Size 2mb in Pdf OR JPG)
            </div>
            <div class="col-lg-6 col-xs-12 form-group has-feedback">
              <input type="file" name="evidence_1" class="form-control">
              
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Attachment:</label>&nbsp;(Maximum Size 2mb in Pdf OR JPG)
            </div>
            <div class="col-lg-6 col-xs-12 form-group has-feedback">
              <input type="file" name="evidence_2" class="form-control">
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Attachment:</label>&nbsp;(Maximum Size 2mb in Pdf OR JPG)
            </div>
            <div class="col-lg-6 col-xs-12 form-group has-feedback">
              <input type="file" name="evidence_3" class="form-control">
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Attachment:</label>&nbsp;(Maximum Size 2mb in Pdf OR JPG)
            </div>
            <div class="col-lg-6 col-xs-12 form-group has-feedback">
              <input type="file" name="evidence_4" class="form-control">
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Attachment:</label>&nbsp;(Maximum Size 2mb in Pdf OR JPG)
            </div>
            <div class="col-lg-6 col-xs-12 form-group has-feedback">
              <input type="file" name="evidence_5" class="form-control">
            </div>
            <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
              <label for="complaint">Attachment:</label>&nbsp;(Maximum Size 2mb in Pdf OR JPG)
            </div>
            <div class="col-lg-6 col-xs-12 form-group has-feedback">
              <input type="file" name="evidence_6" class="form-control">
            </div>
          </div>
          <div class="col-xs-12">
            <button type="submit" class="btn btn-primary float-right" onclick="return confirm('Are you sure you wish to submit your complaint?')">Submit</button>
          </div>
          <div class="clearfix hidden-lg"></div>
        </div>
        <div class="hidden-xs">
          <?php include('../includes/right_side_bar.php');?>
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
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>

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
 close: function( event, ui ) {  update_r_cro(this.value); }
});

$( "#input_product_s" ).autocomplete({
source: "search_company_name.php",
minLength: 1
});

});

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
}}


function show_wizard_cm(b_type){
$.ajax({
url:"get_cm_brokers.php",
data: "b_type="+b_type,
success: function(msg){	
$("#brokers_list").html(msg);
}
});	
$("#insurance_specific_type").html(b_type);
$('#li_insurance_type_inner').click();
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

function show_wizard_insurence(b_type){	
$.ajax({
url:"get_insurance_brokers.php",
data: "b_type="+b_type,
success: function(msg){	
$("#insurance_list").html(msg);
}
});	
$("#insurance_specific_type").html(b_type);
$('#li_insurance_type_inner').click();
}
function show_insurance_agent_list(parent){
	$.ajax({
	url:"get_insurance_brokers_agents.php",
	data: "parent="+parent,
	success: function(msg){
	$("#insurance_agent").html(msg);
}
});	}
function goto_insurence(){ $('#li_insurance').click(); }

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
	/*var sms_code = $('#sms_code').val();
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
	});*/
	goto_dept();
}
	
function goto_last(){
$('#li_finish').click();}
function goto_dept(){
$('#li_deprts').click();}	
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
<?php  /*?>	  
           	<form id="ticketForm" method="post" action="open.php" enctype="multipart/form-data">
				<?php csrf_token(); ?>
				<input type="hidden" name="a" value="open">  
				<div class="heading"><p>Personal Information</p></div>
				<div class="r-l-styl">
				<div class="left-form-sty">
				<label class="label-name">Full Name</label><br />
				<?php
				if($thisclient && $thisclient->isValid()) {
				echo $thisclient->getName();
				} else { ?>
				<input class="field"  name="name" placeholder="First and last name" required tabindex="1" type="text" value="<?php echo $info['name']; ?>" title="Enter your full name"> 
				<span style="color:#F00;">*&nbsp;<?php echo $errors['name']; ?></span>
				<?php } ?>
				</div>
				<div class="right-form-styl" style="margin-right:0px;">
				<label>Email Address</label><br />
				<?php
				if($thisclient && $thisclient->isValid()) { 
				echo $thisclient->getEmail();
				} else { ?> 
				<input class="field" name="email" placeholder="example@domain.com" type="email" value="<?php echo $info['email']; ?>" tabindex="2" title="Enter you valid email address, you will recieve the UCN/PIN code along with the complaint details on this email address"  />
				&nbsp;<?php echo $errors['email']; ?>
				<?php } ?>
				</div>
				</div>				
				<div class="r-l-styl">
				<div class="left-form-sty">
				<label class="label-name">CNIC Number</label><br />
				<input class="field" id="nic" name="nic" required type="text" tabindex="3" title="Enter your CNIC number in the prescribed format">
				<span style="color:#F00;">*&nbsp;<?php echo $errors['nic']; ?></span>
				</div>
				<div class="right-form-styl">
				<label>Mobile Number</label><br />
				<input type="text" id="form-textfield" maxlength="11" name="phone"  value="<?php echo $info['phone']; ?>" tabindex="4" title="Enter your mobile number in the prescribed format"  class="field">
				</div>
				</div>				
				<div class="r-l-styl">
				<div class="left-form-sty">
				<label class="label-name">Gender</label><br />
				<select type="text" placeholder="gender" class="field">
				<option value="" selected disabled="disabled" >&mdash; Select Gender &mdash;</option>
				<option value="Male"> Male</option>
				<option value="Female"> Female</option>
				<option value="other"> Other</option>
				</select>
				
				</div>
				<div class="right-form-styl">
				<label>Address</label>
				<br />
				<textarea name="applicant_address" cols="23" rows="4" title="Enter your complete/full address. This address can be used for future correspondence regarding the complaint" style="width: 249px; height: 90px;"></textarea> 
				</div>
				</div>				
				<div class="r-l-styl">
				<div class="left-form-sty">
				<label class="label-name">Agencies</label><br />
				<select id="jumpMenu" name="district" onChange="get_tehsils(this.value);" class="field">
				<?php
				if($districts=Districts::getDistricts()) {
				foreach($districts as $id =>$name) {
				echo sprintf('<option value="%d" %s>%s</option>',$id, ($info['district']==$id)?'selected="selected"':'',$name);
				}
				}
				?>
				</select>
				
				</div>
				
				</div>				
				<div class="r-l-styl">
				<div class="left-form-sty" id="show_sub_tehsils">
				
				</div>
				<div class="right-form-styl" id="show_agency_tehsils">
				
				
				</div>
				</div>
				<div class="line">
				</div>
				<div class="heading">
				
				<p>  Complaint Information </p>
				
				
				</div><!--heading div ends here-->
				<div class="r-l-styl">
				<div class="left-form-sty">
				<label class="label-name">Type Of Complaint</label><br />
				<select name="topicId" tabindex="5" title="Select complaint type" class="field">
				<option value="" selected="selected">&mdash; Select a Complaint Type &mdash;</option>
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
				<?php echo $errors['topicId']; ?>
				</div>
				<div class="right-form-styl">
				<label class="label-name">Subject / Title of the Complaint</label>
				<br />
				<input name="subject" placeholder="subject" required tabindex="10" type="text" value="<?php echo $info['subject']; ?>" title="Enter a brief Subject / Title of the complaint that best describes your complaint" class="field">
				<span style="color:#F00;">*&nbsp;<?php echo $errors['subject']; ?></span>
				</div><!--textfield-name div end here-->
				
				</div>
				<div class="heading">
				<p style="margin-bottom:10px;">Complaint Detail  </p>
				
				<textarea  name="message" tabindex="11" title="Enter as much details as possible so we can best assist you." cols="76" rows="8" style="width: 772px; height: 116px;"><?php echo $info['message']; ?></textarea>
				</div>
				<div class="r-l-styl">
				<div class="left-form-sty">
				
				
				<?php if(($cfg->allowOnlineAttachments() && !$cfg->allowAttachmentsOnlogin())
				|| ($cfg->allowAttachmentsOnlogin() && ($thisclient && $thisclient->isValid()))){ ?>
				<label class="label-name">Attachments / Evidence / Others: <br />(e.g Word,JPG,PNG,GIF &nbsp;)</label><br />
				
				<input id="attachments" name="attachments[]"  style=" background: none repeat scroll 0 0 #fff;
				border: 1px solid #ccc;
				height: 27px;
				width: 300px; " placeholder="choose a file" tabindex="12" type="file" title="Attach document/evidence. you can upload multiple file using this option" class="field"><br /> <?php echo $errors['attachments'];  } ?>
				<!--<input type="file"  class="field"  />-->
				</div>
				<div class="right-form-styl">
				<?php
				if($cfg && $cfg->isCaptchaEnabled() && (!$thisclient || !$thisclient->isValid())) {
				if($_POST && $errors && !$errors['captcha'])
				$errors['captcha']='Please re-enter the text again';
				?>
				<label><br />Captacha</label><br />
				<span class="captcha"><img src="captcha.php" border="0" align="left"></span>
				<input id="captcha" type="text" name="captcha" size="6" required  tabindex="14" title="Enter the text as shown in the figure" class="field">
				<?php }?>
				<!--<img src="images/captua.png" height="32" width="88" /><br />
				<input type="text" placeholder="name" class="field"  />-->
				</div>
				</div>
				<div class="r-l-styl">
				<div class="left-form-sty">
				<label class="label-name"></label><br />
				</div>
				<div class="right-form-styl">
				<label class="label-name"></label><br />
				<input id="submit" type="image" title="Click here to register the complaint" tabindex="15" src="images/submit.png"  class="sub_button" name="submit">
				
				<input type="reset" class="clear-button" title="click here if you want to reset all the field of this form" tabindex="16" name="reset">
				</div>
				</div>
				</form>
				</td>
				<td width="19%">&nbsp;</td>
				</tr>
				<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				</tr>
				</table>
				</td>
				</tr>
			</table>
			</td>
			</tr>
             
         
<!-------------------------------------------------------------------------------><!--form-persnl div end here-->
        <?php */?>
