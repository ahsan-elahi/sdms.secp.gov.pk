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
  <h1>Most Complaints Against <small> Company Registration/Compliance</small></h1>
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
<h1>Search</h1>                          
</div>
<div class="block-fluid table-sorting clearfix">
<form action="" method="post" id="save" enctype="multipart/form-data">
<?php csrf_token(); ?>
<table cellpadding="0" cellspacing="0" width="100%" class="table"  >
<tr>


<th width="20%" style="padding-top:12px;">From Date</th>
<td>
<input type="text" name="from_date" id="Datepicker" required value="<?php echo $_POST['from_date']; ?>" >
</td>
<th width="20%" style="padding-top:12px;">To Date</th>
<td>
<input type="text" name="to_date" id="Datepicker1" required value="<?php echo $_POST['to_date']; ?>" >
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
<?php
$sql_group_concat_max_len = "SET SESSION group_concat_max_len = 1000000";
mysql_query($sql_group_concat_max_len);
$broker_title = "cr_company_title!=''";
?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Most Complaints Against Company</h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
          <thead>
            <tr>
              <th rowspan="2">Company Name </th>
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
		    $sql_status="SELECT cr_company_title as compay,count(cr_company_title) as total ,group_concat( complaint_id ) as complaint_ids, group_concat( cr_company_title) AS companies FROM `sdms_ticket_cr` WHERE ".$broker_title." group by `cr_company_title`  order by total desc limit 10 ";
			
            $res_status=mysql_query($sql_status);
            $num_status = mysql_num_rows($res_status);
            if($num_status>0){
            $subt_1to15days = 0;
            $subt_16to30days = 0;
            $subt_31to45days = 0;
            $subt_45daysplus = 0;	
            $subnum_status_comp = 0;
            while($row_status = mysql_fetch_array($res_status)){
				
			
			
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
            
            
            $sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") 
            AND DATE(created) <= '".$today_date."'
            AND DATE(created) > '".$days_1to15."'
            ".$dept_add." ".$from_to_date."";
            $res_1to15days = mysql_query($sql_1to15days);
            $t_1to15days = mysql_num_rows($res_1to15days);
            
            
            //echo "second loop 15+ Days";
            $sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") 
            AND DATE(created) <= '".$days_1to15."'
            AND DATE(created) > '".$days_15plus."'
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
			
			if($row_status['compay']!='')
            $compay = trim($row_status['compay']);
            else
            $compay = 'Company';
            
            $href_company = 'report_tickets.php?action=cr_company&compay='.urlencode($compay).'';
            $href_company_1to15days = $href_company.'&r_type=1to15days&from_date='.$today_date.'&to_date='.$days_1to15.'';
            $href_company_16to30days = $href_company.'&r_type=16to30days&from_date='.$days_1to15.'&to_date='.$days_15plus.'';
            $href_company_31to45days = $href_company.'&r_type=31to45days&from_date='.$days_15plus.'&to_date='.$days_30plus.'';
            $href_company_45daysplus = $href_company.'&r_type=45daysplus&from_date='.$days_30plus.'';
            
            $href_type = 'report_tickets.php?action=cr_company_subtotal';
            $href_type_1to15days =$href_type.'&r_type=1to15days&from_date='.$today_date.'&to_date='.$days_1to15.'';
            $href_type_16to30days =$href_type.'&r_type=16to30days&from_date='.$days_1to15.'&to_date='.$days_15plus.'';
            $href_type_31to45days =$href_type.'&r_type=31to45days&from_date='.$days_15plus.'&to_date='.$days_30plus.'';
            $href_type_45daysplus =$href_type.'&r_type=45daysplus&from_date='.$days_30plus.'';
            
            
            ?>
            <tr>
            <th><span style="float:left; width:350px;"  ><?php echo $compay;?></span></th>
            <td>
            <a href="<?php echo $href_company; ?>">
            <b><span align="right"><?php echo $num_total; $subnum_status_comp += $num_total; ?></span></b>
            </a>
            </td>
            <td><a href="<?php echo $href_company_1to15days; ?>"><?php echo $t_1to15days;  $subt_1to15days += $t_1to15days;  ?></a></td>
            <td><a href="<?php echo $href_company_16to30days; ?>"><?php echo $t_16to30days; $subt_16to30days += $t_16to30days;?></a></td>
            <td><a href="<?php echo $href_company_31to45days; ?>"><?php echo $t_31to45days; $subt_31to45days += $t_31to45days;?></a></td>
            <td><a href="<?php echo $href_company_45daysplus; ?>"><?php echo $t_45daysplus; $subt_45daysplus += $t_45daysplus;?></a></td>
            </tr>
            <?php }?>
            <tr id="total">
            <th><span style="float: left; width:350px;">Total</span></th>
            <td><a href="<?php echo $href_type; ?>"><span class="Icon <?php echo $icon;?>" align="right"><b><span align="right"><?php echo $subnum_status_comp; ?> </span></b><br />
            </span></a></td>
            <td><a href="<?php echo $href_type_1to15days; ?>"><b><?php echo $subt_1to15days; ?></b></a></td>
            <td><a href="<?php echo $href_type_16to30days; ?>"><b><?php echo $subt_16to30days; ?></b></a></td>
            <td><a href="<?php echo $href_type_31to45days; ?>"><b><?php echo $subt_31to45days; ?></b></a></td>
            <td><a href="<?php echo $href_type_45daysplus; ?>"><b><?php echo $subt_45daysplus; ?></b></a></td>
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
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Most Complaints Against CRO</h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
          <thead>
            <tr>
              <th rowspan="2">CRO Name </th>
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
$sql_status="SELECT `cr_cro` as agent,count(`cr_cro`) as total ,group_concat( complaint_id ) as complaint_ids, group_concat( `cr_cro` ) AS agents FROM `sdms_ticket_cr` WHERE cr_cro!='' group by `cr_cro` order by total desc limit 10";
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


$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") 
AND DATE(created) <= '".$today_date."'
AND DATE(created) > '".$days_1to15."'
 ".$dept_add." ".$from_to_date."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days = mysql_num_rows($res_1to15days);


//echo "second loop 15+ Days";
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") 
AND DATE(created) <= '".$days_1to15."'
AND DATE(created) > '".$days_15plus."'
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

$href_company = 'report_tickets.php?action=cr_agent&agent='.urlencode($row_status['agent']).'';
$href_company_1to15days = $href_company.'&r_type=1to15days&from_date='.$today_date.'&to_date='.$days_1to15.'';
$href_company_16to30days = $href_company.'&r_type=16to30days&from_date='.$days_1to15.'&to_date='.$days_15plus.'';
$href_company_31to45days = $href_company.'&r_type=31to45days&from_date='.$days_15plus.'&to_date='.$days_30plus.'';
$href_company_45daysplus = $href_company.'&r_type=45daysplus&from_date='.$days_30plus.'';

$href_type = 'report_tickets.php?action=cr_agent_subtotal';
$href_type_1to15days =$href_type.'&r_type=1to15days&from_date='.$today_date.'&to_date='.$days_1to15.'';
$href_type_16to30days =$href_type.'&r_type=16to30days&from_date='.$days_1to15.'&to_date='.$days_15plus.'';
$href_type_31to45days =$href_type.'&r_type=31to45days&from_date='.$days_15plus.'&to_date='.$days_30plus.'';
$href_type_45daysplus =$href_type.'&r_type=45daysplus&from_date='.$days_30plus.'';
?>
            <tr>
              <th><span style="float:left; width:350px;"  ><?php echo $row_status['agent'];?></span></th>
              <td>
              <a href="<?php echo $href_company; ?>">
              <b><span align="right"><?php echo $num_total; $subnum_status_comp += $num_total; ?></span></b>
              </a>
              </td>
              <td><a href="<?php echo $href_company_1to15days; ?>"><?php echo $t_1to15days;  $subt_1to15days += $t_1to15days;  ?></a></td>
              <td><a href="<?php echo $href_company_16to30days; ?>"><?php echo $t_16to30days; $subt_16to30days += $t_16to30days;?></a></td>
              <td><a href="<?php echo $href_company_31to45days; ?>"><?php echo $t_31to45days; $subt_31to45days += $t_31to45days;?></a></td>
              <td><a href="<?php echo $href_company_45daysplus; ?>"><?php echo $t_45daysplus; $subt_45daysplus += $t_45daysplus;?></a></td>
            </tr>
            <?php }?>
            <tr id="total">
              <th><span style="float: left; width:350px;">Total</span></th>
              <td><a href="<?php echo $href_type; ?>"><span class="Icon <?php echo $icon;?>" align="right"><b><span align="right"><?php echo $subnum_status_comp; ?> </span></b><br />
                </span></a></td>
              <td><a href="<?php echo $href_type_1to15days; ?>"><b><?php echo $subt_1to15days; ?></b></a></td>
              <td><a href="<?php echo $href_type_16to30days; ?>"><b><?php echo $subt_16to30days; ?></b></a></td>
              <td><a href="<?php echo $href_type_31to45days; ?>"><b><?php echo $subt_31to45days; ?></b></a></td>
              <td><a href="<?php echo $href_type_45daysplus; ?>"><b><?php echo $subt_45daysplus; ?></b></a></td>
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
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Company Registration/Compliance Most Complaints By Complainant</h1>
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
$sql_status="SELECT  count(ticket_id) as total,group_concat( ticket_id ) as complaint_ids,`email` FROM `sdms_ticket` WHERE  email!='' AND dept_id = '6' AND isquery=0 ".$from_to_date." AND email NOT LIKE '%@novalidemail.pk' group by `email` order by total desc limit 0,5";	
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
$row_total = mysql_fetch_array($res_total);
$num_total += mysql_num_rows($res_total);

