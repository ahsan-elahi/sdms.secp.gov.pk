<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');
$info=array();

$qstr='';
if($topic && $_REQUEST['a']!='add') {
    $title='Update Help Topic';
    $action='update';
    $submit_text='Save Changes';
    $info=$topic->getInfo();
    $info['id']=$topic->getId();
    $info['pid']=$topic->getPid();
    $qstr.='&id='.$topic->getId();
} else {
    $title='Add New Help Topic';
    $action='create';
    $submit_text='Add Topic';
    $info['isactive']=isset($info['isactive'])?$info['isactive']:1;
    $info['ispublic']=isset($info['ispublic'])?$info['ispublic']:1;
	$info['isnature']=isset($info['isnature'])?$info['isnature']:0;
	
    $qstr.='&a='.$_REQUEST['a'];
}

$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
?>
<script>
function get_subcategory(cat_id){
	var tiers = parseInt($("#tiers").val());
	$("#select_" + tiers).attr('name', 'pid');
	var next_tiers = tiers+1;   
	$("#"+tiers+"_tier").after('<li id="'+next_tiers+'_tier"></li>');                               
	
	$("#tiers").val(next_tiers);
	$.ajax({
	url:"get_sub_category_admin.php",
	data: "cat_id="+cat_id+"&next_tiers="+next_tiers+"",
	success: function(msg){
	$("#"+next_tiers+"_tier").html(msg);
}
});
}
</script>
<div class="page-header"><h1>Complaint  <small>Topic </small></h1></div>  
<form action="helptopics.php?<?php echo $qstr; ?>" method="post" id="save">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="<?php echo $action; ?>">
 <input type="hidden" name="a" value="<?php echo Format::htmlchars($_REQUEST['a']); ?>">
 <input type="hidden" name="id" value="<?php echo $info['id']; ?>">         
<div class="row-fluid">
 <!--Left section-->
<div class="span6">
<div class="block-fluid ucard">
            <div class="info">                                                                
                <ul class="rows">
                    <li class="heading"><div class="isw-users"></div><?php echo $title; ?>&nbsp;&nbsp;&nbsp;<em>(Complaint Topic Information)</em></li>
                    <li>
                        <div class="title">Topic:</div>
                        <div class="text"><input type="text" size="30" name="topic" value="<?php echo $info['topic']; ?>">
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['topic']; ?></span></div>
                    </li> 
                    <li>
                        <div class="title">Status:</div>
                        <div class="text">
                        <label class="checkbox inline"><input type="radio" name="isactive" value="1" <?php echo $info['isactive']?'checked="checked"':''; ?>></label>Active
                <label class="checkbox inline"><input type="radio" name="isactive" value="0" <?php echo !$info['isactive']?'checked="checked"':''; ?>></label>Disabled
                &nbsp;<span class="error">*&nbsp;</span>  </div>
                    </li>
                    <li>
                        <div class="title">Type:</div>
                        <div class="text">
                        <label class="checkbox inline"><input type="radio" name="ispublic" value="1" <?php echo $info['ispublic']?'checked="checked"':''; ?>></label>Public
                <label class="checkbox inline"><input type="radio" name="ispublic" value="0" <?php echo !$info['ispublic']?'checked="checked"':''; ?>></label>Private/Internal
                &nbsp;<span class="error">*&nbsp;</span></div>
                    </li>
                    <li>
                        <div class="title">Nature:</div>
                        <div class="text">
                        <label class="checkbox inline">
                        <input type="radio" name="isnature" value="0" <?php echo !$info['isnature']?'checked="checked"':''; ?>></label>Complaint
                		<label class="checkbox inline">
                		<input type="radio" name="isnature" value="1" <?php echo $info['isnature']?'checked="checked"':''; ?>></label>Query
                &nbsp;<span class="error">*&nbsp;</span></div>
                    </li>
                    <?php if($_REQUEST['id']==''){ ?>
                    <li  id="1_tier">
                        <div class="title">Parent Categoy:</div>
                        <div class="text"><select onChange="get_subcategory(this.value);" id="select_1">
                    <option value="">&mdash; Select Parent Category &mdash;</option>
                    <?php
                   /* $sql='SELECT topic_id, topic FROM '.TOPIC_TABLE
                        .' WHERE topic_pid=0 '
                        .' ORDER by topic';*/
						$sql='SELECT t.topic_id, t.topic,d.dept_name FROM sdms_help_topic t,sdms_department d WHERE t.`dept_id`=d.`dept_id` AND t.topic_pid=0 ORDER by topic';
                    if(($res=db_query($sql)) && db_num_rows($res)) {
                        while(list($id, $name,$dpname)=db_fetch_row($res)) {
                            echo sprintf('<option value="%d" %s>%s (%s)</option>',
                                    $id, (($info['pid'] && $id==$info['pid'])?'selected="selected"':'') ,$name,$dpname);
                        }
                    }
                    ?>
                </select> (<em>optional</em>)
                &nbsp;<span class="error">&nbsp;<?php echo $errors['pid']; ?></span></div>
                    </li>
                    <input type="hidden" value="1" name="tiers" id="tiers">    
                    <?php }else{ ?>
						<input type="hidden" name="pid" value="<?php echo $info['pid']; ?>" >
					<?php	} ?>
                </ul>                                                      
           </div>                        
        </div>
</div>
 <!--Right section-->

