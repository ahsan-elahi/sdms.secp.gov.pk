<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

$csv='';						 		 		
$csv.='"Department of Complaint"'.','.'"Total Complaints"'.','.'"Ageing-IN DAYS (Pending Complaints)"'.',';
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
var d1 = [];
var d2 = [];
var d3 = [];
var data =[];
</script>
<div class="page-header">
  <h1>Complaints Ageing <small>Summary</small></h1>
</div>
<?php /*?><div class="row-fluid">
  <div class="span3" style="float:right;">
  
    <p align="right" style="float:right;"> 
    <a href="comlaintsdepartment_new_csv.csv"><button class="btn" type="button"><i class="icon-print"></i> Export</button></a>
    </p>
    
  </div>
</div><?php */?>

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
<input type="text" name="from_date" id="Datepicker"  value="<?php echo $_POST['from_date']; ?>" >
</td>
<th width="20%" style="padding-top:12px;">To Date</th>
<td>
<input type="text" name="to_date" id="Datepicker1"  value="<?php echo $_POST['to_date']; ?>" >
</td>
<th width="20%" style="padding-top:12px;">Ageing Days</th>
<td>
<input type="number" name="ageing_days" required value="<?php echo $_POST['ageing_days']; ?>" >
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
      <h1>Complaints Ageing</h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
      <form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
        <input type="hidden" name="a" value="mass_process" >
        <input type="hidden" name="status" value="<?php echo $statusss?>" >
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
          <thead>
            <tr>
              <th rowspan="2">Department of Complaint</th>
              <th rowspan="2" align="center">Total Complaints</th>
              <th colspan="8" align="center">Ageing-IN DAYS (Pending Complaints)</th>
            </tr>
            <tr>
              <th>N Days</th>
              <th>1 to 15 Days</th>
              <th>15+ Days</th>
              <th>30+ Days</th>
              <th>45+ Days</th>
              <th>180+ Days</th>
              <th>365+ Days</th>
              <th>730+ Days</th>
              <th>1095+ Days</th>

            </tr>
          </thead>
          <tbody class="" page="1">
            <?php 
