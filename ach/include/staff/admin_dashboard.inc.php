<style>
#tSortable_paginate{
	display:none;
	}
#tSortable_filter{
display:none;
}	
#tSortable_length{
	display:none;}
	
#tSortable_7_paginate{
	display:none;
	}
#tSortable_7_filter{
display:none;
}	
#tSortable_7_length{
	display:none;}	
</style>
<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="../excanvas.js"></script><![endif]-->
<script language="javascript">
var d1 = [];
var d2 = [];
var d3 = [];
var data =[];
</script>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
?> 
<div class="row-fluid">
<?php
if($thisstaff->isFocalPerson() == '1' || $thisstaff->getGroupId()=='8')
{
	$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
}
elseif(!$thisstaff->isAdmin() &&  $thisstaff->onChairman() == '1' )
{
	$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	//$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
}
$status_array = array(); 
$sql_status="SELECT * FROM `sdms_status` WHERE p_id='0'";
$res_status=mysql_query($sql_status);
while($row_status=mysql_fetch_array($res_status)){
	
$t_1to15days = 0;
$t_16to30days = 0;
$t_31to45days = 0;
$t_45daysplus = 0;
	
$num_status_comp = 0;
$sub_status .="{
            name: '".$row_status['status_title']."',
            id: '".$row_status['status_title']."',
            data: [ ";
	
$sql_sub_status="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
$res_sub_status=mysql_query($sql_sub_status);
while($row_sub_status=mysql_fetch_array($res_sub_status)){
	
$today_date =  date('Y-m-d'); 
$sql_status_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' ".$dept_add."";
$res_status_comp = mysql_query($sql_status_comp);
$num_status_comp += mysql_num_rows($res_status_comp);
$sub_status .="['".$row_sub_status['status_title']."', ".$num_status_comp."],";

//
//echo "first loop 1 to 15 Days";
for($i=1;$i<16;$i++)
{
$sql_1to15days = "SELECT ticket_id FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_1to15days = mysql_query($sql_1to15days);;
$t_1to15days += mysql_num_rows($res_1to15days);

$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}
//echo "second loop 15+ Days";
for($i=1;$i<16;$i++)
{
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days += mysql_num_rows($res_16to30days);
//echo $today_date.'<br>';
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));

}
//echo "second loop 45+ Days";
for($i=1;$i<16;$i++)
{
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days += mysql_num_rows($res_31to45days);
//echo $today_date.'<br>';
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));

}
$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) <= '".$today_date."' ".$dept_add."";
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus += mysql_num_rows($res_45daysplus);
//echo $today_date.'<br>';
}
	?>
<div class="span2" style="width:17.90%">
    <div class="wBlock <?php echo $row_status['color'];?> clearfix">                        
        <div class="dSpace" style="min-height:85px;min-width:62px;width:28%;">
            <h3><?php echo $row_status['status_title'];?></h3>
            <span class="number"><?php echo $num_status_comp; ?></span>        
            <span class="mChartBar" sparkType="bar" sparkBarColor="white">
            </span>
        </div>
        <div class="rSpace" style="width:55%;">
            <span>(<?php echo $t_1to15days; ?>) <b>1 to 15 Days</b></span>
            <span>(<?php echo $t_16to30days; ?>) <b>15+ Days</b></span>
            <span>(<?php echo $t_31to45days; ?>) <b>30+ Days</b></span>
            <span>(<?php echo $t_45daysplus; ?>) <b>45+ Day</b></span>
            
        </div>                          
    </div>   
</div>
<?php 

$primary_status .= "{ name: '".$row_status['status_title']."', y: ".$num_status_comp.",
            drilldown: '".$row_status['status_title']."'},";
