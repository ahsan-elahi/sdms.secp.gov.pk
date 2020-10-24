<?php
require('staff.inc.php');

$page='report_viewer.inc.php';
$nav->setTabActive('admin_dashboard');
//require(STAFFINC_DIR.'header.inc.php');
require(STAFFINC_DIR.$page);
include(STAFFINC_DIR.'footer.inc.php');
?>
