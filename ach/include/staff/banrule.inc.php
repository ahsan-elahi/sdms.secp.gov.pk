<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');

$info=array();
$qstr='';
if($rule && $_REQUEST['a']!='add'){
    $title='Update Ban Rule';
    $action='update';
    $submit_text='Update';
    $info=$rule->getInfo();
    $info['id']=$rule->getId();
    $qstr.='&id='.$rule->getId();
}else {
    $title='Add New Email Address to Ban List';
    $action='add';
    $submit_text='Add';
    $info['isactive']=isset($info['isactive'])?$info['isactive']:1;
    $qstr.='&a='.urlencode($_REQUEST['a']);
}
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
?>
<form action="banlist.php?<?php echo $qstr; ?>" method="post" id="save">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="<?php echo $action; ?>">
 <input type="hidden" name="a" value="<?php echo Format::htmlchars($_REQUEST['a']); ?>">
 <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
<div class="page-header"><h1>Manage Email <small>Ban List</small></h1></div> 
 
<!--Left section-->
<div class="span6">
<div class="block-fluid ucard">
<div class="info">   
<ul class="rows">
<li class="heading"><div class="isw-users"></div><?php echo $title; ?>&nbsp;&nbsp;&nbsp;<em>Valid email address required.</em></li>
 
            <li>
            <div class="title">Filter Name:</div>
            <div class="text"><?php echo $filter->getName(); ?>
            </div>
            </li> 
            
            
            <li>
            <div class="title"> Ban Status:</div>
            <div class="text">
                 <input type="radio" name="isactive" value="1" <?php echo $info['isactive']?'checked="checked"':''; ?>>Active
                <input type="radio" name="isactive" value="0" <?php echo !$info['isactive']?'checked="checked"':''; ?>>Disabled
              
            </div>
            </li> 
            
            <li>
            <div class="title"> Email Address:</div>
            <div class="text"><input name="val" type="text" size="24" value="<?php echo $info['val']; ?>">&nbsp;<span class="error">*&nbsp;<?php echo $errors['val']; ?></span>
            </div>
            </li> 
            
            <li>
            <div class="title">Internal notes</div>
            <div class="text">Admin notes&nbsp;
            </div>
            </li> 
             <li>
            <div class="title"></div>
            <div class="text"><textarea name="notes" cols="21" rows="8" style="width: 80%;"><?php echo $info['notes']; ?></textarea>
            </div>
            </li> 
 
 </ul></div></div></div></div></div>
<p style="text-align:center;">
    <input type="submit" class="btn" name="submit" value="<?php echo $submit_text; ?>">
    <input type="reset" class="btn" name="reset"  value="Reset">
    <input type="button" class="btn"  name="cancel" value="Cancel" onclick='window.location.href="banlist.php"'>
</p>
</form>
