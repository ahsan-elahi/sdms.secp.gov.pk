<?php error_reporting(0); ?>
<link href="css/stylesheets.css" rel="stylesheet" type="text/css" /> 
<style>
#bg_color td{background:#CCC;}
#text th {text-align:center;vertical-align:middle;}
#table td {vertical-align:middle;}
#color,#bg_color #color{background:#999;color:#FFF;}
</style>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

if($_REQUEST['staffid']) {
$staffid = $_REQUEST['staffid'];
}
?>

<div class="page-header"><h1>Priority  Wise<small> Report</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;"> 
</div>
</div>
<div class="row-fluid" style="min-height:700px;">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Priority Wise Report Listing'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
<table width="100%" cellpadding="0" cellspacing="0" class="table" id="table">
<thead>

<!--<td>Institute</td>
    <td>Disposal</td>
    <td>Pending</td>-->
  <tr id="text">
    <th rowspan="2">Month</th>
    <th rowspan="2">Action</th>
    <th colspan="3">Priority</th>
    <th rowspan="2" id="color">Total</th>
    </tr>


  <tr id="bg_color">
    <td>Low</td>
    <td>Normal</td>
    <td>High</td>
  </tr>
  </thead>
	<?php 
    $year=2014;
    for ($i = 1; $i <= date('m'); $i++) { 
	$count_institute=0;
	$count_disposal=0;
	$count_pending=0;
	?>
    <tr>
        <td colspan="6"><strong><?php echo date("M",strtotime('2014-'.$i)); ?></strong></td>
        </tr>
        <tr>
        <td style="border-right:none;"></td>
        <td style="border-left:none;">Institute</td>
		<?php
        $sql_open_low="select count(ticket_id) as open_low from sdms_ticket where priority_id='1' AND Year(created)='".$year."' AND Month(Created)='".$i."'";
        $res_open_low=db_query($sql_open_low);
        $row_open_low= db_fetch_array($res_open_low);
        ?>
        <td><?php echo $row_open_low['open_low']; $count_institute = $row_open_low['open_low'];
		$total_low +=$row_open_low['open_low'];  ?></td>
        <?php
        $sql_open_normal="select count(ticket_id) as open_normal from sdms_ticket where priority_id='2' AND Year(created)='".$year."' AND Month(Created)='".$i."' ";
        $res_open_normal=db_query($sql_open_normal);
        $row_open_normal= db_fetch_array($res_open_normal);
        ?>
        <td><?php echo $row_open_normal['open_normal']; $count_institute += $row_open_normal['open_normal'];$total_normal +=$row_open_normal['open_normal']; ?></td>
        <?php
        $sql_open_high="select count(ticket_id) as open_high from sdms_ticket where priority_id='3' AND Year(created)='".$year."' AND Month(Created)='".$i."'";
        $res_open_high=db_query($sql_open_high);
        $row_open_high= db_fetch_array($res_open_high);
        ?>
        <td><?php echo $row_open_high['open_high']; $count_institute += $row_open_high['open_high'];$total_high +=$row_open_high['open_high'];  ?></td>
        <td id="color"><?php echo $count_institute; ?></td>
        </tr>
        <tr>
        <td style="border-right:none;"></td>
        <td style="border-left:none;">Disposal</td>
        <?php
        $sql_open_low_dis="select count(ticket_id) as closed_low from sdms_ticket  where status = 'closed' AND priority_id='1' AND Year(created)='".$year."' AND Month(Created)='".$i."'";
        $res_open_low_dis=db_query($sql_open_low_dis);
        $row_open_low_dis= db_fetch_array($res_open_low_dis);
        ?>
        <td><?php echo $row_open_low_dis['closed_low']; $count_disposal = $row_open_low_dis['closed_low'];
		$total_low +=$row_open_low_dis['closed_low'];  ?></td>
        <?php
        $sql_close_normal_dis="select count(ticket_id) as closed_normal from sdms_ticket  where status = 'closed' AND priority_id='2' AND Year(created)='".$year."' AND Month(Created)='".$i."'";
        $res_close_normal_dis=db_query($sql_close_normal_dis); 
        $row_close_normal_dis= db_fetch_array($res_close_normal_dis);
        ?>
        <td><?php echo $row_close_normal_dis['closed_normal']; $count_disposal += $row_close_normal_dis['closed_normal'];
		$total_normal +=$row_close_normal_dis['closed_normal']; ?></td>
        <?php
        $sql_open_high_dis="select count(ticket_id) as closed_high from sdms_ticket  where status = 'closed' AND priority_id='3' AND Year(created)='".$year."' AND Month(Created)='".$i."'";
        $res_open_high_dis=db_query($sql_open_high_dis);
        $row_open_high_dis= db_fetch_array($res_open_high_dis);
        ?>
        <td><?php echo $row_open_high_dis['closed_high']; $count_disposal += $row_open_high_dis['closed_high'];
		$total_high +=$row_open_high_dis['closed_high'];  ?></td>
        <td id="color"><?php echo $count_disposal; ?></td>
        </tr>
        <tr>
        <td style="border-right:none;"></td>
        <td style="border-left:none;">Pending</td>
		
        <td><?php echo $pending_low= $row_open_low['open_low']-$row_open_low_dis['closed_low'];
		
		 $count_pending = $pending_low;$total_low +=$pending_low; ?></td>
        
        <td><?php 
		$pending_normal=$row_open_normal['open_normal']-$row_close_normal_dis['closed_normal'];
		echo $pending_normal; $count_pending += $pending_normal;$total_normal +=$pending_normal; ?></td>
        
        <td><?php 
		$pending_high=$row_open_high['open_high']-$row_open_high_dis['closed_high'];
		echo $pending_high; $count_pending += $pending_high;$total_high +=$pending_high;  ?></td>
        <td id="color"><?php echo $count_pending; ?></td>
        </tr>
    <?php }?>
   <!--<thead>
  <tr>
    <th>Total</th>
    <th>&nbsp;</th>
    <th><?php //echo $total_low; ?></th>
    <th><?php //echo $total_normal; ?></th>
    <th><?php //echo $total_high; ?></th>
    <th id="color"><?php //echo $total_low+$total_normal+$total_high; ?></th>
  </tr>
  </thead>-->
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