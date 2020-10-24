<?php
if(!defined('OSTCLIENTINC')) die('Access Denied!');
if($_REQUEST['lang'] == 'urdu')
{
?>
<div class="col-lg-offset-2 col-lg-5 col-xs-12 form-group has-feedback">
<select name="tehsil" onChange="get_agency_tehsils(this.value);" class="form-control backrnd" ><!--required-->
<option value="">--Select Province--</option>
<?php
if($sub_tehsils=Districts::getTehsils($_REQUEST['district_id'])) {
foreach($sub_tehsils as $id =>$sub_name) {
echo sprintf('<option value="%d" %s>%s</option>',$id, ($info['tehsil']==$id)?'selected="selected"':'', $sub_name);
}
}?>
<option value="0">Not Available</option>
</select>
</div>
<div class="col-lg-5 col-xs-12 padding-top-10 form-group">
<label for="complaint" class="float-right"> <span style="color: red;font-size: 13px;">*</span>صوبہ</label>
</div>



<?php echo $errors['tehsil']; ?>
<?php }else{ ?>
<div class="col-lg-6 col-xs-12 padding-top-10 form-group">
<label for="complaint">Province <span style="color: red;font-size: 13px;">*</span></label>
</div>
<div class="col-lg-6 col-xs-12 form-group has-feedback">

<select name="tehsil" class="form-control backrnd"  onChange="get_agency_tehsils(this.value);" ><!--required-->
<option value="">--Select Province--</option>
<?php
if($sub_tehsils=Districts::getTehsils($_REQUEST['district_id'])) {
foreach($sub_tehsils as $id =>$sub_name) {
echo sprintf('<option value="%d" %s>%s</option>',$id, ($info['tehsil']==$id)?'selected="selected"':'', $sub_name);
}
}?>
<option value="0">Not Available</option>
</select>
</div>
<?php }?>