<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
if($thisstaff->isFocalPerson() == '1' || $thisstaff->getGroupId()=='8')
{
	$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	$dept_id = $thisstaff->getDeptId();
	$fromtodate = '';
}
elseif(!$thisstaff->isAdmin() &&  $thisstaff->onChairman() == '1'  && $_POST['dept_id']!='')
{
	//$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	//$dept_id = $thisstaff->getDeptId();
	
	$dept_add .= ' AND dept_id = '.$_POST['dept_id'].'';
	$dept_id = $_POST['dept_id'];
	
}
elseif($thisstaff->isAdmin() && $_POST['dept_id']!='')
{
$dept_add .= ' AND dept_id = '.$_POST['dept_id'].'';
$dept_id = $_POST['dept_id'];
}
if($_POST['from_date']!='' && $_POST['to_date']!='')
{
$from_to_date = ' AND DATE(created) >= "'.date('Y-m-d',strtotime($_POST['from_date'])).'" AND DATE(created) <= "'.date('Y-m-d',strtotime($_POST['to_date'])).'"  ';
$date_range = '&startDate='.date('m/d/Y',strtotime($_REQUEST['from_date'])).'&endDate='.date('m/d/Y',strtotime($_REQUEST['to_date']));
}else{
$from_to_date = '';
$date_range = '';
}
?>
<div class="page-header">
  <h1>Audit Report <small> Summary</small></h1>
</div>
<div class="row-fluid">
  <div class="span3" style="float:right;">
      <p align="right" style="float:right;display:none;"><a href="audit_report.csv"><button class="btn" type="button" id="export_to_csv" ><i class="icon-print"></i> Export</button></a>
      </p>
      <p align="right" style="float:right;display:none;"><a href="audit_report_new.csv"><button class="btn" type="button" id="export_to_csv_new" ><i class="icon-print"></i> Export New Format</button></a>
      </p>
  </div>
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
<td>
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
<input class="btn" type="submit" name="search" value="Search AND Export">
</td>
</tr>
</table>
</form>
</div>
</div>     
</div>
<?php }?>
<?php 
if(isset($_POST['search'])){
$csv='';			

$sql_dept="SELECT * FROM `sdms_department` WHERE 1 ".$dept_add."";
$res_dept=mysql_query($sql_dept);
$num_dept = mysql_num_rows($res_dept);
if($num_dept>0){
$subnum_dept_comp = 0;
		
while($row_dept=mysql_fetch_array($res_dept)){
$csv.='"Department Name:"'.',"'.$row_dept['dept_name'].'"'.',""'.','.'""'.',';
$csv.="\n";

	
		$sql_status="SELECT * FROM `sdms_status` WHERE p_id='0'";
		$res_status=mysql_query($sql_status);
		$subnum_status_comp = 0;
		$i=0;
		while($row_status=mysql_fetch_array($res_status)){
		$num_status_comp = 0;
		$sql_sub_status="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
		$res_sub_status=mysql_query($sql_sub_status);
		$num_sub_status = mysql_num_rows($res_sub_status);
		while($row_sub_status=mysql_fetch_array($res_sub_status)){	
		$sql_status_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND isaudit = '0' AND complaint_status='".$row_sub_status['status_id']."' AND  dept_id='".$row_dept['dept_id']."' ".$from_to_date."";
		$res_status_comp = mysql_query($sql_status_comp);
		$num_status_comp += mysql_num_rows($res_status_comp);
		}
		$percent = round(($num_status_comp/100)*10);
		$csv.='"Status"'.',"Total Complaints"'.',"10% of Total"'.','.'""'.',';
		$csv.="\n";
		
		$csv.='"'.$row_status['status_title'].'"'.',"'.$num_status_comp.'"'.',"'.$percent.'"'.','.'""'.',';
		$csv.="\n";

	 $sql_audit_complaint="SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND isaudit = '0' AND complaint_status IN (SELECT status_id FROM `sdms_status` WHERE `p_id`='".$row_status['status_id']."') AND  dept_id='".$row_dept['dept_id']."' ".$from_to_date." ORDER BY RAND() LIMIT 0,".$percent."";
		//echo $sql_audit_complaint;exit;
		$res_audit_complaint = mysql_query($sql_audit_complaint);
		if(mysql_num_rows($res_audit_complaint) > 0 ){ 
		//Complaint #	Complainant Name 	Subject 	Sub Status 	Action Taken	Auditor Comments	POC Comments
		
	$csv .='"Complaint #"'.',"Complainant Name"'.',"Subject"'.','.'"Sub Status"'.','.'"Action Taken"'.','.'"Auditor Comments"'.','.'"POC Comments"'.',';
	$csv .="\n";
		
		while($row_audit_complaint=mysql_fetch_array($res_audit_complaint)){
		
		//Sub Status
		$sql_ticket_status="SELECT * FROM `sdms_status` WHERE status_id='".$row_audit_complaint['complaint_status']."'";
		$res_ticket_status=mysql_query($sql_ticket_status);
		$row_ticket_status=mysql_fetch_array($res_ticket_status);	
		
		//Action Taken
		$sql_ticket_action="SELECT * FROM `sdms_ticket_thread` WHERE `ticket_id` ='".$row_audit_complaint['ticket_id']."' order by id desc Limit 1";
		$res_ticket_action=mysql_query($sql_ticket_action);
		$row_ticket_action=mysql_fetch_array($res_ticket_action);	
				
		
		//$sql_audit_done="UPDATE `sdms_ticket` SET isaudit = '1' WHERE `ticket_id` = '".$row_audit_complaint['ticket_id']."'";
		//mysql_query($sql_audit_done);
		
		 $csv.='"'.$row_audit_complaint['ticket_id'].'"'.',"'.$row_audit_complaint['name'].'"'.',"'.$row_audit_complaint['subject'].'"'.','.'"'.$row_ticket_status['status_title'].'"'.','.'"'.$row_ticket_action['body'].'"'.',';
		$csv.="\n";
		
		

		}
		}
		
		}
		$csv.="\n";
		$csv.="\n";
	} 	
//echo $csv;exit;
}
$file = 'audit_report.csv';
if (!$handle = fopen($file, 'w')) 
{
echo "Cannot open file ($filename)";
exit;                    
}
if (fwrite($handle, $csv) === FALSE) 
{
echo "Cannot write to file ($filename)";
exit;
}
fclose($handle);



?>
<script>
document.getElementById("export_to_csv").click();
</script>
<?php
}else{?>

<div class="row-fluid">
  <div class="span6" style="float:right;">
      <p align="right" style="float:right;">To download last searchable audit report. <a href="audit_report.csv">Click Here</a>
      </p>
  </div>
</div>
<?php }
?>
</div>
<!--WorkPlace End-->
</div>