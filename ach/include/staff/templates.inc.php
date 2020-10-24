<?php
if(!defined('OSTADMININC') || !$thisstaff->isAdmin()) die('Access Denied');

$qstr='';
$sql='SELECT tpl.*,count(dept.tpl_id) as depts '.
     'FROM '.EMAIL_TEMPLATE_TABLE.' tpl '.
     'LEFT JOIN '.DEPT_TABLE.' dept USING(tpl_id) '.
     'WHERE 1 ';
$sortOptions=array('name'=>'tpl.name','status'=>'tpl.isactive','created'=>'tpl.created','updated'=>'tpl.updated');
$orderWays=array('DESC'=>'DESC','ASC'=>'ASC');
$sort=($_REQUEST['sort'] && $sortOptions[strtolower($_REQUEST['sort'])])?strtolower($_REQUEST['sort']):'name';
//Sorting options...
if($sort && $sortOptions[$sort]) {
    $order_column =$sortOptions[$sort];
}
$order_column=$order_column?$order_column:'tpl.name';

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

$total=db_count('SELECT count(*) FROM '.EMAIL_TEMPLATE_TABLE.' tpl ');
$page=($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;
$pageNav=new Pagenate($total, $page, PAGE_LIMIT);
$pageNav->setURL('templates.php',$qstr.'&sort='.urlencode($_REQUEST['sort']).'&order='.urlencode($_REQUEST['order']));
//Ok..lets roll...create the actual query
$qstr.='&order='.($order=='DESC'?'ASC':'DESC');
$query="$sql GROUP BY tpl.tpl_id ORDER BY $order_by LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
$res=db_query($query);
if($res && ($num=db_num_rows($res)))
    $showing=$pageNav->showing().' Templates';
else
    $showing='No templates found!';

?>
<div class="page-header"><h1>Email <small>Templates</small></h1></div>                         

<form action="templates.php" method="POST" name="tpls">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="mass_process" >
<input type="hidden" id="action" name="a" value="" >
    <div class="row-fluid">
    
<div class="span3" style="float:right">
    <p style="float:right;">
     <a href="templates.php?a=add" class="Icon newEmailTemplate">
     <button class="btn" type="button"><i class="icon-plus-sign"></i>Add New Template</button></a>                              
    </p>             
</div>
</div>
    <div class="row-fluid">
         <div class="span12">                    
            <div class="head clearfix">
                <div class="isw-grid"></div>
                <h1><?php echo 'Email Templates'; ?></h1>                               
            </div>
            
            <div class="block-fluid table-sorting clearfix">
                <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_6">
                    <thead>
                        <tr>
                        <th><input type="checkbox" name="checkall"/></th>
                        <th>Name</th> 
                        <th>Status</th> 
                        <th>In-Use</th>
                        <th>Date Added</th>
                        <th>Last Updated</th>                      
                        </tr>
                    </thead>
                    <tbody role="alert" aria-live="polite" aria-relevant="all">
    <?php
        $total=0;
        $ids=($errors && is_array($_POST['ids']))?$_POST['ids']:null;
        if($res && db_num_rows($res)):
		$color = 1;
            $defaultTplId=$cfg->getDefaultTemplateId();
            while ($row = db_fetch_array($res)) {
				if(($color%2) == 0)
				$bg = 'odd';
				else
				$bg = 'even';
                $inuse=($row['depts'] || $row['tpl_id']==$defaultTplId);
                $sel=false;
                if($ids && in_array($row['tpl_id'],$ids))
                    $sel=true;
                
                $default=($defaultTplId==$row['tpl_id'])?'<small class="fadded">(System Default)</small>':'';
                ?>
             <tr id="<?php echo $row['tpl_id']; ?>">
                <td align="center" class="nohover">
                  <input type="checkbox" class="ckb" name="ids[]" value="<?php echo $row['tpl_id']; ?>" 
                            <?php echo $sel?'checked="checked"':''; ?> <?php echo $default?'disabled="disabled"':''; ?> >
                </td>
                <td>&nbsp;<a href="templates.php?id=<?php echo $row['tpl_id']; ?>"><?php echo Format::htmlchars($row['name']); ?></a>
                &nbsp;<?php echo $default; ?></td>
                <td>&nbsp;<?php echo $row['isactive']?'Active':'<b>Disabled</b>'; ?></td>
                <td>&nbsp;&nbsp;<?php echo ($inuse)?'<b>Yes</b>':'No'; ?></td>
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
        Are you sure want to <b>enable</b> selected templates?
    </p>
    <p class="confirm-action" style="display:none;" id="disable-confirm">
        Are you sure want to <b>disable</b>  selected templates?
    </p>
    <p class="confirm-action" style="display:none;" id="delete-confirm">
        <font color="red"><strong>Are you sure you want to DELETE selected templates?</strong></font>
        <br><br>Deleted templates CANNOT be recovered.
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



