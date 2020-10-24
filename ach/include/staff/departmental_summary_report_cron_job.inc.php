<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');	
$first_day_this_month = date('Y-m-01');
$last_day_this_month  = date('Y-m-t');
$from_to_date = ' AND DATE(created) >= "'.date('Y-m-d',strtotime($first_day_this_month)).'" AND DATE(created) <= "'.date('Y-m-d',strtotime($last_day_this_month)).'"  ';
?>	
<div class="row-fluid">
  <div class="span12">
    <div class="block-fluid table-sorting clearfix">
      <table cellpadding="0" cellspacing="0" width="100%" class="table">
        <thead>
          <tr>
            <th rowspan="2">Department of Complaint</th>
            <th rowspan="2" align="center">Total Complaints</th>
            <th colspan="5" align="center">Status</th>
          </tr>
          <tr>
            <?php 
			 $sql_status="SELECT * FROM `sdms_status` WHERE p_id='0'";
$res_status=mysql_query($sql_status);
$num_status = mysql_num_rows($res_status);
while($row_status=mysql_fetch_array($res_status)){
$csv .= '"' . $row_status['status_title'] . '",';	
			  ?>
            <th><a onclick="show_substatus(<?php echo $row_status['status_id']; ?>)" id='show_temp_status_<?php echo $row_status['status_id']; ?>'><?php echo $row_status['status_title'];?></a></th>
            <?php 
			  $sql_sub_status="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
$res_sub_status=mysql_query($sql_sub_status);
$num_sub_status = mysql_num_rows($res_sub_status);
while($row_sub_status=mysql_fetch_array($res_sub_status)){
$csv .= '"' . $row_sub_status['status_title'] . '",';	
	  ?>
            <th style="display:none;" class="status_<?php echo $row_status['status_id']; ?>"><?php echo $row_sub_status['status_title'];?></th>
            <?php }  ?>
            <?php  } ?>
          </tr>
        </thead>
        <?php 
$sql_checking = "SELECT * FROM `sdms_comp_dept_report` where YEAR(from_date) = '".date('Y',strtotime($first_day_this_month))."' AND MONTH(from_date) = '".date('m',strtotime($first_day_this_month))."' ";
$res_checking=mysql_query($sql_checking);
$num_checking = mysql_num_rows($res_checking);
if($num_checking <= 0){?>
        <tbody class="" page="1">
          <?php 
$sub_status_count = array();			
$sql_dept="SELECT * FROM `sdms_department` WHERE 1 ";
$res_dept=mysql_query($sql_dept);
$num_dept = mysql_num_rows($res_dept);
if($num_dept>0){
	$subnum_dept_comp = 0;
while($row_dept=mysql_fetch_array($res_dept)){
$num_dept_comp = 0;
$sql_dept_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND dept_id='".$row_dept['dept_id']."'  ".$from_to_date."";
$res_dept_comp = mysql_query($sql_dept_comp);
$num_dept_comp = mysql_num_rows($res_dept_comp);

$sql_add_dept_report = "INSERT INTO `sdms_comp_dept_report` (`dept_id`, `dept_total`, `from_date`, `to_date`) VALUES ( '".$row_dept['dept_id']."', '".$num_dept_comp."', '".$first_day_this_month."', '".$last_day_this_month."')";
mysql_query($sql_add_dept_report);
$last_id = mysql_insert_id();

?>
          <tr>
            <th><span style="float:left; width:350px;"  ><?php echo $row_dept['dept_name']; ?></span></th>
            <td><b><span align="right"><a href="tickets.php?a=search&deptId=<?php echo $row_dept['dept_id'].$date_range; ?>"><?php echo $num_dept_comp; $subnum_dept_comp +=$num_dept_comp; ?></a></span></b></td>
<?php 
$sql_status="SELECT * FROM `sdms_status` WHERE p_id='0'";
$res_status=mysql_query($sql_status);
$subnum_status_comp = 0;
$i=0;
while($row_status=mysql_fetch_array($res_status)){
	$ticket_ids = "";
$num_status_comp = 0;
$sql_sub_status="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
$res_sub_status=mysql_query($sql_sub_status);
$num_sub_status = mysql_num_rows($res_sub_status);
while($row_sub_status=mysql_fetch_array($res_sub_status)){	
$sql_status_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND  dept_id='".$row_dept['dept_id']."' ".$from_to_date."";
$res_status_comp = mysql_query($sql_status_comp);
$num_status_comp += mysql_num_rows($res_status_comp);
while($row_get_tickets=mysql_fetch_array($res_status_comp)){	
	$ticket_ids .= $row_get_tickets['ticketID'].',';
}

}

$sql_add_pstatus_report = "INSERT INTO `sdms_comp_pstatus_report` (`pstatus_id`, `pstatus_total`,`ticket_ids`, `dept_id`, `did`) VALUES ('".$row_status['status_id']."', '".$num_status_comp."','".$ticket_ids."', '".$row_dept['dept_id']."', '".$last_id."')";
mysql_query($sql_add_pstatus_report);
$last_pstatus_id = mysql_insert_id();
?>
            <td><a href="tickets.php?a=search&deptId=<?php echo $row_dept['dept_id']; ?>&primary_stutus=<?php echo $row_status['status_id'].$date_range; ?>"><?php echo $num_status_comp; $sub_status_count[$i] +=$num_status_comp; ?></a></td>
            <?php
                $sql_sub_status_inner="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
                $res_sub_status_inner=mysql_query($sql_sub_status_inner);
                $num_sub_status_inner = mysql_num_rows($res_sub_status_inner);
				while($row_sub_status_inner = mysql_fetch_array($res_sub_status_inner)){
                $num_status_comp_inner = 0;
                $sql_status_comp_inner = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."' AND  dept_id='".$row_dept['dept_id']."' ".$from_to_date."";
                $res_status_comp_inner = mysql_query($sql_status_comp_inner);
                $num_status_comp_inner = mysql_num_rows($res_status_comp_inner);
				$sub_status_total = $num_status_comp_inner;
			
$sql_add_sstatus_report = "INSERT INTO `sdms_comp_sstatus_report`(`sstatus_id`, `sstatus_total`, `dept_id`, `pid`) VALUES ('".$row_sub_status_inner['status_id']."', '".$num_status_comp_inner."', '".$row_dept['dept_id']."', '".$last_pstatus_id."')";
mysql_query($sql_add_sstatus_report);
				
				?>
            <td style="display:none;" class="status_<?php echo $row_status['status_id']; ?>"><a href="tickets.php?a=search&deptId=<?php echo $row_dept['dept_id']; ?>&cstatus=<?php echo $row_sub_status_inner['status_id'].$date_range; ?>"> <?php echo $num_status_comp_inner; 
				?> </a></td>
            <?php }	?>
            <?php $i++; }  ?>
       
          </tr>
          <?php  } 	?>
          
          <?php } ?>
        </tbody>
        <?php } else{ ?>
		<tbody><tr><td colspan="10"> All Ready Record Enter for this month!!!</td></tr></tbody>	
		<?php 	}?>
      </table>
    </div>
  </div>
</div>
</div>
</div>
