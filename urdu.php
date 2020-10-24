<?php include('includes/header.php'); ?>
<div class="container" style="background-color: white;">
    <form id="wizard1" class="wizard comp_form">
    <div class="wizard-header" style="display:none;">
                <ul class="nav nav-tabs">
    <li role="presentation" class="wizard-step-indicator" ><a id="li_profile" href="#profile">Profile</a></li>
    <li role="presentation" class="wizard-step-indicator" ><a id="li_verification" href="#verification">Verification</a></li>
    <li role="presentation" class="wizard-step-indicator" ><a id="li_deprts" href="#deprts">Departments</a></li>
    <li role="presentation" class="wizard-step-indicator" ><a id="li_capital_martket" href="#capital_martket">Capital Market</a></li>
    <li role="presentation" class="wizard-step-indicator" ><a id="li_insurance" href="#insurance">Insurance</a></li>
    <li role="presentation" class="wizard-step-indicator" ><a id="li_specialized_companies" href="#specialized_companies">specialized_companies</a></li>
    <li role="presentation" class="wizard-step-indicator" ><a id="li_finish" href="#finish">Finish</a></li>
                </ul>
            </div>
    <?php include('includes/sidebar_urdu.php') ?>
    <div class="col-lg-8 padding-top-10">
        <div class="wizard-content">
                <div id="profile" class="wizard-step">
                <div class="col-xs-12">
                <h3 style="color: #202020;font-weight: bold;float: right;">شکایت کنندہ کی ذاتی تفصیلات</h3>
                </div>
                <div class="form-group">
                <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
                <input type="text" name="name" class="form-control backrnd">
                </div>
                <div class="col-lg-4 col-xs-12 form-group">
                <label for="complaint" class="float-right" style="padding-top: 20px;"><span class="star">*</span>شکایت کنندہ کا نام </label>
                </div>
                <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
                <input type="text" class="form-control backrnd" name="phone">
                </div>
                <div class="col-lg-4 col-xs-12 form-group">
                <label for="complaint" class="float-right"><span class="star">*</span>موبائل فون کانمبر</label>
                </div>
                <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
                <input type="text" class="form-control backrnd" name="nic">
                </div>
                <div class="col-lg-4 col-xs-12 form-group">
                <label for="complaint" class="float-right"><span class="star">*</span>کمپیوٹرائزڈ قومی شناختی کارڈ </label>
                </div>
                <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
                <input type="email" class="form-control backrnd" name="email">
                </div>
                <div class="col-lg-4 col-xs-12 form-group">
                <label for="complaint" class="float-right"><span class="star">*</span>خط</label>
                </div>
                <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
                <input type="text" class="form-control backrnd" name="alternate_email">
                </div>
                <div class="col-lg-4 col-xs-12 form-group">
                <label for="complaint" class="float-right"> متبادل خط</label>
                </div>
                <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
                <input type="text" class="form-control backrnd" name="landline_number">
                </div>
                <div class="col-lg-4 col-xs-12 form-group">
                <label for="complaint" class="float-right">لینڈلائن نمبر</label>
                </div>
                <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group has-feedback">
                <select class="form-control backrnd" name="district">
                <option>Select Your Contry</option>
                <option>Pakistan</option>
                <option>india</option>
                <option>Afghan</option>
                </select>
                </div>
                <div class="col-lg-4 col-xs-12 padding-top-10 form-group">
                <label for="complaint" class="float-right"> <span class="star">*</span>ملک</label>
                </div>
                <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group has-feedback">
                <select class="form-control backrnd" name="district">
                <option>Select Your Province</option>
                <option>kp</option>
                <option>Panjab</option>
                <option>Sind</option>
                <option>Balochistan</option>
                </select>
                </div>
                <div class="col-lg-4 col-xs-12 padding-top-10 form-group">
                <label for="complaint" class="float-right">صوبہ</label>
                </div>
                <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group has-feedback">
                <select class="form-control backrnd" name="district">
                <option>Select Your City</option>
                <option>Peshawer</option>
                <option>Kohat</option>
                <option>Karak</option>
                </select>
                </div>
                <div class="col-lg-4 col-xs-12 padding-top-10 form-group">
                <label for="complaint" class="float-right">شہر</label>
                </div>
                <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group has-feedback">
                <textarea name="applicant_address" class="form-control"></textarea>
                </div>
                <div class="col-lg-4 col-xs-12 padding-top-10 form-group">
                <label for="complaint" class="float-right">ڈاک کا پتا</label>
                </div>
                </div>
                <div class="col-xs-12">
                <button type="button" class="wizard-next btn btn-primary" >اگلا قدم</button>
                </div>
                </div>
            <!-- sms verifaction -->                
                <div id="verification" class="wizard-step">
                <div class="form-group">
                <div class="col-xs-12">
                <h3 style="color: #202020; float: right;">ایس ایم ایس کی توثیق</h3>
                </div>
                <div class="col-xs-12 text-center">
                <h4 style="color: red;font-weight: bold;"> ہندسوں  کا کوڈ درج کریں </h4>
                </div>
                <div class="col-xs-12 text-center">
                <input type="text" name="sms_vrify" class="form-control" placeholder="Enter Your Code" style="width: 40%; margin: 0 auto;">
                <span class="fa fa-info-circle errspan"></span>
                <img class="img-responsive" src="assets/images/sms.png" height="250px" width="60%" style="margin:0 auto;height: 165px;">
                </div>   
                <div class="col-xs-offset-3 col-xs-6">
                <button type="button" class="wizard-next btn btn-primary float-right"  style="width: 100%;margin: 0 auto;margin-top: 40px;padding-top: 10px;">اگلا قدم</button>
                </div>
                </div>
                </div>   
                    <!-- service desk management system -->         
                <div id="deprts" class="wizard-step">
                <div class="form-group">
                <div class="col-xs-12">
                <h3 style="color: #202020;font-weight: bold;float: right;">سروس ڈیسک مینجمنٹ سسٹم</h3>
                </div>
                <div class="col-lg-5 col-xs-11">
                <label>
                <input type="radio" name="m-system" value="2" onClick="goto_types(this.value);" />
                <img class="img-responsive" src="assets/images/mnge/security.png">
                </label>
                </div>
                <div class="col-lg-1 col-xs-1 padding-top-5">
                <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal"></i> </span>
                </div>
                
                <div class="col-lg-5 col-xs-11 padding-top-10">
                <label>
                <input type="radio" name="m-system" value="3" onClick="goto_types(this.value);" />
                <img class="img-responsive" src="assets/images/mnge/insurance.jpg">
                </label>
                </div>
                <div class="col-lg-1 col-xs-1 padding-top-20">
                <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal"></i> </span>
                </div>
                
                <div class="col-lg-5 col-xs-11">
                <label>
                <input type="radio" name="m-system" value="4"  onClick="goto_types(this.value);" />
                <img class="img-responsive" src="assets/images/mnge/specailzed.jpg">
                </label>
                </div>
                <div class="col-lg-1 col-xs-1 padding-top-5">
                <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal"></i> </span>
                </div>
                
                
                <div class="col-lg-5 col-xs-11 padding-top-10">
                <label>
                <input type="radio" name="m-system" value="small" />
                <img class="img-responsive" src="assets/images/mnge/leasing_labal.jpg">
                </label>
                </div>
                <div class="col-lg-1 col-xs-1 padding-top-20">
                <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal"></i> </span>
                </div>
                <div class="col-lg-5 col-xs-11 padding-top-10">
                <label>
                <input type="radio" name="m-system" value="small" />
                <img class="img-responsive" src="assets/images/mnge/e-services.jpg">
                </label>
                </div>
                <div class="col-lg-1 col-xs-1 padding-top-20">
                <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal"></i> </span>
                </div>
                <div class="col-lg-5 col-xs-11 padding-top-10">
                <label>
                <input type="radio" name="m-system" value="small" />
                <img class="img-responsive" src="assets/images/mnge/company_registration.jpg">
                </label>
                </div>
                <div class="col-lg-1 col-xs-1 padding-top-20">
                <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal"></i> </span>
                </div>
                <div class="col-lg-5 col-xs-11 padding-top-10">
                <label>
                <input type="radio" name="m-system" value="small" />
                <img class="img-responsive" src="assets/images/mnge/company_supervisoin.jpg">
                </label>
                </div>
                <div class="col-lg-1 col-xs-1 padding-top-20">
                <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal"></i> </span>
                </div>
                </div>
                </div>
                <div id="capital_martket" class="wizard-step">
                    <div class="col-xs-12">
                    <h3 style="color: black;float: right;"> سرمایہ مارکیٹ </h3>
                    </div>
                    <div class="form-group" id="capital_martket_inner">
                    <div class="col-lg-3 col-xs-12">
                    <label>
                    <input type="radio" name="m-system" value="Commodities Broker" onClick="show_wizard_cm(this.value);" />
                    <img src="assets/images/managment.jpg">
                    </label>
                    <p>اشیاء استعمال بروکر</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                    <label>
                    <input type="radio" name="m-system" value="Commodities Broker" />
                    <img src="assets/images/debt.png">
                    </label>
                    <p>قرض سیکورٹی ٹرسٹی</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                    <label>
                    <input type="radio" name="m-system" value="Commodities Broker" />
                    <img src="assets/images/Broker.png">
                    </label>
                    <p>سیکورٹیز بروکر</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                    <label>
                    <input type="radio" name="m-system" value="Commodities Broker" />
                    <img src="assets/images/Registrar.png">
                    </label>
                    <p>سیکنڈ اور رجسٹرار</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                    <label>
                    <input type="radio" name="m-system" value="Commodities Broker" />
                    <img src="assets/images/Commodities.png">
                    </label>
                    <p>اشیاء استعمال</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                    <label>
                    <input type="radio" name="m-system" value="Commodities Broker" />
                    <img src="assets/images/PSX.png">
                    </label>
                    <p>PSX</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                    <label>
                    <input type="radio" name="m-system" value="Commodities Broker" />
                    <img src="assets/images/cdc.jpg">
                    </label>
                    <p>CDC</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                    <label>
                    <input type="radio" name="m-system" value="Commodities Broker" />
                    <img src="assets/images/NCCPL.png">
                    </label>
                    <p>رجسٹرار</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                    <label>
                    <input type="radio" name="m-system" value="Commodities Broker" />
                    <img src="assets/images/pmex.jpg">
                    </label>
                    <p>PMEX</p>
                    </div>
                    </div>
                   
                </div>
                <div id="insurance" class="wizard-step">
                <div class="form-group">
                  <div class="col-xs-12">
                    <h3 style="color: black;float: right;"> انشورنس </h3>
                  </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/managment.jpg">
                      <p>ایسیٹ مینجمنٹ</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/finance.jpg">
                      <p>ہاؤسنگ فنانس</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/advisory.jpg">
                      <p>سرمایہ کاری ایڈوائزری</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/invesment_finance.jpg">
                      <p>سرمایہ کاری خزانہ</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/leasing.jpg">
                      <p>لیزنگ کمپنی</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/mdaraba_managment.jpg">
                      <p>مضاربہ کے انتظام</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/non_bank.jpg">
                      <p>غیر بینک مائیکرو فنانس</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/private_equaity.jpg">
                      <p>نجی ایکوئٹی اور وینچر کیپیٹل کی</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/reitmanager.jpg">
                      <p>REIT مینجمنٹ کمپنی</p>
                    </div>
                    <div class="col-xs-12">
                        <button type="button" class="wizard-next btn btn-primary">اگلا قدم</button>
                        <button type="button" class="wizard-prev btn btn-primary">پچھلا</button>
                    </div>
                </div>
                </div>
                <div id="specialized_companies" class="wizard-step">
                <div class="form-group">
                  <div class="col-xs-12">
                    <h3 style="float: right;color: black;"> خصوصی کمپنیوں </h3>
                  </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/managment.jpg">
                      <p>ایسیٹ مینجمنٹ</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/finance.jpg">
                      <p>ہاؤسنگ فنانس</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/advisory.jpg">
                      <p>سرمایہ کاری ایڈوائزری</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/invesment_finance.jpg">
                      <p>سرمایہ کاری خزانہ</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/leasing.jpg">
                      <p>لیزنگ کمپنی</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/mdaraba_managment.jpg">
                      <p>مضاربہ کے انتظام</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/non_bank.jpg">
                      <p>غیر بینک مائیکرو فنانس</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/private_equaity.jpg">
                      <p>نجی ایکوئٹی اور وینچر کیپیٹل کی</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/reitmanager.jpg">
                      <p>REIT مینجمنٹ کمپنی</p>
                    </div>
                    <div class="col-xs-12">
                        <button type="button" class="wizard-next btn btn-primary">اگلا قدم</button>
                        <button type="button" class="wizard-prev btn btn-primary">پچھلا</button>
                    </div>
                </div>
                </div>
                <div id="finish" class="wizard-step">
                        <div class="col-xs-12">
                        <h3 style="float: right;color: black;"> فارم بھیجئے </h3>
                        </div>
                        <div class="form-group">
                        <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
                        <input type="text" name="name" class="form-control backrnd" required>
                        </div>
                        <div class="col-lg-4 col-xs-12 form-group">
                        <label for="complaint" class="float-right"><span class="star">*</span>سرخی
                        </label>
                        </div>
                        <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group has-feedback">
                        <textarea name="applicant_address" class="form-control" rows="5"></textarea>
                        </div>
                        <div class="col-lg-4 col-xs-12 padding-top-10 form-group">
                        <label for="complaint" class="float-right">تفصیل</label>
                        </div>
                        <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group has-feedback">
                        <input type="file" name="evid1" class="form-control">
                        </div>
                        <div class="col-lg-4 col-xs-12 padding-top-10 form-group">
                        <label for="complaint" class="float-right">ثبوت 1 :</label>
                        </div>
                        <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group has-feedback">
                        <input type="file" name="evid1" class="form-control">
                        </div>
                        <div class="col-lg-4 col-xs-12 padding-top-10 form-group">
                        <label for="complaint" class="float-right">ثبوت 2 :</label>
                        </div>
                        <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group has-feedback">
                        <input type="file" name="evid1" class="form-control">
                        </div>
                        <div class="col-lg-4 col-xs-12 padding-top-10 form-group">
                        <label for="complaint" class="float-right">ثبوت 3 :</label>
                        </div>
                        
                         <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group has-feedback">
                        <input type="file" name="evid1" class="form-control">
                        </div>
                        <div class="col-lg-4 col-xs-12 padding-top-10 form-group">
                        <label for="complaint" class="float-right">ثبوت 4 :</label>
                        </div>
                        <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group has-feedback">
                        <input type="file" name="evid1" class="form-control">
                        </div>
                        <div class="col-lg-4 col-xs-12 padding-top-10 form-group">
                        <label for="complaint" class="float-right">ثبوت 5 :</label>
                        </div>
                        <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group has-feedback">
                        <input type="file" name="evid1" class="form-control">
                        </div>
                        <div class="col-lg-4 col-xs-12 padding-top-10 form-group">
                        <label for="complaint" class="float-right">ثبوت 6 :</label>
                        </div>
                        </div>
                        <div class="col-xs-12">
                        <button type="button" class="wizard-finish btn btn-primary">ختم</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
            </div>
            <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content" style="border: 14px solid #929292">
        <div class="modal-header">
          <button type="button" class="close cus_style" data-dismiss="modal">&times;</button>
          <h2 class="modal-title text-center" style="color: #a5b287;"> SECURITIES MARKETS DIVISION </h2>
        </div>
        <div class="modal-body text-center" style="color: #a5b287;font-size: 12px;">
          <p style="line-height: 18px;">
            If you wish to lode a complaint related to Capital Markets, its intermediaries(such as CDC,NCCPL,PSX,PMEX etc.) <br>
            OR <br>Matters related to trading of shares of listed Companies <br> OR <br> PSX Brokers/Agents,PMEX Broker,Book Runner,Share Registrar etc.<br> OR <br> Acquirer/Company in case of takeouers <br> OR <br> 
      Pertaining to Beneficial Ownership Filing <br> OR <br> Insider Trading, Marker Rumors/Manipultion/Abuses etc. <br> OR <br> Unauthorized trading, Non Receipt of Account Trading Statements etc.
          </p>
          
        </div>
      </div>
    </div>
  </div>
  <!-- modal section end -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.14.0/jquery.validate.min.js"></script>
    <script src="assets/js/jquery.simplewizard.js"></script>
    <script>
    $(function () {
            $("#wizard1").simpleWizard({
                cssClassStepActive: "active",
                cssClassStepDone: "done",
                onFinish: function() {
                    //alert("Wizard finished")
                }
            });
        });
			
	function goto_types(dept_id){
	if(dept_id==2){
	$('#li_capital_martket').click();
	}else if(dept_id==3){
	$('#li_insurance').click();
	}else if(dept_id==4){
	$('#li_specialized_companies').click();
	}
	}

	function show_wizard_cm(b_type){
	/*$.ajax({
	url:"get_cm_brokers.php",
	data: "b_type="+b_type,
	success: function(msg){	
	$("#brokers_list").html(msg);
	$('#popup_action_s_m').click();
	}
	});*/	
	$("#capital_martket_inner").html($('#capital_martket_form').html());
	
	}	
    </script>
    <div id="capital_martket_form" style="display:none;">
        <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group has-feedback">
        <select class="form-control backrnd" name="district">
        <option>Select Your Province</option>
        <option>kp</option>
        <option>Panjab</option>
        <option>Sind</option>
        <option>Balochistan</option>
        </select>
        </div>
        <div class="col-lg-4 col-xs-12 padding-top-10 form-group">
            <label for="complaint" class="float-right">صوبہ</label>
        </div>
        <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
        <input type="text" name="name" class="form-control backrnd" required>
        </div>
        <div class="col-lg-4 col-xs-12 form-group">
        <label for="complaint" class="float-right"><span class="star">*</span>صفحہ نمبر </label>
        </div>
         <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
        <input type="text" class="form-control backrnd" name="phone" required>
        </div>
        <div class="col-lg-4 col-xs-12 form-group">
        <label for="complaint" class="float-right"> <span class="star">*</span>موبائل فون کانمبر</label>
        </div>
        <div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group">
        <input type="text" class="form-control backrnd" name="nic">
        </div>
        <div class="col-lg-4 col-xs-12 form-group">
        <label for="complaint" class="float-right"><span class="star">*</span>کمپیوٹرائزڈ قومی شناختی کارڈ </label>
        </div>
        
                
        <div class="col-xs-12">
        <button type="button" class="wizard-next btn btn-primary">اگلا قدم</button>
        </div>
    </div>
    
    
<!-- 
<?php //include('includes/footer.php') ?>
 -->