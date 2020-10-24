<?php
if(!defined('OSTSCPINC') || !$thisstaff || !$thisstaff->canManageFAQ()) die('Access Denied');
$info=array();
$qstr='';
if($category && $_REQUEST['a']!='add'){
    $title='Update Category :'.$category->getName();
    $action='update';
    $submit_text='Save Changes';
    $info=$category->getHashtable();
    $info['id']=$category->getId();
    $qstr.='&id='.$category->getId();
}else {
    $title='Add New Category';
    $action='create';
    $submit_text='Add';
    $qstr.='&a='.$_REQUEST['a'];
}
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);

?>
<div class="page-header"><h1>FAQ  <small>Category </small></h1></div>  
<form action="categories.php?<?php echo $qstr; ?>" method="post" id="save">
<?php csrf_token(); ?>
<input type="hidden" name="do" value="<?php echo $action; ?>">
<input type="hidden" name="a" value="<?php echo Format::htmlchars($_REQUEST['a']); ?>">
<input type="hidden" name="id" value="<?php echo $info['id']; ?>">          
<div class="row-fluid">

<div class="span12">
    <div class="head clearfix">
        <div class="isw-documents"></div>
        <h1><?php echo $title; ?> </h1>
    </div>
    <div class="block-fluid">                        

        <div class="row-form clearfix">
            <div class="span12"><em>Category information: Public categories are published if it has published FAQ articles.</em></div>
        </div>   
        
        <div class="row-form clearfix">
            <div class="span3">Category Type:</div>
             <div class="span9"> <input type="radio" name="ispublic" value="1" <?php echo $info['ispublic']?'checked="checked"':''; ?>><b>Public</b> (publish)
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="ispublic" value="0" <?php echo !$info['ispublic']?'checked="checked"':''; ?>>Private (internal)
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['ispublic']; ?></span></div>
        </div>
        
        <div class="row-form clearfix">
            <div class="span3"><b>Category Name</b>:<br />&nbsp;<span class="faded">Short descriptive name.</span></div>
             <div class="span9">  <input type="text" size="70" name="name" value="<?php echo $info['name']; ?>">
                    &nbsp;<span class="error">*&nbsp;<?php echo $errors['name']; ?></span></div>
        </div>
         
        <div class="row-form clearfix">
            <div class="span3"><b>Category Description</b>:<br />&nbsp;<span class="faded">Summary of the category.</span></div>
             <div class="span9"><font class="error">*&nbsp;<?php echo $errors['description']; ?></font>
             <textarea name="description" cols="21" rows="12" style="width:98%;" ><?php echo $info['description']; ?></textarea></div>
                    </div>                                
                    
        <div class="row-form clearfix">
            <div class="span3">Internal Notes&nbsp;</div>
             <div class="span9"><textarea name="notes" cols="21" rows="8" style="width: 80%;"><?php echo $info['notes']; ?></textarea></div>
        </div>            
        </div>                   
    </div>
</div>

<!--<div class="row-fluid">
    <div class="span12">
        <div class="head clearfix">
            <div class="isw-favorite"></div>
            <h1>WYSIWYG HTML Editor</h1>
        </div>
        <div class="block-fluid" id="wysiwyg_container">

            <textarea id="wysiwyg" name="text" style="height: 300px;"></textarea>

        </div>
    </div>
</div>-->

<div class="row-fluid">  
<div class="span12">
    
    <div class="footer tar">
    <input type="submit" name="submit" value="<?php echo $submit_text; ?>" class="btn">
    <input type="reset"  name="reset"  value="Reset Changes" class="btn">
    <input type="button" name="cancel" value="Cancel" onclick='window.location.href="categories.php"' class="btn">
    </div>  

</div>
</div>
</form>
<div class="dr"><span></span></div>
</div></div>
