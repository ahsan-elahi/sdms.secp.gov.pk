<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
	
$csv='';						 		 		
$csv.='"Department of Query"'.','.'"Total Queries"'.','.'"Ageing-IN DAYS (Pending Queries)"'.',';
$csv.="\n";

$csv .= '""'.','.'""'.','.'"1 to 15"'.','.'"15+"'.','.'"30+"'.','.'"45+"'.',';

$csv.="\n";		
if($thisstaff->isFocalPerson() == '1' || $thisstaff->getGroupId()=='8')
{
	$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	$dept_id = $thisstaff->getDeptId();
}
elseif(!$thisstaff->isAdmin() &&  $thisstaff->onChairman() == '1')
{
	$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	$dept_id = $thisstaff->getDeptId();
	//$dept_add .= ' AND dept_id = '.$_POST['dept_id'].'';
	//$dept_id = $_POST['dept_id'];
}
elseif($thisstaff->isAdmin() && $_POST['dept_id']!='')
{
$dept_add .= ' AND dept_id = '.$_POST['dept_id'].'';
$dept_id = $_POST['dept_id'];
}
if($_POST['from_date']!='' && $_POST['to_date']!='')
{
$from_to_date = ' AND DATE(created) >= "'.date('Y-m-d',strtotime($_POST['from_date'])).'" AND DATE(created) <= "'.date('Y-m-d',strtotime($_POST['to_date'])).'"  ';
$date_range = '&startDate='.date('m/d/Y',strtotime($_REQUEST['from_date'])).'&endDate='.date('m/d/Y',strtotime($_REQUEST['to_date']));
}else{
$from_to_date ='';
$date_range = '';
}
?>
<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("comlaintstatus_new_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
//myWindow.close();
}
function export_to_csv(){
var export_csv = '';    
		//alert(items_csv);
	$.ajax({
			url:"comlaintsdepartment_new_csv.php",
			data: "&export_csv="+export_csv,
			success: function(msg){
			//alert(msg);
			document.getElementById("csv_download").click();
			//display_summary_table();
			}});			
	
}
</script>

<div class="page-header">
  <h1>Queries Ageing <small>Summary</small></h1>
</div>
<div class="row-fluid">
  <div class="span3" style="float:right;">
  
    <p align="right" style="float:right;"> 
    <a href="query_dept_ageing_report.csv"><button class="btn" type="button"><i class="icon-print"></i> Export</button></a>
    </p>
    
  </div>
</div>

<div class="row-fluid">
<div class="span12">                    
<div class="head clearfix">
<div class="isw-grid"></div>
<h1>Search</h1>                          
</div>
<div class="block-fluid table-sorting clearfix">
<form action="" method="post" id="save" enctype="multipart/form-data">
 <?php csrf_token(); ?>
<table cellpadding="0" cellspacing="0" width="100%" class="table"  >
<tr>
<th width="20%" style="padding-top:12px;">From Date</th>
<td>
<input type="text" name="from_date" id="Datepicker" required value="<?php echo $_POST['from_date']; ?>" >
</td>
<th width="20%" style="padding-top:12px;">To Date</th>
<td>
<input type="text" name="to_date" id="Datepicker1" required value="<?php echo $_POST['to_date']; ?>" >
</td>
</tr>        
<tr>
<td style="background-color: #FFFFFF;text-align: right;" colspan="6" align="right">
<input class="btn" type="submit" name="search" value="Search">
</td>
</tr>
</table>
</form>
</div>
</div>     
</div>
<?php if($thisstaff->isAdmin() || $thisstaff->onChairman() == '1'){ ?>
<?php }?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Queries Ageing</h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
      <form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
        <input type="hidden" name="a" value="mass_process" >
        <input type="hidden" name="status" value="<?php echo $statusss?>" >
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
          <thead>
            <tr>
              <th rowspan="2">Department of Query</th>
              <th rowspan="2" align="center">Total Queries</th>
              <th colspan="4" align="center">Ageing-IN DAYS (Pending Queries)</th>
            </tr>
            <tr>
              <th>1 to 15</th>
              <th>15+</th>
              <th>30+</th>
              <th>45+</th>
            </tr>
          </thead>
          <tbody class="" page="1">
            <?php 
