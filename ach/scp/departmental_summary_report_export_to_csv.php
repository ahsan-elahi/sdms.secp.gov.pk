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
$csv.='"Department of Complaint"'.','.'"Total Complaints"'.','.'"Status"'.',';
$csv.="\n";

$csv .= '""'.','.'""'.',';

$sql_status="SELECT * FROM `sdms_status` WHERE p_id='0'";
$res_status=mysql_query($sql_status);
$num_status = mysql_num_rows($res_status);
while($row_status=mysql_fetch_array($res_status)){

$csv .= '"' . $row_status['status_title'] . '",';
			
			  $sql_sub_status="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
$res_sub_status=mysql_query($sql_sub_status);
$num_sub_status = mysql_num_rows($res_sub_status);
while($row_sub_status=mysql_fetch_array($res_sub_status)){
	  
$csv .= '"' . $row_sub_status['status_title'] . '",';
              } 
             } 

$csv.="\n";
$sql_dept="SELECT * FROM `sdms_department` WHERE 1";
$res_dept=mysql_query($sql_dept);
$num_dept = mysql_num_rows($res_dept);
if($num_dept>0){
	$subnum_dept_comp = 0;
while($row_dept=mysql_fetch_array($res_dept)){
	$num_dept_comp = 0;
$sql_dept_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND dept_id='".$row_dept['dept_id']."' ".$dept_add."";
$res_dept_comp = mysql_query($sql_dept_comp);
$num_dept_comp += mysql_num_rows($res_dept_comp);

$sql_sub_dept_inner="SELECT * FROM `sdms_department` WHERE dept_p_id='".$row_dept['dept_id']."'";
$res_sub_dept_inner=mysql_query($sql_sub_dept_inner);
$num_sub_dept_inner = mysql_num_rows($res_sub_dept_inner);
$csv .= '"' . $row_dept['dept_name'] . '",';
$csv .= '"' . $num_dept_comp . '",';
$subnum_dept_comp +=$num_dept_comp;

$sql_status="SELECT * FROM `sdms_status` WHERE p_id='0'";
$res_status=mysql_query($sql_status);
$subnum_status_comp = 0;
while($row_status=mysql_fetch_array($res_status)){
$num_status_comp = 0;
$sql_sub_status="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
$res_sub_status=mysql_query($sql_sub_status);
$num_sub_status = mysql_num_rows($res_sub_status);
while($row_sub_status=mysql_fetch_array($res_sub_status)){	
$sql_status_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status['status_id']."' AND  dept_id='".$row_dept['dept_id']."'";
$res_status_comp = mysql_query($sql_status_comp);
$num_status_comp += mysql_num_rows($res_status_comp);

}
$csv .= '"' . $num_status_comp . '",';


                $sql_sub_status_inner="SELECT * FROM `sdms_status` WHERE p_id='".$row_status['status_id']."'";
                $res_sub_status_inner=mysql_query($sql_sub_status_inner);
                $num_sub_status_inner = mysql_num_rows($res_sub_status_inner);
				while($row_sub_status_inner=mysql_fetch_array($res_sub_status_inner)){
                $num_status_comp_inner = 0;
                $sql_status_comp_inner = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND complaint_status='".$row_sub_status_inner['status_id']."' AND  dept_id='".$row_dept['dept_id']."'";
                $res_status_comp_inner = mysql_query($sql_status_comp_inner);
                $num_status_comp_inner = mysql_num_rows($res_status_comp_inner);
				
				$csv .= '"' . $num_status_comp_inner . '",';
			 
                } 
				
				} 
           
            
$csv.="\n";
}
}
$csv.='"Total"'.','.'"'.$subnum_dept_comp.'"'.',';		
$csv.="\n";
$file = 'departmental_summary_report.csv';
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