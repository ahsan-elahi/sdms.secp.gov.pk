<?php error_reporting(0); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="js_old/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="js_old/jqplot.pieRenderer.min.js"></script>
<link rel="stylesheet" type="text/css" href="js_old/jquery.jqplot.min.css" />
<link type="text/css" rel="stylesheet" href="js_old/shThemejqPlot.min.css" />
<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("complaintdepartment_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
//myWindow.close();
}
</script>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');


$qselect='SELECT count(ticket_id) as ticketno ,t.dept_id, d.dept_name ';
$qfrom='FROM '.TICKET_TABLE.' t, sdms_department d ';
$qwhere=' WHERE t.dept_id=d.dept_id group by t.dept_id ';

//$qselect='SELECT count(ticket_id) as ticketno, d.dept_name';
//$qfrom='FROM '.TICKET_TABLE.' t INNER JOIN sdms_department d ON (t.dept_id = d.dept_id)' ;
//$qwhere='group by t.dept_id';
//get log count based on the query so far..
$total=db_count("SELECT count(*) $qfrom $qwhere");
//$aeraopen=db_num_rows($resact3days);
$pagelimit=30;
//pagenate
$pageNav=new Pagenate($total,$page,$pagelimit);
$pageNav->setURL('admin.php',$qstr);
$query="$qselect $qfrom $qwhere ORDER BY t.created DESC LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
//echo $query;
$result = db_query($query);
$showing=db_num_rows($result)?$pageNav->showing():"";

