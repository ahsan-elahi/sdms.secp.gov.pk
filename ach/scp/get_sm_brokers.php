<?php
require('staff.inc.php');
require_once(INCLUDE_DIR.'class.ticket.php');
require_once(INCLUDE_DIR.'class.topic.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');

$sql='SELECT * FROM sdms_sm_brokers  WHERE type='.$_REQUEST['b_type'].' ';
if(($res=db_query($sql)) && db_num_rows($res)) {
?>
<span>Brokers List:</span>
<p>
<select name="sm_broker_title">
<option value="">&mdash; Select Brokers &mdash;</option>
<?php
while($row = db_fetch_array($res)) { ?>
<option value="<?php echo $row['title'];?>" ><?php echo $row['title']; ?></option>
<?php 
}
?>
</select>
</p>
<?php 
}
?>

