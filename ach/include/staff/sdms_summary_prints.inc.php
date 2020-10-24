<?php error_reporting(0); ?>
<link href="css/stylesheets.css" rel="stylesheet" type="text/css" />
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');				
?>
<div class="page-header"><h1>Complaints  <small> Categories Count</small></h1></div>
<div class="row-fluid">
<!--<div class="span3" style="float:right;">
    <p align="right" style="float:right;">
     <a id="ticket-print" class="action-button" href="" onclick="openWin();">
     <button class="btn" type="button"><i class="icon-print"></i> Print</button></a>                           
    </p>             
</div>-->
</div>
    <div class="row-fluid">
        <div class="span12">                    
            <div class="head clearfix">
                <div class="isw-grid"></div>
                <h1><?php echo 'Character of  complaints'; ?></h1>                               
            </div>
            <div class="block-fluid table-sorting clearfix">
            <table cellpadding="0" cellspacing="0" width="100%" class="table" >							
            <thead>
            <tr>
            <th width="20%"><strong>Data</strong></th>
            <th width="32%"><strong>Type</strong></th>
            <th width="8%"><strong>Units/Numbers</strong></th>
            
            </tr>
            </thead>
            <tbody>          
            <tr>
            <th colspan="3">a) Complaint category</th>
            </tr>
            <?php 
			$m_sql="SELECT *  FROM sdms_help_topic WHERE topic_pid='0'";
			$m_res=mysql_query($m_sql);
			$total_complaint=0;
			while($m_row=mysql_fetch_array($m_res))
			{
				$sub_sql="SELECT *  FROM sdms_help_topic WHERE topic_pid='".$m_row['topic_id']."'";
				$sub_res=mysql_query($sub_sql);
				while($sub_row=mysql_fetch_array($sub_res))
				{
				if ($_SESSION['date']!='') {
				$qselect='SELECT count(ticket_id) as one_total FROM '.TICKET_TABLE.'  WHERE topic_id="'.$sub_row['topic_id'].'" AND MONTH(created)="'.$_SESSION['date'].'"';
				}
				else{
				$qselect='SELECT count(ticket_id) as one_total FROM '.TICKET_TABLE.'  WHERE topic_id="'.$sub_row['topic_id'].'"';
				}
				$qselect_res=mysql_query($qselect);
				$qselect_row=mysql_fetch_array($qselect_res);
				$total_complaint += $qselect_row['one_total'];
				}
				$net_total +=$total_complaint;
				echo '<tr><td></td><td>'.$m_row['topic'].'</td><td>'.$total_complaint.'</td></tr>';
				$total_complaint=0;
			}			
			?>
			<tr>
            <td colspan="2" style="text-align:right;"><b>TOTAL</b></td>
            <td><?php echo $net_total; ?></td>
            </tr>
            <tr>
            <th colspan="3">b) Gender of complainant</th>
            </tr>
			<?php 
			if ($_SESSION['date']!='') {
				$sql_gender='SELECT count(ticket_id) as gender_total ,gender FROM sdms_ticket Where MONTH(created)="'.$_SESSION['date'].'" group by gender ';
				}
				else{
			$sql_gender="SELECT count(ticket_id) as gender_total ,gender FROM sdms_ticket group by gender ";
				}
			$sql_gender_res=mysql_query($sql_gender);
			while($sql_gender_row=mysql_fetch_array($sql_gender_res))
			{
				if($sql_gender_row['gender']=='male')
				{
				$gender_title="MALE";
				}
				elseif($sql_gender_row['gender']=='female')
				{
				$gender_title="FEMALE";
				}
				elseif($sql_gender_row['gender']=='')
				{
				$gender_title="Other";
				}
				echo '<tr><td></td><td colspan="1">'.$gender_title.'</td><td>'.$sql_gender_row['gender_total'].'</td></tr>';
				$net_total_m_f +=$sql_gender_row['gender_total'];
			}
			?>
            <tr>
            <td colspan="2" style="text-align:right;"><b>TOTAL</b></td>
            <td><?php echo $net_total_m_f; ?></td>
            </tr>            
            <tr>
            <th colspan="3">c) Location of complainant</th>
            </tr>
            <?php 
				if ($_SESSION['date']!='') {
				$sql_location='SELECT count(ticket_id) as location_total ,applicant_location FROM sdms_ticket Where MONTH(created)="'.$_SESSION['date'].'" group by applicant_location ';
				}
				else
				{
			$sql_location="SELECT count(ticket_id) as location_total ,applicant_location FROM sdms_ticket group by applicant_location ";
				}
			$sql_location_res=mysql_query($sql_location);
			while($sql_location_row=mysql_fetch_array($sql_location_res))
			{
				if($sql_location_row['applicant_location']=='urban')
				{
				$location_title="Urban";
				}
				elseif($sql_location_row['applicant_location']=='rural')
				{
				$location_title="Rural";
				}
				elseif($sql_location_row['applicant_location']=='foreign')
				{
				$location_title="Foreign";
				}
				
				echo '<tr><td></td><td>'.$location_title.'</td><td>'.$sql_location_row['location_total'].'</td></tr>';
				$net_total_location +=$sql_location_row['location_total'];
			}
			?>
            <tr>
            <td colspan="2" style="text-align:right;"><b>TOTAL</b></td>
            <td><?php echo $net_total_location; ?></td>
            </tr>
            </tbody>
            </table>
            </div>
        </div>                      
    </div>    
    <div class="row-fluid">
        <div class="span12">                    
            <div class="head clearfix">
                <div class="isw-grid"></div>
                <h1><?php echo 'COMPLAINTS HANDLING'; ?></h1>                               
            </div>
            <div class="block-fluid table-sorting clearfix">
            <table cellpadding="0" cellspacing="0" width="100%" class="table" >							
            <thead>
            <tr>
            <td width="20%"><strong>Data</strong></td>
            <td width="32%"><strong>Type</strong></td>
            <td width="8%"><strong>Units/Numbers</strong></td>
            <td width="8%"><strong>Total</strong></td>            
            </tr>
            </thead>
            <tbody>
            <tr>
            <td>a) Total Complaints received</td>
            
            <?php 
			
			$sql_total="SELECT count(ticket_id) as total_complaint FROM sdms_ticket WHERE 1";
            $res_total=mysql_query($sql_total);
            $row_total=mysql_fetch_array($res_total);	
			
			if($_SESSION['date']!='')
            {
			$sql_this_month="SELECT count(ticket_id) as this_month FROM sdms_ticket WHERE Month(created)='".$_SESSION['date']."'";
			$res_this_month=mysql_query($sql_this_month);
            $row_this_month=mysql_fetch_array($res_this_month);	
			echo '<td>'.date('F', mktime(0, 0, 0, $_SESSION['date'], 10)).'</td>';
			}
			else
			{
			$today_date='NOW()';
			$start_thismonth = strtotime('first day of january');
            $start_thismonthdate=date("Y-m-d h:i:s",$start_thismonth);
			
			$sql_this_month="SELECT count(ticket_id) as this_month FROM sdms_ticket WHERE created>='".$start_thismonthdate."' AND created<='".$today_date."'";
            $res_this_month=mysql_query($sql_this_month);
            $row_this_month=mysql_fetch_array($res_this_month);	
			echo '<td>'.date("F",$start_thismonth).' to '.date("F");'</td>';
			}
			?>       
            <td><p align="center"><?php echo $row_this_month['this_month']; ?></p></td>       
            <td><?php echo $row_total['total_complaint']; ?></td>
            </tr>
            <tr>
            <td>b) Complaints accepted for follow-up</td>
            <?php 
			if($_SESSION['date']!='')
            {
			$sql_follow_up="SELECT count(ticket_id) as total_followup FROM sdms_ticket WHERE status='open' AND Month(created)='".$_SESSION['date']."'";
			$res_follow_up=mysql_query($sql_follow_up);
            $row_follow_up=mysql_fetch_array($res_follow_up);	
			echo '<td>'.date('F', mktime(0, 0, 0, $_SESSION['date'], 10)).'</td>';
			}
			else
			{
			$sql_follow_up="SELECT count(ticket_id) as total_followup FROM sdms_ticket WHERE status='open' ";
            $res_follow_up=mysql_query($sql_follow_up);
            $row_follow_up=mysql_fetch_array($res_follow_up);	
			echo '<td>'.date("F",$start_thismonth).' to '.date("F");'</td>';
			}
			?>       
            <td><p align="center"><?php echo $row_follow_up['total_followup']; ?></p></td>
            <td><?php echo $row_total['total_complaint']; ?></td>
            </tr>
            <tr>
            <td>c) Complaints Disposed Of</td>
            <?php 
			if($_SESSION['date']!='')
            {
			$sql_close="SELECT count(ticket_id) as total_closed FROM sdms_ticket WHERE status='closed' AND Month(created)='".$_SESSION['date']."'";
            $res_close=mysql_query($sql_close);
            $row_close=mysql_fetch_array($res_close);	
			echo '<td>'.date('F', mktime(0, 0, 0, $_SESSION['date'], 10)).'</td>';
			}
			else
			{
			$sql_close="SELECT count(ticket_id) as total_closed FROM sdms_ticket WHERE status='closed' ";
			$res_close=mysql_query($sql_close);
            $row_close=mysql_fetch_array($res_close);	
			echo '<td>'.date("F",$start_thismonth).' to '.date("F");'</td>';
			}?>
            <td><p align="center"><?php echo $row_close['total_closed']; ?></p></td>
            <td><?php echo $row_total['total_complaint']; ?></td>
            </tr>
            </tbody>
            </table>
            </div>
        </div>                      
    </div>
    <div class="row-fluid">
        <div class="span12">                    
            <div class="head clearfix">
                <div class="isw-grid"></div>
                <h1><?php echo 'Time between receipt and resolution of complaint'; ?></h1>                               
            </div>
            <div class="block-fluid table-sorting clearfix">
            <table cellpadding="0" cellspacing="0" width="100%" class="table" >							
            <thead>
            <tr>
            <td width="20%"><strong>Data</strong></td>
            <td width="32%"><strong>Minimum</strong></td>
            <td width="8%"><strong>Maximum</strong></td>
            <td width="8%"><strong>Average</strong></td>            
            </tr>
            </thead>
            <tbody>
            <tr>
            <td>Time between receipt and resolution of complaint</td>
            <?php 
			$min=0;
			$max=0;
			$avg=0;
			$sql_resolve="SELECT * FROM `sdms_ticket` WHERE status='closed'";
			$res_resolve=mysql_query($sql_resolve);
			while($row_resolve=mysql_fetch_array($res_resolve))
			{
			$sql_cal="SELECT * FROM `sdms_ticket` WHERE ticket_id='".$row_resolve['ticket_id']."'";
			$res_cal=mysql_query($sql_cal);
			$row_cal=mysql_fetch_array($res_cal);
			$start_time=$row_cal['created'];
			$end_time = $row_cal['closed']; 	
			if($start_time!='' && $end_time!='')
			{				
					$diff = abs(strtotime($end_time) - strtotime($start_time));
					//minimum value							
					if($min==0)
					{ 
					$min=$diff; }
					else if($min>$diff)
					{ $min=$diff;	}
					//maximum value						
					if($max==0)
					{ 
					$max=$diff;
					}
					else if($max<$diff)
					{ 
					$max=$diff;	
					}
					//echo '<br>'.$min;
				}
			}
			$average=floor(($min+$max)/2);
			
			$average_years   = floor($average / (365*60*60*24)); 				
			$average_months  = floor(($average - $average_years * 365*60*60*24) / (30*60*60*24)); 
			$average_days    = floor(($average - $average_years * 365*60*60*24 - $average_months*30*60*60*24)/ (60*60*24));
			$average_hours   = floor(($average - $average_years * 365*60*60*24 - $average_months*30*60*60*24 - $average_days*60*60*24)/ (60*60)); 
			$average_minuts  = floor(($average - $average_years * 365*60*60*24 - $average_months*30*60*60*24 - $average_days*60*60*24 - $average_hours*60*60)/ 60); 
			$average_seconds = floor(($average - $average_years * 365*60*60*24 - $average_months*30*60*60*24 - $average_days*60*60*24 - $average_hours*60*60 - $average_minuts*60));
			
			
			
			$years   = floor($min / (365*60*60*24)); 				
			$months  = floor(($min - $years * 365*60*60*24) / (30*60*60*24)); 
			$days    = floor(($min - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			$hours   = floor(($min - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
			$minuts  = floor(($min - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
			$seconds = floor(($min - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60));
			
			$years_max   = floor($max / (365*60*60*24)); 				
			$months_max  = floor(($max - $years_max * 365*60*60*24) / (30*60*60*24)); 
			$days_max    = floor(($max - $years_max * 365*60*60*24 - $months_max*30*60*60*24)/ (60*60*24));
			$hours_max   = floor(($max - $years_max * 365*60*60*24 - $months_max*30*60*60*24 - $days_max*60*60*24)/ (60*60)); 
			$minuts_max  = floor(($max - $years_max * 365*60*60*24 - $months_max*30*60*60*24 - $days_max*60*60*24 - $hours_max*60*60)/ 60); 
			$seconds_max = floor(($max - $years_max * 365*60*60*24 - $months_max*30*60*60*24 - $days_max*60*60*24 - $hours_max*60*60 - $minuts_max*60)); 
			//echo printf("%dY-%dM-%dD %dH:%dM:%dS\n", $years, $months, $days, $hours, $minuts, $seconds);
			//echo "<br>";
			//echo "============================================================================================<br><br>";
			//echo $seconds;exit;
			?>   
   			<td><?php echo $years.'Y-'.$months.'M-'.$days.'D '.$hours.'H:'.$minuts.'M:'.$seconds.'S';?></td>
   			<td><?php echo $years_max.'Y-'.$months_max.'M-'.$days_max.'D '.$hours_max.'H:'.$minuts_max.'M:'.$seconds_max.'S';?></td>
            <td><?php echo $average_years.'Y-'.$average_months.'M-'.$average_days.'D '.$average_hours.'H:'.$average_minuts.'M:'.$average_seconds.'S';?></td>

            </tr>
            

            </tbody>
            </table>
            </div>
        </div>                      
    </div>
    <!--<div class="row-fluid">
        <div class="span12">                    
            <div class="head clearfix">
                <div class="isw-grid"></div>
                <h1><?php echo 'Reaction to feedback'; ?></h1>                               
            </div>
            <div class="block-fluid table-sorting clearfix">
            <table cellpadding="0" cellspacing="0" width="100%" class="table" >							
            <thead>
            <tr>
            <td width="20%"><strong>Data</strong></td>
            <td width="32%"><strong>Type</strong></td>
            <td width="8%"><strong>Units/Numbers</strong></td>
            <td width="8%"><strong>Total</strong></td>            
            </tr>
            </thead>
            <tbody>
            <tr>
            <td>a) Feedback received by in­dividuals and  communities</td>
            <td>Number/Quarter</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>b) Feedback resulting in action</td>
            <td>Cases/Decisions</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            

            </tbody>
            </table>
            </div>
        </div>                      
    </div>-->
                       
		<div class="dr"><span></span></div>   
   </div><!--WorkPlace End-->  
   </div>  <!--		<tr>
            <td colspan="3"><u>Access to information</u></td>
            </tr>
            <tr>
            <td>a) Hits on website</td>
            <td>number/month</td>
            <td>&nbsp;</td>
            
            </tr>
            <tr>
            <td>b) Downloads</td>
            <td>number/month</td>
            <td>&nbsp;</td>
            
            </tr>
            <tr>
            <td>c) Length of stay on website</td>
            <td>minutes/website use</td>
            <td>&nbsp;</td>
            
            </tr>-->
            <script>
window.print() ;
//window.close();
</script>