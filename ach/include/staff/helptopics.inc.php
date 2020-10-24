<?php
if(!defined('OSTADMININC') || !$thisstaff->isAdmin()) die('Access Denied');

$qstr='';
$sql='SELECT topic.* '
    //.', IF(ptopic.topic_pid IS NULL, topic.topic, CONCAT_WS(" / ", ptopic.topic, topic.topic)) as name '
	.', topic.topic as name ' 
    .', dept.dept_name as department '
    .', priority_desc as priority '
    .' FROM '.TOPIC_TABLE.' topic '
    .' LEFT JOIN '.TOPIC_TABLE.' ptopic ON (ptopic.topic_id=topic.topic_pid) '
    .' LEFT JOIN '.DEPT_TABLE.' dept ON (dept.dept_id=topic.dept_id) '
    .' LEFT JOIN '.TICKET_PRIORITY_TABLE.' pri ON (pri.priority_id=topic.priority_id) ';
$sql.=' WHERE 1';
if($_REQUEST['cat_id']=='')
{
$sql.=' AND topic.topic_pid = 0 ';
}else{
$sql.=' AND topic.topic_pid = '.$_REQUEST['cat_id'].' ';
}

if($_REQUEST['dpart_id']!=''){
$sql.=' AND dept.dept_id = '.$_REQUEST['dpart_id'].' ';	
}

$sortOptions=array('name'=>'name','status'=>'topic.isactive','type'=>'topic.ispublic',
                   'dept'=>'department','priority'=>'priority','updated'=>'topic.updated');
$orderWays=array('DESC'=>'DESC','ASC'=>'ASC');
$sort=($_REQUEST['sort'] && $sortOptions[strtolower($_REQUEST['sort'])])?strtolower($_REQUEST['sort']):'name';
//Sorting options...
if($sort && $sortOptions[$sort]) {
    $order_column =$sortOptions[$sort];
}
$order_column=$order_column?$order_column:'topic.topic';

if($_REQUEST['order'] && $orderWays[strtoupper($_REQUEST['order'])]) {
    $order=$orderWays[strtoupper($_REQUEST['order'])];
}
$order=$order?$order:'ASC';

if($order_column && strpos($order_column,',')){
    $order_column = str_replace(','," $order,",$order_column);
}
$x=$sort.'_sort';
$$x=' class="'.strtolower($order).'" ';
$order_by="$order_column $order ";
//$total=db_count('SELECT count(*) FROM '.TOPIC_TABLE.' topic WHERE topic.topic_pid = 0 ');
$total=db_count($sql);
$page=($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;
$pageNav=new Pagenate($total, $page, PAGE_LIMIT);
$pageNav->setURL();
//Ok..lets roll...create the actual query'helptopics.php',$qstr.'&sort='.urlencode($_REQUEST['sort']).'&order='.urlencode($_REQUEST['order'])
$qstr.='&order='.($order=='DESC'?'ASC':'DESC');
$query="$sql GROUP BY topic.topic_id ORDER BY $order_by LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
$res=db_query($query);
if($res && ($num=db_num_rows($res)))
    $showing=$pageNav->showing().' help topics';
else
    $showing='No help topic found!';
?>
<style>
#tSortable_7_paginate{
	display:none;
	}
#tSortable_7_filter{
display:none;
}	
#tSortable_7_length{
	display:none;}	
</style>
<div class="page-header"><h1>Complaint  <small>Categories</small></h1></div>    
<div class="row-fluid">
                    <div class="span12">                    
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Search</h1>                          
                        </div>
                        <div class="block-fluid table-sorting clearfix">
                          <form action="" method="get" id="save" enctype="multipart/form-data">
                          <table cellpadding="0" cellspacing="0" width="100%" class="table"  >
                          	<?php /*?><tr>
                            <th width="12%" style="padding-top:12px;">Tender No.</th>
                            <td width="20%"><input type="text" name="tender_ref" <?php if(isset($_REQUEST['tender_ref'])){?> value="<?php echo $_REQUEST['tender_ref']; ?>" <?php } ?> /></td>
                            <th width="12%" style="padding-top:12px;">From Date</th>
                            <td width="20%">
                            <input <?php if(isset($_REQUEST['fromDate'])){ ?> value="<?php echo $_REQUEST['fromDate']; ?>" <?php } ?> type="text" name="fromDate" class="TenderDatepicker"  /></td>
                            <th width="10%" style="padding-top:12px;">To Date</th>
                            <td width="20%">
                            <input <?php if(isset($_REQUEST['toDate'])){ ?> value="<?php echo $_REQUEST['toDate']; ?>" <?php } ?> type="text" name="toDate" class="TenderDatepicker"  /></td>
                          	</tr><?php */?>
                            <?php /*?><tr>
                                    <th width="20%" style="padding-top:12px;">By Category</th>
                                    <td  >
                                    <select name="dpart_id" >
                                    <option value="">--Select Department--</option>
                                    <?php 
									
									 $sql_get_topics='SELECT ht.topic_id, ht.topic as name '
                                    .' FROM '.TOPIC_TABLE. ' ht '
                                    .' WHERE ht.topic_pid = 0 ';
                                    $res_get_topic=mysql_query($sql_get_topics);
										while($row_topic=mysql_fetch_array($res_get_topic)){
										 ?>
                                        <option value="<?php echo $row_topic['topic_id'] ;?>"><?php echo $row_topic['name'] ;?></option>
                                        <?php } ?>
                                        </select>
                                    </td>
                                    
                                    </tr><?php */?>
