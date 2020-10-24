
<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("complaintstatus_summary_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
//myWindow.close();
}
</script>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
?>
<div class="page-header"><h1>Complaints Summary <small> Status- Special</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">
    <p align="right" style="float:right;">
     <a id="ticket-print" class="action-button" href="" onclick="openWin();">
     <button class="btn" type="button"><i class="icon-print"></i> Print</button></a>                              
    </p>             
</div>
</div>
<div class="row-fluid">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Complaint Summary status- Special'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
            <table cellpadding="0" cellspacing="0" width="100%" class="table">	

<?php 				
$qselect='SELECT count(ticket_id) as ticketno ,t.topic_id, d.topic ';
$qfrom='FROM '.TICKET_TABLE.' t, sdms_help_topic d ';
$qwhere=' WHERE t.topic_id=d.topic_id group by t.topic_id ';

//$qselect='SELECT count(ticket_id) as ticketno, d.dept_name';
//$qfrom='FROM '.TICKET_TABLE.' t INNER JOIN sdms_department d ON (t.dept_id = d.dept_id)' ;
//$qwhere='group by t.dept_id';
//get log count based on the query so far..
$total=db_count("SELECT count(*) $qfrom $qwhere");
//$aeraopen=db_num_rows($resact3days);
$pagelimit=30;
//pagenate
$pageNav=new Pagenate($total,$page,$pagelimit);
$pageNav->setURL('admin.php',$qstr);
$query="$qselect $qfrom $qwhere ORDER BY t.created DESC LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
//echo $query;
$result = db_query($query);
$showing=db_num_rows($result)?$pageNav->showing():"";
?>

    <form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
    <input type="hidden" name="a" value="mass_process" >
    <input type="hidden" name="status" value="<?php echo $statusss?>" >
       <table cellpadding="0" cellspacing="0" width="100%" class="table">	
  <tr id="headerStyle">
    <td width="148">Summary</td>
    <td colspan="7">
   	<?php 
			$sql_gender="SELECT count(ticket_id) as gender_total ,gender FROM sdms_ticket group by gender ";
			$sql_gender_res=mysql_query($sql_gender);
			while($sql_gender_row=mysql_fetch_array($sql_gender_res))
			{
				if($sql_gender_row['gender']=='Male')
				{
				$gender_title="MALE";
				}
				elseif($sql_gender_row['gender']=='Female')
				{
				$gender_title="FEMALE";
				}
				elseif($sql_gender_row['gender']=='')
				{
				$gender_title="Unknown";
				}
	echo $gender_title.':<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$sql_gender_row['gender_total'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>';
				//$net_total_m_f +=$sql_gender_row['gender_total'];
			}
			?>
    </td>
  </tr>
  
  <tr id="headerStyle">
    <td rowspan="2">Status</td>
    <td width="101" rowspan="2">Total Complaints</td>
    <td colspan="3">Processing Time</td>
    <td colspan="3">Gender</td>

  </tr>
  <tr id="headerStyle">
    <td width="88">Minimum</td>
    <td width="95">Maximum</td>
    <td width="97">Avarage</td>
    <td width="24">M</td>
    <td width="24">F</td>
    <td width="44">N/a</td>
  </tr>
  <tr id="headerStyle">
    <td>a) Pending</td>
    <td>Subtotals</td>
    <td>Min(Lowest)</td>
    <td>Max(Highest)</td>
    <td>Avg(Mean)</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php
  $default_status='Pending';
 // $now_status='Set-aside (if not approved)';
  $min=0;
  $max=0;
  $min_time=0;
  $max_time=0;
  $average_time=0;  
  $average_count=0;
  //$query="SELECT * FROM sdms_status stat WHERE stat.status_id!=0 AND status_title='".$now_status."'";
    $query="SELECT * FROM sdms_status stat WHERE stat.status_id!=0";
  $result = db_query($query);
  while ($row = db_fetch_array($result)) {
	  
   ?>
  <tr>
  <th>
                <a href="javascript:toggleMessage('<?php echo $i;?>');">
                <span style="float:left; width:350px;margin-left:30px;"><?php if($row['status_title']!="") echo $row['status_title']; else echo "No Status";?></span></a></th>
    <td>          
   <?php $sql_total="SELECT count(ticket_id) as total FROM sdms_ticket WHERE complaint_status ='".$row['status_title']."'";
            $res_total=mysql_query($sql_total);
            $row_total=mysql_fetch_array($res_total);	?>       
            <span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$row_total['total'].'</span></b><br />'; ?></span></td>
		<?php
        $sql_ticket="SELECT * FROM `sdms_ticket` WHERE ticket_id!=0";
        $res_ticket=mysql_query($sql_ticket);
        while($row_ticket=mysql_fetch_array($res_ticket))
        {
			$sql_ticket_thread="SELECT * FROM `sdms_ticket` WHERE ticket_id='".$row_ticket['ticket_id']."'";
			$res_ticket_thread=mysql_query($sql_ticket_thread);
			while($row_ticket_thread=mysql_fetch_array($res_ticket_thread))
			{
				$average_count++;
				
				$sql_min_time1="SELECT * FROM sdms_ticket_thread  WHERE id=(SELECT min(id) from  sdms_ticket_thread  WHERE complaint_status='".$default_status."' AND ticket_id= '".$row_ticket['ticket_id']."' )";
				$res_min_time1=mysql_query($sql_min_time1);
				$row_min_time1=mysql_fetch_array($res_min_time1);
				$start_time = $row_min_time1['created'];
				//echo $sql_min_time1.'<br>';
				//echo $start_time.'<br>';
				
				$sql_min_time2="SELECT * FROM sdms_ticket_thread  WHERE id=(SELECT min(id) from  sdms_ticket_thread  WHERE complaint_status='".$row['status_title']."' AND ticket_id= '".$row_ticket['ticket_id']."')";
				$res_min_time2=mysql_query($sql_min_time2);
				$row_min_time2=mysql_fetch_array($res_min_time2);
				$end_time = $row_min_time2['created'];
				//echo $sql_min_time2.'<br>';
				//echo $end_time.'<br>';
					if($start_time!='' && $end_time!='')
					{
						$diff = abs(strtotime($end_time) - strtotime($start_time));
						/*$date1="2014-01-01";
						$date2="2014-02-01";
						$date3="2014-01-01";
						$date4="2014-01-10";
						
						$d1 = abs(strtotime($date1) - strtotime($date2));
						$d2 = abs(strtotime($date3) - strtotime($date4));
						$diff1=$d1+$d2;*/
						
						$average_time=$average_time+$diff;
						
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
					
					//echo '<br>';
			}
			
			
        }
		$total_avrage_time=($average_time/$average_count);
			//echo $min.'<br>';
			$average_years=floor($total_avrage_time / (365*60*60*24)); 
			$average_months  = floor(($total_avrage_time - $average_years * 365*60*60*24) / (30*60*60*24)); 
			$average_days    = floor(($total_avrage_time - $average_years * 365*60*60*24 - $average_months*30*60*60*24)/ (60*60*24));
			$average_hours   = floor(($total_avrage_time - $average_years * 365*60*60*24 - $average_months*30*60*60*24 - $average_days*60*60*24)/ (60*60));
			$average_minuts  = floor(($total_avrage_time - $average_years * 365*60*60*24 - $average_months*30*60*60*24 - $average_days*60*60*24 - $average_hours*60*60)/ 60); 			$average_seconds = floor(($total_avrage_time - $average_years * 365*60*60*24 - $average_months*30*60*60*24 - $average_days*60*60*24 - $average_hours*60*60 - $average_minuts*60));
			
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
    <?php 
	$sql_gender="SELECT gender FROM sdms_ticket WHERE complaint_status='".$row['status_title']."'";
			$sql_gender_res=mysql_query($sql_gender);
			$male=0;
			$female=0;
			$unknown=0;
			while($sql_gender_row=mysql_fetch_array($sql_gender_res))
			{
				if($sql_gender_row['gender']=='Male')
				{
				$male++;
				}
				elseif($sql_gender_row['gender']=='Female')
				{
				$female++;
				}
				elseif($sql_gender_row['gender']=='')
				{
				$unknown++;
				}
			}
	 ?>
    <td><?php echo $male; ?></td>
    <td><?php echo $female; ?></td>
    <td><?php echo $unknown; ?></td>
  </tr>
  <?php
 $default_status=$row['status_title'];
		$min=0;
		$diff=0;
		$max=0;
		$average_time=0;
		$average_count=0;
		
		 }
  ?>
  <tr>
    <td id="tdStyle">b) Completed</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td id="tdStyle">Recommendation send to dept</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td  id="tdStyle">Closed</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td id="tdStyle">Action Taken</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td id="tdStyle">c) Total</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
        </form>
	  </div>
 </div>                      
</div>                        
<div class="dr"><span></span></div>   
</div><!--WorkPlace End-->  
</div>   
