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
        <div class="wizard-content">
            
                <div id="profile" class="wizard-step">
                <div class="col-lg-8 padding-top-10">
                <div class="col-xs-12">
                <h3 style="color: #202020;font-weight: bold;">Personal Details of Complainant</h3>
                </div>
                <div class="form-group">
                <div class="col-lg-6 col-xs-12 form-group">
                <label for="complaint">Complainant Name <span style="color: red;font-size: 13px;">*</span> </label>
                </div>
                <div class="col-lg-6 col-xs-12 form-group">
                <input type="text" name="name" class="form-control backrnd">
                </div>
                <div class="col-lg-6 col-xs-12 form-group">
                <label for="complaint">Mobile Number <span style="color: red;font-size: 13px;">*</span></label>
                </div>
                <div class="col-lg-6 col-xs-12 form-group">
                <input type="text" class="form-control backrnd" name="phone">
                </div>
                <div class="col-lg-6 col-xs-12 form-group">
                <label for="complaint">CNIC <span style="color: red;font-size: 13px;">*</span></label>
                </div>
                <div class="col-lg-6 col-xs-12 form-group">
                <input type="text" class="form-control backrnd" name="nic">
                </div>
                <div class="col-lg-6 col-xs-12 form-group">
                <label for="complaint">Email <span style="color: red;font-size: 13px;">*</span></label>
                </div>
                <div class="col-lg-6 col-xs-12 form-group">
                <input type="email" class="form-control backrnd" name="email">
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
                <select class="form-control backrnd" name="district">
                <option>Select Your Contry</option>
                <option>Pakistan</option>
                <option>india</option>
                <option>Afghan</option>
                </select>
                </div>
                <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
                <label for="complaint">Province</label>
                </div>
                <div class="col-lg-6 col-xs-12 form-group has-feedback">
                <select class="form-control backrnd" name="district">
                <option>Select Your Province</option>
                <option>kp</option>
                <option>Panjab</option>
                <option>Sind</option>
                <option>Balochistan</option>
                </select>
                </div>
                <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
                <label for="complaint">City</label>
                </div>
                <div class="col-lg-6 col-xs-12 form-group has-feedback">
                <select class="form-control backrnd" name="district">
                <option>Select Your City</option>
                <option>Peshawer</option>
                <option>Kohat</option>
                <option>Karak</option>
                </select>
                </div>
                <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
                <label for="complaint">Postal Address</label>
                </div>
                <div class="col-lg-6 col-xs-12 form-group has-feedback">
                <textarea name="applicant_address" class="form-control"></textarea>
                </div>
                </div>
                <div class="col-xs-12">
                <button type="button" class="wizard-next btn btn-primary float-right" >Next</button>
                </div>
                <div class="clearfix"></div>
                </div>
                 <div class="col-lg-4 hidden-xs">
                <fieldset style="border: 3px dotted #03BEA3;">
                <legend style="color: #03BEA3;padding-left: 16px;width: 57%;border-bottom: none;">Complaint Process</legend>
                <div class="col-xs-12"> <img src="assets/images/step-1.jpg"></div>
                <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-2.jpg"></div>
                <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-3.jpg"></div>
                <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-4.jpg"></div>
                <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-5.jpg"></div>
                <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-6.jpg"></div>
                </fieldset>
                </div>
                </div>
                
                <div id="verification" class="wizard-step">
                <div class="col-lg-8 padding-top-10">
                <div class="form-group">
                <div class="col-xs-12">
                <h3 style="color: #202020;">SMS Verifcation</h3>
                </div>
                <div class="col-xs-12 text-center">
                <h4 style="color: red;font-weight: bold;">Enter the 4-digit code we just send to your phone</h4>
                </div>
                <div class="col-xs-12 text-center">
                <input type="text" name="sms_vrify" class="form-control" placeholder="Enter Your Code" style="width: 40%; margin: 0 auto;">
                <span class="fa fa-info-circle errspan"></span>
                <img class="img-responsive" src="assets/images/sms.png" height="250px" width="60%" style="margin:0 auto;height: 165px;">
                </div>   
                <div class="col-xs-offset-3 col-xs-6">
                <button type="button" class="wizard-next btn btn-primary float-right"  style="width: 100%;margin: 0 auto;margin-top: 40px;padding-top: 10px;">Next</button>
                </div>
                </div>
                <div class="clearfix"></div>
                </div>
                 <div class="col-lg-4 hidden-xs">
                <fieldset style="border: 3px dotted #03BEA3;">
                <legend style="color: #03BEA3;padding-left: 16px;width: 57%;border-bottom: none;">Complaint Process</legend>
                <div class="col-xs-12"> <img src="assets/images/step-1.jpg"></div>
                <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-2.jpg"></div>
                <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-3.jpg"></div>
                <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-4.jpg"></div>
                <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-5.jpg"></div>
                <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-6.jpg"></div>
                </fieldset>
                </div>
                </div>   
                             
                <div id="deprts" class="wizard-step">
                <div class="col-lg-8 padding-top-10">
                <div class="form-group">
                <div class="col-xs-12">
                <h3 style="color: #202020;font-weight: bold;">Service Desk Management System</h3>
                <div class="col-lg-5 col-xs-11">
                <label>
                <input type="radio" name="m-system" value="2" onClick="goto_types(this.value);" />
                <img class="img-responsive" src="assets/images/security.png">
                </label>
                </div>
                <div class="col-lg-1 col-xs-1 padding-top-5">
                <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal"></i> </span>
                </div>
                
                <div class="col-lg-5 col-xs-11 padding-top-10">
                <label>
                <input type="radio" name="m-system" value="3" onClick="goto_types(this.value);" />
                <img class="img-responsive" src="assets/images/insurance.jpg">
                </label>
                </div>
                <div class="col-lg-1 col-xs-1 padding-top-20">
                <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal"></i> </span>
                </div>
                
                <div class="col-lg-5 col-xs-11">
                <label>
                <input type="radio" name="m-system" value="4"  onClick="goto_types(this.value);" />
                <img class="img-responsive" src="assets/images/specailzed.jpg">
                </label>
                </div>
                <div class="col-lg-1 col-xs-1 padding-top-5">
                <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal"></i> </span>
                </div>
                
                
                <div class="col-lg-5 col-xs-11 padding-top-10">
                <label>
                <input type="radio" name="m-system" value="small" />
                <img class="img-responsive" src="assets/images/leasing_labal.jpg">
                </label>
                </div>
                <div class="col-lg-1 col-xs-1 padding-top-20">
                <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal"></i> </span>
                </div>
                <div class="col-lg-5 col-xs-11 padding-top-10">
                <label>
                <input type="radio" name="m-system" value="small" />
                <img class="img-responsive" src="assets/images/e-services.jpg">
                </label>
                </div>
                <div class="col-lg-1 col-xs-1 padding-top-20">
                <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal"></i> </span>
                </div>
                <div class="col-lg-5 col-xs-11 padding-top-10">
                <label>
                <input type="radio" name="m-system" value="small" />
                <img class="img-responsive" src="assets/images/company_registration.jpg">
                </label>
                </div>
                <div class="col-lg-1 col-xs-1 padding-top-20">
                <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal"></i> </span>
                </div>
                <div class="col-lg-5 col-xs-11 padding-top-10">
                <label>
                <input type="radio" name="m-system" value="small" />
                <img class="img-responsive" src="assets/images/company_supervisoin.jpg">
                </label>
                </div>
                <div class="col-lg-1 col-xs-1 padding-top-20">
                <span> <i class="fa fa-question-circle fa-1x" aria-hidden="true" data-toggle="modal" data-target="#myModal"></i> </span>
                </div>
                </div>
                </div>
                <div class="clearfix"></div>
                </div>
                 <div class="col-lg-4 hidden-xs">
                <fieldset style="border: 3px dotted #03BEA3;">
                <legend style="color: #03BEA3;padding-left: 16px;width: 57%;border-bottom: none;">Complaint Process</legend>
                <div class="col-xs-12"> <img src="assets/images/step-1.jpg"></div>
                <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-2.jpg"></div>
                <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-3.jpg"></div>
                <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-4.jpg"></div>
                <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-5.jpg"></div>
                <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-6.jpg"></div>
                </fieldset>
                </div>
                </div>
                <div id="capital_martket" class="wizard-step">
                	
                    <div class="col-lg-8 padding-top-10 text-center">
                    <div class="col-xs-12">
                    <h2 style="text-align: left;"> Capital Market </h2>
                    </div>
                    <div class="form-group" id="capital_martket_inner">
                    <div class="col-lg-3 col-xs-12">
                    <label>
                    <input type="radio" name="m-system" value="Commodities Broker" onClick="show_wizard_cm(this.value);" />
                    <img src="assets/images/managment.jpg">
                    </label>
                    <p>Commodities Broker</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                    <img src="assets/images/debt.png">
                    <p>Debt Security Trustee</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                    <img src="assets/images/Broker.png">
                    <p>Securities Broker</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                    <img src="assets/images/Registrar.png">
                    <p>Share Registrar</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                    <img src="assets/images/Commodities.png">
                    <p>Commodities</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                    <img src="assets/images/PSX.png">
                    <p>PSX</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                    <img src="assets/images/cdc.jpg">
                    <p>CDC</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                    <img src="assets/images/NCCPL.png">
                    <p>Share Registrar</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                    <img src="assets/images/pmex.jpg">
                    <p>PMEX</p>
                    </div>
                    </div>
                    </div>
                    <div class="clearfix"></div>
                    </div>
                     <div class="col-lg-4 hidden-xs">
                    <fieldset style="border: 3px dotted #03BEA3;">
                    <legend style="color: #03BEA3;padding-left: 16px;width: 57%;border-bottom: none;">Complaint Process</legend>
                    <div class="col-xs-12"><img src="assets/images/step-1.jpg"></div>
                    <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-2.jpg"></div>
                    <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-3.jpg"></div>
                    <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-4.jpg"></div>
                    <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-5.jpg"></div>
                    <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-6.jpg"></div>
                    </fieldset>
                    </div>
                
                </div>
                <div id="insurance" class="wizard-step">
                <div class="form-group">
                <div class="col-lg-8 padding-top-10">
                  <div class="col-xs-12">
                    <h2 style="text-align: left;"> Insurence </h2>
                  </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/managment.jpg">
                      <p>Asset Management</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/finance.jpg">
                      <p>Housing Finance</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/advisory.jpg">
                      <p>Inestment Advisory</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/invesment_finance.jpg">
                      <p>Investment Finance</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/leasing.jpg">
                      <p>Leasing Company</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/mdaraba_managment.jpg">
                      <p>Modaraba Management</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/non_bank.jpg">
                      <p>Non-bank micro finance</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/private_equaity.jpg">
                      <p>Private Equity and Venture Capital</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/reitmanager.jpg">
                      <p>REIT Management Company</p>
                    </div>
                    <div class="col-xs-12">
                        <button type="button" class="wizard-next btn btn-primary float-right">Next</button>
                        <button type="button" class="wizard-prev btn btn-primary float-right" style="margin-right: 8px;">Previous</button>
                    </div>
                    <div class="clearfix"></div>
                    </div>
                     <div class="col-lg-4 hidden-xs">
                        <fieldset style="border: 3px dotted #03BEA3;">
                          <legend style="color: #03BEA3;padding-left: 16px;width: 57%;border-bottom: none;">Complaint Process</legend>
                          <div class="col-xs-12"> <img src="assets/images/step-1.jpg"></div>
                          <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-2.jpg"></div>
                          <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-3.jpg"></div>
                          <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-4.jpg"></div>
                          <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-5.jpg"></div>
                          <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-6.jpg"></div>
                        </fieldset>
                    </div>
                </div>
                </div>
                <div id="specialized_companies" class="wizard-step">
                <div class="form-group">
                <div class="col-lg-8 padding-top-10 text-center">
                  <div class="col-xs-12">
                    <h2 style="text-align: left;"> Specialized Companies </h2>
                  </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/managment.jpg">
                      <p>Asset Management</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/finance.jpg">
                      <p>Housing Finance</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/advisory.jpg">
                      <p>Inestment Advisory</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/invesment_finance.jpg">
                      <p>Investment Finance</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/leasing.jpg">
                      <p>Leasing Company</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/mdaraba_managment.jpg">
                      <p>Modaraba Management</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/non_bank.jpg">
                      <p>Non-bank micro finance</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/private_equaity.jpg">
                      <p>Private Equity and Venture Capital</p>
                    </div>
                    <div class="col-lg-3 col-xs-12">
                      <img src="assets/images/reitmanager.jpg">
                      <p>REIT Management Company</p>
                    </div>
                    <div class="col-xs-12">
                        <button type="button" class="wizard-next btn btn-primary float-right">Next</button>
                        <button type="button" class="wizard-prev btn btn-primary float-right" style="margin-right: 8px;">Previous</button>
                    </div>
                <div class="clearfix"></div>
                </div>
                 <div class="col-lg-4 hidden-xs">
                      <fieldset style="border: 3px dotted #03BEA3;">
                        <legend style="color: #03BEA3;padding-left: 16px;width: 57%;border-bottom: none;">Complaint Process</legend>
                        <div class="col-xs-12"> <img src="assets/images/step-1.jpg"></div>
                        <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-2.jpg"></div>
                        <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-3.jpg"></div>
                        <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-4.jpg"></div>
                        <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-5.jpg"></div>
                        <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-6.jpg"></div>
                      </fieldset>
                  </div>
                </div>
                </div>
                <div id="finish" class="wizard-step">
                    <div class="col-lg-8 padding-top-10" style="color: black;">
                        <div class="col-xs-12">
                        <h2 style="text-align: left;"> Last Section </h2>
                        </div>
                        <div class="form-group">
                        <div class="col-lg-6 col-xs-12 form-group">
                        <label for="complaint">Title <span style="color: red;font-size: 13px;">*</span> </label>
                        </div>
                        <div class="col-lg-6 col-xs-12 form-group">
                        <input type="text" name="name" class="form-control backrnd" required>
                        </div>
                       
                        <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
                        <label for="complaint">Detail</label>
                        </div>
                        <div class="col-lg-6 col-xs-12 form-group has-feedback">
                        <textarea name="applicant_address" class="form-control" rows="5"></textarea>
                        </div>
                        <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
                        <label for="complaint">Evidance 1 :</label>
                        </div>
                        <div class="col-lg-6 col-xs-12 form-group has-feedback">
                        <input type="file" name="evid1" class="form-control">
                        </div>
                        <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
                        <label for="complaint">Evidance 1 :</label>
                        </div>
                        <div class="col-lg-6 col-xs-12 form-group has-feedback">
                        <input type="file" name="evid1" class="form-control">
                        </div>
                        <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
                        <label for="complaint">Evidance 1 :</label>
                        </div>
                        <div class="col-lg-6 col-xs-12 form-group has-feedback">
                        <input type="file" name="evid1" class="form-control">
                        </div>
                        <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
                        <label for="complaint">Evidance 1 :</label>
                        </div>
                        <div class="col-lg-6 col-xs-12 form-group has-feedback">
                        <input type="file" name="evid1" class="form-control">
                        </div>
                        <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
                        <label for="complaint">Evidance 1 :</label>
                        </div>
                        <div class="col-lg-6 col-xs-12 form-group has-feedback">
                        <input type="file" name="evid1" class="form-control">
                        </div>
                        <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
                        <label for="complaint">Evidance 1 :</label>
                        </div>
                        <div class="col-lg-6 col-xs-12 form-group has-feedback">
                        <input type="file" name="evid1" class="form-control">
                        </div>

                        </div>
                        <div class="col-xs-12">
                        <button type="button" class="wizard-finish btn btn-primary float-right">Finish</button>
                        </div>
                        <div class="clearfix"></div>
                     </div>
                     <div class="col-lg-4 hidden-xs">
                        <fieldset style="border: 3px dotted #03BEA3;">
                          <legend style="color: #03BEA3;padding-left: 16px;width: 57%;border-bottom: none;">Complaint Process</legend>
                          <div class="col-xs-12"> <img src="assets/images/step-1.jpg"></div>
                          <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-2.jpg"></div>
                          <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-3.jpg"></div>
                          <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-4.jpg"></div>
                          <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-5.jpg"></div>
                          <div class="col-xs-12 padding-top-10"> <img src="assets/images/step-6.jpg"></div>
                        </fieldset>
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
        <div class="col-lg-6 col-xs-12 padding-top-10 form-group">
            <label for="complaint">Province</label>
        </div>
        <div class="col-lg-6 col-xs-12 form-group has-feedback">
        <select class="form-control backrnd" name="district">
        <option>Select Your Province</option>
        <option>kp</option>
        <option>Panjab</option>
        <option>Sind</option>
        <option>Balochistan</option>
        </select>
        </div>
			
        <div class="col-lg-6 col-xs-12 form-group">
        <label for="complaint">Folio Number<span style="color: red;font-size: 13px;">*</span> </label>
        </div>
        <div class="col-lg-6 col-xs-12 form-group">
        <input type="text" name="name" class="form-control backrnd" required>
        </div>
        <div class="col-lg-6 col-xs-12 form-group">
        <label for="complaint">Mobile Number <span style="color: red;font-size: 13px;">*</span></label>
        </div>
        <div class="col-lg-6 col-xs-12 form-group">
        <input type="text" class="form-control backrnd" name="phone" required>
        </div>
        <div class="col-lg-6 col-xs-12 form-group">
        <label for="complaint">CNIC <span style="color: red;font-size: 13px;">*</span></label>
        </div>
        <div class="col-lg-6 col-xs-12 form-group">
        <input type="text" class="form-control backrnd" name="nic">
        </div>
                
        <div class="col-xs-12">
        <button type="button" class="wizard-next btn btn-primary float-right">Next</button>
        </div>
    </div>
    
    
<!-- 
<?php //include('includes/footer.php') ?>
 -->