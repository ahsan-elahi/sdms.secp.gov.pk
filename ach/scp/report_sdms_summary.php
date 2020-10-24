<?php
require('staff.inc.php');
include_once(INCLUDE_DIR.'class.districts.php');
include_once(INCLUDE_DIR.'class.status.php');
include_once(INCLUDE_DIR.'class.topic.php');
include_once(INCLUDE_DIR.'class.staff.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');

$all_districts = new Districts();

$all_status = new Status();

$all_categories = new Topic();

$all_staff = new Staff();

$nav->setTabActive('report_sdms_summary');

$page='report_sdms_summary.inc.php';

require(STAFFINC_DIR.'header.inc.php');

require(STAFFINC_DIR.$page);

include(STAFFINC_DIR.'footer.inc.php');

?>

