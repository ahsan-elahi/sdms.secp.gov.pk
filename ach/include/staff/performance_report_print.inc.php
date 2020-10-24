<?php error_reporting(0); ?>
<link href="css/stylesheets.css" rel="stylesheet" type="text/css" />
<style>
#bg_color td{background:#CCC;}
#text th {text-align:center;vertical-align:middle;}
#color,#bg_color #color{background:#999;color:#FFF;}
</style>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

if($_REQUEST['staffid']) {
$staffid = $_REQUEST['staffid'];
}
?>

<div class="page-header"><h1>Performance <small> Report</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">    
</div>
</div>
<div class="row-fluid" style="min-height:770px;">
   <div class="span12" style="width:2085px;">                   
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Performance Report Listing'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
<table width="100%" cellpadding="0" cellspacing="0" class="table">
<thead>
  <tr id="text">
    <th rowspan="2">User Name</th>
    <th rowspan="2">Designation</th>
    <?php 
    $year=2014;
    for ($i = 1; $i <= date('m'); $i++) { 
	?>
    <th colspan="3"><?php echo date("M",strtotime('2014-'.$i)); ?></th>
    <?php }?>
	<th colspan="3" id="color">Total</th>
    </tr>


  <tr id="bg_color">
     <td>Assign</td>
    <td>Pro</td>
    <td>Pend</td>
     <td>Assign</td>
    <td>Pro</td>
    <td>Pend</td>
     <td>Assign</td>
    <td>Pro</td>
    <td>Pend</td>
     <td>Assign</td>
    <td>Pro</td>
    <td>Pend</td>
     <td>Assign</td>
    <td>Pro</td>
    <td>Pend</td>
     <td>Assign</td>
    <td>Pro</td>
    <td>Pend</td>
    <td id="color">Assigned</td>
    <td id="color">Process</td>
    <td id="color">Pending</td>
  </tr>
  </thead>
	<?php 
	$grand_total_assign=0;
	$grand_total_pro=0;
	$grand_total_pend=0;
	$arr_total=array();
	 $year=2014;
    $sql_staff="select * from sdms_staff where staff_id != '' ";
    $res_staff=db_query($sql_staff);
    while($row_staff= db_fetch_array($res_staff)){
		$a=0;
		$complete_assign=0;
		$complete_process=0;
		$complete_pending=0;
    ?>
      <tr>
        <td><?php echo $row_staff['firstname'].' '.$row_staff['lastname']; ?></td>
        <?php 
		$sql_group="select * from  sdms_groups where group_id = '". $row_staff['group_id']."' ";
    	$res_group=db_query($sql_group);
    	$row_group= db_fetch_array($res_group);
		?>
        <td><?php echo $row_group['group_name'] ?></td>
        <?php 
    for ($i = 1; $i <= date('m'); $i++) { ?>
        <?php
		$sql_assign="SELECT count(Distinct (ticket_id)) as total_asssign from sdms_ticket_event where state = 'assigned' AND Year(timestamp)='".$year."' AND staff_id='".$row_staff['staff_id']."' AND Month(timestamp)='".$i."' ";
		
   //echo $sql_assign="SELECT count(Distinct (ticket_id)) as total_asssign from sdms_ticket_thread where complaint_status = '1' AND Year(created)='".$year."' AND staff_id='".$row_staff['staff_id']."' AND Month(created)='".$i."' ";
	$res_assign=db_query($sql_assign);
    $row_assign= db_fetch_array($res_assign);
	$complete_assign += $row_assign['total_asssign'];
    ?>
    <td><?php echo $row_assign['total_asssign'];$arr_total[$a++] +=$row_assign['total_asssign']; ?></td>
    
	<?php
    $sql_process="SELECT count(Distinct (ticket_id)) as total_process from sdms_ticket_event where state = 'assigned' AND Year(timestamp)='".$year."' AND staff='".$row_staff['username']."' AND Month(timestamp)='".$i."' ";
	 //$sql_process="SELECT count(Distinct (ticket_id)) as total_process from sdms_ticket_thread where complaint_status = '2' AND Year(created)='".$year."' AND staff_id='".$row_staff['staff_id']."' AND Month(created)='".$i."' ";
	$res_process=db_query($sql_process);
    $row_process= db_fetch_array($res_process);
	$complete_process += $row_process['total_process'];
    ?>
    <td><?php echo $row_process['total_process'];$arr_total[$a++] +=$row_process['total_process']; ?></td>
    
    <?php	$row_pending=$row_assign['total_asssign'] - $row_process['total_process']; 
	if($row_pending<0){$row_pending=$row_pending*-1;}
	 $complete_pending += $row_pending; ?>
    <td><?php echo $row_pending;$arr_total[$a++] +=$row_pending; ?></td>
        
  	<?php }?>
   
   		<td id="color"><?php echo $complete_assign;$grand_total_assign +=$complete_assign; ?></td>
        <td id="color"><?php echo $complete_process;$grand_total_pro +=$complete_process; ?></td>
        <td id="color"><?php echo $complete_pending;$grand_total_pend +=$complete_pending; ?></td>
     <?php }?>   
  <thead>
  <tr>
    <th>Total</th>
    <th>&nbsp;</th>
    <?php foreach($arr_total as $grand_total){ ?>
    <th><?php echo $grand_total; ?></th>
    <?php } ?>
    <!--<th>1000</th>
    <th>1200</th>
    <th>1400</th>
    <th>1600</th>
    <th>1600</th>
    <th>60</th>
    <th>1000</th>
    <th>1200</th>
    <th>1400</th>
    <th>1600</th>
    <th>1600</th>
    <th>60</th>
    <th>1000</th>
    <th>1200</th>-->
    <th id="color"><?php echo $grand_total_assign;  ?></th>
    <th id="color"><?php echo $grand_total_pro;  ?></th>
    <th id="color"><?php echo $grand_total_pend;  ?></th>
  </tr>
  </thead>
</table>

  
        </div>
 </div>  
 <div class="dr"><span></span></div>                      
</div>                    
 
</div><!--WorkPlace End-->  
</div>   
<script>
window.print() ;
window.close();
</script>