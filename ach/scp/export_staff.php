<?php
require('staff.inc.php');
$csv='';	
$csv.='" List"'.',';	
$csv.="\n";	 		 		
$csv.='"Name"'.',';
$csv.='"UserName"'.',';
$csv.='"Status"'.',';
$csv.='"Group"'.',';
$csv.='"Department"'.',';
$csv.='"Staff Locatoion"'.',';
$csv.='"Created"'.','; 
$csv.="\n";
$total=0;
$query=$_SESSION['staff_list_session'];

$res = db_query($query);
if($res && ($num=db_num_rows($res))):
while ($row = db_fetch_array($res)) {
	$staff_status = '';
	$staff_status = $row['isactive']?'Active':'Locked';
	$staff_status .=$row['onvacation']?'(vacation)':'';
	
                
$csv .= '"'.Format::htmlchars($row['name']).'",';
$csv .= '"'.$row['username'].'",';
$csv .= '"'.$staff_status.'",'; 
$csv .= '"'.Format::htmlchars($row['group_name']).'",'; 
$csv .= '"'.Format::htmlchars($row['dept']).'",'; 
$csv .= '"'.$row['agency_name'].'",'; 
$csv .= '"'.Format::db_date($row['created']).'",';

$csv.="\n";
}
else:
$ferror='There are no Staff here. (Leave a little early today).';  
endif;

$csv.="\n";
$file = 'staff_lisitng.csv';
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

header("Content-Description: File Transfer"); 
header("Content-Type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=" . basename($file) . ""); 

readfile ($file);
exit(); 
?>