$complainant = $row_total['name'];

//echo "first loop 1 to 15 Days";


$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") 
AND DATE(created) <= '".$today_date."'
AND DATE(created) > '".$days_1to15."'
 ".$dept_add." ".$from_to_date."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days = mysql_num_rows($res_1to15days);


//echo "second loop 15+ Days";
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") 
AND DATE(created) <= '".$days_1to15."'
AND DATE(created) > '".$days_15plus."'
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

$href_company = 'report_tickets.php?action=cr_complainant&email='.urlencode($row_status['email']).'';
$href_company_1to15days = $href_company.'&r_type=1to15days&from_date='.$today_date.'&to_date='.$days_1to15.'';
$href_company_16to30days = $href_company.'&r_type=16to30days&from_date='.$days_1to15.'&to_date='.$days_15plus.'';
$href_company_31to45days = $href_company.'&r_type=31to45days&from_date='.$days_15plus.'&to_date='.$days_30plus.'';
$href_company_45daysplus = $href_company.'&r_type=45daysplus&from_date='.$days_30plus.'';

$href_type = 'report_tickets.php?action=cr_complainant_subtotal';
$href_type_1to15days =$href_type.'&r_type=1to15days&from_date='.$today_date.'&to_date='.$days_1to15.'';
$href_type_16to30days =$href_type.'&r_type=16to30days&from_date='.$days_1to15.'&to_date='.$days_15plus.'';
$href_type_31to45days =$href_type.'&r_type=31to45days&from_date='.$days_15plus.'&to_date='.$days_30plus.'';
$href_type_45daysplus =$href_type.'&r_type=45daysplus&from_date='.$days_30plus.'';
?>
            <tr>
              <th><span style="float:left; width:350px;"  ><?php echo $complainant;?></span></th>
              <td>
              <a href="<?php echo $href_company; ?>">
              <b><span align="right"><?php echo $num_total; $subnum_status_comp += $num_total; ?></span></b>
              </a>
              </td>
              <td><a href="<?php echo $href_company_1to15days; ?>"><?php echo $t_1to15days;  $subt_1to15days += $t_1to15days;  ?></a></td>
              <td><a href="<?php echo $href_company_16to30days; ?>"><?php echo $t_16to30days; $subt_16to30days += $t_16to30days;?></a></td>
              <td><a href="<?php echo $href_company_31to45days; ?>"><?php echo $t_31to45days; $subt_31to45days += $t_31to45days;?></a></td>
              <td><a href="<?php echo $href_company_45daysplus; ?>"><?php echo $t_45daysplus; $subt_45daysplus += $t_45daysplus;?></a></td>
            </tr>
            <?php }?>
            <tr id="total">
              <th><span style="float: left; width:350px;">Total</span></th>
              <td><a href="<?php echo $href_type; ?>"><span class="Icon <?php echo $icon;?>" align="right"><b><span align="right"><?php echo $subnum_status_comp; ?> </span></b><br />
                </span></a></td>
              <td><a href="<?php echo $href_type_1to15days; ?>"><b><?php echo $subt_1to15days; ?></b></a></td>
              <td><a href="<?php echo $href_type_16to30days; ?>"><b><?php echo $subt_16to30days; ?></b></a></td>
              <td><a href="<?php echo $href_type_31to45days; ?>"><b><?php echo $subt_31to45days; ?></b></a></td>
              <td><a href="<?php echo $href_type_45daysplus; ?>"><b><?php echo $subt_45daysplus; ?></b></a></td>
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

