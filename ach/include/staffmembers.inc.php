<style>
#tSortable_paginate{
	display:none;
	}
#tSortable_filter{
display:none;
}	
#tSortable_length{
	display:none;}
	
#tSortable_paginate{
	display:none;
	}
#tSortable_filter{
display:none;
}	
#tSortable_length{
	display:none;}	
</style>
<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');
$qstr='';
$select='SELECT staff.*,CONCAT_WS(" ",firstname,lastname) as name, grp.group_name, dept.dept_name as dept,count(m.team_id) as teams,agency.Location as agency_name';
$from='FROM '.STAFF_TABLE.' staff '.
      'LEFT JOIN '.GROUP_TABLE.' grp ON(staff.group_id=grp.group_id) '.
      'LEFT JOIN '.DEPT_TABLE.' dept ON(staff.dept_id=dept.dept_id) '.
	  'LEFT JOIN '.STAFF_LOCATION.' agency ON(staff.Location_ID=agency.Location_ID) '.
      'LEFT JOIN '.TEAM_MEMBER_TABLE.' m ON(m.staff_id=staff.staff_id) ';
$where='WHERE 1 ';


if($_REQUEST['did'] && is_numeric($_REQUEST['did'])) {
    $where.=' AND staff.dept_id='.db_input($_REQUEST['did']);
    $qstr.='&did='.urlencode($_REQUEST['did']);
}

if($_REQUEST['gid'] && is_numeric($_REQUEST['gid'])) {
    $where.=' AND .group_id='.db_input($_REQUEST['gid']);
    $qstr.='&gid='.urlencode($_REQUEST['gid']);
}

if($_REQUEST['staff_title']) {
    $where.=' AND CONCAT_WS(" ",staff.firstname,staff.lastname) LIKE  "%'.$_REQUEST['staff_title'].'%"'  ;
  $qstr.='&staff_title='.urlencode($_REQUEST['staff_title']);
}


if($_REQUEST['username']) {
    $where.=' AND staff.username = '.db_input($_REQUEST['username']);
   $qstr.='&username='.urlencode($_REQUEST['username']);
}



if($_REQUEST['tid'] && is_numeric($_REQUEST['tid'])) {
    $where.=' AND m.team_id='.db_input($_REQUEST['tid']);
    $qstr.='&tid='.urlencode($_REQUEST['tid']);
}

$sortOptions=array('name'=>'staff.firstname,staff.lastname','username'=>'staff.username','status'=>'isactive',
                   'group'=>'grp.group_name','dept'=>'dept.dept_name','created'=>'staff.created','login'=>'staff.lastlogin');
$orderWays=array('DESC'=>'DESC','ASC'=>'ASC');
$sort=($_REQUEST['sort'] && $sortOptions[strtolower($_REQUEST['sort'])])?strtolower($_REQUEST['sort']):'name';
//Sorting options...
if($sort && $sortOptions[$sort]) {
    $order_column =$sortOptions[$sort];
}
$order_column=$order_column?$order_column:'staff.firstname,staff.lastname';

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

