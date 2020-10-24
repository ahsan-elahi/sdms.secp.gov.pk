<?php
require('staff.inc.php');
require_once(INCLUDE_DIR.'class.ticket.php');
require_once(INCLUDE_DIR.'class.topic.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');

$sql="SELECT * FROM sdms_capital_markets WHERE type='".$_REQUEST['b_type']."' AND parent!='' group by parent ";
if(($res=db_query($sql)) && db_num_rows($res)) { ?>
<span>Type:</span>
<p><?php echo $_REQUEST['b_type']; ?></p>
<input type="hidden" value="<?php echo $_REQUEST['b_type']; ?>" name="cm_type" >

<?php if($_REQUEST['b_type'] == 'Debt Security Trustee'){ ?>
<label for="complaint">Company/Bank Name:</label>
<?php }elseif( $_REQUEST['b_type'] == 'Securities Broker' || $_REQUEST['b_type'] == 'Commodities Broker'){ ?>
<label for="complaint">Broker List:</label>
<?php }else{?>
<label for="complaint">Company List:</label>
<?php }?>
<p>
<select name="cm_broker_title" onChange="show_cm_agent_list(this.value);">
<option value="">&mdash; Please Select &mdash;</option>
<?php
while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['parent'];?>" <?php if($_REQUEST['company_title']==$row['parent']){ ?> selected <?php } ?> ><?php echo $row['parent']; ?></option>
<?php } ?>
</select>
</p>
<?php 
}else{ ?>
<span>Type:</span>
<input type="hidden" value="<?php echo $_REQUEST['b_type']; ?>" name="cm_type" >
<p><?php echo $_REQUEST['b_type']; ?></p>
<?php }?>