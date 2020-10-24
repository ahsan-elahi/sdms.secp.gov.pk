<?php include_once('connection.php');
$query="select * from sdms_syslog";
$res=mysql_query($query)or die("error in query");
$abc=array();
$xyz=array(array());
$i=1;
$xyz[0]=array('Log id','Log type','Title','Log','Ip Address','Created','Updated');
$csv='';
$csv.='"Log id"'.','.'"Log type"'.','.'"Title"'.','.'"Log"'.','.'"Ip Address"'.','.'"Created"'.','.'"Created"';
$csv.="\n";
while($row=mysql_fetch_array($res)){
$csv.='"'.$row['log_id'].'"'.','.'"'.$row['log_type'].'"'.','.'"'.$row['title'].'"'.','.'"'.$row['log'].'"'.','.'"'.$row['ip_address'].'"'.','.'"'.$row['created'].'"'.','.'"'.$row['updated'].'"';
$csv.="\n";
}
$file = 'log.csv';
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
header('Content-Description: File Transfer');
header('Content-Type: application/force-download');
header('Content-Length: ' . filesize('log.csv'));
header("Content-Disposition: attachment; filename=\"".basename('log.csv')."\";" );
readfile('log.csv'); 
exit;
?>