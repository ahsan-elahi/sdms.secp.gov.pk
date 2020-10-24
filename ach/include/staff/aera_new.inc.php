<style type="text/css">
.DETAILS {
    display: none;
}
</style>
<script type="text/javascript" language="javascript">
function show_item(id)
{
    if ($('#show_temp_item_'+id).prop("click")) {
		if ($('#item_temp_section_'+id).css("display") == 'none') {
         $('#item_temp_section_'+id).show();
    	}
    else {
		$('#item_temp_section_'+id).hide();
    }
	}
}
</script>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
  $i=1;
  ?>
<div class="page-header"><h1>Overall Complaints  <small> Summary</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">
    <p align="right" style="float:right;">
     <a id="ticket-print" class="action-button" href="" onclick="openWin();">
     <button class="btn" type="button"><i class="icon-print"></i> Print</button></a>                              
    </p>             
</div>
</div>
<div class="row-fluid">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Overall Complaints Summary'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
            <table cellpadding="0" cellspacing="0" width="100%" class="table">	
<?php 				
$qselect='SELECT count(ticket_id) as ticketno ,t.topic_id, d.topic ';
$qfrom='FROM '.TICKET_TABLE.' t, sdms_help_topic d ';
$qwhere=' WHERE t.topic_id=d.topic_id group by t.topic_id ';


$total=db_count("SELECT count(*) $qfrom $qwhere");
$pagelimit=30;

//pagenate
$pageNav=new Pagenate($total,$page,$pagelimit);
$pageNav->setURL('admin.php',$qstr);
$query="$qselect $qfrom $qwhere ORDER BY t.created DESC LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
//echo $query;
$result = db_query($query);
$showing=db_num_rows($result)?$pageNav->showing():"";
?>
       <table cellpadding="0" cellspacing="0" width="100%" class="table">	
  <tr id="headerStyle">
    <td width="148">Agencies/FR/Departments</td>
    <td colspan="2">Resolved Complaints</td>
    <td colspan="2">Pending Complaints</td>
    <td colspan="2">Total Complaints</td>
  </tr>
  
  <tr id="headerStyle">
    <td width="148"><b style="font:bold;">Grand Total</b></td>
    <?php
	$sql_total_closed="Select * from sdms_ticket where status='closed'";
	$result_total_closed = db_query($sql_total_closed);
	$nums_total_closed = db_num_rows($result_total_closed);
	 ?>
    <td colspan="2" style="text-align:right;"><b style="font:bold;"><a href="tickets.php?a=search&status=closed"><?php echo $nums_total_closed;?></a></b></td>
    
    
    <?php 
	$sql_total_open="Select * from sdms_ticket where status='open'";
	$result_total_open = db_query($sql_total_open);
	$nums_total_open = db_num_rows($result_total_open);
	?>
    <td colspan="2" style="text-align:right;"><b style="font:bold;"><a href="tickets.php?a=search&status=open"><?php echo $nums_total_open;?></a></b></td>
   	
	<?php 
	$sql_total="Select * from sdms_ticket";
	$result_total = db_query($sql_total);
	$nums_total = db_num_rows($result_total);
	?>
     
    <td colspan="2" style="text-align:right;"><b style="font:bold;"><a href="tickets.php"><?php echo $nums_total;?></a></b></td>

