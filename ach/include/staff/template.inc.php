<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');

$info=array();
$qstr='';
if($template && $_REQUEST['a']!='add'){
    $title='Update Template';
    $action='update';
    $submit_text='Save Changes';
    $info=$template->getInfo();
    $info['id']=$template->getId();
    $qstr.='&id='.$template->getId();
}else {
    $title='Add New Template';
    $action='add';
    $submit_text='Add Template';
    $info['isactive']=isset($info['isactive'])?$info['isactive']:0;
    $qstr.='&a='.urlencode($_REQUEST['a']);
}
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
?>
<form action="templates.php?<?php echo $qstr; ?>" method="post" id="save">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="<?php echo $action; ?>">
 <input type="hidden" name="a" value="<?php echo Format::htmlchars($_REQUEST['a']); ?>">
 <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
  <div class="page-header"><h1>Email<small>Template</small></h1></div> 
 
 <!--Left section-->
<div class="span6" style="width:850px;">
<div class="block-fluid ucard">
<div class="info">   
<ul class="rows">
<li class="heading"><div class="isw-users"></div><?php echo $title; ?>&nbsp;<em>Template information.</em></li>


 <li>
            <div class="title" style="width:171px;">Name:</div>
            <div class="text" style="margin-left:198px;"><input type="text" size="30" name="name" value="<?php echo $info['name']; ?>">
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['name']; ?></span>
            </div>
            </li>
        <li>
            <div class="title" style="width:171px;"> Status:</div>
            <div class="text" style="margin-left:198px;"><input type="radio" name="isactive" value="1" <?php echo $info['isactive']?'checked="checked"':''; ?>><strong>Active</strong>
                <input type="radio" name="isactive" value="0" <?php echo !$info['isactive']?'checked="checked"':''; ?>>Disabled
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['isactive']; ?></span>
            </div>
            </li>
            
            
            <li>
            <div class="title" style="width:171px;">  Language:</div>
            <div class="text" style="margin-left:198px;"><select name="lang_id">
                    <option value="en" selected="selected">English (US)</option>
                </select>
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['lang_id']; ?></span>
            </div>
            </li>

     
      
        <?php
        if($template){ ?>
       <li>
            <div class="title" style="width:200px;"> <strong>Template Messages</strong> </div>
            <div class="text" style="margin-left:198px;"> Click on the message to edit.&nbsp;
             <span class="error">*&nbsp;<?php echo $errors['rules']; ?></span></em>
            </div>
            </li> 
        <?php
         foreach(Template::message_templates() as $k=>$tpl){
            echo sprintf(' <li>
            <div class="title" style="width:200px;"> <strong><a href="templates.php?id=%d&a=manage&tpl=%s">%s</a></strong> </div>
            <div class="text" style="margin-left:198px;"><em>%s</em></div>
            </li>',
                    $template->getId(),$k,Format::htmlchars($tpl['name']),Format::htmlchars($tpl['desc']));
         }
        }else{ ?>
        
        
        <li>
            <div class="title" style="width:171px;"> Template To Clone:</div>
            <div class="text" style="margin-left:198px;"><select name="tpl_id">
                    <option value="0">&mdash; Select One &dash;</option>
                    <?php
                    $sql='SELECT tpl_id,name FROM '.EMAIL_TEMPLATE_TABLE.' ORDER by name';
                    if(($res=db_query($sql)) && db_num_rows($res)){
                        while(list($id,$name)=db_fetch_row($res)){
                            $selected=($info['tpl_id'] && $id==$info['tpl_id'])?'selected="selected"':'';
                            echo sprintf('<option value="%d" %s>%s</option>',$id,$selected,$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['tpl_id']; ?></span>
                 <em>(select an existing template to copy and edit it thereafter)</em>
            </div>
            </li>
     
        <?php } ?>
        <li>
            <div class="title" style="width:171px;">Admin Notes</div>
            <div class="text" style="margin-left:198px;"> Internal notes.
            </div>
            </li>
            
             <li>
            <div class="title" style="width:171px;"></div>
            <div class="text" style="margin-left:198px;"><textarea name="notes" cols="21" rows="8" style="width: 80%;"><?php echo $info['notes']; ?></textarea>
            </div>
            </li>
       </ul></div></div></div></div></div>
       
<p style="text-align:center;">
    <input type="submit" class="btn" name="submit" value="<?php echo $submit_text; ?>">
    <input type="reset"  class="btn" name="reset"  value="Reset">
    <input type="button"  class="btn" name="cancel" value="Cancel" onclick='window.location.href="templates.php"'>
</p>
</form>
