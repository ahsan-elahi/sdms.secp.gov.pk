<?php
if(!defined('OSTSCPINC') || !$thisstaff) die('Access Denied');
$info=array();
$qstr='';
if($canned && $_REQUEST['a']!='add'){
    $title='Update Canned Response';
    $action='update';
    $submit_text='Save Changes';
    $info=$canned->getInfo();
    $info['id']=$canned->getId();
    $qstr.='&id='.$canned->getId();
}else {
    $title='Add New Canned Response';
    $action='create';
    $submit_text='Add Response';
    $info['isenabled']=isset($info['isenabled'])?$info['isenabled']:1;
    $qstr.='&a='.$_REQUEST['a'];
}
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);

?>

<div class="page-header"><h1>Canned Response  <small>Add New Canned Response</small></h1></div>  
<form action="canned.php?<?php echo $qstr; ?>" method="post" id="save" enctype="multipart/form-data">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="<?php echo $action; ?>">
 <input type="hidden" name="a" value="<?php echo Format::htmlchars($_REQUEST['a']); ?>">
 <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
 
<!--section divided into row span-->

<div class="row-fluid">  <!--main row-fluid div start-->

<!--left top section start-->
<div class="span12">
       

<!--left top section End-->


<!--right section start-->

        <div class="block-fluid ucard">
            <div class="info">                                                                
                <ul class="rows">
                   <!--header start-->
                    <li class="heading"><div class="isw-users"></div><em><strong>Canned Response</strong>:&nbsp;&nbsp;(Make the title short and clear)</em>
                    </li>
                   <!-- header end-->
                    
                    <li>
                        <div class="title">Status:</div>
                        <div class="text">
                        <label class="checkbox inline"><input type="radio" name="isenabled" value="1" <?php echo $info['isenabled']?'checked="checked"':''; ?>></label>Active
                       <label class="checkbox inline"><input type="radio" name="isenabled" value="0" <?php echo !$info['isenabled']?'checked="checked"':''; ?>></label>Disabled
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['isenabled']; ?>
                </span></div>
                    </li> 
                   
                    <li>
                        <div class="title">Department:</div>
                        <div class="text">
                        <select name="dept_id">
                    <option value="0">&mdash; All Departments &mdash;</option>
                    <?php
                    $sql='SELECT dept_id, dept_name FROM '.DEPT_TABLE.' dept ORDER by                     dept_name';
                    if(($res=db_query($sql)) && db_num_rows($res)) {
                        while(list($id,$name)=db_fetch_row($res)) {
                            $selected=($info['dept_id'] && $id==$info['dept_id'])?                             'selected="selected"':'';
                            echo sprintf('<option value="%d" %s>%s</option>',$id,                          $selected,$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['dept_id']; ?>
                </span></div>
                    </li>
                   
                    <li>
                        <div class="text">
                        <div><b>Title</b><span class="error">*&nbsp;<?php echo $errors['title']; ?></span></div>
                <input type="text" size="70" name="title" value="<?php echo $info['title']; ?>">
               
                <br><br><div><b>Canned Response</b> <font class="error">*&nbsp;<?php echo $errors['response']; ?></font>
                    &nbsp;&nbsp;&nbsp;(<a class="tip" href="ticket_variables">Supported Variables</a>)</div>
               
                <textarea name="response" cols="21" rows="6" style="width: 80%;"><?php echo $info['response']; ?></textarea>
                
                
                <br><br><div>
                <b>Canned Attachments</b> (optional) <font class="error">&nbsp;<?php echo $errors['files']; ?></font></div>
                <?php
                if($canned && ($files=$canned->getAttachments())) {
                    echo '<div id="canned_attachments"><span class="faded">Uncheck to delete the attachment on submit</span><br>';
                    foreach($files as $file) {
                        $hash=$file['hash'].md5($file['id'].session_id().$file['hash']);
                        echo sprintf('<label><input type="checkbox" name="files[]" id="f%d" value="%d" checked="checked">
                                      <a href="file.php?h=%s">%s</a>&nbsp;&nbsp;</label>&nbsp;',
                                      $file['id'], $file['id'], $hash, $file['name']);
                    }
                    echo '</div><br>';
            
                }
                //Hardcoded limit... TODO: add a setting on admin panel - what happens on tickets page??
                if(count($files)<10) {
                ?>
                <div>
                    <input type="file" name="attachments[]" value=""/>
                </div>
                <?php 
                }?>
                <div class="faded">You can upload up to 10 attachments per canned response.
               </div>
                        
                        </div>
                        
                        
                    </li> 
                    
                    
                                    
                </ul>                                                      
           </div>                        
        </div>
</div>
<!--right section End-->





</div>                     <!--main row-fluid div End-->


<div class="row-fluid">    <!--main row-fluid div start-->

<!--left bottom section-->
<div class="span12">
        <div class="block-fluid ucard">
            <div class="info">                                                                
                <ul class="rows">
                   <!--header start-->
                    <li class="heading"><div class="isw-users"></div><em><strong>Internal Notes</strong>:&nbsp;&nbsp;(Notes about the canned response)</em></li>
                   <!-- header end-->
                    <li>
                        
                        <div class="text">
                        <textarea name="notes" cols="21" rows="6" style="width: 80%;"><?php echo $info['notes']; ?></textarea>
                        </div>
                    </li> 
                                    
                </ul>                                                      
           </div>                        
        </div>
</div>

</div>                      <!--main row-fluid div End-->



<?php if ($canned && $canned->getFilters()) { ?>
    <br/>
    <div id="msg_warning">Canned response is in use by email filter(s): <?php
    echo implode(', ', $canned->getFilters()); ?></div>
 <?php } ?>






<!--footer-->
<div class="row-fluid">  
<div class="span12">
    
    <div class="footer tar">
    <!--<p style="padding-left:225px;">-->
    <input type="submit" name="submit" value="<?php echo $submit_text; ?>" class="btn">
    <input type="reset"  name="reset"  value="Reset" class="btn">
    <input type="button" name="cancel" value="Cancel" onclick='window.location.href="canned.php"' class="btn">
<!--</p>-->
    </div>  

</div>
</div>

<div class="dr"><span></span></div>
</div></div>