<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');
?>
<li>
	<div class="title">Departments:</div>
    <div class="text">
       <select name="dept_id" title="Select the department againt whom you want to register a complaint">
                    <option value=""  >&mdash; Select Sub-Department &mdash;</option>
                    <?php
        if($sub_depart=Dept::getSubPublicCategory($_REQUEST['m_comp_type'])) {
        foreach($sub_depart as $id =>$name) {
        echo sprintf('<option value="%d" %s>%s</option>',$id, ($info['deptId']==$id)?'selected="selected"':'',$name);
        }
        }
        ?>
                    </select>
        &nbsp;<font class="error"><b>*</b>&nbsp;<?php echo $errors['dept_id']; ?></font>
    </div>
</li>      