$total=db_count('SELECT count(DISTINCT staff.staff_id) '.$from.' '.$where);
$page=($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;
$pageNav=new Pagenate($total,$page,PAGE_LIMIT);
$pageNav->setURL('staff.php',$qstr.'&sort='.urlencode($_REQUEST['sort']).'&order='.urlencode($_REQUEST['order']));
//Ok..lets roll...create the actual query
$qstr.='&order='.($order=='DESC'?'ASC':'DESC');
$query="$select $from $where GROUP BY staff.staff_id ORDER BY $order_by LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
?>
<div class="page-header">
            <h1>Staff<small>Members</small></h1>
        </div> 
<div class="row-fluid">
                    <div class="span12">                    
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Search</h1>                          
                        </div>
                        <div class="block-fluid table-sorting clearfix">
                          <form action="" method="get" id="save" enctype="multipart/form-data">
                          <table cellpadding="0" cellspacing="0" width="100%" class="table"  >
<tr>
<th width="12%" style="padding-top:12px;">By Name</th>
<td width="20%"><input type="text" name="staff_title" <?php if(isset($_REQUEST['staff_title'])){?> value="<?php echo $_REQUEST['staff_title']; ?>" <?php } ?> /></td>

<th width="12%" style="padding-top:12px;">By Username</th>
<td width="20%"><input type="text" name="username" <?php if(isset($_REQUEST['username'])){?> value="<?php echo $_REQUEST['username']; ?>" <?php } ?> /></td>

<th width="20%" style="padding-top:12px;">By Department</th>
<td  >
<select name="did" >
<option value="">--Select Department--</option>
<?php 
$sql_get_dept='SELECT * from  sdms_department WHERE 1 ';
$res_get_dept = mysql_query($sql_get_dept);
while($row_dept = mysql_fetch_array($res_get_dept)){
?>
<option value="<?php echo $row_dept['dept_id'] ;?>" <?php if($row_dept['dept_id']==$_REQUEST['did']){ ?> selected <?php }?>><?php echo $row_dept['dept_name'] ;?></option>
<?php } ?>
</select>
</td>

</tr>        
                            <tr>
                            <td style="background-color: #FFFFFF;text-align: right;" colspan="6" align="right">
                            <input class="btn" type="submit" name="search" value="Search">
                            </td>
                            </tr>
                          </table>
  
  
</form>
                        </div>
                    </div>     
</div>        
  
<?php
$res=db_query($query);
if($res && ($num=db_num_rows($res)))        
    $showing=$pageNav->showing();
else
    $showing='No staff found!';
?>                         
<form action="staff.php" method="POST" name="staff" >
<?php csrf_token(); ?>
<input type="hidden" name="do" value="mass_process" >
<input type="hidden" id="action" name="a" value="" >
    <div class="row-fluid">
    
<div class="span3" style="float:right">
    <p style="float:right;">
     <a href="staff.php?a=add" class="Icon newstaff">
     <button class="btn" type="button"><i class="icon-plus-sign"></i> Add New Staff</button></a>                              
    </p>             
</div>
</div>
    <div class="row-fluid">
         <div class="span12">                    
            <div class="head clearfix">
                <div class="isw-grid"></div>
                <h1><?php echo 'Staff Members'; ?></h1>                               
            </div>
            
            <div class="block-fluid table-sorting clearfix">
                <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable">
                    <thead>
                        <tr>
                        <th><input type="checkbox" name="checkall"/></th>
                        <th>Name</th>                          
                        <th>UserName</th>
                        <th>Status</th>
                        <th>Group</th>
                        <th nowrap >Department</th>
                        <th nowrap >Staff Location</th> 
                        <th nowrap >Created</th> 
                         <!--<th nowrap >Last Login</th>  -->       
                        </tr>
                    </thead>
                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                    <?php
        if($res && db_num_rows($res)):
            $ids=($errors && is_array($_POST['ids']))?$_POST['ids']:null;
			$color = 1;
            while ($row = db_fetch_array($res)) {
			if(($color%2) == 0)
				$bg = 'odd';
				else
				$bg = 'even';	
                $sel=false;
                if($ids && in_array($row['staff_id'],$ids))
                    $sel=true;
                ?>
               <tr id="<?php echo $row['staff_id']; ?>" class="<?php echo $bg;?>">
                 <td align="center" class="nohover">
                  <input type="checkbox" class="ckb" name="ids[]" value="<?php echo $row['staff_id']; ?>" <?php echo $sel?'checked="checked"':''; ?> ></td>
                <td><a href="staff.php?id=<?php echo $row['staff_id']; ?>"><?php echo Format::htmlchars($row['name']); ?></a>&nbsp;</td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['isactive']?'Active':'<b>Locked</b>'; ?>&nbsp;<?php echo $row['onvacation']?'<small>(<i>vacation</i>)</small>':''; ?></td>
                <td>
                <a href="groups.php?id=<?php echo $row['group_id']; ?>"><?php echo Format::htmlchars($row['group_name']); ?></a></td>
                <td>
                <a href="departments.php?id=<?php echo $row['dept_id']; ?>"><?php echo Format::htmlchars($row['dept']); ?></a></td>
                <td><?php echo $row['agency_name']; ?>&nbsp;</td>
                <td><?php echo Format::db_date($row['created']); ?></td>
                <!--<td><?php //echo Format::db_datetime($row['lastlogin']); ?>&nbsp;</td>-->
               </tr>               
            <?php
            } //end of while.
        endif; ?>
            </tbody> 
            <tfoot>
                <tr>
                <td colspan="4"><?php echo '<div align="left" style="width:40%;float:left;">'.$showing .'&nbsp;'.'&nbsp;'.'&nbsp;'.'</div>';?></td>
                <td colspan="4" align="right"><?php echo '<div class="dataTables_paginate paging_full_numbers">Page:'.$pageNav->getPageLinks().'</div>'; ?></td>
                </tr>
            </tfoot>                             
                </table>
            </div>
         </div>                      
    </div>   
    <div class="row-fluid">
        <div class="span6" style="width:96.718%;">
         <?php if($res && $num):?>                
            <p style="text-align:center;" id="actions">
                <input class="btn btn-primary" type="submit" name="enable" value="Enable" id="button">
                <input class="btn btn-inverse" type="submit" name="disable" value="Lock" id="button" >    
                <input class="btn btn-danger" type="submit" name="delete" value="Delete" id="button">              
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
    <p class="confirm-action" style="display:none;" id="enable-confirm">
        Are you sure want to <b>enable</b> (unlock) selected staff?
    </p>
    <p class="confirm-action" style="display:none;" id="disable-confirm">
        Are you sure want to <b>disable</b> (lock) selected staff?
        <br><br>Locked staff won't be able to login to Staff Control Panel.
    </p>
    <p class="confirm-action" style="display:none;" id="delete-confirm">
        <font color="red"><strong>Are you sure you want to DELETE selected staff?</strong></font>
        <br><br>Deleted staff CANNOT be recovered.
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