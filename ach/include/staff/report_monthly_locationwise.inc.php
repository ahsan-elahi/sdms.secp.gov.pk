<?php error_reporting(0); ?>
<script>

function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("report_monthly_locationwise_print.php","Print Report","toolbar=yes,width=1500px,height=14031px");
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

<div class="page-header"><h1>Monthly Location Wise<small> Report</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">    
<input style="margin-left:160px;" id="report_create" onclick="openWin();" class="btn" type="button" value="Print">     
</div>
</div>
<div class="row-fluid"  style="min-height:770px;">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Monthly Location Wise Report Listing'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
          <table width="100%" cellpadding="0" cellspacing="0" class="table">
<thead>
  <tr id="text">
    <th rowspan="2">Month</th>
    <th colspan="2" align="center">Gender</th>
    <th colspan="3" align="center">Foreign</th>
    <th colspan="3" align="center">Rural</th>
    <th colspan="3" align="center">Urban</th>
    <th colspan="3" align="center" id="color">Total</th>
    </tr>

  <tr id="bg_color">
    <td>Male</td>
    <td>Female</td>
    <td>Institute</td>
    <td>Disposal</td>
    <td>Pending</td>
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
   $total_array=array();
   
	 for ($i = 1; $i <= date('m'); $i++) {$a=0; ?>
  <tr>
    <td><?php echo date("M",strtotime('2014-'.$i)); ?></td>
    <?php 
	$sql_male="select count(ticket_id) as total_male from sdms_ticket where gender = 'male' AND Year(created)='".$year."' AND Month(Created)='".$i."'";
	$res_male=db_query($sql_male);
    $row_male= db_fetch_array($res_male);
	?>
    <td><?php echo $row_male['total_male'];$total_male +=$row_male['total_male']; ?></td>
    <?php 
	$sql_female="select count(ticket_id) as total_female from sdms_ticket where gender = 'female' AND Year(created)='".$year."' AND Month(Created)='".$i."'";
	$res_female=db_query($sql_female);
    $row_female= db_fetch_array($res_female);
	?>
    <td><?php echo $row_female['total_female'];$total_female +=$row_female['total_female']; ?></td>
	<?php 
	$sql_location="SELECT applicant_location as location FROM sdms_ticket group by applicant_location ";
	$res_location=mysql_query($sql_location);
	$total_ins=0;
	$total_dis=0;
	$total_pend=0;
	while($row_location=mysql_fetch_array($res_location))
	{
    $sql_open="select count(ticket_id) as total from sdms_ticket where applicant_location = '".$row_location['location']."' AND Year(created)='".$year."' AND Month(Created)='".$i."'";
	$res_open=db_query($sql_open);
    $row_open= db_fetch_array($res_open);
	?>
    <td><?php echo $row_open['total'];$total_ins +=$row_open['total'];$total_array[$a++] +=$row_open['total']; ?></td>
    
     <?php 
    $sql_close="select count(ticket_id) as dispose_total from sdms_ticket where applicant_location = '".$row_location['location']."' AND Year(created)='".$year."' AND Month(Created)='".$i."' AND status='closed' ";
	$res_close=db_query($sql_close);
    $row_close= db_fetch_array($res_close);
	?>
    <td><?php echo $row_close['dispose_total'];$total_dis +=$row_close['dispose_total'];
	$total_array[$a++] +=$row_close['dispose_total'];  ?></td>
    
    <td><?php  
	$total_pending=$row_open['total']-$row_close['dispose_total'];
	echo $total_pending;$total_pend +=$total_pending;$total_array[$a++] +=$total_pending; ?></td>
	 <?php }?>
    <td id="color"><?php echo $total_ins;$grand_total_ins +=$total_ins; ?></td>
    <td id="color"><?php echo $total_dis;$grand_total_dis +=$total_dis; ?></td>
    <td id="color"><?php echo $total_pend;$grand_total_pend +=$total_pend; ?></td>
  </tr>
  <?php }?>
  <thead>
  <tr>
    <th>Total</th>
    <th><?php echo $total_male; ?></th>
    <th><?php echo $total_female; ?></th>
    <?php foreach($total_array as $arr){ ?>
    <th><?php echo $arr; ?></th>
    <?php } ?>
    <!--<th>60</th>
    <th>1000</th>
    <th>1200</th>
    <th>1400</th>
    <th>1600</th>
    <th>1800</th>
    <th>200</th>
    <th>140</th>-->
    <th id="color"><?php echo $grand_total_ins; ?></th>
    <th id="color"><?php echo $grand_total_dis; ?></th>
    <th id="color"><?php echo $grand_total_pend; ?></th>
  </tr>
  </thead>
</table>

  
        </div>
 </div> 
 <div class="dr"><span></span></div>                      
</div>                  
  
</div><!--WorkPlace End-->     

</div>