$sql_dept="SELECT * FROM `sdms_department` WHERE 1 ".$dept_add."";
$res_dept=mysql_query($sql_dept);
$num_dept = mysql_num_rows($res_dept);
if($num_dept>0){

$subt_ndays = 0;
$subt_1to15days = 0;
$subt_16to30days = 0;
$subt_31to45days = 0;
$subt_45daysplus = 0;
$subt_180daysplus = 0;
$subt_365daysplus = 0;
$subt_730daysplus = 0;
$subt_1095daysplus = 0;
	
$subnum_dept_comp = 0;

$s_1_day=0;
$s_16_day=0;
$s_30_day=0;
$s_45_day=0;

while($row_dept=mysql_fetch_array($res_dept)){

$t_ndays = 0;
$t_1to15days = 0;
$t_16to30days = 0;
$t_31to45days = 0;
$t_45daysplus = 0;
$t_180daysplus = 0;
$t_365daysplus = 0;
$t_730daysplus = 0;
$t_1095daysplus = 0;
$num_dept_comp = 0;

if($_POST['from_date']!='' && $_POST['to_date']!='')
{
$today_date =  date('Y-m-d',strtotime($_POST['to_date'])); 
$s_1_day =   date('Y-m-d',strtotime($_POST['to_date'])); 
}else{
$today_date =  date('Y-m-d'); 

$s_1_day =  date('Y-m-d'); 
}

	
	$days_n = date ("Y-m-d", strtotime("-".$_POST['ageing_days']." day", strtotime($today_date)));
  $days_1to15 = date ("Y-m-d", strtotime("-15 day", strtotime($today_date)));
	$days_15plus = date ("Y-m-d", strtotime("-30 day", strtotime($today_date)));
	$days_30plus = date ("Y-m-d", strtotime("-45 day", strtotime($today_date)));
	$days_45plus = date ("Y-m-d", strtotime("-180 day", strtotime($today_date)));
	$days_180plus = date ("Y-m-d", strtotime("-365 day", strtotime($today_date)));
	$days_365plus = date ("Y-m-d", strtotime("-730 day", strtotime($today_date)));
	$days_730plus = date ("Y-m-d", strtotime("-1095 day", strtotime($today_date)));


$sql_dept_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND status !='closed' AND dept_id='".$row_dept['dept_id']."' ".$dept_add." ".$from_to_date."";
$res_dept_comp = mysql_query($sql_dept_comp);
$num_dept_comp = mysql_num_rows($res_dept_comp);

//echo "first loop 1 to 15 Days";
$sql_ndays = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND status !='closed' AND dept_id='".$row_dept['dept_id']."' 
AND DATE(created) <= '".$today_date."'
AND DATE(created) > '".$days_n."'
 ".$dept_add." ".$from_to_date."";
$res_ndays = mysql_query($sql_ndays);
$t_ndays = mysql_num_rows($res_ndays);

//echo "first loop 1 to 15 Days";
$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND status !='closed' AND dept_id='".$row_dept['dept_id']."' 
AND DATE(created) <= '".$today_date."'
AND DATE(created) > '".$days_1to15."'
 ".$dept_add." ".$from_to_date."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days = mysql_num_rows($res_1to15days);

//echo "second loop 15+ Days";
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND status !='closed' AND dept_id='".$row_dept['dept_id']."'
AND DATE(created) <= '".$days_1to15."'
AND DATE(created) > '".$days_15plus."'
 ".$dept_add." ".$from_to_date."";
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days = mysql_num_rows($res_16to30days);

//echo "second loop 45+ Days";
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND status !='closed' AND dept_id='".$row_dept['dept_id']."'
AND DATE(created) <= '".$days_15plus."'
AND DATE(created) > '".$days_30plus."'
 ".$dept_add." ".$from_to_date."";
$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days = mysql_num_rows($res_31to45days);




$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0'  AND status !='closed' AND dept_id='".$row_dept['dept_id']."'
AND DATE(created) <= '".$days_30plus."'
AND DATE(created) > '".$days_45plus."'
".$dept_add."";
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus = mysql_num_rows($res_45daysplus);

$sql_180daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0'  AND status !='closed' AND dept_id='".$row_dept['dept_id']."'
AND DATE(created) <= '".$days_45plus."'
AND DATE(created) > '".$days_180plus."'
".$dept_add."";
$res_180daysplus = mysql_query($sql_180daysplus);
$t_180daysplus = mysql_num_rows($res_180daysplus);


$sql_365daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0'  AND status !='closed' AND dept_id='".$row_dept['dept_id']."'
AND DATE(created) <= '".$days_180plus."'
AND DATE(created) > '".$days_365plus."'
".$dept_add."";
$res_365daysplus = mysql_query($sql_365daysplus);
$t_365daysplus = mysql_num_rows($res_365daysplus);


$sql_730daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0'  AND status !='closed' AND dept_id='".$row_dept['dept_id']."'
AND DATE(created) <= '".$days_365plus."'
AND DATE(created) > '".$days_730plus."'
".$dept_add."";
$res_730daysplus = mysql_query($sql_730daysplus);
$t_730daysplus = mysql_num_rows($res_730daysplus);

$sql_1095daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0'  AND status !='closed' AND dept_id='".$row_dept['dept_id']."'
AND DATE(created) <= '".$days_730plus."'
".$dept_add."";
$res_1095daysplus = mysql_query($sql_1095daysplus);
$t_1095daysplus = mysql_num_rows($res_1095daysplus);



$sql_sub_dept_inner="SELECT * FROM `sdms_department` WHERE dept_p_id='".$row_dept['dept_id']."'";
$res_sub_dept_inner=mysql_query($sql_sub_dept_inner);
$num_sub_dept_inner = mysql_num_rows($res_sub_dept_inner);

$primary_status .= "{ name: '".$row_dept['dept_name']."', y: ".$num_dept_comp.",
            drilldown: '".$row_dept['dept_name']."'},";
$sub_status .="{
            name: '".$row_dept['dept_name']."',
            id: '".$row_dept['dept_name']."',
            data: [ ";
			
$href = 'tickets.php?a=search&deptId='.$row_dept['dept_id'].'&status=open&action=report';
$href_ndays = $href.'&r_type=ndays&from_date='.$today_date.'&to_date='.$days_n.'';
$href_1to15days = $href.'&r_type=1to15days&from_date='.$today_date.'&to_date='.$days_1to15.'';
$href_16to30days = $href.'&r_type=16to30days&from_date='.$days_1to15.'&to_date='.$days_15plus.'';
$href_31to45days = $href.'&r_type=31to45days&from_date='.$days_15plus.'&to_date='.$days_30plus.'';
$href_45daysplus = $href.'&r_type=45daysplus&from_date='.$days_30plus.'&to_date='.$days_45plus.'';
$href_180daysplus = $href.'&r_type=180daysplus&from_date='.$days_45plus.'&to_date='.$days_180plus.'';
$href_365daysplus = $href.'&r_type=365daysplus&from_date='.$days_180plus.'&to_date='.$days_365plus.'';
$href_730daysplus = $href.'&r_type=730daysplus&from_date='.$days_365plus.'&to_date='.$days_730plus.'';
$href_1095daysplus = $href.'&r_type=1095daysplus&from_date='.$days_730plus.'';


$t_href = 'tickets.php?a=search&status=open&action=report';
$t_href_ndays =$t_href.'&r_type=ndays&from_date='.$today_date.'&to_date='.$days_n.'';
$t_href_1to15days =$t_href.'&r_type=1to15days&from_date='.$today_date.'&to_date='.$days_1to15.'';
$t_href_16to30days =$t_href.'&r_type=16to30days&from_date='.$days_1to15.'&to_date='.$days_15plus.'';
$t_href_31to45days =$t_href.'&r_type=31to45days&from_date='.$days_15plus.'&to_date='.$days_30plus.'';
$t_href_45daysplus =$t_href.'&r_type=45daysplus&from_date='.$days_30plus.'&to_date='.$days_45plus.'';
$t_href_180daysplus = $t_href.'&r_type=180daysplus&from_date='.$days_45plus.'&to_date='.$days_180plus.'';
$t_href_365daysplus = $t_href.'&r_type=365daysplus&from_date='.$days_180plus.'&to_date='.$days_365plus.'';
$t_href_730daysplus = $t_href.'&r_type=730daysplus&from_date='.$days_365plus.'&to_date='.$days_730plus.'';
$t_href_1095daysplus = $t_href.'&r_type=1095daysplus&from_date='.$days_730plus.'';
		



$subnum_dept_comp +=$num_dept_comp;
$subt_ndays += $t_ndays;
$subt_1to15days += $t_1to15days;
$subt_16to30days += $t_16to30days;
$subt_31to45days += $t_31to45days;
$subt_45daysplus += $t_45daysplus;
$subt_180daysplus += $t_180daysplus;
$subt_365daysplus += $t_365daysplus;
$subt_730daysplus += $t_730daysplus;
$subt_1095daysplus += $t_1095daysplus;
			
?>
            <tr>            
		      <th><span style="float:left; width:350px;"  ><?php echo $row_dept['dept_name'];?></span></th> 		
              <td><b><span align="right"><a href="<?php echo $href; ?>"><?php echo $num_dept_comp; ?></a></span></b></td>              
              <td><a href="<?php echo $href_ndays; ?>"><?php echo $t_ndays;   ?></a></td>   
              <td><a href="<?php echo $href_1to15days; ?>"><?php echo $t_1to15days;   ?></a></td>            
              <td><a href="<?php echo $href_16to30days; ?>"><?php echo $t_16to30days; ?></a></td>
              <td><a href="<?php echo $href_31to45days; ?>"><?php echo $t_31to45days; ?></a></td>
              <td><a href="<?php echo $href_45daysplus; ?>"><?php echo $t_45daysplus; ?></a></td>
              <td><a href="<?php echo $href_180daysplus; ?>"><?php echo $t_180daysplus; ?></a></td>
              <td><a href="<?php echo $href_365daysplus; ?>"><?php echo $t_365daysplus; ?></a></td>
              <td><a href="<?php echo $href_730daysplus; ?>"><?php echo $t_730daysplus; ?></a></td>
              <td><a href="<?php echo $href_1095daysplus; ?>"><?php echo $t_1095daysplus; ?></a></td>
            </tr>
            
            <?php 
			$sub_status .="['N Days', ".$t_ndays."]['1 to 15', ".$t_1to15days."],['15+', ".$t_16to30days."],['30+', ".$t_31to45days."],['45+', ".$t_45daysplus."],['180+', ".$t_180daysplus."],['365+', ".$t_365daysplus."],['730+', ".$t_730daysplus."],['1095+', ".$t_1095daysplus."]";
$sub_status .="]
},
";
     	$csv .= '"' . $row_dept['dept_name'] . '",';
			$csv .= '"' . $num_dept_comp. '",';
			$csv .= '"' . $t_ndays. '",';
      $csv .= '"' . $t_1to15days. '",';
			$csv .= '"' . $t_16to30days. '",';
			$csv .= '"' . $t_31to45days. '",';
			$csv .= '"' . $t_45daysplus. '",';
			$csv .= '"' . $t_180daysplus. '",';
			$csv .= '"' . $t_365daysplus. '",';
			$csv .= '"' . $t_730daysplus. '",';
			$csv .= '"' . $t_1095daysplus. '",';
			
			  $csv.="\n"; 
			}?>
            <tr id="total">
              <th><span style="float: left; width:350px;">Total</span></th>
              <td><span class="Icon <?php echo $icon;?>" align="right"><b><span align="right"><a href="<?php echo $t_href; ?>"><?php echo $subnum_dept_comp; ?> </a></span></b><br />
                </span></td>
                <td><a href="<?php echo $t_href_ndays; ?>"><b><?php echo $subt_ndays; ?></b></a></td>
                <td><a href="<?php echo $t_href_1to15days; ?>"><b><?php echo $subt_1to15days; ?></b></a></td>
                <td><a href="<?php echo $t_href_16to30days; ?>"><b><?php echo $subt_16to30days; ?></b></a></td>
                <td><a href="<?php echo $t_href_31to45days; ?>"><b><?php echo $subt_31to45days; ?></b></a></td>
                <td><a href="<?php echo $t_href_45daysplus; ?>"><b><?php echo $subt_45daysplus; ?></b></a></td>
                <td><a href="<?php echo $t_href_180daysplus; ?>"><b><?php echo $subt_180daysplus; ?></b></a></td>
                <td><a href="<?php echo $t_href_365daysplus; ?>"><b><?php echo $subt_365daysplus; ?></b></a></td>
                <td><a href="<?php echo $t_href_730daysplus; ?>"><b><?php echo $subt_730daysplus; ?></b></a></td>
                <td><a href="<?php echo $t_shref_1095daysplus; ?>"><b><?php echo $subt_1095daysplus; ?></b></a></td>
            </tr>
            <?php 

			
			$csv.='"Total"'.','.'"'.$subnum_dept_comp.'"'.','.'"'.$subt_ndays.'"'.','.'"'.$subt_1to15days.'"'.','.'"'.$subt_16to30days.'"'.','.'"'.$subt_31to45days.'"'.','.'"'.$subt_45daysplus.'"'.',';
			$csv.="\n";
$file = 'comlaintsdepartment_new_csv.csv';
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
<?php
//echo $primary_status.'<Br>';
//echo $sub_status;exit;
 ?>
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
			var defaultTitle = "Complaints Ageing";
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
