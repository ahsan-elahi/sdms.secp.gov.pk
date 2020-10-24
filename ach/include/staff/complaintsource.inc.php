<?php error_reporting(0); ?>
<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("complaintsource_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
//myWindow.close();
}
</script>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

$qselect='SELECT count(ticket_id) as ticketno,source';
$qfrom='FROM '.TICKET_TABLE.' t';
$qwhere='group by source WHERE `isquery`=0';
//get log count based on the query so far..
$total=db_count("SELECT count(*) $qfrom $qwhere");
$pagelimit=30;
//pagenate
$pageNav=new Pagenate($total,$page,$pagelimit);
$pageNav->setURL('admin.php',$qstr);
echo $query="$qselect $qfrom $qwhere ORDER BY t.created DESC LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
//echo $query;
$result = db_query($query);
$showing=db_num_rows($result)?$pageNav->showing():"";

?>
<div class="page-header"><h1>Complaints  <small> Source Data</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">
    <p align="right" style="float:right;">
     <a id="ticket-print" class="action-button" href="" onclick="openWin();">
     <button class="btn" type="button"><i class="icon-print"></i> Print</button></a>                              
    </p>             
</div>
</div>
<form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
<input type="hidden" name="a" value="mass_process" >
<input type="hidden" name="status" value="<?php echo $statusss?>" >
<div class="row-fluid">
    <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Complaints Source Data'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
            <table cellpadding="0" cellspacing="0" width="100%" class="table">							
                <thead>
                    <tr>
            <th>Channel</th>
          
             <th>Total</th>  
            </tr>
                </thead>
                <tbody role="alert" aria-live="polite" aria-relevant="all">
                <?php 
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
                    <tr id="<?php echo $i?>">
                    
                     
            <?php $sql_daily="SELECT count(ticketID) as daily FROM sdms_ticket WHERE 0 >=TIMESTAMPDIFF(DAY,created,NOW()) AND source ='".$row['source']."'";
		$res_daily=mysql_query($sql_daily);
		$row_daily=mysql_fetch_array($res_daily);		
		if($row_daily['daily']==0)
		$check="-";
		else
		$check=$row_daily['daily'];

		?>
                    
                    
                    <?php $total_daily=$total_daily+$row_daily['daily']; ?>
                    
            <?php $sql_weekly="SELECT count(ticketID) as weekly FROM sdms_ticket WHERE 7 >  =TIMESTAMPDIFF(DAY,created,NOW()) AND source ='".$row['source']."'";
		$res_weekly=mysql_query($sql_weekly);
		$row_weekly=mysql_fetch_array($res_weekly);		
		if($row_weekly['weekly']==0)
		$check="-";
		else
		$check=$row_weekly['weekly'];
		?>
        
        <?php $total_weekly=$total_weekly+$row_weekly['weekly']; ?>
        
        <?php $sql_monthly="SELECT count(ticketID) as monthly FROM sdms_ticket WHERE 30 >=TIMESTAMPDIFF(DAY,created,NOW()) AND source ='".$row['source']."'";
		$res_monthly=mysql_query($sql_monthly);
		$row_monthly=mysql_fetch_array($res_monthly);		
		if($row_monthly['monthly']==0)
		$check="-";
		else
		$check=$row_monthly['monthly'];
		?>
        
        <?php $total_monthly=$total_monthly+$row_monthly['monthly']; ?>
        
        <?php $sql_yearly="SELECT count(ticketID) as yearly FROM sdms_ticket WHERE 375 >=TIMESTAMPDIFF(DAY,created,NOW()) AND source ='".$row['source']."'";
		$res_yearly=mysql_query($sql_yearly);
		$row_yearly=mysql_fetch_array($res_yearly);	
		if($row_yearly['yearly']==0)
		$check="-";
		else
		$check=$row_yearly['yearly'];
		?>
        <td>&nbsp;</td>
        <?php $total_yearly=$total_yearly+$row_yearly['yearly']; ?>
        
           &nbsp;&nbsp;
                 <?php
            if($row['ticketno']==0)
            $check="-";
            else
            $check=$row['ticketno'];
                     ?>
        <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td> 
        
                    
                    <!--<td><?php echo '<b>'.$row['ticketno'].'</span></b>'; ?></td>--> 
					<?php $report .= " [ '" . $row['source'] . "' , ". $row['ticketno']. " ], " ; ?>
                    <?php $count_total +=$row['ticketno']; ?>   
                    <div id="msg_<?php echo $i?>" class="hide">
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
                    <span style="text-align:left;float:left;"><i><?php echo $strt; ?>&nbsp;&nbsp;</i></span>
                    </div>
                    <?php $abc[$row['username']]=$numn; ?>  
                    </tr>
				<?php $class = ($class =='row2') ?'row1':'row2';
                $i++;
                } //end of while.
                else: //not tickets found!! ?>
                <tr class="<?php echo $class; ?>">
                <td><b>Query returned 0 results.</b></td></tr>
                <?php endif; ?>
                <tr >
                <th><span style="color:#666; float: left; width:350px;">Total</span></th>
                &nbsp;&nbsp;
   
                <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$count_total.'</span></b><br />'; ?></span></td>
                </tr>
                <tr>
                <!--<td colspan="2"> 
                
                <script class="code" type="text/javascript">
                $(document).ready(function(){
                var data = [ <?php //echo $report; ?> ];
                var plot1 = jQuery.jqplot ('pie1', [data], 
                { 
                seriesDefaults: {
                // Make this a pie chart.
                renderer: jQuery.jqplot.PieRenderer, 
                rendererOptions: {
                // Put data labels on the pie slices.
                // By default, labels show the percentage of the slice.
                showDataLabels: true
                }
                }, 
                legend: { show:true, location: 'e' }
                }
                );
                
                });
                </script>
                <div id="pie1" style="margin-top:20px; margin-left:210px; width:500px; height:500px;"></div>
                </td>-->
                </tr>       
               </tbody>                              
            </table>
        </div>
	</div>                      
</div>
</form>  
                <div class="dr"><span></span></div>  
<!-- <div class="row-fluid">

                    <div class="span12">
                        <div class="head clearfix">
                            <div class="isw-right_circle"></div>
                            <h1>Pie charts</h1>
                        </div>
                        <div class="block">
                            <div id="chart-3" style="height: 300px;">

                            </div>
                        </div>
                    </div>

                                 

                </div>             
                                    
<div class="dr"><span></span></div> -->  
</div><!--WorkPlace End-->  
</div>   
