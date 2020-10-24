<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("role_activity_new_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
//myWindow.close();
}
</script>

<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

?>
<div class="row-fluid">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1>Search</h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
          <form action="role_activity_new.php" method="POST">
           <?php csrf_token(); ?>
          <table width="100%" cellpadding="0" cellspacing="0" class="table" style="line-height:35px;">
          <tr>
            <td colspan="3">Date</td>
            <td><input type="text" value="<?php if (isset($_POST['CompTDate'])){ echo $_POST['CompTDate']; }else echo date("m/d/Y"); ?>" name="CompTDate" placeholder="CompTDate" id="Datepicker1"/></td>
          </tr>
          
          <tr>
          	<td colspan="6" style="text-align:right">
            <input type="submit" value="Search" class="btn" name="Search" />
            &nbsp;&nbsp;</td>
          </tr>
		  </table> 
		  </form> 
        </div>
 </div>                      
</div>
<div class="page-header"><h1>User Activity  <small> Detail</small></h1></div>
<!--<div class="row-fluid">
<div class="span3" style="float:right;">
    <p align="right" style="float:right;">
     <a id="ticket-print" class="action-button" href="" onclick="openWin();">
     <button class="btn" type="button"><i class="icon-print"></i> Print</button></a>                              
    </p>             
</div>
</div>-->
<div class="row-fluid">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'User Activity Detail'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">

    <table cellpadding="0" cellspacing="0" width="100%" class="table">			
    <thead>
            <tr>
            <th>User</th>
            <th>Role</th>
            <th>Date/Time</th>
            <th>Activity Type</th>  
            <th>Activity</th>      
            </tr>
            </thead>
    <tbody class="" page="1">
		<?php
		if($_POST['CompTDate']!==''  && isset($_POST['CompTDate'])){
			$query="SELECT * FROM sdms_ticket_event where DATE(timestamp)='".date('Y-m-d',strtotime($_POST['CompTDate']))."'";
            }
			else{
			$query="SELECT * FROM sdms_ticket_event where DATE(timestamp)='".date('Y-m-d')."'";
			}
	$result = db_query($query);
        $i=1;
        if($result && ($num=db_num_rows($result))):
            while ($row = db_fetch_array($result)) {
                ?>
        <tr>
            <th>
			<?php 
$query_staff="SELECT * FROM sdms_staff  where staff_id='".$row['staff_id']."'";
$result_staff = db_query($query_staff);
$row_staff = db_fetch_array($result_staff);
?>
			<?php if($row_staff['username']!="") echo $row_staff['username']; else echo "No Staff";?></th>
            <td>
            <?php 
	$query_designation="SELECT * FROM sdms_groups where group_id='".$row_staff['group_id']."'";
	$result_designation = db_query($query_designation);
	$row_designation = db_fetch_array($result_designation);
	echo $row_designation['group_name'];	
		 ?></td>
            
            <td><?php 
	echo date('Y-m-d',strtotime($row['timestamp']));	
		 ?></td>
            <td><?php echo $row['state']; ?></td>
            <td><?php echo $row['state']; ?></td>
            </tr>
            <?php
            $class = ($class =='row2') ?'row1':'row2';
            $i++;
            } //end of while.
        else: //not tickets found!! ?>
            <tr class="<?php echo $class?>"><td><b>Query returned 0 results.</b></td></tr>
        <?php
        endif; ?>
        <tr>
        	<th><span style="float: left;;">Total</span></th>
            &nbsp;&nbsp;
            <td><b><?php echo $total_role; ?></b></td>
            <td><b><?php echo $total_date; ?></b></td>
            <td><b><?php echo $total_type; ?></b></td>
        <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$total_acitivity.'</span></b><br />'; ?></span></td>
        </tr> 
        </tbody>                    
		</table>
        
        </div>
 </div>                      
</div>                        
<div class="dr"><span></span></div>   
</div><!--WorkPlace End-->  
</div>   