</tr>    
<?php
    $query="SELECT * FROM sdms_department dept WHERE dept.dept_p_id=0 order by dept.dept_id desc";
    $result = db_query($query);
    while ($row = db_fetch_array($result)) {
    $nums_total=0;
    $nums_total_closed=0;
   ?>
  <tr id="headerStyle">
        <td rowspan="2">
        <span style="float:left;width:350px;margin-left:30px;font-size:20px;margin-top:20px;">
        <?php if($row['dept_name']!="") echo $row['dept_name']; else echo "No Dept";?>
        <img src='img/lens_icon.png' onclick="show_item(<?php echo $row['dept_id']; ?>)" id='show_temp_item_<?php echo $row['dept_id']; ?>' />
        </span>
        </td>
        
        <td colspan="2">Resolved Complaint</td>
        <td colspan="2">Pending Complaint</td>
        <td colspan="2">Total Complaint</td>
    </tr>
    <tr></tr>
    <tr class="DETAILS"  id="item_temp_section_<?php echo $row['dept_id']; ?>">
        <td colspan="10">
            <table style="width:100%" style="border-right:1px solid;">
                <tr>
                <td style="width:371px;border-left:1px #CCCCCC solid;" colspan="1"></td>
                <td style="width:200px;" colspan="2"></td>
                <td style="width:180px;"></td>
                <td colspan="2" style="width:148px;border-right:1px #CCCCCC solid;"></td>
                </tr>
              <?php
			
			  $subtotalin10days=0;
			  $subtotalin20days=0;
                $query_sub_dept="SELECT * FROM sdms_department dept WHERE dept.dept_p_id='".$row['dept_id']."' order by dept.dept_id asc";
				
              $result_sub_dept = db_query($query_sub_dept);
              while ($row_sub_dept = db_fetch_array($result_sub_dept)) {
               ?>
              <tr>
                <th colspan="1" style="border-left:1px #CCCCCC solid;bottom-left:1px #CCCCCC solid;"><a><span style="float:left; "><?php if($row_sub_dept['dept_name']!="") echo $row_sub_dept['dept_name']; else echo "No Dept";?></span></a></th>
                <?php 
                $sql_total="Select * from sdms_ticket where dept_id='".$row_sub_dept['dept_id']."'";
                $result_total = db_query($sql_total);
                $nums_total = db_num_rows($result_total);
                $sql_total_open="Select * from sdms_ticket where status='open' AND  dept_id='".$row_sub_dept['dept_id']."'";
                $result_total_open = db_query($sql_total_open);
                $nums_total_open = db_num_rows($result_total_open);
                ?>
                <?php 
                $totalin10days=0;
                $totalin20days=0;
                $sql_1to10days="Select * from sdms_ticket where status='closed' AND  dept_id='".$row_sub_dept['dept_id']."'";
                $result_1to10days = db_query($sql_1to10days);
                while($row_1to10days = db_fetch_array($result_1to10days)){
                $start_time=$row_1to10days['created'];
                $end_time=$row_1to10days['closed'];
                if($end_time!=''){
                $diff = abs(strtotime($end_time) - strtotime($start_time));
                $years   = floor($diff / (365*60*60*24)); 				
                $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
                $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
                $minuts  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
                $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60));
                //$totalin10days = $years.'Y-'.$months.'M-'.$days.'D '.$hours.'H:'.$minuts.'M:'.$seconds.'S';
                if($days<10){
                $totalin10days++;
                }else{
                $totalin20days++;
                
                }
                }
                }
                ?>
                <td colspan="2" style="text-align:right;border-bottom:1px #CCCCCC solid;"><a href="tickets.php?a=search&status=closed&deptId=<?php echo $row_sub_dept['dept_id']; ?>"><?php echo $totalin10days+$totalin20days;?></td>
                <?php /*?><td style="text-align:right;border-bottom:1px #CCCCCC solid;"><?php echo $totalin20days;?></td><?php */?>
                
                <td  style="text-align:right;border-bottom:1px #CCCCCC solid;"><a href="tickets.php?a=search&status=open&deptId=<?php echo $row_sub_dept['dept_id']; ?>"><?php echo  $nums_total_open;?></td>
                
                <td colspan="2" style="text-align:right;border-right:1px #CCCCCC solid;border-bottom:1px #CCCCCC solid;">
				<a href="tickets.php?a=search&deptId=<?php echo $row_sub_dept['dept_id']; ?>"><?php echo $nums_total; ?></a></td>
              </tr>
             <?php 
			 $subtotalin10days.$i +=$totalin10days;
			 $subtotalin20days.$i +=$totalin20days;
			 $i++; 
			 }  ?>
           </table>
      </td>
	</tr>
  <tr id="headerStyle">
    <td width="148"><b style="font:bold;">Grand Total</b></td>
	<?php 
    $query_sub_dept="SELECT * FROM sdms_department dept WHERE dept.dept_p_id='".$row['dept_id']."' order by dept.dept_id asc";
    $result_sub_dept = db_query($query_sub_dept);
    while ($row_sub_dept = db_fetch_array($result_sub_dept)) {
    
    $sql_total_closed="Select * from sdms_ticket where status='closed' AND dept_id ='".$row_sub_dept['dept_id']."'";
    $result_total_closed = db_query($sql_total_closed);
    $nums_total_closed += db_num_rows($result_total_closed);
    }
    ?>
    <td colspan="2" style="text-align:right;"><b style="font:bold;"><a onclick="show_item(<?php echo $row['dept_id']; ?>)" id='show_temp_item_<?php echo $row['dept_id']; ?>'><?php echo $nums_total_closed; ?></b></td>
    
	<?php 
    $nums_total=0;
    $query_sub_dept="SELECT * FROM sdms_department dept WHERE dept.dept_p_id='".$row['dept_id']."' order by dept.dept_id asc";
    $result_sub_dept = db_query($query_sub_dept);
    while ($row_sub_dept = db_fetch_array($result_sub_dept)) {
    $sql_total="Select * from sdms_ticket where dept_id= '".$row_sub_dept['dept_id']."'";
    $result_total = db_query($sql_total);
    $nums_total += db_num_rows($result_total);
    } 
    ?>
    <td colspan="2" style="text-align:right;"><b style="font:bold;"><a onclick="show_item(<?php echo $row['dept_id']; ?>)" id='show_temp_item_<?php echo $row['dept_id']; ?>'><?php echo $nums_total-$nums_total_closed; ?></a></b></td>
    
    <td colspan="2" style="text-align:right;"><b style="font:bold;"><a onclick="show_item(<?php echo $row['dept_id']; ?>)" id='show_temp_item_<?php echo $row['dept_id']; ?>'><?php echo $nums_total; ?></b></a></td>
  </tr>

