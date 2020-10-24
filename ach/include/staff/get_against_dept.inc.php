<?php
//if(!defined('OSTSCPINC') || !$thisstaff || !$thisstaff->canCreateTickets()) die('Access Denied');
?>
	<div class="title">Against :</div>
    <div class="text">
    <select name="comp_against_dept">
        <option value="">--Select Department--</option>
                    <?php
        if($sub_depart=Dept::getSubPublicCategory(26)) {
        foreach($sub_depart as $id =>$name) {
        if($id=='27')
			{}else{
        echo sprintf('<option value="%d" >%s</option>',$id,$name);
			}
		}
        }
        ?>
    </select>
    </div>