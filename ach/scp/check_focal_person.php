<?php
require('staff.inc.php');
require_once(INCLUDE_DIR.'class.ticket.php');
require_once(INCLUDE_DIR.'class.dept.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');
$sql="Select * from sdms_staff where dept_id = '".$_REQUEST['dept_id']."' AND isfocalperson='1'";
$res = db_query($sql);
if(db_num_rows($res)>0){$row_result = db_fetch_row($res);echo 'Focal Person already Created.';}else{echo '0';}?>