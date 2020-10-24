<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');
$info=array();
$qstr='';
if($status && $_REQUEST['a']!='add') {
    $title='Update Status';
    $action='update';
    $submit_text='Save Changes';
    $info=$status->getInfo();
    $info['id']=$status->getId();
    $qstr.='&id='.$status->getId();
} else {
    $title='Add New Status';
    $action='create';
    $submit_text='Add Status';
    $qstr.='&a='.$_REQUEST['a'];
}
$info=Format::htmlchars(($errors && $_POST)?$_POST:$info);
?>
<script>
function get_substatus(status_id){
	$.ajax({
	url:"get_sub_status_admin.php",
	data: "status_id="+status_id,
	success: function(msg){
	$("#sub_status").html(msg);
}
});
}
</script>
<div class="page-header"><h1>Complaint  <small>Status</small></h1></div>  
<form action="status.php?<?php echo $qstr; ?>" method="post" id="save">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="<?php echo $action; ?>">
 <input type="hidden" name="a" value="<?php echo Format::htmlchars($_REQUEST['a']); ?>">
 <input type="hidden" name="id" value="<?php echo $info['id']; ?>">         
<div class="row-fluid">
 <!--Left section-->

<div class="span12">
<div class="block-fluid ucard">
            <div class="info">   <!-- onChange="get_substatus(this.value);"-->                                                             
                <ul class="rows">
                    <li class="heading"><div class="isw-users"></div><?php echo $title; ?>&nbsp;&nbsp;&nbsp;<em>(Complaint Status Information)</em></li>
                    <?php if($_REQUEST['id']==''){ ?>
                    <li  id="">
                        <div class="title">Parent Status:</div>
                        <div class="text"><select name="p_id">
                        <option value="">--Select--</option>
                        <?php
		$sql_status="Select * from sdms_status where p_id='0'";
		$res_status=mysql_query($sql_status);
		while($row_status=mysql_fetch_array($res_status)){
		?>
		<option value="<?php echo $row_status['status_id']; ?>" <?php if($info['p_id']==$row_status['status_id']){ ?> selected <?php }?>><?php echo Format::htmlchars($row_status['status_title']); ?></option>
		<?php } ?>
                         </select>
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['status_title']; ?></span></div>
                    </li>
                    <?php }else{?>
                    <input type="hidden" name="p_id" value="<?php echo $info['p_id']; ?>" >
                    <?php } ?>
                    <li>
                        <div class="title">Status Title:</div>
                        <div class="text"><input type="text" size="30" name="status" value="<?php echo $info['status_title']; ?>">
                &nbsp;<span class="error">*&nbsp;<?php echo $errors['status_title']; ?></span></div>
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
            <div class="span12"><em><strong>Admin Notes</strong>: Internal notes about the Status.&nbsp;</em></div>
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
