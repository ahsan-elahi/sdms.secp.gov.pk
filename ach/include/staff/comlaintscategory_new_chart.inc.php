<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("comlaintstatus_new_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
//myWindow.close();
}
</script>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
?>
<?php 
if($thisstaff->isFocalPerson() == '1' || $thisstaff->getGroupId()=='8')
{
	$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	$dept_id = $thisstaff->getDeptId();
	$_REQUEST['dept_id'] = $thisstaff->getDeptId();
}
elseif(!$thisstaff->isAdmin() &&  $thisstaff->onChairman() == '1' )
{
	$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	$dept_id = $thisstaff->getDeptId();
	$_REQUEST['dept_id'] = $thisstaff->getDeptId();
	//$dept_add .= ' AND dept_id = '.$_REQUEST['dept_id'].'';
	//$dept_id = $_REQUEST['dept_id']; 
}
elseif($thisstaff->isAdmin() && $_REQUEST['dept_id']!='')
{
$dept_add .= ' AND dept_id = '.$_REQUEST['dept_id'].'';
$dept_id = $_REQUEST['dept_id'];
}


if($_REQUEST['dept_id']=='')
{
	$dept_add .= ' AND dept_id = 1';
	$dept_id = 1;
	$_REQUEST['dept_id'] = 1;
	$_REQUEST['topic_id'] = 0;
	
}
if($_REQUEST['from_date']!='' && $_REQUEST['to_date']!='')
{
$from_to_date = ' AND DATE(created) >= "'.date('Y-m-d',strtotime($_REQUEST['from_date'])).'" AND DATE(created) <= "'.date('Y-m-d',strtotime($_REQUEST['to_date'])).'"  ';
$date_range = '&startDate='.date('m/d/Y',strtotime($_REQUEST['from_date'])).'&endDate='.date('m/d/Y',strtotime($_REQUEST['to_date']));

}else{
$from_to_date ='';
$date_range = '';
}

function get_complaints_by_topic_id($topic_id){
	global $dept_add;
	global $from_to_date;

	$sql_topic_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND topic_id='".$topic_id."' ".$dept_add." ".$from_to_date."";
$res_topic_comp = mysql_query($sql_topic_comp);
$num_topic_comp = mysql_num_rows($res_topic_comp);
return $num_topic_comp;
}
function get_open_complaints_by_topic_id($topic_id){
	global $dept_add;
	global $from_to_date;

	$sql_topic_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND status = 'open' AND topic_id='".$topic_id."' ".$dept_add." ".$from_to_date."";
$res_topic_comp = mysql_query($sql_topic_comp);
$num_topic_comp = mysql_num_rows($res_topic_comp);
return $num_topic_comp;
}
function get_closed_complaints_by_topic_id($topic_id){
	global $dept_add;
	global $from_to_date;

	$sql_topic_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND status = 'closed' AND topic_id='".$topic_id."' ".$dept_add." ".$from_to_date."";
$res_topic_comp = mysql_query($sql_topic_comp);
$num_topic_comp = mysql_num_rows($res_topic_comp);
return $num_topic_comp;
}
function get_complaints_by_deparment(){
	global $dept_add;
	global $from_to_date;
	$sql_topic_deprt = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' ".$dept_add."  ".$from_to_date."";
$res_topic_deprt = mysql_query($sql_topic_deprt);
$num_topic_deprt = mysql_num_rows($res_topic_deprt);
return $num_topic_deprt;
}
function get_open_complaints_by_deparment(){
	global $dept_add;
	global $from_to_date;
	$sql_topic_deprt = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND status = 'open' ".$dept_add."  ".$from_to_date."";
$res_topic_deprt = mysql_query($sql_topic_deprt);
$num_topic_deprt = mysql_num_rows($res_topic_deprt);
return $num_topic_deprt;
}
function get_closed_complaints_by_deparment(){
	global $dept_add;
	global $from_to_date;
	$sql_topic_deprt = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND status = 'closed'  ".$dept_add."  ".$from_to_date."";
$res_topic_deprt = mysql_query($sql_topic_deprt);
$num_topic_deprt = mysql_num_rows($res_topic_deprt);
return $num_topic_deprt;
}


