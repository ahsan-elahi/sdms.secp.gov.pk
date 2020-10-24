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
$csv.='"Channel"'.','.'"Total Complaints"'.',';
$csv.="\n";
$sql_status="SELECT count(ticket_id) as ticketno,source FROM sdms_ticket WHERE `isquery`=0 group by source";
$res_status=mysql_query($sql_status);
$num_status = mysql_num_rows($res_status);
if($num_status>0){
while($row_status=mysql_fetch_array($res_status)){
	$res_status['source'];
			 $csv.='"'.$row_status['source'].'"'.','.'"'.$row_status['ticketno'].'"'.',';	
			 $csv.="\n";

			}

			} 

$file = 'complaintsource_new_csv.csv';
if (!$handle = fopen($file, 'w')) 
{
echo "Cannot open file ($file)";
exit;                    
}
if (fwrite($handle, $csv) === FALSE) 
{
echo "Cannot write to file ($filename)";
exit;
}
fclose($handle);
?>