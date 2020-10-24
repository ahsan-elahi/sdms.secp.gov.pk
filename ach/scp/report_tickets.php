<?php
require('staff.inc.php');
require_once(INCLUDE_DIR.'class.ticket.php');
require_once(INCLUDE_DIR.'class.dept.php');
require_once(INCLUDE_DIR.'class.status.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');
require_once(INCLUDE_DIR.'class.staff.php');
require_once(INCLUDE_DIR.'class.status.php');
require_once(INCLUDE_DIR.'class.districts.php');

////////////////atta code ////////////////////////////////////////////
$page='';
$ticket=null; //clean start.

/*... Quick stats ...*/
$stats= $thisstaff->getTicketsStats();
//Navigation
$nav->setTabActive('tickets');
	
$inc = 'report_tickets.inc.php';
require_once(STAFFINC_DIR.'header.inc.php');
require_once(STAFFINC_DIR.$inc);
require_once(STAFFINC_DIR.'footer.inc.php');
?>