
<?php
if(!defined('OSTCLIENTINC')) die('Access Denied!');
?>
<?php if($_REQUEST['against_comp_type']=='2'){ ?>
	    <tr>
        <td><span style="float:left">Against Depart:<span class="aestarik">*</span></span></td>
        <td><input id="comp_against" name="comp_against" placeholder="Deapartment Name" type="text" tabindex="7"></td>
        </tr>
        <tr>
        <td><span style="float:left">Phone:<span class="aestarik">*</span></span></td>
        <td><input type="text" size="50" name="against_phone" id="against_phone" class="typeahead" value="" /></td>
        </tr>
        <tr>
        <td><span style="float:left">Email:<span class="aestarik">*</span></span></td>
        <td><input type="email" size="50" name="against_email" id="against_email" class="typeahead" value="" /></td>
        </tr>
        <tr>
        <td><span style="float:left">Location/ Area:</span></td>
        <td><select class="select-style gender" name="location" tabindex="8">
             <option value="" selected >&mdash; Select Location/ Area&mdash;</option>
    <?php
    if($districts=Districts::getDistricts()) {
    foreach($districts as $id =>$name) {
		 echo sprintf('<option value="%s" %s>%s</option>',$name, ($info['location']==$name)?'selected="selected"':'',$name);
    }
    }
    ?>
        </select></td>
		</tr>
        <tr>
        <td><span style="float:left">Address:</span></td>
        <td> <textarea cols="19" name="address"></textarea></td>
        </tr>
<?php }else{?>
		<tr>
        <td><span style="float:left">Name:<span class="aestarik">*</span></span></td>
        <td><input id="comp_against" name="comp_against" placeholder="person name" type="text" tabindex="7"></td>
        </tr>
        <tr>
        <td><span style="float:left">Father's Name:<span class="aestarik">*</span></span></td>
        <td><input type="text" name="against_fname"  /></td>
        </tr>
        <tr>
        <td><span style="float:left">Phone:<span class="aestarik">*</span></span></td>
        <td><input type="text" size="50" name="against_phone" id="against_phone" class="typeahead" value="" /></td>
        </tr>
        <tr>
        <td><span style="float:left">CNIC:<span class="aestarik">*</span></span></td>
        <td><input type="text" size="50" name="against_cnic" id="nic" class="typeahead" value="" /></td>
        </tr>
        <tr>
        <td><span style="float:left">Email:<span class="aestarik">*</span></span></td>
        <td><input type="email" size="50" name="against_email" id="against_email" class="typeahead" value="" /></td>
        </tr>
        <tr>
        <td><span style="float:left">Location/ Area:</span></td>
        <td><select class="select-style gender" name="location" tabindex="8">
             <option value="" selected >&mdash; Select Location/ Area&mdash;</option>
    <?php
    if($districts=Districts::getDistricts()) {
    foreach($districts as $id =>$name) {
		 echo sprintf('<option value="%s" %s>%s</option>',$name, ($info['location']==$name)?'selected="selected"':'',$name);
    }
    }
    ?>
        </select></td>
		</tr>
        <tr>
        <td><span style="float:left">Address:</span></td>
        <td> <textarea cols="19" name="address"></textarea></td>
        </tr>
<?php }?>        