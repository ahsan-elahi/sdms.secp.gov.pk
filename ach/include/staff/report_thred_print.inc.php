<?php error_reporting(0); ?>
<link href="css/stylesheets.css" rel="stylesheet" type="text/css" /> 
<style>
#text th {text-align:center;vertical-align:middle;}
#color{background:#999;color:#FFF;}
</style>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

if($_REQUEST['staffid']) {
$staffid = $_REQUEST['staffid'];
}
?>
<div class="page-header"><h1>Reports<small>Based on Threads</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">        
</div>
</div>
<div class="row-fluid" style="min-height:770px;">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Reports Based on Thread'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
          <table width="100%" cellpadding="0" cellspacing="0" class="table">
           <thead>
  <tr id="text">
 
    <th>Month</th>
    <th>Registred</th>
    <th>Completed Summary</th>
    <th>Marked to Director I</th>
    <th>Marked to Director II</th>
    <!--<th>Proceding Done</th>-->
     <th>Dispatched</th>
     <th>Wating for reply</th>
     <th id="color">Disposal</th>
  </tr>
  </thead>
  <?php $year=date('Y'); ?>
  <tr>
    <td colspan="9">Year : <?php echo $year;  ?></td>
    
  </tr>
  <?php
  $complete_reg=0;
  $complete_summary=0;
  $complete_dir1=0;
  $complete_dir2=0;
  $complete_proced=0;
  $complete_dispach=0; 
  $complete_reply=0;
  $complete_dispose=0;
   for ($i = 1; $i <= date('m'); $i++) { ?>
  <tr>
    <td><?php echo date("M",strtotime('2014-'.$i)); ?></td>
    <td><?php 
	$sql_reg="select count(ticket_id) as total_reg from sdms_ticket where Year(created)='".$year."' AND Month(Created)='".$i."' ";
	$res_reg=db_query($sql_reg);
    $row_reg= db_fetch_array($res_reg);
	echo $row_reg['total_reg'];
	$complete_reg += $row_reg['total_reg'];
	?></td>
    <td><?php 
	$sql_summary="SELECT count(Distinct (ticket_id)) as total_summary from sdms_ticket_thread where thread_type = 'N' AND ticket_id IN (select ticket_id from sdms_ticket where Year(created)='".$year."' AND Month(Created)='".$i."')";
	$res_summary=db_query($sql_summary);
    $row_summary= db_fetch_array($res_summary);
	echo $row_summary['total_summary']; 
	$complete_summary += $row_summary['total_summary'];
	?></td>
    <td><?php 
	$sql_dir1="SELECT count(Distinct (ticket_id)) as total_dirI from sdms_ticket_thread where thread_type = 'N' AND title = 'Complaint Assigned to Director -I' AND Year(created)='".$year."' AND Month(Created)='".$i."' ";
	$res_dir1=db_query($sql_dir1);
    $row_dir1= db_fetch_array($res_dir1);
	echo $row_dir1['total_dirI']; 
	$complete_dir1 += $row_dir1['total_dirI'];
	?></td>
    <td><?php 
	$sql_dir2="SELECT count(Distinct (ticket_id)) as total_dirII from sdms_ticket_thread where thread_type = 'N' AND title = 'Complaint Assigned to Director -II' AND Year(created)='".$year."' AND Month(Created)='".$i."' ";
	$res_dir2=db_query($sql_dir2);
    $row_dir2= db_fetch_array($res_dir2);
	echo $row_dir2['total_dirII']; 
	$complete_dir2 += $row_dir2['total_dirII'];
	?></td>
    <!--<td><?php 
	/*$sql_proced="SELECT count(Distinct (ticket_id)) as total_proceding from sdms_ticket_thread where thread_type = 'R' AND Year(created)='".$year."' AND Month(Created)='".$i."' ";
	$res_proced=db_query($sql_proced);
    $row_proced= db_fetch_array($res_proced);
	echo $row_proced['total_proceding']; 
	$complete_proced += $row_proced['total_proceding']; */
	?></td>-->
    <td><?php 
	$sql_dispach="select count(Distinct(ticket_id)) as total_dispach from sdms_ticket_thread where complaint_status = '3' AND Year(created)='".$year."' AND Month(Created)='".$i."' ";
	$res_dispach=db_query($sql_dispach);
    $row_dispach= db_fetch_array($res_dispach);
	echo $row_dispach['total_dispach'];
	$complete_dispach += $row_dispach['total_dispach']; 
	?></td>
    <td><?php 
	$sql_reply="SELECT count(Distinct (ticket_id)) as total_reply from sdms_ticket_thread where thread_type = 'D' AND Year(created)='".$year."' AND Month(Created)='".$i."' ";
	$res_reply=db_query($sql_reply);
    $row_reply= db_fetch_array($res_reply);
	echo $row_reply['total_reply']; 
	$complete_reply += $row_reply['total_reply']; 
	?></td>
    <td id="color"><?php 
	$sql_dispose="select count(Distinct (ticket_id)) as total_dispose from sdms_ticket_thread where complaint_status = '4' AND Year(created)='".$year."' AND Month(Created)='".$i."' ";
	$res_dispose=db_query($sql_dispose);
    $row_dispose= db_fetch_array($res_dispose);
	echo $row_dispose['total_dispose']; 
	$complete_dispose += $row_dispose['total_dispose']; 
	?></td>
   </tr>
  <?php }?>
  <tr>
  <td>Total</td>
  <td><?php echo $complete_reg; ?></td>
  <td><?php echo $complete_summary; ?></td>
  <td><?php echo $complete_dir1; ?></td>
  <td><?php echo $complete_dir2; ?></td>
  <!--<td><?php //echo $complete_proced; ?></td>-->
  <td><?php echo $complete_dispach; ?></td>
  <td><?php echo $complete_reply; ?></td>
  <td id="color"><?php echo $complete_dispose; ?></td>
  </tr>
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