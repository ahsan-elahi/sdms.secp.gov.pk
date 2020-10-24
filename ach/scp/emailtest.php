<?php require('admin.inc.php');
include_once(INCLUDE_DIR.'class.email.php');
include_once(INCLUDE_DIR.'class.csrf.php');
$info=array();
$info['subj']='Test email';

if($_POST){
    $errors=array();
    $email=null;
    if(!$_POST['email_id'] || !($email=Email::lookup($_POST['email_id'])))
        $errors['email_id']='Select from email address';

    if(!$_POST['email'] || !Validator::is_email($_POST['email']))
        $errors['email']='To email address required';

    if(!$_POST['subj'])
        $errors['subj']='Subject required';

    if(!$_POST['message'])
        $errors['message']='Message required';
    if(!$errors && $email){
        if($email->send($_POST['email'],$_POST['subj'],$_POST['message']))
            $msg='Test email sent successfully to '.Format::htmlchars($_POST['email']);
        else
            $errors['err']='Error sending email - try again.';
    }elseif($errors['err']){
        $errors['err']='Error sending email - try again.';
    }

}
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
$nav->setTabActive('emails');
require(STAFFINC_DIR.'header.inc.php');
?>

<div class="page-header"><h1>Test<small>Outgoing Email</small></h1></div>  
<form action="emailtest.php" method="post" id="save">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="<?php echo $action; ?>">              
                <div class="row-fluid">

                    <div class="span12">
                        <div class="head clearfix">
                            <div class="isw-documents"></div>
                            <h1>Test Outgoing Email</h1><em>Emails delivery depends on your server settings (php.ini) and/or email SMTP configuration.</em>
                        </div>
                        <div class="block-fluid">                        

                            <div class="row-form clearfix">
                                <div class="span3">From:</div>
                                <div class="span9"><select name="email_id">
                    <option value="0">&mdash; Select FROM Email &mdash;</option>
                    <?php
                    $sql='SELECT email_id,email,name,smtp_active FROM '.EMAIL_TABLE.' email ORDER by name';
                    if(($res=db_query($sql)) && db_num_rows($res)){
                        while(list($id,$email,$name,$smtp)=db_fetch_row($res)){
                            $selected=($info['email_id'] && $id==$info['email_id'])?'selected="selected"':'';
                            if($name)
                                $email=Format::htmlchars("$name <$email>");
                            if($smtp)
                                $email.=' (SMTP)';

                            echo sprintf('<option value="%d" %s>%s</option>',$id,$selected,$email);
                        }
                    }
                    ?>
                </select>
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['email_id']; ?></span></div>
                            </div>
                            <div class="row-form clearfix">
                                <div class="span3">To:</div>
                                <div class="span9"><input type="text" size="60" name="email" value="<?php echo $info['email']; ?>">
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['email']; ?></span></div>
                            </div>
                            <div class="row-form clearfix">
                                <div class="span3">Subject:</div>
                                <div class="span9"><input type="text" size="60" name="subj" value="<?php echo $info['subj']; ?>">
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['email']; ?></span></div>
                            </div>
                            <div class="row-form clearfix">
                                <div class="span3">Message:</div>
                                <div class="span9"><em> email message to send.</em>&nbsp;<span class="error">*&nbsp;<?php echo $errors['message']; ?></span><br><textarea name="message" cols="21" rows="10" style="width: 90%;"><?php echo $info['message']; ?></textarea></div>
                            </div>                          
                        </div>
						
                    </div>
                </div>
                <div class="row-fluid">
<div class="footer tar">
    
                        <input type="submit" name="submit" value="Send Message" class="btn">
                        <input type="reset"  name="reset"  value="Reset" class="btn">
                        <input type="button" name="cancel" value="Cancel" onclick='window.location.href="emails.php"' class="btn">
                        </div>
                    <div class="span12">
                    </div>
                    </div>
</form>
<div class="dr"><span></span></div>
   </div><!--WorkPlace End-->  
   </div> 

<?php
include(STAFFINC_DIR.'footer.inc.php');
?>
