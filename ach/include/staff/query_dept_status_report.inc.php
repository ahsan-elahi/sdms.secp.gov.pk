<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
$csv='';						 		 		
$csv.='"Department of Query"'.','.'"Total Queries"'.','.'"Status"'.',';
$csv.="\n";
$csv .= '""'.','.'""'.',';	
	
if($thisstaff->isFocalPerson() == '1' || $thisstaff->getGroupId()=='8')
{
	$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	$dept_id = $thisstaff->getDeptId();
	$fromtodate = '';
}
elseif(!$thisstaff->isAdmin() &&  $thisstaff->onChairman() == '1' )
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

function export_to_csv(query_sting){
var from_to_date = query_sting;    
		//alert(items_csv);
	$.ajax({
			url:"departmental_summary_report_export_to_csv.php",
			data: "&from_to_date="+from_to_date,
			success: function(msg){
			document.getElementById("csv_download").click();
			}});			
	
}
var d1 = [];
var d2 = [];
var d3 = [];
var data =[];
</script>

<div class="page-header">
  <h1>Queries Status <small> Summary</small></h1>
</div>
<div class="row-fluid">
  <div class="span3" style="float:right;">
      <p align="right" style="float:right;"> <a href="query_dept_status_report.csv"><button class="btn" type="button" ><i class="icon-print"></i> Export</button></a>
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
      <h1>Queries Status</h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
          <thead>
            <tr>
              <th rowspan="2">Department of Query</th>
              <th rowspan="2" align="center">Total Queries</th>
              <th colspan="5" align="center">Status</th>
            </tr>
            <tr>
             <?php 
			 $sql_status="SELECT * FROM `sdms_status` WHERE status_id = '1' OR status_id = '2'";
$res_status=mysql_query($sql_status);
$num_status = mysql_num_rows($res_status);
while($row_status=mysql_fetch_array($res_status)){
$csv .= '"' . $row_status['status_title'] . '",';	
			  ?>
              <th><a onclick="show_substatus(<?php echo $row_status['status_id']; ?>)" id='show_temp_status_<?php echo $row_status['status_id']; ?>'><?php echo $row_status['status_title'];?></a></th>
              <?php 
			
			  $sql_sub_status="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
$res_sub_status=mysql_query($sql_sub_status);
$num_sub_status = mysql_num_rows($res_sub_status);
while($row_sub_status=mysql_fetch_array($res_sub_status)){
$csv .= '"' . $row_sub_status['status_title'] . '",';	
	  ?>
              <th style="display:none;" class="status_<?php echo $row_status['status_id']; ?>"><?php echo $row_sub_status['status_title'];?></th>
              <?php }  ?>
             <?php  } ?> 
            </tr>
          </thead>
          <tbody class="" page="1">
            <?php 
$csv.="\n";

