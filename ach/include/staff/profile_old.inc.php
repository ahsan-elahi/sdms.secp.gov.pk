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

    &nbsp;<span class="error">&nbsp;<?php echo $errors['email']; ?></span>

    <?php 

    if($cfg->notifyONNewStaffTicket()) { ?>

    &nbsp;&nbsp;&nbsp;

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
                    <li class="heading"><div class="isw-users"></div><em><strong>Preferences</strong>: Profile preferences and settings.</em></li>
                   <!-- header end-->
                    <li>
                        <div class="title">Time Zone:</div>
                        <div>
                        <select name="timezone_id" id="timezone_id" style="width:300px;margin-left:100px;">
    <option value="0">&mdash; Select Time Zone &mdash;</option>
    <?php

    $sql='SELECT id, offset,timezone FROM '.TIMEZONE_TABLE.' ORDER BY id';

    if(($res=db_query($sql)) && db_num_rows($res)){

        while(list($id,$offset, $tz)=db_fetch_row($res)){

            $sel=($info['timezone_id']==$id)?'selected="selected"':'';

            echo sprintf('<option value="%d" %s>GMT %s - %s</option>',$id,$sel,$offset,$tz);

        }

    }

    ?>

    </select>

    &nbsp;<span class="error">*&nbsp;<?php echo $errors['timezone_id']; ?></span>
                        
                        </div>
                    </li> 
                    <li>
                        <div class="title">Daylight Saving:</div>
                        <div class="text">
                        <input type="checkbox" name="daylight_saving" value="1" <?php echo $info['daylight_saving']?'checked="checked"':''; ?>>

    Observe daylight saving

    <em>(Current Time: <strong><?php echo Format::date($cfg->getDateTimeFormat(),Misc::gmtime(),$info['tz_offset'],$info['daylight_saving']); ?></strong>)</em>
                        </div>
                    </li>
                    <li>
                        <div class="title">Maximum Page size:</div>
                        <div class="text">
                        <select name="max_page_size" style="margin-left:45px;">

    <option value="0">&mdash; system default &mdash;</option>

    <?php

    $pagelimit=$info['max_page_size']?$info['max_page_size']:$cfg->getPageSize();

    for ($i = 5; $i <= 50; $i += 5) {

        $sel=($pagelimit==$i)?'selected="selected"':'';

         echo sprintf('<option value="%d" %s>show %s records</option>',$i,$sel,$i);

    } ?>

    </select> per page.
                        
                        </div>
                    </li>
                    <li>
                        <div class="title">Auto Refresh Rate:</div><br/>
                        <div class="text">
                        <select name="auto_refresh_rate" style="margin-left:43px; margin-top:-25px;">

    <option value="0">&mdash; disable &mdash;</option>

    <?php

    $y=1;

    for($i=1; $i <=30; $i+=$y) {

     $sel=($info['auto_refresh_rate']==$i)?'selected="selected"':'';

     echo sprintf('<option value="%d" %s>Every %s %s</option>',$i,$sel,$i,($i>1?'mins':'min'));

     if($i>9)

        $y=2;

    } ?>

    </select>

    <em>(Complaints page refresh rate in minutes.)</em>
                        
                        </div>
                    </li>
                    <li>
                        <div class="title">Default Signature:</div>
                        <div class="text">
                        <select name="default_signature_type" style="margin-left:43px; margin-top:1px;">

    <option value="none" selected="selected">&mdash; None &mdash;</option>

    <?php

    $options=array('mine'=>'My Signature','dept'=>'Dept. Signature (if set)');

    foreach($options as $k=>$v) {

      echo sprintf('<option value="%s" %s>%s</option>',

                $k,($info['default_signature_type']==$k)?'selected="selected"':'',$v);

    }

    ?>

    </select>

   <br/> <em>(You can change selection on Complaint page)</em>

    &nbsp;<span class="error">&nbsp;<?php echo $errors['default_signature_type']; ?></span>
                        
                        </div>
                    </li>
                    <li>
                        <div class="title">Default Paper Size:</div>
                        <div class="text">
                        <select name="default_paper_size" style="margin-left:43px; margin-top:1px;">

    <option value="none" selected="selected">&mdash; None &mdash;</option>

    <?php

    $options=array('Letter', 'Legal', 'A4', 'A3');

    foreach($options as $v) {

      echo sprintf('<option value="%s" %s>%s</option>',

                $v,($info['default_paper_size']==$v)?'selected="selected"':'',$v);

    }

    ?>

    </select>

    <br/><em>Paper size used when printing Complaints to PDF</em>

    &nbsp;<span class="error">&nbsp;<?php echo $errors['default_paper_size']; ?></span>
                        
                        </div>
                    </li>
                    
                    <?php

    //Show an option to show assigned Complaints to admins & managers.

    if($staff->isAdmin() || $staff->isManager()){ ?>
                    <li style="padding-bottom:15px;">
                        <div class="title">Show Assigned Complaints:</div>
                        <div class="text">
                        <input type="checkbox" name="show_assigned_tickets" <?php echo $info['show_assigned_tickets']?'checked="checked"':''; ?>>

    <em>Show assigned Complaints on open queue.</em>
                        </div>
                    </li>
                    <?php } ?>
                                         
                </ul>                                                      
           </div>                        
        </div>
</div>
<!--second section End-->

</div>    <!--main row-fluid div End-->


<!--section divided into row span-->
<div class="row-fluid">  <!--main row-fluid div start-->

<!--first section start-->
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
<!--first section End-->

<!--second section start-->
<div class="span6">
        <div class="block-fluid ucard">
            <div class="info">                                                                
                <ul class="rows">
                   <!--header start-->
                    <li class="heading"><div class="isw-users"></div><em><strong> Signature:</strong> Optional signature used on outgoing emails.</em></li>
                   <!-- header end-->
                    <li>
                        <div class="title">Signature:</div>
                        <div class="text">
                         &nbsp;<span class="error">&nbsp;<?php echo $errors['signature']; ?></span>

                <textarea name="signature" cols="21" rows="5" style="width: 60%;"><?php echo $info['signature']; ?></textarea>

    <br><em style="margin-left:-45px;">Signature is made available as a choice, on Complaint reply.</em>
                        
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