?>
<div class="page-header"><h1>Department  <small> Complaints Count</small></h1></div>
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
                <h1><?php echo 'Departments Complaints Count'; ?></h1>                               
            </div>
            <div class="block-fluid table-sorting clearfix">
            <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable">							
                <thead>
                <!--<tr>
            <th>Department Name</th>
            <th>Opened<img src="images/Opened.png"/></th>
            <th>Overdue<img src="images/Overdue.png"></th>
            <th>Closed<img src="images/Closed.png"/></th> 
            <th>Totals<img src="images/Opened.png"/></th>       
                </tr>-->
                <tr>
            <th>Department Name</th>
            <th>Totals</th>
            <th>Today</th>
            <th>This Week</th>
            <th>Last Week</th>
            <th>Current Month</th>
            <th>Last Month</th>
            <th>This Year</th>
            </tr>
                </thead>
                <tbody role="alert" aria-live="polite" aria-relevant="all">
				<?php
        $total_today=0;
		$total_this_week=0;
		$total_last_week=0;
		$total_this_month=0;
		$total_last_month=0;
		$total_years=0;
		$all_complaints=0;
        $class = "row1";
        $total=0;
        $i=1;
        if($result && ($num=db_num_rows($result))):
            while ($row = db_fetch_array($result)) {
                ?>
            <tr>
                <th>
                <a href="javascript:toggleMessage('<?php echo $i?>');">
                <span style="color:#666; float: left; width:350px;"><?php if($row['dept_name']!="") echo $row['dept_name']; else echo "No Department";?></span></a></th>
               
                <?php 
            /*$query1="SELECT count(status) as open FROM ".TICKET_TABLE."  where status = 'open' and dept_id='".$row['dept_id']."'";	
            $result1 = db_query($query1);
            $row1 = db_fetch_array($result1);*/?>
            
                <td>
            <?php
            $check="";
			if($row['ticketno']==0)
			$check="-";
			else
			$check=$row['ticketno'];
			?>
            <span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td>
            <?php $all_complaints=$all_complaints+$row['ticketno']; ?>
                <td>
        <?php $sql_daily="SELECT count(ticket_id) as daily FROM sdms_ticket  WHERE 0 >=TIMESTAMPDIFF(DAY,created,NOW()) AND dept_id ='".$row['dept_id']."' ";
        $res_daily=mysql_query($sql_daily);
        $row_daily=mysql_fetch_array($res_daily);	
		if($row_daily['daily']==0)
			$check="-";
			else
			$check=$row_daily['daily'];
		?>       
        <span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td>
            <?php $total_today=$total_today+$row_daily['daily']; ?>
                <td>    
            <?php 
            $start_thisweek = strtotime('this week');		
            $start_thisweekdate=date("Y-m-d h:i:s",$start_thisweek);
            $today_date='NOW()';
            $sql_this_week="SELECT count(ticket_id) as this_week FROM sdms_ticket WHERE created>='".$start_thisweekdate."' AND created<='".$today_date."' AND dept_id ='".$row['dept_id']."'";
            $res_this_week=mysql_query($sql_this_week);
            $row_this_week=mysql_fetch_array($res_this_week);
			if($row_this_week['this_week']==0)
			$check="-";
			else
			$check=$row_this_week['this_week'];
			?>       
            <span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td>
            <?php $total_this_week=$total_this_week+$row_this_week['this_week']; ?>
                <td>        
            <?php 
            $start_lastweek = strtotime('last week');
            $start_lastweekdate=date("Y-m-d h:i:s",$start_lastweek);
            $sql_last_week="SELECT count(ticket_id) as last_week FROM sdms_ticket WHERE created>='".$start_lastweekdate."' AND created<='".$start_thisweekdate."' AND dept_id ='".$row['dept_id']."'";
            $res_last_week=mysql_query($sql_last_week);
            $row_last_week=mysql_fetch_array($res_last_week);	
			if($row_last_week['last_week']==0)
			$check="-";
			else
			$check=$row_last_week['last_week'];
			?>       
            <span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td>
            <?php $total_last_week=$total_last_week+$row_last_week['last_week']; ?>
                <td>
            <?php 
            $start_thismonth = strtotime('first day of this month');
            $start_thismonthdate=date("Y-m-d h:i:s",$start_thismonth);
            $sql_this_month="SELECT count(ticket_id) as this_month FROM sdms_ticket WHERE created>='".$start_thismonthdate."' AND created<='".$today_date."' AND dept_id ='".$row['dept_id']."'";
            $res_this_month=mysql_query($sql_this_month);
            $row_this_month=mysql_fetch_array($res_this_month);	
			if($row_this_month['this_month']==0)
			$check="-";
			else
			$check=$row_this_month['this_month'];
			?>       
            <span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td>
            <?php $total_this_month=$total_this_month+$row_this_month['this_month']; ?>
                <td>   <?php 
            $start_lastmonth = strtotime('first day of last month');
            $start_lastmonthdate=date("Y-m-d h:i:s",$start_lastmonth);
            $sql_last_month="SELECT count(ticket_id) as last_month FROM sdms_ticket WHERE created>='".$start_lastmonthdate."' AND created<='".$start_thismonthdate."' AND dept_id ='".$row['dept_id']."'";
            $res_last_month=mysql_query($sql_last_month);
            $row_last_month=mysql_fetch_array($res_last_month);	
			if($row_last_month['last_month']==0)
			$check="-";
			else
			$check=$row_last_month['last_month'];
			?>       
            <span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td>         
			<?php $total_last_month=$total_last_month+$row_last_month['last_month']; ?>
                <td>
            <?php 
            $start_thisyear = strtotime('first day of january');
            $start_thismonthdate=date("Y-m-d h:i:s",$start_thisyear);
            $sql_this_year="SELECT count(ticket_id) as this_year FROM sdms_ticket WHERE created>='".$start_thismonthdate."' AND created<='Now()' AND dept_id ='".$row['dept_id']."'";
            $res_this_year=mysql_query($sql_this_year);
            $row_this_year=mysql_fetch_array($res_this_year);	
			if($row_this_year['this_year']==0)
			$check="-";
			else
			$check=$row_this_year['this_year'];
			?>       
            <span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td>
             <?php $total_years=$total_years+$row_this_year['this_year']; ?>
               &nbsp;&nbsp;
               
               
                 <!--<td>
			<?php //echo '<b><span align="right">'.$row1['open'].'</span></b><br />'; ?></td>-->
                <?php 
			    /*$query3="SELECT * FROM ".TICKET_TABLE." where isoverdue != '1' AND dept_id='".$row['dept_id']."'";				
            $result3 = db_query($query3);
            $num3=db_num_rows($result3);*/
			    ?>
                <!--<td><?php //echo '<b><span align="right">'.$num3.'</span></b><br />'; ?></td>-->
                 <?php 
			    /*$query4="SELECT count(status) as close FROM ".TICKET_TABLE."  where status = 'closed' and dept_id='".$row['dept_id']."'";		
            $result4 = db_query($query4);
            $row4 = db_fetch_array($result4);*/
			    ?>
                <!--<td><?php //echo '<b><span align="right">'.$row4['close'].'</span></b><br />'; ?></td>-->                    
                <!--<td>
                <span class="Icon <?php //echo $icon?>" align="right"><?php //echo '<b><span align="right">'.$row['ticketno'].'</span></b><br />'; ?></span></td>-->
                <?php //$report .= " [ '" . $row['dept_name'] . "' , ". $row['ticketno']. " ], " ; ?>
               
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
                              <span style="text-align:left;float:left;"><i><?php echo $strt?>&nbsp;&nbsp;</i></span>
                              </div>
               <?php $abc[$row['username']]=$numn; ?>
            </tr>
            <?php
            $class = ($class =='row2') ?'row1':'row2';
            $i++;
            } //end of while.
        else: //not tickets found!! ?>
            <tr class="<?php echo $class?>"><td><b>Query returned 0 results.</b></td></tr>
        <?php
        endif;
		if($all_complaints==0)
		$all_complaints="-";
		if($total_today==0)
		$total_today="-";
		if($total_this_week==0)
		$total_this_week="-";
		if($total_last_week==0)
		$total_last_week="-";
		if($total_this_month==0)
		$total_this_month="-";
		if($total_last_month==0)
		$total_last_month="-";
		if($total_years==0)
		$total_years="-";
		 ?>
         <tr id="total">
        <th><span style="float: left; width:350px;">Total</span></th>
                &nbsp;&nbsp;
            <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$all_complaints.'</span></b><br />'; ?></span></td>
            <td><b><?php echo $total_today; ?></b></td>
            <td><b><?php echo $total_this_week; ?></b></td>
            <td><b><?php echo $total_last_week; ?></b></td>
            <td><b><?php echo $total_this_month; ?></b></td>
            <td><b><?php echo $total_last_month; ?></b></td>
            <td><b><?php echo $total_years; ?></b></td>  
        </tr>
                
                    
                    <!--<tr>
                    <td colspan="6">                    
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
                    </td>
                    </tr>-->              
                </tbody>                              
            </table>
            </div>
        </div>                      
    </div>
</form>                        
		<div class="dr"><span></span></div>   
   </div><!--WorkPlace End-->  
   </div>   