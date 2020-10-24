<?php error_reporting(0); ?>
<script>

function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("sms_report_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
window.location.reload();
}
</script>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

if($_REQUEST['staffid']) {
$staffid = $_REQUEST['staffid'];
}
?>

<div class="page-header"><h1>SMS <small> Reports</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">    
<input style="margin-left:160px;" id="report_create" onclick="openWin();" class="btn" type="button" value="Print">    
</div>
</div>
<div class="row-fluid" style="min-height:770px;">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'SMS Reports'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
          <table width="100%" cellpadding="0" cellspacing="0" class="table">
           <thead>
  <tr>
 
    <th>Month</th>
    <th>SMS Send</th>
    <th>SMS Inquiry</th>
  </tr>
  </thead>
   <?php 
   $year=2014;
   $total_sms=0;
	 for ($i = 1; $i <= date('m'); $i++) { ?>
  <tr>
  <td><?php echo date("M",strtotime('2014-'.$i)); ?></td>
  <?php 
  $sql="SELECT count(sms_id) as sms_count FROM sdms_sms_logs WHERE Year(sms_date)='".$year."' AND Month(sms_date)='".$i."' ";
  $res=db_query($sql);
  $row=db_fetch_array($res);
   ?>
    <td><?php echo $row['sms_count'];$total_sms +=$row['sms_count']; ?></td>
    <td>0</td>
  </tr>
  <?php } ?>  
  <thead>
  <tr>
 
    <th>Total</th>
    <th><?php echo $total_sms; ?></th>
    <th>0</th>
  </tr>
  </thead>
</table>
  
        </div>
 </div>   
 <div class="dr"><span></span></div>                    
</div>                        
  
</div><!--WorkPlace End-->  
</div>   


