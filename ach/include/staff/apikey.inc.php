<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');
$info=array();
$qstr='';
if($api && $_REQUEST['a']!='add'){
    $title='Update API Key';
    $action='update';
    $submit_text='Save Changes';
    $info=$api->getHashtable();
    $qstr.='&id='.$api->getId();
}else {
    $title='Add New API Key';
    $action='add';
    $submit_text='Add Key';
    $info['isactive']=isset($info['isactive'])?$info['isactive']:1;
    $qstr.='&a='.urlencode($_REQUEST['a']);
}
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
?>
<div class="page-header"><h1>API Key <small>Add New SLA Plan</small></h1></div>
<form action="apikeys.php?<?php echo $qstr; ?>" method="post" id="save">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="<?php echo $action; ?>">
 <input type="hidden" name="a" value="<?php echo Format::htmlchars($_REQUEST['a']); ?>">
 <input type="hidden" name="id" value="<?php echo $info['id']; ?>">

<!--section divided into row span-->
<div class="row-fluid">         <!--main row-fluid div start-->

<!--first section start-->
<div class="span6">
<div class="block-fluid ucard">
            <div class="info">                                                                
                <ul class="rows">
                   <!--header start-->
                    <li class="heading"><div class="isw-users"></div><em><strong><?php echo $title; ?>:</strong>&nbsp;&nbsp;(API Key is auto-generated. Delete and re-add to change the key)</em></li>
                   <!-- header end-->
                    <li>
                        <div class="title">Status:</div>
                        <div class="text">
                        <label class="checkbox inline"><input type="radio" name="isactive" value="1" <?php echo $info['isactive']?'checked="checked"':''; ?>></label><strong>Active</strong>
                <label class="checkbox inline"><input type="radio" name="isactive" value="0" <?php echo !$info['isactive']?'checked="checked"':''; ?>></label>Disabled
                &nbsp;<span class="error">*&nbsp;</span>
                        </div>
                    </li>
                    <?php if($api){ ?> 
                    <li>
                        <div class="title">IP Address:</div>
                        <div class="text"> <?php echo $api->getIPAddr(); ?></div>
                    </li>
                    <li>
                        <div class="title">API Key:</div>
                        <div class="text"><?php echo $api->getKey(); ?> &nbsp;</div>
                    </li>
                    <?php }else{ ?>
                    <li>
                        <div class="title">IP Address:</div>
                        <div class="text">
                <input type="text" size="30" name="ipaddr" value="<?php echo $info['ipaddr']; ?>">
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['ipaddr']; ?></span>
                        </div>
                    </li>
                     <?php } ?>
                                    
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
                    <li class="heading"><div class="isw-users"></div><em><strong>Services:</strong>&nbsp;&nbsp;(Check applicable API services enabled for the key)</em></li>
                   <!-- header end-->
                    <li>
                        <!--<div class="title">label here:</div>-->
                        <div class="text">
                        <label>
                    <input type="checkbox" name="can_create_tickets" value="1" <?php echo $info['can_create_tickets']?'checked="checked"':''; ?> >
                    Can Create Complaints <em>(XML/JSON/EMAIL)</em>
                        </label>
                        </div>
                    </li>
                     
                    <li>
                        <!--<div class="title">label here:</div>-->
                        <div class="text">
                        <label>
                    <input type="checkbox" name="can_exec_cron" value="1" <?php echo $info[                    'can_exec_cron']?'checked="checked"':''; ?> >
                    Can Execute Cron
                        </label>
                        </div>
                    </li>
                    
                    
                                         
                </ul>                                                      
           </div>                        
        </div>
</div>
<!--second section End-->

</div>                            <!--main row-fluid div End-->

<div class="row-fluid">           <!--main row-fluid div start-->

<!--third section start-->
<div class="span12">
<div class="block-fluid ucard">
            <div class="info">                                                                
                <ul class="rows">
                   <!--header start-->
                    <li class="heading"><div class="isw-users"></div><em><strong>Admin Notes</strong>:&nbsp;&nbsp;(Internal notes)</em></li>
                   <!-- header end-->
                    <li>
                        <div class="title">Internal Notes:</div>
                        <div class="text">
                        <textarea name="notes" cols="21" rows="8" style="width: 80%;"><?php echo                        $info['notes']; ?></textarea>
                        </div>
                    </li>
                                    
                </ul>                                                      
           </div>                        
        </div>
</div>
<!--third section End-->

</div>                             <!--main row-fluid div End-->

<!--footer start-->
<div class="row-fluid">  
<div class="span12">
    
    <div class="footer tar">
  <p style="padding-left:225px;">
    <input type="submit" name="submit" value="<?php echo $submit_text; ?>" class="btn">
    <input type="reset"  name="reset"  value="Reset" class="btn">
    <input type="button" name="cancel" value="Cancel" onclick='window.location.href="apikeys.php"' class="btn">
   </p>
     </div>  

</div>
</div>
<!--footer end-->

</form>
</div>
</div>