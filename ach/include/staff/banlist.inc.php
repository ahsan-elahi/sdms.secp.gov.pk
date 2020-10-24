<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin() || !$filter) die('Access Denied');

$qstr='';
$select='SELECT rule.* ';
$from='FROM '.FILTER_RULE_TABLE.' rule ';
$where='WHERE rule.filter_id='.db_input($filter->getId());
$search=false;
if($_REQUEST['q'] && strlen($_REQUEST['q'])>3) {
    $search=true;
    if(strpos($_REQUEST['q'],'@') && Validator::is_email($_REQUEST['q']))
        $where.=' AND rule.val='.db_input($_REQUEST['q']);
    else
        $where.=' AND rule.val LIKE "%'.db_input($_REQUEST['q'],false).'%"';

}elseif($_REQUEST['q']) {
    $errors['q']='Term too short!';
}

$sortOptions=array('email'=>'rule.val','status'=>'isactive','created'=>'rule.created','created'=>'rule.updated');
$orderWays=array('DESC'=>'DESC','ASC'=>'ASC');
$sort=($_REQUEST['sort'] && $sortOptions[strtolower($_REQUEST['sort'])])?strtolower($_REQUEST['sort']):'email';
//Sorting options...
if($sort && $sortOptions[$sort]) {
    $order_column =$sortOptions[$sort];
}
$order_column=$order_column?$order_column:'rule.val';

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

$total=db_count('SELECT count(DISTINCT rule.id) '.$from.' '.$where);
$page=($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;
$pageNav=new Pagenate($total, $page, PAGE_LIMIT);
$pageNav->setURL('banlist.php',$qstr.'&sort='.urlencode($_REQUEST['sort']).'&order='.urlencode($_REQUEST['order']));
$qstr.='&order='.($order=='DESC'?'ASC':'DESC');
$query="$select $from $where ORDER BY $order_by LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
//echo $query;
?>
<div class="page-header"><h1>Banned Email<small>Addresses</small></h1></div>                         
<?php
if(($res=db_query($query)) && ($num=db_num_rows($res)))
    $showing=$pageNav->showing();
else
    $showing='No banned emails matching the query found!';

if($search)
    $showing='Search Results: '.$showing;
    
?>
<form action="banlist.php" method="POST" name="banlist">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="mass_process" >
<input type="hidden" id="action" name="a" value=""  />
<div class="row-fluid">
<div class="span3" style="float:right">
    <p style="float:right;">
     <a href="banlist.php?a=add" class="Icon newstaff">
     <button class="btn" type="button"><i class="icon-plus-sign"></i>Ban New Email</button></a>                              
    </p>             
</div>
</div>
<div class="row-fluid">
         <div class="span12">                    
            <div class="head clearfix">
                <div class="isw-grid"></div>
                <h1><?php echo 'Banned Email Addresses'; ?></h1>                               
            </div>
            
            <div class="block-fluid table-sorting clearfix">
                <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_5">
                    <thead>
                        <tr>
                        <th><input type="checkbox" name="checkall"/></th>
                        <th>Email Address</th> 
                        <th>Ban Status</th> 
                        <th>Date Added</th>
                        <th>Last Updated</th>                       
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
                if($ids && in_array($row['id'],$ids))
                    $sel=true;
                ?>
                <tr id="<?php echo $row['id']; ?>">
                <td align="center" class="nohover">
                  <input type="checkbox" class="ckb" name="ids[]" value="<?php echo $row['id']; ?>" <?php echo $sel?'checked="checked"':''; ?>>
                </td>
                <td>&nbsp;<a href="banlist.php?id=<?php echo $row['id']; ?>"><?php echo Format::htmlchars($row['val']); ?></a></td>
                <td>&nbsp;&nbsp;<?php echo $row['isactive']?'Active':'<b>Disabled</b>'; ?></td>
                <td><?php echo Format::db_date($row['created']); ?></td>
                <td><?php echo Format::db_datetime($row['updated']); ?>&nbsp;</td>
               </tr>
            <?php
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
        Are you sure want to <b>enable</b> selected ban rules?
    </p>
    <p class="confirm-action" style="display:none;" id="disable-confirm">
        Are you sure want to <b>disable</b>  selected ban rules?
    </p>
    <p class="confirm-action" style="display:none;" id="delete-confirm">
        <font color="red"><strong>Are you sure you want to DELETE selected ban rules?</strong></font>
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

