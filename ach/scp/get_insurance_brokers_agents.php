<?php
require('staff.inc.php');
require_once(INCLUDE_DIR.'class.ticket.php');
require_once(INCLUDE_DIR.'class.topic.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');

$sql="SELECT * FROM sdms_insurance WHERE parent='".$_REQUEST['parent']."' AND child_agent!=''  group by child_agent ";
if(($res=db_query($sql)) && db_num_rows($res)) {
?>
<span>Agent List:</span>
<p>
<select name="i_broker_agent">
<option value="">&mdash; Select Brokers Agents &mdash;</option>
<?php
while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['child_agent'];?>" ><?php echo $row['child_agent']; ?></option>
<?php 
}
?>
</select>
</p>
<?php 
}else{ ?>
<?php }?>

