<?php
if(!defined('OSTSCPINC') || !$thisstaff || !$thisstaff->canManageFAQ()) die('Access Denied');
$info=array();
$qstr='';
if($faq){
    $title='Update FAQ: '.$faq->getQuestion();
    $action='update';
    $submit_text='Save Changes';
    $info=$faq->getHashtable();
    $info['id']=$faq->getId();
    $info['topics']=$faq->getHelpTopicsIds();
    $qstr='id='.$faq->getId();
}else {
    $title='Add New FAQ';
    $action='create';
    $submit_text='Add FAQ';
    if($category) {
        $qstr='cid='.$category->getId();
        $info['category_id']=$category->getId();
    }
}
//TODO: Add attachment support.
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
?>
<div class="page-header"><h1>Add  <small>FAQ Information </small></h1></div> 

 


<form action="faq.php?<?php echo $qstr; ?>" method="post" id="save" enctype="multipart/form-data">
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
            <div class="span3"><b>Category Listing</b>:&nbsp;<span class="faded">FAQ category the question belongs to.</span></div>
             <div class="span9"><select name="category_id" style="width:350px;">
                    <option value="0">Select FAQ Category </option>
                    <?php
                    $sql='SELECT category_id, name, ispublic FROM '.FAQ_CATEGORY_TABLE;
                    if(($res=db_query($sql)) && db_num_rows($res)) {
                        while($row=db_fetch_array($res)) {
                            echo sprintf('<option value="%d" %s>%s (%s)</option>',
                                    $row['category_id'],
                                    (($info['category_id']==$row['category_id'])?'selected="selected"':''),
                                    $row['name'],
                                    ($info['ispublic']?'Public':'Internal'));
                        }
                    }
                   ?>
                </select>
                <span class="error">*&nbsp;<?php echo $errors['category_id']; ?></span></div>
        </div>
        
        <div class="row-form clearfix">
            <div class="span3"><b>Listing Type</b>:</div>
             <div class="span9">
              <input type="radio" name="ispublished" value="1" <?php echo $info['ispublished']?'checked="checked"':''; ?>>Public (publish)
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="ispublished" value="0" <?php echo !$info['ispublished']?'checked="checked"':''; ?>>Internal (private)
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['ispublished']; ?></span></div>
        </div>
        
        <div class="row-form clearfix">
            <div class="span3">Question:</div>
             <div class="span9">
             <input type="text" size="70" name="question" value="<?php echo $info['question']; ?>">
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['question']; ?></span></div>
        </div>
        
     
        <div class="row-form clearfix">
            <div class="span3"><b>Answer</b>&nbsp;<font class="error">*&nbsp;<?php echo $errors['answer']; ?></font></div>
             <div class="span9">
             <textarea name="answer" cols="21" rows="12" style="width:98%;" class="richtext"><?php echo $info['answer']; ?></textarea>
             </div>
                    </div>                                
                    
                
        </div>                   
    </div>
</div>
<div class="row-fluid">  
<div class="span12">
    
    <div class="footer tar">
    <input type="submit" name="submit" value="<?php echo $submit_text; ?>" class="btn">
    <input type="reset"  name="reset"  value="Reset Changes" class="btn">
    <input type="button" name="cancel" value="Cancel" onclick='window.location.href="faq.php?<?php echo $qstr; ?>"' class="btn">
    </div>  

