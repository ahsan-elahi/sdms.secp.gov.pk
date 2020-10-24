<?php error_reporting(0); ?>
<script>

function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("report_tehsil_month_print.php","Print Report","toolbar=yes,width=1500px,height=14031px");
window.location.reload();
}
</script>
<style>
#bg_color td{background:#CCC;}
#text th {text-align:center;vertical-align:middle;}
#color,#bg_color #color{background:#999;color:#FFF;}
#align{vertical-align:middle;}
</style>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

if($_REQUEST['staffid']) {
$staffid = $_REQUEST['staffid'];
}
?>

<div class="page-header"><h1>Tehsil Wise<small>Monthly Report</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">   
<input style="margin-left:160px;" id="report_create" onclick="openWin();" class="btn" type="button" value="Print">      
</div>
</div>
<div class="row-fluid" style="min-height:700px;">
   <div class="span12" style="width:1900px;">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Tehsil Wise Monthly Report'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
<table width="40%" cellpadding="0" cellspacing="0" class="table">
<thead>
  <tr id="text">
    <th rowspan="2">Month/<br/>District</th>
    <th rowspan="2">Tehsil</th>
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
	 for ($i = 1; $i <= date('m'); $i++) { ?>
        <td>Ins</td>
    <td>Dis</td>
    <td>Pend</td>
    <?php }?>
    <td id="color">Institute</td>
    <td id="color">Disposal</td>
    <td id="color">Pending</td>
  </tr>
  </thead>
  <?php 
    $sql_districts="select * from sdms_districts";
 $res_districts=db_query($sql_districts);
 $total_array=array();
  while ($row_districts = db_fetch_array($res_districts)) { 
  ?>
  <tr>
    <td colspan="18" id="align"><strong><?php echo $row_districts['District']; ?></strong></td>
  </tr>
  <?php 
    $sql_tehsil="select * from sdms_tehsils WHERE District_ID='".$row_districts['District_ID']."'";
 $res_tehsil=db_query($sql_tehsil);
  while ($row_tehsil = db_fetch_array($res_tehsil)) {
	  $a=0;
  $total_ins=0;
  $total_dis=0;
  $total_pend=0;
  ?>
    <tr>
    <td style="border-right:none;"></td>
    <td style="border-left:none;"><?php echo $row_tehsil['Tehsil_Name'] ?></td>
    <?php 
   $year=2014;
	 for ($i = 1; $i <= date('m'); $i++) { 
	 $sql_open="select count(ticket_id) as total from sdms_ticket where tehsil = '".$row_tehsil['Tehsil_ID']."' AND Year(created)='".$year."' AND Month(Created)='".$i."'";
	$res_open=db_query($sql_open);
    $row_open= db_fetch_array($res_open);
	?>
    <td><?php echo $row_open['total'];$total_ins +=$row_open['total'];
	$total_array[$a++] +=$row_open['total'];  ?></td>
    
     <?php 
    $sql_close="select count(ticket_id) as dispose_total from sdms_ticket where tehsil = '".$row_tehsil['Tehsil_ID']."' AND Year(created)='".$year."' AND Month(Created)='".$i."' AND status='closed' ";
	$res_close=db_query($sql_close);
    $row_close= db_fetch_array($res_close);
	?>
    <td><?php echo $row_close['dispose_total'];$total_dis +=$row_close['dispose_total'];
	$total_array[$a++] +=$row_close['dispose_total'];  ?></td>
    
    <td><?php 
	$total_pending=$row_open['total']-$row_close['dispose_total'];
	echo $total_pending;$total_pend +=$total_pending;
	$total_array[$a++] +=$total_pending; ?></td>
    <?php }?>
    <td id="color"><?php echo $total_ins;$grand_total_ins +=$total_ins; ?></td>
    <td id="color"><?php echo $total_dis;$grand_total_dis +=$total_dis; ?></td>
    <td id="color"><?php echo $total_pend; $grand_total_pend +=$total_pend; ?></td>
    </tr>
    
  <?php 
  } }
  ?>
<!--<tr>
    <td rowspan="3" id="align"><?php echo $row_districts['District']; ?></td>
    <td>Karak</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td id="color">700</td>
    <td id="color">800</td>
    <td id="color">700</td>
    </tr>
    <tr>
    <td >Takht-e- Nusrati</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td id="color">700</td>
    <td id="color">800</td>
    <td id="color">700</td>
    </tr>
    <tr>
    <td>Banda Dawood Shah</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td id="color">700</td>
    <td id="color">800</td>
    <td id="color">700</td>
    </tr>
    <tr>
    <td rowspan="3" id="align">Bannu</td>
    <td>Bannu</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td id="color">700</td>
    <td id="color">800</td>
    <td id="color">700</td>
    </tr>
    <tr>
    <td>Kakki</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td id="color">700</td>
    <td id="color">800</td>
    <td id="color">700</td>
    </tr>
    <tr>
    <td>Domel</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td>30</td>
    <td>500</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
    <td>700</td>
    <td id="color">700</td>
    <td id="color">800</td>
    <td id="color">700</td>
    </tr>-->
  
  <thead>
  <tr>
    <th>Total</th>
    <th>&nbsp;</th>
    <?php foreach($total_array as $arr){ ?>
    <th><?php echo $arr; ?></th>
    <?php } ?>
    <th><?php echo $grand_total_ins; ?></th>
    <th><?php echo $grand_total_dis; ?></th>
     <th><?php echo $grand_total_pend; ?></th>
   <!-- <th>1600</th>
    <th>1200</th>
    <th>1400</th>
    <th>1600</th>
    <th>1400</th>
    <th>60</th>
    <th>1000</th>
    <th>1200</th>
     <th>1400</th>
    <th>1600</th>
    <th>1200</th>-->
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
    <th>Month</th>
    <th>Institute</th>
    <th>Disposal</th>
    <th>Pending</th>
    </tr>
</thead>

 
  <tr>
    <td>Jan</td>
    <td>1500</td>
    <td>830</td>
    <td>1370</td>
  </tr>
  <tr>
    <td>Feb</td>
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


