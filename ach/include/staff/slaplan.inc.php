<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');
$info=array();
$qstr='';
if($sla && $_REQUEST['a']!='add'){
    $title='Update SLA Plan';
    $action='update';
    $submit_text='Save Changes';
    $info=$sla->getInfo();
    $info['id']=$sla->getId();
    $qstr.='&id='.$sla->getId();
}else {
    $title='Add New SLA Plan';
    $action='add';
    $submit_text='Add Plan';
    $info['isactive']=isset($info['isactive'])?$info['isactive']:1;
    $info['enable_priority_escalation']=isset($info['enable_priority_escalation'])?$info['enable_priority_escalation']:1;
    $info['disable_overdue_alerts']=isset($info['disable_overdue_alerts'])?$info['disable_overdue_alerts']:1;
    $qstr.='&a='.urlencode($_REQUEST['a']);
}
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
?>

<div class="page-header"><h1>Service Level Agreement <small>Add New SLA Plan</small></h1></div>
<form action="slas.php?<?php echo $qstr; ?>" method="post" id="save">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="<?php echo $action; ?>">
 <input type="hidden" name="a" value="<?php echo Format::htmlchars($_REQUEST['a']); ?>">
 <input type="hidden" name="id" value="<?php echo $info['id']; ?>">

<div class="row-fluid">
<!--first section--> 
<div class="span12">
      <div class="block-fluid ucard">
            <div class="info">                                                                
                <ul class="rows">
                   <!--header start-->
                    <li class="heading">
                    <div class="isw-users"></div><em><strong><?php echo $title; ?>:</strong>&nbsp;&nbsp;(Tickets are marked overdue on grace period violation)</em>
                    </li>
                   <!-- header end-->
                   
                    <li>
                        <div class="title"> Name:</div>
                       <div class="text" style="margin-left:170px;">
                <input type="text" size="30" name="name" value="<?php echo $info['name']; ?>">
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['name']; ?></span>
                       </div>
                    </li>
                    
                    <li>
                    <div class="title">Priority:</div>
                    <div class="text" style="margin-left:170px;">
                    <select name="priorityId">
                    <option value="0" selected >&mdash; System Default &mdash;</option>
                    <?php
                    if($priorities=Priority::getPriorities()) {
                    foreach($priorities as $id =>$name) {
                    echo sprintf('<option value="%d" %s>%s</option>',$id, ($info['priority_id']==$id)?'selected="selected"':'',$name);
                    } } ?>
                    </select>
                    &nbsp;<font class="error">&nbsp;<?php echo $errors['priorityId']; ?></font></div>
                    </li>
                     
                    <li>
                        <div class="title"> Grace Period:</div>
                        <div class="text"  style="margin-left:170px;">
                <input type="text" size="10" name="grace_period" value="<?php echo $info[                'grace_period']; ?>"><em>( in hours )</em>
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['grace_period']; ?></span>
                        </div>
                    </li>                 
                    
                    <li>
                        <div class="title">Status:</div>
                        <div class="text"  style="margin-left:170px;">
               <label class="checkbox inline"><input type="radio" name="isactive" value="1" <?php echo $info['isactive']?'checked="checked"':''; ?>></label><strong>Active</strong>
               <label class="checkbox inline"><input type="radio" name="isactive" value="0" <?php echo !$info['isactive']?'checked="checked"':''; ?>></label>Disabled
                &nbsp;<span class="error">*&nbsp;</span>
                        </div>
                    </li>                    
                    
                    <li>
                        <div class="title">Priority Escalation:</div>
                        <div class="text"  style="margin-left:170px;">
              <input type="checkbox" name="enable_priority_escalation" value="1" <?php echo $info[              'enable_priority_escalation']?'checked="checked"':''; ?> >
                    <strong>Enable</strong> priority escalation on overdue tickets.
                        </div>
                    </li>
                    
                    <li>
                        <div class="title" style="width: 130px;"> Ticket Overdue Alerts:</div>
                        <div class="text"  style="margin-left:170px;">
                        <input type="checkbox" name="disable_overdue_alerts" value="1" <?php echo                        $info['disable_overdue_alerts']?'checked="checked"':''; ?> >
                        <strong>Disable</strong> overdue alerts notices. <em>(Overwrite global                        setting)</em>
                        </div>
                    </li>                     
                </ul>                                                      
            </div>                        
      </div>
      
</div>
 </div>
 <div class="row-fluid">
<!--second section-->
<div class="span12">
      <div class="block-fluid ucard">
            <div class="info">                                                                
                <ul class="rows">
                   <!--header start-->
                    <li class="heading"><div class="isw-users"></div><em><strong>Admin Notes:</strong>:&nbsp;&nbsp;&nbsp;(Internal notes)</em>
                    
                    </li>
                   <!-- header end-->
                   
                    <li>
                        <div class="title"> Internal notes:</div>
                        <div class="text">
               <textarea name="notes" cols="21" rows="8" style="width: 80%;"><?php echo $info['notes']; ?></textarea>
                       </div>
                    </li>
                     
                </ul>                                                      
            </div>                        
      </div>
</div>

</div>
 
<!--footer-->
<div class="row-fluid">  
<div class="span12">
 
  <div class="footer tar">
  <!--<p style="padding-left:225px;">-->
    <input type="submit" name="submit" value="<?php echo $submit_text; ?>" class="btn">
    <input type="reset"  name="reset"  value="Reset" class="btn">
    <input type="button" name="cancel" value="Cancel" onclick='window.location.href="slas.php"' class="btn">
  <!--</p>-->
  </div>  

</div>
</div>

</form>

</div>
</div>

