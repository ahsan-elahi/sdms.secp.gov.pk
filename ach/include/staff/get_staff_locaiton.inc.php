<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');
?>
<li>
	<div class="title">Staff Location:</div>
    <div class="text">
        <select name="location_id" onchange="getparentstaff(this.value);">
      <option value="0" selected="selected">&mdash; Select Staff Locaiotn &mdash;</option>
            <?php
            if($staff_locations=Staff::getStaffLocation($_REQUEST['locaiotn_type'])) {
            foreach($staff_locations as $id =>$sub_name) {
            echo sprintf('<option value="%d" >%s</option>',$id, $sub_name);
            }
            }?>
        </select>
        &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['tehsil']; ?></font>
    </div>
</li>                       