<?php
/*********************************************************************
    directory.php

    Staff directory

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2013 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/
require('staff.inc.php');

$csv='';						 		 		
$csv.='"Department of Complaint"'.','.'"Total Complaints"'.','.'"Ageing-IN DAYS (Pending Complaints)"'.',';
$csv.="\n";

$csv .= '""'.','.'""'.','.'"1 to 15"'.','.'"15+"'.','.'"30+"'.','.'"45+"'.',';

$csv.="\n";

$sql_dept="SELECT * FROM `sdms_department` WHERE 1";
$res_dept=mysql_query($sql_dept);
$num_dept = mysql_num_rows($res_dept);
if($num_dept>0){

$subt_1to15days = 0;
$subt_16to30days = 0;
$subt_31to45days = 0;
$subt_45daysplus = 0;	
$subnum_dept_comp = 0;

while($row_dept=mysql_fetch_array($res_dept)){
	
$t_1to15days = 0;
$t_16to30days = 0;
$t_31to45days = 0;
$t_45daysplus = 0;
$num_dept_comp = 0;


$today_date =  date('Y-n-j'); 
$sql_dept_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND status !='closed' AND dept_id='".$row_dept['dept_id']."' ".$dept_add."";
$res_dept_comp = mysql_query($sql_dept_comp);
$num_dept_comp += mysql_num_rows($res_dept_comp);

//echo "first loop 1 to 15 Days";
for($i=1;$i<16;$i++)
{
$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND status !='closed' AND dept_id='".$row_dept['dept_id']."' AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days += mysql_num_rows($res_1to15days);

$today_date = date ("Y-n-j", strtotime("-1 day", strtotime($today_date)));
}
//echo "second loop 15+ Days";
for($i=1;$i<16;$i++)
{
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND status !='closed' AND dept_id='".$row_dept['dept_id']."' AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days += mysql_num_rows($res_16to30days);
//echo $today_date.'<br>';
$today_date = date ("Y-n-j", strtotime("-1 day", strtotime($today_date)));

}
//echo "second loop 45+ Days";
for($i=1;$i<16;$i++)
{
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND status !='closed' AND dept_id='".$row_dept['dept_id']."' AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days += mysql_num_rows($res_31to45days);
//echo $today_date.'<br>';
$today_date = date ("Y-n-j", strtotime("-1 day", strtotime($today_date)));
}
$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND status !='closed' AND dept_id='".$row_dept['dept_id']."' AND DATE(created) <= '".$today_date."' ".$dept_add."";
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus += mysql_num_rows($res_45daysplus);

$sql_sub_dept_inner="SELECT * FROM `sdms_department` WHERE dept_p_id='".$row_dept['dept_id']."'";
$res_sub_dept_inner=mysql_query($sql_sub_dept_inner);
$num_sub_dept_inner = mysql_num_rows($res_sub_dept_inner);
			$csv .= '"' . $row_dept['dept_name'] . '",';
			$csv .= '"' . $num_dept_comp. '",';
			$csv .= '"' . $t_1to15days. '",';
			$csv .= '"' . $t_16to30days. '",';
			$csv .= '"' . $t_31to45days. '",';
			$csv .= '"' . $t_45daysplus. '",';
			
$subnum_dept_comp +=$num_dept_comp;	
$subt_1to15days += $t_1to15days; 
$subt_16to30days += $t_16to30days;
$subt_31to45days += $t_31to45days;
$subt_45daysplus += $t_45daysplus;
 
            
      $csv.="\n"; 
       }
	   
	   $csv.='"Total"'.','.'"'.$subnum_dept_comp.'"'.','.'"'.$subt_1to15days.'"'.','.'"'.$subt_16to30days.'"'.','.'"'.$subt_31to45days.'"'.','.'"'.$subt_45daysplus.'"'.',';	
	   
          }

$csv.="\n";
$file = 'comlaintsdepartment_new_csv.csv';
if (!$handle = fopen($file, 'w')) 
{
echo "Cannot open file ($filename)";
exit;                    
}
if (fwrite($handle, $csv) === FALSE) 
{
echo "Cannot write to file ($filename)";
exit;
}
fclose($handle);
?>