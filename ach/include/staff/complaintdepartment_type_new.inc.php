<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("complaintdepartment_type_new_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
//myWindow.close();
}
</script>

<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
?>
<div class="page-header"><h1>Complaints Volume Summary<small> By Department Type</small></h1></div>
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
            <h1><?php echo 'Complaint Volume Summary: By Department Type'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
            <table cellpadding="0" cellspacing="0" width="100%" class="table">	

    <form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
    <input type="hidden" name="a" value="mass_process" >
    <input type="hidden" name="status" value="<?php echo $statusss?>" >
      <table cellpadding="0" cellspacing="0" width="100%" class="table">	
    <thead>
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
    <tbody class="" page="1">
<?php 
			$total_today=0;
		$total_this_week=0;
		$total_last_week=0;
		$total_this_month=0;
		$total_last_month=0;
		$total_years=0;
			$m_sql="SELECT * FROM sdms_department WHERE dept_p_id='0'";
			$m_res=mysql_query($m_sql);
			$total_complaint=0;
			while($m_row=mysql_fetch_array($m_res))
			{
				$sub_sql="SELECT *  FROM sdms_department WHERE dept_p_id	='".$m_row['dept_id']."'";
				$sub_res=mysql_query($sub_sql);
				while($sub_row=mysql_fetch_array($sub_res))
				{
				$qselect='SELECT count(ticket_id) as one_total FROM '.TICKET_TABLE.'  WHERE dept_id="'.$sub_row['dept_id'].'"';
				$qselect_res=mysql_query($qselect);
				$qselect_row=mysql_fetch_array($qselect_res);
				$total_complaint += $qselect_row['one_total'];
				}
				$net_total += $total_complaint;
				if($total_complaint==0)
				$total_complaint="-";
				
				echo '<tr><td id="tdStyle" style="text-align:left">'.$m_row['dept_name'].'</td><td><b>'.$total_complaint.'</b></td>';
				
				?>
				<td>
            <?php //Today
			$sql_daily="SELECT count(ticket_id) as daily FROM sdms_ticket WHERE 0 >=TIMESTAMPDIFF(DAY,created,NOW()) AND dept_id ='".$sub_row['dept_id']."'";
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
				<?php $total_complaint=0;?>
                <td>    
            <?php // for week 
            $start_thisweek = strtotime('this week');		
            $start_thisweekdate=date("Y-m-d h:i:s",$start_thisweek);
            $today_date='NOW()';
            $sql_this_week="SELECT count(ticket_id) as this_week FROM sdms_ticket WHERE created>='".$start_thisweekdate."' AND created<='".$today_date."' AND  dept_id ='".$sub_row['dept_id']."'";
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
            <?php //last week
            $start_lastweek = strtotime('last week');
            $start_lastweekdate=date("Y-m-d h:i:s",$start_lastweek);
            $sql_last_week="SELECT count(ticket_id) as last_week FROM sdms_ticket WHERE created>='".$start_lastweekdate."' AND created<='".$start_thisweekdate."' AND  dept_id ='".$sub_row['dept_id']."'";
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
            <?php //month
            $start_thismonth = strtotime('first day of this month');
            $start_thismonthdate=date("Y-m-d h:i:s",$start_thismonth);
            $sql_this_month="SELECT count(ticket_id) as this_month FROM sdms_ticket WHERE created>='".$start_thismonthdate."' AND created<='".$today_date."' AND  dept_id ='".$sub_row['dept_id']."'";
            $res_this_month=mysql_query($sql_this_month);
            $row_this_month=mysql_fetch_array($res_this_month);	
			if($row_this_month['this_month']==0)
			$check="-";
			else
			$check=$row_this_month['this_month'];
			?>       
            <span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td>
            <?php $total_this_month=$total_this_month+$row_this_month['this_month']; ?> 
            <td>   <?php //last month
            $start_lastmonth = strtotime('first day of last month');
            $start_lastmonthdate=date("Y-m-d h:i:s",$start_lastmonth);
            $sql_last_month="SELECT count(ticket_id) as last_month FROM sdms_ticket WHERE created>='".$start_lastmonthdate."' AND created<='".$start_thismonthdate."' AND  dept_id ='".$sub_row['dept_id']."'";
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
            <?php //this year
            $start_thisyear = strtotime('first day of january');
            $start_thismonthdate=date("Y-m-d h:i:s",$start_thisyear);
            $sql_this_year="SELECT count(ticket_id) as this_year FROM sdms_ticket WHERE created>='".$start_thismonthdate."' AND created<='Now()' AND  dept_id ='".$sub_row['dept_id']."'";
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
				
			<?php }	?>
            
           
           
         <!--<tr class="<?php //echo $class?>"><td><b>Query returned 0 results.</b></td></tr>-->
        <tr id="total">
        <th><span style="float: left; width:350px;">Total</span></th>
                &nbsp;&nbsp;
                <?php
                if($net_total==0)
				$net_total="-";
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
            <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$net_total.'</span></b><br />'; ?></span></td>
            <td><b><?php echo $total_today; ?></b></td>
            <td><b><?php echo $total_this_week; ?></b></td>
            <td><b><?php echo $total_last_week; ?></b></td>
            <td><b><?php echo $total_this_month; ?></b></td>
            <td><b><?php echo $total_last_month; ?></b></td>
            <td><b><?php echo $total_years; ?></b></td>  
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