$sub_status .="]
},
";				
}?>
</div>          
<!--Complaints Status , Complaint Categories , Geophrical Summary, Line chart , Bar chart -->
<div class="dr"><span></span></div> 
    <div class="row-fluid">
        <div class="span4" >
        <div class="head clearfix">
        <div class="isw-archive"></div>
        <h1>All Complaints</h1>
        
        </div>
        <div class="block-fluid accordion" style="min-height:290px;">
        <?php 
        //===============================Complaints Status=============================
        $month=date("m", mktime(0,0,0, date('m')));  
        ?>
        <h3><?php echo date("F Y", mktime(0,0,0, date('m')));?></h3>
        <div>
        <table cellpadding="0" cellspacing="0" width="100%" class="sOrders">
        <thead>
        <tr>
        <th width="60">Date</th><th>Complaint ID</th><th width="60">Status</th>
        </tr>
        </thead>
        <tbody>
        <?php 
        //===================================Second Month========================================
        $sql_three = 'SELECT * FROM '.TICKET_TABLE.' '.'WHERE isquery = "0" AND  month(created)='.$month.' AND  status = "open"  '.$dept_add.'';
	    $sql_three .=' order by ticket_id desc LIMIT 5 ';
	    $sql_three_query=db_query($sql_three);
        while($row_sql_three = db_fetch_array($sql_three_query))
        {?>                   
        <tr>
        <td><span class="date"><?php echo date('M d',strtotime($row_sql_three['created'])) ;?> </span>
        <span class="time"><?php echo date('h:i A',strtotime($row_sql_three['created'])) ;?></span></td>
        <td><?php echo $row_sql_three['ticketID']; ?></td>
        <?php $sql_status="SELECT * FROM `sdms_status` WHERE status_id='".$row_sql_three['complaint_status']."'";
        $res_status=mysql_query($sql_status);
        $row_status=mysql_fetch_array($res_status);?>
        <td><span class="price"><?php echo $row_status['status_title']; ?></span></td>
        </tr>                                                  
        <?php     
        }	?>
        </tbody>
        <tfoot>
        <tr>
        <td colspan="3" align="right"><a href="tickets.php"><button class="btn btn-small">More...</button></a></td>
        </tr>
        </tfoot>
        </table>
        </div>  
        </div>
        </div>
        <div class="span4" >
        <div class="head clearfix">
        <div class="isw-archive"></div>
        <h1>Recently Lodged/Unaccepted</h1>
        </div>
        <div class="block-fluid accordion" style="min-height:290px;">
        <?php 
        //===============================Complaints Status=============================
        $month=date("m", mktime(0,0,0, date('m')));  
        ?>
        <h3><?php echo date("F Y", mktime(0,0,0, date('m')));?></h3>
        <div>
        <table cellpadding="0" cellspacing="0" width="100%" class="sOrders">
        <thead>
        <tr>
        <th width="60">Date</th><th>Complaint ID</th><th width="60">Status</th>
        </tr>
        </thead>
        <tbody>
        <?php 
        //===================================Second Month========================================
        $sql_three = 'SELECT * FROM '.TICKET_TABLE.' '.'WHERE isquery = "0" AND  month(created)='.$month.' AND  status = "open"  AND isaccepted = "0" '.$dept_add.'';
        $sql_three .=' order by ticket_id desc LIMIT 5 '; 
        $sql_three_query=db_query($sql_three);
        while($row_sql_three = db_fetch_array($sql_three_query))
        {?>                   
        <tr>
        <td><span class="date"><?php echo date('M d',strtotime($row_sql_three['created'])) ;?> </span>
        <span class="time"><?php echo date('h:i A',strtotime($row_sql_three['created'])) ;?></span></td>
        <td><?php echo $row_sql_three['ticketID']; ?></td>
        <?php $sql_status="SELECT * FROM `sdms_status` WHERE status_id='".$row_sql_three['complaint_status']."'";
        $res_status=mysql_query($sql_status);
        $row_status=mysql_fetch_array($res_status);?>
        <td><span class="price"><?php echo $row_status['status_title']; ?></span></td>
        </tr>                                                  
        <?php     
        }	?>
        </tbody>
        <tfoot>
        <tr>
        <td colspan="3" align="right"><a href="tickets.php?status=lodged"><button class="btn btn-small">More...</button></a></td>
        </tr>
        </tfoot>
        </table>
        </div>    
        <?php //  }     ?>
        </div>
        </div>           
        <div class="span4" >
        <div class="head clearfix">
        <div class="isw-archive"></div>
        <h1>Accepted</h1>
        
        </div>
        <div class="block-fluid accordion" style="min-height:290px;">
        <?php 
        //===============================Complaints Status=============================
        $month=date("m", mktime(0,0,0, date('m')));  
        ?>
        <h3><?php echo date("F Y", mktime(0,0,0, date('m')));?></h3>
        <div>
        <table cellpadding="0" cellspacing="0" width="100%" class="sOrders">
        <thead>
        <tr>
        <th width="60">Date</th><th>Complaint ID</th><th width="60">Status</th>
        </tr>
        </thead>
        <tbody>
        <?php 
        //===================================Second Month========================================
        $sql_three = 'SELECT * FROM '.TICKET_TABLE.' '.'WHERE isquery = "0" AND  month(created)='.$month.' AND  status = "open"   AND isaccepted = "1" '.$dept_add.'';
        $sql_three .=' order by ticket_id desc LIMIT 5 '; 
        $sql_three_query=db_query($sql_three);
        while($row_sql_three = db_fetch_array($sql_three_query))
        {?>                   
        <tr>
        <td><span class="date"><?php echo date('M d',strtotime($row_sql_three['created'])) ;?> </span>
        <span class="time"><?php echo date('h:i A',strtotime($row_sql_three['created'])) ;?></span></td>
        <td><?php echo $row_sql_three['ticketID']; ?></td>
        <?php $sql_status="SELECT * FROM `sdms_status` WHERE status_id='".$row_sql_three['complaint_status']."'";
        $res_status=mysql_query($sql_status);
        $row_status=mysql_fetch_array($res_status);?>
        <td><span class="price"><?php echo $row_status['status_title']; ?></span></td>
        </tr>                                                  
        <?php     
        }	?>
        </tbody>
        <tfoot>
        <tr>
        <td colspan="3" align="right"><a href="tickets.php"><button class="btn btn-small">More...</button></a></td>
        </tr>
        </tfoot>
        </table>
        </div>    
        <?php //  }     ?>
        </div>
        </div>
    </div>
<div class="dr"><span></span></div>
<!--Line Chart/Bar Chart-->

<?php 
//===============================Line chart | Bar chart by Status (OPEN,OVERDUE,CLOSE)============================= 
?>
    <div class="row-fluid">
            <div class="span12">
            <div class="head clearfix">
            <div class="isw-right_circle"></div>
            <h1>Pie charts</h1>
            </div>
            <div class="block">
            <div id="container" style="min-width: 310px; max-width: 600px; height: 400px; margin: 0 auto"></div>
            </div>
            </div>
            <script type="text/javascript">
            // Create the chart
            Highcharts.chart('container', {
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
</div><!--WorkPlace End-->  
</div> 
   