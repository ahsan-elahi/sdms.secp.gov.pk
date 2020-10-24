<?php
if(!defined('OSTSCPINC') || !$thisstaff || !$thisstaff->canCreateTickets()) die('Access Denied');
?>

<?php if($_REQUEST['identify_id']=='1'){ ?>
                    <li>
                                            <div class="title">Name:</div>
                                            <div class="text">
                                            <select name="name_title" style="width:77px;" >
                                            <option value="Mr." selected="selected" >Mr.</option>
                                            <option value="Miss." >Miss.</option>
                                            </select>
                                            <input type="text" size="50" name="name" value="<?php echo $info['name']; ?>" required="required">
                                            &nbsp;<span class="error">*&nbsp;<?php echo $errors['name']; ?></span>
                                            </div>
                                        </li>
                    <li>
                        <div class="title"><select name="relation_title" style="width:90px;">
        <option value="Son of" selected="selected" >Son of</option>
        <option value="Wife of" >Wife of</option>
        <option value="Daughter of" >Daughter of</option>
        </select></div>
                        <div class="text"><input type="text" style="width:285px;" name="relation_name" id="relation_name" value="<?php echo $info['relation_name']; ?>">
        &nbsp;<span class="error">&nbsp;<?php echo $errors['relation_name']; ?></span></div>
                    </li>
                    <li>
                        <div class="title">Address:</div>
                        <div class="text"><textarea name="applicant_address" rows="2"  style="width:285px;" ></textarea></div>
                    </li> 
                    <li>
                        <div class="title">CNIC:</div>
                        <div class="text"><input id="nic" type="text" name="nic" size="30" value=""  style="width:285px;" required="required" >
                         &nbsp;<span class="error">*&nbsp;<?php echo $errors['nic']; ?></span>
                         <span>Example: 99999-9999999-9</span>                         
                        </div>
                    </li>
					<li>
                        <div class="title">Gender:</div>
                        <div class="text"><select class="select-style gender" name="gender"  style="width:300px;">
        <option value="" selected disabled="disabled" >&mdash; Select Gender &mdash;</option>
        <option value="Male"> Male</option>
        <option value="Female"> Female</option>
        <option value="other"> Other</option>
        </select></div>
                    </li>
                    <li>
                    <div class="title">Cell :</div>
                    <div class="text">
                    <input type="text" name="phone" id="phone" maxlength="11" value="<?php echo $info['phone']; ?>" style="width:300px;">
                    &nbsp;<span class="error">&nbsp;<?php echo $errors['phone']; ?></span></div>
                    </li>
                    <li>
                        <div class="title">Email:</div>
                        <div class="text">
                        <input type="text" size="50" name="email" id="email" class="typeahead" value="<?php echo $info['email']; ?>" autocomplete="off" autocorrect="off" autocapitalize="off"><br />
        &nbsp;<span class="error">&nbsp;<?php echo $errors['email']; ?></span>
        <?php 
        if($cfg->notifyONNewStaffTicket()) { ?>
        &nbsp;&nbsp;&nbsp;
        <input type="checkbox" name="alertuser" <?php echo (!$errors || $info['alertuser'])? 'checked="checked"': ''; ?>>Send alert to user.
        <?php } ?></div>
                    </li>
                    <li>
                        <div class="title">Outside Fata:</div>
                        <div class="text">
                        <input type="checkbox" name="checkbox" value="" id="chkCaption">Outside Fata
    &nbsp;<font class="error">&nbsp;<?php echo $errors['district']; ?></font>
       </div>
		 </li>      
                    <div id="divCaption">              
                                <li>
                                    <div class="title">Agencies:</div>
                                    <div class="text">
                                    <select name="district" onChange="get_tehsils(this.value);">
                <?php
                if($districts=Districts::getDistricts()) {
                foreach($districts as $id =>$name) {
                     echo sprintf('<option value="%d" %s>%s</option>',$id, ($info['district']==$id)?'selected="selected"':'',$name);
                }
                }
                ?>
                    </select>
                &nbsp;<font class="error">&nbsp;<?php echo $errors['district']; ?></font>
                   </div>
                     </li>                    
                                <div id="show_sub_tehsils"></div>
                                  </div>
<?php }else{
	
	}?>     