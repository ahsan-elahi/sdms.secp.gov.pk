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
elseif(!$thisstaff->isAdmin() &&  $thisstaff->onChairman() == '1' && $_POST['dept_id']!='')
{
	/*$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	$dept_id = $thisstaff->getDeptId();
	$_POST['dept_id'] = $thisstaff->getDeptId();*/
	$dept_add .= ' AND dept_id = '.$_POST['dept_id'].'';
	$dept_id = $_POST['dept_id'];
}
elseif($thisstaff->isAdmin() && $_POST['dept_id']!='')
{
$dept_add .= ' AND dept_id = '.$_POST['dept_id'].'';
$dept_id = $_POST['dept_id'];
}
/*if($_POST['dept_id']=='')
{
	$dept_add .= ' AND dept_id = 1';
	$dept_id = 1;
	$_POST['dept_id'] = 1;
}*/
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
  <h1>HOD Login <small>Summary</small></h1>
</div>
<?php if($thisstaff->isAdmin() || $thisstaff->onChairman() == '1'){ ?>
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
<?php }?>
<div class="row-fluid">
  <div class="span12">
<div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Department HOD</h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
            <col width="26">
            <col width="221">
            <col width="60">
            <col width="95">
            <col width="88">
            <tr>
              <td width="26">No.</td>
              <td width="221">Department/POCs</td>
              <td width="60">No. of Days <br>
                Logged in </td>
              <td width="95">Individual <br>
                Login <br>
                %age</td>
              <td width="88">Departmental <br>
                Login %age </td>
            </tr>
			<?php 
$now = time(); // or your date as well
$your_date = strtotime("2017-06-01");
$datediff = $now - $your_date;
$total_days = floor($datediff / (60 * 60 * 24));
			$i=1;
			$sql_dept="SELECT * FROM `sdms_department` where 1 ".$dept_add."  order by dept_id asc";	
            $res_dept=mysql_query($sql_dept);
            while($row_dept = mysql_fetch_array($res_dept)){
				$t_login_department = 0;
            ?>
            <tr>
              <td align="right"><?php echo $i; ?></td>
              <td colspan="4"><b><?php echo $row_dept['dept_name']; $dept_title = $row_dept['dept_name']; ?></b></td>
            </tr>
            <?php
$sql_staff="SELECT * FROM `sdms_staff` WHERE dept_id = '".$row_dept['dept_id']."' AND group_id = '4' order by group_id asc";
$res_staff=mysql_query($sql_staff);
$num_staff = mysql_num_rows($res_staff);
while($row_staff=mysql_fetch_array($res_staff)){
?>
<tr>
<td align="right"></td>
<td><?php echo $row_staff['firstname'].' '.$row_staff['lastname'];
$sql_group="SELECT * FROM `sdms_groups` WHERE group_id='".$row_staff['group_id']."'";
$res_group = mysql_query($sql_group);
$row_group = mysql_fetch_array($res_group);
echo '<br><b>('.$row_group['group_name'].')<b>';
$months  .= "'";
//'<br><b>('.$row_group['group_name'].')<b>'
$months  .= ''.$row_staff['firstname'].' '.$row_staff['lastname'].'';
$months  .= "',";
 ?></b></td>
    <td><?php 
	$sql_logins = "Select * from sdms_login where user_id=".$row_staff['staff_id']." ".$from_to_date." group by created";
	$res_logins = mysql_query($sql_logins);
	$num_logins = mysql_num_rows($res_logins);
	echo $num_logins;
	$data .= $num_logins.',';
	$t_login_department += $num_logins;
	?></td>
    <td><?php echo round(($num_logins/$total_days)*100).'%'; ?></td>
    <td></td>
</tr>
<?php } ?>
<tr>
<td colspan="2">Total</td>
<td><?php echo $t_login_department;  ?></td>
<td></td>
<td><?php echo round(($t_login_department/$total_days)*100).'%'; ?></td>
</tr>

            <?php $i++;	} ?>
</table>
</div>
  </div>
</div>
<div class="row-fluid">
<div class="span12">
<div class="head clearfix">
<div class="isw-right_circle"></div>
<h1>Bar charts</h1>
</div>
<div class="block">
<div id="container" style="margin: 0 auto"></div>
</div>
</div>
  <?php
 // $months  =  " 'Jan', 'Feb','Mar', 'Apr','May','Jun', 'Jul','Aug', 'Sep','Oct','Nov','Dec'";
// echo $months;exit;
// $data = "49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4";
if($_POST['dept_id']!=''){
$dept_title = $dept_title.' '.'HOD Login';
}else{
$dept_title = 'HOD Login';
}
   ?>          
<script type="text/javascript">

Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: '<?php echo $dept_title; ?>'
    },
    subtitle: {
       // text: 'Source: WorldClimate.com'
    },
    xAxis: {
        categories: [
            <?php echo $months; ?>
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Number of Logins'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Number of Logins',
        data: [<?php echo $data; ?>]

    }]
});
		</script>                        
    </div>
<div class="dr"><span></span></div>
</div>
<!--WorkPlace End-->
</div>
