<?php
require('admin.inc.php');

if($_POST){
    switch(strtolower($_POST['do'])){
        case 'mass_process':
            if(!$_POST['ids'] || !is_array($_POST['ids']) || !count($_POST['ids'])) {
                $errors['err'] = 'You must select at least one log to delete';
            } else {
                $count=count($_POST['ids']);
                if($_POST['a'] && !strcasecmp($_POST['a'], 'delete')) {

                    $sql='DELETE FROM '.SYSLOG_TABLE
                        .' WHERE log_id IN ('.implode(',', db_input($_POST['ids'])).')';
                    if(db_query($sql) && ($num=db_affected_rows())){
                        if($num==$count)
                            $msg='Selected logs deleted successfully';
                        else
                            $warn="$num of $count selected logs deleted";
                    } elseif(!$errors['err'])
                        $errors['err']='Unable to delete selected logs';
                } else {
                    $errors['err']='Unknown action - get technical help';
                }
            }
            break;
        default:
            $errors['err']='Unknown command/action';
            break;
    }
}

$page='syslogs.inc.php';
$nav->setTabActive('logs');
require(STAFFINC_DIR.'header.inc.php');
require(STAFFINC_DIR.$page);
include(STAFFINC_DIR.'footer.inc.php');
?>
