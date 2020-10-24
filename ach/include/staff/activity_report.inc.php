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
<div class="row-fluid">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1>Search</h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
          <form action="activity_report.php" method="POST">
           <?php csrf_token(); ?>
          <table width="100%" cellpadding="0" cellspacing="0" class="table" style="line-height:35px;">
          <tr>
          <td>Districts</td>
            <td><select name="staff_id">
                    <option value="" selected="selected">All USERS</option>
                     <?php
					 $query="SELECT * FROM sdms_staff group by username ORDER BY group_id ASC ";
$result = db_query($query);
                    while($row = db_fetch_array($result)){
                     ?>
                      <option value="<?php echo $row['staff_id']; ?>" <?php if ($_POST['staff_id'] == $row['staff_id']) { ?> selected="selected" <?php } ?>><?php echo $row['firstname'].' '.$row['lastname']; ?></option>
                    <?php } ?>
                    </select></td>
            <td style="padding-left:3px;padding-right:2px;padding-top:10px;">
            <select name="group_id">
                    <option value="" selected="selected">All GROUPS</option>
                     <?php
					 $query="SELECT * FROM sdms_groups";
$result = db_query($query);
                    while($row = db_fetch_array($result)){
                     ?>
                      <option value="<?php echo $row['group_id']; ?>" <?php if ($_POST['group_id'] == $row['group_id']) { ?> selected="selected" <?php } ?>><?php echo $row['group_name']; ?></option>
                    <?php } ?>
                    </select></td>
            <td></td>
            <td>Date</td>
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
<div class="page-header"><h1>User Activity  <small> Report</small></h1></div>
<div class="row-fluid">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'User Activity Summary'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">

           <table cellpadding="0" cellspacing="0" width="100%" class="table">		
    <thead>
            <tr>
            <th>S.No</th>
            <th  >Name</th>
            <th >UserName</th>
            <th  >Designation</th>
            <th  >Login</th>
            </tr>
            </thead>
    <tbody class="" page="1">
		<?php
if($_POST['staff_id']!=='' && isset($_POST['staff_id'])){
	$query="SELECT * FROM sdms_staff where staff_id='".$_POST['staff_id']."' group by username ORDER BY group_id ASC ";
}
elseif($_POST['group_id']!=='' && isset($_POST['group_id'])){
	$query="SELECT * FROM sdms_staff where group_id='".$_POST['group_id']."' group by username ORDER BY group_id ASC ";
}
else{
$query="SELECT * FROM sdms_staff group by username ORDER BY group_id ASC ";
}
$result = db_query($query);
        $i=1;
        if($result && ($num=db_num_rows($result))):
            while ($row = db_fetch_array($result)) {
                ?>
        <tr>
        <td ><?php echo $i; ?></td>
        <td><?php echo $row['firstname'].' '.$row['lastname']; ?></td>
        <td ><a href="javascript:toggleMessage('<?php echo $i?>');"><?php if($row['username']!="") echo $row['username']; else echo "No Staff";?></a></td>
        <td ><?php 
	$query_designation="SELECT * FROM sdms_groups where group_id='".$row['group_id']."'";
	$result_designation = db_query($query_designation);
	$row_designation = db_fetch_array($result_designation);
	echo $row_designation['group_name'];	
		 ?></td>
        <td >
            <?php 
			if($_POST['CompTDate']!==''  && isset($_POST['CompTDate'])){
			$sql_login="SELECT count(user_id) as login FROM sdms_login WHERE user_id ='".$row['staff_id']."' AND created ='".date('Y-m-d',strtotime($_POST['CompTDate']))."'";
            }
			else{
			$sql_login="SELECT count(user_id) as login FROM sdms_login WHERE user_id ='".$row['staff_id']."' AND created ='".date("Y-m-d")."'";
			}
			//echo $sql_login;exit;
			$res_login=mysql_query($sql_login);
            $row_login=mysql_fetch_array($res_login);	
			$check="";
			if($row_login['login']==0)
			$check="-";
			else
			$check=$row_login['login'];
			?> 
            <span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></span></td>
            </tr>
            <?php
            $class = ($class =='row2') ?'row1':'row2';
            $i++;
            } //end of while.
        else: //not tickets found!! ?>
            <tr class="<?php echo $class?>"><td><b>Query returned 0 results.</b></td></tr>
        <?php
        endif; ?>
         
        </tbody>                    
		</table>
      
	  </div>
 </div>                      
</div>                        
<div class="dr"><span></span></div>   
</div><!--WorkPlace End-->  
</div>   


