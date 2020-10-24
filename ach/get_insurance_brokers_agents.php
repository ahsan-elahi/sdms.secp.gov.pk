<?php
require('client.inc.php');
define('SOURCE','Web'); //Ticket source.
if($_REQUEST['lang']=='urdu')
{
$sql="SELECT * FROM sdms_insurance WHERE parent='".$_REQUEST['parent']."' AND child_agent!=''  group by child_agent ";
if(($res=db_query($sql)) && db_num_rows($res)) {
?>
<div style="clear:both"></div>
<div class="col-lg-offset-3 col-lg-5 col-xs-12 form-group" style="padding-top: 20px;">
<select name="i_broker_agent" id="i_agent"  class="form-control backrnd">
<option value="">&mdash; Select Brokers Agents &mdash;</option>
<?php
while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['child_agent'];?>" ><?php echo $row['child_agent']; ?></option>
<?php 
}
?>
</select>
</div>
<div class="col-lg-4 col-xs-12 form-group">
<label for="complaint" class="float-right" style="padding-top: 20px;">ایجنٹ کی فہرست</label>
</div>
<div style="clear:both"></div>
<?php }

}else{	
$sql="SELECT * FROM sdms_insurance WHERE parent='".$_REQUEST['parent']."' AND child_agent!=''  group by child_agent ";
if(($res=db_query($sql)) && db_num_rows($res)) {
?>
<div style="clear:both"></div>
<div class="col-lg-6 col-xs-12 padding-top-10 form-group">
<?php if( $_REQUEST['type'] == 'Authorized Surveying Company'){ ?>
<label for="complaint">Survivor List:</label>
<?php }else{?>
<label for="complaint">Agent List:</label>
<?php }?>
</div>
<div class="col-lg-6 col-xs-12 form-group has-feedback">
<select name="i_broker_agent"  id="i_agent"  class="form-control backrnd">
<option value="">&mdash;  Please Select  &mdash;</option>
<?php
while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['child_agent'];?>" ><?php echo $row['child_agent']; ?></option>
<?php 
}
?>
</select>
</div>
<?php }
}
?>