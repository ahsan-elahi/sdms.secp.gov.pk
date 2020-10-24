<?php error_reporting(0); ?>

<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');				
?>
<div align="center" class="page-header"><h1>Complaints  <small> Categories Count</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">
</div>
</div>
    <div class="row-fluid">
        <div class="span12">                    
            <div class="head clearfix">
                <div class="isw-grid"></div>
                <h3><?php echo 'Character of  complaints'; ?></h3>                               
            </div>
            <div class="block-fluid table-sorting clearfix">
            <table cellpadding="0" cellspacing="0" width="100%" class="table" border="1" >							
            <thead>
            <tr>
            <th width="20%" align="left"><strong>Data</strong></th>
            <th width="32%" align="left"><strong>Type</strong></th>
            <th width="8%" align="left"><strong>Units/Numbers</strong></th>
            
            </tr>
            </thead>
            <tbody>          
            <tr>
            <th colspan="3"  align="left">a) Complaint category</th>
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
				$qselect='SELECT count(ticket_id) as one_total FROM '.TICKET_TABLE.'  WHERE topic_id="'.$sub_row['topic_id'].'"';
				$qselect_res=mysql_query($qselect);
				$qselect_row=mysql_fetch_array($qselect_res);
				$total_complaint += $qselect_row['one_total'];
				}
				$net_total +=$total_complaint;
				echo '<tr><td  align="left"></td><td  align="left">'.$m_row['topic'].'</td><td  align="left">'.$total_complaint.'</td></tr>';
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
			$sql_gender="SELECT count(ticket_id) as gender_total ,gender FROM sdms_ticket group by gender ";
			$sql_gender_res=mysql_query($sql_gender);
			while($sql_gender_row=mysql_fetch_array($sql_gender_res))
			{
				if($sql_gender_row['gender']=='')
				{
				$gender_title="Not Assigned";
				}
				elseif($sql_gender_row['gender']=='female')
				{
				$gender_title="FEMALE";
				}
				elseif($sql_gender_row['gender']=='male')
				{
				$gender_title="MALE";
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
			$sql_location="SELECT count(ticket_id) as location_total ,applicant_location FROM sdms_ticket group by applicant_location ";
			$sql_location_res=mysql_query($sql_location);
			while($sql_location_row=mysql_fetch_array($sql_location_res))
			{
				if($sql_location_row['applicant_location']=='')
				{
				$location_title="No Location";
				}
				elseif($sql_location_row['applicant_location']=='Urban')
				{
				$location_title="Urban";
				}
				elseif($sql_location_row['applicant_location']=='Rural')
				{
				$location_title="Rural";
				}
				elseif($sql_location_row['applicant_location']=='Foreign')
				{
				$location_title="Foreign";
				}
				
				echo '<tr><Td></td><td>'.$location_title.'</td><td>'.$sql_location_row['location_total'].'</td></tr>';
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
                <h3><?php echo 'COMPLAINTS HANDLING'; ?></h3>                               
            </div>
            <div class="block-fluid table-sorting clearfix">
            <table cellpadding="0" cellspacing="0" width="100%" class="table" border="1">							
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
            $start_thismonth = strtotime('first day of this month');
            $start_thismonthdate=date("Y-m-d h:i:s",$start_thismonth);
			$today_date='NOW()';
			echo '<td>'.date("F",$start_thismonth).'</td>';
            $sql_this_month="SELECT count(ticket_id) as this_month FROM sdms_ticket WHERE created>='".$start_thismonthdate."' AND created<='".$today_date."'";
            $res_this_month=mysql_query($sql_this_month);
            $row_this_month=mysql_fetch_array($res_this_month);	?>       
            <td><p align="center"><?php echo $row_this_month['this_month']; ?></p></td>
			<?php $sql_total="SELECT count(ticket_id) as total_complaint FROM sdms_ticket WHERE 1";
            $res_total=mysql_query($sql_total);
            $row_total=mysql_fetch_array($res_total);	?>       
            <td><?php echo $row_total['total_complaint']; ?></td>
            </tr>
            <tr>
            <td>b) Complaints accepted for follow-up</td>
            <?php 
			echo '<td>'.date("F",$start_thismonth).'</td>';
            $sql_follow_up="SELECT count(ticket_id) as total_followup FROM sdms_ticket WHERE complaint_status!='1'";
            $res_follow_up=mysql_query($sql_follow_up);
            $row_follow_up=mysql_fetch_array($res_follow_up);	?>       
            <td><p align="center"><?php echo $row_follow_up['total_followup']; ?></p></td>
            <td><?php echo $row_total['total_complaint']; ?></td>
            </tr>
            <tr>
            <td>c) Complaints Disposed Of</td>
            <?php 
			echo '<td>'.date("F",$start_thismonth).'</td>';
            $sql_close="SELECT count(ticket_id) as total_closed FROM sdms_ticket WHERE status='closed'";
            $res_close=mysql_query($sql_close);
            $row_close=mysql_fetch_array($res_close);	?>       
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
                <h3><?php echo 'Time between receipt and resolution of complaint'; ?></h3>                               
            </div>
            <div class="block-fluid table-sorting clearfix">
            <table cellpadding="0" cellspacing="0" width="100%" class="table" border="1" >							
            <thead>
            <tr>
            <td width="20%"><strong>Data</strong></td>
            <td width="32%"><strong>Minimum</strong></td>
            <td width="8%"><strong>Maximum</strong></td>
            <td width="8%"><strong>Avarage</strong></td>            
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
            <td>&nbsp;</td>

            </tr>
            

            </tbody>
            </table>
            </div>
        </div>                      
    </div>                       
		<div class="dr"><span></span></div>   
   </div><!--WorkPlace End-->  
   </div>  