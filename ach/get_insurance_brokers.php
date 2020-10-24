<?php
require('client.inc.php');
define('SOURCE','Web'); //Ticket source.
if($_REQUEST['lang']=='urdu')
{
$sql="SELECT * FROM sdms_insurance WHERE type='".$_REQUEST['b_type']."' AND parent!='' group by parent ";
//echo $sql;
if(($res=db_query($sql)) && db_num_rows($res)) {
?>

<div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
<b><?php echo $_REQUEST['b_type_urdu']; ?></b>
<input type="hidden" value="<?php echo $_REQUEST['b_type']; ?>" name="i_type" >
</div>
<div class="col-lg-4 col-xs-12 form-group">
<label for="complaint" class="float-right" style="padding-top: 20px;">قسم</label>
</div>
<div style="clear:both"></div>
<div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
<select name="i_broker_title"  id="i_broker" onChange="show_insurance_agent_list('<?php echo $_REQUEST['b_type']; ?>',this.value);"  class="form-control backrnd">
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
<?php if( $_REQUEST['b_type'] == 'Insurance Broker'){ ?>
<label for="complaint" class="float-right" style="padding-top: 20px;">:بروکروں کی فہرست</label>
<?php }else{?>
<label for="complaint" class="float-right" style="padding-top: 20px;">:کمپنیوں کی فہرست</label>
<?php }?>
</div>
<?php 
}else{ ?>
<div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
<b><?php echo $_REQUEST['b_type_urdu']; ?></b>
<input type="hidden" value="<?php echo $_REQUEST['b_type']; ?>" name="i_type" >
</div>
<div class="col-lg-4 col-xs-12 form-group">
<label for="complaint" class="float-right" style="padding-top: 20px;">قسم</label>
</div>
<div style="clear:both"></div>

<?php } 
}else{
	
$sql="SELECT * FROM sdms_insurance WHERE type='".$_REQUEST['b_type']."' AND parent!='' group by parent ";
//echo $sql;
if(($res=db_query($sql)) && db_num_rows($res)) {
?>
<div class="col-lg-6 col-xs-12 form-group"><label for="complaint">Type: </label></div>
<div class="col-lg-6 col-xs-12 form-group"><b><?php echo $_REQUEST['b_type']; ?></b>
<input type="hidden" value="<?php echo $_REQUEST['b_type']; ?>" name="i_type" ></div>
<div style="clear:both"></div>


<div class="col-lg-6 col-xs-12 padding-top-10 form-group">
<?php if( $_REQUEST['b_type'] == 'Insurance Broker'){ ?>
<label for="complaint">Broker List:</label>
<?php }else{?>
<label for="complaint">Company List:</label>
<?php }?>

</div>
<div class="col-lg-6 col-xs-12 form-group has-feedback">
<select name="i_broker_title" id="i_broker" onChange="show_insurance_agent_list('<?php echo $_REQUEST['b_type']; ?>',this.value);"  class="form-control backrnd">
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
<input type="hidden" value="<?php echo $_REQUEST['b_type']; ?>" name="i_type" ></div>
<div style="clear:both"></div>
<?php } 

}?>

