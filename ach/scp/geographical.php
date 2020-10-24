<?php
require('staff.inc.php');
$nav->setTabActive('report');
$page='geographical.inc.php';
require(STAFFINC_DIR.'header.inc.php');
require(STAFFINC_DIR.$page);
include(STAFFINC_DIR.'footer.inc.php');
?>