<?php /*?><div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Most Complaints Against Company</h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
          <thead>
            <tr>
              <th rowspan="2">Company Name </th>
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
$sql_status="SELECT cr_company_title as compay,count(cr_company_title) as total ,group_concat( complaint_id ) as complaint_ids, group_concat( cr_company_title ) AS companies FROM `sdms_ticket_cr` WHERE cr_company_title!=''  group by `cr_company_title` order by total desc limit 10 ";
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
$s_1_day =   date('Y-m-d',strtotime($_POST['to_date'])); 
}else{
$today_date =  date('Y-m-d'); 
$s_1_day =  date('Y-m-d'); 
}
$t_1to15days = 0;
$t_16to30days = 0;
$t_31to45days = 0;
$t_45daysplus = 0;
$num_total = 0;

//$num_total =  $row_status['total'];
$sql_total = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") ".$dept_add." ".$from_to_date."";
$res_total = mysql_query($sql_total);
$num_total += mysql_num_rows($res_total);


//echo "first loop 1 to 15 Days";
for($i=1;$i<16;$i++)
{
$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days += mysql_num_rows($res_1to15days);

$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}
//echo "second loop 15+ Days";
for($i=1;$i<16;$i++)
{
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days += mysql_num_rows($res_16to30days);
//echo $today_date.'<br>';
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));

}
//echo "second loop 45+ Days";
for($i=1;$i<16;$i++)
{
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days += mysql_num_rows($res_31to45days);
//echo $today_date.'<br>';
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));

}
$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") AND DATE(created) <= '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus += mysql_num_rows($res_45daysplus);

?>
            <tr>
              <th><span style="float:left; width:350px;"  ><?php echo $row_status['compay'];?></span></th>
              <td><b><span align="right"><?php echo $num_total; $subnum_status_comp += $num_total; ?></span></b></td>
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
      <h1>Most Complaints Against CRO</h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
          <thead>
            <tr>
              <th rowspan="2">CRO Name </th>
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
$sql_status="SELECT `cr_cro` as agent,count(`cr_cro`) as total ,group_concat( complaint_id ) as complaint_ids, group_concat( `cr_cro` ) AS agents FROM `sdms_ticket_cr` WHERE cr_cro!='' group by `cr_cro` order by total desc limit 10";
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
$s_1_day =   date('Y-m-d',strtotime($_POST['to_date'])); 
}else{
$today_date =  date('Y-m-d'); 
$s_1_day =  date('Y-m-d'); 
}
$t_1to15days = 0;
$t_16to30days = 0;
$t_31to45days = 0;
$t_45daysplus = 0;
$num_total = 0;
//$num_total = $row_status['total'];
$sql_total = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") ".$dept_add." ".$from_to_date."";
$res_total = mysql_query($sql_total);
$num_total += mysql_num_rows($res_total);

//echo "first loop 1 to 15 Days";
for($i=1;$i<16;$i++)
{
$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days += mysql_num_rows($res_1to15days);

$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}
//echo "second loop 15+ Days";
for($i=1;$i<16;$i++)
{
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days += mysql_num_rows($res_16to30days);
//echo $today_date.'<br>';
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));

}
//echo "second loop 45+ Days";
for($i=1;$i<16;$i++)
{
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days += mysql_num_rows($res_31to45days);
//echo $today_date.'<br>';
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));

}
$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") AND DATE(created) <= '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus += mysql_num_rows($res_45daysplus);

?>
            <tr>
              <th><span style="float:left; width:350px;"  ><?php echo $row_status['agent'];?></span></th>
              <td><b><span align="right"><?php echo $num_total; $subnum_status_comp +=$num_total; ?></span></b></td>
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
      <h1>Company Registration/Compliance Most Complaints By Complainant</h1>
    </div>
    <div class="block-fluid table-sorting clearfix">
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
          <thead>
            <tr>
              <th rowspan="2">Complainant Name </th>
              <th rowspan="2" align="center">No. of Complaints</th>
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
$sql_status="SELECT  count(ticket_id) as total,group_concat( ticket_id ) as complaint_ids,`email` FROM `sdms_ticket` WHERE  email!='' AND dept_id = '6' AND isquery=0 ".$from_to_date." AND email NOT LIKE '%@novalidemail.pk' group by `email` order by total desc limit 0,5";

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
$s_1_day =   date('Y-m-d',strtotime($_POST['to_date'])); 
}else{
$today_date =  date('Y-m-d'); 
$s_1_day =  date('Y-m-d'); 
}
$t_1to15days = 0;
$t_16to30days = 0;
$t_31to45days = 0;
$t_45daysplus = 0;
$num_total = 0;

//$num_total =  $row_status['total'];
$sql_total = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") ".$dept_add." ".$from_to_date."";
$res_total = mysql_query($sql_total);
$row_total = mysql_fetch_array($res_total);
$num_total += mysql_num_rows($res_total);

//echo "first loop 1 to 15 Days";
for($i=1;$i<16;$i++)
{
$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days += mysql_num_rows($res_1to15days);

$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));
}
//echo "second loop 15+ Days";
for($i=1;$i<16;$i++)
{
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days += mysql_num_rows($res_16to30days);
//echo $today_date.'<br>';
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));

}
//echo "second loop 45+ Days";
for($i=1;$i<16;$i++)
{
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") AND DATE(created) = '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days += mysql_num_rows($res_31to45days);
//echo $today_date.'<br>';
$today_date = date ("Y-m-d", strtotime("-1 day", strtotime($today_date)));

}
$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND ticket_id IN(".$row_status['complaint_ids'].") AND DATE(created) <= '".$today_date."' ".$dept_add." ".$from_to_date."";
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus += mysql_num_rows($res_45daysplus);
if($num_total > 0){

?>
            <tr>
              <th><span style="float:left; width:350px;"  ><?php echo $row_total['name'];?></span></th>
              <td><b><span align="right"><?php echo $num_total; $subnum_status_comp += $num_total; ?></span></b></td>
              <td><?php echo $t_1to15days;  $subt_1to15days += $t_1to15days;  ?></td>
              <td><?php echo $t_16to30days; $subt_16to30days += $t_16to30days;?></td>
              <td><?php echo $t_31to45days; $subt_31to45days += $t_31to45days;?></td><td><?php echo $t_45daysplus; $subt_45daysplus += $t_45daysplus;?></td>
            </tr>
<?php } ?>            
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
</div><?php */?>





<div class="dr"><span></span></div>
</div>
<!--WorkPlace End-->
</div>
