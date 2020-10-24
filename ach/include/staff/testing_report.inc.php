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
if($_POST['from_date']!='' && $_POST['to_date']!='')
{
$from_to_date = ' AND DATE(created) >= "'.date('Y-m-d',strtotime($_POST['from_date'])).'" AND DATE(created) <= "'.date('Y-m-d',strtotime($_POST['to_date'])).'"  ';
$date_range = '&startDate='.date('m/d/Y',strtotime($_REQUEST['from_date'])).'&endDate='.date('m/d/Y',strtotime($_REQUEST['to_date']));
}else{
$from_to_date ='';
$date_range = '';
}
if($_POST['broker_type']=='')
{
$broker_type = 'Commodities Broker';
$_POST['broker_type'] =  'Commodities Broker';
}else{
$broker_type = $_POST['broker_type'];
$_POST['broker_type'] =  $_POST['broker_type'];
}
?>
<div class="page-header">   
  <h1>Most Complaints Against <small> Capital Market</small></h1>
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
<th width="20%" style="padding-top:12px;">Select Broker</th>
<td>
<select name="broker_type" required>
<option value="Commodities Broker" <?php if($_POST['broker_type']=='Commodities Broker'){?> selected="selected"<?php } ?>>Commodities Broker</option>
<option value="Debt Security Trustee" <?php if($_POST['broker_type']=='Debt Security Trustee'){?> selected="selected"<?php } ?>>Debt Security Trustee</option>
<option value="Securities Broker" <?php if($_POST['broker_type']=='Securities Broker'){?> selected="selected"<?php } ?>>Securities Broker</option>
</select>
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
      <h1>Capital Market Most Complaints Against <?php echo $broker_type ;?></h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
          <thead>
            <tr>
              <th><?php echo $broker_type ;?></th>
              <th align="center">No. of Complaints</th>
              <th align="center">Open</th>
              <th align="center">Closed</th>
            </tr>
          </thead>
          <tbody class="" page="1">
            <?php 		
$sql_status="SELECT cm_broker_title as compay,count(cm_broker_title) as total ,group_concat( complaint_id ) as complaint_ids, group_concat( cm_broker_title ) AS companies FROM `sdms_ticket_capital_markets` WHERE cm_broker_title!='' AND cm_type = '".$broker_type."'  group by `cm_broker_title` order by total desc ";
$res_status=mysql_query($sql_status);
$num_status = mysql_num_rows($res_status);
if($num_status>0){
while($row_status=mysql_fetch_array($res_status)){

$sql_total = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") ";
$res_total = mysql_query($sql_total);
$num_total = mysql_num_rows($res_total);

$sql_open = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND status = 'open' AND ticket_id IN(".$row_status['complaint_ids'].") ";
$res_open = mysql_query($sql_open);
$num_open = mysql_num_rows($res_open);


$sql_closed = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND status = 'closed' AND ticket_id IN(".$row_status['complaint_ids'].") ";
$res_closed = mysql_query($sql_closed);
$num_closed = mysql_num_rows($res_closed);


if($num_total > 0){
?>
            <tr>
              <th><span style="float:left; width:350px;"  ><?php echo $row_status['compay'];?></span></th>
              <td><b><span align="right"><?php echo $num_total; $subnum_status_comp += $num_total; ?></span></b></td>
              <td><?php echo $num_open; $t_open += $num_open; ?></td>
              <td><?php echo $num_closed; $t_closed += $num_closed;?></td>
            </tr>
<?php } ?>            
            <?php }?>
            <tr id="total">
              <th><span style="float: left; width:350px;">Total</span></th>
              <td><span class="Icon <?php echo $icon;?>" align="right"><b><span align="right"><?php echo $subnum_status_comp; ?> </span></b><br />
                </span></td>
              <td><b><?php echo $t_open; ?></b></td>
              <td><b><?php echo $t_closed; ?></b></td>
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
