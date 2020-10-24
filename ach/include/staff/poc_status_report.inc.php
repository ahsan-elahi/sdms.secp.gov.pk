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
			url:"departmental_summary_report_export_to_csv.php",
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
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied'); 				
if($thisstaff->isFocalPerson() == '1' || $thisstaff->getGroupId()=='8')
{
	$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	$dept_id = $thisstaff->getDeptId();
	$_POST['dept_id'] = $thisstaff->getDeptId();
}
elseif(!$thisstaff->isAdmin() &&  $thisstaff->onChairman() == '1')
{
	$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	$dept_id = $thisstaff->getDeptId();
	$_POST['dept_id'] = $thisstaff->getDeptId();
	//$dept_add .= ' AND dept_id = '.$_POST['dept_id'].'';
	//$dept_id = $_POST['dept_id'];
}
elseif($thisstaff->isAdmin() && $_POST['dept_id']!='')
{
$dept_add .= ' AND dept_id = '.$_POST['dept_id'].'';
$dept_id = $_POST['dept_id'];
}
if($_POST['dept_id']=='')
{
	$dept_add .= ' AND dept_id = 1';
	$dept_id = 1;
	$_POST['dept_id'] = 1;
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
<div class="page-header">
  <h1>POC / Sub POC Performance Status <small> Summary</small></h1>
</div>
<!--<div class="row-fluid">
  <div class="span3" style="float:right;">
      <a href="departmental_summary_report.csv"><input style="float:left;display:none;" id="csv_download" type="button"  value="Dowanlod CSV" name="download_csv"  ></a>
      <p align="right" style="float:right;"> 
      <button class="btn" type="button" id="csv_download" onClick="export_to_csv()"><i class="icon-print"></i> Export</button>
      </p>
  </div>
</div>-->

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
<?php if($thisstaff->isAdmin() ){
	//|| $thisstaff->onChairman() == '1' ?>
<th width="20%" style="padding-top:12px;">By Department</th>
<td  >
<select name="dept_id" >
<option value="">--Select Department--</option>
<?php 
$sql_get_dept='SELECT * from  sdms_department WHERE 1 ';
$res_get_dept = mysql_query($sql_get_dept);
while($row_dept = mysql_fetch_array($res_get_dept)){
?>
<option value="<?php echo $row_dept['dept_id'] ;?>" <?php if($row_dept['dept_id']==$_POST['dept_id']){ ?> selected <?php }?>><?php echo $row_dept['dept_name'] ;?></option>
<?php } ?>
</select>
</td>
<?php }?>
<th width="20%" style="padding-top:12px;">From Date</th>
<td>
<input type="text" name="from_date" id="Datepicker"  value="<?php echo $_POST['from_date']; ?>" >
</td>
<th width="20%" style="padding-top:12px;">To Date</th>
<td>
<input type="text" name="to_date" id="Datepicker1"  value="<?php echo $_POST['to_date']; ?>" >
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

<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>POC / Sub POC Performance</h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
      <form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
        <input type="hidden" name="a" value="mass_process" >
        <input type="hidden" name="status" value="<?php echo $statusss?>" >
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
          <thead>
            <tr>
              <th rowspan="2">POC / Sub POC</th>
              <th rowspan="2" align="center">Total Complaints</th>
              <th colspan="5" align="center">Status</th>
            </tr>
            <tr>
             <?php 
			 $sql_status="SELECT * FROM `sdms_status` WHERE p_id='0'";
$res_status=mysql_query($sql_status);
$num_status = mysql_num_rows($res_status);
while($row_status=mysql_fetch_array($res_status)){
			  ?>
              <th><a onclick="show_substatus(<?php echo $row_status['status_id']; ?>)" id='show_temp_status_<?php echo $row_status['status_id']; ?>'><?php echo $row_status['status_title'];?></a></th>
              <?php 
			  $sql_sub_status="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
$res_sub_status=mysql_query($sql_sub_status);
$num_sub_status = mysql_num_rows($res_sub_status);
while($row_sub_status=mysql_fetch_array($res_sub_status)){
	
	  ?>
              <th style="display:none;" class="status_<?php echo $row_status['status_id']; ?>"><?php echo $row_sub_status['status_title'];?></th>
              <?php }  ?>
             <?php  } ?> 
            </tr>
          </thead>
          <tbody class="" page="1">
            <?php 
$sub_status_count = array();
			
$sql_staff="SELECT * FROM `sdms_staff` WHERE group_id != '4' AND  group_id != '7' AND  group_id != '9' ".$dept_add." order by dept_id asc";
$res_staff=mysql_query($sql_staff);
$num_staff = mysql_num_rows($res_staff);
if($num_staff>0){
	$subnum_dept_comp = 0;
while($row_staff=mysql_fetch_array($res_staff)){
	
	$sql_dept="SELECT * FROM `sdms_department` WHERE dept_id='".$row_staff['dept_id']."'";
$res_dept=mysql_query($sql_dept);
$row_dept = mysql_fetch_array($res_dept);


	$num_staff_comp = 0;
$sql_staff_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND staff_id='".$row_staff['staff_id']."' ".$dept_add." ".$from_to_date."";
$res_staff_comp = mysql_query($sql_staff_comp);
$num_staff_comp += mysql_num_rows($res_staff_comp);
$staff_name = '';
?>
<tr>

    <th><span style="float:left; width:350px;"  ><?php 
	
	$staff_name .= $row_staff['firstname'].' '.$row_staff['lastname'];
	
	if($row_staff['isadmin']=='1'){
	$staff_name .= '<br><b>(Administrator)</b>';
	}elseif($row_staff['isfocalperson']=='1'){
	$staff_name .= '<br><b>('.$row_dept['dept_name'].' POC)</b>';
	
	}elseif($row_staff['isfocalperson']!='1'){
	$staff_name .= '<br><b>('.$row_dept['dept_name'].' Sub POC)</b>';
	}
	echo $staff_name;
	?></span></th> 		
    
    <td><b><span align="right"><a href="tickets.php?a=search&assign_staff=<?php echo $row_staff['staff_id']; ?>&deptId=<?php echo $row_staff['dept_id'].$date_range; ?>"><?php echo $num_staff_comp; $subnum_dept_comp +=$num_staff_comp; ?></a></span></b></td>
    
    <?php 
   //pie chart work
   $primary_status .= "{ name: '".$staff_name."', y: ".$num_staff_comp.",
            drilldown: '".$row_staff['staff_id']."'},";
	$sub_status .="{
            name: '".$row_staff['staff_id']."',
            id: '".$row_staff['staff_id']."',
            data: [ ";
   ?>  
				
<?php 
$sql_status="SELECT * FROM `sdms_status` WHERE p_id='0'";
$res_status=mysql_query($sql_status);
$subnum_status_comp = 0;
$i=0;
while($row_status=mysql_fetch_array($res_status)){
$num_status_comp = 0;
$sql_sub_status="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
$res_sub_status=mysql_query($sql_sub_status);
$num_sub_status = mysql_num_rows($res_sub_status);
while($row_sub_status=mysql_fetch_array($res_sub_status)){	
$sql_status_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND  staff_id='".$row_staff['staff_id']."' ".$from_to_date."";
$res_status_comp = mysql_query($sql_status_comp);
$num_status_comp += mysql_num_rows($res_status_comp);

}
$sub_status .="['".$row_status['status_title']."', ".$num_status_comp."],";
?>
              <td><a href="tickets.php?a=search&assign_staff=<?php echo $row_staff['staff_id']; ?>&deptId=<?php echo $row_staff['dept_id']; ?>&primary_stutus=<?php echo $row_status['status_id'].$date_range; ?>"><?php echo $num_status_comp; $sub_status_count[$i] +=$num_status_comp;?></a></td>
              <?php
                
                $sql_sub_status_inner="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
                $res_sub_status_inner=mysql_query($sql_sub_status_inner);
                $num_sub_status_inner = mysql_num_rows($res_sub_status_inner);
				while($row_sub_status_inner=mysql_fetch_array($res_sub_status_inner)){
                $num_status_comp_inner = 0;
                $sql_status_comp_inner = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."' AND  staff_id='".$row_staff['staff_id']."' ".$from_to_date."";
                $res_status_comp_inner = mysql_query($sql_status_comp_inner);
                $num_status_comp_inner = mysql_num_rows($res_status_comp_inner);
				?>
                
                <td style="display:none;" class="status_<?php echo $row_status['status_id']; ?>">
				<a href="tickets.php?a=search&assign_staff=<?php echo $row_staff['staff_id']; ?>&deptId=<?php echo $row_staff['dept_id']; ?>&cstatus=<?php echo $row_sub_status_inner['status_id'].$date_range; ?>"><?php echo $num_status_comp_inner; ?></a></td>
                <?php 
                }
                ?>
                
        <?php  $i++;} ?> 
<?php  $sub_status .="]
},
";	?> 
            </tr>
           
            <?php } ?>
            <tr id="total">
              <th><span style="float: left; width:350px;">Total</span></th>
              <td><span class="Icon <?php echo $icon;?>" align="right"><b><span align="right"><?php echo $subnum_dept_comp; ?> </span></b><br />
                </span></td>
               <?php foreach($sub_status_count as $value){ ?>
              <td><b><?php echo $value; //$csv .= '"' . $value . '",'; ?></b></td>
           <?php }  ?> 
            </tr>
            <?php }else{?>
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
			var defaultTitle = "Complaints Status";
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
