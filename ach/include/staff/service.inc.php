<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');
$info=array();
$qstr='';
if($service && $_REQUEST['a']!='add') {
    $title='Update Subject';
    $action='update';
    $submit_text='Save Changes';
    $info=$service->getInfo();
    $info['id']=$service->getId();
    $qstr.='&id='.$service->getId();
} else {
    $title='Add New Subject';
    $action='create';
    $submit_text='Add Subject';
    $qstr.='&a='.$_REQUEST['a'];
}
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
?>

<div class="page-header"><h1>Complaint  <small>Subject </small></h1></div>  
<form action="services.php?<?php echo $qstr; ?>" method="post" id="save">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="<?php echo $action; ?>">
 <input type="hidden" name="a" value="<?php echo Format::htmlchars($_REQUEST['a']); ?>">
 <input type="hidden" name="id" value="<?php echo $info['id']; ?>">         
<div class="row-fluid">
 <!--Left section-->
<div class="span12">
<div class="block-fluid ucard">
            <div class="info">                                                                
                <ul class="rows">
                    <li class="heading"><div class="isw-users"></div><?php echo $title; ?>&nbsp;&nbsp;&nbsp;<em>(Complaint Subject Information)</em></li>
                    <li>
                        <div class="title">Subject Title:</div>
                        <div class="text"><input type="text" size="30" name="service" value="<?php echo $info['service_title']; ?>">
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['service_title']; ?></span></div>
                    </li>                        
                </ul>                                                      
           </div>                        
        </div>
</div>  
</div>

<div class="row-fluid">
<div class="span12">
    <div class="head clearfix">
        <div class="isw-documents"></div>
        <h1>Admin Notes</h1>
    </div>
    <div class="block-fluid">                        

        <div class="row-form clearfix">
            <div class="span12"><em><strong>Admin Notes</strong>: Internal notes about the Subject.&nbsp;</em></div>
        </div>   
        
        <div class="row-form clearfix">
            <div class="span12"><textarea name="notes" cols="21" rows="8" style="width: 80%;"><?php echo $info['notes']; ?></textarea></div>
             
        </div>

</div>
</div>
</div>

<div class="row-fluid">  
<div class="span12">
    
    <div class="footer tar">
    <input type="submit" name="submit" value="<?php echo $submit_text; ?>" class="btn">
    <input type="reset"  name="reset"  value="Reset" class="btn">
    <input type="button" name="cancel" value="Cancel" onclick='window.location.href="helptopics.php"' class="btn">
    </div>  

</div>
</div>
</form>
<div class="dr"><span></span></div>
</div></div>
