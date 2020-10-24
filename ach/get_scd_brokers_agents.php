<?php
require('client.inc.php');
if($_REQUEST['lang']=='urdu')
{
if($_REQUEST['action']=='reit_scheme'){
$sql="SELECT * FROM sdms_scd WHERE parent='".$_REQUEST['parent']."' AND reit_scheme!=''  group by reit_scheme ";
if(($res=db_query($sql)) && db_num_rows($res)) { ?>
<div style="clear:both"></div>
<div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
<select name="reit_scheme" id="reit"  class="form-control backrnd">
<option value="">&mdash; Select REIT Scheme &mdash;</option>
<?php while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['reit_scheme'];?>" ><?php echo $row['reit_scheme']; ?></option>
<?php } ?>
</select>
</div>
<div class="col-lg-4 col-xs-12 form-group">
<label for="complaint" class="float-right" style="padding-top: 20px;"><span style="color: red;font-size: 13px;">*</span> REIT سکیم</label>
</div>
<div style="clear:both"></div>
<?php } }
else if($_REQUEST['action']=='modaraba_fund'){	
$sql="SELECT * FROM sdms_scd WHERE parent='".$_REQUEST['parent']."' AND modaraba_fund!=''  group by modaraba_fund ";
if(($res=db_query($sql)) && db_num_rows($res)) { ?>
<div style="clear:both"></div>
<div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
<select name="modaraba_fund"  id="modaraba" class="form-control backrnd">
<option value="">&mdash; Select Modaraba Fund &mdash;</option>
<?php while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['modaraba_fund'];?>" ><?php echo $row['modaraba_fund']; ?></option>
<?php } ?>
</select>
</div>
<div class="col-lg-4 col-xs-12 form-group">
<label for="complaint" class="float-right" style="padding-top: 20px;"><span style="color: red;font-size: 13px;">*</span> مضاربہ فنڈ</label>
</div>
<div style="clear:both"></div>
<?php } }
else if($_REQUEST['action']=='mutual_fund'){
	
$sql="SELECT * FROM sdms_scd WHERE parent='".$_REQUEST['parent']."' AND mutual_fund!=''  group by mutual_fund ";
if(($res=db_query($sql)) && db_num_rows($res)) { ?>
<div style="clear:both"></div>
<div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
<select name="mutual_fund"  id="mutual" class="form-control backrnd">
<option value="">&mdash; Select Mutual Fund &mdash;</option>
<?php while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['mutual_fund'];?>" ><?php echo $row['mutual_fund']; ?></option>
<?php } ?>
</select>
</div>
<div class="col-lg-4 col-xs-12 form-group">
<label for="complaint" class="float-right" style="padding-top: 20px;"><span style="color: red;font-size: 13px;">*</span> مشترکہ فنڈ</label>
</div>
<div style="clear:both"></div>
<?php } }
else if($_REQUEST['action']=='pension_fund'){
	
$sql="SELECT * FROM sdms_scd WHERE parent='".$_REQUEST['parent']."' AND pension_fund!=''  group by pension_fund ";
if(($res=db_query($sql)) && db_num_rows($res)) { ?>

<div style="clear:both"></div>
<div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
<select name="pension_fund" id="pension"  class="form-control backrnd">
<option value="">&mdash; Select Pension Fund &mdash;</option>
<?php while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['pension_fund'];?>" ><?php echo $row['pension_fund']; ?></option>
<?php } ?>
</select>
</div>
<div class="col-lg-4 col-xs-12 form-group">
<label for="complaint" class="float-right" style="padding-top: 20px;"><span style="color: red;font-size: 13px;">*</span> وظیفہ کی رقم</label>
</div>
<div style="clear:both"></div>
<?php } } 
}else{
	if($_REQUEST['action']=='reit_scheme'){
$sql="SELECT * FROM sdms_scd WHERE parent='".$_REQUEST['parent']."' AND reit_scheme!=''  group by reit_scheme ";
if(($res=db_query($sql)) && db_num_rows($res)) { ?>
<div class="col-lg-6 col-xs-12 form-group"><label for="complaint">REIT Scheme: <span style="color: red;font-size: 13px;">*</span></label></div>
<div class="col-lg-6 col-xs-12 form-group">
<select name="reit_scheme" id="reit"  class="form-control backrnd">
<option value="">&mdash; Select REIT Scheme &mdash;</option>
<?php while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['reit_scheme'];?>" ><?php echo $row['reit_scheme']; ?></option>
<?php } ?>
</select>
</div>
<div style="clear:both"></div>
<?php } }
else if($_REQUEST['action']=='modaraba_fund'){	
$sql="SELECT * FROM sdms_scd WHERE parent='".$_REQUEST['parent']."' AND modaraba_fund!=''  group by modaraba_fund ";
if(($res=db_query($sql)) && db_num_rows($res)) { ?>
<div class="col-lg-6 col-xs-12 form-group"><label for="complaint">Modaraba Fund: <span style="color: red;font-size: 13px;">*</span></label></div>
<div class="col-lg-6 col-xs-12 form-group">
<select name="modaraba_fund" id="modaraba"  class="form-control backrnd">
<option value="">&mdash; Select Modaraba Fund &mdash;</option>
<?php while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['modaraba_fund'];?>" ><?php echo $row['modaraba_fund']; ?></option>
<?php } ?>
</select>
</div>
<div style="clear:both"></div>
<?php } }
else if($_REQUEST['action']=='mutual_fund'){
	
$sql="SELECT * FROM sdms_scd WHERE parent='".$_REQUEST['parent']."' AND mutual_fund!=''  group by mutual_fund ";
if(($res=db_query($sql)) && db_num_rows($res)) { ?>
<div class="col-lg-6 col-xs-12 form-group"><label for="complaint">Mutual Fund: <span style="color: red;font-size: 13px;">*</span></label></div>
<div class="col-lg-6 col-xs-12 form-group">
<select name="mutual_fund" id="mutual"  class="form-control backrnd">
<option value="">&mdash; Select Mutual Fund &mdash;</option>
<?php while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['mutual_fund'];?>" ><?php echo $row['mutual_fund']; ?></option>
<?php } ?>
</select>
</div>
<div style="clear:both"></div>
<?php } }
else if($_REQUEST['action']=='pension_fund'){
	
$sql="SELECT * FROM sdms_scd WHERE parent='".$_REQUEST['parent']."' AND pension_fund!=''  group by pension_fund ";
if(($res=db_query($sql)) && db_num_rows($res)) { ?>
<div class="col-lg-6 col-xs-12 form-group"><label for="complaint">Pension Fund: <span style="color: red;font-size: 13px;">*</span></label></div>
<div class="col-lg-6 col-xs-12 form-group">
<select name="pension_fund"  id="pension"  class="form-control backrnd">
<option value="">&mdash; Select Pension Fund &mdash;</option>
<?php while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['pension_fund'];?>" ><?php echo $row['pension_fund']; ?></option>
<?php } ?>
</select>
</div>
<div style="clear:both"></div>
<?php } } 
}
?>