<?php

require('staff.inc.php');

$nav->setTabActive('report_sdms_summary');

$page='report_tehsil_month.inc.php';

require(STAFFINC_DIR.'header.inc.php');

require(STAFFINC_DIR.$page);

include(STAFFINC_DIR.'footer.inc.php');

?>

