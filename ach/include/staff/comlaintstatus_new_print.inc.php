<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
?>
<div style="margin-bottom:20px; padding-top:0px;">
<table width="100%" border="0" cellspacing=1 cellpadding=2>
<tr>
		<td>
       		<table width="100%" border="0" cellspacing=0 cellpadding=2 class="logs" align="center">
       			<tr>
				<th style="font-size:22px; color:blue;">Complaints Status Summary</th>
				</tr>
				</table>
   <div id="table-here">
    <form action="tickets.php" method="POST" name='tickets'>
    <input type="hidden" name="a" value="mass_process" >
   <table cellpadding="0" cellspacing="0" width="100%" class="table" border="1">
  <thead>
            <tr>
              <th rowspan="2">Status of Complaint</th>
              <th rowspan="2" align="center">Total Complaints</th>
              <th colspan="4" align="center">Aging-IN DAYS</th>
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
if($_REQUEST['dept_id']!='')
{
$dept_add .= ' AND dept_id = '.$_REQUEST['dept_id'].'';
}			
$sql_status="SELECT * FROM `sdms_status` WHERE p_id='0'";
$res_status=mysql_query($sql_status);
$num_status = mysql_num_rows($res_status);
if($num_status>0){

$subt_1to15days = 0;
$subt_16to30days = 0;
$subt_31to45days = 0;
$subt_45daysplus = 0;	
$subnum_status_comp = 0;

while($row_status=mysql_fetch_array($res_status)){
	
$t_1to15days = 0;
$t_16to30days = 0;
$t_31to45days = 0;
$t_45daysplus = 0;
$num_status_comp = 0;

$sql_sub_status="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
$res_sub_status=mysql_query($sql_sub_status);
$num_sub_status = mysql_num_rows($res_sub_status);
while($row_sub_status=mysql_fetch_array($res_sub_status)){
$today_date =  date('Y-m-d'); 
$sql_status_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' ".$dept_add."";
$res_status_comp = mysql_query($sql_status_comp);
$num_status_comp += mysql_num_rows($res_status_comp);

//echo "first loop 1 to 15 Days";
for($i=1;$i<16;$i++)
{
$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days += mysql_num_rows($res_1to15days);

$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}

//echo "second loop 15+ Days";
for($i=1;$i<16;$i++)
{
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days += mysql_num_rows($res_16to30days);
//echo $today_date.'<br>';
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}

//echo "second loop 45+ Days";
for($i=1;$i<16;$i++)
{
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days += mysql_num_rows($res_31to45days);
//echo $today_date.'<br>';
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}

$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) <= '".$today_date."' ".$dept_add."";
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus += mysql_num_rows($res_45daysplus);

}
?>
            <tr>
              <th> <a onclick="show_item(<?php echo $row_status['status_id']; ?>)" id='show_temp_item_<?php echo $row_status['status_id']; ?>'><span style="float:left; width:350px;"  ><?php echo $row_status['status_title'];?></span></a></th>
              <td><b><span align="right"><?php echo $num_status_comp; $subnum_status_comp +=$num_status_comp; ?></span></b></td>
              <td><?php echo $t_1to15days;  $subt_1to15days += $t_1to15days;  ?></td>
              <td><?php echo $t_16to30days; $subt_16to30days += $t_16to30days;?></td>
              <td><?php echo $t_31to45days; $subt_31to45days += $t_31to45days;?></td>
              <td><?php echo $t_31to45days; $subt_31to45days += $t_31to45days;?></td>
            </tr>
     
            <?php }?>
            <tr id="total">
              <th><span style="float: left; width:350px;">Total</span></th>
              <td><span class="Icon <?php echo $icon;?>" align="right"><b><span align="right"><?php echo $subnum_status_comp; ?> </span></b><br />
                </span></td>
              <td><b><?php echo $subt_1to15days; ?></b></td>
              <td><b><?php echo $subt_16to30days; ?></b></td>
              <td><b><?php echo $subt_31to45days; ?></b></td>
              <td><b><?php echo $subt_31to45days; ?></b></td>
            </tr>
            <?php }else{?>
            <tr class="<?php echo $class?>">
              <td><b>Query returned 0 results.</b></td>
            </tr>
            <?php } ?>
          </tbody>                 
        </table>
        </form>
        </div>
  </table>
<tr>
<td> 
</div>
</td>
</tr>
</table>
</td>
</tr>
</table>
</div>