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
if($_REQUEST['dept_id']!='')
{
$dept_add .= ' AND dept_id = '.$_REQUEST['dept_id'].'';
}	

$csv='';						 		 		
$csv.='"Status of Complaint"'.','.'"Total Complaints"'.','.'"Ageing-IN DAYS"'.',';
$csv.="\n";

$csv .= '""'.','.'""'.','.'"1 to 15"'.','.'"15+"'.','.'"30+"'.','.'"45+"'.',';

$csv.="\n";
 
$sql_status="SELECT * FROM `sdms_status` WHERE p_id='0' AND status_id != '2' AND status_id != '5' ";
$res_status=mysql_query($sql_status);
$num_status = mysql_num_rows($res_status);
if($num_status>0){

$subt_1to15days = 0;
$subt_16to30days = 0;
$subt_31to45days = 0;
$subt_45daysplus = 0;	
$subnum_status_comp = 0;

while($row_status=mysql_fetch_array($res_status)){
	
$t_1to15days = 0;
$t_16to30days = 0;
$t_31to45days = 0;
$t_45daysplus = 0;
$num_status_comp = 0;
	
$sql_sub_status="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
$res_sub_status=mysql_query($sql_sub_status);
$num_sub_status = mysql_num_rows($res_sub_status);
while($row_sub_status=mysql_fetch_array($res_sub_status)){
$today_date =  date('Y-n-j'); 
$sql_status_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' ".$dept_add."";
$res_status_comp = mysql_query($sql_status_comp);
$num_status_comp += mysql_num_rows($res_status_comp);
//echo "first loop 1 to 15 Days";
for($i=1;$i<16;$i++)
{
$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days += mysql_num_rows($res_1to15days);

$today_date = date ("Y-n-j", strtotime("-1 day", strtotime($today_date)));
}

//echo "second loop 15+ Days";
for($i=1;$i<16;$i++)
{
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days += mysql_num_rows($res_16to30days);
//echo $today_date.'<br>';
$today_date = date ("Y-n-j", strtotime("-1 day", strtotime($today_date)));

}
//echo "second loop 45+ Days";
for($i=1;$i<16;$i++)
{
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days += mysql_num_rows($res_31to45days);
//echo $today_date.'<br>';
$today_date = date ("Y-n-j", strtotime("-1 day", strtotime($today_date)));

}
$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) <= '".$today_date."' ".$dept_add."";
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus += mysql_num_rows($res_45daysplus);


}

$csv .= '"' . $row_status['status_title'] . '",';
$csv .= '"' . $num_dept_comp. '",';
$csv .= '"' . $t_1to15days. '",';
$csv .= '"' . $t_16to30days. '",';
$csv .= '"' . $t_31to45days. '",';
$csv .= '"' . $t_45daysplus. '",';
			
$subnum_status_comp +=$num_status_comp;	
$subt_1to15days += $t_1to15days; 
$subt_16to30days += $t_16to30days;
$subt_31to45days += $t_31to45days;
$subt_45daysplus += $t_45daysplus;

      $csv.="\n"; 

			}
			 $csv.='"Total"'.','.'"'.$subnum_status_comp.'"'.','.'"'.$subt_1to15days.'"'.','.'"'.$subt_16to30days.'"'.','.'"'.$subt_31to45days.'"'.','.'"'.$subt_45daysplus.'"'.',';	
			} 
$csv.="\n";
$csv.="\n";
$csv.="\n";
$csv.="\n";
$sql_status="SELECT * FROM `sdms_status` WHERE p_id='0' AND status_id = '2' OR status_id = '5' ";
$res_status=mysql_query($sql_status);
$num_status = mysql_num_rows($res_status);
if($num_status>0){

$subt_1to15days = 0;
$subt_16to30days = 0;
$subt_31to45days = 0;
$subt_45daysplus = 0;	
$subnum_status_comp = 0;

while($row_status=mysql_fetch_array($res_status)){
	
$t_1to15days = 0;
$t_16to30days = 0;
$t_31to45days = 0;
$t_45daysplus = 0;
$num_status_comp = 0;
	
$sql_sub_status="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
$res_sub_status=mysql_query($sql_sub_status);
$num_sub_status = mysql_num_rows($res_sub_status);
while($row_sub_status=mysql_fetch_array($res_sub_status)){
$today_date =  date('Y-n-j'); 
$sql_status_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' ".$dept_add."";
$res_status_comp = mysql_query($sql_status_comp);
$num_status_comp += mysql_num_rows($res_status_comp);

//echo "first loop 1 to 15 Days";
for($i=1;$i<16;$i++)
{
$sql_1to15days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_1to15days = mysql_query($sql_1to15days);
$t_1to15days += mysql_num_rows($res_1to15days);

$today_date = date ("Y-n-j", strtotime("-1 day", strtotime($today_date)));
}

//echo "second loop 15+ Days";
for($i=1;$i<16;$i++)
{
$sql_16to30days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_16to30days = mysql_query($sql_16to30days);
$t_16to30days += mysql_num_rows($res_16to30days);
//echo $today_date.'<br>';
$today_date = date ("Y-n-j", strtotime("-1 day", strtotime($today_date)));

}
//echo "second loop 45+ Days";
for($i=1;$i<16;$i++)
{
$sql_31to45days = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) = '".$today_date."' ".$dept_add."";
$res_31to45days = mysql_query($sql_31to45days);
$t_31to45days += mysql_num_rows($res_31to45days);
//echo $today_date.'<br>';
$today_date = date ("Y-n-j", strtotime("-1 day", strtotime($today_date)));

}
$sql_45daysplus = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND DATE(created) <= '".$today_date."' ".$dept_add."";
$res_45daysplus = mysql_query($sql_45daysplus);
$t_45daysplus += mysql_num_rows($res_45daysplus);


}

$csv .= '"' . $row_status['status_title'] . '",';
$csv .= '"' . $num_dept_comp. '",';
$csv .= '"' . $t_1to15days. '",';
$csv .= '"' . $t_16to30days. '",';
$csv .= '"' . $t_31to45days. '",';
$csv .= '"' . $t_45daysplus. '",';
			
$subnum_status_comp +=$num_status_comp;	
$subt_1to15days += $t_1to15days; 
$subt_16to30days += $t_16to30days;
$subt_31to45days += $t_31to45days;
$subt_45daysplus += $t_45daysplus;

      $csv.="\n"; 

			}
			 $csv.='"Total"'.','.'"'.$subnum_status_comp.'"'.','.'"'.$subt_1to15days.'"'.','.'"'.$subt_16to30days.'"'.','.'"'.$subt_31to45days.'"'.','.'"'.$subt_45daysplus.'"'.',';	
			}

$file = 'comlaintstatus_new_csv.csv';
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