</div>
</div>
 <?php /*?><table class="form_table" width="940" border="0" cellspacing="0" cellpadding="2">
    <thead>
        <tr>
            <th colspan="2">
                <h4><?php echo $title; ?></h4>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th colspan="2">
                <em>FAQ Information</em>
            </th>
        </tr>
        <tr>
            <td colspan=2>
                <div style="padding-top:3px;"><b>Question</b>&nbsp;<span class="error">*&nbsp;<?php echo $errors['question']; ?></span></div>
                    <input type="text" size="70" name="question" value="<?php echo $info['question']; ?>">
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <div><b>Category Listing</b>:&nbsp;<span class="faded">FAQ category the question belongs to.</span></div>
                <select name="category_id" style="width:350px;">
                    <option value="0">Select FAQ Category </option>
                    <?php
                    $sql='SELECT category_id, name, ispublic FROM '.FAQ_CATEGORY_TABLE;
                    if(($res=db_query($sql)) && db_num_rows($res)) {
                        while($row=db_fetch_array($res)) {
                            echo sprintf('<option value="%d" %s>%s (%s)</option>',
                                    $row['category_id'],
                                    (($info['category_id']==$row['category_id'])?'selected="selected"':''),
                                    $row['name'],
                                    ($info['ispublic']?'Public':'Internal'));
                        }
                    }
                   ?>
                </select>
                <span class="error">*&nbsp;<?php echo $errors['category_id']; ?></span>
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <div><b>Listing Type</b>:&nbsp;
                    <span class="faded">Published questions are listed on public knowledgebase if the parent category is public.</span></div>
                <input type="radio" name="ispublished" value="1" <?php echo $info['ispublished']?'checked="checked"':''; ?>>Public (publish)
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="ispublished" value="0" <?php echo !$info['ispublished']?'checked="checked"':''; ?>>Internal (private)
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['ispublished']; ?></span>
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <div>
                    <b>Answer</b>&nbsp;<font class="error">*&nbsp;<?php echo $errors['answer']; ?></font></div>
                    <textarea name="answer" cols="21" rows="12" style="width:98%;" class="richtext"><?php echo $info['answer']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <div><b>Attachments</b> (optional) <font class="error">&nbsp;<?php echo $errors['files']; ?></font></div>
                <?php
                if($faq && ($files=$faq->getAttachments())) {
                    echo '<div class="faq_attachments"><span class="faded">Uncheck to delete the attachment on submit</span><br>';
                    foreach($files as $file) {
                        $hash=$file['hash'].md5($file['id'].session_id().$file['hash']);
                        echo sprintf('<label><input type="checkbox" name="files[]" id="f%d" value="%d" checked="checked">
                                      <a href="file.php?h=%s">%s</a>&nbsp;&nbsp;</label>&nbsp;',
                                      $file['id'], $file['id'], $hash, $file['name']);
                    }
                    echo '</div><br>';
                }
                ?>
                <div class="faded">Select files to upload.</div>
                <div class="uploads"></div>
                <div class="file_input">
                    <input type="file" class="multifile" name="attachments[]" size="30" value="" />
                </div>
            </td>
        </tr>
        <?php
        $sql='SELECT ht.topic_id, CONCAT_WS(" / ", pht.topic, ht.topic) as name '
            .' FROM '.TOPIC_TABLE.' ht '
            .' LEFT JOIN '.TOPIC_TABLE.' pht ON(pht.topic_id=ht.topic_pid) ';
        if(($res=db_query($sql)) && db_num_rows($res)) { ?>
        <tr>
            <th colspan="2">
                <em><strong>Help Topics</strong>: Check all help topics related to this FAQ.</em>
            </th>
        </tr>
        <tr><td>
            <?php
            while(list($topicId,$topic)=db_fetch_row($res)) {
                echo sprintf('<input type="checkbox" name="topics[]" value="%d" %s>%s<br>',
                        $topicId,
                        (($info['topics'] && in_array($topicId,$info['topics']))?'checked="checked"':''),
                        $topic);
            }
             ?>
            </td>
        </tr>
        <?php
        } ?>
        <tr>
            <th colspan="2">
                <em><strong>Internal Notes</strong>: &nbsp;</em>
            </th>
        </tr>
        <tr>
            <td colspan=2>
                <textarea name="notes" cols="21" rows="8" style="width: 80%;"><?php echo $info['notes']; ?></textarea>
            </td>
        </tr>
    </tbody>
</table>
<p style="padding-left:225px;">
    <input type="submit" name="submit" value="<?php echo $submit_text; ?>">
    <input type="reset"  name="reset"  value="Reset">
    <input type="button" name="cancel" value="Cancel" onclick='window.location.href="faq.php?<?php echo $qstr; ?>"'>
</p><?php */?>
</form>
