<?php error_reporting(0); ?>
<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("report_monthly_districtwise_print.php","Print Report","toolbar=yes,width=1500px,height=14031px");
window.location.reload();
}
</script>
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
<div class="page-header"><h1>Monthly District Wise<small> Report</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">   
<input style="margin-left:160px;" id="report_create" onclick="openWin();" class="btn" type="button" value="Print">        
</div>
</div>
<div class="row-fluid" style="min-height:700px;">
   <div class="span12" style="width:1808px;">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Monthly District Wise Report Listing'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
          <table width="70%" cellpadding="0" cellspacing="0" class="table">
<thead>
  <tr id="text">
    <th rowspan="2">District/<br/>Month</th>
     <?php 
   $year=2014;
	 for ($i = 1; $i <= date('m'); $i++) { ?>
    <th colspan="3"><?php echo date("M",strtotime('2014-'.$i)); ?></th>
     <?php }?>
    <th colspan="3" id="color">Total</th>
    </tr>
  <tr id="bg_color">
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
  $sql_districts="select * from sdms_districts";
 $res_districts=db_query($sql_districts);
  while ($row_districts = db_fetch_array($res_districts)) {  ?>
  <tr>
    <td><?php echo $row_districts['District'] ?></td>
     <?php 
	 $complete_institute=0;
	 $complete_disposal=0;
   	 $complete_pending=0;
	 $year=2014;
	 for ($i = 1; $i <= date('m'); $i++) { 
	 
    $sql_open="select count(ticket_id) as total from sdms_ticket where district = '".$row_districts['District_ID']."' AND Year(created)='".$year."' AND Month(Created)='".$i."'";
	$res_open=db_query($sql_open);
    $row_open= db_fetch_array($res_open);
	$complete_institute  += $row_open['total'];
	?>
    <td><?php echo $row_open['total'];  ?></td>
    
	<?php 
    $sql_close="select count(ticket_id) as dispose_total from sdms_ticket where district = '".$row_districts['District_ID']."' AND Year(created)='".$year."' AND Month(Created)='".$i."' AND status='closed' ";
	$res_close=db_query($sql_close);
    $row_close= db_fetch_array($res_close);
	 $complete_disposal  += $row_close['dispose_total'];
	?>
    <td><?php echo $row_close['dispose_total'];  ?></td>
        
    <td><?php 
	$total_pending=$row_open['total']-$row_close['dispose_total'];
	$complete_pending  +=  $total_pending;
	echo $total_pending;  ?></td>
	 <?php }?>
    <td id="color"><?php echo  $complete_institute; ?></td>
    <td id="color"><?php echo  $complete_disposal; ?></td>
    <td id="color"><?php echo  $complete_pending;  ?></td>
  </tr>
  <?php } ?>
  <thead>
  <tr>
    <th>Total</th>
      <?php 
   $year=2014;
  for ($i = 1; $i <= date('m'); $i++) { 
  $sql_districts="select * from sdms_districts";
 $res_districts=db_query($sql_districts);
 $full_institute=0;
 $full_disposal=0;
 $full_pending=0;
  while ($row_districts = db_fetch_array($res_districts)) { 
  ?>
  <?php 
	$sql_open="select count(ticket_id) as total from sdms_ticket where district = '".$row_districts['District_ID']."' AND Year(created)='".$year."' AND Month(Created)='".$i."'";
	$res_open=db_query($sql_open);
    $row_open= db_fetch_array($res_open);
	$full_institute +=$row_open['total']; 
    
	$sql_close="select count(ticket_id) as dispose_total from sdms_ticket where district = '".$row_districts['District_ID']."' AND Year(created)='".$year."' AND Month(Created)='".$i."' AND status='closed' ";
	$res_close=db_query($sql_close);
    $row_close= db_fetch_array($res_close);
	$full_disposal  += $row_close['dispose_total'];
	$total_pending=$row_open['total']-$row_close['dispose_total'];
	$full_pending  += $total_pending;?>
    <?php }?>
    <th><?php echo $full_institute; $final_institute +=$full_institute;?></th>
    <th><?php echo $full_disposal;  $final_disposal +=$full_disposal;?></th>
    <th><?php echo $full_pending;   $final_pending +=$full_pending; ?></th>
    <?php }?>
    <th id="color"><?php echo $final_institute; ?></th>
    <th id="color"><?php echo $final_disposal;?></th>
    <th id="color"><?php echo $final_pending; ?></th>
    
  </tr>
  
  </thead>
</table>

  
        </div>
 </div>  
 <div class="dr"><span></span></div>                     
</div>                      
  
</div><!--WorkPlace End-->  
</div>   


