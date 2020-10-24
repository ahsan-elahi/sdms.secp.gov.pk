<?php
require('staff.inc.php');
require_once(INCLUDE_DIR.'class.ticket.php');
require_once(INCLUDE_DIR.'class.topic.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');

$sql='SELECT topic_id, topic FROM '.TOPIC_TABLE
.' WHERE topic_pid='.$_REQUEST['cat_id'].' '
.' ORDER by topic';
if(($res=db_query($sql)) && db_num_rows($res)) {
?>
<div class="title">Parent Categoy:</div>
<div class="text">
<select onChange="get_subcategory(this.value);" id="select_<?php echo $_REQUEST['next_tiers'] ?>">
<option value="">&mdash; Select Parent Category &mdash;</option>
<?php
while(list($id, $name)=db_fetch_row($res)) {
echo sprintf('<option value="%d" %s>%s</option>',
$id, (($info['pid'] && $id==$info['pid'])?'selected="selected"':'') ,$name);
}
?>
</select> (<em>optional</em>)
&nbsp;<span class="error">&nbsp;<?php echo $errors['pid']; ?></span></div>
<?php 
}
?>
