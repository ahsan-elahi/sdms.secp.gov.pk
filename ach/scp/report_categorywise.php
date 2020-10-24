<?php
require('staff.inc.php');

include_once(INCLUDE_DIR.'class.districts.php');

include_once(INCLUDE_DIR.'class.status.php');

include_once(INCLUDE_DIR.'class.topic.php');

$all_districts = new Districts();

$all_status = new Status();

$all_categories = new Topic();

$nav->setTabActive('report_sdms_summary');

$nav->setTabActive('report_sdms_summary');

$page='report_categorywise.inc.php';

require(STAFFINC_DIR.'header.inc.php');

require(STAFFINC_DIR.$page);

include(STAFFINC_DIR.'footer.inc.php');

?>

