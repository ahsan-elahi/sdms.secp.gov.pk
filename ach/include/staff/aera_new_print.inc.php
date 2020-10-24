<div class="page-header"><h1>Overall Complaints  <small> Summary</small></h1></div>
<div class="row-fluid">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Overall Complaints Summary'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
            <table cellpadding="0" cellspacing="0" width="100%" class="table">	
<?php 				
$qselect='SELECT count(ticket_id) as ticketno ,t.topic_id, d.topic ';
$qfrom='FROM '.TICKET_TABLE.' t, sdms_help_topic d ';
$qwhere=' WHERE t.topic_id=d.topic_id group by t.topic_id ';

$total=db_count("SELECT count(*) $qfrom $qwhere");
$pagelimit=30;

//pagenate
$pageNav=new Pagenate($total,$page,$pagelimit);
$pageNav->setURL('admin.php',$qstr);
$query="$qselect $qfrom $qwhere ORDER BY t.created DESC LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();

$result = db_query($query);
$showing=db_num_rows($result)?$pageNav->showing():"";
?>
       <table cellpadding="0" cellspacing="0" width="120%" class="table">	
    
    
  
<?php
    $query="SELECT * FROM sdms_department dept WHERE dept.dept_p_id=0 order by dept.dept_id desc";
  $result = db_query($query);
  while ($row = db_fetch_array($result)) {
	    $nums_total=0;
  $nums_total_closed=0;
   ?>
   <tr id="headerStyle">
    <td width="30%"><h4><?php echo $row['dept_name']; ?></h4></td>
    <td colspan="2">Open </td>
    <td colspan="2">Resolved </td>
    <td colspan="2">Overdue </td>
    <td colspan="2">Total </td>
  </tr>
  <?php 
   $query_sub_dept="SELECT * FROM sdms_department dept WHERE dept.dept_p_id='".$row['dept_id']."' order by dept.dept_id asc";
              $result_sub_dept = db_query($query_sub_dept);
              while ($row_sub_dept = db_fetch_array($result_sub_dept)) {
  ?>
  <tr id="headerStyle">
        <td rowspan="2">
        <?php if($row_sub_dept['dept_name']!="") echo $row_sub_dept['dept_name']; else echo "No Dept";?>
        </td>
        <td colspan="2" style="text-align:right;width:30%"><?php 
                $sql_total_open="Select * from sdms_ticket where status='open' AND isoverdue != '1' AND  dept_id='".$row_sub_dept['dept_id']."'";
                $result_total_open = db_query($sql_total_open);
                $nums_total_open = db_num_rows($result_total_open);
                 echo $nums_total_open;
                ?></td>
        <td colspan="2" style="text-align:right;"><?php 
                $sql_total_closed="Select * from sdms_ticket where dept_id='".$row_sub_dept['dept_id']."' AND status='closed'";
                $result_total_closed = db_query($sql_total_closed);
                $nums_total_closed = db_num_rows($result_total_closed);
                 echo $nums_total_closed;
                ?></td>
        <td colspan="2" style="text-align:right;"><?php 
                $sql_total_isoverdue="Select * from sdms_ticket where dept_id='".$row_sub_dept['dept_id']."' AND status='open' AND isoverdue='1'";
                $result_total_isoverdue = db_query($sql_total_isoverdue);
                $nums_total_isoverdue = db_num_rows($result_total_isoverdue);
               
			   echo $nums_total_isoverdue;?>
                </td>
                <td colspan="2" style="text-align:right;"><?php 
                $sql_total="Select * from sdms_ticket where dept_id='".$row_sub_dept['dept_id']."'";
                $result_total = db_query($sql_total);
                $nums_total = db_num_rows($result_total);
				echo $nums_total;?></td>
    </tr>
    
    <?php }?>
  <tr id="headerStyle">
    <td width="148"><b style="font:bold;">Grand Total</b></td>
    <td colspan="2" style="text-align:right;"><b style="font:bold;">
	<?php
	  $nums_total=0;
  $nums_total_closed=0;
    $query_sub_dept="SELECT * FROM sdms_department dept WHERE dept.dept_p_id='".$row['dept_id']."' order by dept.dept_id asc";
	
  $result_sub_dept = db_query($query_sub_dept);
  while ($row_sub_dept = db_fetch_array($result_sub_dept)) {
	$sql_total="Select * from sdms_ticket where dept_id= '".$row_sub_dept['dept_id']."'";
	$result_total = db_query($sql_total);
	$nums_total += db_num_rows($result_total);
	
  } echo $nums_total;
	?></b></td>
    <td colspan="2" style="text-align:right;"><b style="font:bold;"><?php 
	    $query_sub_dept="SELECT * FROM sdms_department dept WHERE dept.dept_p_id='".$row['dept_id']."' order by dept.dept_id asc";
  $result_sub_dept = db_query($query_sub_dept);
  while ($row_sub_dept = db_fetch_array($result_sub_dept)) {
	
	$sql_total_closed="Select * from sdms_ticket where status='closed' AND dept_id ='".$row_sub_dept['dept_id']."'";
	$result_total_closed = db_query($sql_total_closed);
	$nums_total_closed += db_num_rows($result_total_closed);
  }
	echo $nums_total_closed;
	?></b></td>
    <td colspan="2" style="text-align:right;"><b style="font:bold;"><?php 
	
echo $nums_total-$nums_total_closed;

	?></b></td>
    <td colspan="2" style="text-align:right;"><b style="font:bold;"><?php 
	
echo $nums_total-$nums_total_closed;

	?></b></td>
  </tr>

<?php }  ?>
</table>
      </div>
 </div>                        
<div class="dr"><span></span></div>   
</div><!--WorkPlace End-->  
</div>