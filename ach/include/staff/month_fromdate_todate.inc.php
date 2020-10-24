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
#bg_color td{background:#CCC;}
#style{border-left: 1px solid #DDDDDD;vertical-align:middle;}
#style2{vertical-align:middle;}
</style>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

if($_REQUEST['staffid']) {
$staffid = $_REQUEST['staffid'];
}
?>

<div class="page-header"><h1>Month Wise <small>From Date To Date</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">        
</div>
</div>
<div class="row-fluid">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Month Wise From date to date'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
          <table width="100%" cellpadding="0" cellspacing="0" class="table">
           <thead>
  <tr>
 
    <th>Data</th>
    <th>Type</th>
    <th>Units/Numbers</th>
    <th>Total </th>
    <th>Remarks</th>
  </tr>
  </thead>
 
  <tr id="bg_color">
    <td colspan="5" >Access to information</td>
  </tr>
  <tr>
    <td>a)&nbsp;Hit on Website</td>
    <td>Number/Month</td>
   <td>&nbsp;</td>
   <td>&nbsp;</td>
   <td>&nbsp;</td>
  </tr>
  <tr>
    <td>b)&nbsp;Downloads</td>
    <td>Number/Month</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>c)&nbsp;Lenght Of stay on Website</td>
    <td>Minutes/website use</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr id="bg_color">
 <td colspan="5" >CHARACTER OF COMPLAINTS</td>
  </tr>
  <tr>
    <td>a)&nbsp;Complaint Category</td>
    <td>a,b,c</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Category to be developed (domestic ,landownership,violance)</td>
  </tr>
  
  <tr>
    <td rowspan="2" id="style2">b)&nbsp;Gender of Complaint</td>
    <td>Male</td>
    <td>331</td>
    <td rowspan="2" id="style">353</td>
    <td rowspan="2" >&nbsp;</td>
  </tr>
  <tr>
    <td>Female</td>
    <td>22</td>
  </tr>
    <tr>
    <td rowspan="3" id="style2">c)&nbsp;Location of Complaint</td>
    <td>Rural</td>
    <td>137</td>
    <td rowspan="3" id="style">353</td>
    <td rowspan="3">&nbsp;</td>
  </tr>
    <tr>
      <td>Urban</td>
      <td>213</td>
    </tr>
    <tr>
      <td>forigen</td>
      <td>3</td>
    </tr>
    <tr id="bg_color">
 <td colspan="5" >COMPLAINTS HANDLING</td>
  </tr>
  <tr>
    <td>a)&nbsp;Total Complaints received </td>
    <td>July</td>
    <td>353</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>b)&nbsp;Complaints accepted for follow-up</td>
    <td>July</td>
    <td>353<br/>(100%)</td>
    <td>&nbsp;</td>
    <td>All files are accepted for follow-up</td>
  </tr>
  <tr>
    <td>c)&nbsp;Complaints Disposed Of</td>
    <td>July</td>
    <td>98<br/>(27.76%)</td>
    <td>&nbsp;</td>
    <td>Also: Percentage of complaints accepted</td>
  </tr>
  <tr>
    <td>d)&nbsp;Time between receipt and resolution of complaint</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Also: Average time</td>
  </tr>
  <tr id="bg_color">
 <td colspan="5" >REACTION TO FEEDBACK</td>
  </tr>
  <tr>
    <td>a)&nbsp;Feedback received by individuals and communities</td>
    <td>number/quarter</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Also: Brief description of content of feedback</td>
  </tr>
  <tr>
    <td>b)&nbsp;Feedback resulting in action</td>
    <td>cases/decisions</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Description(narrative)</td>
  </tr>
  
  
          </table>
  
        </div>
 </div>   
 <div class="dr"><span></span></div>                    
</div>

<!--<div class="row-fluid">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php //echo 'Reports Handling'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
          <table width="100%" cellpadding="0" cellspacing="0" class="table">
           <thead>
  <tr>
 
    <th>Data</th>
    <th>Type</th>
    <th>Units/Numbers</th>
    <th>Total </th>
    <th>Remarks</th>
  </tr>
  </thead>
 
  <tr>
    <td>Total Complaint recived</td>
    <td>July </td>
    <td>253</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Complaint accepted for follow up</td>
    <td>July</td>
   <td>253</td>
   <td>&nbsp;</td>
   <td>All files are accepted for follow-up</td>
  </tr>
  <tr>
    <td>Complaind Disposd of</td>
    <td>july</td>
    <td>98</td>
    <td>&nbsp;</td>
    <td>Also percentage of complaint accepted</td>
  </tr>
  <tr>
    <td>Time between recipet and resulation of complaint</td>
    <td>Minutes/website use</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Also average time</td>
  </tr>
   <tr>
    <td colspan="5">REACTION TO FEED-BACK</td>
  </tr>
    <tr>
    <td>Feedback recived indivisual and comunities</td>
    <td>Number/quarter</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Also brief discription of content of feedback</td>
  </tr>
      <tr>
    <td>Feedback resulting in action </td>
    <td>cases/desicion</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Description</td>
  </tr>
  
 </table>
  
        </div>
 </div>                      
</div>-->                        
  
</div><!--WorkPlace End-->  
</div>   


