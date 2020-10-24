<?php
if(!defined('OSTADMININC') || !$thisstaff->isAdmin()) die('Access Denied');

$qstr='';
$sql='SELECT email.*,dept.dept_name as department,priority_desc as priority '.
     ' FROM '.EMAIL_TABLE.' email '.
     ' LEFT JOIN '.DEPT_TABLE.' dept ON (dept.dept_id=email.dept_id) '.
     ' LEFT JOIN '.TICKET_PRIORITY_TABLE.' pri ON (pri.priority_id=email.priority_id) ';
$sql.=' WHERE 1';
$sortOptions=array('email'=>'email.email','dept'=>'department','priority'=>'priority','created'=>'email.created','updated'=>'email.updated');
$orderWays=array('DESC'=>'DESC','ASC'=>'ASC');
$sort=($_REQUEST['sort'] && $sortOptions[strtolower($_REQUEST['sort'])])?strtolower($_REQUEST['sort']):'email';
//Sorting options...
if($sort && $sortOptions[$sort]) {
    $order_column =$sortOptions[$sort];
}
$order_column=$order_column?$order_column:'email.email';

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

$total=db_count('SELECT count(*) FROM '.EMAIL_TABLE.' email ');
$page=($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;
$pageNav=new Pagenate($total, $page, PAGE_LIMIT);
$pageNav->setURL('emails.php',$qstr.'&sort='.urlencode($_REQUEST['sort']).'&order='.urlencode($_REQUEST['order']));
//Ok..lets roll...create the actual query
$qstr.='&order='.($order=='DESC'?'ASC':'DESC');
$query="$sql GROUP BY email.email_id ORDER BY $order_by LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
$res=db_query($query);
if($res && ($num=db_num_rows($res)))
    $showing=$pageNav->showing().' emails';
else
    $showing='No emails found!';

?>

<div class="page-header">
            <h1>Email<small>Addresses</small></h1>
        </div>                         
<form action="emails.php" method="POST" name="emails">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="mass_process" >
 <input type="hidden" id="action" name="a" value="" >
<div class="row-fluid">    
<div class="span3" style="float:right">
    <p style="float:right;">
     <a href="emails.php?a=add" class="Icon newEmail">
     <button class="btn" type="button"><i class="icon-plus-sign"></i> Add New Email</button></a>                              
    </p>             
</div>
</div>
    <div class="row-fluid">

         <div class="span12">                    
            <div class="head clearfix">
                <div class="isw-grid"></div>
                <h1><?php echo 'Email Addresses'; ?></h1>                               
            </div>
            
            <div class="block-fluid table-sorting clearfix">
                <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_6">
                    <thead>
                        <tr>
                        <th><input type="checkbox" name="checkall"/></th>
                        <th>Email</th> 
                        <th>Priority</th> 
                        <th>Department</th>
                        <th>Created</th>
                        <th nowrap>Last Updated</th>                         
                        </tr>
                    </thead>
                    <tbody role="alert" aria-live="polite" aria-relevant="all">
    <?php
        $total=0;
        $ids=($errors && is_array($_POST['ids']))?$_POST['ids']:null;
        if($res && db_num_rows($res)):
            $defaultId=$cfg->getDefaultEmailId();
			$color = 1;
            while ($row = db_fetch_array($res)) {
				if(($color%2) == 0)
				$bg = 'odd';
				else
				$bg = 'even';
                $sel=false;
                if($ids && in_array($row['email_id'],$ids))
                    $sel=true;
                $default=($row['email_id']==$defaultId);
                $email=$row['email'];
                if($row['name'])
                    $email=$row['name'].' <'.$row['email'].'>';
                ?>
            <tr id="<?php echo $row['email_id']; ?>">
                <td align="center" class="nohover">
                  <input type="checkbox" class="ckb" name="ids[]" value="<?php echo $row['email_id']; ?>" 
                            <?php echo $sel?'checked="checked"':''; ?>  <?php echo $default?'disabled="disabled"':''; ?>>
                </td>
                <td><a href="emails.php?id=<?php echo $row['email_id']; ?>"><?php echo Format::htmlchars($email); ?></a>&nbsp;</td>
                <td><?php echo $row['priority']; ?></td>
                <td><a href="departments.php?id=<?php echo $row['dept_id']; ?>"><?php echo $row['department']; ?></a></td>
                <td>&nbsp;<?php echo Format::db_date($row['created']); ?></td>
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
                                    
                
                <input class="btn btn-danger" type="submit" name="delete" value="Delete Email(s)" id="button">
                      
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
    <p class="confirm-action" style="display:none;" id="delete-confirm">
        <font color="red"><strong>Are you sure you want to DELETE selected emails?</strong></font>
        <br><br>Deleted emails CANNOT be recovered.
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



