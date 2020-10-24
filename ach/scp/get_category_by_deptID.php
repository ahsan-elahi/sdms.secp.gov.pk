<?php 
require('staff.inc.php');
require_once(INCLUDE_DIR.'class.ticket.php');
require_once(INCLUDE_DIR.'class.dept.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');
require_once(INCLUDE_DIR.'class.topic.php');
?>
<div class="title">Sub Category:</div>
<div class="text">
<select onChange="get_subcategory(this.value,this.id);" id="select_1">
<option value="">--Select Category--</option>
<?php
if($topics=Topic::getPublicHelpTopics($_REQUEST['dept_id'],'1')) {
foreach($topics as $id =>$name) {
echo sprintf('<option value="%d" %s>%s</option>',
    $id, ($info['topicId']==$id)?'selected="selected"':'', $name);
}
} else { ?>
<option value="0" >General Inquiry</option>
<?php
} ?>
</select>
</div>