$sub_status_count = array();			
$sql_dept="SELECT * FROM `sdms_department` WHERE 1 ".$dept_add."";
$res_dept=mysql_query($sql_dept);
$num_dept = mysql_num_rows($res_dept);
if($num_dept>0){
	$subnum_dept_comp = 0;
while($row_dept=mysql_fetch_array($res_dept)){
$num_dept_comp = 0;
$sql_dept_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '1' AND dept_id='".$row_dept['dept_id']."' ".$dept_add." ".$from_to_date."";
$res_dept_comp = mysql_query($sql_dept_comp);
$num_dept_comp = mysql_num_rows($res_dept_comp);
?>
<tr>

    <th><span style="float:left; width:350px;"  ><?php echo $row_dept['dept_name']; $csv .= '"' . $row_dept['dept_name'] . '",';?></span></th> 		
    <td><b><span align="right"><a href="queries.php?a=search&deptId=<?php echo $row_dept['dept_id'].$date_range; ?>"><?php echo $num_dept_comp; $subnum_dept_comp +=$num_dept_comp; $csv .= '"' . $num_dept_comp . '",';?></a></span></b></td>
<?php 
//pie chart work
$primary_status .= "{ name: '".$row_dept['dept_name']."', y: ".$num_dept_comp.",
drilldown: '".$row_dept['dept_name']."'},";
$sub_status .="{
name: '".$row_dept['dept_name']."',
id: '".$row_dept['dept_name']."',
data: [ ";
?>
<?php 
$sql_status="SELECT * FROM `sdms_status` WHERE status_id = '1' OR status_id = '2'";
$res_status=mysql_query($sql_status);
$subnum_status_comp = 0;
$i=0;
$sub_sub_status = "";
while($row_status=mysql_fetch_array($res_status)){
$num_status_comp = 0;
$sql_sub_status="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
$res_sub_status=mysql_query($sql_sub_status);
$num_sub_status = mysql_num_rows($res_sub_status);
while($row_sub_status=mysql_fetch_array($res_sub_status)){	
$sql_status_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '1' AND complaint_status='".$row_sub_status['status_id']."' AND  dept_id='".$row_dept['dept_id']."' ".$from_to_date."";
$res_status_comp = mysql_query($sql_status_comp);
$num_status_comp += mysql_num_rows($res_status_comp);
}
//$sub_status .="['".$row_status['status_title']."', ".$num_status_comp."],";
$sub_status .="{ name:'".$row_status['status_title']."', y: ".$num_status_comp.", drilldown: '".$row_dept['dept_name'].$row_status['status_title']."'},";

?>
              <td><a href="queries.php?a=search&deptId=<?php echo $row_dept['dept_id']; ?>&primary_stutus=<?php echo $row_status['status_id'].$date_range; ?>"><?php echo $num_status_comp; $sub_status_count[$i] +=$num_status_comp; $csv .= '"' . $num_status_comp . '",'; ?></a></td>
              <?php
                $sub_sub_status .= "{ id: '".$row_dept['dept_name'].$row_status['status_title']."',data: [";
                $sql_sub_status_inner="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
                $res_sub_status_inner=mysql_query($sql_sub_status_inner);
                $num_sub_status_inner = mysql_num_rows($res_sub_status_inner);
				while($row_sub_status_inner = mysql_fetch_array($res_sub_status_inner)){
                $num_status_comp_inner = 0;
                $sql_status_comp_inner = "SELECT * FROM `sdms_ticket` WHERE isquery = '1' AND complaint_status='".$row_sub_status_inner['status_id']."' AND  dept_id='".$row_dept['dept_id']."' ".$from_to_date."";
                $res_status_comp_inner = mysql_query($sql_status_comp_inner);
                $num_status_comp_inner = mysql_num_rows($res_status_comp_inner);
				$sub_status_total = $num_status_comp_inner;
				?>
                <td style="display:none;" class="status_<?php echo $row_status['status_id']; ?>">
				<a href="queries.php?a=search&deptId=<?php echo $row_dept['dept_id']; ?>&cstatus=<?php echo $row_sub_status_inner['status_id'].$date_range; ?>">
				<?php echo $num_status_comp_inner; 
				$csv .= '"' . $num_status_comp_inner . '",';?>
                </a>
                </td>
                <?php 
				$sub_sub_status .= "['".$row_sub_status_inner['status_title']."' ,".$sub_status_total."],";
                }
				$sub_sub_status .= "]},";
                ?>
        <?php  $i++;}  ?> 
         <?php  $sub_status .="]},";
		 $sub_status .= $sub_sub_status;
		 	?> 
            </tr>
            <?php  $csv.="\n";} 
			
			$csv.='"Total"'.','.'"'.$subnum_dept_comp.'"'.',';		

			?>
            <tr id="total">
              <th><span style="float: left; width:350px;">Total</span></th>
              <td><span class="Icon <?php echo $icon;?>" align="right"><b><span align="right"><?php echo $subnum_dept_comp; ?> </span></b><br />
                </span></td>
            <?php foreach($sub_status_count as $value){ ?>
              <td><b><?php echo $value; //$csv .= '"' . $value . '",'; ?></b></td>
           <?php } 
		   
		   
		   
		   
		   $csv.="\n";
		   $file = 'query_dept_status_report.csv';
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
		   ?>
            </tr>
            <?php }else{?>
            <tr class="<?php echo $class?>">
              <td><b>0 results found.</b></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
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
			var defaultTitle = "Queries Status";
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
            text: 'Primary Status'
            },
            subtitle: {
            text: 'Click the slices to view Status.'
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