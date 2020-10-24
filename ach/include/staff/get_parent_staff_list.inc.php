<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');
?>

                <div class="title">Parent User:</div>
                <div class="text">
                <select id="staff_pid" name="staff_pid" style="width:300px;">
        <option value="0" selected="selected">&mdash; Select Staff Member &mdash;</option>
        <?php
		$sid=$tid=0;
        if(($admin_users=Staff::getAdminMembers())) {
        echo '<OPTGROUP label="Supervisor FATA Administrator ('.count($admin_users).')">';
        foreach($admin_users as $id => $name) {
        if($staffId && $staffId==$id)
        continue;
        $k="$id";
        echo sprintf('<option value="%s" %s>%s</option>',$k,(($info['staff_pid']==$k)?'selected="selected"':''),$name);
        }
        echo '</OPTGROUP>';
        }
		
		//show last assign user
		
		//Childs under this user
		if(($users=Staff::getParentMembersbylocation($_REQUEST['staff_location']))) {
        echo '<OPTGROUP label="Agencies Members ('.count($users).')">';
        foreach($users as $id => $name) {
        if($staffId && $staffId==$id)
        continue;
        $k="$id";
        echo sprintf('<option value="%s" %s>%s</option>',$k,(($info['staff_pid']==$k)?'selected="selected"':''),$name);
        }
        echo '</OPTGROUP>';
        }
        ?>
        </select></div>

