<div class="span3">Primary Department:</div>
        <div class="span9">
             <select name="dept_id_new" style="width:300px;" onchange="set_assign_value(this.value);">
                    <option value=""  >&mdash; Select Sub-Department &mdash;</option>
                    <?php
        if($sub_depart=Dept::getSubPublicCategory($_REQUEST['m_dept_id'])) {
        foreach($sub_depart as $id =>$name) {
        echo sprintf('<option value="%d" >%s</option>',$id,$name);
        }
        }
        ?>
                    </select>
       
    </div>