<?php error_reporting(0); ?>
<link href="css/stylesheets.css" rel="stylesheet" type="text/css" /> 
<style>
#bg_color td{background:#CCC;}
#text th {text-align:center;vertical-align:middle;}
#color,#bg_color #color{background:#999;color:#FFF;}
</style>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

if($_REQUEST['staffid']) {
$staffid = $_REQUEST['staffid'];
}
?>

<div class="page-header"><h1>Monthly Gender Wise<small> Report</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">      
</div>
</div>
<div class="row-fluid" style="min-height:700px;">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Monthly Gender Wise Report'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
          <table width="100%" cellpadding="0" cellspacing="0" class="table">
<thead>
  <tr id="text">
      <th rowspan="2">Month/<br/>Gender</th>
    <th colspan="3" align="center">Male</th>
    <th colspan="3" align="center">Female</th>
    <th colspan="3" align="center" id="color">Total</th>
    </tr>

  <tr id="bg_color">
    <td>Institute</td>
    <td>Disposal</td>
    <td>Pending</td>
    <td>Institute</td>
    <td>Disposal</td>
    <td>Pending</td>
    <td id="color">Institute</td>
    <td id="color">Disposal</td>
    <td id="color">Pending</td>
  </tr>
  </thead>
  <?php 
   $year=2014;
	 for ($i = 1; $i <= date('m'); $i++) { 
	 $total_m_ins=0;
	 $total_m_dip=0;
	 $total_m_pend=0;
	 
	 ?>
  <tr>
    <td><?php echo date("M",strtotime('2014-'.$i)); ?></td>
    <?php 
    $sql_open="select count(ticket_id) as total from sdms_ticket where gender = 'male' AND Year(created)='".$year."' AND Month(Created)='".$i."' ";
	$res_open=db_query($sql_open);
    $row_open= db_fetch_array($res_open);
	?>
    <td><?php echo $row_open['total']; $total_m_ins += $row_open['total']; $male_Institute +=$row_open['total'];?></td>
    
     <?php 
    $sql_close="select count(ticket_id) as dispose_total from sdms_ticket where gender = 'male' AND Year(created)='".$year."' AND Month(Created)='".$i."' AND status='closed' ";
	$res_close=db_query($sql_close);
    $row_close= db_fetch_array($res_close);
	?>
    <td><?php echo $row_close['dispose_total']; $total_m_dip += $row_close['dispose_total']; $male_Disposal +=$row_close['dispose_total']; ?></td>
    
    <td><?php 
	$total_pending=$row_open['total']-$row_close['dispose_total'];
	echo $total_pending; $total_m_pend += $total_pending; $male_Pending +=$total_pending; ?></td>
<!--For Female-->
    <?php 
    $sql_open="select count(ticket_id) as total from sdms_ticket where gender = 'female' AND Year(created)='".$year."' AND Month(Created)='".$i."'";
	$res_open=db_query($sql_open);
    $row_open= db_fetch_array($res_open);
	?>
    <td><?php echo $row_open['total']; $total_m_ins +=$row_open['total']; $female_Institute +=$row_open['total'];?></td>
    
     <?php 
    $sql_close="select count(ticket_id) as dispose_total from sdms_ticket where gender = 'female'  AND Year(created)='".$year."' AND Month(Created)='".$i."' AND status='closed' ";
	$res_close=db_query($sql_close);
    $row_close= db_fetch_array($res_close);
	?>
    <td><?php echo $row_close['dispose_total']; $total_m_dip += $row_close['dispose_total']; $female_Disposal +=$row_close['dispose_total']; ?></td>
    
    <td><?php 
	$total_pending=$row_open['total']-$row_close['dispose_total'];
	echo $total_pending; $total_m_pend += $total_pending;  $female_Pending +=$row_close['dispose_total'];  ?>  </td>
    
    <th id="color"><?php echo $total_m_ins;  $complete_m_ins +=$total_m_ins; ?></th>
    <th id="color"><?php echo $total_m_dip;  $complete_m_dip +=$total_m_dip; ?></th>
    <th id="color"><?php echo $total_m_pend; $complete_m_pend +=$total_m_pend; ?></th>
  </tr>
  <?php }?>
  <thead>
  <tr>
    <th>Total</th>
    <th><?php echo $male_Institute; ?></th>
    <th><?php echo $male_Disposal; ?></th> 	 	
    <th><?php echo $male_Pending; ?></th>
    <th><?php echo $female_Institute; ?></th>
    <th><?php echo $female_Disposal; ?></th>
    <th><?php echo $female_Pending; ?></th>
    <th id="color"><?php echo $complete_m_ins; ?></th>
    <th id="color"><?php echo $complete_m_dip; ?></th>
    <th id="color"><?php echo $complete_m_pend; ?></th>
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