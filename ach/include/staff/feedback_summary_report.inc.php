<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("comlaintstatus_new_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
//myWindow.close();
}

function export_to_csv(){
var export_csv = '';    
		//alert(items_csv);
	$.ajax({
			url:"comlaintsdepartment_new_csv.php",
			data: "&export_csv="+export_csv,
			success: function(msg){
			//alert(msg);
			document.getElementById("csv_download").click();
			//display_summary_table();
			}});			
	
}
</script>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
?>
<?php 				
if($thisstaff->isFocalPerson() == '1' || $thisstaff->getGroupId()=='8')
{
	$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	$dept_id = $thisstaff->getDeptId();
	$fromtodate = '';
	$feedback_from_to_date = '';
}
elseif(!$thisstaff->isAdmin() &&  $thisstaff->onChairman() == '1'  )
{
	$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	$dept_id = $thisstaff->getDeptId();
	
	//$dept_add .= ' AND dept_id = '.$_POST['dept_id'].'';
	//$dept_id = $_POST['dept_id'];
	
}
elseif($thisstaff->isAdmin() && $_POST['dept_id']!='')
{
$dept_add .= ' AND dept_id = '.$_POST['dept_id'].'';
$dept_id = $_POST['dept_id'];
}
if($_POST['from_date']!='' && $_POST['to_date']!='')
{
$from_to_date = ' AND DATE(created) >= "'.date('Y-m-d',strtotime($_POST['from_date'])).'" AND DATE(created) <= "'.date('Y-m-d',strtotime($_POST['to_date'])).'"  ';

$feedback_from_to_date = ' AND DATE(date) >= "'.date('Y-m-d',strtotime($_POST['from_date'])).'" AND DATE(date) <= "'.date('Y-m-d',strtotime($_POST['to_date'])).'"  ';
$date_range = '&startDate='.date('m/d/Y',strtotime($_REQUEST['from_date'])).'&endDate='.date('m/d/Y',strtotime($_REQUEST['to_date']));

$f_date_range = '&sDate='.date('m/d/Y',strtotime($_REQUEST['from_date'])).'&eDate='.date('m/d/Y',strtotime($_REQUEST['to_date']));
}else{
$from_to_date ='';
$date_range = '';
$f_date_range = '';

}
?>
<div class="page-header">
  <h1>Feedback Summary<small> Report</small></h1>
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
<?php if($thisstaff->isAdmin() ){
	//|| $thisstaff->onChairman() == '1' ?>
<th width="20%" style="padding-top:12px;">By Department</th>
<td  >
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
<?php }?>
<th width="20%" style="padding-top:12px;">From Date</th>
<td>
<input type="text" name="from_date" id="Datepicker" value="<?php echo $_POST['from_date']; ?>"  autocomplete="off">
</td>
<th width="20%" style="padding-top:12px;">To Date</th>
<td>
<input type="text" name="to_date" id="Datepicker1" value="<?php echo $_POST['to_date']; ?>"  autocomplete="off">
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

<div class="row-fluid">
  <div class="span12">
<div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Feedback Summary</h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
            
            <thead>
            <tr>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th colspan="3" width="">Feedback Responses</th>
            </tr>
            <tr>
              <th>Departments</th>
              <th>Sent</th>
              <th>Received</th>
              <th>Satisfied</th>
              <th>Neutral</th>
              <th>Dissatisfied</th>

            </tr>
            </thead>
            <tbody>
          <?php
		  $sub_total_close = 0;
		  $sub_total_recieved = 0;
		  $sub_num_satisfied = 0;
		  $sub_num_neutral = 0;
		  
