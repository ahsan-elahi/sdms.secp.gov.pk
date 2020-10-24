<?php
if(!defined('OSTADMININC') || !$thisstaff->isAdmin()) die('Access Denied');

$qstr='';
$sql='SELECT dept.dept_id,dept_name,email.email_id,email.email,email.name as email_name,ispublic,count(staff.staff_id) as users '.
     ',CONCAT_WS(" ",mgr.firstname,mgr.lastname) as manager,mgr.staff_id as manager_id,dept.created,dept.updated  FROM '.DEPT_TABLE.' dept '.
     ' LEFT JOIN '.STAFF_TABLE.' mgr ON dept.manager_id=mgr.staff_id '.
     ' LEFT JOIN '.EMAIL_TABLE.' email ON dept.email_id=email.email_id '.
     ' LEFT JOIN '.STAFF_TABLE.' staff ON dept.dept_id=staff.dept_id ';

$sql.=' WHERE 1';
$sortOptions=array('name'=>'dept.dept_name','type'=>'ispublic','users'=>'users','email'=>'email_name, email.email','manager'=>'manager');
$orderWays=array('DESC'=>'DESC','ASC'=>'ASC');
$sort=($_REQUEST['sort'] && $sortOptions[strtolower($_REQUEST['sort'])])?strtolower($_REQUEST['sort']):'name';
//Sorting options...
if($sort && $sortOptions[$sort]) {
    $order_column =$sortOptions[$sort];
}
$order_column=$order_column?$order_column:'dept.dept_name';

if($_REQUEST['order'] && $orderWays[strtoupper($_REQUEST['order'])]) {
    $order=$orderWays[strtoupper($_REQUEST['order'])];
}
$order=$order?$order:'ASC';

if($order_column && strpos($order_column,',')){
    $order_column=str_replace(','," $order,",$order_column);
}
$x=$sort.'_sort';
$$x=' class="'.strtolower($order).'" ';
$order_by="$order_column $order ";

$qstr.='&order='.($order=='DESC'?'ASC':'DESC');

$query="$sql GROUP BY dept.dept_id ORDER BY $order_by";
$res=db_query($query);
if($res && ($num=db_num_rows($res)))
    $showing="Showing 1-$num of $num departments";
else
    $showing='No departments found!';

?>

<div class="page-header"><h1>Department <small>Names</small></h1></div> 
<form action="departments.php" method="POST" name="depts">
<?php csrf_token(); ?>
<input type="hidden" name="do" value="mass_process" >
<input type="hidden" id="action" name="a" value="" >
    <div class="row-fluid">
        <div class="span3" style="float:right">
            <p style="float:right;">
            <a href="departments.php?a=add" class="Icon newgroup">
             <button class="btn" type="button"><i class="icon-plus-sign"></i> Add New Department</button></a>                              
            </p>             
        </div>
        </div>
