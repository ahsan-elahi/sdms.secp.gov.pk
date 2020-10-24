  <?php
        if($assign_id=Staff::getFocalPersonbyDept($_REQUEST['sub_dept_id'])) {
       echo trim($assign_id);
        }
        ?>
