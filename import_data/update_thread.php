<?php 
//database connection details
//$connect = mysql_connect('localhost','ahsan','ubuntu');
$connect = mysql_connect('localhost','root','lastfight@secp321$');
if (!$connect) {
 die('Could not connect to MySQL: ' . mysql_error());
 }
//your database name
$cid = mysql_select_db('sdms_scep',$connect);
$nums_sub_category = 0;
$sql_category = "Select * from sdms_ticket where status = 'closed' AND isquery = '1' order  by ticket_id desc";
$res_category = mysql_query($sql_category);
while($row_category = mysql_fetch_array($res_category)){
	
		$sql_sub_category = "Select * from sdms_ticket_thread where ticket_id='".$row_category['ticket_id']."' order by id DESC LIMIT 1";
		$res_sub_category = mysql_query($sql_sub_category);
		$row_sub_category = mysql_fetch_array($res_sub_category);
		if($row_category['complaint_status'] != $row_sub_category['complaint_status'])
		{
			$nums_sub_category++;
		}
}

	echo $nums_sub_category;exit;