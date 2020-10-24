
<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("comlainttopics_new_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
//myWindow.close();
}
</script>

<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
?>
<?php 				
$qselect='SELECT * ';
$qfrom='FROM sdms_help_topic d ';
$qwhere=' WHERE d.topic_pid=0 group by d.topic_id ';

$total=db_count("SELECT count(*) $qfrom $qwhere");
//$aeraopen=db_num_rows($resact3days);
$pagelimit=30;
//pagenate
$pageNav=new Pagenate($total,$page,$pagelimit);
$pageNav->setURL('admin.php',$qstr);
$query="$qselect $qfrom $qwhere ORDER BY d.created DESC LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();

$result = db_query($query);
$showing=db_num_rows($result)?$pageNav->showing():"";
?>
<div class="page-header"><h1>Complaints Volume Summary  <small> By Type</small></h1></div>
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
            <h1><?php echo 'Complaints Volume Summary: By Type'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
            

    <form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
    <input type="hidden" name="a" value="mass_process" >
    <input type="hidden" name="status" value="<?php echo $statusss?>" >
   <table cellpadding="0" cellspacing="0" width="100%" class="table">
    <thead>
            <tr>
            <th>Type of Complaint</th>
            <th>Today</th>
            <th>This Week</th>
            <th>Last Week</th>
            <th>Current Month</th>
            <th>Last Month</th>
            <th>This Year</th>
            <!--<th>Total</th>-->
            </tr>
            </thead>
    <tbody class="" page="1">
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
                <span style="float: left; width:350px;"><?php if($row['topic']!="") echo $row['topic']; else echo "No Topic";?></span></a></th>
                <?php $report .= " [ '" . $row['topic'] . "' , ". $row['ticketno']. " ], " ; ?>
            <td>
            <?php $sql_daily="SELECT count(ticket_id) as daily FROM sdms_ticket WHERE 0 >=TIMESTAMPDIFF(DAY,created,NOW()) AND  	topic_id ='".$row['topic_id']."'";
            $res_daily=mysql_query($sql_daily);
            $row_daily=mysql_fetch_array($res_daily);
			$check="";	
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
            $sql_this_week="SELECT count(ticket_id) as this_week FROM sdms_ticket WHERE created>='".$start_thisweekdate."' AND created<='".$today_date."' AND  	topic_id ='".$row['topic_id']."'";
            $res_this_week=mysql_query($sql_this_week);
            $row_this_week=mysql_fetch_array($res_this_week);
			if($row_this_week['this_week']==0)
			$check="-";
			else
			$check=$row_this_week['this_week'];
			?>       
            <span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td>            <?php $total_this_week=$total_this_week+$row_this_week['this_week']; ?>
            
            <td>        
            <?php 
            $start_lastweek = strtotime('last week');
            $start_lastweekdate=date("Y-m-d h:i:s",$start_lastweek);
            $sql_last_week="SELECT count(ticket_id) as last_week FROM sdms_ticket WHERE created>='".$start_lastweekdate."' AND created<='".$start_thisweekdate."' AND topic_id ='".$row['topic_id']."'";
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
            $sql_this_month="SELECT count(ticket_id) as this_month FROM sdms_ticket WHERE created>='".$start_thismonthdate."' AND created<='".$today_date."' AND topic_id ='".$row['topic_id']."'";
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
            $sql_last_month="SELECT count(ticket_id) as last_month FROM sdms_ticket WHERE created>='".$start_lastmonthdate."' AND created<='".$start_thismonthdate."' AND topic_id ='".$row['topic_id']."'";
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
            $sql_this_year="SELECT count(ticket_id) as this_year FROM sdms_ticket WHERE created>='".$start_thismonthdate."' AND created<='Now()' AND topic_id ='".$row['topic_id']."'";
            $res_this_year=mysql_query($sql_this_year);
            $row_this_year=mysql_fetch_array($res_this_year);	
			if($row_this_year['this_year']==0)
			$check="-";
			else
			$check=$row_this_year['this_year'];
			?>       
            <span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td>
              <?php $total_years=$total_years+$row_this_year['this_year']; ?>                
            </tr>
            <?php
            $class = ($class =='row2') ?'row1':'row2';
            $i++;
            } //end of while.
        else: //not tickets found!! ?>
            <tr class="<?php echo $class?>"><td><b>Query returned 0 results.</b></td></tr>
        <?php
        endif; ?>
        <tr id="total">
        <th><span style="float: left; width:350px;">Total</span></th>
                &nbsp;&nbsp;
                <?php
                if($total_today==0)
			$total_today="-";
				?>
            <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$total_today.'</span></b><br />'; ?></span></td>
            <td><b><?php
			if($total_this_week==0)
			echo "-";
			else
			echo $total_this_week; ?></b></td>
            <td><b><?php
			if($total_last_week==0)
			echo "-";
			else
			echo $total_last_week; ?></b></td>
            <td><b><?php
			if($total_this_month==0)
			echo "-";
			else
			echo $total_this_month; ?></b></td>
            <td><b><?php 
			if($total_last_month==0)
			echo "-";
			else
			echo $total_last_month; ?></b></td>
            <td><b><?php 
			if($total_years==0)
			echo "-";
			else
			echo $total_years; ?></td> 
        </tr>
        </tbody>                    
	 </table>
        </div>
 </div>                      
</div>                        
<div class="dr"><span></span></div>   
</div><!--WorkPlace End-->  
</div>   

