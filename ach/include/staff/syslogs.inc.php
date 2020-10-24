<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');

$qstr='';
if($_REQUEST['type']) {
    $qstr.='&amp;type='.urlencode($_REQUEST['type']);
}
$type=null;
switch(strtolower($_REQUEST['type'])){
    case 'error':
        $title='Errors';
        $type=$_REQUEST['type'];
        break;
    case 'warning':
        $title='Warnings';
        $type=$_REQUEST['type'];
        break;
    case 'debug':
        $title='Debug logs';
        $type=$_REQUEST['type'];
        break;
    default:
        $type=null;
        $title='All logs';
}

$qwhere =' WHERE 1';
//Type
if($type)
    $qwhere.=' AND log_type='.db_input($type);

//dates
$startTime  =($_REQUEST['startDate'] && (strlen($_REQUEST['startDate'])>=8))?strtotime($_REQUEST['startDate']):0;
$endTime    =($_REQUEST['endDate'] && (strlen($_REQUEST['endDate'])>=8))?strtotime($_REQUEST['endDate']):0;
if( ($startTime && $startTime>time()) or ($startTime>$endTime && $endTime>0)){
    $errors['err']='Entered date span is invalid. Selection ignored.';
    $startTime=$endTime=0;
}else{
    if($startTime){
        $qwhere.=' AND created>=FROM_UNIXTIME('.$startTime.')';
        $qstr.='&startDate='.urlencode($_REQUEST['startDate']);
    }
    if($endTime){
        $qwhere.=' AND created<=FROM_UNIXTIME('.$endTime.')';
        $qstr.='&endDate='.urlencode($_REQUEST['endDate']);
    }
}
$sortOptions=array('id'=>'log.log_id', 'title'=>'log.title','type'=>'log_type','ip'=>'log.ip_address'
                    ,'date'=>'log.created','created'=>'log.created','updated'=>'log.updated');
$orderWays=array('DESC'=>'DESC','ASC'=>'ASC');
$sort=($_REQUEST['sort'] && $sortOptions[strtolower($_REQUEST['sort'])])?strtolower($_REQUEST['sort']):'id';
//Sorting options...
if($sort && $sortOptions[$sort]) {
    $order_column =$sortOptions[$sort];
}
$order_column=$order_column?$order_column:'log.created';

if($_REQUEST['order'] && $orderWays[strtoupper($_REQUEST['order'])]) {
    $order=$orderWays[strtoupper($_REQUEST['order'])];
}
$order=$order?$order:'DESC';

if($order_column && strpos($order_column,',')){
    $order_column=str_replace(','," $order,",$order_column);
}
$x=$sort.'_sort';
$$x=' class="'.strtolower($order).'" ';
$order_by="$order_column $order ";

$qselect = 'SELECT log.* ';
$qfrom=' FROM '.SYSLOG_TABLE.' log ';
$total=db_count("SELECT count(*) $qfrom $qwhere");
$page = ($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;
//pagenate
$pageNav=new Pagenate($total, $page, PAGE_LIMIT);
$pageNav->setURL('logs.php',$qstr);
$qstr.='&order='.($order=='DESC'?'ASC':'DESC');
$query="$qselect $qfrom $qwhere ORDER BY $order_by LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
$res=db_query($query);
if($res && ($num=db_num_rows($res)))
    $showing=$pageNav->showing().' '.$title;
else
    $showing='No logs found!';
?>

<div class="page-header"><h1>System  <small> Logs</small></h1></div>
<form action="logs.php" method="POST" name="logs">
<?php csrf_token(); ?>
 <input type="hidden" name="do" value="mass_process" >
 <input type="hidden" id="action" name="a" value="" >
 <div class="row-fluid">
        <div class="span3" style="float:right">
            <p style="float:right;">
            <a href="../testclass.php">
             <button class="btn" type="button"><i class="icon-print"></i> Export</button></a>                              
            </p>             
        </div>
        </div>
 
        <div class="row-fluid">
                    <div class="span12">                    
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1><?php echo 'System Logs'; ?></h1>                               
                        </div>
                        <div class="block-fluid table-sorting clearfix">
                            <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_5">							
                                <thead>
                                    <tr>
              						<th><input type="checkbox" name="checkall"/></th>
                                        <th>Log Title</th>
                                        <th>Log Type</th>
                                        <th>Log Date</th>
                                        <th>IP Address</th>                   
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
                if($ids && in_array($row['log_id'],$ids))
                    $sel=true;
                ?>
            <tr id="<?php echo $row['log_id']; ?>">

                    <td><input type="checkbox" class="ckb" name="ids[]" value="<?php echo $row['log_id']; ?>" 
                            <?php echo $sel?'checked="checked"':''; ?>></td>
            
              <td nowrap>&nbsp;<a class="tip" href="log/<?php echo $row['log_id']; ?>"><?php echo Format::htmlchars($row['title']); ?></a> </td>
              <td nowrap><?php echo $row['log_type']; ?></td>
              <td nowrap>&nbsp;<?php echo Format::db_daydatetime($row['created']); ?></td>
              <td nowrap><?php echo $row['ip_address']; ?></td>
              
            </tr>
            <?php
			$color ++;
            } //end of while.
        else: //not tickets found!! set fetch error.
            echo 'No logs found';  
        endif; ?>           
            </tbody>                              
                            </table>
                        </div>
             </div>                      
                </div>   
        <div class="row-fluid"> 
            <div class="span6" style="width:96.718%;">       
                <p style="text-align:center;" id="actions">					
                    <input class="btn btn-danger" type="submit" name="delete" value="Delete Selected Entries" id="button">
                           
                </p>  
            </div> 
        </div>                     
		<div class="dr"><span></span></div>   
       </form>

<div style="display:none;" class="dialog" id="confirm-action">
    <h3>Please Confirm</h3>
    <a class="close" href="">&times;</a>
    <hr/>
    <p class="confirm-action" style="display:none;" id="delete-confirm">
        <font color="red"><strong>Are you sure you want to DELETE selected logs?</strong></font>
        <br><br>Deleted logs CANNOT be recovered.
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