?>
<div class="page-header">
  <h1>Nature of complaints <small>Summary</small></h1>
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
<option value="<?php echo $row_dept['dept_id'] ;?>" <?php if($row_dept['dept_id']==$_REQUEST['dept_id']){ ?> selected <?php }?>><?php echo $row_dept['dept_name'] ;?></option>
<?php } ?>
</select>
</td>
<?php }?>
<th width="20%" style="padding-top:12px;">From Date</th>
<td>
<input type="text" name="from_date" id="Datepicker" value="<?php echo $_REQUEST['from_date']; ?>" >
</td>
<th width="20%" style="padding-top:12px;">To Date</th>
<td>
<input type="text" name="to_date" id="Datepicker1" value="<?php echo $_REQUEST['to_date']; ?>" >
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
<?php
$i = 0;
$sql_categories_v1 = "SELECT * FROM `sdms_help_topic` WHERE topic_pid = '".$_REQUEST['topic_id']."' AND isnature = '0' ".$dept_add." ";
$res_categories_v1 = mysql_query($sql_categories_v1);
while($row = mysql_fetch_array($res_categories_v1)) 
{
	
$complaint_count  = count_complaint($row['topic_id'],$_REQUEST['dept_id'],1);	
if($complaint_count>0){	

if($i == 0)
{
$for_first = ",sliced: true,selected: true";
}
else{
$for_first = "";
}

if(strlen($row_categories_v1['topic'])>30)
{
	$soon =  '..';
}else{
	$soon =  '';
}
	$data_v1 .= "{name: '".addslashes(substr($row['topic'],0,30)).$soon."', y: ".$complaint_count.$for_first.", id: '".$row['topic_id']."'},";

$i++;
}

}
function count_complaint($topic_id,$department,$one_time){
	global $total_complaints;
	
	if($one_time == 1){
	$sql_tickets = "SELECT * FROM `sdms_ticket` WHERE dept_id = '".$department."' AND topic_id='".$topic_id."' AND isquery = '0'";
	$res_tickets = mysql_query($sql_tickets);
	$num_tickets = mysql_num_rows($res_tickets);
	$total_complaints = $num_tickets;
	$one_time ++;
	}
	
	$sql_inner_topics = "SELECT * FROM `sdms_help_topic` WHERE dept_id = '".$department."' AND topic_pid = '".$topic_id."' AND isnature = '0' ";
	$res_inner_topics = mysql_query($sql_inner_topics);
	
	//$num_inner_topics = mysql_num_rows($res_inner_topics);
	//echo '<br>'.$num_inner_topics.'<br>';
	while($row_inner_topics = mysql_fetch_array($res_inner_topics)){
			
		$sql_inner_tickets = "SELECT * FROM `sdms_ticket` WHERE  dept_id = '".$department."' AND topic_id='".$row_inner_topics['topic_id']."' AND isquery = '0'";
		$res_inner_tickets = mysql_query($sql_inner_tickets);
		$num_inner_tickets = mysql_num_rows($res_inner_tickets);
		$total_complaints += $num_inner_tickets;
		//echo '<br>Ticket in '.$row_inner_topics['topic_id'].' ='. $total_complaints;	
		count_complaint($row_inner_topics['topic_id'],$department,$one_time);
		}
	//echo '<br>Ticket Total ='. $total_complaints;	
	return $total_complaints;
	
} ?>
<div class="row-fluid">
            <div class="span12">
            <div class="head clearfix">
            <div class="isw-right_circle"></div>
            <h1>Pie charts</h1>
            </div>
            <div class="block">
            <div id="container_v1" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>			
            </div>
            </div>         
            <script>
			var defaultTitle = "Nature of complaints";
			var drilldownTitle = "More about ";
			
            Highcharts.chart('container_v1', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
	title: {
	text: defaultTitle
	},
	subtitle: {
	text: 'Click the slices to view categories.'
	},
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.y}',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Category',
        colorByPoint: true,
        data: [<?php echo $data_v1; ?>],
		point:{
                  events:{
                      click: function (event) {
                          window.location.replace('comlaintscategory_new_chart.php?dept_id=<?php echo $_REQUEST['dept_id'] ?>&topic_id='+this.id);
                      }
                  }
              } 
    }]
});
            </script>                  
    </div>
    
<div class="dr"><span></span></div>
</div>
</div>
