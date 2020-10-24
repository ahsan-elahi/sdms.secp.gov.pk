<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

$csv='';						 		 		
$csv.='"Status of Complaint"'.','.'"Total Complaints"'.','.'"Ageing-IN DAYS"'.',';
$csv.="\n";
$csv .= '""'.','.'""'.','.'"1 to 15"'.','.'"15+"'.','.'"30+"'.','.'"45+"'.',';

$csv.="\n";
$q_dept_id = ''; 				
if($thisstaff->isFocalPerson() == '1' || $thisstaff->getGroupId()=='8')
{
	$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	$dept_id = $thisstaff->getDeptId();
	$q_dept_id = '&deptId='.$thisstaff->getDeptId().'';
}
elseif(!$thisstaff->isAdmin() &&  $thisstaff->onChairman() == '1' )
{
	$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	$dept_id = $thisstaff->getDeptId();
	$q_dept_id = '&deptId='.$dept_id.'';
	//$dept_add .= ' AND dept_id = '.$_POST['dept_id'].'';
	//$dept_id = $_POST['dept_id'];
	//$q_dept_id = '&deptId='.$_POST['dept_id'].'';
}
elseif($thisstaff->isAdmin() && $_POST['dept_id']!='')
{
$dept_add .= ' AND dept_id = '.$_POST['dept_id'].'';
$dept_id = $_POST['dept_id'];
$q_dept_id = '&deptId='.$_POST['dept_id'].'';
}
if($_POST['from_date']!='' && $_POST['to_date']!='')
{
$from_date = date('Y-m-d',strtotime($_REQUEST['from_date']));
$to_date = date('Y-m-d',strtotime($_REQUEST['to_date']));
	
$from_to_date = ' AND DATE(created) >= "'.date('Y-m-d',strtotime($_POST['from_date'])).'" AND DATE(created) <= "'.date('Y-m-d',strtotime($_POST['to_date'])).'"  ';
$date_range = '&startDate='.date('m/d/Y',strtotime($_REQUEST['from_date'])).'&endDate='.date('m/d/Y',strtotime($_REQUEST['to_date']));
}else{
$from_to_date ='';
$date_range = '';
}
?>
<script>
function openWin(dept_id)
{
//window.open(URL,name,specs,replace)
myWindow=window.open("comlaintstatus_new_print.php?dept_id="+dept_id,"Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
//myWindow.close();
}
function export_to_csv(){
	var dept_id = '';
	$.ajax({
	url:"comlaintstatus_new_csv.php",
	data: "dept_id="+dept_id,
	success: function(msg){	
	//alert(msg);
	document.getElementById("csv_download").click();
	}
	});				
}

function export_to_csv_search(dept_id){
	$.ajax({
	url:"comlaintstatus_new_csv.php",
	data: "dept_id="+dept_id,
	success: function(msg){	
	//alert(msg);
	document.getElementById("csv_download").click();
	}
	});				
}
var d1 = [];
var d2 = [];
var d3 = [];
var data =[];
</script>
<div class="page-header">
  <h1>Status Ageing<small> Summary</small></h1>
</div>
<div class="row-fluid">
  <div class="span3" style="float:right;">
    <!--<p align="right" style="float:right;"> <a id="ticket-print" class="action-button" href="" onclick="openWin(<?php //echo $dept_id; ?>);">
      <button class="btn" type="button"><i class="icon-print"></i> Print</button>
      </a> </p>-->

<p align="right" style="float:right;"> 
<a href="comlaintstatus_new_csv.csv"><button class="btn" type="button"><i class="icon-print"></i> Export</button></a>
    </p>
  </div>
</div>
<!-- search code start -->
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
<?php if($thisstaff->isAdmin() ){ ?>
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
<?php } ?>
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
<!-- search code end -->
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Status Ageing</h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
      <form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
        <input type="hidden" name="a" value="mass_process" >
        <input type="hidden" name="status" value="<?php echo $statusss?>" >
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
          <thead>
            <tr>
              <th rowspan="2">Status of Complaint</th>
              <th rowspan="2" align="center">Total Complaints</th>
              <th colspan="4" align="center">Ageing-IN DAYS</th>
            </tr>
            <tr>
              <th>1 to 15 Days</th>
              <th>15+ Days</th>
              <th>30+ Days</th>
              <th>45+ Days</th>
            </tr>
          </thead>
          <tbody class="" page="1">
            <?php 
$sql_status="SELECT * FROM `sdms_status` WHERE p_id='0' AND status_id != '2' AND status_id != '5' ";
$res_status=mysql_query($sql_status);
$num_status = mysql_num_rows($res_status);
if($num_status>0){

$subt_1to15days = 0;
$subt_16to30days = 0;
$subt_31to45days = 0;
$subt_45daysplus = 0;	
$subnum_status_comp = 0;

$s_1_day=0;
$s_16_day=0;
$s_30_day=0;
$s_45_day=0;

while($row_status=mysql_fetch_array($res_status)){
$sub_sub_status = "";	
$t_1to15days = 0;
$t_16to30days = 0;
$t_31to45days = 0;
$t_45daysplus = 0;
$num_status_comp = 0;

$sql_sub_status="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
$res_sub_status=mysql_query($sql_sub_status);
$num_sub_status = mysql_num_rows($res_sub_status);
while($row_sub_status=mysql_fetch_array($res_sub_status)){
if($_POST['from_date']!='' && $_POST['to_date']!='')
{
$today_date =  date('Y-m-d',strtotime($_POST['to_date'])); 
$s_1_day =   date('Y-m-d',strtotime($_POST['to_date'])); 
}else{
$today_date =  date('Y-m-d'); 
$s_1_day =  date('Y-m-d'); 
}

$sql_status_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' ".$dept_add." ".$from_to_date."";
$res_status_comp = mysql_query($sql_status_comp);
$num_status_comp += mysql_num_rows($res_status_comp);

//echo "first loop 1 to 15 Days";
for($i=1;$i<16;$i++)
{
$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days += mysql_num_rows($res_1to15days);
$s_16_day = $today_date;
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}

//echo "second loop 15+ Days";
for($i=1;$i<16;$i++)
{
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days += mysql_num_rows($res_16to30days);
$s_30_day = $today_date;
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}

//echo "second loop 45+ Days";
for($i=1;$i<16;$i++)
{
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days += mysql_num_rows($res_31to45days);
$s_45_day =  $today_date;
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}


$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) <= '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus += mysql_num_rows($res_45daysplus);
}

if($s_1_day < $from_date){
$s_1_day=$from_date;
}else{
$s_1_day=$s_1_day;
}
if($s_16_day < $from_date){
	$s_16_day = $from_date;
}else{
	$s_16_day=$s_16_day;
}
if($s_30_day < $from_date){
	$s_30_day=$from_date;
}else{
$s_30_day=$s_30_day;
}
if($s_45_day < $from_date){
$s_45_day=$from_date;
}else{
$s_45_day=$s_45_day;
}
$primary_status .= "{ name: '".$row_status['status_title']."', y: ".$num_status_comp.",
            drilldown: '".$row_status['status_title']."'},";
$sub_status .="{
            name: '".$row_status['status_title']."',
            id: '".$row_status['status_title']."',
            data: [ ";
?>
         <tr>
              <th> <a onclick="show_item(<?php echo $row_status['status_id']; ?>)" id='show_temp_item_<?php echo $row_status['status_id']; ?>'><span style="float:left; width:350px;"  ><?php echo $row_status['status_title'];?></span></a></th>
              <td><b><span align="right"><a href="tickets.php?a=search&primary_stutus=<?php echo $row_status['status_id'].$q_dept_id.$date_range; ?>&status=open&action=report"><?php echo $num_status_comp; $subnum_status_comp +=$num_status_comp; ?></a></span></b></td>
              <td>
			  <a href="tickets.php?a=search&primary_stutus=<?php echo $row_status['status_id'].$q_dept_id; ?>&status=open&startDate=<?php echo date('m/d/Y',strtotime($s_16_day)); ?>&endDate=<?php echo date('m/d/Y',strtotime($s_1_day)); ?>&action=report">
			  <?php echo $t_1to15days; $subt_1to15days += $t_1to15days; ?>
              </a>
              </td>              
              <td> <a href="tickets.php?a=search&primary_stutus=<?php echo $row_status['status_id'].$q_dept_id; ?>&status=open&startDate=<?php echo date('m/d/Y',strtotime($s_30_day)); ?>&endDate=<?php echo date('m/d/Y',strtotime("-1 day",strtotime($s_16_day))); ?>&action=report"><?php echo $t_16to30days; $subt_16to30days += $t_16to30days;?></a></td>
              <td><a href="tickets.php?a=search&primary_stutus=<?php echo $row_status['status_id'].$q_dept_id; ?>&status=open&startDate=<?php echo date('m/d/Y',strtotime($s_45_day)); ?>&endDate=<?php echo date('m/d/Y',strtotime("-1 day",strtotime($s_30_day))); ?>&action=report"><?php echo $t_31to45days; $subt_31to45days += $t_31to45days;?></a></td>
              <td>
			   <a href="tickets.php?a=search&primary_stutus=<?php echo $row_status['status_id'].$q_dept_id; ?>&status=open&endDate=<?php echo date('m/d/Y',strtotime("-1 day",strtotime($s_45_day))); ?>&action=report"><?php echo $t_45daysplus; $subt_45daysplus += $t_45daysplus;?></a></td>
            </tr>  
            <?php
//$sub_status .="['1 to 15', ".$t_1to15days."],['15+', ".$t_16to30days."],['30+', ".$t_31to45days."],['45+', ".$t_45daysplus."]";
//$sub_status .="]},";
			 ?> 
            <?php if($num_sub_status>0){ ?>
            <tr>
                <td class="report_sub_table" align="CENTER" colspan="6" id="item_temp_section_<?php echo $row_status['status_id']; ?>">
                
                    <table >
                    <thead>            <tr>
              <th rowspan="2">Sub Status of Complaint</th>
              <th rowspan="2" align="center">Total Complaints</th>
              <th colspan="4" align="center">Ageing-IN DAYS</th>
            </tr>
            <tr>
              <th>1 to 15 Days</th>
              <th>15+ Days</th>
              <th>30+ Days</th>
              <th>45+ Days</th>
            </tr>
            
            
          </thead>
                        <tbody>
<?php						
$sql_sub_status_inner="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
$res_sub_status_inner=mysql_query($sql_sub_status_inner);
$num_sub_status_inner = mysql_num_rows($res_sub_status_inner);
while($row_sub_status_inner=mysql_fetch_array($res_sub_status_inner)){
	

if($_POST['from_date']!='' && $_POST['to_date']!='')
{
$today_date =  date('Y-m-d',strtotime($_POST['to_date'])); 
$s_1_day =   date('Y-m-d',strtotime($_POST['to_date'])); 
}else{
$today_date =  date('Y-m-d'); 
$s_1_day =  date('Y-m-d'); 
}
$t_1to15days = 0;
$t_16to30days = 0;
$t_31to45days = 0;
$t_45daysplus = 0;
$num_status_comp_inner = 0;

$sql_status_comp_inner = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."' ".$dept_add." ".$from_to_date."";
$res_status_comp_inner = mysql_query($sql_status_comp_inner);
$num_status_comp_inner += mysql_num_rows($res_status_comp_inner);


//echo "first loop 1 to 15 Days";
for($i=1;$i<16;$i++)
{
$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days += mysql_num_rows($res_1to15days);
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}

//echo "second loop 15+ Days";
for($i=1;$i<16;$i++)
{
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days += mysql_num_rows($res_16to30days);
//echo $today_date.'<br>';
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}

//echo "second loop 45+ Days";
for($i=1;$i<16;$i++)
{
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days += mysql_num_rows($res_31to45days);
//echo $today_date.'<br>';
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}

$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."' AND DATE(created) <= '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus += mysql_num_rows($res_45daysplus);
if($s_1_day < $from_date){
$s_1_day=$from_date;
}else{
$s_1_day=$s_1_day;
}
if($s_16_day < $from_date){
	$s_16_day = $from_date;
}else{
	$s_16_day=$s_16_day;
}
if($s_30_day < $from_date){
	$s_30_day=$from_date;
}else{
$s_30_day=$s_30_day;
}
if($s_45_day < $from_date){
$s_45_day=$from_date;
}else{
$s_45_day=$s_45_day;
}
$sub_status .="{ name:'".$row_sub_status_inner['status_title']."', y: ".$num_status_comp_inner.", drilldown: '".$row_dept['dept_name'].$row_sub_status_inner['status_title']."'},";
$sub_sub_status .= "{ id: '".$row_dept['dept_name'].$row_sub_status_inner['status_title']."',data: [";
//$sub_sub_status .= "['".$row_sub_status_inner['status_title']."' ,".$sub_status_total."],";
$sub_sub_status .="['1 to 15', ".$t_1to15days."],['15+', ".$t_16to30days."],['30+', ".$t_31to45days."],['45+', ".$t_45daysplus."]";
$sub_sub_status .= "]},";
?>
<tr>
              <td> <span style="float:left; width:430px;"><?php echo $row_sub_status_inner['status_title'];?></span></a></td>
              <td style="width:125px;""><a href="tickets.php?a=search&cstatus=<?php echo $row_sub_status_inner['status_id'].$q_dept_id.$date_range; ?>&status=open&action=report"><?php echo $num_status_comp_inner; ?></a></td>
              <td style="width:80px;"> <a href="tickets.php?a=search&cstatus=<?php echo $row_sub_status_inner['status_id']; ?>&status=open&startDate=<?php echo date('m/d/Y',strtotime($s_16_day)); ?>&endDate=<?php echo date('m/d/Y',strtotime($s_1_day)); ?>&action=report"><?php echo $t_1to15days;?></a></td>
              <td style="width:80px;"><a href="tickets.php?a=search&cstatus=<?php echo $row_sub_status_inner['status_id']; ?>&status=open&startDate=<?php echo date('m/d/Y',strtotime($s_30_day)); ?>&endDate=<?php echo date('m/d/Y',strtotime("-1 day",strtotime($s_16_day))); ?>&action=report"><?php echo $t_16to30days;?></a></td>
              <td style="width:80px;"><a href="tickets.php?a=search&cstatus=<?php echo $row_sub_status_inner['status_id']; ?>&status=open&startDate=<?php echo date('m/d/Y',strtotime($s_45_day)); ?>&endDate=<?php echo date('m/d/Y',strtotime("-1 day",strtotime($s_30_day))); ?>&action=report"><?php echo $t_31to45days;?></a></td>
              
              <td style="width:50px;"><a href="tickets.php?a=search&cstatus=<?php echo $row_sub_status_inner['status_id']; ?>&status=open&endDate=<?php echo date('m/d/Y',strtotime("-1 day",strtotime($s_45_day))); ?>&action=report"><?php echo $t_45daysplus;?></a></td>
     
            </tr>

<?php 
}
$sub_status .="]},";
$sub_status .= $sub_sub_status;
							 ?>
                        
                        </tbody>
                    </table>
                </td>
            </tr>
            <?php } ?>
            
            <?php 
			
			$csv .= '"' . $row_status['status_title'] . '",';
$csv .= '"' . $num_dept_comp. '",';
$csv .= '"' . $t_1to15days. '",';
$csv .= '"' . $t_16to30days. '",';
$csv .= '"' . $t_31to45days. '",';
$csv .= '"' . $t_45daysplus. '",';
			
      $csv.="\n"; 
			
			
			}?>
            <tr id="total">
              <th><span style="float: left; width:350px;">Total</span></th>
              <td><span class="Icon <?php echo $icon;?>" align="right"><b><span align="right"><?php echo $subnum_status_comp; ?> </span></b><br />
                </span></td>
              <td ><b><?php echo $subt_1to15days; ?></b></td>
              <td><b><?php echo $subt_16to30days; ?></b></td>
              <td><b><?php echo $subt_31to45days; ?></b></td>
              <td><b><?php echo $subt_45daysplus; ?></b></td>
            </tr>
            <?php
$csv.='"Total"'.','.'"'.$subnum_status_comp.'"'.','.'"'.$subt_1to15days.'"'.','.'"'.$subt_16to30days.'"'.','.'"'.$subt_31to45days.'"'.','.'"'.$subt_45daysplus.'"'.',';	
$csv.="\n";
$csv.="\n";
$csv.="\n";
$csv.="\n";
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
			var defaultTitle = "Status Ageing";
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

<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Status Ageing</h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
      <form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
        <input type="hidden" name="a" value="mass_process" >
        <input type="hidden" name="status" value="<?php echo $statusss?>" >
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
          <thead>
            <tr>
              <th rowspan="2">Status of Complaint</th>
              <th rowspan="2" align="center">Total Complaints</th>
              <th colspan="4" align="center">Ageing-IN DAYS</th>
            </tr>
            <tr>
              <th>1 to 15 Days</th>
              <th>15+ Days</th>
              <th>30+ Days</th>
              <th>45+ Days</th>
            </tr>
          </thead>
          <tbody class="" page="1">
            <?php 
$primary_status = '';
$sub_status = '' ;
$sql_status="SELECT * FROM `sdms_status` WHERE p_id='0' AND status_id = '2' OR status_id = '5' ";
$res_status=mysql_query($sql_status);
$num_status = mysql_num_rows($res_status);
if($num_status>0){

$subt_1to15days = 0;
$subt_16to30days = 0;
$subt_31to45days = 0;
$subt_45daysplus = 0;	
$subnum_status_comp = 0;

$s_1_day=0;
$s_16_day=0;
$s_30_day=0;
$s_45_day=0;

while($row_status=mysql_fetch_array($res_status)){
$sub_sub_status = "";
$t_1to15days = 0;
$t_16to30days = 0;
$t_31to45days = 0;
$t_45daysplus = 0;
$num_status_comp = 0;

$sql_sub_status="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
$res_sub_status=mysql_query($sql_sub_status);
$num_sub_status = mysql_num_rows($res_sub_status);
while($row_sub_status=mysql_fetch_array($res_sub_status)){
if($_POST['from_date']!='' && $_POST['to_date']!='')
{
$today_date =  date('Y-m-d',strtotime($_POST['to_date'])); 
$s_1_day =   date('Y-m-d',strtotime($_POST['to_date'])); 
}else{
$today_date =  date('Y-m-d'); 
$s_1_day =  date('Y-m-d'); 
}
$sql_status_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' ".$dept_add." ".$from_to_date."";
$res_status_comp = mysql_query($sql_status_comp);
$num_status_comp += mysql_num_rows($res_status_comp);

//echo "first loop 1 to 15 Days";
for($i=1;$i<16;$i++)
{
$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days += mysql_num_rows($res_1to15days);
$s_16_day = $today_date;
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}

//echo "second loop 15+ Days";
for($i=1;$i<16;$i++)
{
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days += mysql_num_rows($res_16to30days);
//echo $today_date.'<br>';
$s_30_day = $today_date;
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}

//echo "second loop 45+ Days";
for($i=1;$i<16;$i++)
{
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days += mysql_num_rows($res_31to45days);
//echo $today_date.'<br>';
$s_45_day =  $today_date;
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}

$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) <= '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus += mysql_num_rows($res_45daysplus);
}

if($s_1_day < $from_date){
$s_1_day=$from_date;
}else{
$s_1_day=$s_1_day;
}
if($s_16_day < $from_date){
	$s_16_day = $from_date;
}else{
	$s_16_day=$s_16_day;
}
if($s_30_day < $from_date){
	$s_30_day=$from_date;
}else{
$s_30_day=$s_30_day;
}
if($s_45_day < $from_date){
$s_45_day=$from_date;
}else{
$s_45_day=$s_45_day;
}
$primary_status .= "{ name: '".$row_status['status_title']."', y: ".$num_status_comp.",
            drilldown: '".$row_status['status_title']."'},";
$sub_status .="{
            name: '".$row_status['status_title']."',
            id: '".$row_status['status_title']."',
            data: [ ";
?>
         <tr>
              <th> <a onclick="show_item(<?php echo $row_status['status_id']; ?>)" id='show_temp_item_<?php echo $row_status['status_id']; ?>'><span style="float:left; width:350px;"  ><?php echo $row_status['status_title'];?></span></a></th>
              <td><b><span align="right"><a href="tickets.php?a=search&primary_stutus=<?php echo $row_status['status_id'].$q_dept_id.$date_range; ?>&status=closed&action=report"><?php echo $num_status_comp; $subnum_status_comp +=$num_status_comp; ?></a></span></b></td>
              <td>
			  <a href="tickets.php?a=search&primary_stutus=<?php echo $row_status['status_id'].$q_dept_id; ?>&status=closed&startDate=<?php echo date('m/d/Y',strtotime($s_16_day)); ?>&endDate=<?php echo date('m/d/Y',strtotime($s_1_day)); ?>&action=report">
			  <?php echo $t_1to15days;  $subt_1to15days += $t_1to15days;  ?>
              </a>
              </td>              
              <td> <a href="tickets.php?a=search&primary_stutus=<?php echo $row_status['status_id'].$q_dept_id; ?>&status=closed&startDate=<?php echo date('m/d/Y',strtotime($s_30_day)); ?>&endDate=<?php echo date('m/d/Y',strtotime("-1 day",strtotime($s_16_day))); ?>&action=report"><?php echo $t_16to30days; $subt_16to30days += $t_16to30days;?></a></td>
              <td><a href="tickets.php?a=search&primary_stutus=<?php echo $row_status['status_id'].$q_dept_id; ?>&status=closed&startDate=<?php echo date('m/d/Y',strtotime($s_45_day)); ?>&endDate=<?php echo date('m/d/Y',strtotime("-1 day",strtotime($s_30_day))); ?>&action=report"><?php echo $t_31to45days; $subt_31to45days += $t_31to45days;?></a></td>
              <td>
			   <a href="tickets.php?a=search&primary_stutus=<?php echo $row_status['status_id'].$q_dept_id; ?>&status=closed&endDate=<?php echo date('m/d/Y',strtotime("-1 day",strtotime($s_45_day))); ?>&action=report"><?php echo $t_45daysplus; $subt_45daysplus += $t_45daysplus;?></a></td>
            </tr>
            <?php 
		//	$sub_status .="['1 to 15', ".$t_1to15days."],['15+', ".$t_16to30days."],['30+', ".$t_31to45days."],['45+', ".$t_45daysplus."]";
		//$sub_status .="]},";
?>   
            <?php if($num_sub_status>0){ ?>
            <tr>
                <td class="report_sub_table" align="CENTER" colspan="6" id="item_temp_section_<?php echo $row_status['status_id']; ?>">
                
                    <table >
                    <thead>            <tr>
              <th rowspan="2">Sub Status of Complaint</th>
              <th rowspan="2" align="center">Total Complaints</th>
              <th colspan="4" align="center">Ageing-IN DAYS</th>
            </tr>
            <tr>
              <th>1 to 15 Days</th>
              <th>15+ Days</th>
              <th>30+ Days</th>
              <th>45+ Days</th>
            </tr>
            
            
          </thead>
                        <tbody>
                            
                            <?php

						
$sql_sub_status_inner="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
$res_sub_status_inner=mysql_query($sql_sub_status_inner);
$num_sub_status_inner = mysql_num_rows($res_sub_status_inner);
while($row_sub_status_inner=mysql_fetch_array($res_sub_status_inner)){
	if($_POST['from_date']!='' && $_POST['to_date']!='')
{
$today_date =  date('Y-m-d',strtotime($_POST['to_date'])); 
$s_1_day =   date('Y-m-d',strtotime($_POST['to_date'])); 
}else{
$today_date =  date('Y-m-d'); 
$s_1_day =  date('Y-m-d'); 
}
$t_1to15days = 0;
$t_16to30days = 0;
$t_31to45days = 0;
$t_45daysplus = 0;
$num_status_comp_inner = 0;

$sql_status_comp_inner = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."' ".$dept_add." ".$from_to_date."";
$res_status_comp_inner = mysql_query($sql_status_comp_inner);
$num_status_comp_inner += mysql_num_rows($res_status_comp_inner);


//echo "first loop 1 to 15 Days";
for($i=1;$i<16;$i++)
{
$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days += mysql_num_rows($res_1to15days);
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}

//echo "second loop 15+ Days";
for($i=1;$i<16;$i++)
{
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days += mysql_num_rows($res_16to30days);
//echo $today_date.'<br>';
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}

//echo "second loop 45+ Days";
for($i=1;$i<16;$i++)
{
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";

$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days += mysql_num_rows($res_31to45days);
//echo $today_date.'<br>';
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}

$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."' AND DATE(created) <= '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus += mysql_num_rows($res_45daysplus);

if($s_1_day < $from_date){
$s_1_day=$from_date;
}else{
$s_1_day=$s_1_day;
}
if($s_16_day < $from_date){
	$s_16_day = $from_date;
}else{
	$s_16_day=$s_16_day;
}
if($s_30_day < $from_date){
	$s_30_day=$from_date;
}else{
$s_30_day=$s_30_day;
}
if($s_45_day < $from_date){
$s_45_day=$from_date;
}else{
$s_45_day=$s_45_day;
}
$sub_status .="{ name:'".$row_sub_status_inner['status_title']."', y: ".$num_status_comp_inner.", drilldown: '".$row_dept['dept_name'].$row_sub_status_inner['status_title']."'},";
$sub_sub_status .= "{ id: '".$row_dept['dept_name'].$row_sub_status_inner['status_title']."',data: [";
//$sub_sub_status .= "['".$row_sub_status_inner['status_title']."' ,".$sub_status_total."],";
$sub_sub_status .="['1 to 15', ".$t_1to15days."],['15+', ".$t_16to30days."],['30+', ".$t_31to45days."],['45+', ".$t_45daysplus."]";
$sub_sub_status .= "]},";
?>
<tr>
              <td> <span style="float:left; width:430px;"><?php echo $row_sub_status_inner['status_title'];?></span></a></td>
              <td style="width:125px;""><a href="tickets.php?a=search&cstatus=<?php echo $row_sub_status_inner['status_id'].$q_dept_id.$date_range; ?>&status=closed&action=report"><?php echo $num_status_comp_inner; ?></a></td>
              <td style="width:80px;"> <a href="tickets.php?a=search&cstatus=<?php echo $row_sub_status_inner['status_id']; ?>&status=closed&startDate=<?php echo date('m/d/Y',strtotime($s_16_day)); ?>&endDate=<?php echo date('m/d/Y',strtotime($s_1_day)); ?>&action=report"><?php echo $t_1to15days;?></a></td>
              <td style="width:80px;"><a href="tickets.php?a=search&cstatus=<?php echo $row_sub_status_inner['status_id']; ?>&status=closed&startDate=<?php echo date('m/d/Y',strtotime($s_30_day)); ?>&endDate=<?php echo date('m/d/Y',strtotime("-1 day",strtotime($s_16_day))); ?>&action=report"><?php echo $t_16to30days;?></a></td>
              <td style="width:80px;"><a href="tickets.php?a=search&cstatus=<?php echo $row_sub_status_inner['status_id']; ?>&status=closed&startDate=<?php echo date('m/d/Y',strtotime($s_45_day)); ?>&endDate=<?php echo date('m/d/Y',strtotime("-1 day",strtotime($s_30_day))); ?>&action=report"><?php echo $t_31to45days;?></a></td>
              
              <td style="width:50px;"><a href="tickets.php?a=search&cstatus=<?php echo $row_sub_status_inner['status_id']; ?>&status=closed&endDate=<?php echo date('m/d/Y',strtotime("-1 day",strtotime($s_45_day))); ?>&action=report"><?php echo $t_45daysplus;?></a></td>
     
            </tr>

<?php 
}
$sub_status .="]},";
$sub_status .= $sub_sub_status;							 ?>
                        
                        </tbody>
                    </table>
                </td>
            </tr>
            <?php } ?>
            
            <?php 
			
			$csv .= '"' . $row_status['status_title'] . '",';
$csv .= '"' . $num_dept_comp. '",';
$csv .= '"' . $t_1to15days. '",';
$csv .= '"' . $t_16to30days. '",';
$csv .= '"' . $t_31to45days. '",';
$csv .= '"' . $t_45daysplus. '",';
			
      $csv.="\n"; 
			
			
			}?>
            <tr id="total">
              <th><span style="float: left; width:350px;">Total</span></th>
              <td><span class="Icon <?php echo $icon;?>" align="right"><b><span align="right"><?php echo $subnum_status_comp; ?> </span></b><br />
                </span></td>
              <td ><b><?php echo $subt_1to15days; ?></b></td>
              <td><b><?php echo $subt_16to30days; ?></b></td>
              <td><b><?php echo $subt_31to45days; ?></b></td>
              <td><b><?php echo $subt_45daysplus; ?></b></td>
            </tr>
            <?php
			
			 $csv.='"Total"'.','.'"'.$subnum_status_comp.'"'.','.'"'.$subt_1to15days.'"'.','.'"'.$subt_16to30days.'"'.','.'"'.$subt_31to45days.'"'.','.'"'.$subt_45daysplus.'"'.',';	
$csv.="\n";
$csv.="\n";
$csv.="\n";
$csv.="\n";
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
            <div id="container_second" style="margin: 0 auto"></div>
            </div>
            </div>
            <script type="text/javascript">
            // Create the chart
            Highcharts.chart('container_second', {
            chart: {
            type: 'pie'
            },
            title: {
            text: 'Primary Status'
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