<div class="span6">
    <div class="block-fluid ucard">
       <div class="info">
         <ul class="rows">
                    <li class="heading"><div class="isw-users"></div>New Complaint options</li>
                    <li>
                        <div class="title">Priority:</div>
                        <div class="text"><select name="priority_id">
                    <option value="">&mdash; Select Priority &mdash;</option>
                    <?php
                    $sql='SELECT priority_id,priority_desc FROM '.PRIORITY_TABLE.' pri ORDER by priority_urgency DESC';
                    if(($res=db_query($sql)) && db_num_rows($res)){
                        while(list($id,$name)=db_fetch_row($res)){
                            $selected=($info['priority_id'] && $id==$info['priority_id'])?'selected="selected"':'';
                            echo sprintf('<option value="%d" %s>%s</option>',$id,$selected,$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['priority_id']; ?></span></div>
                    </li> 
                    <li>
                        <div class="title">Department:</div>
                        <div class="text"><select name="dept_id">
                    <option value="">&mdash; Select Department &mdash;</option>
                    <?php
                    $sql='SELECT dept_id,dept_name FROM '.DEPT_TABLE.' dept ORDER by dept_name';
                    if(($res=db_query($sql)) && db_num_rows($res)){
                        while(list($id,$name)=db_fetch_row($res)){
                            $selected=($info['dept_id'] && $id==$info['dept_id'])?'selected="selected"':'';
                            echo sprintf('<option value="%d" %s>%s</option>',$id,$selected,$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['dept_id']; ?></span>
                  </div>
                    </li>
                    <li>
                        <div class="title">SLA Plan:</div>
                        <div class="text"><select name="sla_id">
                    <option value="0">&mdash; Department's Default &mdash;</option>
                    <?php
                    if($slas=SLA::getSLAs()) {
                        foreach($slas as $id =>$name) {
                            echo sprintf('<option value="%d" %s>%s</option>',
                                    $id, ($info['sla_id']==$id)?'selected="selected"':'',$name);
                        }
                    }
                    ?>
                </select>
                &nbsp;<span class="error">&nbsp;<?php echo $errors['sla_id']; ?></span>
                <em>(Overwrites department's SLA)</em></div>
                    </li>
                    <li>
                        <div class="title">Auto-assign To:</div>
                        <div class="text"><select name="assign">
                    <option value="0">&mdash; Unassigned &mdash;</option>
                                
    
                    <?php
                    
                                
                    $sql=' SELECT staff_id,CONCAT_WS(", ",firstname,lastname) as name '.
                         ' FROM '.STAFF_TABLE.' WHERE isactive=1 ORDER BY name';
                                
                    if(($res=db_query($sql)) && db_num_rows($res)){
                        echo '<OPTGROUP label="Staff Members">';
                        while (list($id,$name) = db_fetch_row($res)){
                            $k="s$id";
                            $selected = ($info['assign']==$k || $info['staff_id']==$id)?'selected="selected"':'';
                            ?>
                            <option value="<?php echo $k; ?>"<?php echo $selected; ?>><?php echo $name; ?></option>
                            
                        <?php }
                        echo '</OPTGROUP>';
                        
                    }
					
                    /*$sql='SELECT team_id, name FROM '.TEAM_TABLE.' WHERE isenabled=1';
                    if(($res=db_query($sql)) && db_num_rows($res)){
                        echo '<OPTGROUP label="Teams">';
                        while (list($id,$name) = db_fetch_row($res)){
                            $k="t$id";
                            $selected = ($info['assign']==$k || $info['team_id']==$id)?'selected="selected"':'';
                            ?>
                            <option value="<?php echo $k; ?>"<?php echo $selected; ?>><?php echo $name; ?></option>
                        <?php
                        }
                        echo '</OPTGROUP>';
                    }*/
                    ?>
                </select>
                &nbsp;<span class="error">&nbsp;<?php echo $errors['assign']; ?></span></div>
                    </li> 
                    <li>
                        <div class="title">Complaint auto-response:</div>
                        <div class="text">
                        <input type="checkbox" name="noautoresp" value="1" <?php echo $info['noautoresp']?'checked="checked"':''; ?> >
                    <strong>Disable</strong> new Complaint auto-response for this topic (Overwrites Dept. settings).
                    </div>
                    </li>                      
                </ul>
                </div>
    </div>
</div>
  
</div>

<div class="row-fluid">
<div class="span12">
    <div class="head clearfix">
        <div class="isw-documents"></div>
        <h1>Admin Notes</h1>
    </div>
    <div class="block-fluid">                        

        <div class="row-form clearfix">
            <div class="span12"><em><strong>Admin Notes</strong>: Internal notes about the help topic.&nbsp;</em></div>
        </div>   
        
        <div class="row-form clearfix">
            <div class="span12"><textarea name="notes" cols="21" rows="8" style="width: 80%;"><?php echo $info['notes']; ?></textarea></div>
             
        </div>

</div>
</div>
</div>

<div class="row-fluid">  
<div class="span12">
    
    <div class="footer tar">
    <input type="submit" name="submit" value="<?php echo $submit_text; ?>" class="btn">
    <input type="reset"  name="reset"  value="Reset" class="btn">
    <input type="button" name="cancel" value="Cancel" onclick='window.location.href="helptopics.php"' class="btn">
    </div>  

</div>
</div>
</form>
<div class="dr"><span></span></div>
</div></div>
