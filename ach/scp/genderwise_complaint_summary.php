<?php

require('staff.inc.php');

$nav->setTabActive('report_sdms_summary');

$page='genderwise_complaint_summary.inc.php';

require(STAFFINC_DIR.'header.inc.php');

require(STAFFINC_DIR.$page);

include(STAFFINC_DIR.'footer.inc.php');

?>

