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
<style>
#text th {text-align:center;vertical-align:middle;}
</style>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

if($_REQUEST['staffid']) {
$staffid = $_REQUEST['staffid'];
}
?>

<div class="page-header"><h1>Complaint<small> Summary</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">        
</div>
</div>
<div class="row-fluid" style="min-height:700px;">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Complaint Summary'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
          <table width="100%" cellpadding="0" cellspacing="0" class="table">
           <thead>
  <tr id="text">
 
    <th>Daily Reports</th>
    <th>Opened</th>
    <th>Overdue</th>
    <th>Closed</th>
  </tr>
  </thead>
 
  <tr>
    <td>Active Complaints</td>
    <td>3034</td>
    <td>0</td>
    <td>0</td>
  </tr>
  <tr>
    <td>Active Complaints (Today)</td>
    <td>1</td>
    <td>0</td>
    <td>0</td>
  </tr>
  <tr>
    <td>Active Complaints(Current Week)</td>
    <td>1</td>
    <td>0</td>
    <td>0</td>
  </tr>
  <tr>
    <td>Active Complaints(Current Month)</td>
    <td>1</td>
    <td>0</td>
    <td>0</td>
  </tr>
   <tr>
    <td>Active Complaints(Current Year)</td>
    <td>23542354</td>
    <td>0</td>
    <td>0</td>
  </tr>
  
</table>
  
        </div>
 </div>  
 <div class="dr"><span></span></div>                    
</div>                        
   
</div><!--WorkPlace End-->  
</div>   


