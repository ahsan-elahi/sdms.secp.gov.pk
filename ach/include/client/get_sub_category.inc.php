<?php
if(!defined('OSTCLIENTINC')) die('Access Denied!');
?>

          <td><span style="float:left">Type of Sub-Complaint:<span class="aestarik">*</span></span></td>
            <td>
            <select id="topicId" name="topicId" tabindex="5">
            <option value="" selected="selected">&mdash; Select a Sub-Complaint Type &mdash;</option>
             <?php
                if($sub_topics=Topic::getSubPublicHelpTopics($_REQUEST['m_comp_type'])) {
                    foreach($sub_topics as $id =>$sub_name) {
                        echo sprintf('<option value="%d" %s>%s</option>',$id, ($info['topicId']==$id)?'selected="selected"':'', $sub_name);
                    }
                }?>
               </select>
               <?php echo $errors['topicId']; ?></td>