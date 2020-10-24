<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("comlaintstatus_new_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
//myWindow.close();
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
}
elseif(!$thisstaff->isAdmin() &&  $thisstaff->onChairman() == '1')
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
$from_to_date = ' AND DATE(notify_date) >= "'.date('Y-m-d',strtotime($_POST['from_date'])).'" AND DATE(notify_date) <= "'.date('Y-m-d',strtotime($_POST['to_date'])).'"  ';
$date_range = '&startDate='.date('m/d/Y',strtotime($_REQUEST['from_date'])).'&endDate='.date('m/d/Y',strtotime($_REQUEST['to_date']));
}else{
$from_to_date ='';
$date_range = '';
}
?>
<div class="page-header">   
  <h1>Queries Escalation <small></small></h1>
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
<?php if($thisstaff->isAdmin()){ ?>
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
<input type="text" name="from_date" id="Datepicker"  value="<?php echo $_POST['from_date']; ?>" >
</td>
<th width="20%" style="padding-top:12px;">To Date</th>
<td>
<input type="text" name="to_date" id="Datepicker1"  value="<?php echo $_POST['to_date']; ?>" >
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

<!--<div class="row-fluid">
  <div class="span3" style="float:right;">
    <p align="right" style="float:right;"> <a id="ticket-print" class="action-button" href="" onclick="openWin();">
      <button class="btn" type="button"><i class="icon-print"></i> Print</button>
      </a> </p>
  </div>
</div>-->
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Queries Escalation</h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
  <col width="162" span="2">
  <col width="100" span="3">
  <col width="87">
  <col width="90">
  <col width="64" span="2">
  <col width="113">
  <col width="103">
  <col width="127">
  <col width="113">
  <tr>
    <td width="162"></td>
    <td width="162">From - To</td>
    <td width="100"></td>
    <td width="100">&nbsp;</td>
    <td width="100">&nbsp;</td>
    <td colspan="4" width="305">No Intimation to Complainant </td>
    <td width="113"></td>
    <td width="103"></td>
 
  </tr>
  <tr>
    <td>POC Name </td>
    <td width="100"> Non Acceptance<br>
      beyond 3 Days </td>
    
    <td width="87"> <br>
      within  7 Days</td>
    <td width="90"> <br>
      within  15 Days</td>
    <td width="64">No    action <br>
      within  30 Days</td>
    <td width="64">No    action <br>
      within  45 Days</td>
    <td width="103">Total    Escalations <br>
      to Service Desk</td>
    <td width="113">Total    Escalations <br>
      to Primary POC </td>
    <td width="127">Total    Escalations <br>
      to HOD </td>
    <td width="113">Total    Escalations <br>
      to Commissioner </td>
  </tr>
  <?php $sql_poclist="Select * from sdms_staff where group_id = 5 ".$dept_add."";
  $res_poclist = mysql_query($sql_poclist);
  while($row_poclist = mysql_fetch_array($res_poclist)){
	  $days3 = 0;
	  $days7 = 0;
	  $days15 = 0;
	  $days30  = 0;
	  $days45  = 0;
	  
$sql_dept="SELECT * FROM `sdms_department` WHERE dept_id='".$row_poclist['dept_id']."'";
$res_dept=mysql_query($sql_dept);
$row_dept = mysql_fetch_array($res_dept);

$sql_escalations ="select distinct notify_days, count(notify_days) as CountOf from sdms_notify where staff_id = '".$row_poclist['staff_id']."' AND ticket_id IN(SELECT DISTINCT(`ticket_id`) as ticket_id FROM sdms_ticket where isquery =1) ".$from_to_date." group by notify_days";
	 $res_escalations = mysql_query($sql_escalations);
     while($row_escalations = mysql_fetch_array($res_escalations)){
		 
	 if($row_escalations['notify_days'] == 3 )
	 { $days3 = $row_escalations['CountOf']; }
	 
	 if($row_escalations['notify_days'] == 7 )
	  {$days7 = $row_escalations['CountOf']; }
	 
	 if($row_escalations['notify_days'] == 15 )
	 { $days15 = $row_escalations['CountOf']; }
	 
	 if($row_escalations['notify_days'] == 30 )
	 { $days30 = $row_escalations['CountOf']; }
	 
	 if($row_escalations['notify_days'] == 45 )
	 { $days45 = $row_escalations['CountOf']; }
	 
	  
	 }
	
   ?>
  <tr>
    <td><?php echo $row_poclist['firstname'].''.$row_poclist['lastname']; 
	if($row_poclist['isfocalperson']=='1'){
	echo '<br><b>('.$row_dept['dept_name'].' POC)</b>';
	}
	?></td>
<td><a href="queries.php?esc_staffid=<?php echo $row_poclist['staff_id'].$date_range; ?>&esc_days=3&action=escalation"><?php echo $days3; ?></a></td>
<td><a href="queries.php?esc_staffid=<?php echo $row_poclist['staff_id'].$date_range; ?>&esc_days=7&action=escalation"><?php echo $days7; ?></a></td>
<td><a href="queries.php?esc_staffid=<?php echo $row_poclist['staff_id'].$date_range; ?>&esc_days=15&action=escalation"><?php echo $days15; ?></a></td>
<td><a href="queries.php?esc_staffid=<?php echo $row_poclist['staff_id'].$date_range; ?>&esc_days=30&action=escalation"><?php echo $days30; ?></a></td>
<td><a href="queries.php?esc_staffid=<?php echo $row_poclist['staff_id'].$date_range; ?>&esc_days=45&action=escalation"><?php echo $days45; ?></a></td>
<td><a href="queries.php?esc_staffid=<?php echo $row_poclist['staff_id'].$date_range; ?>&esc_days=3&action=escalation"><?php echo $days3; ?></a></td>
<td><a href="queries.php?esc_staffid=<?php echo $row_poclist['staff_id'].$date_range; ?>&esc_days=7-15&action=escalation"><?php echo $days7+$days15; ?></a></td>
<td><a href="queries.php?esc_staffid=<?php echo $row_poclist['staff_id'].$date_range; ?>&esc_days=30&action=escalation"><?php echo $days30; ?></a></td>
<td><a href="queries.php?esc_staffid=<?php echo $row_poclist['staff_id'].$date_range; ?>&esc_days=&action=escalation"><?php echo $days45; ?></a></td>
  </tr>
  <?php } ?>
</table>
    </div>
  </div>
</div>
<div class="dr"><span></span></div>



</div>
<!--WorkPlace End-->
</div>
