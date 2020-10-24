<?php


  
require('staff.inc.php');
require_once(INCLUDE_DIR.'class.ticket.php');
require_once(INCLUDE_DIR.'class.dept.php');
require_once(INCLUDE_DIR.'class.filter.php');
//$query2="update sdms_ticket set `ticket_voice`='".."' where ticket_id='".$_REQUEST['query']."'";
//mysql_query($query2)or die('error in updatation query');
$inc = 'thanks.inc.php';
require_once(STAFFINC_DIR.'header.inc.php');
require_once(STAFFINC_DIR.$inc);
require_once(STAFFINC_DIR.'footer.inc.php');
?>

