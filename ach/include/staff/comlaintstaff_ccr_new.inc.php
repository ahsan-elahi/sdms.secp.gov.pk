<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("comlaintstaff_ccr_new_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
//myWindow.close();
}
</script>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
?>
<div class="page-header"><h1>CCR  <small> Performance</small></h1></div>
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
            <h1><?php echo 'CCR - Performance'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
            		
<?php
$qselect='SELECT * ';
$qfrom='FROM '.STAFF_TABLE.' s ';
$qwhere=' WHERE s.staff_id <> "0"  AND s.group_id=4 ';
//get log count based on the query so far..
$total=db_count("SELECT count(*) $qfrom $qwhere");
$pagelimit=30;
//pagenate
$pageNav=new Pagenate($total,$page,$pagelimit);
$pageNav->setURL('admin.php',$qstr);
$query="$qselect $qfrom $qwhere ORDER BY s.created ASC LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
//echo $query;exit;
$result = db_query($query);
$showing=db_num_rows($result)?$pageNav->showing():"";
?>
    <form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
    <input type="hidden" name="a" value="mass_process" >
    <input type="hidden" name="status" value="<?php echo $statusss;?>" >
<table cellpadding="0" cellspacing="0" width="100%" class="table">	
    
    <thead>
  <tr>
    <th rowspan="2" align="justify">CCR Name</th>
    <th colspan="6">Number of Complaints</th>
  </tr>
  <tr>
    <th>Today</th>
    <th>This Week</th>
    <th>Last Week</th>
    <th>Last Month</th>
    <th>This Year</th>
    <th>Total Complaints</th>
  </tr>
            </thead>
    <tbody class="" page="1">
  
    <?php
	$total_today=0;
		$total_this_week=0;
		$total_last_week=0;
		$total_last_month=0;
		$total_years=0;
		$all_complaints=0;
        $class = "row1";
        $total=0;
        $i=1;
				
        if($result && ($num=db_num_rows($result))):
		  while ($row = db_fetch_array($result)) {
               $total_completed=0; ?>
        <tr class="<?php echo $class;?> " id="<?php echo $i;?>">
        <th>
                <a href="javascript:toggleMessage('<?php echo $i;?>');">
                <span style="float: left; width:350px;"><?php echo $row['username']." (".$row['firstname']." ".$row['lastname'].")";?></span></a>
                </th>      
				<?php 
				$today_date=date("Y-m-d H:i:s");
                $query2="SELECT  * FROM sdms_ticket_thread WHERE staff_id='".$row['staff_id']."' And date(created)='".date('Y-m-d',strtotime($today_date))."'";
                $result2 = db_query($query2);
                $num2=db_num_rows($result2);
				$check="";
				$total_completed = $num2;
				if($num2==0)
				$check="-";
				else
				$check=$num2;
                ?>
                <td><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></td>      
                <?php $total_today=$total_today+$num2; ?>
                <td>    
            <?php 
            $start_thisweek = strtotime('this week');		
            $start_thisweekdate=date("Y-m-d h:i:s",$start_thisweek);
            $today_date='NOW()';
            $sql_this_week="SELECT count(staff_id) as this_week FROM sdms_ticket_thread WHERE created>='".$start_thisweekdate."' AND created<='".$today_date."' AND staff_id ='".$row['staff_id']."'";
            $res_this_week=mysql_query($sql_this_week);
            $row_this_week=mysql_fetch_array($res_this_week);
			$total_completed += $row_this_week['this_week'];
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
            $sql_last_week="SELECT count(staff_id) as last_week FROM sdms_ticket_thread WHERE created>='".$start_lastweekdate."' AND created<='".$start_thisweekdate."' AND staff_id ='".$row['staff_id']."'";
            $res_last_week=mysql_query($sql_last_week);
            $row_last_week=mysql_fetch_array($res_last_week);
			$total_completed += $row_last_week['last_week'];	
			if($row_last_week['last_week']==0)
				$check="-";
				else
				$check=$row_last_week['last_week'];
			?>       
            <span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td>            <?php $total_last_week=$total_last_week+$row_last_week['last_week']; ?>
            <td>   <?php 
            $start_lastmonth = strtotime('first day of last month');
            $start_lastmonthdate=date("Y-m-d h:i:s",$start_lastmonth);
            $sql_last_month="SELECT count(staff_id) as last_month FROM sdms_ticket_thread WHERE created>='".$start_lastmonthdate."' AND created<='".$start_thismonthdate."' AND staff_id ='".$row['staff_id']."'";
            $res_last_month=mysql_query($sql_last_month);
            $row_last_month=mysql_fetch_array($res_last_month);	
			$total_completed += $row_last_month['last_month'];
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
            $sql_this_year="SELECT count(staff_id) as this_year FROM sdms_ticket_thread WHERE created>='".$start_thismonthdate."' AND created<='Now()' AND staff_id ='".$row['staff_id']."'";
            $res_this_year=mysql_query($sql_this_year);
            $row_this_year=mysql_fetch_array($res_this_year);	
			$total_completed += $row_this_year['this_year'];
			if($row_this_year['this_year']==0)
				$check="-";
				else
				$check=$row_this_year['this_year'];
			?>       
            <span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td>
               <?php $total_years=$total_years+$row_this_year['this_year']; ?>
               <?php 
			   if($total_completed==0) 
			   $total_completed="-";
			   ?>
             <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$total_completed.'</span></b><br />'; ?></span></td>
             <?php $all_complaints=$all_complaints+$total_completed; ?>    
                  <?php
				$abc[$row['username']]=$numn;
			     ?>
            </tr>
            <?php
            $class = ($class =='row2') ?'row1':'row2';
            $i++;
			
            } //end of while.
        else: //not tickets found!! ?>
            <tr class="<?php echo $class;?>"><td><b>Query returned 0 results.</b></td></tr>
        <?php
        endif; ?>
        <?php  
		if($total_today==0)
		$total_today="-";
		if($total_this_week==0)
		$total_this_week="-";
		if($total_last_week==0)
		$total_last_week="-";
		if($total_last_month==0)
		$total_last_month="-";
		if($total_years==0)
		$total_years="-";
		if($all_complaints==0)
		$all_complaints="-";
		?>
        <tr id="total">
        <th><span style="float: left; width:350px;">Total</span></th>
                &nbsp;&nbsp;
       <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$total_today.'</span></b><br />'; ?></span></td>
       <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$total_this_week.'</span></b><br />'; ?></span></td>
       <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$total_last_week.'</span></b><br />'; ?></span></td>
       <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$total_last_month.'</span></b><br />'; ?></span></td>
       <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$total_years.'</span></b><br />'; ?></span></td>
       <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$all_complaints.'</span></b><br />'; ?></span></td>  
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


