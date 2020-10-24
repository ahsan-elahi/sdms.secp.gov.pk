<link href="css/stylesheets.css" rel="stylesheet" type="text/css" />
<style>
#tSortable_paginate{
	display:none;
	}
#tSortable_filter{
display:none;
}	
#tSortable_length{
	display:none;}
	
#tSortable_9_paginate{
	display:none;
	}
#tSortable_9_filter{
display:none;
}	
#tSortable_9_length{
	display:none;}	
</style>
<?php error_reporting(0); ?>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');s
?>
<div class="page-header"><h1>Report Summary  <small> Aging and Its</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">        
</div>
</div>
<div class="row-fluid" style="min-height:550px;">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Complaints Aging and Its Count'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
          <form id="sdms_report_result" name="sdms_report_result" method="post">
          <table width="100%" cellpadding="0" cellspacing="0" class="table" id="tSortable_8">
           <thead>
              <tr>
             
                <th>Complaint No</th>
                <th>Application Name</th>
                <th>Complaint Date</th>
                <th>Status</th>
                <th>Last Asign Date</th>
                <th>Assign To</th>
                <th>Assign By</th>
                <th>Last Proceeding</th>
              </tr>
  		   </thead>
 <?php 
$sql_report_summary=$_SESSION['print'];
$_SESSION['print']='';
$res_report_summary=db_query($sql_report_summary);
 while ($row_summary = db_fetch_array($res_report_summary)) { 
 if($_SESSION['query']=='search'){
 ?>
 <tr>
    <td><?php echo $row_summary['ticketID'];?></td>
    
    <td><?php echo $row_summary['name_title']." ".$row_summary['name'];?></td>
    
    <td><?php echo date("d-M-Y",strtotime($row_summary['created']));?></td>
    
    <td><?php echo $row_summary['status'];?></td>
    <td><?php echo date("d-M-Y", strtotime($row_summary['timestamp'])); ?></td>
    
	<?php 
		$sql_staff="select * from sdms_staff where staff_id = '".$row_summary['staff_id']."'";
		$res_staff=db_query($sql_staff);
		$row_staff= db_fetch_array($res_staff);		
	?>
    <td><?php echo $row_staff['firstname']." ".$row_staff['lastname']; ?></td>
    <?php
	$sql_assignby_name="Select * from sdms_staff where username='".$row_summary['staff']."'";
	$res_assignby_name=db_query($sql_assignby_name);
	$row_assignby_name = db_fetch_array($res_assignby_name);
	
	 ?>
    <td><?php echo $row_assignby_name['firstname']." ".$row_assignby_name['lastname']; ?></td>
    
	<?php 
    $sql_status="select * from sdms_status where status_id = '".$row_summary['complaint_status']."'";
    $res_status=db_query($sql_status);
    $row_status= db_fetch_array($res_status);
    ?>
    <td><?php echo $row_status['status_title'];  ?></td>	
  </tr>
 <?php }else{  ?>
  <tr>
    <td><?php echo $row_summary['ticketID'];?></td>
    
    <td><?php echo $row_summary['name_title']." ".$row_summary['name'];?></td>
    
    <td><?php echo date("d-M-Y",strtotime($row_summary['created']));?></td>
    
    <td><?php echo $row_summary['status'];?></td>
	<?php
    $sql_last_assign="select * from sdms_ticket_event where ticket_id='".$row_summary['ticketID']."' order by timestamp DESC LIMIT 1";
	$res_last_assign=db_query($sql_last_assign);
	$row_last_assign = db_fetch_array($res_last_assign);
	?>
    <td><?php echo date("d-M-Y", strtotime($row_last_assign['timestamp'])); ?></td>
    
	<?php 
		$sql_staff="select * from sdms_staff where staff_id = '".$row_summary['staff_id']."'";
		$res_staff=db_query($sql_staff);
		$row_staff= db_fetch_array($res_staff);		
		
	?>
    <td><?php echo $row_staff['firstname']." ".$row_staff['lastname']; ?></td>
    <?php
	$sql_assignby_name="Select * from sdms_staff where username='".$row_last_assign['staff']."'";
	$res_assignby_name=db_query($sql_assignby_name);
	$row_assignby_name = db_fetch_array($res_assignby_name);
	 ?>
    <td><?php echo $row_assignby_name['firstname']." ".$row_assignby_name['lastname']; ?></td>
    
	<?php 
    $sql_status="select * from sdms_status where status_id = '".$row_summary['complaint_status']."'";
    $res_status=db_query($sql_status);
    $row_status= db_fetch_array($res_status);
    ?>
    <td><?php echo $row_status['status_title'];  ?></td>	
  </tr>
  <?php }
 }
  ?>
</table>
		  </form>
         </div>
 </div>  
 <div class="dr">
 <span></span>
 </div>                    
</div>
</div><!--WorkPlace End-->  
</div>   
<script>
window.print() ;
window.close();
</script>


