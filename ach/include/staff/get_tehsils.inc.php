<?php
if(!defined('OSTSCPINC') || !$thisstaff || !$thisstaff->canCreateTickets()) die('Access Denied');
?>
<li>
	<div class="title">Province:</div>
    <div class="text">
        <select name="tehsil" onChange="get_agency_tehsils(this.value);" required id="tehsil" style="width:301px;">
         <option value="">--Select Province--</option>
            <?php
			$info['tehsil'] = 56;
            if($sub_tehsils=Districts::getTehsils($_REQUEST['district_id'])) {
            foreach($sub_tehsils as $id =>$sub_name) {
            echo sprintf('<option value="%d" %s>%s</option>',$id, ($info['tehsil']==$id)?'selected="selected"':'', $sub_name);
            }
            }?>
            <option value="0">Not Available</option>
        </select>
        &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['tehsil']; ?></font>
    </div>
</li>      