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

function numToOrdinalWord($num)
{
    $first_word = array('eth','First','Second','Third','Fouth','Fifth','Sixth','Seventh','Eighth','Ninth','Tenth','Elevents','Twelfth','Thirteenth','Fourteenth','Fifteenth','Sixteenth','Seventeenth','Eighteenth','Nineteenth','Twentieth');
    $second_word =array('','','Twenty','Thirthy','Forty','Fifty');

    if($num <= 20)
        return $first_word[$num];

    $first_num = substr($num,-1,1);
    $second_num = substr($num,-2,1);

    return $string = str_replace('y-eth','ieth',$second_word[$second_num].'-'.$first_word[$first_num]);
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
<select name="dept_id"  >
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
            <td width="10%"><strong>Department</strong></td>
            <td width="5%"><strong>Complaint ID</strong></td>
            <td width="15%"><strong>Created Date</strong></td>
            <td width="20%"><strong>Subject</strong></td>
            <td width="25%"><strong>Summary</strong></td>
            <td width="10%"><strong>Total Frequency</strong></td>
            <td><strong>Average Time</strong></td>            
            </tr>
            </thead>
            <tbody>
            <?php 
$t_rowcount = 0;
$t_average = 0;
$count = 0;
$average_frequency = 0;
$average_time = 0;

$sql_get_complaints="SELECT * from  sdms_ticket WHERE 1  ".$dept_add."  ".$from_to_date." ";
$res_get_complaints = mysql_query($sql_get_complaints);
while($row_get_complaints = mysql_fetch_array($res_get_complaints)){

    $sql_dept = "Select * from sdms_department where dept_id='".$row_get_complaints['dept_id']."' ";
$res_dept = mysql_query($sql_dept);
$row_dept = mysql_fetch_array($res_dept);

        $diff = 0;
        $diff_total = 0;
        $rowcount = 0;
        $average = 0;
        $summary = '';

        $sql_ticket_threads = "Select * from sdms_ticket_thread where ticket_id='".$row_get_complaints['ticket_id']."' AND thread_type ='R' order by id asc";
        $res_ticket_threads = mysql_query($sql_ticket_threads);
        $rowcount=mysql_num_rows($res_ticket_threads);
        if($rowcount > 0)
        {

?>
            <tr>
            <td><?php echo $row_dept['dept_name'] ;?></td>
            <td><a href="tickets.php?id=<?php echo $row_get_complaints['ticket_id']; ?>"><?php echo $row_get_complaints['ticket_id'] ;?></a></td>
            <td><?php echo date('Y-m-d H:i:s',strtotime($row_get_complaints['created'])); ?></td>
            <td><?php echo $row_get_complaints['subject'] ;?></td>
            
            <?php
             $time_of_customer_request =  date('Y-m-d',strtotime($row_get_complaints['created']));
             $i = 1;
             $summary .= 'Created: '.$time_of_customer_request.'<br>';

            while($row_ticket_thread = mysql_fetch_array($res_ticket_threads)){

                 
                
                $reply_done =  date('Y-m-d',strtotime($row_ticket_thread['created']));
                //Time of first response - time of customer request = (# Minutes/hours/days)
                $diff = abs(strtotime($reply_done) - strtotime($time_of_customer_request));
                $diff_total += $diff / (60 * 60 * 24);
                $time_of_customer_request =  date('Y-m-d',strtotime($row_ticket_thread['created']));
                
                $summary .= numToOrdinalWord($i).' Reply'.': '.$time_of_customer_request.'<br>';
                $summary .= 'Respons Time: '.$diff / (60 * 60 * 24).'<br><br>';
                $i++;

            }
            $average= ($diff_total)/$rowcount;
            ?>
            <td><?php echo $summary ;?></td>
            <td><?php echo $rowcount; ?></td> 
            <td><?php echo number_format($average,2);?></td>
            </tr>
        <?php 
            $t_rowcount += $rowcount; 
            $t_average += $average;
            $count++;
    }
}
$average_frequency = $t_rowcount/$count;
$average_time = $t_average/$count;
 ?>
    
            </tbody>
            <tfoot>
            <tr>
            <td colspan="5"><strong></strong></td>
            <td colspan="2">
            <b>Average Frequency:</b>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo number_format($average_frequency,2); ?><br>
            <b>Average Time:</b>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo number_format($average_time,2); ?></td>          
            </tr>
            </tfoot>
            </table>
            </div>
        </div>                      
    </div>

                       
		<div class="dr"><span></span></div>   
   </div><!--WorkPlace End-->  
   </div> 