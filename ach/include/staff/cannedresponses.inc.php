<?php
if(!defined('OSTSCPINC') || !$thisstaff) die('Access Denied');

$qstr='';
$sql='SELECT canned.*, count(attach.file_id) as files, dept.dept_name as department '.
     ' FROM '.CANNED_TABLE.' canned '.
     ' LEFT JOIN '.DEPT_TABLE.' dept ON (dept.dept_id=canned.dept_id) '.
     ' LEFT JOIN '.CANNED_ATTACHMENT_TABLE.' attach ON (attach.canned_id=canned.canned_id) ';
$sql.=' WHERE 1';

$sortOptions=array('title'=>'canned.title','status'=>'canned.isenabled','dept'=>'department','updated'=>'canned.updated');
$orderWays=array('DESC'=>'DESC','ASC'=>'ASC');
$sort=($_REQUEST['sort'] && $sortOptions[strtolower($_REQUEST['sort'])])?strtolower($_REQUEST['sort']):'title';
//Sorting options...
if($sort && $sortOptions[$sort]) {
    $order_column =$sortOptions[$sort];
}

$order_column=$order_column?$order_column:'canned.title';

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

$total=db_count('SELECT count(*) FROM '.CANNED_TABLE.' canned ');
$page=($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;
$pageNav=new Pagenate($total, $page, PAGE_LIMIT);
$pageNav->setURL('canned.php',$qstr.'&sort='.urlencode($_REQUEST['sort']).'&order='.urlencode($_REQUEST['order']));
//Ok..lets roll...create the actual query
$qstr.='&order='.($order=='DESC'?'ASC':'DESC');
$query="$sql GROUP BY canned.canned_id ORDER BY $order_by LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
$res=db_query($query);
if($res && ($num=db_num_rows($res)))
    $showing=$pageNav->showing().' premade responses';
else
    $showing='No premade responses found!';

?>

<div class="page-header"><h1>Canned  <small>Responses</small></h1></div> 
<form action="canned.php" method="POST" name="canned">
<?php csrf_token(); ?>
<input type="hidden" name="do" value="mass_process" >
<input type="hidden" id="action" name="a" value="" >
<div class="row-fluid">
<div class="span3" style="float:right">
    <p align="right" style="float:right;">
     <a href="canned.php?a=add" class="Icon newReply">
     <button class="btn" type="button"><i class="icon-plus-sign"></i>Add New Response</button></a>                              
    </p>             
</div>
</div>
    <div class="row-fluid">
         <div class="span12">                    
            <div class="head clearfix">
                <div class="isw-grid"></div>
                <h1><?php echo 'Canned Responses'; ?></h1>                               
            </div>
            
            <div class="block-fluid table-sorting clearfix">
                <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_5">
                    <thead>
                        <tr>
                        <th><input type="checkbox" name="checkall"/></th>
                        <th>Title</th>                          
                        <th>Status</th>
                        <th>Department</th>
                        <th>Last Updated</th>     
                        </tr>
                    </thead>
                    <tbody role="alert" aria-live="polite" aria-relevant="all">
<?php
        $total=0;
        $ids=($errors && is_array($_POST['ids']))?$_POST['ids']:null;
        if($res && db_num_rows($res)):
		$color = 1;
            while ($row = db_fetch_array($res)) {
			if(($color%2) == 0)
			$bg = 'odd';
			else
			$bg = 'even';
                $sel=false;
                if($ids && in_array($row['canned_id'],$ids))
                    $sel=true;
                $files=$row['files']?'<span class="Icon file">&nbsp;</span>':'';
                ?>
              <tr id="<?php echo $row['canned_id']; ?>" >
                <td align="center" class="nohover">
                  <input type="checkbox" name="ids[]" value="<?php echo $row['canned_id']; ?>" class="ckb"
                            <?php echo $sel?'checked="checked"':''; ?> />
                </td>
                <td>
                    <a href="canned.php?id=<?php echo $row['canned_id']; ?>"><?php echo Format::truncate($row['title'],200); echo "&nbsp;$files"; ?></a>&nbsp;
                </td>
                <td><?php echo $row['isenabled']?'Active':'<b>Disabled</b>'; ?></td>
                <td><?php echo $row['department']?$row['department']:'&mdash; All Departments &mdash;'; ?></td>
                <td>&nbsp;<?php echo Format::db_datetime($row['updated']); ?></td>
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
                
                <input class="btn btn-primary" type="submit" name="enable" value="Enable" id="button">
                
                <input class="btn btn-inverse" type="submit" name="disable" value="Disable" id="button" >                    
                
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
        Are you sure want to <b>enable</b> selected canned responses?
    </p>
    <p class="confirm-action" style="display:none;" id="disable-confirm">
        Are you sure want to <b>disable</b> selected canned responses?
    </p>
    <p class="confirm-action" style="display:none;" id="mark_overdue-confirm">
        Are you sure want to flag the selected Complaints as <font color="red"><b>overdue</b></font>?
    </p>
    <p class="confirm-action" style="display:none;" id="delete-confirm">
        <font color="red"><strong>Are you sure you want to DELETE selected canned responses?</strong></font>
        <br><br>Deleted items CANNOT be recovered, including any associated attachments.
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


