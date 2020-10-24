<?php

require('admin.inc.php');
$nav->setTabActive('reports');
$page='geographical.inc.php';
require(STAFFINC_DIR.'header.inc.php');
require(STAFFINC_DIR.$page);
include(STAFFINC_DIR.'footer.inc.php');
?>
