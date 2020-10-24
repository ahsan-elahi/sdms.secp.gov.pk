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

<!--<script type="text/javascript">
$(document).ready(function() {
$('#report_create').click(function () {
	alert('welcome');
	$.post('report_sdms_print.php', $('form#sdms_report_result').serialize(), function () {
		myWindow=window.open("report_sdms_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
		myWindow.print() ;
		myWindow.close();
	});
	return false;
});
});
</script>-->
<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("report_sdms_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
window.location.reload();
}
</script>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');s
?>
<div class="row-fluid">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1>Search<?php $category=$all_categories->load(); ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
        <form action="report_sdms_summary.php" method="GET">
         <table width="100%" cellpadding="0" cellspacing="0" class="table" style="line-height:35px;">
            <tr>
                <td><input type="text" name="AssingFDate" placeholder="AssingFDate" id="Datepicker" value="<?php if (isset($_GET['AssingFDate'])){ echo $_GET['AssingFDate']; }?>"/></td>
                <td><select name="district">
                <option value="" selected="selected">All District</option>
                <?php 
                $districts=$all_districts->getDistricts();
                foreach($districts as $key=>$row){
                 ?>
                <option value="<?php echo $key; ?>" <?php if ($_GET['district'] == $key) { ?> selected="selected" <?php } ?>><?php echo $row; ?></option>
                <?php } ?>
                </select></td>
                <td><select name="Assign_from">
                <option value="" selected="selected">Assing From All</option>
                  <?php
                  //$staff=$all_staff->getStaff();
				  $sql_staff_username="select * from sdms_staff order by staff_id ASC";
				  $res_staff_username=db_query($sql_staff_username);
                while($row_staff_username = db_fetch_array($res_staff_username)){
                 ?>
                <option value="<?php  echo $row_staff_username['username']; ?>" <?php if ($_GET['Assign_from'] == $row_staff_username['username']) { ?> selected="selected" <?php } ?>><?php echo $row_staff_username['firstname']." ".$row_staff_username['lastname']; ?></option>
                <?php } ?>
               
                </select></td>
                <td><select name="status">
            <option value="" selected="selected">All Status</option>
            
              <option value="open" <?php if ($_GET['status'] == 'open') { ?> selected="selected" <?php } ?> >open</option>
            <option value="closed" <?php if ($_GET['status'] == 'closed') { ?> selected="selected" <?php } ?> >closed</option>
            </select></td>
              </tr>
            <tr>
                <td><input type="text" name="AssingTDate" placeholder="AssingTDate" id="Datepicker1" value="<?php if (isset($_GET['AssingTDate'])){ echo $_GET['AssingTDate']; }?>"/></td>
                <td><select name="category">
                    <option value="" selected="selected">All Categories</option>
                     <?php
					 $sql_cat="Select * from sdms_help_topic where topic_pid='0'";
					 $res_cat=db_query($sql_cat);
				
                    //$category=$all_categories->getsubTopics();
                    while($row_cat= db_fetch_array($res_cat)){
                     ?>
                      <option value="<?php echo $row_cat['topic_id']; ?>" <?php if ($_GET['category'] == $row_cat['topic_id']) { ?> selected="selected" <?php } ?>><?php echo $row_cat['topic']; ?></option>
                    <?php } ?>
                    </select></td>
                <td><select name="Assign_to">
                <option value="" selected="selected">Assign to All</option>
                 <?php
                  $staff=$all_staff->getStaff();
                foreach($staff as $key_staff=>$row_staff){
                 ?>
                <option value="<?php echo $key_staff; ?>" <?php echo $key_category; ?>" <?php if ($_GET['Assign_to'] == $key_staff) { ?> selected="selected" <?php } ?>"><?php echo $row_staff; ?></option>
                <?php } ?>
                </select></td>
                <td><input type="text" name="complaint_no" placeholder="Complaint No" value="<?php if (isset($_GET['complaint_no'])){ echo $_GET['complaint_no']; }?>"/></td>
              </tr>
            <tr>
              <td colspan="6" style="text-align:right">
              <input type="submit" value="Search" name="Search" class="btn" />&nbsp;&nbsp;
              <input type="button" value="Print" onclick="openWin();" class="btn" id="report_create" /></td>
              </tr>
		</table>  
        </form>
        </div>
 </div>                      
</div>
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
 
 
 if(isset($_GET['Search']))
{	
$sql_report_summary="select a.*,b.* from sdms_ticket_event a join sdms_ticket b on a.ticket_id=b.ticket_id WHERE a.ticket_id!='' ";
if($_GET['AssingFDate']!="" && $_GET['AssingTDate']!="")
$sql_report_summary .=" AND date(a.timestamp) >='".date('Y-m-d',strtotime($_GET['AssingFDate']))."' AND date(a.timestamp) <='".date('Y-m-d',strtotime($_GET['AssingTDate']))."'";
if($_GET['Assign_from']!="" && $_GET['Assign_to']!="")
$sql_report_summary .=" AND a.staff ='".$_GET['Assign_from']."' AND b.staff_id='".$_GET['Assign_to']."'";
if($_GET['status']!="")
$sql_report_summary .=" AND b.status ='".$_GET['status']."'";
if($_GET['complaint_no']!="")
$sql_report_summary .=" AND b.ticketID ='".$_GET['complaint_no']."'";
if($_GET['district']!="")
$sql_report_summary .=" AND b.district ='".$_GET['district']."'";
if($_GET['category']!="")
$sql_report_summary .=" AND b.topic_id IN (select topic_id from sdms_help_topic where topic_pid='".$_GET['category']."')";
$sql_report_summary .=' group by a.ticket_id order by a.timestamp desc';	
$_SESSION['query']='search';
}
else{
$sql_report_summary="select * from sdms_ticket Order By ticket_id Desc ";
$_SESSION['query']='';
}
//echo $sql_report_summary;
$page_result=mysql_query($sql_report_summary);
$total=mysql_num_rows($page_result);
  if($_GET['limit'])
    $qstr.='&limit='.urlencode($_GET['limit']);
//pagenate
$pagelimit=($_GET['limit'] && is_numeric($_GET['limit']))?$_GET['limit']:PAGE_LIMIT;
$page=($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;
$pageNav=new Pagenate($total,$page,$pagelimit);
$pageNav->setURL('report_sdms_summary.php',$qstr.'&sort='.urlencode($_REQUEST['sort']).'&order='.urlencode($_REQUEST3['order']).'&'.$_SERVER['QUERY_STRING']);

$sql_report_summary .=" LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
$_SESSION['print']=$sql_report_summary;

$res_report_summary=db_query($sql_report_summary);
$num=db_num_rows($res_report_summary);
$showing=db_num_rows($res_report_summary)?$pageNav->showing():"";
 while ($row_summary = db_fetch_array($res_report_summary)) { 
 if(isset($_GET['Search'])){
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
 <?php }else{ ?>
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
  $i++;
 }
  ?>
  <?php if($num>0){  ?>      
        <tfoot>
        <tr>
        <td colspan="4"><?php echo '<div align="left" style="width:40%;float:left;">'.$showing .'&nbsp;'.'&nbsp;'.'&nbsp;'.'</div>';?></td>
         <td colspan="5" align="right"><?php echo '<div class="dataTables_paginate paging_full_numbers">Page:'.$pageNav->getPageLinks().'</div>'; ?></td>
        </tr>
        </tfoot>                            
<?php }?>
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


