<?php 
//database connection details
//$connect = mysql_connect('localhost','ahsan','ubuntu');
$connect = mysql_connect('localhost','root','lastfight@secp321$');
if (!$connect) {
 die('Could not connect to MySQL: ' . mysql_error());
 }
//your database name
$cid = mysql_select_db('sdms_scep',$connect);

// path where your CSV file is located
define('CSV_PATH','');
if($_REQUEST['action']=='Cpital Markets'){
// Name of your CSV file
$csv_file = CSV_PATH . "Cpital Markets.csv";

if (($getfile = fopen($csv_file, "r")) !== FALSE) { 
        $data = fgetcsv($getfile, 1000, ",");
        while (($data = fgetcsv($getfile, 1000, ",")) !== FALSE) {
         //$num = count($data); 
         //for ($c=0; $c < $num; $c++) {
             $result = $data; 
             $str = implode(",", $result); 
			 $slice = explode(",", $str);			
             $col1 = $slice[0]; 
             $col2 = $slice[1]; 
             $col3 = $slice[2]; 
             $col4 = $slice[3];
			// echo $num.' '. $col1." ".$col2." ".$col3." ".$col4."<br>";
// SQL Query to insert data into DataBase	 	
$query = "INSERT INTO  sdms_capital_markets(type,type_urdu,parent,child_agent,sort_number,status,icon)
VALUES('".$col1."','','".$col2."','".$col3."',0,'".$col4."','')";
$s=mysql_query($query, $connect ); 
     //}
   } 
  }

echo "Cpital Markets data successfully imported to database!!"; 
mysql_close($connect); 
}elseif($_REQUEST['action']=='Insurance'){
// Name of your CSV file
$csv_file = CSV_PATH . "Insurance.csv";

if (($getfile = fopen($csv_file, "r")) !== FALSE) { 
        $data = fgetcsv($getfile, 1000, ",");
        while (($data = fgetcsv($getfile, 1000, ",")) !== FALSE) {
         //$num = count($data); 
         //for ($c=0; $c < $num; $c++) {
             $result = $data; 
             $str = implode(",", $result); 
			 $slice = explode(",", $str);			
             $col1 = $slice[0]; 
             $col2 = $slice[1]; 
             $col3 = $slice[2]; 
             $col4 = $slice[3];
			// echo $num.' '. $col1." ".$col2." ".$col3." ".$col4."<br>";
// SQL Query to insert data into DataBase	 	
$query = "INSERT INTO  sdms_insurance(type,parent,child_agent,status)
VALUES('".$col1."','".$col2."','".$col3."','".$col4."')";
$s=mysql_query($query, $connect ); 
     //}
   } 
  }
echo "Insurance data successfully imported to database!!"; 
mysql_close($connect); 
}elseif($_REQUEST['action']=='scd'){
// Name of your CSV file
$csv_file = CSV_PATH . "scd.csv";

if (($getfile = fopen($csv_file, "r")) !== FALSE) { 
        $data = fgetcsv($getfile, 1000, ",");
        while (($data = fgetcsv($getfile, 1000, ",")) !== FALSE) {
         //$num = count($data); 
         //for ($c=0; $c < $num; $c++) {
             $result = $data; 
             $str = implode(",", $result); 
			 $slice = explode(",", $str);			
             $col1 = $slice[0]; 
             $col2 = $slice[1]; 
             $col3 = $slice[2]; 
             $col4 = $slice[3];
			 $col5 = $slice[4];
			 $col6 = $slice[5];
			 $col7 = $slice[6];
			 
			// echo $num.' '. $col1." ".$col2." ".$col3." ".$col4."<br>";
// SQL Query to insert data into DataBase	 	
$query = "INSERT INTO  sdms_scd(type,type_urdu,parent,reit_scheme,modaraba_fund,mutual_fund,pension_fund,sort_number,status,icon)
VALUES('".$col1."','','".$col2."','".$col3."','".$col4."','".$col5."','".$col6."',0,'".$col7."','')";
$s=mysql_query($query, $connect ); 
     //}
   } 
  }
echo "scd data successfully imported to database!!"; 
mysql_close($connect); 
}
?>