<div class="row-fluid">
<div class="span12">                    
            <div class="head clearfix">
                <div class="isw-grid"></div>
                <h1><?php echo 'Department'; ?></h1>                               
            </div>
            
            <div class="block-fluid table-sorting clearfix">
                <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_6">
                    <thead>
                        <tr>
                        <th><input type="checkbox" name="checkall"/></th>
                        <th><a <?php echo $name_sort; ?> href="departments.php?<?php echo $qstr; ?>&sort=name">Name</a></th>                       
                        <th><a  <?php echo $type_sort; ?> href="departments.php?<?php echo $qstr; ?>&sort=type">Type</a></th>                     
                        <th><a  <?php echo $users_sort; ?>href="departments.php?<?php echo $qstr; ?>&sort=users">Users</a></th>                        
                        <th><a  <?php echo $email_sort; ?> href="departments.php?<?php echo $qstr; ?>&sort=email">Email Address</a></th>                       
                        <th nowrap ><a  <?php echo $manager_sort; ?> href="departments.php?<?php echo $qstr; ?>&sort=manager">Dept. Manager</a></th>           
                        </tr>
                    </thead>
                    <tbody role="alert" aria-live="polite" aria-relevant="all">
    <?php
        $total=0;
        $ids=($errors && is_array($_POST['ids']))?$_POST['ids']:null;
        if($res && db_num_rows($res)):
            $defaultId=$cfg->getDefaultDeptId();
			$color = 1;
            while ($row = db_fetch_array($res)) {
			if(($color%2) == 0)
				$bg = 'odd';
				else
				$bg = 'even';		
                $sel=false;
                if($ids && in_array($row['dept_id'],$ids))
                    $sel=true;
                
                $row['email']=$row['email_name']?($row['email_name'].' &lt;'.$row['email'].'&gt;'):$row['email'];
                $default=($defaultId==$row['dept_id'])?' <small>(Default)</small>':'';
                ?>
             <tr id="<?php echo $row['dept_id']; ?>">    
                <td align="center" class="nohover">
                  <input type="checkbox" class="ckb" name="ids[]" value="<?php echo $row['dept_id']; ?>" 
                            <?php echo $sel?'checked="checked"':''; ?>  <?php echo $default?'disabled="disabled"':''; ?> >
                </td>
                <td><a href="departments.php?id=<?php echo $row['dept_id']; ?>"><?php echo $row['dept_name']; ?></a>&nbsp;<?php echo $default; ?></td>
                <td><?php echo $row['ispublic']?'Public':'<b>Private</b>'; ?></td>
                <td>&nbsp;&nbsp;
                    <b>
                    <?php if($row['users']>0) { ?>
                        <a href="staff.php?did=<?php echo $row['dept_id']; ?>"><?php echo $row['users']; ?></a>
                    <?php }else{ ?> 0
                    <?php } ?>
                    </b>
                </td>
                <td><a href="emails.php?id=<?php echo $row['email_id']; ?>"><?php echo $row['email']; ?></a></td>
                <td><a href="staff.php?id=<?php echo $row['manager_id']; ?>"><?php echo $row['manager']; ?>&nbsp;</a></td>
            </tr>
            <?php
			$color++;
            } //end of while.
        endif; ?>
                    </tbody>                              
                </table>
            </div>
         </div>                      
    </div>   
    <div class="row-fluid"> 
        <div class="span6" style="width:96.718%;">
         <?php if($res && $num):?>                
            <p style="text-align:center;" id="actions">         
                <input class="btn btn-primary" type="submit" name="make_public" value="Make Public" id="button">               
                <input class="btn btn-inverse" type="submit" name="make_private" value="Make Private" id="button" >               
                <input class="btn btn-danger" type="submit" name="delete" value="Delete Dept(s)" id="button">                      
            </p>   
            <?php endif;?>
        </div> 
    </div>                     
<div class="dr"><span></span></div>   
</form>
<div style="display:none;" class="dialog" id="confirm-action">
    <h3>Please Confirm</h3>
    <a class="close" href="">&times;</a>
    <hr/>
    <p class="confirm-action" style="display:none;" id="make_public-confirm">
        Are you sure want to make selected departments <b>public</b>?
    </p>
    <p class="confirm-action" style="display:none;" id="make_private-confirm">
        Are you sure want to make selected departments <b>private</b>?
    </p>
    <p class="confirm-action" style="display:none;" id="delete-confirm">
        <font color="red"><strong>Are you sure you want to DELETE selected departments?</strong></font>
        <br><br>Deleted departments CANNOT be recovered.
    </p>
    <div>Please confirm to continue.</div>
    <hr style="margin-top:1em"/>
    <p class="full-width">
        <span class="buttons" style="float:left">
            <input type="button" value="No, Cancel" class="close">
        </span>
        <span class="buttons" style="float:right">
            <input type="button" value="Yes, Do it!" class="confirm">
        </span>
     </p>
    <div class="clear"></div>
</div>    
                 
   </div><!--WorkPlace End-->  
   </div> 