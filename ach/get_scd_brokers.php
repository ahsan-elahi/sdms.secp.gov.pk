<?php
require('client.inc.php');
if($_REQUEST['lang']=='urdu')
{
$sql="SELECT * FROM sdms_scd WHERE type='".$_REQUEST['b_type']."' AND parent!='' group by parent ";
if(($res=db_query($sql)) && db_num_rows($res)) {
?>
<div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
<b><?php echo $_REQUEST['b_type_urdu']; ?></b>
<input type="hidden" value="<?php echo $_REQUEST['b_type']; ?>" name="scd_type" >
</div>
<div class="col-lg-4 col-xs-12 form-group">
<label for="complaint" class="float-right" style="padding-top: 20px;"><span style="color: red;font-size: 13px;">*</span>قسم</label>
</div>
<div style="clear:both"></div>

<div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
<select name="scd_broker_title" id="scd_broker" onChange="show_scd_agent_list(this.value);"  class="form-control backrnd">
<option value="">&mdash; Select Company &mdash;</option>
<?php
while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['parent'];?>" ><?php echo $row['parent']; ?></option>
<?php 
}
?>
</select>
</div>
<div class="col-lg-4 col-xs-12 form-group">
<label for="complaint" class="float-right" style="padding-top: 20px;">:کمپنیوں کی فہرست</label>
</div>

<?php 
}else{ ?>
<div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
<b><?php echo $_REQUEST['b_type_urdu']; ?></b>
<input type="hidden" value="<?php echo $_REQUEST['b_type']; ?>" name="scd_type" >
</div>
<div class="col-lg-4 col-xs-12 form-group">
<label for="complaint" class="float-right" style="padding-top: 20px;">قسم</label>
</div>
<div style="clear:both"></div>
<?php }
}else{
$sql="SELECT * FROM sdms_scd WHERE type='".$_REQUEST['b_type']."' AND parent!='' group by parent ";
if(($res=db_query($sql)) && db_num_rows($res)) {
?>
<div class="col-lg-6 col-xs-12 form-group"><label for="complaint">Type: </label></div>
<div class="col-lg-6 col-xs-12 form-group"><b><?php echo $_REQUEST['b_type']; ?></b>
<input type="hidden" value="<?php echo $_REQUEST['b_type']; ?>" name="scd_type" ></div>
<div style="clear:both"></div>
<div class="col-lg-6 col-xs-12 padding-top-10 form-group">
<label for="complaint">Company List:<span style="color: red;font-size: 13px;">*</span></label></div>
<div class="col-lg-6 col-xs-12 form-group has-feedback">
<select name="scd_broker_title" id="scd_broker" onChange="show_scd_agent_list(this.value);"  class="form-control backrnd">
<option value="">&mdash; Select Company &mdash;</option>
<?php
while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['parent'];?>" ><?php echo $row['parent']; ?></option>
<?php 
}
?>
</select>
</div>
<?php 
}else{ ?>
<div class="col-lg-6 col-xs-12 form-group"><label for="complaint">Type: </label></div>
<div class="col-lg-6 col-xs-12 form-group"><b><?php echo $_REQUEST['b_type']; ?></b>
<input type="hidden" value="<?php echo $_REQUEST['b_type']; ?>" name="scd_type" ></div>
<div style="clear:both"></div>
<?php }
	
}?>

