<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("complaint_query_info_new_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
//myWindow.close();
}
$(document).ready(function() {
	$('#csv').hide();
$('#csv_create1').click(function () {
	var type="Mobile";
			$.ajax({
			url:"create_csv.php",
			data: "type="+type,
			success: function(msg){
				$('div#table-here').fadeOut( function () { $('#csv').show(); });
			}
			});
				return false;
});

$('#csv_create2').click(function () {
	var type="WEB";
			$.ajax({
			url:"create_csv.php",
			data: "type="+type,
			success: function(msg){
				$('div#table-here').fadeOut( function () { $('#csv').show(); });
			}
			});
				return false;
});

$('#csv_create3').click(function () {
	var type="CSR";
			$.ajax({
			url:"create_csv.php",
			data: "type="+type,
			success: function(msg){
				$('div#table-here').fadeOut( function () { $('#csv').show(); });
			}
			});
				return false;
}); 
});
</script>
<?php
//SELECT count(type) as nos,type FROM complaint_views group by type
$qselect='SELECT count(type) as nos,type';
$qfrom='FROM complaint_views ';
$qwhere='group by type';
//get log count based on the query so far..
$total=db_count("SELECT count(*) $qfrom $qwhere");
$pagelimit=30;
//pagenate
$pageNav=new Pagenate($total,$page,$pagelimit);
$pageNav->setURL('admin.php',$qstr);
$query="$qselect $qfrom $qwhere ORDER BY date DESC ";
//echo $query;
$result = db_query($query);
$showing=db_num_rows($result)?$pageNav->showing():"";
?>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
?>

<div class="page-header"><h1>Complaints  <small> Processing Status Inquiry</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">
    <p align="right" style="float:right;">
     <a id="ticket-print" class="action-button" href="" onclick="openWin();">
     <button class="btn" type="button"><i class="icon-print"></i> Print</button></a>                              
    </p>             
</div>
</div>
<div class="row-fluid">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Complaints Status Inquiry'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">

<div id="csv" align="center">
<p> Please <a href="complaints_report.csv"> download </a> your results in CSV Version . </p></div>
   <div id="table-here">
    <form action="tickets.php" method="POST" name='tickets' id="query_search_result" onSubmit="return checkbox_checker(this,1,0);">
    <input type="hidden" name="a" value="mass_process" >
    <input type="hidden" name="status" value="<?php echo $statusss; ?>" >
   <table cellpadding="0" cellspacing="0" width="100%" class="table">	
    <thead>
            <tr>
            <th>Complaints Source</th>
            <th>Today</th>
            <th>Current Week</th>
            <th>Current Month</th>
            <th>This Year</th>
            <th>Total</th>
            <th>Quick Aciton</th>
            </tr>
            </thead>
    <tbody class="" page="1">
        <?php
		$total_todays=0;
		$total_weeks=0;
		$total_months=0;
		$total_years=0;
		
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
                <a href="javascript:toggleMessage('<?php echo $i;?>');">
                <span style="float: left; width:350px;"><?php if($row['type']!="") echo $row['type']; else echo "No Source";?></span></a>
        </th>
                &nbsp;&nbsp;
        <?php $sql_daily="SELECT count(type) as daily FROM complaint_views WHERE 0 >=TIMESTAMPDIFF(DAY,date,NOW()) AND type ='".$row['type']."'";
		$res_daily=mysql_query($sql_daily);
		$row_daily=mysql_fetch_array($res_daily);
		$check="";		
		if($row_daily['daily']==0)
			$check="-";
			else
			$check=$row_daily['daily'];
		?>       
        <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td>
        <?php $total_todays=$total_todays+$row_daily['daily']; ?>
        
        <?php $sql_weekly="SELECT count(type) as weekly FROM complaint_views WHERE 7 >=TIMESTAMPDIFF(DAY,date,NOW()) AND type ='".$row['type']."'";
		$res_weekly=mysql_query($sql_weekly);
		$row_weekly=mysql_fetch_array($res_weekly);		
			if($row_weekly['weekly']==0)
			$check="-";
			else
			$check=$row_weekly['weekly'];
			?>   
		
		
        <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td>
        <?php $total_weeks=$total_weeks+$row_weekly['weekly']; ?>
        
        <?php $sql_monthly="SELECT count(type) as monthly FROM complaint_views WHERE 30 >=TIMESTAMPDIFF(DAY,date,NOW()) AND type ='".$row['type']."'";
		$res_monthly=mysql_query($sql_monthly);
		$row_monthly=mysql_fetch_array($res_monthly);		
			 if($row_monthly['monthly']==0)
			$check="-";
			else
			$check=$row_monthly['monthly'];
		
		?> 
       <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td>
	   <?php $total_months=$total_months+$row_monthly['monthly']; ?>
       
	   <?php $sql_yearly="SELECT count(type) as yearly FROM complaint_views WHERE 375 >=TIMESTAMPDIFF(DAY,date,NOW()) AND type ='".$row['type']."'";
		$res_yearly=mysql_query($sql_yearly);
		$row_yearly=mysql_fetch_array($res_yearly);	
		 if($row_yearly['yearly']==0)
			$check="-";
			else
			$check=$row_yearly['yearly'];
		?> 
        <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td>
        <?php $total_years=$total_years+$row_yearly['yearly']; ?>
        
        <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$row['nos'].'</span></b><br />'; ?></span></td>

         
        <td id="csv_create<?php echo $i; ?>" class="action-button" style="float:left;"><i class="icon-print"></i>Export</td>
        
        <?php $report .= " [ '" . $row['type'] . "' , ". $row['nos']. " ], " ; ?>
        
		<?php $count_total +=$row['nos']; ?>   
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
         if($total_todays==0)
		 $total_todays="-";
		 if($total_weeks==0)
		 $total_weeks="-";
		 if($total_months==0)
		 $total_months="-";
		 if($total_years==0)
		 $total_years="-";
		 if($count_total==0)
		 $count_total="-";
		 
		 ?>
         <tr id="total">
        <th><span style="float: left; width:350px;">Total</span></th>
                &nbsp;&nbsp;
        <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$total_todays.'</span></b><br />'; ?></span></td>
        <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$total_weeks.'</span></b><br />'; ?></span></td>
        <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$total_months.'</span></b><br />'; ?></span></td>
        <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$total_years.'</span></b><br />'; ?></span></td>              
        <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$count_total.'</span></b><br />'; ?></span></td>
        </tr>
        </tbody>                    
		 </table>
        </div>
 </div>                      
</div>                        
<div class="dr"><span></span></div>   
</div><!--WorkPlace End-->  
</div>   
