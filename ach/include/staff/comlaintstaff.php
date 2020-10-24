<?php
require('admin.inc.php');
$page='comlaintstaff.inc.php';
$nav->setTabActive('reports');
require(STAFFINC_DIR.'header.inc.php');
require(STAFFINC_DIR.$page);
include(STAFFINC_DIR.'footer.inc.php');
?>
