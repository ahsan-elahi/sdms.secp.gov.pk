<?php error_reporting(0); ?>
<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("sdms_summary_print.php","Print Summary Report","toolbar=yes,width=800px,height=18000px");
window.location.reload();
}
</script>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
?>
<?php 
if($thisstaff->isFocalPerson() == '1' || $thisstaff->getGroupId()=='8')
{
	$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	$dept_id = $thisstaff->getDeptId();
	$_POST['dept_id'] = $thisstaff->getDeptId();
}
elseif(!$thisstaff->isAdmin() &&  $thisstaff->onChairman() == '1' && $_POST['dept_id']!='')
{
	/*$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	$dept_id = $thisstaff->getDeptId();
	$_POST['dept_id'] = $thisstaff->getDeptId();*/
	$dept_add .= ' AND dept_id = '.$_POST['dept_id'].'';
	$dept_id = $_POST['dept_id'];
}
elseif($thisstaff->isAdmin() && $_POST['dept_id']!='')
{
$dept_add .= ' AND dept_id = '.$_POST['dept_id'].'';
$dept_id = $_POST['dept_id'];
}
elseif($_POST['dept_id']=='')
{
	$dept_add .= '';
	$dept_id = '';
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

<div class="page-header"><h1>Complaints <small> Average Resolution Time Count</small></h1></div>
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
<?php if($thisstaff->isAdmin() || $thisstaff->onChairman() == '1'){ ?>
<th width="20%" style="padding-top:12px;">By Department</th>
<td  >
<select name="dept_id" >
<option value="">--Select Department--</option>
<?php 
$sql_get_dept='SELECT * from  sdms_department WHERE 1 ';
$res_get_dept = mysql_query($sql_get_dept);
while($row_dept = mysql_fetch_array($res_get_dept)){
?>
<option value="<?php echo $row_dept['dept_id'] ;?>" <?php if($row_dept['dept_id']==$_POST['dept_id']){ ?> selected <?php }?>><?php echo $row_dept['dept_name'] ;?></option>
<?php } ?>
</select>
</td>
<?php }?>
<th width="20%" style="padding-top:12px;">From Date</th>
<td>
<input type="text" name="from_date" id="Datepicker"  value="<?php echo $_POST['from_date']; ?>" >
</td>
<th width="20%" style="padding-top:12px;">To Date</th>
<td>
<input type="text" name="to_date" id="Datepicker1"  value="<?php echo $_POST['to_date']; ?>" >
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
                <h1><?php echo 'Time between receipt and resolution of complaint'; ?></h1>                               
            </div>
            <div class="block-fluid table-sorting clearfix">
            <table cellpadding="0" cellspacing="0" width="100%" class="table" >							
            <thead>
            <tr>
            <td width="32%"><strong>Department</strong></td>
            <td><strong>Minimum</strong></td>
            <td><strong>Maximum</strong></td>
            <td><strong>Average</strong></td>            
            </tr>
            </thead>
            <tbody>
            <?php 
$sql_get_dept="SELECT * from  sdms_department WHERE 1 AND  dept_id !='1'  ".$dept_add."";
$res_get_dept = mysql_query($sql_get_dept);
while($row_dept = mysql_fetch_array($res_get_dept)){
?>
            <tr>
            <td><?php echo $row_dept['dept_name'] ;?></td>
            <?php 
			$count=0;
			$min=0;
			$max=0;
			$avg=0;
			$diff_total=0;
			//$sql_resolve="SELECT * FROM `sdms_ticket` WHERE status='closed'  ".$dept_add." AND isquery=0 AND complaint_status IN (SELECT status_id FROM `sdms_status` WHERE `p_id`=5)";
			$sql_resolve="SELECT * FROM `sdms_ticket` WHERE status='closed' AND dept_id = '".$row_dept['dept_id']."' AND isquery=1";
			//echo $sql_resolve.'<br>';
			$res_resolve=mysql_query($sql_resolve);
			while($row_resolve=mysql_fetch_array($res_resolve))
			{
			$sql_cal="SELECT * FROM `sdms_ticket` WHERE ticket_id='".$row_resolve['ticket_id']."' ".$from_to_date."";
			//echo '<br>'.$sql_cal.'<br>';
			$res_cal=mysql_query($sql_cal);
			$row_cal=mysql_fetch_array($res_cal);
			$start_time=$row_cal['created'];
			$end_time = $row_cal['closed']; 	
			if($start_time!='' && $end_time!='')
			{
			//	echo $start_time.' '.$end_time.'<br>';
					$count++;			
					$diff = abs(strtotime($end_time) - strtotime($start_time));
					//minimum value							
					if($min==0)
					{ 
					$min=$diff; 
					}
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
					//echo 	$row_resolve['ticket_id'].'<br>';
					}
					//echo '<br>'.$min;
				$diff_total += $diff; 
				}
				
			}
			//echo 'jhjk';exit();
			//$average=floor(($min+$max)/2);
			$average=floor(($diff_total)/$count);
			
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
         <?php } ?>

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