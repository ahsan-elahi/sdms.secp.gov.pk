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
	$_POST['dept_id'] = $thisstaff->getDeptId();
}
elseif(!$thisstaff->isAdmin() &&  $thisstaff->onChairman() == '1' )
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
$from_to_date = ' AND DATE(created) >= "'.date('Y-m-d',strtotime($_POST['from_date'])).'" AND DATE(created) <= "'.date('Y-m-d',strtotime($_POST['to_date'])).'"';
$date_range = '&startDate='.date('m/d/Y',strtotime($_REQUEST['from_date'])).'&endDate='.date('m/d/Y',strtotime($_REQUEST['to_date']));

}
else{
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
function get_complaints_by_deparment(){
	global $dept_add;
	global $from_to_date;
	$sql_topic_deprt = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' ".$dept_add."  ".$from_to_date."";
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
<option value="<?php echo $row_dept['dept_id'] ;?>" <?php if($row_dept['dept_id']==$_POST['dept_id']){ $dept_name = $row_dept['dept_name'];  ?> selected <?php }?>><?php echo $row_dept['dept_name'] ;?></option>
<?php } ?>
</select>
</td>
<?php }?>
<th width="20%" style="padding-top:12px;">From Date</th>
<td>
<input type="text" name="from_date" id="Datepicker" value="<?php echo $_POST['from_date']; ?>" >
</td>
<th width="20%" style="padding-top:12px;">To Date</th>
<td>
<input type="text" name="to_date" id="Datepicker1" value="<?php echo $_POST['to_date']; ?>" >
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
      <h1><?php echo $dept_name; ?> Category Summary</h1>
    </div>
    <div class="block-fluid">
      

        
       
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
  <thead>
  <tr>
    <td width="221">Primary    Category </td>
    <td width="304">Sub Category </td>
    <td width="22"></td>
    <td width="73">Total</td>
    <th width="73">Open</th>
    <th width="73">Closed</th>


  </tr>
  </thead>
  <tbody>
  <tr><td colspan="6">
          <?php 
		category_tree(0);
 
