<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
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

$mydate = date('m/d/Y',strtotime($_REQUEST['to_date']));
$first = strtotime($mydate);


}else{
$from_to_date ='';
$date_range = '';
$first  = strtotime('first day this month');
}



?>
<div class="page-header">
  <h1>Primary Status <small> Summary</small></h1>
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
<?php if($thisstaff->isAdmin() || $thisstaff->onChairman() == '1'){ ?>
<th width="20%" style="padding-top:12px;">By Department</th>
<td  >
<select name="dept_id" >
<option value="">--Select Department--</option>
<?php 
$sql_get_dept='SELECT * from  sdms_department WHERE 1 ';
$res_get_dept = mysql_query($sql_get_dept);
while($row_dept = mysql_fetch_array($res_get_dept)){
?>
<option value="<?php echo $row_dept['dept_id'] ;?>" <?php if($row_dept['dept_id']==$_POST['dept_id']){ $dept_name =  $row_dept['dept_name']; ?> selected <?php }?>><?php echo $row_dept['dept_name'] ;?></option>
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
<?php 
$in_process = '';
$closed = '';
$sub_judice = '';
$referred_to_third_party = '';
$resolved = '';
			
for ($i = 11; $i >= 0; $i--) {
$months  .= "'".date('F', strtotime("first day of -$i month", $first))."',";
$sql_status="SELECT * FROM `sdms_status` WHERE p_id='0'";
$res_status=mysql_query($sql_status);
$subnum_status_comp = 0;
while($row_status=mysql_fetch_array($res_status))
{
$num_status_comp = 0;
$sql_sub_status="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
$res_sub_status=mysql_query($sql_sub_status);
$num_sub_status = mysql_num_rows($res_sub_status);
while($row_sub_status=mysql_fetch_array($res_sub_status)){	
$sql_status_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' ".$dept_add." ".$from_to_date." AND MONTH(created)='".date('m', strtotime("first day of -$i month", $first))."'";
$res_status_comp = mysql_query($sql_status_comp);
$num_status_comp += mysql_num_rows($res_status_comp); }

if($row_status['status_title']=='In Process')
{
$in_process .= $num_status_comp.',';
}
if($row_status['status_title']=='Closed')
{
$closed .= $num_status_comp.',';
}
if($row_status['status_title']=='Sub Judice')
{
$sub_judice .= $num_status_comp.',';
}
if($row_status['status_title']=='Referred to third party')
{
$referred_to_third_party .= $num_status_comp.',';
}
if($row_status['status_title']=='Resolved')
{
$resolved .= $num_status_comp.',';
}
//$sub_status .="['".$row_status['status_title']."', ".$num_status_comp."],";
?>
<td><?php //echo $num_status_comp; ?></td>
<?php  } 
}
?>

<?php 

$data = "{
        name: 'In Process',
        data: [".$in_process."]
    }, {
        name: 'Closed',
        data: [".$closed."]
    }, {
        name: 'Sub Judice',
        data: [".$sub_judice."]
    }, {
        name: 'Referred to third party',
        data: [".$referred_to_third_party."]
    }, {
        name: 'Resolved',
        data: [".$resolved."]
    }";
	//echo $data;
?>
   <div class="row-fluid">
            <div class="span12">
            <div class="head clearfix">
            <div class="isw-right_circle"></div>
            <h1>Column charts</h1>
            </div>
            <div class="block">
            <div id="container" style="margin: 0 auto"></div>
            </div>
            </div>
          
           
<script type="text/javascript">

Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: '<?php echo $dept_name; ?>'
    },
    subtitle: {
       /* text: 'Source: WorldClimate.com'*/
    },
    xAxis: {
        categories: [<?php echo $months; ?>],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Total Complaints'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
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
    series: [<?php echo $data; ?>]
});
		</script>        
    
       
    
            </div> 
            
            <div class="row-fluid">
            <div class="span12">
            <div class="head clearfix">
            <div class="isw-right_circle"></div>
            <h1>Column charts</h1>
            </div>
            <div class="block">
            <div id="container_new" style="margin: 0 auto"></div>
            </div>
            </div>
          
           <script type="text/javascript">

Highcharts.chart('container_new', {
    chart: {
        type: 'column'
    },
    title: {
        text: '<?php echo $dept_name; ?>'
    },
    xAxis: {
        categories: [<?php echo $months; ?>]
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Total Complaints'
        },
        stackLabels: {
            enabled: true,
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        }
    },
    legend: {
        align: 'right',
        x: -30,
        verticalAlign: 'top',
        y: 25,
        floating: true,
        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
        borderColor: '#CCC',
        borderWidth: 1,
        shadow: false
    },
    tooltip: {
		headerFormat: '<b>{point.x}</b><br/>',
		pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}',
		formatter: function() {
		return '<b>'+ this.x +'</b><br/>'+
		this.series.name +': '+ this.y +'<br/>'+
		'Total: '+ this.point.stackTotal;
		}
    },
    plotOptions: {
        column: {
            stacking: 'normal',
            dataLabels: {
                enabled: true,
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                        formatter: function() {
                            if (this.y === 0) {
                                return null;
                            } else {
                                return this.y;
                            }
                        }
            }
        }
    },
    series: [<?php echo $data;?>]
});
		</script>
        
    
       
       
            </div> 



		                      
    </div>

<div class="dr"><span></span></div>
</div>
<!--WorkPlace End-->
</div>