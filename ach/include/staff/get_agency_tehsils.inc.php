<?php
if(!defined('OSTSCPINC') || !$thisstaff || !$thisstaff->canCreateTickets()) die('Access Denied');
?>
<li>
	<div class="title">City:</div>
    <div class="text">
        <select name="agency_tehsils" style="width:301px;">
        <option value="">--Select City--</option>
            <?php
			$info['agency_tehsils'] = 73;
            if($agency_tehsils=Districts::getAgencyTehsil($_REQUEST['tehsil_id'])) {
            foreach($agency_tehsils as $id =>$sub_name) {    
			   echo sprintf('<option value="%d"  %s>%s</option>',$id, ($info['agency_tehsils']==$id)?'selected="selected"':'', $sub_name);
            }
            }?>
         <option value="0">Not Available</option>
        </select>
        &nbsp;<font class="error">&nbsp;<?php echo $errors['tehsil']; ?></font>
    </div>
</li>      <!--%s  >, ($info['tehsil']==$id)?'selected="selected"':''-->