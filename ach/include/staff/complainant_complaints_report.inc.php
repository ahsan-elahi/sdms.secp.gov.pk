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
  <h1>Most Complaints Agianst by a<small> Complainant</small></h1>
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
      <h1>Most Complaints Agianst by a Complainant</h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
          <thead>
            <tr>
              <th rowspan="2">Complainant Name </th>
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
while($row_sub_status=mysql_fetch_array($res_sub_status)){}
?>
            <tr>
              <th><span style="float:left; width:350px;"  ><?php echo $row_status['status_title'];?></span></th>
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