$sql_dept="SELECT * FROM `sdms_department` WHERE 1 ".$dept_add ."";
$res_dept = mysql_query($sql_dept);
while($row_dept = mysql_fetch_array($res_dept)){ ?>
            <tr>
              <td><?php echo $row_dept['dept_name']; ?></td>
              <td align="right">
<?php 
$num_status_comp = 0;			  
$primary_stutus = 5;
$sql_status_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status IN (SELECT status_id FROM `sdms_status` WHERE `p_id`= '".$primary_stutus."') AND dept_id='".$row_dept['dept_id']."'  ".$dept_add." ".$from_to_date."";
$res_status_comp = mysql_query($sql_status_comp);
$num_status_comp += mysql_num_rows($res_status_comp);
?>

<a href="tickets.php?a=search&deptId=<?php echo $row_dept['dept_id']; ?>&primary_stutus=<?php echo $primary_stutus.$date_range; ?>"><?php echo $num_status_comp; $sub_total_close += $num_status_comp; ?></a>

</td>
              <td><?php
$sql_feedback="SELECT * FROM `sdms_feedback` WHERE 1 AND dept_id= ".$row_dept['dept_id']."  ".$dept_add." ".$feedback_from_to_date." group by complainant_id";
$res_feedback=mysql_query($sql_feedback);
$num_feedback = mysql_num_rows($res_feedback);?>
<a href="tickets.php?a=search&deptId=<?php echo $row_dept['dept_id']; ?>&action=feedbac_received&primary_stutus=<?php echo $primary_stutus.$f_date_range; ?>"><?php echo $num_feedback; ?></a>
<?php $sub_total_recieved += $num_feedback; 
			   ?></td>
              <td><?php
			  
$sql_satisfied="SELECT * FROM `sdms_feedback` WHERE 1 AND dept_id= ".$row_dept['dept_id']." AND experiance = '3' ".$dept_add." ".$feedback_from_to_date."  group by complainant_id";
$res_satisfied=mysql_query($sql_satisfied);
$num_satisfied = mysql_num_rows($res_satisfied); ?>
<a href="tickets.php?a=search&deptId=<?php echo $row_dept['dept_id']; ?>&action=feedbac_received&experiance=3&primary_stutus=<?php echo $primary_stutus.$f_date_range; ?>">
<?php echo $num_satisfied;?>
</a>
<?php $sub_num_satisfied += $num_satisfied; 
			   ?></td>
              <td><?php
			  
$sql_neutral="SELECT * FROM `sdms_feedback` WHERE 1 AND dept_id= ".$row_dept['dept_id']."  AND experiance = '2' ".$dept_add." ".$feedback_from_to_date."  group by complainant_id";
$res_neutral=mysql_query($sql_neutral);
$num_neutral = mysql_num_rows($res_neutral); ?>
<a href="tickets.php?a=search&deptId=<?php echo $row_dept['dept_id']; ?>&action=feedbac_received&experiance=2&primary_stutus=<?php echo $primary_stutus.$f_date_range; ?>"><?php echo $num_neutral; ?></a>
<?php $sub_num_neutral += $num_neutral; 
			   ?></td>
              <td><?php
$sql_dissatisfied="SELECT * FROM `sdms_feedback` WHERE 1 AND dept_id= ".$row_dept['dept_id']." AND experiance = '1' ".$dept_add." ".$feedback_from_to_date."  group by complainant_id";
$res_dissatisfied=mysql_query($sql_dissatisfied);
$num_dissatisfied = mysql_num_rows($res_dissatisfied); ?>
<a href="tickets.php?a=search&deptId=<?php echo $row_dept['dept_id']; ?>&action=feedbac_received&experiance=1&primary_stutus=<?php echo $primary_stutus.$f_date_range; ?>"><?php echo $num_dissatisfied; ?></a>
<?php $sub_num_dissatisfied += $num_dissatisfied; 
			   ?></td>
              
            </tr>
            <?php } ?>
            </tbody>
            
            <tfoot>
            <tr>
              <td>Total</td>
              <td align="right"><a href="tickets.php?a=search&primary_stutus=<?php echo $primary_stutus.$date_range; ?>"><?php  echo $sub_total_close;?></a></td>
              <td><a href="tickets.php?a=search&action=all_feedbac_received&primary_stutus=<?php echo $primary_stutus.$f_date_range; ?>"><?php echo $sub_total_recieved; ?></a></td>
              <td><a href="tickets.php?a=search&action=all_feedbac_received&experiance=3&primary_stutus=<?php echo $primary_stutus.$f_date_range; ?>"><?php echo $sub_num_satisfied; ?></a></td>
              <td><a href="tickets.php?a=search&action=all_feedbac_received&experiance=2&primary_stutus=<?php echo $primary_stutus.$f_date_range; ?>"><?php echo $sub_num_neutral; ?></a></td>
              <td><a href="tickets.php?a=search&action=all_feedbac_received&experiance=1&primary_stutus=<?php echo $primary_stutus.$f_date_range; ?>"><?php echo $sub_num_dissatisfied; ?></a></td>              
            </tr>
            </tfoot>
</table>
</div>
  </div>
</div>
<div class="dr"><span></span></div>
</div>
<!--WorkPlace End-->
</div>