<?php error_reporting(0);?>
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
if($_POST['from_month']!='' && $_POST['to_month']!='')
{
$from_month = $_POST['from_month'];
$to_month = $_POST['to_month'];

$from_to_date = ' AND MONTH(created) >= "'.$from_month.'" AND MONTH(created) <= "'.$to_month.'" AND YEAR(created) = "2020" AND isquery ="0"  ';
}
else{
$from_month = date('m');
$to_month = date('m');

$from_to_date = ' AND MONTH(created) >= "'.$from_month.'" AND MONTH(created) <= "'.$to_month.'" AND YEAR(created) = "2020" AND isquery ="0" ';
}
?>

<div class="page-header"><h1>Complaints <small> Average Reply Time Count</small></h1></div>
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
<select name="dept_id" required="required" >
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
<th width="20%" style="padding-top:12px;">From Month</th>
<td>
<select name="from_month" required="required">
<?php 
$i = 1;
$date = strtotime('2020-01-01');
while($i <= 12)
{
    $month_name = date('F', $date);
    $month_number = date('m', $date);

    if($month_number == $from_month)
    $selected = 'selected="selected"';
    else
    $selected = '';
        
    echo '<option value="'. $month_number. '" '.$selected.' >'.$month_name.'</option>';
    $date = strtotime('+1 month', $date);
    $i++;
}
?>
</select>
</td>

<th width="20%" style="padding-top:12px;">To Month</th>
<td>
<select name="to_month" required="required">
<?php 
$i = 1;
$date = strtotime('2020-01-01');
while($i <= 12)
{
    $month_name = date('F', $date);
    $month_number = date('m', $date);

    if($month_number == $to_month)
    $selected = 'selected="selected"';
    else
    $selected = '';
        
    echo '<option value="'. $month_number. '" '.$selected.' >'.$month_name.'</option>';
    $date = strtotime('+1 month', $date);
    $i++;
}
?>
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
                <h1><?php echo 'Time between reply of complaint'; ?></h1>                               
            </div>
            <div class="block-fluid table-sorting clearfix">
            <table cellpadding="0" cellspacing="0" width="100%" class="table" >							
            <thead>
            <tr>
            <td width="32%"><strong>Complaint ID</strong></td>
            <td><strong>Created Date</strong></td>
            <td><strong>Subject</strong></td>
            <td><strong>Total Reply</strong></td>
            <td><strong>Avg Response Time</strong></td>            
            </tr>
            </thead>
            <tbody>
            <?php 
           
            
$t_average_years   = 0;
$t_average_months  = 0;
$t_average_days    = 0;
$t_average_hours   = 0;
$t_average_minuts  = 0; 
$t_average_seconds = 0;



$days_to_seconds = 0;
$hours_to_seconds = 0;
$minutes_to_seconds = 0;
$all_seconds= 0;

$t_years   = 0;
$t_months  = 0;
$t_days    = 0;
$t_hours   = 0;
$t_minuts  = 0; 
$t_seconds = 0;
$t_rowcount = 0;
$t_average = 0;

