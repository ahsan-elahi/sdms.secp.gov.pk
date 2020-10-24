<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

$csv='';						 		 		
$csv.='"Status of Complaint"'.','.'"Total Complaints"'.','.'"Ageing-IN DAYS"'.',';
$csv.="\n";
$csv .= '""'.','.'""'.','.'"1 to 15"'.','.'"15+"'.','.'"30+"'.','.'"45+"'.','.'"180+"'.','.'"365+"'.','.'"730+"'.','.'"1095+"'.',';

$csv.="\n";
$q_dept_id = ''; 				
if($thisstaff->isFocalPerson() == '1' || $thisstaff->getGroupId()=='8')
{
	$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	$dept_id = $thisstaff->getDeptId();
	$q_dept_id = '&dept_id='.$thisstaff->getDeptId().'';
}
elseif(!$thisstaff->isAdmin() &&  $thisstaff->onChairman() == '1' )
{
	$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	$dept_id = $thisstaff->getDeptId();
	$q_dept_id = '&dept_id='.$dept_id.'';
}
elseif($thisstaff->isAdmin() && $_POST['dept_id']!='')
{
$dept_add .= ' AND dept_id = '.$_POST['dept_id'].'';
$dept_id = $_POST['dept_id'];
$q_dept_id = '&dept_id='.$_POST['dept_id'].'';
}
if($_POST['from_date']!='' && $_POST['to_date']!='')
{
$from_date = date('Y-m-d',strtotime($_REQUEST['from_date']));
$to_date = date('Y-m-d',strtotime($_REQUEST['to_date']));
	
$from_to_date = ' AND DATE(created) >= "'.date('Y-m-d',strtotime($_POST['from_date'])).'" AND DATE(created) <= "'.date('Y-m-d',strtotime($_POST['to_date'])).'"  ';
$date_range = '&startDate='.date('m/d/Y',strtotime($_REQUEST['from_date'])).'&endDate='.date('m/d/Y',strtotime($_REQUEST['to_date']));
}else{
$from_to_date ='';
$date_range = '';
}
?>
<script>
function openWin(dept_id)
{
//window.open(URL,name,specs,replace)
myWindow=window.open("comlaintstatus_new_print.php?dept_id="+dept_id,"Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
//myWindow.close();
}
function export_to_csv(){
	var dept_id = '';
	$.ajax({
	url:"comlaintstatus_new_csv.php",
	data: "dept_id="+dept_id,
	success: function(msg){	
	//alert(msg);
	document.getElementById("csv_download").click();
	}
	});				
}

function export_to_csv_search(dept_id){
	$.ajax({
	url:"comlaintstatus_new_csv.php",
	data: "dept_id="+dept_id,
	success: function(msg){	
	//alert(msg);
	document.getElementById("csv_download").click();
	}
	});				
}
var d1 = [];
var d2 = [];
var d3 = [];
var data =[];
</script>
<div class="page-header">
  <h1>Status Ageing<small> Summary</small></h1>
</div>
<?php /*?><div class="row-fluid">
  <div class="span3" style="float:right;">
    <!--<p align="right" style="float:right;"> <a id="ticket-print" class="action-button" href="" onclick="openWin(<?php //echo $dept_id; ?>);">
      <button class="btn" type="button"><i class="icon-print"></i> Print</button>
      </a> </p>-->

<p align="right" style="float:right;"> 
<a href="comlaintstatus_new_csv.csv"><button class="btn" type="button"><i class="icon-print"></i> Export</button></a>
    </p>
  </div>
</div><?php */?>
<!-- search code start -->
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
<?php if($thisstaff->isAdmin() ){ ?>
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
<?php } ?>
<th width="20%" style="padding-top:12px;">From Date</th>
<td>
<input type="text" name="from_date" id="Datepicker"  value="<?php echo $_POST['from_date']; ?>" >
</td>
<th width="20%" style="padding-top:12px;">To Date</th>
<td>
<input type="text" name="to_date" id="Datepicker1" value="<?php echo $_POST['to_date']; ?>" >
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
<!-- search code end -->
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Status Ageing</h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
      <form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
        <input type="hidden" name="a" value="mass_process" >
        <input type="hidden" name="status" value="<?php echo $statusss?>" >
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
          <thead>
            <tr>
              <th rowspan="2">Status of Complaint</th>
              <th rowspan="2" align="center">Total Complaints</th>
              <th colspan="8" align="center">Ageing-IN DAYS</th>
            </tr>
            <tr>
              <th>1 to 15 Days</th>
              <th>15+ Days</th>
              <th>30+ Days</th>
              <th>45+ Days</th>
              <th>180+ Days</th>
              <th>365+ Days</th>
              <th>730+ Days</th>
              <th>1095+ Days</th>
            </tr>
          </thead>
          <tbody class="" page="1">
            <?php 
$sql_status="SELECT * FROM `sdms_status` WHERE p_id='0' AND status_id != '2' AND status_id != '5' ";
$res_status=mysql_query($sql_status);
$num_status = mysql_num_rows($res_status);
if($num_status>0){

$subt_1to15days = 0;
$subt_16to30days = 0;
$subt_31to45days = 0;
$subt_45daysplus = 0;	
$subnum_status_comp = 0;

$s_1_day=0;
$s_16_day=0;
$s_30_day=0;
$s_45_day=0;

while($row_status=mysql_fetch_array($res_status)){
$sub_sub_status = "";	
$t_1to15days = 0;
$t_16to30days = 0;
$t_31to45days = 0;
$t_45daysplus = 0;
$num_status_comp = 0;

$sql_sub_status="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
$res_sub_status=mysql_query($sql_sub_status);
$num_sub_status = mysql_num_rows($res_sub_status);
if($_POST['from_date']!='' && $_POST['to_date']!='')
            {
            $today_date =  date('Y-m-d',strtotime($_POST['to_date'])); 
            $s_1_day =   date('Y-m-d',strtotime($_POST['to_date'])); 
            }else{
            $today_date =  date('Y-m-d');            
            $s_1_day =  date('Y-m-d'); 
            }
 
			$days_1to15 = date ("Y-m-d", strtotime("-15 day", strtotime($today_date)));
            $days_15plus = date ("Y-m-d", strtotime("-30 day", strtotime($today_date)));
            $days_30plus = date ("Y-m-d", strtotime("-45 day", strtotime($today_date)));
            $days_45plus = date ("Y-m-d", strtotime("-180 day", strtotime($today_date)));
			$days_180plus = date ("Y-m-d", strtotime("-365 day", strtotime($today_date)));
			$days_365plus = date ("Y-m-d", strtotime("-730 day", strtotime($today_date)));
			$days_730plus = date ("Y-m-d", strtotime("-1095 day", strtotime($today_date)));
			
			//echo $today_date;
			//echo 
			
            $t_1to15days = 0;
            $t_16to30days = 0;
            $t_31to45days = 0;
            $t_45daysplus = 0;
			$t_180daysplus = 0;
			$t_365daysplus = 0;
			$t_730daysplus = 0;
			$t_1095daysplus = 0;
			
            $num_status_comp = 0;
            
while($row_sub_status=mysql_fetch_array($res_sub_status)){
			
$sql_status_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' ".$dept_add." ".$from_to_date."";
$res_status_comp = mysql_query($sql_status_comp);
$num_status_comp += mysql_num_rows($res_status_comp);            
//echo "first loop 1 to 15 Days";
	

$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' 
AND DATE(created) <= '".$today_date."'
AND DATE(created) > '".$days_1to15."'
".$dept_add."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days += mysql_num_rows($res_1to15days);

//echo "second loop 15+ Days";
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."'
AND DATE(created) <= '".$days_1to15."'
AND DATE(created) > '".$days_15plus."'
".$dept_add."";
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days += mysql_num_rows($res_16to30days);


//echo '<br><br>';
//echo "second loop 45+ Days";
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' 
AND DATE(created) <= '".$days_15plus."'
AND DATE(created) > '".$days_30plus."'
".$dept_add."";
$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days += mysql_num_rows($res_31to45days);
//echo '<br><br>';
$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."'
AND DATE(created) <= '".$days_30plus."'
AND DATE(created) > '".$days_45plus."'
".$dept_add."";
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus += mysql_num_rows($res_45daysplus);
//echo '<br><br>';
$sql_180daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."'
AND DATE(created) <= '".$days_45plus."'
AND DATE(created) > '".$days_180plus."'
".$dept_add."";
$res_180daysplus = mysql_query($sql_180daysplus);
$t_180daysplus += mysql_num_rows($res_180daysplus);
//echo '<br><br>';

$sql_365daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."'
AND DATE(created) <= '".$days_180plus."'
AND DATE(created) > '".$days_365plus."'
".$dept_add."";
$res_365daysplus = mysql_query($sql_365daysplus);
$t_365daysplus += mysql_num_rows($res_365daysplus);

//echo '<br><br>';
 $sql_730daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."'
AND DATE(created) <= '".$days_365plus."'
AND DATE(created) > '".$days_730plus."'
".$dept_add."";
$res_730daysplus = mysql_query($sql_730daysplus);
$t_730daysplus += mysql_num_rows($res_730daysplus);
//echo '<br><br>';
 $sql_1095daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."'
AND DATE(created) <= '".$days_730plus."'
".$dept_add."";
//echo '<br><br>';

$res_1095daysplus = mysql_query($sql_1095daysplus);
$t_1095daysplus += mysql_num_rows($res_1095daysplus);

//exit;
}
$primary_status .= "{ name: '".$row_status['status_title']."', y: ".$num_status_comp.",
            drilldown: '".$row_status['status_title']."'},";
$sub_status .="{
            name: '".$row_status['status_title']."',
            id: '".$row_status['status_title']."',
            data: [ ";
			
$subnum_status_comp +=$num_status_comp;
$subt_1to15days += $t_1to15days;
$subt_16to30days += $t_16to30days;
$subt_31to45days += $t_31to45days;
$subt_45daysplus += $t_45daysplus;
$subt_180daysplus += $t_180daysplus;
$subt_365daysplus += $t_365daysplus;
$subt_730daysplus += $t_730daysplus;
$subt_1095daysplus += $t_1095daysplus;

$href_status = 'tickets.php?action=report&status=open&primary_stutus='.$row_status['status_id'].$q_dept_id;
$href_status_1to15days = $href_status.'&r_type=1to15days&from_date='.$today_date.'&to_date='.$days_1to15.'';
$href_status_16to30days = $href_status.'&r_type=16to30days&from_date='.$days_1to15.'&to_date='.$days_15plus.'';
$href_status_31to45days = $href_status.'&r_type=31to45days&from_date='.$days_15plus.'&to_date='.$days_30plus.'';
$href_status_45daysplus = $href_status.'&r_type=45daysplus&from_date='.$days_30plus.'&to_date='.$days_45plus.'';
$href_status_180daysplus = $href_status.'&r_type=180daysplus&from_date='.$days_45plus.'&to_date='.$days_180plus.'';
$href_status_365daysplus = $href_status.'&r_type=365daysplus&from_date='.$days_180plus.'&to_date='.$days_365plus.'';
$href_status_730daysplus = $href_status.'&r_type=730daysplus&from_date='.$days_365plus.'&to_date='.$days_730plus.'';
$href_status_1095daysplus = $href_status.'&r_type=1095daysplus&from_date='.$days_730plus.'';


$href_status_total = 'tickets.php?action=report&status=open';
$href_status_total_1to15days =$href_status_total.'&r_type=1to15days&from_date='.$today_date.'&to_date='.$days_1to15.'';
$href_status_total_16to30days =$href_status_total.'&r_type=16to30days&from_date='.$days_1to15.'&to_date='.$days_15plus.'';
$href_status_total_31to45days =$href_status_total.'&r_type=31to45days&from_date='.$days_15plus.'&to_date='.$days_30plus.'';
$href_status_total_45daysplus = $href_status_total.'&r_type=45daysplus&from_date='.$days_30plus.'&to_date='.$days_45plus.'';
$href_status_total_180daysplus = $href_status_total.'&r_type=180daysplus&from_date='.$days_45plus.'&to_date='.$days_180plus.'';
$href_status_total_365daysplus = $href_status_total.'&r_type=365daysplus&from_date='.$days_180plus.'&to_date='.$days_365plus.'';
$href_status_total_730daysplus = $href_status_total.'&r_type=730daysplus&from_date='.$days_365plus.'&to_date='.$days_730plus.'';
$href_status_total_1095daysplus = $href_status_total.'&r_type=1095daysplus&from_date='.$days_730plus.'';


?>
         <tr>
              <th> <a onclick="show_item(<?php echo $row_status['status_id']; ?>)" id='show_temp_item_<?php echo $row_status['status_id']; ?>'><span style="float:left; width:350px;"  ><?php echo $row_status['status_title'];?></span></a></th>
              
              
              <td><b><span align="right">
              <a href="<?php echo $href_status; ?>">
			  <?php echo $num_status_comp;  ?>
              </a></span></b></td>
              
              <td><a href="<?php echo $href_status_1to15days; ?>"><?php echo $t_1to15days; ?></a></td>              
              <td><a href="<?php echo $href_status_16to30days; ?>"><?php echo $t_16to30days; ?></a></td>
              <td><a href="<?php echo $href_status_31to45days; ?>"><?php echo $t_31to45days; ?></a></td>
              <td><a href="<?php echo $href_status_45daysplus; ?>"><?php echo $t_45daysplus; ?></a></td>
              <td><a href="<?php echo $href_status_180daysplus; ?>"><?php echo $t_180daysplus; ?></a></td>
              <td><a href="<?php echo $href_status_365daysplus; ?>"><?php echo $t_365daysplus; ?></a></td>
              <td><a href="<?php echo $href_status_730daysplus; ?>"><?php echo $t_730daysplus; ?></a></td>
              <td><a href="<?php echo $href_status_1095daysplus; ?>"><?php echo $t_1095daysplus; ?></a></td>
              
              
  
            </tr>  

            <?php if($num_sub_status>0){ ?>
            <tr>
                <td class="report_sub_table" align="CENTER" colspan="10" id="item_temp_section_<?php echo $row_status['status_id']; ?>">
                
                    <table >
                    <thead>            <tr>
              <th rowspan="2">Sub Status of Complaint</th>
              <th rowspan="2" align="center">Total Complaints</th>
              <th colspan="8" align="center">Ageing-IN DAYS</th>
            </tr>
            <tr>
              <th>1 to 15 Days</th>
              <th>15+ Days</th>
              <th>30+ Days</th>
              <th>45+ Days</th>
              <th>180+ Days</th>
              <th>365+ Days</th>
              <th>730+ Days</th>
              <th>1095+ Days</th>
            </tr>
            
            
          </thead>
                        <tbody>
<?php						
$sql_sub_status_inner="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
$res_sub_status_inner=mysql_query($sql_sub_status_inner);
$num_sub_status_inner = mysql_num_rows($res_sub_status_inner);
while($row_sub_status_inner=mysql_fetch_array($res_sub_status_inner)){
	
	
if($_POST['from_date']!='' && $_POST['to_date']!='')
            {
            $today_date =  date('Y-m-d',strtotime($_POST['to_date'])); 
            $s_1_day =   date('Y-m-d',strtotime($_POST['to_date'])); 
            }else{
            $today_date =  date('Y-m-d');             
            $s_1_day =  date('Y-m-d'); 
            }
			
			$days_1to15 = date ("Y-m-d", strtotime("-15 day", strtotime($today_date)));
            $days_15plus = date ("Y-m-d", strtotime("-30 day", strtotime($today_date)));
            $days_30plus = date ("Y-m-d", strtotime("-45 day", strtotime($today_date)));
            $days_45plus = date ("Y-m-d", strtotime("-180 day", strtotime($today_date)));
			$days_180plus = date ("Y-m-d", strtotime("-365 day", strtotime($today_date)));
			$days_365plus = date ("Y-m-d", strtotime("-730 day", strtotime($today_date)));
			$days_730plus = date ("Y-m-d", strtotime("-1095 day", strtotime($today_date)));
			
            $t_1to15days = 0;
            $t_16to30days = 0;
            $t_31to45days = 0;
            $t_45daysplus = 0;
			$t_180daysplus = 0;
			$t_365daysplus = 0;
			$t_730daysplus = 0;
			$t_1095daysplus = 0;
			
            $num_status_comp_inner = 0;
			


$sql_status_comp_inner = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."' ".$dept_add."";
$res_status_comp_inner = mysql_query($sql_status_comp_inner);
$num_status_comp_inner = mysql_num_rows($res_status_comp_inner);


	

$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."' 
AND DATE(created) <= '".$today_date."'
AND DATE(created) > '".$days_1to15."'
".$dept_add."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days += mysql_num_rows($res_1to15days);


	//echo "second loop 15+ Days";
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."'
AND DATE(created) <= '".$days_1to15."'
AND DATE(created) > '".$days_15plus."'
".$dept_add."";
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days += mysql_num_rows($res_16to30days);



//echo "second loop 45+ Days";
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."' 
AND DATE(created) <= '".$days_15plus."'
AND DATE(created) > '".$days_30plus."'
".$dept_add."";
$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days += mysql_num_rows($res_31to45days);



$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."'
AND DATE(created) <= '".$days_30plus."'
AND DATE(created) > '".$days_45plus."'
".$dept_add."";
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus += mysql_num_rows($res_45daysplus);

$sql_180daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."'
AND DATE(created) <= '".$days_45plus."'
AND DATE(created) > '".$days_180plus."'
".$dept_add."";
$res_180daysplus = mysql_query($sql_180daysplus);
$t_180daysplus += mysql_num_rows($res_180daysplus);


$sql_365daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."'
AND DATE(created) <= '".$days_180plus."'
AND DATE(created) > '".$days_365plus."'
".$dept_add."";
$res_365daysplus = mysql_query($sql_365daysplus);
$t_365daysplus += mysql_num_rows($res_365daysplus);


$sql_730daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."'
AND DATE(created) <= '".$days_365plus."'
AND DATE(created) > '".$days_730plus."'
".$dept_add."";
$res_730daysplus = mysql_query($sql_730daysplus);
$t_730daysplus += mysql_num_rows($res_730daysplus);

$sql_1095daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."'
AND DATE(created) <= '".$days_730plus."'
".$dept_add."";
$res_1095daysplus = mysql_query($sql_1095daysplus);
$t_1095daysplus += mysql_num_rows($res_1095daysplus);









$sub_status .="{ name:'".$row_sub_status_inner['status_title']."', y: ".$num_status_comp_inner.", drilldown: '".$row_dept['dept_name'].$row_sub_status_inner['status_title']."'},";
$sub_sub_status .= "{ id: '".$row_dept['dept_name'].$row_sub_status_inner['status_title']."',data: [";
//$sub_sub_status .= "['".$row_sub_status_inner['status_title']."' ,".$sub_status_total."],";
$sub_sub_status .="['1 to 15', ".$t_1to15days."],['15+', ".$t_16to30days."],['30+', ".$t_31to45days."],['45+', ".$t_45daysplus."]";
$sub_sub_status .= "]},";


$href_sub_status = 'tickets.php?&a=search&action=report&status=open&cstatus='.$row_sub_status_inner['status_id'].$q_dept_id;
$href_sub_status_1to15days = $href_sub_status.'&r_type=1to15days&from_date='.$today_date.'&to_date='.$days_1to15.'';
$href_sub_status_16to30days = $href_sub_status.'&r_type=16to30days&from_date='.$days_1to15.'&to_date='.$days_15plus.'';
$href_sub_status_31to45days = $href_sub_status.'&r_type=31to45days&from_date='.$days_15plus.'&to_date='.$days_30plus.'';
$href_sub_status_45daysplus = $href_sub_status.'&r_type=45daysplus&from_date='.$days_30plus.'&to_date='.$days_45plus.'';
$href_sub_status_180daysplus = $href_sub_status.'&r_type=180daysplus&from_date='.$days_45plus.'&to_date='.$days_180plus.'';
$href_sub_status_365daysplus = $href_sub_status.'&r_type=365daysplus&from_date='.$days_180plus.'&to_date='.$days_365plus.'';
$href_sub_status_730daysplus = $href_sub_status.'&r_type=730daysplus&from_date='.$days_365plus.'&to_date='.$days_730plus.'';
$href_sub_status_1095daysplus = $href_sub_status.'&r_type=1095daysplus&from_date='.$days_730plus.'';
?>
    <tr>
    <td> <span style="float:left; width:430px;"><?php echo $row_sub_status_inner['status_title'];?></span></a></td>
    <td style="width:125px;"><a href="<?php echo $href_sub_status ?>"><?php echo $num_status_comp_inner; ?></a></td>
    <td style="width:80px;"><a href="<?php echo $href_sub_status_1to15days; ?>"><?php echo $t_1to15days; ?></a></td>              
    <td style="width:80px;"><a href="<?php echo $href_sub_status_16to30days; ?>"><?php echo $t_16to30days; ?></a></td>
    <td style="width:80px;"><a href="<?php echo $href_sub_status_31to45days; ?>"><?php echo $t_31to45days; ?></a></td>
    <td style="width:80px;"><a href="<?php echo $href_sub_status_45daysplus; ?>"><?php echo $t_45daysplus; ?></a></td>
    
    <td style="width:50px;"><a href="<?php echo $href_sub_status_180daysplus; ?>"><?php echo $t_180daysplus; ?></a></td>
    <td style="width:50px;"><a href="<?php echo $href_sub_status_365daysplus; ?>"><?php echo $t_365daysplus; ?></a></td>
    <td style="width:50px;"><a href="<?php echo $href_sub_status_730daysplus; ?>"><?php echo $t_730daysplus; ?></a></td>
    <td style="width:50px;"><a href="<?php echo $href_sub_status_1095daysplus; ?>"><?php echo $t_1095daysplus; ?></a></td>
    
    </tr>

<?php 
}
$sub_status .="]},";
$sub_status .= $sub_sub_status;
							 ?>
                        
                        </tbody>
                    </table>
                </td>
            </tr>
            <?php } ?>
            
            <?php 
			
			$csv .= '"' . $row_status['status_title'] . '",';
$csv .= '"' . $num_dept_comp. '",';
$csv .= '"' . $t_1to15days. '",';
$csv .= '"' . $t_16to30days. '",';
$csv .= '"' . $t_31to45days. '",';
$csv .= '"' . $t_45daysplus. '",';
$csv .= '"' . $t_180daysplus. '",';
$csv .= '"' . $t_365daysplus. '",';
$csv .= '"' . $t_730daysplus. '",';
$csv .= '"' . $t_1095daysplus. '",';

			
      $csv.="\n"; 
			
			
			}?>
            <tr id="total">
              <th><span style="float: left; width:350px;">Total</span></th>
                <td>
                <a href="<?php echo $href_status_total; ?>">
                <span class="Icon <?php echo $icon;?>" align="right"><b><span align="right"><?php echo $subnum_status_comp; ?> </span></b></span>
                </a>
                </td>
                <td><a href="<?php echo $href_status_total_1to15days; ?>"><b><?php echo $subt_1to15days; ?></b></a></td>
                <td><a href="<?php echo $href_status_total_16to30days; ?>"><b><?php echo $subt_16to30days; ?></b></a></td>
                <td><a href="<?php echo $href_status_total_31to45days; ?>"><b><?php echo $subt_31to45days; ?></b></a></td>
                <td><a href="<?php echo $href_status_total_45daysplus; ?>"><b><?php echo $subt_45daysplus; ?></b></a></td>
                <td><a href="<?php echo $href_status_total_180daysplus; ?>"><b><?php echo $subt_180daysplus; ?></b></a></td>
                <td><a href="<?php echo $href_status_total_365daysplus; ?>"><b><?php echo $subt_365daysplus; ?></b></a></td>
                <td><a href="<?php echo $href_status_total_730daysplus; ?>"><b><?php echo $subt_730daysplus; ?></b></a></td>
                <td><a href="<?php echo $href_status_total_1095daysplus; ?>"><b><?php echo $subt_1095daysplus; ?></b></a></td>
                
                
            </tr>
            <?php
$csv.='"Total"'.','.'"'.$subnum_status_comp.'"'.','.'"'.$subt_1to15days.'"'.','.'"'.$subt_16to30days.'"'.','.'"'.$subt_31to45days.'"'.',
'.'"'.$subt_45daysplus.'"'.','.'"'.$subt_180daysplus.'"'.','.'"'.$subt_365daysplus.'"'.','.'"'.$subt_730daysplus.'"'.','.'"'.$subt_1095daysplus.'"'.',';	
$csv.="\n";
$csv.="\n";
$csv.="\n";
$csv.="\n";
			 }else{?>
            <tr class="<?php echo $class?>">
              <td><b>Query returned 0 results.</b></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div>
<div class="row-fluid">
            <div class="span12">
            <div class="head clearfix">
            <div class="isw-right_circle"></div>
            <h1>Pie charts</h1>
            </div>
            <div class="block">
            <div id="container" style="margin: 0 auto"></div>
            </div>
            </div>
            <script type="text/javascript">
            // Create the chart
			var defaultTitle = "Status Ageing";
			var drilldownTitle = "More about ";
            Highcharts.chart('container', {
            chart: {
            type: 'pie',
			events: {
			drilldown: function(e) {
			this.setTitle({ text: drilldownTitle + e.point.name });
			},
			drillup: function(e) {
			this.setTitle({ text: defaultTitle });
			}
			}
            },
            title: {
            text: 'Primary Status'
            },
            subtitle: {
            text: 'Click the slices to view substatus.'
            },
            plotOptions: {
            series: {
            dataLabels: {
            enabled: true,
           // format: '{point.name}: {point.y:.1f}'
			format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
            }
            },
            tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> of total<br/>'
			},
            series: [{
            name: 'Status',
            colorByPoint: true,
            data: [<?php echo $primary_status; ?>]
            }],
            drilldown: {
            series: [<?php echo $sub_status;  ?>]
            }
            });
			</script>                        
    </div>
<div class="dr"><span></span></div>



<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Status Ageing</h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
      <form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
        <input type="hidden" name="a" value="mass_process" >
        <input type="hidden" name="status" value="<?php echo $statusss?>" >
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
          <thead>
            <tr>
              <th rowspan="2">Status of Complaint</th>
              <th rowspan="2" align="center">Total Complaints</th>
              <th colspan="8" align="center">Ageing-IN DAYS</th>
            </tr>
            <tr>
              <th>1 to 15 Days</th>
              <th>15+ Days</th>
              <th>30+ Days</th>
              <th>45+ Days</th>
              <th>180+ Days</th>
              <th>365+ Days</th>
              <th>730+ Days</th>
              <th>1095+ Days</th>
            </tr>
          </thead>
          <tbody class="" page="1">
            <?php 

$sql_status="SELECT * FROM `sdms_status` WHERE p_id='0' AND status_id = '2' OR status_id = '5' ";
$res_status=mysql_query($sql_status);
$num_status = mysql_num_rows($res_status);
if($num_status>0){

$subt_1to15days = 0;
$subt_16to30days = 0;
$subt_31to45days = 0;
$subt_45daysplus = 0;	
$subnum_status_comp = 0;

$s_1_day=0;
$s_16_day=0;
$s_30_day=0;
$s_45_day=0;

while($row_status=mysql_fetch_array($res_status)){
	
$sub_sub_status = "";	
$t_1to15days = 0;
$t_16to30days = 0;
$t_31to45days = 0;
$t_45daysplus = 0;
$num_status_comp = 0;

if($_POST['from_date']!='' && $_POST['to_date']!='')
{
$today_date =  date('Y-m-d',strtotime($_POST['to_date'])); 
$s_1_day =   date('Y-m-d',strtotime($_POST['to_date'])); 
}else{
$today_date =  date('Y-m-d');           
$s_1_day =  date('Y-m-d'); 
}


$days_1to15 = date ("Y-m-d", strtotime("-15 day", strtotime($today_date)));
$days_15plus = date ("Y-m-d", strtotime("-30 day", strtotime($today_date)));
$days_30plus = date ("Y-m-d", strtotime("-45 day", strtotime($today_date)));
$days_45plus = date ("Y-m-d", strtotime("-60 day", strtotime($today_date)));  

$days_180plus = date ("Y-m-d", strtotime("-180 day", strtotime($today_date)));  
$days_365plus = date ("Y-m-d", strtotime("-3650 day", strtotime($today_date)));  
$days_730plus = date ("Y-m-d", strtotime("-730 day", strtotime($today_date)));  
$days_1095plus = date ("Y-m-d", strtotime("-1095 day", strtotime($today_date)));  



$t_1to15days = 0;
$t_16to30days = 0;
$t_31to45days = 0;
$t_45daysplus = 0;

$t_180daysplus = 0;
$t_365daysplus = 0;
$t_730daysplus = 0;
$t_1095daysplus = 0;
$num_status_comp = 0;
       
			
$sql_status_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status IN (SELECT status_id FROM `sdms_status` WHERE `p_id`='".$row_status['status_id']."') ".$dept_add." ".$from_to_date."";
$res_status_comp = mysql_query($sql_status_comp);
$num_status_comp += mysql_num_rows($res_status_comp);            
//echo "first loop 1 to 15 Days";

$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status IN (SELECT status_id FROM `sdms_status` WHERE `p_id`='".$row_status['status_id']."') 
AND (
(TIMESTAMPDIFF(DAY,created,closed)<15 AND reopened IS NULL) 
OR 
(TIMESTAMPDIFF(DAY,reopened,closed)<15 AND reopened IS NOT NULL)
)
".$dept_add."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days += mysql_num_rows($res_1to15days);

//echo "second loop 15+ Days";
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status IN (SELECT status_id FROM `sdms_status` WHERE `p_id`='".$row_status['status_id']."')
AND (
(TIMESTAMPDIFF(DAY,created,closed)>=15 AND TIMESTAMPDIFF(DAY,created,closed)<30 AND reopened IS NULL) 
OR 
(TIMESTAMPDIFF(DAY,reopened,closed)>=15 AND TIMESTAMPDIFF(DAY,reopened,closed)<30 AND reopened IS NOT NULL)
)
".$dept_add."";
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days += mysql_num_rows($res_16to30days);

//echo "second loop 45+ Days";
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status IN (SELECT status_id FROM `sdms_status` WHERE `p_id`='".$row_status['status_id']."')
AND ( 
(TIMESTAMPDIFF(DAY,created,closed)>=30 AND TIMESTAMPDIFF(DAY,created,closed)<45  AND reopened IS NULL) 
OR 
(TIMESTAMPDIFF(DAY,reopened,closed)>=30 AND TIMESTAMPDIFF(DAY,reopened,closed)<45  AND reopened IS NOT NULL) 
)
".$dept_add."";
$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days += mysql_num_rows($res_31to45days);

$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status IN (SELECT status_id FROM `sdms_status` WHERE `p_id`='".$row_status['status_id']."')
AND ( 
(TIMESTAMPDIFF(DAY,created,closed)>=45  AND reopened IS NULL)
OR
(TIMESTAMPDIFF(DAY,reopened,closed)>=45  AND reopened IS NOT NULL)
)
".$dept_add."";
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus += mysql_num_rows($res_45daysplus);


$sql_180daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status IN (SELECT status_id FROM `sdms_status` WHERE `p_id`='".$row_status['status_id']."')
AND ( 
(TIMESTAMPDIFF(DAY,created,closed)>=180  AND reopened IS NULL)
OR
(TIMESTAMPDIFF(DAY,reopened,closed)>=180  AND reopened IS NOT NULL)
)
".$dept_add."";
$res_180daysplus = mysql_query($sql_180daysplus);
$t_180daysplus += mysql_num_rows($res_180daysplus);


$sql_365daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status IN (SELECT status_id FROM `sdms_status` WHERE `p_id`='".$row_status['status_id']."')
AND ( 
(TIMESTAMPDIFF(DAY,created,closed)>=365  AND reopened IS NULL)
OR
(TIMESTAMPDIFF(DAY,reopened,closed)>=365  AND reopened IS NOT NULL)
)
".$dept_add."";
$res_365daysplus = mysql_query($sql_365daysplus);
$t_365daysplus += mysql_num_rows($res_365daysplus);


$sql_730daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status IN (SELECT status_id FROM `sdms_status` WHERE `p_id`='".$row_status['status_id']."')
AND ( 
(TIMESTAMPDIFF(DAY,created,closed)>=730  AND reopened IS NULL)
OR
(TIMESTAMPDIFF(DAY,reopened,closed)>=730  AND reopened IS NOT NULL)
)
".$dept_add."";
$res_730daysplus = mysql_query($sql_730daysplus);
$t_730daysplus += mysql_num_rows($res_730daysplus);

$sql_1095daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status IN (SELECT status_id FROM `sdms_status` WHERE `p_id`='".$row_status['status_id']."')
AND ( 
(TIMESTAMPDIFF(DAY,created,closed)>=1095  AND reopened IS NULL)
OR
(TIMESTAMPDIFF(DAY,reopened,closed)>=1095  AND reopened IS NOT NULL)
)
".$dept_add."";
$res_1095daysplus = mysql_query($sql_1095daysplus);
$t_1095daysplus += mysql_num_rows($res_1095daysplus);







$primary_status .= "{ name: '".$row_status['status_title']."', y: ".$num_status_comp.",
            drilldown: '".$row_status['status_title']."'},";
$sub_status .="{
            name: '".$row_status['status_title']."',
            id: '".$row_status['status_title']."',
            data: [ ";
			
$subnum_status_comp +=$num_status_comp;

$subt_1to15days += $t_1to15days;
$subt_16to30days += $t_16to30days;
$subt_31to45days += $t_31to45days;
$subt_45daysplus += $t_45daysplus;
$subt_180daysplus += $t_180daysplus;
$subt_365daysplus += $t_365daysplus;
$subt_730daysplus += $t_730daysplus;
$subt_1095daysplus += $t_1095daysplus;

$href_status = 'tickets.php?action=report&status=closed&primary_stutus='.$row_status['status_id'].$q_dept_id;
$href_status_1to15days = $href_status.'&r_type=1to15days&from_date='.$today_date.'&to_date='.$days_1to15.'';
$href_status_16to30days = $href_status.'&r_type=16to30days&from_date='.$days_1to15.'&to_date='.$days_15plus.'';
$href_status_31to45days = $href_status.'&r_type=31to45days&from_date='.$days_15plus.'&to_date='.$days_30plus.'';
$href_status_45daysplus = $href_status.'&r_type=45daysplus&from_date='.$days_30plus.'&to_date='.$days_45plus.'';
$href_status_180daysplus = $href_status.'&r_type=180daysplus&from_date='.$days_45plus.'&to_date='.$days_180plus.'';
$href_status_365daysplus = $href_status.'&r_type=365daysplus&from_date='.$days_180plus.'&to_date='.$days_365plus.'';
$href_status_730daysplus = $href_status.'&r_type=730daysplus&from_date='.$days_365plus.'&to_date='.$days_730plus.'';
$href_status_1095daysplus = $href_status.'&r_type=1095daysplus&from_date='.$days_730plus.'';


$href_status_total = 'tickets.php?action=report&status=closed'.$q_dept_id;
$href_status_total_1to15days =$href_status_total.'&r_type=1to15days&from_date='.$today_date.'&to_date='.$days_1to15.'';
$href_status_total_16to30days =$href_status_total.'&r_type=16to30days&from_date='.$days_1to15.'&to_date='.$days_15plus.'';
$href_status_total_31to45days =$href_status_total.'&r_type=31to45days&from_date='.$days_15plus.'&to_date='.$days_30plus.'';
$href_status_total_45daysplus = $href_status_total.'&r_type=45daysplus&from_date='.$days_30plus.'&to_date='.$days_45plus.'';
$href_status_total_180daysplus = $href_status_total.'&r_type=180daysplus&from_date='.$days_45plus.'&to_date='.$days_180plus.'';
$href_status_total_365daysplus = $href_status_total.'&r_type=365daysplus&from_date='.$days_180plus.'&to_date='.$days_365plus.'';
$href_status_total_730daysplus = $href_status_total.'&r_type=730daysplus&from_date='.$days_365plus.'&to_date='.$days_730plus.'';
$href_status_total_1095daysplus = $href_status_total.'&r_type=1095daysplus&from_date='.$days_730plus.'';

?>
         <tr>
              <th> <a onclick="show_item(<?php echo $row_status['status_id']; ?>)" id='show_temp_item_<?php echo $row_status['status_id']; ?>'><span style="float:left; width:350px;"  ><?php echo $row_status['status_title'];?></span></a></th>
              
              
              <td><b><span align="right">
              <a href="<?php echo $href_status; ?>">
			  <?php echo $num_status_comp;  ?>
              </a></span></b></td>
              
              <?php /*?><td><a href="<?php echo $href_status_1to15days; ?>"><?php echo $t_1to15days; ?></a></td>              
              <td><a href="<?php echo $href_status_16to30days; ?>"><?php echo $t_16to30days; ?></a></td>
              <td><a href="<?php echo $href_status_31to45days; ?>"><?php echo $t_31to45days; ?></a></td>
              <td><a href="<?php echo $href_status_45daysplus; ?>"><?php echo $t_45daysplus; ?></a></td>
              <td><a href="<?php echo $href_status_180daysplus; ?>"><?php echo $t_180daysplus; ?></a></td>
              <td><a href="<?php echo $href_status_365daysplus; ?>"><?php echo $t_365daysplus; ?></a></td>
              <td><a href="<?php echo $href_status_730daysplus; ?>"><?php echo $t_730daysplus; ?></a></td>
              <td><a href="<?php echo $href_status_1095daysplus; ?>"><?php echo $t_1095daysplus; ?></a></td>
<?php */?>              
              <td><?php echo $t_1to15days; ?></td>              
              <td><?php echo $t_16to30days; ?></td>
              <td><?php echo $t_31to45days; ?></td>
              <td><?php echo $t_45daysplus; ?></td>
              <td><?php echo $t_180daysplus; ?></td>
              <td><?php echo $t_365daysplus; ?></td>
              <td><?php echo $t_730daysplus; ?></td>
              <td><?php echo $t_1095daysplus; ?></td>
              
              
              
              
            </tr>  

            <?php if($num_sub_status>0){ ?>
            <?php /*?><tr>
                <td class="report_sub_table" align="CENTER" colspan="6" id="item_temp_section_<?php echo $row_status['status_id']; ?>">
                
                    <table >
                    <thead>            <tr>
              <th rowspan="2">Sub Status of Complaint</th>
              <th rowspan="2" align="center">Total Complaints</th>
              <th colspan="4" align="center">Ageing-IN DAYS</th>
            </tr>
            <tr>
              <th>1 to 15 Days</th>
              <th>15+ Days</th>
              <th>30+ Days</th>
              <th>45+ Days</th>
            </tr>
            
            
          </thead>
                        <tbody>
<?php						
$sql_sub_status_inner="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
$res_sub_status_inner=mysql_query($sql_sub_status_inner);
$num_sub_status_inner = mysql_num_rows($res_sub_status_inner);
while($row_sub_status_inner=mysql_fetch_array($res_sub_status_inner)){
	
	
if($_POST['from_date']!='' && $_POST['to_date']!='')
            {
            $today_date =  date('Y-m-d',strtotime($_POST['to_date'])); 
            $days_1to15 = date ("Y-m-d", strtotime("-15 day", strtotime($today_date)));
            $days_15plus = date ("Y-m-d", strtotime("-30 day", strtotime($today_date)));
            $days_30plus = date ("Y-m-d", strtotime("-45 day", strtotime($today_date)));
            $days_45plus = date ("Y-m-d", strtotime("-60 day", strtotime($today_date)));
            $s_1_day =   date('Y-m-d',strtotime($_POST['to_date'])); 
            }else{
            $today_date =  date('Y-m-d'); 
            $days_1to15 = date ("Y-m-d", strtotime("-15 day", strtotime($today_date)));
            $days_15plus = date ("Y-m-d", strtotime("-30 day", strtotime($today_date)));
            $days_30plus = date ("Y-m-d", strtotime("-45 day", strtotime($today_date)));
            $days_45plus = date ("Y-m-d", strtotime("-60 day", strtotime($today_date)));            
            $s_1_day =  date('Y-m-d'); 
            }
			
            $t_1to15days = 0;
            $t_16to30days = 0;
            $t_31to45days = 0;
            $t_45daysplus = 0;
            $num_status_comp_inner = 0;
			


$sql_status_comp_inner = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."' ".$dept_add."";
$res_status_comp_inner = mysql_query($sql_status_comp_inner);
$num_status_comp_inner = mysql_num_rows($res_status_comp_inner);


	

$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."' 
AND DATE(created) <= '".$today_date."'
AND DATE(created) > '".$days_1to15."'
".$dept_add."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days += mysql_num_rows($res_1to15days);


	//echo "second loop 15+ Days";
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."'
AND DATE(created) <= '".$days_1to15."'
AND DATE(created) > '".$days_15plus."'
".$dept_add."";
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days += mysql_num_rows($res_16to30days);



//echo "second loop 45+ Days";
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."' 
AND DATE(created) <= '".$days_15plus."'
AND DATE(created) > '".$days_30plus."'
".$dept_add."";
$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days += mysql_num_rows($res_31to45days);

$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."'
AND DATE(created) <= '".$days_30plus."'
".$dept_add."";
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus += mysql_num_rows($res_45daysplus);


$sub_status .="{ name:'".$row_sub_status_inner['status_title']."', y: ".$num_status_comp_inner.", drilldown: '".$row_dept['dept_name'].$row_sub_status_inner['status_title']."'},";
$sub_sub_status .= "{ id: '".$row_dept['dept_name'].$row_sub_status_inner['status_title']."',data: [";
//$sub_sub_status .= "['".$row_sub_status_inner['status_title']."' ,".$sub_status_total."],";
$sub_sub_status .="['1 to 15', ".$t_1to15days."],['15+', ".$t_16to30days."],['30+', ".$t_31to45days."],['45+', ".$t_45daysplus."]";
$sub_sub_status .= "]},";


$href_sub_status = 'tickets.php?&a=search&action=report&status=closed&cstatus='.$row_sub_status_inner['status_id'].$q_dept_id;
$href_sub_status_1to15days = $href_sub_status.'&r_type=1to15days&from_date='.$today_date.'&to_date='.$days_1to15.'';
$href_sub_status_16to30days = $href_sub_status.'&r_type=16to30days&from_date='.$days_1to15.'&to_date='.$days_15plus.'';
$href_sub_status_31to45days = $href_sub_status.'&r_type=31to45days&from_date='.$days_15plus.'&to_date='.$days_30plus.'';
$href_sub_status_45daysplus = $href_sub_status.'&r_type=45daysplus&from_date='.$days_30plus.'';

?>
    <tr>
    <td> <span style="float:left; width:430px;"><?php echo $row_sub_status_inner['status_title'];?></span></a></td>
    <td style="width:125px;"><a href="<?php echo $href_sub_status ?>"><?php echo $num_status_comp_inner; ?></a></td>
    <td style="width:80px;"><a href="<?php echo $href_sub_status_1to15days; ?>"><?php echo $t_1to15days; ?></a></td>              
    <td style="width:80px;"><a href="<?php echo $href_sub_status_16to30days; ?>"><?php echo $t_16to30days; ?></a></td>
    <td style="width:80px;"><a href="<?php echo $href_sub_status_31to45days; ?>"><?php echo $t_31to45days; ?></a></td>
    <td style="width:50px;"><a href="<?php echo $href_sub_status_45daysplus; ?>"><?php echo $t_45daysplus; ?></a></td>
    </tr>

<?php 
}
$sub_status .="]},";
$sub_status .= $sub_sub_status;
							 ?>
                        
                        </tbody>
                    </table>
                </td>
            </tr><?php */?>
            <?php } ?>
            
            <?php 
		

			$csv .= '"' . $row_status['status_title'] . '",';
$csv .= '"' . $num_dept_comp. '",';
$csv .= '"' . $t_1to15days. '",';
$csv .= '"' . $t_16to30days. '",';
$csv .= '"' . $t_31to45days. '",';
$csv .= '"' . $t_45daysplus. '",';
$csv .= '"' . $t_180daysplus. '",';
$csv .= '"' . $t_365daysplus. '",';
$csv .= '"' . $t_730daysplus. '",';
$csv .= '"' . $t_1095daysplus. '",';			
			
			      $csv.="\n"; 
			
			
			}?>
            <tr id="total">
              <th><span style="float: left; width:350px;">Total</span></th>
                <td>
                <a href="<?php echo $href_status_total; ?>">
                <span class="Icon <?php echo $icon;?>" align="right"><b><span align="right"><?php echo $subnum_status_comp; ?> </span></b></span>
                </a>
                </td>
                
                <?php /*?>      <td><a href="<?php echo $href_status_total_1to15days; ?>"><b><?php echo $subt_1to15days; ?></b></a></td>
                <td><a href="<?php echo $href_status_total_16to30days; ?>"><b><?php echo $subt_16to30days; ?></b></a></td>
                <td><a href="<?php echo $href_status_total_31to45days; ?>"><b><?php echo $subt_31to45days; ?></b></a></td>
                <td><a href="<?php echo $href_status_total_45daysplus; ?>"><b><?php echo $subt_45daysplus; ?></b></a></td>
                <td><a href="<?php echo $href_status_total_180daysplus; ?>"><b><?php echo $subt_180daysplus; ?></b></a></td>
                <td><a href="<?php echo $href_status_total_365daysplus; ?>"><b><?php echo $subt_365daysplus; ?></b></a></td>
                <td><a href="<?php echo $href_status_total_730daysplus; ?>"><b><?php echo $subt_730daysplus; ?></b></a></td>
                <td><a href="<?php echo $href_status_total_1095daysplus; ?>"><b><?php echo $subt_1095daysplus; ?></b></a></td>
                <?php */?>
                      <td><b><?php echo $subt_1to15days; ?></b></td>
                <td><b><?php echo $subt_16to30days; ?></b></td>
                <td><b><?php echo $subt_31to45days; ?></b></td>
                <td><b><?php echo $subt_45daysplus; ?></b></td>
                <td><b><?php echo $subt_180daysplus; ?></b></td>
                <td><b><?php echo $subt_365daysplus; ?></b></td>
                <td><b><?php echo $subt_730daysplus; ?></b></td>
                <td><b><?php echo $subt_1095daysplus; ?></b></td>
                
             
                
            </tr>
            <?php
$csv.='"Total"'.','.'"'.$subnum_status_comp.'"'.','.'"'.$subt_1to15days.'"'.','.'"'.$subt_16to30days.'"'.','.'"'.$subt_31to45days.'"'.',
'.'"'.$subt_45daysplus.'"'.','.'"'.$subt_180daysplus.'"'.','.'"'.$subt_365daysplus.'"'.','.'"'.$subt_730daysplus.'"'.','.'"'.$subt_1095daysplus.'"'.',';
$csv.="\n";
$csv.="\n";
$csv.="\n";
$csv.="\n";
			 }else{?>
            <tr class="<?php echo $class?>">
              <td><b>Query returned 0 results.</b></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div>
<?php /*?><div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Status Ageing</h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
      <form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
        <input type="hidden" name="a" value="mass_process" >
        <input type="hidden" name="status" value="<?php echo $statusss?>" >
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
          <thead>
            <tr>
              <th rowspan="2">Status of Complaint</th>
              <th rowspan="2" align="center">Total Complaints</th>
              <th colspan="4" align="center">Ageing-IN DAYS</th>
            </tr>
            <tr>
              <th>1 to 15 Days</th>
              <th>15+ Days</th>
              <th>30+ Days</th>
              <th>45+ Days</th>
            </tr>
          </thead>
          <tbody class="" page="1">
            <?php

$primary_status = '';
$sub_status = '' ;
$sql_status="SELECT * FROM `sdms_status` WHERE p_id='0' AND status_id = '2' OR status_id = '5' ";
$res_status=mysql_query($sql_status);
$num_status = mysql_num_rows($res_status);
if($num_status>0){

$subt_1to15days = 0;
$subt_16to30days = 0;
$subt_31to45days = 0;
$subt_45daysplus = 0;	
$subnum_status_comp = 0;

$s_1_day=0;
$s_16_day=0;
$s_30_day=0;
$s_45_day=0;

while($row_status=mysql_fetch_array($res_status)){
$sub_sub_status = "";
$t_1to15days = 0;
$t_16to30days = 0;
$t_31to45days = 0;
$t_45daysplus = 0;
$num_status_comp = 0;

$sql_status_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status IN (SELECT status_id FROM `sdms_status` WHERE `p_id`='".$row_status['status_id']."') ".$dept_add." ".$from_to_date."";
//echo $sql_status_comp.'<br>';
$res_status_comp = mysql_query($sql_status_comp);
$num_status_comp += mysql_num_rows($res_status_comp);
//echo $num_status_comp.'<br><br>';
//echo "first loop 1 to 15 Days";
$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0'  AND complaint_status IN (SELECT status_id FROM `sdms_status` WHERE `p_id`='".$row_status['status_id']."') 
AND (
(TIMESTAMPDIFF(DAY,created,closed)<15 AND reopened IS NULL) 
OR 
(TIMESTAMPDIFF(DAY,reopened,closed)<15 AND reopened IS NOT NULL)
)
 ".$dept_add." ".$from_to_date."";
//echo $sql_1to15days;
//echo $sql_1to15days.'<br>';
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days += mysql_num_rows($res_1to15days);
//echo $t_1to15days.'<br>';
//echo "second loop 15+ Days";
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status IN (SELECT status_id FROM `sdms_status` WHERE `p_id`='".$row_status['status_id']."') 
AND (
(TIMESTAMPDIFF(DAY,created,closed)>=15 AND TIMESTAMPDIFF(DAY,created,closed)<30 AND reopened IS NULL) 
OR 
(TIMESTAMPDIFF(DAY,reopened,closed)>=15 AND TIMESTAMPDIFF(DAY,reopened,closed)<30 AND reopened IS NOT NULL)
)
 ".$dept_add." ".$from_to_date."";
//echo $sql_16to30days.'<br>';
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days += mysql_num_rows($res_16to30days);
//echo $t_16to30days.'<br>';
//echo "second loop 45+ Days";
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status IN (SELECT status_id FROM `sdms_status` WHERE `p_id`='".$row_status['status_id']."') 
AND ( 
(TIMESTAMPDIFF(DAY,created,closed)>=30 AND TIMESTAMPDIFF(DAY,created,closed)<45  AND reopened IS NULL) 
OR 
(TIMESTAMPDIFF(DAY,reopened,closed)>=30 AND TIMESTAMPDIFF(DAY,reopened,closed)<45  AND reopened IS NOT NULL) 
)

".$dept_add." ".$from_to_date."";
//echo $sql_31to45days.'<br>';
$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days += mysql_num_rows($res_31to45days);
//echo $t_31to45days.'<br>';

$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status IN (SELECT status_id FROM `sdms_status` WHERE `p_id`='".$row_status['status_id']."') 
AND ( 
(TIMESTAMPDIFF(DAY,created,closed)>=45  AND reopened IS NULL)
OR
(TIMESTAMPDIFF(DAY,reopened,closed)>=45  AND reopened IS NOT NULL)
)
  ".$dept_add." ".$from_to_date."";
//echo $sql_45daysplus.'<br>';
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus += mysql_num_rows($res_45daysplus);
//echo $t_45daysplus.'<br>';exit;

$primary_status .= "{ name: '".$row_status['status_title']."', y: ".$num_status_comp.",
            drilldown: '".$row_status['status_title']."'},";
$sub_status .="{
            name: '".$row_status['status_title']."',
            id: '".$row_status['status_title']."',
            data: [ ";
?>
         <tr>
              <th> <a onclick="show_item(<?php echo $row_status['status_id']; ?>)" id='show_temp_item_<?php echo $row_status['status_id']; ?>'><span style="float:left; width:350px;"  ><?php echo $row_status['status_title'];?></span></a></th>
              <td><b><span align="right"><a href="tickets.php?a=search&primary_stutus=<?php echo $row_status['status_id'].$q_dept_id.$date_range; ?>&status=closed&action=report"><?php echo $num_status_comp; $subnum_status_comp +=$num_status_comp; ?></a></span></b></td>
              <td><a href="tickets.php?a=search&primary_stutus=<?php echo $row_status['status_id'].$q_dept_id.$date_range; ?>&status=closed&r_type=1to15days&action=report"><?php echo $t_1to15days;  $subt_1to15days += $t_1to15days;  ?></a></td>              
              <td> <a href="tickets.php?a=search&primary_stutus=<?php echo $row_status['status_id'].$q_dept_id.$date_range; ?>&status=closed&r_type=16to30days&action=report"><?php echo $t_16to30days; $subt_16to30days += $t_16to30days;?></a></td>
              <td><a href="tickets.php?a=search&primary_stutus=<?php echo $row_status['status_id'].$q_dept_id.$date_range; ?>&status=closed&r_type=31to45days&action=report"><?php echo $t_31to45days; $subt_31to45days += $t_31to45days;?></a></td>
              <td><a href="tickets.php?a=search&primary_stutus=<?php echo $row_status['status_id'].$q_dept_id.$date_range; ?>&status=closed&r_type=45daysplus&action=report"><?php echo $t_45daysplus; $subt_45daysplus += $t_45daysplus;?></a></td>
            </tr>
            <?php if($num_sub_status>0){ ?>
         
            <?php } ?>
            
            <?php 
			
			$csv .= '"' . $row_status['status_title'] . '",';
$csv .= '"' . $num_dept_comp. '",';
$csv .= '"' . $t_1to15days. '",';
$csv .= '"' . $t_16to30days. '",';
$csv .= '"' . $t_31to45days. '",';
$csv .= '"' . $t_45daysplus. '",';
			
      $csv.="\n"; 
			
			
			}?>
            <tr id="total">
              <th><span style="float: left; width:350px;">Total</span></th>
              <td><span class="Icon <?php echo $icon;?>" align="right"><b><span align="right"><?php echo $subnum_status_comp; ?> </span></b><br />
                </span></td>
              <td ><b><?php echo $subt_1to15days; ?></b></td>
              <td><b><?php echo $subt_16to30days; ?></b></td>
              <td><b><?php echo $subt_31to45days; ?></b></td>
              <td><b><?php echo $subt_45daysplus; ?></b></td>
            </tr>
            <?php
			
			 $csv.='"Total"'.','.'"'.$subnum_status_comp.'"'.','.'"'.$subt_1to15days.'"'.','.'"'.$subt_16to30days.'"'.','.'"'.$subt_31to45days.'"'.','.'"'.$subt_45daysplus.'"'.',';	
$csv.="\n";
$csv.="\n";
$csv.="\n";
$csv.="\n";
			 }else{?>
            <tr class="<?php echo $class?>">
              <td><b>Query returned 0 results.</b></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div><?php */?>
<?php
//echo $primary_status.'<Br>';
//echo $sub_status;exit;
 ?>
<?php /*?><div class="row-fluid">
            <div class="span12">
            <div class="head clearfix">
            <div class="isw-right_circle"></div>
            <h1>Pie charts</h1>
            </div>
            <div class="block">
            <div id="container_second" style="margin: 0 auto"></div>
            </div>
            </div>
            <script type="text/javascript">
            // Create the chart
            Highcharts.chart('container_second', {
            chart: {
            type: 'pie'
            },
            title: {
            text: 'Primary Status'
            },
            subtitle: {
            text: 'Click the slices to view substatus.'
            },
            plotOptions: {
            series: {
            dataLabels: {
            enabled: true,
           // format: '{point.name}: {point.y:.1f}'
			format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
            }
            },
            tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> of total<br/>'
			},
            series: [{
            name: 'Status',
            colorByPoint: true,
            data: [<?php echo $primary_status; ?>]
            }],
            drilldown: {
            series: [<?php echo $sub_status;  ?>]
            }
            });
			</script>                        
    </div><?php */?>
<div class="dr"><span></span></div>
</div>
<!--WorkPlace End-->
</div>