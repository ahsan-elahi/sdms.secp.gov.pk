<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
$csv='';						 		 		
$csv.='"Channel"'.','.'"Total Queries"'.',';
$csv.="\n";
if($_POST['from_date']!='' && $_POST['to_date']!='')
{
$from_to_date = ' AND DATE(created) >= "'.date('Y-m-d',strtotime($_POST['from_date'])).'" AND DATE(created) <= "'.date('Y-m-d',strtotime($_POST['to_date'])).'"  ';
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
<div class="page-header"><h1>Queries Source<small> Summary</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">
    <p align="right" style="float:right;">
      <a href="query_source_report.csv"><button class="btn" type="button" ><i class="icon-print"></i> Export</button></a>          
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
<th width="20%" style="padding-top:12px;">From Date</th>
<td>
<input type="text" name="from_date" id="Datepicker" required value="<?php echo $_POST['from_date']; ?>" >
</td>
<th width="20%" style="padding-top:12px;">To Date</th>
<td>
<input type="text" name="to_date" id="Datepicker1" required value="<?php echo $_POST['to_date']; ?>" >
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
            <h1>Queries Source</h1>                               
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
$qwhere='WHERE `isquery`=1 '.$from_to_date.' group by source';
$total=db_count("SELECT count(*) $qfrom $qwhere");
$pagelimit=30;
$query="$qselect $qfrom $qwhere"; 
$result = db_query($query);

        $total=0;
        $i=1;
		$source=0;
		$count=1;
        if($result && ($num=db_num_rows($result))):
		  while ($row = db_fetch_array($result)) {
                ?>
        <tr >
        <th>
         <span style="float: left; width:350px;"><?php if($row['source']!="") echo $row['source']; else echo "No Source";?></span>
        </th>
                
        <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$row['ticketno'].'</span></b><br />'; ?></span></td> 
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
        <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$count_total.'</span></b><br />'; ?></span></td>
        </tr>
        <?php
		
		$csv.='"Total"'.','.'"'.$count_total.'"'.',';
		$csv.="\n";	
			
		$file = 'query_source_report.csv';
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