$sql_dept="SELECT * FROM `sdms_department` WHERE 1 ".$dept_add."";
$res_dept=mysql_query($sql_dept);
$num_dept = mysql_num_rows($res_dept);
if($num_dept>0){

$subt_1to15days = 0;
$subt_16to30days = 0;
$subt_31to45days = 0;
$subt_45daysplus = 0;	
$subnum_dept_comp = 0;

$s_1_day=0;
$s_16_day=0;
$s_30_day=0;
$s_45_day=0;

while($row_dept=mysql_fetch_array($res_dept)){
	
$t_1to15days = 0;
$t_16to30days = 0;
$t_31to45days = 0;
$t_45daysplus = 0;
$num_dept_comp = 0;


$today_date =  date('Y-m-d'); 
$s_1_day =  date('Y-m-d'); 
$sql_dept_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '1' AND status !='closed' AND dept_id='".$row_dept['dept_id']."' ".$dept_add." ".$from_to_date."";
$res_dept_comp = mysql_query($sql_dept_comp);
$num_dept_comp += mysql_num_rows($res_dept_comp);

//echo "first loop 1 to 15 Days";
for($i=1;$i<16;$i++)
{
$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '1' AND status !='closed' AND dept_id='".$row_dept['dept_id']."' AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days += mysql_num_rows($res_1to15days);
$s_16_day = $today_date;
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}
//echo "second loop 15+ Days";
for($i=1;$i<16;$i++)
{
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '1' AND status !='closed' AND dept_id='".$row_dept['dept_id']."' AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days += mysql_num_rows($res_16to30days);
$s_30_day = $today_date;
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}
//echo $today_date.'<br><br>';
//echo "second loop 45+ Days";

for($i=1;$i<16;$i++)
{
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '1' AND status !='closed' AND dept_id='".$row_dept['dept_id']."' AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days += mysql_num_rows($res_31to45days);
//echo $today_date.'<br>';
$s_45_day =  $today_date;
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}
$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '1' AND status !='closed' AND dept_id='".$row_dept['dept_id']."' AND DATE(created) <= '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus += mysql_num_rows($res_45daysplus);

$sql_sub_dept_inner="SELECT * FROM `sdms_department` WHERE dept_p_id='".$row_dept['dept_id']."'";
$res_sub_dept_inner=mysql_query($sql_sub_dept_inner);
$num_sub_dept_inner = mysql_num_rows($res_sub_dept_inner);

$primary_status .= "{ name: '".$row_dept['dept_name']."', y: ".$num_dept_comp.",
            drilldown: '".$row_dept['dept_name']."'},";
$sub_status .="{
            name: '".$row_dept['dept_name']."',
            id: '".$row_dept['dept_name']."',
            data: [ ";
?>
            <tr>
            
		        <th><span style="float:left; width:350px;"  ><?php echo $row_dept['dept_name'];?></span></th> 		
              <td><b><span align="right"><a href="queries.php?a=search&deptId=<?php echo $row_dept['dept_id'].$date_range; ?>&status=open&action=report"><?php echo $num_dept_comp; $subnum_dept_comp +=$num_dept_comp; ?></a></span></b></td>
              <td><a href="queries.php?a=search&deptId=<?php echo $row_dept['dept_id']; ?>&status=open&startDate=<?php echo date('m/d/Y',strtotime($s_16_day)); ?>&endDate=<?php echo date('m/d/Y',strtotime($s_1_day)); ?>&action=report"><?php echo $t_1to15days;  $subt_1to15days += $t_1to15days;  ?></a></td>
              
              <td> <a href="queries.php?a=search&deptId=<?php echo $row_dept['dept_id']; ?>&status=open&startDate=<?php echo date('m/d/Y',strtotime($s_30_day)); ?>&endDate=<?php echo date('m/d/Y',strtotime("-1 day",strtotime($s_16_day))); ?>&action=report"><?php echo $t_16to30days; $subt_16to30days += $t_16to30days;?></a></td>
              
              <td>   <a href="queries.php?a=search&deptId=<?php echo $row_dept['dept_id']; ?>&status=open&startDate=<?php echo date('m/d/Y',strtotime($s_45_day)); ?>&endDate=<?php echo date('m/d/Y',strtotime("-1 day",strtotime($s_30_day))); ?>&action=report"><?php echo $t_31to45days; $subt_31to45days += $t_31to45days;?></a></td>
              
              <td> <a href="queries.php?a=search&deptId=<?php echo $row_dept['dept_id']; ?>&status=open&endDate=<?php echo date('m/d/Y',strtotime("-1 day",strtotime($s_45_day))); ?>&action=report"><?php echo $t_45daysplus; $subt_45daysplus += $t_45daysplus;?></a></td>
            
            </tr>
            
            <?php 
							$sub_status .="['1 to 15', ".$t_1to15days."],['15+', ".$t_16to30days."],['30+', ".$t_31to45days."],['45+', ".$t_45daysplus."]";
$sub_status .="]
},
";
			$csv .= '"' . $row_dept['dept_name'] . '",';
			$csv .= '"' . $num_dept_comp. '",';
			$csv .= '"' . $t_1to15days. '",';
			$csv .= '"' . $t_16to30days. '",';
			$csv .= '"' . $t_31to45days. '",';
			$csv .= '"' . $t_45daysplus. '",';
			  $csv.="\n"; 
			 
	}?>
            <tr id="total">
              <th><span style="float: left; width:350px;">Total</span></th>
              <td><span class="Icon <?php echo $icon;?>" align="right"><b><span align="right"><?php echo $subnum_dept_comp; ?> </span></b><br />
                </span></td>
              <td><b><?php echo $subt_1to15days; ?></b></td>
              <td><b><?php echo $subt_16to30days; ?></b></td>
              <td><b><?php echo $subt_31to45days; ?></b></td>
              <td><b><?php echo $subt_45daysplus; ?></b></td>
            </tr>
            <?php $csv.='"Total"'.','.'"'.$subnum_dept_comp.'"'.','.'"'.$subt_1to15days.'"'.','.'"'.$subt_16to30days.'"'.','.'"'.$subt_31to45days.'"'.','.'"'.$subt_45daysplus.'"'.',';
			$csv.="\n";
