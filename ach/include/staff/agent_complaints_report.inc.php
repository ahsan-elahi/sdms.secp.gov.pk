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
if($thisstaff->isFocalPerson() == '1')
{
	$dept_add = ' AND dept_id = '.$thisstaff->getDeptId().'';
}
elseif(!$thisstaff->isAdmin() &&  $thisstaff->onChairman() == '1' )
{
	$dept_add = ' AND dept_id = '.$thisstaff->getDeptId().'';
}
?>
<div class="page-header">   
  <h1>Most Complaints Against by <small> Agent</small></h1>
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
      <h1>Capital Market Most Complaints Against by Agent</h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
          <thead>
            <tr>
              <th rowspan="2">Agent Name </th>
              <th rowspan="2" align="center">No. of Complaints</th>
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
$sql_status="SELECT `cm_broker_agent` as agent,count(`cm_broker_agent`) as total ,group_concat( complaint_id ) as complaint_ids, group_concat( `cm_broker_agent` ) AS agents FROM `sdms_ticket_capital_markets` WHERE cm_broker_agent!='' group by `cm_broker_agent` order by total desc limit 10";
$res_status=mysql_query($sql_status);
$num_status = mysql_num_rows($res_status);
if($num_status>0){

$subt_1to15days = 0;
$subt_16to30days = 0;
$subt_31to45days = 0;
$subt_45daysplus = 0;	
$subnum_status_comp = 0;

while($row_status=mysql_fetch_array($res_status)){
$today_date =  date('Y-n-j'); 
$t_1to15days = 0;
$t_16to30days = 0;
$t_31to45days = 0;
$t_45daysplus = 0;
$num_status_comp = 0;
$num_sub_status = $row_status['total'];

//echo "first loop 1 to 15 Days";
for($i=1;$i<16;$i++)
{
$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days += mysql_num_rows($res_1to15days);

$today_date = date ("Y-n-j", strtotime("-1 day", strtotime($today_date)));
}
//echo "second loop 15+ Days";
for($i=1;$i<16;$i++)
{
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days += mysql_num_rows($res_16to30days);
//echo $today_date.'<br>';
$today_date = date ("Y-n-j", strtotime("-1 day", strtotime($today_date)));

}
//echo "second loop 45+ Days";
for($i=1;$i<16;$i++)
{
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days += mysql_num_rows($res_31to45days);
//echo $today_date.'<br>';
$today_date = date ("Y-n-j", strtotime("-1 day", strtotime($today_date)));

}
$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") AND DATE(created) <= '".$today_date."' ".$dept_add."";
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus += mysql_num_rows($res_45daysplus);

?>
            <tr>
              <th><span style="float:left; width:350px;"  ><?php echo $row_status['agent'];?></span></th>
              <td><b><span align="right"><?php echo $num_sub_status; $subnum_status_comp +=$num_sub_status; ?></span></b></td>
              <td><?php echo $t_1to15days;  $subt_1to15days += $t_1to15days;  ?></td>
              <td><?php echo $t_16to30days; $subt_16to30days += $t_16to30days;?></td>
              <td><?php echo $t_31to45days; $subt_31to45days += $t_31to45days;?></td>
              <td><?php echo $t_45daysplus; $subt_45daysplus += $t_45daysplus;?></td>
            </tr>
            <?php }?>
            <tr id="total">
              <th><span style="float: left; width:350px;">Total</span></th>
              <td><span class="Icon <?php echo $icon;?>" align="right"><b><span align="right"><?php echo $subnum_status_comp; ?> </span></b><br />
                </span></td>
              <td><b><?php echo $subt_1to15days; ?></b></td>
              <td><b><?php echo $subt_16to30days; ?></b></td>
              <td><b><?php echo $subt_31to45days; ?></b></td>
              <td><b><?php echo $subt_45daysplus; ?></b></td>
            </tr>
            <?php }else{?>
            <tr class="<?php echo $class?>">
              <td><b>No Record Found</b></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
    </div>
  </div>
</div>

<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Insurance Most Complaints Against by Agent</h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
          <thead>
            <tr>
              <th rowspan="2">Agent Name </th>
              <th rowspan="2" align="center">No. of Complaints</th>
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
$sql_status="SELECT `i_broker_agent` as agent,count(`i_broker_agent`) as total ,group_concat( complaint_id ) as complaint_ids, group_concat( `i_broker_agent` ) AS agents FROM `sdms_ticket_insurance` WHERE i_broker_agent!='' group by `i_broker_agent` order by total desc limit 10";
$res_status=mysql_query($sql_status);
$num_status = mysql_num_rows($res_status);
if($num_status>0){

$subt_1to15days = 0;
$subt_16to30days = 0;
$subt_31to45days = 0;
$subt_45daysplus = 0;	
$subnum_status_comp = 0;

while($row_status=mysql_fetch_array($res_status)){
$today_date =  date('Y-n-j'); 
$t_1to15days = 0;
$t_16to30days = 0;
$t_31to45days = 0;
$t_45daysplus = 0;
$num_status_comp = 0;
$num_sub_status = $row_status['total'];

//echo "first loop 1 to 15 Days";
for($i=1;$i<16;$i++)
{
$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days += mysql_num_rows($res_1to15days);

$today_date = date ("Y-n-j", strtotime("-1 day", strtotime($today_date)));
}
//echo "second loop 15+ Days";
for($i=1;$i<16;$i++)
{
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days += mysql_num_rows($res_16to30days);
//echo $today_date.'<br>';
$today_date = date ("Y-n-j", strtotime("-1 day", strtotime($today_date)));

}
//echo "second loop 45+ Days";
for($i=1;$i<16;$i++)
{
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days += mysql_num_rows($res_31to45days);
//echo $today_date.'<br>';
$today_date = date ("Y-n-j", strtotime("-1 day", strtotime($today_date)));

}
$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") AND DATE(created) <= '".$today_date."' ".$dept_add."";
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus += mysql_num_rows($res_45daysplus);

?>
            <tr>
              <th><span style="float:left; width:350px;"  ><?php echo $row_status['agent'];?></span></th>
              <td><b><span align="right"><?php echo $num_sub_status; $subnum_status_comp +=$num_sub_status; ?></span></b></td>
              <td><?php echo $t_1to15days;  $subt_1to15days += $t_1to15days;  ?></td>
              <td><?php echo $t_16to30days; $subt_16to30days += $t_16to30days;?></td>
              <td><?php echo $t_31to45days; $subt_31to45days += $t_31to45days;?></td>
              <td><?php echo $t_45daysplus; $subt_45daysplus += $t_45daysplus;?></td>
            </tr>
            <?php }?>
            <tr id="total">
              <th><span style="float: left; width:350px;">Total</span></th>
              <td><span class="Icon <?php echo $icon;?>" align="right"><b><span align="right"><?php echo $subnum_status_comp; ?> </span></b><br />
                </span></td>
              <td><b><?php echo $subt_1to15days; ?></b></td>
              <td><b><?php echo $subt_16to30days; ?></b></td>
              <td><b><?php echo $subt_31to45days; ?></b></td>
              <td><b><?php echo $subt_45daysplus; ?></b></td>
            </tr>
            <?php }else{?>
            <tr class="<?php echo $class?>">
              <td><b>No Record Found</b></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
    </div>
  </div>
</div>
<div class="dr"><span></span></div>
</div>
<!--WorkPlace End-->
</div>
