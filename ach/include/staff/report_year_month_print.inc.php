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

<div class="page-header"><h1>Year & Monthly Wise<small> Report</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">        
</div>
</div>
<div class="row-fluid" style="min-height:770px;">
   <div class="span12" style="width:1808px;">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Year & Monthly Wise Report Listing'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
<table width="100%" cellpadding="0" cellspacing="0" class="table">
  <thead>
  <tr id="text">
      <th rowspan="2">Month/<br/>Year</th>
      <?php 
   $year=2014;
	 for ($i = 1; $i <= 12; $i++) { ?>
    <th colspan="3"><?php echo date("M",strtotime('2014-'.$i)); ?></th>
    <?php }?>
    <th colspan="3" id="color">Total</th>
    </tr>
  <tr id="bg_color">
  <?php 
   $year=2014;
	 for ($i = 1; $i <= 12; $i++) { ?>
    <td>Ins</td>
    <td>Dis</td>
    <td>Pend</td>
    <?php }?>
    <td id="color">Institute</td>
    <td id="color">Disposed</td>
    <td id="color">Pending</td>
  </tr>
  </thead>
   <?php 
   $year=2014;
   for ($curren_year = 2011; $curren_year <= $year; $curren_year++) { ?>
      <tr>
      <td><?php echo $curren_year; ?></td>
      <?php for ($i = 1; $i <= 12; $i++) { 
	  
       $sql_open="select count(ticket_id) as total from sdms_ticket where Year(created)='".$curren_year."' AND Month(Created)='".$i."'";
	$res_open=db_query($sql_open);
    $row_open= db_fetch_array($res_open);
	?>
    <td><?php echo $row_open['total']; $total_Ins +=$row_open['total'];?></td>
    
     <?php 
    $sql_close="select count(ticket_id) as dispose_total from sdms_ticket where Year(created)='".$curren_year."' AND Month(Created)='".$i."' AND status='closed' ";
	$res_close=db_query($sql_close);
    $row_close= db_fetch_array($res_close);
	?>
    <td><?php echo $row_close['dispose_total'];   	$total_Dis += $row_close['dispose_total']	 ?></td>
    
    <td><?php 
	
	$total_pending=$row_open['total']-$row_close['dispose_total'];
	echo $total_pending; $total_Pend +=  $total_pending; ?></td>
        <?php }?>
        <td id="color"><?php echo  $total_Ins ; ?></td>
        <td id="color"><?php echo  $total_Dis ; ?></td>
        <td id="color"><?php echo  $total_Pend ; ?></td>
      </tr>
  <?php }?>
  <thead>
  <tr>
    <th>Total</th>
       <?php 
	    $year=2014;
for ($i = 1; $i <= 12; $i++) { 
$full_Ins=0;
$full_Dis=0;
$full_Pend=0;
	for ($curren_year = 2011; $curren_year <= $year; $curren_year++) { 
    
	$sql_open="select count(ticket_id) as total from sdms_ticket where Year(created)='".$curren_year."' AND Month(Created)='".$i."'";
	$res_open=db_query($sql_open);
    $row_open= db_fetch_array($res_open);
	$full_Ins +=$row_open['total'];
	 
    $sql_close="select count(ticket_id) as dispose_total from sdms_ticket where Year(created)='".$curren_year."' AND Month(Created)='".$i."' AND status='closed' ";
	$res_close=db_query($sql_close);
    $row_close= db_fetch_array($res_close);
	$full_Dis += $row_close['dispose_total'];
    
	
	$total_pending=$row_open['total']-$row_close['dispose_total'];
	$full_Pend +=  $total_pending; ?></th>
  <?php }  ?>
   <th><?php echo  $full_Ins ; $complete_Ins +=$full_Ins; ?></th>
    <th><?php echo  $full_Dis; $complete_Dis += $full_Dis; ?></th>
    <th><?php echo  $full_Pend; $complete_Pend += $full_Pend; ?></th>
  <?php  }?>
    <th id="color"><?php echo  $complete_Ins ; ?></th>
    <th id="color"><?php echo  $complete_Dis ; ?></th>
    <th id="color"><?php echo  $complete_Pend ; ?></th>

  </tr>
  </thead>
</table>

  
        </div>
 </div>  
 <div class="dr"><span></span></div>                     
</div> 

<!--<div class="row-fluid">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php //echo 'Total'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
          <table width="100%" cellpadding="0" cellspacing="0" class="table">
<thead>
  <tr>
    <th>Location</th>
    <th>Institute</th>
    <th>Disposal</th>
    <th>Pending</th>
    </tr>
</thead>

 
  <tr>
    <td>Urban</td>
    <td>1500</td>
    <td>830</td>
    <td>1370</td>
  </tr>
  <tr>
    <td>Rural</td>
    <td>1500</td>
    <td>830</td>
    <td>1370</td>
  </tr>
</table>

  
        </div>
 </div>                      
</div>-->                       
  
</div><!--WorkPlace End-->  
</div>   
<script>
window.print() ;
window.close();
</script>