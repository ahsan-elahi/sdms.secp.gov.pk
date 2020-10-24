<?php error_reporting(0); ?>
<script>

function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("report_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
myWindow.close();
}
</script>
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
<div class="row-fluid">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Priority Wise Report Listing'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
<table width="100%" cellpadding="0" cellspacing="0" class="table">
<thead>
  <tr>
    <th>Month</th>
    <th colspan="3">Action</th>
    <th colspan="3">Priority</th>
    </tr>
</thead>

  <tr>
    <td>Month</td>
    <td>Institute</td>
    <td>Disposal</td>
    <td>Pending</td>
    <td>Institute</td>
    <td>Disposal</td>
    <td>Pending</td>
  </tr>
  <tr>
    <td>Jan</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
  </tr>
  <tr>
    <td>Feb</td>
    <td>200</td>
    <td>30</td>
    <td>500</td>
    <td>600</td>
    <td>700</td>
    <td>800</td>
  </tr>
  <thead>
  <tr>
    <th>Total</th>
    <th>400</th>
    <th>60</th>
    <th>1000</th>
    <th>1200</th>
    <th>1400</th>
    <th>1600</th>
  </tr>
  </thead>
</table>

  
        </div>
 </div>                      
</div> 

<div class="row-fluid">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Total'; ?></h1>                               
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
    <td>Jan</th>
    <td>1500</td>
    <td>830</td>
    <td>1370</td>
  </tr>
  <tr>
    <td>Feb</th>
    <td>1500</td>
    <td>830</td>
    <td>1370</td>
  </tr>
</table>

  
        </div>
 </div>                      
</div>                       
<div class="dr"><span></span></div>   
</div><!--WorkPlace End-->  
</div>   


