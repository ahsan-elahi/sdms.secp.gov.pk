<?php
require('staff.inc.php');
require_once(INCLUDE_DIR.'class.ticket.php');
require_once(INCLUDE_DIR.'class.topic.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');

$sql="SELECT * FROM sdms_insurance WHERE type='".$_REQUEST['b_type']."' AND parent!='' group by parent ";
//echo $sql;
if(($res=db_query($sql)) && db_num_rows($res)) {
?>
<span>Type:</span>
<p><?php echo $_REQUEST['b_type']; ?></p>
<input type="hidden" value="<?php echo $_REQUEST['b_type']; ?>" name="i_type" >
<?php if( $_REQUEST['b_type'] == 'Insurance Broker'){ ?>
<span>Broker List:</span>
<?php }else{?>
<span>Company List:</span>
<?php }?>
<p>
<select name="i_broker_title" onChange="show_insurance_agent_list(this.value);" id="i_broker_title">
<option value="">&mdash; Please Select &mdash;</option>
<?php
while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['parent'];?>" <?php if(urldecode($_REQUEST['company_title'])==$row['parent']){ ?> selected <?php } ?> ><?php echo $row['parent']; ?></option>
<?php 
}
?>
</select>
</p>
<?php 
}else{ ?>
<span>Type:</span>
<p><?php echo $_REQUEST['b_type']; ?></p>
<?php }?>