$sql_get_complaints="SELECT * from  sdms_ticket WHERE 1  ".$dept_add."  ".$from_to_date." ";
$res_get_complaints = mysql_query($sql_get_complaints);
while($row_get_complaints = mysql_fetch_array($res_get_complaints)){
        $diff = 0;
        $diff_total = 0;
        $rowcount = 0;
        $average = 0;
        $average_years = 0;
        $average_months = 0;
        $average_days   = 0;
        $average_hours  = 0;
        $average_minuts = 0;
        $average_seconds= 0;

        $sql_ticket_threads = "Select * from sdms_ticket_thread where ticket_id='".$row_get_complaints['ticket_id']."' AND thread_type ='R' order by id asc";
        $res_ticket_threads = mysql_query($sql_ticket_threads);
        $rowcount=mysql_num_rows($res_ticket_threads);

?>
            <tr>
            <td><a href="tickets.php?id=<?php echo $row_get_complaints['ticket_id']; ?>"><?php echo $row_get_complaints['ticket_id'] ;?></a></td>
            <td><?php echo date('Y-m-d H:i:s',strtotime($row_get_complaints['created'])); ?></td>
            <td><?php echo $row_get_complaints['subject'] ;?></td>
            <td><?php echo $rowcount; ?></td>
            <?php
             $time_of_customer_request =  $row_get_complaints['created'];
            while($row_ticket_thread = mysql_fetch_array($res_ticket_threads)){

                $diff = abs(strtotime($row_ticket_thread['created']) - strtotime($time_of_customer_request));
                $diff_total += $diff;
                $time_of_customer_request =  $row_ticket_thread['created'];

            }
            //Time of first response - time of customer request = (# Minutes/hours/days)


			$average=floor(($diff_total)/$rowcount);
			
			$average_years   = floor($average / (365*60*60*24)); 				
			$average_months  = floor(($average - $average_years * 365*60*60*24) / (30*60*60*24)); 
			$average_days    = floor(($average - $average_years * 365*60*60*24 - $average_months*30*60*60*24)/ (60*60*24));
			$average_hours   = floor(($average - $average_years * 365*60*60*24 - $average_months*30*60*60*24 - $average_days*60*60*24)/ (60*60)); 
			$average_minuts  = floor(($average - $average_years * 365*60*60*24 - $average_months*30*60*60*24 - $average_days*60*60*24 - $average_hours*60*60)/ 60); 
			$average_seconds = floor(($average - $average_years * 365*60*60*24 - $average_months*30*60*60*24 - $average_days*60*60*24 - $average_hours*60*60 - $average_minuts*60));
			
			?>   
            <td><?php echo $average_years.'Y-'.$average_months.'M-'.$average_days.'D '.$average_hours.'H:'.$average_minuts.'M:'.$average_seconds.'S';?></td>

            </tr>
         <?php 


$t_average_years   += $average_years;
$t_average_months  += $average_months;
$t_average_days    += $average_days;
$t_average_hours   += $average_hours;
$t_average_minuts  += $average_minuts; 
$t_average_seconds += $average_seconds;
$t_rowcount += $rowcount;

     }

$days_to_seconds = 86400 * $t_average_days;
$hours_to_seconds = 3600 * $t_average_hours;
$minutes_to_seconds = 60 * $t_average_minuts; 

$all_seconds = $days_to_seconds + $hours_to_seconds+ $minutes_to_seconds + $t_average_seconds; 
$t_average=floor(($all_seconds)/$t_rowcount);
//$t_average = date('H:i:s',strtotime($t_average));

$t_years   = floor($t_average / (365*60*60*24));                
$t_months  = floor(($t_average - $t_years * 365*60*60*24) / (30*60*60*24)); 
$t_days    = floor(($t_average - $t_years * 365*60*60*24 - $t_months*30*60*60*24)/ (60*60*24));
$t_hours   = floor(($t_average - $t_years * 365*60*60*24 - $t_months*30*60*60*24 - $t_days*60*60*24)/ (60*60)); 
$t_minuts  = floor(($t_average - $t_years * 365*60*60*24 - $t_months*30*60*60*24 - $t_days*60*60*24 - $t_hours*60*60)/ 60); 
$t_seconds = floor(($t_average - $t_years * 365*60*60*24 - $t_months*30*60*60*24 - $t_days*60*60*24 - $t_hours*60*60 - $t_minuts*60));

      ?>

            </tbody>
            <tfoot>
            <tr>
            <td width="32%" colspan="3"><strong>TOTAL</strong></td>
            <td width="32%"><?php echo $t_rowcount; ?></td>
            <td><strong><?php echo $t_years.'Y-'.$t_months.'M-'.$t_days.'D '.$t_hours.'H:'.$t_minuts.'M:'.$t_seconds.'S';?></strong></td>            
            </tr>
            </tfoot> 
            
            </table>
            </div>
        </div>                      
    </div>

                       
		<div class="dr"><span></span></div>   
   </div><!--WorkPlace End-->  
   </div> 