<tr>
<th width="20%" style="padding-top:12px;">By Department</th>
<td  >
<select name="dpart_id" >
<option value="">--Select Department--</option>
<?php 
$sql_get_dept='SELECT * from  sdms_department WHERE 1 ';
$res_get_dept = mysql_query($sql_get_dept);
while($row_dept = mysql_fetch_array($res_get_dept)){
?>
<option value="<?php echo $row_dept['dept_id'] ;?>" <?php if($row_dept['dept_id']==$_REQUEST['dpart_id']){ ?> selected <?php }?>><?php echo $row_dept['dept_name'] ;?></option>
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
                     
<form action="helptopics.php" method="POST" name="topics">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="mass_process" >
<input type="hidden" id="action" name="a" value="" >

<div class="row-fluid">
<div class="span3" style="float:right">
<p align="right" style="float:right">
<a href="helptopics.php?a=add" class="Icon newHelpTopic">
<button class="btn" type="button"><i class="icon-plus-sign"></i>Add New Complaint Category</button></a>                              
</p>             
</div>
</div>
<div class="row-fluid">
<div class="span12">                    
<div class="head clearfix">
    <div class="isw-grid"></div>
    <h1><?php echo 'Complaint Categories'; ?></h1>                               
</div>

<div class="block-fluid table-sorting clearfix">
    <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_7">
        <thead>
            <tr>
            <th><input type="checkbox" name="checkall"/></th>
            <th>Complaint Category</th> 
            <th>Status</th> 
            <th>Type</th>
            <th>Priority</th> 
            <th>Department</th>
            <th>Last Updated</th> 
            <th>Action</th>                     
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
    if($ids && in_array($row['topic_id'],$ids))
        $sel=true;
    ?>
<tr id="<?php echo $row['topic_id']; ?>" >
    <td align="center" class="nohover">
      <input type="checkbox" class="ckb" name="ids[]" value="<?php echo $row['topic_id']; ?>"<?php echo $sel?'checked="checked"':''; ?>>
    </td>
    <td><a href="helptopics.php?cat_id=<?php echo $row['topic_id']; ?>"><?php echo $row['name']; ?></a>&nbsp;</td>
    <td><?php echo $row['isactive']?'Active':'<b>Disabled</b>'; ?></td>
    <td><?php echo $row['ispublic']?'Public':'<b>Private</b>'; ?></td>
    <td><?php echo $row['priority']; ?></td>
    <td><a href="departments.php?id=<?php echo $row['dept_id']; ?>"><?php echo $row['department']; ?></a></td>
    <td>&nbsp;<?php echo Format::db_datetime($row['updated']); ?></td>
    <td><p class="about"><a href="helptopics.php?id=<?php echo $row['topic_id']; ?>"><span class="icon-pencil"></span></a></p></td>
</tr>
<?php
$color++;
} //end of while.
endif; ?>
        </tbody> 
        <?php if($num>0){  ?>      
<tfoot>
<tr>
<td colspan="4"><?php echo '<div align="left" style="width:40%;float:left;">'.$showing .'&nbsp;'.'&nbsp;'.'&nbsp;'.'</div>';?></td>
<td colspan="4" align="right"><?php echo '<div class="dataTables_paginate paging_full_numbers">Page:'.$pageNav->getPageLinks().'</div>'; ?></td>
</tr>
</tfoot>                            
<?php }?>                             
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



