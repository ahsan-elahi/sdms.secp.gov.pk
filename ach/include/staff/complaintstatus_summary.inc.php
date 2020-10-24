<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
?>
<div class="page-header"><h1>Compalint Status  <small> Summary</small></h1></div>
<div class="row-fluid">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Compalint Status Summary'; ?></h1>                               
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
    <td width="148">Types</td>
    <?php
    $query_dept="SELECT * FROM sdms_department dept WHERE dept.dept_p_id=0 order by dept.dept_id desc";
  $result_dept = db_query($query_dept);
  while ($row_dept = db_fetch_array($result_dept)) { ?>
    <td colspan="2"><?php echo $row_dept['dept_name']; ?></td>
    <?php }?>
   <td>Grand Total</td> 
  </tr>
  
  

  <tr id="headerStyle">
    <td></td>
    <td colspan="">Resolved </td>
    <td colspan="">Pending </td>
    <td colspan="">Resolved </td>
    <td colspan="">Pending </td>
    <td colspan=""></td>  
  </tr>
  <?php
  $grand_total=0;
    $query_topics="SELECT * FROM sdms_help_topic topic WHERE topic.topic_id!=0";
  $result_topics = db_query($query_topics);
  while ($row_topics = db_fetch_array($result_topics)) {
	  $grand_total=0;
	  $nums_resolved=0;
  $nums_pending=0;
   ?>
  <tr>
  <th>
                <a>
                <span style="float:left; width:350px;margin-left:30px;"><?php if($row_topics['topic']!="") echo $row_topics['topic']; else echo "No Topic";?></span></a></th>
  <?php 
   $query_dept="SELECT * FROM sdms_department dept WHERE dept.dept_p_id=0 order by dept.dept_id desc";
  $result_dept = db_query($query_dept);
  while ($row_dept = db_fetch_array($result_dept)) {   
  $query_sub_dept="SELECT * FROM sdms_department dept WHERE dept.dept_p_id='".$row_dept['dept_id']."' order by dept.dept_id asc";
  $result_sub_dept = db_query($query_sub_dept);
  while ($row_sub_dept = db_fetch_array($result_sub_dept)) {
	$sql_total="Select * from sdms_ticket where dept_id= '".$row_sub_dept['dept_id']."' AND topic_id= '".$row_topics['topic_id']."' AND status='closed' ";
	$result_total = db_query($sql_total);
	$nums_resolved += db_num_rows($result_total);
	$sql_total="Select * from sdms_ticket where dept_id= '".$row_sub_dept['dept_id']."' AND topic_id= '".$row_topics['topic_id']."' AND status='open' ";
	$result_total = db_query($sql_total);
	$nums_pending += db_num_rows($result_total);
	
  } 
  ?>
     <td style="text-align:right;"><?php echo $nums_resolved; ?></td>
    <td style="text-align:right;"><?php echo $nums_pending; ?></td>
<?php 
$grand_total=$nums_resolved+$nums_pending;

}?>    
 
	
    <td style="text-align:right;"><?php echo $grand_total; ?></td>

  </tr>
  <?php }  ?>
</table>
	  </div>
 </div>                      
</div>
 <div class="row-fluid">
 
          <div class="span6">
            <div class="head clearfix">                            
                <h1>Complaint Status Summary </h1>
                <div align="right">
                
                </div>
            </div>
            <div class="block">
            <div id="chart-31" style="height: 300px;">
<?php 

$qselect_topic='SELECT count(ticket_id) as ticketno ,t.topic_id, d.topic,d.topic_img ';
$qfrom_topic='FROM '.TICKET_TABLE.' t, sdms_help_topic d ';
$qwhere_topic=' WHERE t.topic_id=d.topic_id group by t.topic_id '; 

$query_topic="$qselect_topic $qfrom_topic $qwhere_topic ORDER BY t.created DESC ";
$result_topic = db_query($query_topic);
$i=0;
?>
<script type="text/javascript">
var data =[];
<?php 
while ($row = db_fetch_array($result_topic)) { ?>
data[<?php echo $i; ?>] = { label: "<?php echo $row['topic']; ?>", data: <?php echo $row['ticketno']; ?> };
<?php  $i++;} ?>

        $.plot($("#chart-31"), data, 
	{
            series: {
                pie: { show: true }
            },
            legend: { show: false }
	});
</script>
                </div>
            </div>                        
        </div>  
                             
<div class="span12">
            <div class="head clearfix">                            
                <h1>Compalint Status Analysis</h1>
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
$query="SELECT * FROM sdms_help_topic topic WHERE topic.topic_id!=0";
$result = db_query($query);
while ($row_topics = db_fetch_array($result)) {
$nums_total=0;
$nums_total_closed=0; 
$nums_total_pending=0; 

$six_open_month .=  '"'.$row_topics['topic'].'",'; 

$sql_total="Select * from sdms_ticket where topic_id= '".$row_topics['topic_id']."'";
$result_total = db_query($sql_total);
$nums_total = db_num_rows($result_total);

$open_month_ticket .=  $nums_total.',';	


$sql_total_closed="Select * from sdms_ticket where status='closed' AND topic_id= '".$row_topics['topic_id']."'";
$result_total_closed = db_query($sql_total_closed);
$nums_total_closed = db_num_rows($result_total_closed);

$close_month_ticket .=  $nums_total_closed.',';


//resolved
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
</div>                        
<div class="dr"><span></span></div>   
</div><!--WorkPlace End-->  
</div>   
