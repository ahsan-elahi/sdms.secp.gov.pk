<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');
$info=array();
$qstr='';
if($team && $_REQUEST['a']!='add'){
    //Editing Team
    $title='Update Team';
    $action='update';
    $submit_text='Save Changes';
    $info=$team->getInfo();
    $info['id']=$team->getId();
    $qstr.='&id='.$team->getId();
}else {
    $title='Add New Team';
    $action='create';
    $submit_text='Create Team';
    $info['isenabled']=1;
    $info['noalerts']=0;
    $qstr.='&a='.$_REQUEST['a'];
}
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
?>

<form action="teams.php?<?php echo $qstr; ?>" method="post" id="save">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="<?php echo $action; ?>">
 <input type="hidden" name="a" value="<?php echo Format::htmlchars($_REQUEST['a']); ?>">
 <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
 <div class="page-header"><h1>Team <small></small></h1></div> 
<div class="span6">
<div class="block-fluid ucard">
<div class="info">   
<ul class="rows">
<li class="heading"><div class="isw-users"></div><?php echo $title; ?><em style="font-size:9px;">Team Information Disabled team won't be availabe for ticket assignment or alerts.</em></li>
 


<li>
            <div class="title"> Name:</div>
            <div class="text"> <input type="text" size="30" name="name" value="<?php echo $info['name']; ?>">
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['name']; ?></span>
            </div>
            </li> 
            
            <li>
            <div class="title"> Status:</div>
            <div class="text">
             <label class="checkbox inline"><input type="radio" name="isenabled" value="1" <?php echo $info['isenabled']?'checked="checked"':''; ?>>Active</label>
                <label class="checkbox inline"><input type="radio" name="isenabled" value="0" <?php echo !$info['isenabled']?'checked="checked"':''; ?>>Disabled</label>
            </div>
            </li> 
            
            <li>
            <div class="title">Team Lead:</div>
            <div class="text"><select name="lead_id">
                    <option value="0">&mdash; None &mdash;</option>
                    <option value="" disabled="disabled">Select Team Lead (Optional)</option>
                    <?php
                    if($team && ($members=$team->getMembers())){
                        foreach($members as $k=>$staff){
                            $selected=($info['lead_id'] && $staff->getId()==$info['lead_id'])?'selected="selected"':'';
                            echo sprintf('<option value="%d" %s>%s</option>',$staff->getId(),$selected,$staff->getName());
                        }
                    }
                    ?>
                </select>
                 &nbsp;<span class="error">&nbsp;<?php echo $errors['lead_id']; ?></span>
            </div>
            </li> 
         
         
          <li>
            <div class="title">Assignment Alerts:</div>
            <div class="text"><input type="checkbox" name="noalerts" value="1" <?php echo $info['noalerts']?'checked="checked"':''; ?> >&nbsp;
                <strong>Disable</strong> assignment alerts for this team (<i>overwrite global settings.</i>) 
            </div>
            </li> 
   
       
        <?php
        if($team && ($members=$team->getMembers())){ ?>
        <tr>
            <th colspan="2">
                <em><strong>Team Members</strong>: To add additional members go to target member's profile&nbsp;</em>
            </th>
        </tr>
        <?php
            foreach($members as $k=>$staff){
                echo sprintf('<tr><td colspan=2><span style="width:350px;padding-left:5px; display:block; float:left;">
                            <b><a href="staff.php?id=%d">%s</a></span></b>
                            &nbsp;<input type="checkbox" name="remove[]" value="%d"><i>Remove</i></td></tr>',
                          $staff->getId(),$staff->getName(),$staff->getId());
               
            
            }
        } ?>
        
         <li>
            <div class="title">Admin Notes</div>
            <div class="text">Internal notes viewable by all admins.
            </div>
            </li> 
   
         <li>
            <div class="title"></div>
            <div class="text"> <textarea name="notes" cols="21" rows="8" style="width: 80%;"><?php echo $info['notes']; ?></textarea>
            </div>
            </li> 
            </ul></div></div></div></div></div>
<p style=" padding-left:418px;padding-bottom:2px; margin-top:90px;" >
    <input class="btn" type="submit" name="submit" value="<?php echo $submit_text; ?>">
    <input class="btn"  type="reset"  name="reset"  value="Reset">
    <input class="btn"  type="button" name="cancel" value="Cancel" onclick='window.location.href="teams.php"'>
</p>
</form>
