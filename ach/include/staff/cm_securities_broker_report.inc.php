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
if($_POST['from_date']!='' && $_POST['to_date']!='')
{
$from_to_date = ' AND DATE(created) >= "'.date('Y-m-d',strtotime($_POST['from_date'])).'" AND DATE(created) <= "'.date('Y-m-d',strtotime($_POST['to_date'])).'"  ';
$date_range = '&startDate='.date('m/d/Y',strtotime($_REQUEST['from_date'])).'&endDate='.date('m/d/Y',strtotime($_REQUEST['to_date']));
}else{
$from_to_date ='';
$date_range = '';
}
?>
<div class="page-header">   
  <h1>Most Complaints Against <small> Capital Market</small></h1>
</div>

<?php
$company_types = array('Securities Broker');
foreach($company_types as $company_type){	
$broker_title = "cm_broker_title!=''";
?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Most Complaints Against <?php echo $company_type; ?></h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
          <thead>
            <tr>
              <th rowspan="2"><?php echo $company_type; ?> Name </th>
              <th rowspan="2" align="center">No. of Complaints</th>
              <th colspan="4" align="center">Status</th>
            </tr>
            <tr>
              <th>Open</th>
              <th>Closed</th>
            </tr>
          </thead>
          <tbody class="" page="1">
			<?php 		
		    $sql_status="SELECT cm_broker_title as compay,count(cm_broker_title) as total ,group_concat( complaint_id ) as complaint_ids, group_concat( cm_broker_title) AS companies FROM `sdms_ticket_capital_markets` WHERE ".$broker_title." AND cm_type = '".$company_type."'   group by `cm_broker_title`  order by total desc";			
            $res_status=mysql_query($sql_status);
            $num_status = mysql_num_rows($res_status);
            if($num_status>0){
            $subt_1to15days = 0;
            $subt_16to30days = 0;
            $subt_31to45days = 0;
            $subt_45daysplus = 0;	
            $subnum_status_comp = 0;
            while($row_status=mysql_fetch_array($res_status)){
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
            $num_total = 0;
            
            $sql_total = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") ".$dept_add." ".$from_to_date."";
            $res_total = mysql_query($sql_total);
            $num_total += mysql_num_rows($res_total);            
            //echo "first loop 1 to 15 Days";
            
            
            $sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND status = 'open' AND  ticket_id IN(".$row_status['complaint_ids'].") 
            ".$dept_add." ".$from_to_date."";
            $res_1to15days = mysql_query($sql_1to15days);
            $t_1to15days = mysql_num_rows($res_1to15days);
            
            
            //echo "second loop 15+ Days";
            $sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND status = 'closed' AND ticket_id IN(".$row_status['complaint_ids'].") 
            ".$dept_add." ".$from_to_date."";
            $res_16to30days = mysql_query($sql_16to30days);
            $t_16to30days = mysql_num_rows($res_16to30days);
            
            
            
            //echo "second loop 45+ Days";
            $sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") 
            AND DATE(created) <= '".$days_15plus."'
            AND DATE(created) > '".$days_30plus."'
            ".$dept_add." ".$from_to_date."";
            $res_31to45days = mysql_query($sql_31to45days);
            $t_31to45days = mysql_num_rows($res_31to45days);
            
            $sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") 
            AND DATE(created) <= '".$days_30plus."'
            ".$dept_add." ".$from_to_date."";
            $res_45daysplus = mysql_query($sql_45daysplus);
            $t_45daysplus = mysql_num_rows($res_45daysplus);
            
            $href_company = 'report_tickets.php?action=cm_company&company_type='.urlencode($company_type).'&compay='.urlencode($row_status['compay']).'';
            $href_company_1to15days = $href_company.'&r_type=1to15days&from_date='.$today_date.'&to_date='.$days_1to15.'';
            $href_company_16to30days = $href_company.'&r_type=16to30days&from_date='.$days_1to15.'&to_date='.$days_15plus.'';
            $href_company_31to45days = $href_company.'&r_type=31to45days&from_date='.$days_15plus.'&to_date='.$days_30plus.'';
            $href_company_45daysplus = $href_company.'&r_type=45daysplus&from_date='.$days_30plus.'';
            
            $href_type = 'report_tickets.php?action=cm_company_subtotal&company_type='.urlencode($company_type).'';
            $href_type_1to15days =$href_type.'&r_type=1to15days&from_date='.$today_date.'&to_date='.$days_1to15.'';
            $href_type_16to30days =$href_type.'&r_type=16to30days&from_date='.$days_1to15.'&to_date='.$days_15plus.'';
            $href_type_31to45days =$href_type.'&r_type=31to45days&from_date='.$days_15plus.'&to_date='.$days_30plus.'';
            $href_type_45daysplus =$href_type.'&r_type=45daysplus&from_date='.$days_30plus.'';
            
            if($row_status['compay']!='')
            $compay = $row_status['compay'];
            else
            $compay = $company_type;
            ?>
            <tr>
            <th><span style="float:left; width:350px;"  ><?php echo $compay;?></span></th>
            <td>
            <b><span align="right"><?php echo $num_total; $subnum_status_comp += $num_total; ?></span></b>
            </td>
            <td><?php echo $t_1to15days;  $subt_1to15days += $t_1to15days;  ?></td>
            <td><?php echo $t_16to30days; $subt_16to30days += $t_16to30days;?></td>
            </tr>
            <?php }?>
            <tr id="total">
            <th><span style="float: left; width:350px;">Total</span></th>
            <td><b><span align="right"><?php echo $subnum_status_comp; ?> </span></b><br />
            </span></td>
            <td><b><?php echo $subt_1to15days; ?></b></td>
            <td><b><?php echo $subt_16to30days; ?></b></td>
            </tr>
            <?php }
            else{?>
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
<?php }?>
</div>
