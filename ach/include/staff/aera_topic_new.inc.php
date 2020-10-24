<style type="text/css">
.DETAILS {
    display: none;
}
</style>
<script type="text/javascript" language="javascript">
function show_item(id)
{
    if ($('#show_temp_item_'+id).prop("click")) {
		if ($('#item_temp_section_'+id).css("display") == 'none') {
         $('#item_temp_section_'+id).show();
    	}
    else {
		$('#item_temp_section_'+id).hide();
    }
	}
}
</script>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
  $i=1;
  ?>
<div class="page-header"><h1>Overall Complaints  <small> Summary</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">
    <p align="right" style="float:right;">
     <a id="ticket-print" class="action-button" href="" onclick="openWin();">
     <button class="btn" type="button"><i class="icon-print"></i> Print</button></a>                              
    </p>             
</div>
</div>
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
//echo $query;
$result = db_query($query);
$showing=db_num_rows($result)?$pageNav->showing():"";
?>
       <table cellpadding="0" cellspacing="0" width="100%" class="table">	
    
    
  
<?php
    $query="SELECT * FROM sdms_department dept WHERE dept.dept_p_id=0 order by dept.dept_id desc";
  $result = db_query($query);
  while ($row = db_fetch_array($result)) {
	    $nums_total=0;
  $nums_total_closed=0;
   ?>
   <tr id="headerStyle">
    <td width="148" ><h3  style="border-bottom: 1px solid #d5d5d5;
    color: #30557f;
    font-family: Cambria,Georgia,serif;
    font-style: italic;
    line-height: 20px;
    margin: 0;
    padding-bottom: 10px;
    text-shadow: 1px 1px 0 #fff;"><?php echo $row['dept_name']; ?></h3></td>
    <td colspan="2">Open Complaints</td>
    <td colspan="2">Resolved Complaints</td>
    <td colspan="2">Overdue Complaints</td>
    <td colspan="2">Total Complaints</td>
  </tr>
  <?php 
   $query_sub_dept="SELECT * FROM sdms_department dept WHERE dept.dept_p_id='".$row['dept_id']."' order by dept.dept_id asc";
              $result_sub_dept = db_query($query_sub_dept);
              while ($row_sub_dept = db_fetch_array($result_sub_dept)) {
  ?>
  <tr id="headerStyle">
        <td rowspan="2">
        <span style="float:left;width:350px;margin-left:30px;font-size:16px;margin-top:5px;">
        <?php if($row_sub_dept['dept_name']!="") echo $row_sub_dept['dept_name']; else echo "No Dept";?>
        <img src='img/lens_icon.png' onclick="show_item(<?php echo $row_sub_dept['dept_id']; ?>)" id='show_temp_item_<?php echo $row_sub_dept['dept_id']; ?>' />
        </span>
        </td>
        <td colspan="2" style="text-align:right;"><?php 
                $sql_total_open="Select * from sdms_ticket where status='open' AND isoverdue != '1' AND  dept_id='".$row_sub_dept['dept_id']."'";
                $result_total_open = db_query($sql_total_open);
                $nums_total_open = db_num_rows($result_total_open);
                ?><a href="tickets.php?a=search&deptId=<?php echo $row_sub_dept['dept_id']; ?>">
                <?php echo $nums_total_open;
                ?></a></td>
        <td colspan="2" style="text-align:right;"><?php 
                $sql_total_closed="Select * from sdms_ticket where dept_id='".$row_sub_dept['dept_id']."' AND status='closed'";
                $result_total_closed = db_query($sql_total_closed);
                $nums_total_closed = db_num_rows($result_total_closed);
                ?>
                <a href="tickets.php?a=search&deptId=<?php echo $row_sub_dept['dept_id']; ?>">
                <?php echo $nums_total_closed;
                ?></a></td>
        <td colspan="2" style="text-align:right;"><?php 
                $sql_total_isoverdue="Select * from sdms_ticket where dept_id='".$row_sub_dept['dept_id']."' AND status='open' AND isoverdue='1'";
                $result_total_isoverdue = db_query($sql_total_isoverdue);
                $nums_total_isoverdue = db_num_rows($result_total_isoverdue);
                ?>
                <a href="tickets.php?a=search&deptId=<?php echo $row_sub_dept['dept_id']; ?>&status=overdue"><?php echo $nums_total_isoverdue;?></a>
                </td>
                <td colspan="2" style="text-align:right;"><?php 
                $sql_total="Select * from sdms_ticket where dept_id='".$row_sub_dept['dept_id']."'";
                $result_total = db_query($sql_total);
                $nums_total = db_num_rows($result_total);
                ?><a href="tickets.php?a=search&deptId=<?php echo $row_sub_dept['dept_id']; ?>"><?php echo $nums_total;?></a></td>
    </tr>
    <tr></tr>
    <tr class="DETAILS"  id="item_temp_section_<?php echo $row_sub_dept['dept_id']; ?>">
        <td colspan="10">
            <table style="width:100%" style="border-right:1px solid;">
                <tr>
                <td style="width:371px;border-left:1px #CCCCCC solid;" colspan="1">Complaint Categories</td>
                
                <td style="width:112px;">Open Days</td>
                <td style="width:141px;">Resolved Days</td>
                <td colspan="2" style="width:148px;border-right:1px #CCCCCC solid;">Overdue</td>
                <td style="width:110px;">Total</td>
                </tr>
              <?php
                $query_topic="SELECT * FROM sdms_help_topic order by topic asc";
              $result_topic = db_query($query_topic);
              while ($row_topic = db_fetch_array($result_topic)) {
               ?>
              <tr>
                <th colspan="1" style="border-left:1px #CCCCCC solid;bottom-left:1px #CCCCCC solid;"><a><span style="float:left; "><?php if($row_topic['topic']!="") echo $row_topic['topic']; else echo "No Category";?></span></a></th>
                
                <td style="text-align:right;border-bottom:1px #CCCCCC solid;"><?php 
                $sql_total_open="Select * from sdms_ticket where status='open' AND dept_id='".$row_sub_dept['dept_id']."' AND topic_id='".$row_topic['topic_id']."'";
                $result_total_open = db_query($sql_total_open);
                $nums_total_open = db_num_rows($result_total_open);
                ?><a href="tickets.php?a=search&deptId=<?php echo $row_sub_dept['dept_id']; ?>&topicId=<?php echo $row_topic['topic_id'] ?>"><?php echo $nums_total_open; ?></td>
                <td style="text-align:right;border-bottom:1px #CCCCCC solid;"><?php 
                $sql_total_closed="Select * from sdms_ticket where status='closed' AND dept_id='".$row_sub_dept['dept_id']."' AND topic_id='".$row_topic['topic_id']."'";
                $result_total_closed = db_query($sql_total_closed);
                $nums_total_closed = db_num_rows($result_total_closed);
                ?><a href="tickets.php?a=search&deptId=<?php echo $row_sub_dept['dept_id']; ?>&topicId=<?php echo $row_topic['topic_id'] ?>"><?php echo $nums_total_closed;
                ?></td>
                <td colspan="2" style="text-align:right;border-right:1px #CCCCCC solid;border-bottom:1px #CCCCCC solid;"><?php 
                $sql_total_isoverdue="Select * from sdms_ticket where status='open' AND  isoverdue='1' AND dept_id='".$row_sub_dept['dept_id']."' AND topic_id='".$row_topic['topic_id']."'";
                $result_total_isoverdue = db_query($sql_total_isoverdue);
                $nums_total_isoverdue = db_num_rows($result_total_isoverdue);
                ?>
                <a href="tickets.php?a=search&deptId=<?php echo $row_sub_dept['dept_id']; ?>&topicId=<?php echo $row_topic['topic_id'] ?>"><?php echo $nums_total_isoverdue;
                ?></a></td>
                <td  style="text-align:right;border-bottom:1px #CCCCCC solid;"><?php 
                $sql_total="Select * from sdms_ticket where dept_id='".$row_sub_dept['dept_id']."' AND topic_id='".$row_topic['topic_id']."' ";
                $result_total = db_query($sql_total);
                $nums_total = db_num_rows($result_total);
                ?><a href="tickets.php?a=search&deptId=<?php echo $row_sub_dept['dept_id']; ?>&topicId=<?php echo $row_topic['topic_id'] ?>"><?php
                echo $nums_total;
                ?></td>
              </tr>
             <?php 
			 $i++; 
			 }  ?>
           </table>
      </td>
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