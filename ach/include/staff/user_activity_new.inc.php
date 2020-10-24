<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("user_activity_new_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
//myWindow.close();
}
</script>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
?>
<div class="page-header"><h1>User Activity  <small> Summary</small></h1></div>
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
            <h1><?php echo 'User Activity Summary'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
        
<?php 
$qselect='SELECT *';
$qfrom='FROM sdms_staff ';
$qwhere='group by username';

//get log count based on the query so far..
$total=db_count("SELECT count(*) $qfrom $qwhere");

//$aeraopen=db_num_rows($resact3days);
$pagelimit=30;
//pagenate
$pageNav=new Pagenate($total,$page,$pagelimit);
$pageNav->setURL('admin.php',$qstr);
$query="$qselect $qfrom $qwhere ORDER BY created DESC LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
//echo $query;
$result = db_query($query);
$showing=db_num_rows($result)?$pageNav->showing():"";
?>
    <form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
    <input type="hidden" name="a" value="mass_process" >
    <input type="hidden" name="status" value="<?php echo $statusss;?>" >
           <table cellpadding="0" cellspacing="0" width="100%" class="table">		
    <thead>
            <tr>
            <th>Group and User</th>
            <th>Login</th>
            <th>Checked Status of Complaint</th>
            <th>View Report</th>      
            </tr>
            </thead>
    <tbody class="" page="1">
		<?php
		$total_login=0;
		$total_checked=0;
		$total_reports=0;
        $class = "row1";
        $total=0;
        $i=1;
        if($result && ($num=db_num_rows($result))):
            while ($row = db_fetch_array($result)) {
                ?>
        <tr>
            <th>
            <a href="javascript:toggleMessage('<?php echo $i?>');">
            <span style="float: left; width:350px;"><?php if($row['username']!="") echo $row['username']; else echo "No Staff";?></span></a></th>
            <td>
            <?php 
			$sql_login="SELECT count(user_id) as login FROM sdms_session WHERE user_id ='".$row['staff_id']."'";
            $res_login=mysql_query($sql_login);
            $row_login=mysql_fetch_array($res_login);	
			$check="";
			if($row_login['login']==0)
			$check="-";
			else
			$check=$row_login['login'];
			?> 
            <span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td>
            <?php $total_login=$total_login+ $row_login['login'];?>
            <td>
            <?php $sql_daily="SELECT count(ticket_id) as daily FROM sdms_ticket WHERE 0 >=TIMESTAMPDIFF(DAY,created,NOW()) AND location ='".$row['location']."'";
            $res_daily=mysql_query($sql_daily);
            $row_daily=mysql_fetch_array($res_daily);	
			if($row_daily['daily']==0)
			$check="-";
			else
			$check=$row_daily['daily'];
			?>       
            <span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td>
             <?php $total_checked=$total_checked+ $row_daily['daily'];?>
            <td>
            <?php $sql_daily="SELECT count(ticket_id) as daily FROM sdms_ticket WHERE 0 >=TIMESTAMPDIFF(DAY,created,NOW()) AND location ='".$row['location']."'";
            $res_daily=mysql_query($sql_daily);
            $row_daily=mysql_fetch_array($res_daily);	
			if($row_daily['daily']==0)
			$check="-";
			else
			$check=$row_daily['daily'];
			?>       
            <span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td>
            <?php $total_reports=$total_reports+ $row_daily['daily'];?>                   
            </tr>
            <?php
            $class = ($class =='row2') ?'row1':'row2';
            $i++;
            } //end of while.
        else: //not tickets found!! ?>
            <tr class="<?php echo $class?>"><td><b>Query returned 0 results.</b></td></tr>
        <?php
        endif; ?>
        <?php
        if($total_login==0)
		$total_login="-";
		if($total_checked==0)
		$total_checked="-";
		if($total_reports==0)
		$total_reports="-";
		?>
        <tr id="total">
        	<th><span style="float: left; width:350px;">Total</span></th>
            &nbsp;&nbsp;
            <td><b><?php echo $total_login?></b></td>
            <td><b><?php echo $total_checked?></b></td>
        <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$total_reports.'</span></b><br />'; ?></span></td>
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