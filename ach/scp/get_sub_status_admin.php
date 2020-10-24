<?php
require('staff.inc.php');
require_once(INCLUDE_DIR.'class.ticket.php');
require_once(INCLUDE_DIR.'class.topic.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');
$sql='SELECT *  FROM sdms_status status WHERE p_id='.$_REQUEST['status_id'].' ';
if(($res=db_query($sql)) && db_num_rows($res)) {
?>
<div class="title">Parent Status:</div>
<div class="text">
<select onChange="get_substatus(this.value);" id="select_<?php echo $_REQUEST['next_tiers'] ?>" required>
<option value="">&mdash; Select Status &mdash;</option>
<?php
 while ($row = db_fetch_array($res)) { ?>

<option value="<?php echo $row['status_id'] ?>"><?php echo $row['status_title'] ?></option>
<?php } ?>
</select>
&nbsp;<span class="error">&nbsp;<?php echo $errors['p_id']; ?></span></div>
<?php 
}
?>
