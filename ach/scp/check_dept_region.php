<?php
require('staff.inc.php');
require_once(INCLUDE_DIR.'class.ticket.php');
require_once(INCLUDE_DIR.'class.dept.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');
$sql="Select * from sdms_department where dept_id = '".$_REQUEST['dept_id']."' AND islocation='1'";
$res = db_query($sql);
if(db_num_rows($res)>0){?>
<div class="title">Region Types:</div>
<div class="text">
<select name="region_id" id="region_id" >
<option value="">--Select Region--</option>
<option value="1" >North</option>
<option value="2" >South</option>
</select>
</div>
<?php	}?>