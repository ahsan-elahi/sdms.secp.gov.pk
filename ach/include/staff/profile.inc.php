<?php
if(!defined('OSTSTAFFINC') || !$staff || !$thisstaff) die('Access Denied');

$info=$staff->getInfo();

$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);

$info['id']=$staff->getId();
?>
<div class="page-header"><h1>My Account<small>Profile</small></h1></div> 
 <form action="profile.php" method="post" id="save" autocomplete="off">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="update">
 <input type="hidden" name="id" value="<?php echo $info['id']; ?>">              
<!--section divided into row span-->
<div class="row-fluid">  <!--main row-fluid div start-->
<!--first section start-->
<div class="span6">
        <div class="block-fluid ucard">
            <div class="info">                                                                
                <ul class="rows">
                   <!--header start-->
                    <li class="heading"><div class="isw-users"></div><em><strong>Account Information</strong>: Contact information.</em></li>
                   <!-- header end-->
                    <li>
                        <div class="title">Username:</div>
                        <div class="text"><?php echo $staff->getUserName(); ?></div>
                    </li> 
                    <li>
                        <div class="title">First Name:</div>
                        <div class="text">
                        <input type="text" size="34" name="firstname" value="<?php echo $info['firstname']; ?>">
    					&nbsp;<span class="error">*&nbsp;<?php echo $errors['firstname']; ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="title">Last Name:</div>
                        <div class="text">
                        <input type="text" size="34" name="lastname" value="<?php echo $info['lastname']; ?>">
						&nbsp;<span class="error">*&nbsp;<?php echo $errors['lastname']; ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="title">Email Address:</div>
                        <div class="text">
                        <input type="text" size="34" name="email" value="<?php echo $info['email']; ?>">

    &nbsp;<span class="error">*&nbsp;<?php echo $errors['email']; ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="title">Phone Number:</div>
                        <div class="text">
                        <input type="text" size="22" name="phone" value="<?php echo $info['phone']; ?>">

    &nbsp;<span class="error">&nbsp;<?php echo $errors['phone']; ?></span>

    Ext <input type="text" size="5" name="phone_ext" value="<?php echo $info['phone_ext']; ?>">

    &nbsp;<span class="error">&nbsp;<?php echo $errors['phone_ext']; ?></span>
                        
                        </div>
                    </li>
                    <li>
                        <div class="title">Mobile Number:</div>
                        <div class="text">
                        <input type="text" size="34" name="mobile" value="<?php echo $info['mobile']; ?>">

    &nbsp;<span class="error">*&nbsp;<?php echo $errors['email']; ?></span>
                        
                        </div>
                    </li>
                    
                    <li>
                        <div class="title">Email:</div>
                        <div class="text">
                        <input type="text" size="50" name="email" id="email" class="typeahead" value="<?php echo $info['email']; ?>"

    autocomplete="off" autocorrect="off" autocapitalize="off">

    <span class="error">&nbsp;<?php echo $errors['email']; ?></span><br />

    <?php 

    if($cfg->notifyONNewStaffTicket()) { ?>

 

    <input type="checkbox" name="alertuser" <?php echo (!$errors || $info['alertuser'])? 'checked="checked"': ''; ?>>Send alert to user.

    <?php 

    } ?>
                        
                        
                        </div>
                    </li>
                                         
                </ul>                                                      
           </div>                        
        </div>
</div>
<!--first section End-->

<!--second section start-->
<div class="span6">
        <div class="block-fluid ucard">
            <div class="info">                                                                
                <ul class="rows">
                   <!--header start-->
                    <li class="heading"><div class="isw-users"></div><em> <strong>Password:</strong> To reset your password.</em></li>
                   <!-- header end-->
                    <li>
                        <div class="title">Current Password:</div>
                        <div class="text">
                        &nbsp;<span class="error">&nbsp;<?php echo $errors['passwd']; ?></span>

                <input type="password" size="18" name="cpasswd" value="<?php echo $info['cpasswd']; ?>">

    &nbsp;<span class="error">&nbsp;<?php echo $errors['cpasswd']; ?></span>
                        
                        </div>
                    </li> 
                    <li>
                        <div class="title">New Password:</div>
                        <div class="text">
                         <input type="password" size="18" name="passwd1" value="<?php echo $info['passwd1']; ?>" style="margin-left:10px;">

    &nbsp;<span class="error">&nbsp;<?php echo $errors['passwd1']; ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="title">Confirm New Password:</div>
                        <div class="text">
                         <input type="password" size="18" name="passwd2" value="<?php echo $info['passwd2']; ?>" style="margin-left:10px;">

    &nbsp;<span class="error">&nbsp;<?php echo $errors['passwd2']; ?></span>
                        
                        </div>
                    </li>
                    
                                         
                </ul>                                                      
           </div>                        
        </div>
</div>
<!--second section End-->

</div>    <!--main row-fluid div End-->




<!--footer-->
<div class="row-fluid">       

    <div class="span12">                        

        <div class="footer tar">

        <input type="submit" name="submit" value="Save Changes" class="btn">

        <input type="reset"  name="reset"  value="Reset Changes" class="btn">

        <input type="button" name="cancel" value="Cancel Changes" onclick='window.location.href="index.php"' class="btn">

        </div>  

    

    </div>

</div> 

                    

</form>

<div class="dr"><span></span></div>

</div></div>
