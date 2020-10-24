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
myWindow.print() ;
myWindow.close();
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
                  $staff=$all_staff->getStaff();
                foreach($staff as $key_staff=>$row_staff){
                 ?>
                <option value="<?php  echo $key_staff; ?>" <?php if ($_GET['Assign_from'] == $key_staff) { ?> selected="selected" <?php } ?>><?php echo $row_staff; ?></option>
                <?php } ?>
               
                </select></td>
                <td><select name="status">
                <option value="" selected="selected">All Status</option>
                <?php
                $status=$all_status->getStatus();
                foreach($status as $key_status=>$row_status){
                 ?>
                  <option value="<?php echo $key_status; ?>" <?php if ($_GET['status'] == $key_status) { ?> selected="selected" <?php } ?>><?php echo $row_status; ?></option>
                <?php } ?>
                 
                </select></td>
              </tr>
            <tr>
                <td><input type="text" name="AssingTDate" placeholder="AssingTDate" id="Datepicker1" value="<?php if (isset($_GET['AssingTDate'])){ echo $_GET['AssingTDate']; }?>"/></td>
                <td><select name="category">
                    <option value="" selected="selected">All Categories</option>
                     <?php
                    $category=$all_categories->getsubTopics();
                    foreach($category as $key_category=>$row_category){
                     ?>
                      <option value="<?php echo $key_category; ?>" <?php if ($_GET['category'] == $key_category) { ?> selected="selected" <?php } ?>><?php echo $row_category; ?></option>
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
//$sql_report_summary="select DISTINCT a.ticket_id,b.* from sdms_ticket_thread a JOIN sdms_ticket b on a.ticket_id=b.ticket_id where a.ticket_id!='' ";

$sql_report_summary="SELECT DISTINCT(thread_id) FROM `sdms_ticket_thread` where thread_id!='' ";

//a JOIN sdms_ticket b ON a.ticket_id = b.ticketID ";

if($_GET['AssingFDate']!="" && $_GET['AssingTDate']!="")
$sql_report_summary .=" AND date(a.created) >='".date('Y-m-d',strtotime($_GET['AssingFDate']))."' AND date(a.created) <='".date('Y-m-d',strtotime($_GET['AssingTDate']))."' AND a.thread_type='N'";

if($_GET['district']!="")
$sql_report_summary .=" AND b.district ='".$_GET['district']."'";

if($_GET['category']!="")
$sql_report_summary .=" AND b.topic_id ='".$_GET['category']."'";

if($_GET['Assign_from']!="")
$sql_report_summary .=" AND b.staff_id ='".$_GET['Assign_from']."'";

//if($_GET['Assign_to']!="")
//$sql_report_summary .=" AND b.ticket_id IN (Select DISTINCT ticket_id from sdms_ticket_thread where staff_id ='".$_GET['Assign_to']."' order by id DESC )";

if($_GET['Assign_to']!="")
$sql_report_summary .=" AND b.ticket_id IN (
Select DISTINCT ticket_id,MAX(id) from sdms_ticket_thread where staff_id ='".$_GET['Assign_to']."' GROUP BY ticket_id
ORDER BY `sdms_ticket_thread`.`ticket_id` ASC)";

if($_GET['status']!="")
$sql_report_summary .=" AND b.complaint_status ='".$_GET['status']."'";

if($_GET['complaint_no']!="")
$sql_report_summary .=" AND b.ticketID ='".$_GET['complaint_no']."'";

$sql_report_summary .="WHERE  a.id IN ( SELECT MAX( id ) FROM sdms_ticket_thread GROUP BY `ticket_id` ORDER BY ticket_id ASC ) Order By a.ticket_id Desc ";	
}
else{
$sql_report_summary="select * from sdms_ticket Order By ticket_id Desc ";
}
echo $sql_report_summary;
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
 ?>
  <tr>
    <td><?php echo $row_summary['ticketID'];?></td>
    <input type="hidden" name="ticketID[]" value="<?php echo $row_summary['ticketID'];?>"/>
    
    <td><?php echo $row_summary['name_title']." ".$row_summary['name'];?></td>
    <input type="hidden" name="name_title[]" value="<?php echo $row_summary['name_title'];?>"/>
    
    <td><?php echo date("d-M-Y",strtotime($row_summary['created']));?></td>
    <input type="hidden" name="created[]" value="<?php echo date("d-M-Y",strtotime($row_summary['created']));?>"/>
    
    <td><?php echo $row_summary['status'];?></td>
    <input type="hidden" name="status[]" value="<?php echo $row_summary['status'];?>"/>
	<?php 	
	$sql_report_theard="Select th.*,s.staff_id,s.firstname ,s.lastname from sdms_ticket_thread th
	LEFT JOIN  sdms_staff s ON th.staff_id=s.staff_id 
	where th.ticket_id ='".$row_summary['ticketID']."' Order By id Desc LIMIT 1";
	$res_report_theard=db_query($sql_report_theard);
	$row_summary_theard = db_fetch_array($res_report_theard);
	if($row_summary_theard['staff_id']=='')
	{
		$sql_report_theard="Select th.*,s.staff_id,s.firstname ,s.lastname from sdms_ticket_thread th
		LEFT JOIN  sdms_staff s ON th.staff_id=s.staff_id 
		where th.ticket_id ='".$row_summary['ticketID']."' Order By id Desc LIMIT 1,1";
		$res_report_theard=db_query($sql_report_theard);
		$row_summary_theard = db_fetch_array($res_report_theard);
		if($row_summary_theard['firstname']=='')
		{
			$staffname ='Web';
		}else{
		
		$staffname =  $row_summary_theard['firstname'].' '.$row_summary_theard['lastname'];
		}
	}
	else
	{
			if($row_summary_theard['firstname']=='')
		{
			$staffname ='Web';
		}else{
		
		$staffname =  $row_summary_theard['firstname'].' '.$row_summary_theard['lastname'];
		} 
	}
	 ?>
    <td><?php echo date("d-M-Y",strtotime($row_summary_theard['created']));  ?></td>
    <input type="hidden" name="created_thread[]" value="<?php echo date("d-M-Y",strtotime($row_summary_theard['created']));?>"/>
    
	<?php 
		$sql_staff="select * from sdms_staff where staff_id = '".$row_summary['staff_id']."'";
		$res_staff=db_query($sql_staff);
		$row_staff= db_fetch_array($res_staff);
	?>
    <td><?php echo $row_staff['firstname'].' '.$row_staff['lastname'];?></td>
    <input type="hidden" name="fullname[]" value="<?php echo $row_staff['firstname'].' '.$row_staff['lastname'];?>"/>
    
    <td><?php echo $staffname;  ?></td>
	<input type="hidden" name="staffname[]" value="<?php echo $staffname;?>"/>
    
	<?php 
    $sql_status="select * from sdms_status where status_id = '".$row_summary['complaint_status']."'";
    $res_status=db_query($sql_status);
    $row_status= db_fetch_array($res_status);
    ?>
    <td><?php echo $row_status['status_title'];  ?></td>
    <input type="hidden" name="status_title[]" value="<?php echo $row_status['status_title'];?>"/>
  </tr>
  <?php 
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


