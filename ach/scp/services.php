<?php
require('admin.inc.php');
include_once(INCLUDE_DIR.'class.services.php');

$service=null;
if($_REQUEST['id'] && !($service=Service::lookup($_REQUEST['id'])))
    $errors['err']='Unknown or invalid Service ID.';

if($_POST){
    switch(strtolower($_POST['do'])){
        case 'update':
            if(!$service){
                $errors['err']='Unknown or invalid help topic.';
            }elseif($service->update($_POST,$errors)){
                $msg='Complaint Service updated successfully';
            }elseif(!$errors['err']){
                $errors['err']='Error updating help topic. Try again!';
            }
            break;
        case 'create':
            if(($id=Service::create($_POST,$errors))){
                $msg='Complaint Service added successfully';
                $_REQUEST['a']=null;
            }elseif(!$errors['err']){
                $errors['err']='Unable to add Complaint Service. Correct error(s) below and try again.';
            }
            break;
        case 'mass_process':
            if(!$_POST['ids'] || !is_array($_POST['ids']) || !count($_POST['ids'])) {
                $errors['err'] = 'You must select at least one help topic';
            } else {
                $count=count($_POST['ids']);
                switch(strtolower($_POST['a'])) {
                    case 'enable':
                        $sql='UPDATE '.TOPIC_TABLE.' SET isactive=1 '
                            .' WHERE topic_id IN ('.implode(',', db_input($_POST['ids'])).')';
                        if(db_query($sql) && ($num=db_affected_rows())) {
                            if($num==$count)
                                $msg = 'Selected help topics enabled';
                            else
                                $warn = "$num of $count selected help topics enabled";
                        } else {
                            $errors['err'] = 'Unable to enable selected help topics.';
                        }
                        break;
                    case 'disable':
                        $sql='UPDATE '.TOPIC_TABLE.' SET isactive=0 '
                            .' WHERE topic_id IN ('.implode(',', db_input($_POST['ids'])).')';
                        if(db_query($sql) && ($num=db_affected_rows())) {
                            if($num==$count)
                                $msg = 'Selected help topics disabled';
                            else
                                $warn = "$num of $count selected help topics disabled";
                        } else {
                            $errors['err'] ='Unable to disable selected help topic(s)';
                        }
                        break;
                    case 'delete':
                        $i=0;
                        foreach($_POST['ids'] as $k=>$v) {
                            if(($t=Topic::lookup($v)) && $t->delete())
                                $i++;
                        }
                        if($i && $i==$count)
                            $msg = 'Selected help topics deleted successfully';
                        elseif($i>0)
                            $warn = "$i of $count selected help topics deleted";
                        elseif(!$errors['err'])
                            $errors['err']  = 'Unable to delete selected help topics';
                        break;
                    default:
                        $errors['err']='Unknown action - get technical help.';
                }
            }
            break;
        default:
            $errors['err']='Unknown command/action';
            break;
    }
}
$page='services.inc.php';
if($service || ($_REQUEST['a'] && !strcasecmp($_REQUEST['a'],'add')))
    $page='service.inc.php';
//echo $page;exit;
$nav->setTabActive('helptopics');
require(STAFFINC_DIR.'header.inc.php');
require(STAFFINC_DIR.$page);
include(STAFFINC_DIR.'footer.inc.php');
?>
