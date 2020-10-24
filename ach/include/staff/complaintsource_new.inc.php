<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
$csv='';						 		 		
$csv.='"Channel"'.','.'"Total Complaints"'.',';
$csv.="\n";

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
if($_POST['dept_id']=='')
{
	$dept_add .= ' AND dept_id = 1';
	$dept_id = 1;
	$_POST['dept_id'] = 1;
}
if($_POST['dept_id']=='all')
{
	$dept_add = ' ';
	$dept_id = '';
	$_POST['dept_id'] = '';
}
if($_POST['from_date']!='' && $_POST['to_date']!='')
{
$from_to_date = ' AND DATE(created) >= "'.date('Y-m-d',strtotime($_POST['from_date'])).'" AND DATE(created) <= "'.date('Y-m-d',strtotime($_POST['to_date'])).'"';
}else{
$from_to_date ='';
}
?>
<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("complaintsource_new_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
//myWindow.close();
}
function export_to_csv(){
var export_csv = '';    
		//alert(items_csv);
	$.ajax({
			url:"complaintsource_new_csv.php",
			data: "&export_csv="+export_csv,
			success: function(msg){
			//alert(msg);
			document.getElementById("csv_download").click();
			//display_summary_table();
			}});			
	
}
</script>
<div class="page-header"><h1>Complaints Source<small> Summary</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">
    <p align="right" style="float:right;">
      <a href="complaintsource_new_csv.csv"><button class="btn" type="button" ><i class="icon-print"></i> Export</button></a>          
    </p>             
</div>
</div>
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
<select name="dept_id" >
<option value="">--Select Department--</option>
<?php 
$sql_get_dept='SELECT * from  sdms_department WHERE 1 ';
$res_get_dept = mysql_query($sql_get_dept);
while($row_dept = mysql_fetch_array($res_get_dept)){
?>
<option value="<?php echo $row_dept['dept_id'] ;?>" <?php if($row_dept['dept_id']==$_POST['dept_id']){ ?> selected <?php }?>><?php echo $row_dept['dept_name'] ;?></option>
<?php } ?>
<option value="all">All</option>
</select>
</td>
<?php }?>

<th width="20%" style="padding-top:12px;">From Date</th>
<td>
<input type="text" name="from_date" id="Datepicker"  value="<?php echo $_POST['from_date']; ?>" >
</td>
<th width="20%" style="padding-top:12px;">To Date</th>
<td>
<input type="text" name="to_date" id="Datepicker1"  value="<?php echo $_POST['to_date']; ?>" >
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
<?php if($thisstaff->isAdmin() || $thisstaff->onChairman() == '1'){ ?>
<?php }?>
<div class="row-fluid">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1>Complaints Source</h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
 <table cellpadding="0" cellspacing="0" width="100%" class="table">
    <thead>
            <tr>
            <th>Channel</th>
          
             <th>Total</th>  
            </tr>
            </thead>
    <tbody class="" page="1">
        <?php
$qselect='SELECT count(ticket_id) as ticketno,source';
$qfrom='FROM '.TICKET_TABLE.' t';
$qwhere='WHERE `isquery`=0 '.$dept_add.' '.$from_to_date.' group by source';
$total=db_count("SELECT count(*) $qfrom $qwhere");
$pagelimit=30;
$query="$qselect $qfrom $qwhere"; 
$result = db_query($query);

$href_source_total = 'tickets.php?action=by_source_subtotal&dept_id='.$dept_id.'';
			
        $total=0;
        $i=1;
		$source=0;
		$count=1;
        if($result && ($num=db_num_rows($result))):
		  while ($row = db_fetch_array($result)) {
			  
			  $href_source = 'tickets.php?action=by_source&dept_id='.$dept_id.'&source='.urlencode($row['source']).'';
        ?>
        <tr>
        <th>
         <span style="float: left; width:350px;"><?php if($row['source']!="") echo $row['source']; else echo "No Source";?></span>
        </th>
                
        <td><span class="Icon <?php echo $icon?>" align="right">
		<a href="<?php echo $href_source; ?>">
		<?php echo '<b><span align="right">'.$row['ticketno'].'</span></b><br />'; ?>
        </a>
        </span></td> 
		<?php $count_total +=$row['ticketno']; ?>   
			 
                </tr>           
            <?php
             $csv.='"'.$row['source'].'"'.','.'"'.$row['ticketno'].'"'.',';	
			 $csv.="\n";
			 } //end of while.
        else: //not tickets found!! ?>
            <tr class="<?php $class;?>"><td><b>Query returned 0 results.</b></td></tr>
        <?php endif; ?>
        
         <tr id="total">
         
        <th><span style="float: left; width:350px;">Total</span></th>
        <td><span class="Icon <?php echo $icon;?>" align="right">
		<a href="<?php echo $href_source_total; ?>">
		<?php echo '<b><span align="right">'.$count_total.'</span></b><br />'; ?>
        </a>
        </span></td>
        </tr>
        <?php
		
		$csv.='"Total"'.','.'"'.$count_total.'"'.',';
		$csv.="\n";	
			
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
        
        </tbody>                    
		</table>

        </div>
 </div>                      
</div>                        
<div class="dr"><span></span></div>   
</div><!--WorkPlace End-->  
</div>   