//Recursive php function
function category_tree($catid){


	$sql_topics = "SELECT * FROM `sdms_help_topic` WHERE dept_id = '".$_POST['dept_id']."' AND topic_pid = '".$catid."' AND isnature = '0' ";
	$res_topics = mysql_query($sql_topics);
	//$num_topics = mysql_num_rows($res_topics);
	//echo '<br>'.$num_topics.'<br>';
	while($row = mysql_fetch_array($res_topics)){
		$i = 0;
		if ($i == 0)
		{ 
			echo '<ul>';
		}
		echo '<li>' .$row['topic'];
				
				echo '<div style="text-align: center;margin-right: 240px;float: right;">(';
				echo '<a href="tickets.php?a=search&deptId='.$_POST['dept_id'].'&topicId='.$row['topic_id'].$date_range.'">';
				echo count_complaint($row['topic_id'],$_POST['dept_id'],1);
				echo '</a>';
				echo ')</div>';

				echo '<div style="text-align: center;margin-right: -120px;float: right;">(';
				echo get_open_complaints_by_topic_id($row['topic_id']);
				echo ')</div>';

				echo '<div style="text-align: center;margin-right: -220px;float: right;">(';
				echo get_closed_complaints_by_topic_id($row['topic_id']);	
				echo ')</div>';

		
				//echo '<li>' .$row['topic'].' (<a>'.count_complaint($row['topic_id'],$_POST['dept_id'],1).')';
				
				
				if($row['topic_id'] != $catid){
				category_tree($row['topic_id']);
				}
				echo '</li>';
		$i++;
		if ($i > 0) 
		{
			echo '</ul>';
		}
	}
}
function count_complaint($topic_id,$department,$one_time){
	global $total_complaints;
	global $from_to_date;
	if($one_time == 1){
	$sql_tickets = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND topic_id='".$topic_id."' AND dept_id='".$department."' ".$from_to_date."";
	$res_tickets = mysql_query($sql_tickets);
	$num_tickets = mysql_num_rows($res_tickets);
	$total_complaints = $num_tickets;
	//echo '<br>Ticket default in '.$topic_id.' ='. $total_complaints;
	$one_time ++;
	}
	$sql_inner_topics = "SELECT * FROM `sdms_help_topic` WHERE dept_id = '".$department."' AND topic_pid = '".$topic_id."' AND isnature = '0' ";
	$res_inner_topics = mysql_query($sql_inner_topics);
	//$num_inner_topics = mysql_num_rows($res_inner_topics);
	//echo '<br>'.$num_inner_topics.'<br>';
	while($row_inner_topics = mysql_fetch_array($res_inner_topics)){
			
		$sql_inner_tickets = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND topic_id='".$row_inner_topics['topic_id']."' AND dept_id='".$department."' ".$from_to_date."";
		$res_inner_tickets = mysql_query($sql_inner_tickets);
		$num_inner_tickets = mysql_num_rows($res_inner_tickets);
		$total_complaints += $num_inner_tickets;
		//echo '<br>Ticket in '.$row_inner_topics['topic_id'].' ='. $total_complaints;	
		count_complaint($row_inner_topics['topic_id'],$department,$one_time);
		}
	//echo '<br>Ticket Total ='. $total_complaints;	
	return $total_complaints;
}
function get_open_complaints_by_topic_id($topic_id){
	global $dept_add;
	global $from_to_date;
	global $dept_id;
	global $date_range;

	$sql_topic_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND status = 'open' AND topic_id='".$topic_id."' ".$dept_add." ".$from_to_date."";
$res_topic_comp = mysql_query($sql_topic_comp);
$num_topic_comp = mysql_num_rows($res_topic_comp);
return '<a href="tickets.php?a=search&amp;deptId='.$dept_id.'&amp;topicId='.$topic_id.'&status=open'.$date_range.'">'.$num_topic_comp.'</a>';
//return $num_topic_comp;

}
function get_closed_complaints_by_topic_id($topic_id){
	global $dept_add;
	global $from_to_date;
	global $dept_id;
	global $date_range;

	$sql_topic_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND status = 'closed' AND topic_id='".$topic_id."' ".$dept_add." ".$from_to_date."";
$res_topic_comp = mysql_query($sql_topic_comp);
$num_topic_comp = mysql_num_rows($res_topic_comp);
return '<a href="tickets.php?a=search&amp;deptId='.$dept_id.'&amp;topicId='.$topic_id.'&status=closed'.$date_range.'">'.$num_topic_comp.'</a>';

//return $num_topic_comp;
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
  </td>
  </tr>
  
   <tr>
    <td colspan="3"><b>Not Categorized</b></td>
    <td>
<?php 
echo '<a href="tickets.php?a=search&deptId='.$_POST['dept_id'].'&topicId=43'.$date_range.'">';
				echo get_complaints_by_topic_id(43);
				echo '</a>';
				?></td>
				<td><?php echo get_open_complaints_by_topic_id(43);?></td>
				<td><?php echo get_closed_complaints_by_topic_id(43);?></td>
				
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
	<td>
	<?php 
	echo '<a href="tickets.php?a=search&deptId='.$_POST['dept_id'].$date_range.'">';
	echo get_complaints_by_deparment();
	echo '</a>';
	?>
	</td>
	<td><?php echo get_open_complaints_by_deparment();?></td>
	<td><?php echo get_closed_complaints_by_deparment();?></td>
  </tr>
</tbody>
</table>

      
    </div>
  </div>
</div>
<?php

/*$sql_dept="SELECT * FROM `sdms_department` WHERE 1 ".$dept_add."";
$res_dept=mysql_query($sql_dept);
$num_dept = mysql_num_rows($res_dept);
if($num_dept>0){
	$subnum_dept_comp = 0;
while($row_dept=mysql_fetch_array($res_dept)){
$num_dept_comp = 0;
$sql_dept_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND dept_id='".$row_dept['dept_id']."' ".$from_to_date."";
$res_dept_comp = mysql_query($sql_dept_comp);
$num_dept_comp = mysql_num_rows($res_dept_comp);

$primary_status .= "{ name: '".$row_dept['dept_name']."', y: ".$num_dept_comp.",
            drilldown: '".$row_dept['dept_name']."'},";

$sub_status .="{
			name: '".$row_dept['dept_name']."',
            id: '".$row_dept['dept_name']."',
            data: [ ";
			
$sql_comp_topic = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND dept_id='".$row_dept['dept_id']."' ".$from_to_date." group by topic_id";
$res_comp_topic = mysql_query($sql_comp_topic);
while($row_comp_topic = mysql_fetch_array($res_comp_topic)){
	
$sql_topic_data = "SELECT * FROM `sdms_help_topic` WHERE topic_id = '".$row_comp_topic['topic_id']."'";
$res_topic_data = mysql_query($sql_topic_data);
$row_topic_data = mysql_fetch_array($res_topic_data);

$sql_topic_pdata = "SELECT * FROM `sdms_help_topic` WHERE topic_id = '".$row_topic_data['topic_pid']."'";
$res_topic_pdata = mysql_query($sql_topic_pdata);
$row_topic_pdata = mysql_fetch_array($res_topic_pdata);
	
$sql_topic_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND topic_id='".$row_comp_topic['topic_id']."' AND dept_id='".$row_dept['dept_id']."' ".$from_to_date."";
$res_topic_comp = mysql_query($sql_topic_comp);
$num_topic_comp = mysql_num_rows($res_topic_comp);

//$sub_status .="['".$row_topic_pdata['topic'].'=>'.$row_topic_data['topic']."', ".$num_topic_comp."],";
if(strlen($row_topic_data['topic'])>60)
{
	$soon =  '....';
}else{
	$soon =  '';
}
$sub_status .="['".addslashes(substr($row_topic_data['topic'],0,60)).$soon."', ".$num_topic_comp."],";
}
$sub_status .="]
},
";	
}
}*/

$sql_dept="SELECT * FROM `sdms_department` WHERE 1 ".$dept_add."";
$res_dept=mysql_query($sql_dept);
$num_dept = mysql_num_rows($res_dept);
if($num_dept>0){
	$subnum_dept_comp = 0;
while($row_dept=mysql_fetch_array($res_dept)){
$num_dept_comp = 0;
$sql_dept_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND dept_id='".$row_dept['dept_id']."' ".$from_to_date."";
$res_dept_comp = mysql_query($sql_dept_comp);
$num_dept_comp = mysql_num_rows($res_dept_comp);

/*$primary_status .= "{ name: '".$row_dept['dept_name']."', y: ".$num_dept_comp.",
            drilldown: '".$row_dept['dept_name']."'},";*/


			
$sql_comp_topic = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND dept_id='".$row_dept['dept_id']."' ".$from_to_date." group by topic_id";
$res_comp_topic = mysql_query($sql_comp_topic);
while($row_comp_topic = mysql_fetch_array($res_comp_topic)){
	
$sql_topic_data = "SELECT * FROM `sdms_help_topic` WHERE topic_id = '".$row_comp_topic['topic_id']."'";
$res_topic_data = mysql_query($sql_topic_data);
$row_topic_data = mysql_fetch_array($res_topic_data);

$sql_topic_pdata = "SELECT * FROM `sdms_help_topic` WHERE topic_id = '".$row_topic_data['topic_pid']."'";
$res_topic_pdata = mysql_query($sql_topic_pdata);
$row_topic_pdata = mysql_fetch_array($res_topic_pdata);
	
$sql_topic_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND topic_id='".$row_comp_topic['topic_id']."' AND dept_id='".$row_dept['dept_id']."' ".$from_to_date."";
$res_topic_comp = mysql_query($sql_topic_comp);
$num_topic_comp = mysql_num_rows($res_topic_comp);

//$sub_status .="['".$row_topic_pdata['topic'].'=>'.$row_topic_data['topic']."', ".$num_topic_comp."],";
if(strlen($row_topic_data['topic'])>60)
{
	$soon =  '....';
}else{
	$soon =  '';
}

$primary_status .= "{ name: '".addslashes(substr($row_topic_data['topic'],0,60)).$soon."', y: ".$num_topic_comp.",
            drilldown: '".$row_dept['dept_name']."'},";
/*$sub_status .="['".addslashes(substr($row_topic_data['topic'],0,60)).$soon."', ".$num_topic_comp."],";*/
}
	
}
}
/*
 echo $primary_status.'<br>'; 
 echo $sub_status; */
 $defaultTitle = '"Nature of '.$dept_name.' Complaints"';
?>
<div class="row-fluid">
            <div class="span12">
            <div class="head clearfix">
            <div class="isw-right_circle"></div>
            <h1>Pie Charts</h1>
            </div>
            <div class="block">
            <div id="container" style="height:600px;"></div>
            </div>
            </div>
            <script type="text/javascript">
			// Create the chart
			var defaultTitle = <?php echo $defaultTitle; ?>;
			var drilldownTitle = "More about ";
            Highcharts.chart('container', {
            chart: {
            type: 'pie'
            },
			title: {
            text: defaultTitle
            },
            subtitle: {
            text: ''
            },
			
            plotOptions: {
            series: {
            dataLabels: {
            enabled: true,
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
            }]
            });
			</script>                        
    </div>
<div class="dr"><span></span></div>
</div>
</div>
