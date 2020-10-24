<?php error_reporting(0); ?>
<link href="css/stylesheets.css" rel="stylesheet" type="text/css" /> 
<style>
#bg_color td{background:#CCC;}
#text th {text-align:center;vertical-align:middle;}
#color,#bg_color #color{background:#999;color:#FFF;}
</style>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

?>

<div class="page-header"><h1>Monthly Category Wise<small> Report</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">    
</div>
</div>
<div class="row-fluid" style="min-height:700px;">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Monthly Wise Report Listing'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
          <table width="" cellpadding="0" cellspacing="0" class="table">
<thead>
  <tr id="text">
    <th rowspan="2">Category/Month</th>
        <?php 
    $sql_topic="select * from sdms_help_topic where topic_pid != '' ";
    $res_topic=db_query($sql_topic);
    while($row_topic= db_fetch_array($res_topic)){
	?>
    <th colspan="3" align="center"><?php echo $row_topic['topic']; ?></th>
    <?php }?>
    <th colspan="3" align="center" id="color">Total</th>
    </tr>

  <tr id="bg_color"> 
  <?php 
    $sql_topic="select * from sdms_help_topic where topic_pid != '' ";
    $res_topic=db_query($sql_topic);
    while($row_topic= db_fetch_array($res_topic)){
	?>
    <td>Institute</td>
    <td>Disposal</td>
    <td>Pending</td>
    <?php }?>
    <td id="color">Institute</td>
    <td id="color">Disposal</td>
    <td id="color">Pending</td>
  </tr>
  </thead>
   <?php 
   $year=2014;
	for ($i = 1; $i <= date('m'); $i++) { 
	$complete_institute=0; 	
	$complete_disposal=0;
	$complete_pending=0;
	$full_institute=0; 	
	$full_disposal=0;
	$full_pending=0;
	?>
      <tr>
        <td><?php echo date("M",strtotime('2014-'.$i)); ?></td>
         <?php 
        $sql_topic="select * from sdms_help_topic where topic_pid != '' ";
        $res_topic=db_query($sql_topic);
        while($row_topic= db_fetch_array($res_topic)){
        $sql_open="select count(ticket_id) as total from sdms_ticket where topic_id = '".$row_topic['topic_id']."' AND Year(created)='".$year."' AND Month(Created)='".$i."' ";
        $res_open=db_query($sql_open);
        $row_open= db_fetch_array($res_open);
        ?>
        <td><?php echo $row_open['total'];  ?></td>
        <?php $complete_institute  += $row_open['total'];?>
         <?php 
        $sql_close="select count(ticket_id) as dispose_total from sdms_ticket where topic_id = '".$row_topic['topic_id']."' AND Year(created)='".$year."' AND Month(Created)='".$i."' AND status='closed' ";
        $res_close=db_query($sql_close);
        $row_close= db_fetch_array($res_close);
        ?>
        <td><?php echo $row_close['dispose_total'];  ?></td>
        <?php $complete_disposal  += $row_close['dispose_total'];?>
        <td><?php 
        $total_pending=$row_open['total']-$row_close['dispose_total'];
        echo $total_pending;  ?></td>
        <?php $complete_pending  +=  $total_pending;?>
        <?php 
        }?>
        <td id="color"><?php echo  $complete_institute; ?></td>
        <td id="color"><?php echo  $complete_disposal; ?></td>
        <td id="color"><?php echo  $complete_pending;  ?></td>
      </tr>
  <?php }?>
  <thead>
  <tr>
    <th>Total</th>
      <?php 
    $sql_topic="select * from sdms_help_topic where topic_pid != '' ";
    $res_topic=db_query($sql_topic);
    while($row_topic= db_fetch_array($res_topic)){
	?>
    <?php 
	$sql_open="select count(ticket_id) as total from sdms_ticket where topic_id = '".$row_topic['topic_id']."' AND Year(created)='".$year."'";
	$res_open=db_query($sql_open);
    $row_open= db_fetch_array($res_open);
	?>
    <th><?php echo $row_open['total']; $full_institute +=$row_open['total']; ?></th>
    <?php $complete_institute  += $row_open['total'];?>
     <?php 
    $sql_close="select count(ticket_id) as dispose_total from sdms_ticket where topic_id = '".$row_topic['topic_id']."' AND Year(created)='".$year."' AND status='closed' ";
	$res_close=db_query($sql_close);
    $row_close= db_fetch_array($res_close);
	?>
    <th><?php echo $row_close['dispose_total'];  ?></th>
    <?php $full_disposal  += $row_close['dispose_total'];?>
    <th><?php echo $total_pending=$row_open['total']-$row_close['dispose_total'];?></th>
    <?php $full_pending  += $total_pending;?>
    <?php } ?>
    <th id="color"><?php echo $full_institute; ?></th>
    <th id="color"><?php echo $full_disposal;?></th>
    <th id="color"><?php echo $full_pending; ?></th>
  </tr>
  </thead>
</table>

  
        </div>
 </div>
 <div class="dr"><span></span></div>                  
</div>                   
</div><!--WorkPlace End-->  
</div>   
<script>
window.print() ;
window.close();
</script>