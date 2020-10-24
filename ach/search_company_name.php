<?php 
include("assets/config.php"); 
$input=$_REQUEST['term'];
$sql="Select company_title from sdms_companis where company_title LIKE '".$input."%' LIMIT 0,20";
$res=mysql_query($sql) or die("error in query");
$search='[';
$a="";
while($row=mysql_fetch_array($res)){
if($a=="")
{
$a.='"'.$row['company_title'].'"';
}
else
{
$a.=',"'.$row['company_title'].'"';
}
}
$search.=$a.']';
echo $search;
?>