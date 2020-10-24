<?php
require('client.inc.php');
define('SOURCE','Web'); //Ticket source.
if($_REQUEST['lang']=='urdu')
{

$sql="SELECT * FROM sdms_capital_markets WHERE type='".$_REQUEST['b_type']."' AND parent!='' group by parent ";
//echo $sql;
if(($res=db_query($sql)) && db_num_rows($res)) {
?>
<div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
<b><?php echo $_REQUEST['b_type_urdu']; ?></b>
<input type="hidden" value="<?php echo $_REQUEST['b_type']; ?>" name="cm_type" >
</div>
<div class="col-lg-4 col-xs-12 form-group">
<label for="complaint" class="float-right" style="padding-top: 20px;">قسم</label>
</div>
<div style="clear:both"></div>
<div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
<select name="cm_broker_title" id="cm_broker" onChange="show_cm_agent_list(this.value);"  class="form-control backrnd">
<option value="">&mdash; Please Select &mdash;</option>
<?php
while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['parent'];?>" ><?php echo $row['parent']; ?></option>
<?php 
}
?>
</select>
</div>
<div class="col-lg-4 col-xs-12 form-group">
<?php if($_REQUEST['b_type'] == 'Debt Security Trustee'){ ?>
<label for="complaint" class="float-right" style="padding-top: 20px;"><span style="color: red;font-size: 13px;">*</span> :کمپنی / بینک کا نام</label>
<?php }elseif( $_REQUEST['b_type'] == 'Securities Broker' || $_REQUEST['b_type'] == 'Commodities Broker'){ ?>
<label for="complaint" class="float-right" style="padding-top: 20px;"><span style="color: red;font-size: 13px;">*</span> :بروکروں کی فہرست</label>
<?php }else{?>
<label for="complaint" class="float-right" style="padding-top: 20px;"><span style="color: red;font-size: 13px;">*</span> :کمپنیوں کی فہرست</label>
<?php }?>
</div>

<?php 
}else{ ?>
<div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
<b><?php echo $_REQUEST['b_type_urdu']; ?></b>
<input type="hidden" value="<?php echo $_REQUEST['b_type']; ?>" name="cm_type" >
</div>
<div class="col-lg-4 col-xs-12 form-group">
<label for="complaint" class="float-right" style="padding-top: 20px;">قسم</label>
</div>
<div style="clear:both"></div>
<?php }?>


<?php  }else{
$sql="SELECT * FROM sdms_capital_markets WHERE type='".$_REQUEST['b_type']."' AND parent!='' group by parent ";
//echo $sql;
if(($res=db_query($sql)) && db_num_rows($res)) {
?>
<div class="col-lg-6 col-xs-12 form-group"><label for="complaint">Type: </label></div>
<div class="col-lg-6 col-xs-12 form-group"><b><?php echo $_REQUEST['b_type']; ?></b>
<input type="hidden" value="<?php echo $_REQUEST['b_type']; ?>" name="cm_type" ></div>
<div style="clear:both"></div>
<div class="col-lg-6 col-xs-12 padding-top-10 form-group">
<?php if($_REQUEST['b_type'] == 'Debt Security Trustee'){ ?>
<label for="complaint">Company/Bank Name: <span style="color: red;font-size: 13px;">*</span></label>
<?php }elseif( $_REQUEST['b_type'] == 'Securities Broker' || $_REQUEST['b_type'] == 'Commodities Broker'){ ?>
<label for="complaint">Broker List: <span style="color: red;font-size: 13px;">*</span></label>
<?php }else{?>
<label for="complaint">Company List: <span style="color: red;font-size: 13px;">*</span></label>
<?php }?>
</div>
<div class="col-lg-6 col-xs-12 form-group has-feedback">
<select name="cm_broker_title"  id="cm_broker" onChange="show_cm_agent_list(this.value);"  class="form-control backrnd">
<option value="">&mdash; Please Select &mdash;</option>
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
<div class="col-lg-6 col-xs-12 form-group"><?php echo $_REQUEST['b_type']; ?>
<input type="hidden" value="<?php echo $_REQUEST['b_type']; ?>" name="cm_type" ></div>
<div style="clear:both"></div>
<?php }?>
<?php }?>