<?php }  ?>
</table>
      </div>
 </div>
 <div class="row-fluid">
 	<div class="span6">
            <div class="head clearfix">                            
                <h1>Compalint Status Summary</h1>
                <div align="right">
                <table style="margin-top:10px;right:5px;font-size: 11px; color:#333">
                    <tbody>
                        <tr>
                        
                            <td class="legendLabel"><img src="img/open.png"  />
                            <span style="color:#FFF;">Total</span>
                            </td>
                            <td></td>
                             
                            
                            <td class="legendLabel"><img src="img/resolved.png"  />
                            <span style="color:#FFF;">Resolved</span>
                            </td>
                            <td></td>
                            
                            <td class="legendLabel"><img src="img/close.png"  />
                            <span style="color:#FFF;">Pending</span>
                            </td>
                           <td></td>
                            
                        </tr>
                    </tbody>
                </table>
                </div>
                </div>
<?php
    $query="SELECT * FROM sdms_department dept WHERE dept.dept_p_id=0 order by dept.dept_id desc";
  $result = db_query($query);
  while ($row = db_fetch_array($result)) {
  $nums_total=0;
  $nums_total_closed=0; 
  $nums_total_pending=0; 
  
  $six_open_month .=  '"'.$row['dept_name'].'",'; 
  
  $query_sub_dept="SELECT * FROM sdms_department dept WHERE dept.dept_p_id='".$row['dept_id']."' order by dept.dept_id asc";
  $result_sub_dept = db_query($query_sub_dept);
  while ($row_sub_dept = db_fetch_array($result_sub_dept)) {
	$sql_total="Select * from sdms_ticket where dept_id= '".$row_sub_dept['dept_id']."'";
	$result_total = db_query($sql_total);
	$nums_total += db_num_rows($result_total);
	
  } 
  $open_month_ticket .=  $nums_total.',';	
 	
$query_sub_dept="SELECT * FROM sdms_department dept WHERE dept.dept_p_id='".$row['dept_id']."' order by dept.dept_id asc";
  $result_sub_dept = db_query($query_sub_dept);
  while ($row_sub_dept = db_fetch_array($result_sub_dept)) {
	
	$sql_total_closed="Select * from sdms_ticket where status='closed' AND dept_id ='".$row_sub_dept['dept_id']."'";
	$result_total_closed = db_query($sql_total_closed);
	$nums_total_closed += db_num_rows($result_total_closed);
  }
$close_month_ticket .=  $nums_total_closed.',';

$nums_total_pending = $nums_total-$nums_total_closed;
$over_month_ticket .=  $nums_total_pending.',';
}
?>
            <div class="block">
                <canvas id="barChart"/>
                <script type="text/javascript"> 
           var bctx = $("#barChart").get(0).getContext("2d");
           $("#barChart").attr('width',$("#barChart").parent('div').width()).attr('height',300);

           
           barChart = new Chart(bctx).Bar({
                
                labels :[<?php echo $six_open_month;?> ],
                series: [{label: 'Beans'},{label: 'Oranges'},{label: 'Crackers'}],
				
                datasets : [
                        {
                                fillColor : "rgba(0,102,204,0.6)",
                                strokeColor : "rgba(220,220,220,1)",
                                data : [<?php echo $open_month_ticket; ?>]
                        },
                        {
                                fillColor : "rgba(0,153,0,0.6)",
                                strokeColor : "rgba(151,187,205,1)",
                                data : [<?php echo $close_month_ticket; ?>]
                        },
                        {
                                fillColor : "rgba(255,0,0,0.6)",
                                strokeColor : "rgba(151,187,205,1)",
                                data : [<?php echo $over_month_ticket; ?>]
                        }
                ]
            });
                </script>
            </div>                        
        </div>
     <div class="span6">
            <div class="head clearfix">                            
                <h1>Complaint Resolution Anaylsis <?php //$i=1;$x=2; 
				//echo $subtotalin10days.$i.'<br>'.$subtotalin20days.$i.'<br>'.$subtotalin10days.$x.'<br>'.$subtotalin20days.$x.'<br>';?></h1>
             </div>   
        <script type="text/javascript">
  window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer",
    {
      title:{
      text: ""
      },

      axisX: {
        valueFormatString: "MMM",
        interval: 1,
        intervalType: "month"
      },

      data: [
      {
	    color: "green",
        type: "stackedBar",
        legendText: "Complaint Resolved 1 - 10 Days",
        showInLegend: "true",
        dataPoints: [
        { label: "Departtment", y: 45},
        { label: "Agency", y: 55}

        ]
      },
        {
		color: "red",
        type: "stackedBar",
        legendText: "Complaint Resolved more then 10 Days",
        showInLegend: "true",
        dataPoints: [
        { label: "Departtment", y: 55 },
        { label: "Agency", y: 45}
        ]
      }

      ]
    });

    chart.render();
  }
  </script>
        
 <script type="text/javascript" src="js/canvasjs.min.js"></script>
       <div id="chartContainer" style="height: 325px; width: 100%;"></div>                   
</div>
</div>                        
<div class="dr"><span></span></div>   
</div><!--WorkPlace End-->  
</div>