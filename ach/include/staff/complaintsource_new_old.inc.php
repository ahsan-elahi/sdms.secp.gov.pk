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

<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

if($_POST['from_date']!='' && $_POST['to_date']!='')
{
$from_to_date = ' AND DATE(created) >= "'.date('Y-m-d',strtotime($_POST['from_date'])).'" AND DATE(created) <= "'.date('Y-m-d',strtotime($_POST['to_date'])).'"  ';
}else{
$from_to_date ='';
}
?>
<div class="page-header"><h1>Complaints Source<small> Summary</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">
    <p align="right" style="float:right;">
      <a href="complaintsource_new_csv.csv"><input style="float:left;display:none;" id="csv_download" type="button"  value="Dowanlod CSV" name="download_csv"  ></a>
     <button class="btn" type="button" id="csv_download" onClick="export_to_csv()"><i class="icon-print"></i> Export</button>          
    </p>             
</div>
</div>
<?php if($thisstaff->isAdmin() || $thisstaff->onChairman() == '1'){ ?>
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
<?php }?>
<div class="row-fluid">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1>Complaints Source</h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
           	
    <form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
    <input type="hidden" name="a" value="mass_process" >
    <input type="hidden" name="status" value="<?php echo $statusss;?>" >
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
$qwhere='WHERE `isquery`=0 '.$from_to_date.' group by source';
//get log count based on the query so far..
$total=db_count("SELECT count(*) $qfrom $qwhere");
$pagelimit=30;
//pagenate
$pageNav=new Pagenate($total,$page,$pagelimit);
$pageNav->setURL('admin.php',$qstr);
$query="$qselect $qfrom $qwhere"; 
//ORDER BY t.created DESC LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
//echo $query;
$result = db_query($query);
$showing=db_num_rows($result)?$pageNav->showing():"";
/*$day_sqlact3daysopen='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  0 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND status = "open"';*/
		$total_daily=0;
		$total_weekly=0;
		$total_monthly=0;
		$total_yearly=0;
        $class = "row1";
        $total=0;
        $i=1;
		$source=0;
		$count=1;
        if($result && ($num=db_num_rows($result))):
		  while ($row = db_fetch_array($result)) {
                ?>
        <tr class="<?php echo $class;?> " id="<?php echo $i;?>">
        <th>
                <a href="javascript:toggleMessage('<?php echo $i?>');">
                <span style="float: left; width:350px;"><?php if($row['source']!="") echo $row['source']; else echo "No Source";?></span></a>
        </th>
        <?php $sql_daily="SELECT count(ticketID) as daily FROM ost_ticket WHERE 0 >=TIMESTAMPDIFF(DAY,created,NOW()) AND source ='".$row['source']."'";
		$res_daily=mysql_query($sql_daily);
		$row_daily=mysql_fetch_array($res_daily);		
		if($row_daily['daily']==0)
		$check="-";
		else
		$check=$row_daily['daily'];

		?>
      
        <?php $total_daily=$total_daily+$row_daily['daily']; ?>
         
         <?php $sql_weekly="SELECT count(ticketID) as weekly FROM ost_ticket WHERE 7 >=TIMESTAMPDIFF(DAY,created,NOW()) AND source ='".$row['source']."'";
		$res_weekly=mysql_query($sql_weekly);
		$row_weekly=mysql_fetch_array($res_weekly);		
		if($row_weekly['weekly']==0)
		$check="-";
		else
		$check=$row_weekly['weekly'];
		?>
    
         <?php $total_weekly=$total_weekly+$row_weekly['weekly']; ?>
        
        <?php $sql_monthly="SELECT count(ticketID) as monthly FROM ost_ticket WHERE 30 >=TIMESTAMPDIFF(DAY,created,NOW()) AND source ='".$row['source']."'";
		$res_monthly=mysql_query($sql_monthly);
		$row_monthly=mysql_fetch_array($res_monthly);		
		if($row_monthly['monthly']==0)
		$check="-";
		else
		$check=$row_monthly['monthly'];
		?>
      
        <?php $total_monthly=$total_monthly+$row_monthly['monthly']; ?>
       
        <?php $sql_yearly="SELECT count(ticketID) as yearly FROM ost_ticket WHERE 375 >=TIMESTAMPDIFF(DAY,created,NOW()) AND source ='".$row['source']."'";
		$res_yearly=mysql_query($sql_yearly);
		$row_yearly=mysql_fetch_array($res_yearly);	
		if($row_yearly['yearly']==0)
		$check="-";
		else
		$check=$row_yearly['yearly'];
		?>
       
         <?php $total_yearly=$total_yearly+$row_yearly['yearly']; ?>
                
                 &nbsp;&nbsp;
                 <?php
            if($row['ticketno']==0)
            $check="-";
            else
            $check=$row['ticketno'];
                     ?>
        <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td> 
        <?php $report .= " [ '" . $row['source'] . "' , ". $row['ticketno']. " ], " ; ?>
		<?php $count_total +=$row['ticketno']; ?>   
			 <div id="msg_<?php echo $i;?>" class="hide">
                <hr>
                <?php
                $sqlt='SELECT ticketID, ticket_id FROM '.TICKET_TABLE.' WHERE location='.$row['location'].'';
                if(($rest=db_query($sqlt)) && ($numt=db_num_rows($rest))) {
                    $strt='';
                    $it=1;
                    while ($rowt = db_fetch_array($rest)){
                    if ($it<$numt) {
                        $strt.='<a href="tickets.php?id='.$rowt['ticket_id'].'" target="_blank">'.$rowt['ticketID'].'</a>, ';
                    } elseif ($it>=$numt) {
                      $strt.='<a href="tickets.php?id='.$rowt['ticket_id'].'" target="_blank">'.$rowt['ticketID'].'</a>';
                    }
                    $it++;
                    }
                   }
                ?>
                  <span style="text-align:left;float:left;"><i><?=$strt?>&nbsp;&nbsp;</i></span>
                  </div>
                  <?php
				$abc[$row['username']]=$numn;
			     ?>
                  
                </tr>           
            <?php
            $class = ($class =='row2') ?'row1':'row2';
            $i++;
            } //end of while.
        else: //not tickets found!! ?>
            <tr class="<?php $class;?>"><td><b>Query returned 0 results.</b></td></tr>
        <?php endif; ?>
         <?php
         if($total_daily==0)
		 $total_daily="-";
		 if($total_weekly==0)
		 $total_weekly="-";
		 if($total_monthly==0)
		 $total_monthly="-";
		 if($total_yearly==0)
		 $total_yearly="-";
		 if($count_total==0)
		 $count_total="-";
		 ?>
         <tr id="total">
         
        <th><span style="float: left; width:350px;">Total</span></th>
                &nbsp;&nbsp;

        <td><b><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$count_total.'</span></b><br />'; ?></span></td>
        </tr>
        </tbody>                    
		</table>
    </form>
        </div>
 </div>                      
</div>                        
<div class="dr"><span></span></div>   
</div><!--WorkPlace End-->  
</div>   