$file = 'query_dept_ageing_report.csv';
if (!$handle = fopen($file, 'w')) 
{
echo "Cannot open file ($filename)";
exit;                    
}
if (fwrite($handle, $csv) === FALSE) 
{
echo "Cannot write to file ($filename)";
exit;
}
fclose($handle);

			 }else{?>
            <tr class="<?php echo $class?>">
              <td><b>Query returned 0 results.</b></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div>
<div class="row-fluid">
            <div class="span12">
            <div class="head clearfix">
            <div class="isw-right_circle"></div>
            <h1>Pie charts</h1>
            </div>
            <div class="block">
            <div id="container" style="margin: 0 auto"></div>
            </div>
            </div>
            <script type="text/javascript">
            // Create the chart
			var defaultTitle = "Queries Ageing";
			var drilldownTitle = "More about ";
            Highcharts.chart('container', {
            chart: {
            type: 'pie',
			events: {
			drilldown: function(e) {
			this.setTitle({ text: drilldownTitle + e.point.name });
			},
			drillup: function(e) {
			this.setTitle({ text: defaultTitle });
			}
			}
            },
            title: {
            text: defaultTitle
            },
            subtitle: {
            text: 'Click the slices to view substatus.'
            },
            plotOptions: {
            series: {
            dataLabels: {
            enabled: true,
           // format: '{point.name}: {point.y:.1f}'
			format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
            }
            },
            tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> of total<br/>'
			},
            series: [{
            name: 'Status',
            colorByPoint: true,
            data: [<?php echo $primary_status; ?>]
            }],
            drilldown: {
            series: [<?php echo $sub_status;  ?>]
            }
            });
			</script>                        
    </div>
<div class="dr"><span></span></div>
</div>
<!--WorkPlace End-->
</div>
