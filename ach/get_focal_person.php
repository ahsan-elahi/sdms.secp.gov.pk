<?php
require('client.inc.php');
define('SOURCE','Web'); //Ticket source.
$sql="Select * from sdms_staff where dept_id = '".$_REQUEST['dept_id']."' AND isfocalperson='1'";
$res = db_query($sql);
if(db_num_rows($res)>0){$row_result = db_fetch_row($res);echo $row_result[0];}else{echo '0';}?>