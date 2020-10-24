<?php
require('staff.inc.php');
require_once(INCLUDE_DIR.'class.ticket.php');
require_once(INCLUDE_DIR.'class.topic.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');
if($_REQUEST['action']=='reit_scheme'){
$sql="SELECT * FROM sdms_scd WHERE parent='".$_REQUEST['parent']."' AND reit_scheme!=''  group by reit_scheme ";
if(($res=db_query($sql)) && db_num_rows($res)) { ?>
<span>REIT Scheme:</span>
<p>
<select name="reit_scheme">
<option value="">&mdash; Select REIT Scheme &mdash;</option>
<?php while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['reit_scheme'];?>" ><?php echo $row['reit_scheme']; ?></option>
<?php } ?>
</select></p>
<?php } 
}else if($_REQUEST['action']=='modaraba_fund'){	
$sql="SELECT * FROM sdms_scd WHERE parent='".$_REQUEST['parent']."' AND modaraba_fund!=''  group by modaraba_fund ";
if(($res=db_query($sql)) && db_num_rows($res)) { ?>
<span>Modaraba Fund:</span>
<p>
<select name="modaraba_fund">
<option value="">&mdash; Select Modaraba Fund &mdash;</option>
<?php while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['modaraba_fund'];?>" ><?php echo $row['modaraba_fund']; ?></option>
<?php } ?>
</select></p>
<?php } 
}else if($_REQUEST['action']=='mutual_fund'){
	
$sql="SELECT * FROM sdms_scd WHERE parent='".$_REQUEST['parent']."' AND mutual_fund!=''  group by mutual_fund ";
if(($res=db_query($sql)) && db_num_rows($res)) { ?>
<span>Mutual Fund :</span>
<p>
<select name="mutual_fund">
<option value="">&mdash; Select Mutual Fund &mdash;</option>
<?php while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['mutual_fund'];?>" ><?php echo $row['mutual_fund']; ?></option>
<?php } ?>
</select></p>
<?php } 
}else if($_REQUEST['action']=='pension_fund'){
	
$sql="SELECT * FROM sdms_scd WHERE parent='".$_REQUEST['parent']."' AND pension_fund!=''  group by pension_fund ";
if(($res=db_query($sql)) && db_num_rows($res)) { ?>
<span>Pension Fund:</span>
<p>
<select name="pension_fund">
<option value="">&mdash; Select Pension Fund &mdash;</option>
<?php while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['pension_fund'];?>" ><?php echo $row['pension_fund']; ?></option>
<?php } ?>
</select></p>
<?php } 
} ?>