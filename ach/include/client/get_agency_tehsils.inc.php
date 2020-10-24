<?php
if(!defined('OSTCLIENTINC')) die('Access Denied!');
if($_REQUEST['lang'] == 'urdu')
{
?>
<div class="col-lg-offset-2 col-lg-5 col-xs-12 form-group has-feedback">
<select id="jumpMenu" name="agency_tehsils" class="form-control backrnd" required>
<option value="">--Select City--</option>
<?php
if($agency_tehsils=Districts::getAgencyTehsil($_REQUEST['agency_tehsil_id'])) {
foreach($agency_tehsils as $id =>$sub_name) {
echo sprintf('<option value="%d" >%s</option>',$id, $sub_name);
}
}?>
 <option value="0">Not Available</option>
</select>
</div>
<div class="col-lg-5 col-xs-12 padding-top-10 form-group">
<label for="complaint" class="float-right"> <span style="color: red;font-size: 13px;">*</span>شہر</label>
</div>
<?php }else{ ?>
<div class="col-lg-6 col-xs-12 padding-top-10 form-group">
<label for="complaint">City <span style="color: red;font-size: 13px;">*</span></label>
</div>
<div class="col-lg-6 col-xs-12 form-group has-feedback">
<select class="form-control backrnd"  name="agency_tehsils" required>
<option value="">--Select City--</option>
<?php
if($agency_tehsils=Districts::getAgencyTehsil($_REQUEST['agency_tehsil_id'])) {
foreach($agency_tehsils as $id =>$sub_name) {
echo sprintf('<option value="%d" >%s</option>',$id, $sub_name);
}
}?>
 <option value="0">Not Available</option>
</select>
</div>
<?php }?>