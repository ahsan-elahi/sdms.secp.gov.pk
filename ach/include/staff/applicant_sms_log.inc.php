<?php error_reporting(0); ?>
<script>

function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("applicant_sms_log_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
window.location.reload();
}
</script>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

if($_REQUEST['staffid']) {
$staffid = $_REQUEST['staffid'];
}
?>

<div class="page-header"><h1>Applicant <small>SMS Log</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">  
<input style="margin-left:160px;" id="report_create" onclick="openWin();" class="btn" type="button" value="Print">      
</div>
</div>
<div class="row-fluid" style="min-height:770px;">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Applicant SMS Log'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
          <table width="100%" cellpadding="0" cellspacing="0" class="table">
           <thead>
  <tr>
  	<th>S. NO</th> 
  	<th>Applicant Name</th>
    <th>Complaint No</th> 
    <th>SMS send Date/Time</th>
    <th>Send To</th>
    <th>Send Text</th>
    <th>Sent From Agent</th>
  </tr>
  </thead>
  <?php 
  $res=db_query("SELECT * FROM sdms_sms_logs");
  $i=1;
   while($row=db_fetch_array($res)){
   ?>
 
  <tr>
  <?php 
  $sql_name="SELECT name FROM sdms_ticket WHERE ticketID='".$row['ticketID']."'";
  $res_name=db_query($sql_name);
  $row_name=db_fetch_array($res_name);
   ?>
   <td><?php echo $i++; ?></td>
    <td><?php echo $row_name['name']; ?></td>
    <td><?php echo $row['ticketID']; ?></td>
    <td><?php echo date("d-m-Y H:m:i",strtotime($row['sms_date'])); ?></td>
    <td><?php echo $row['send_to']; ?></td>
    <td><?php echo substr($row['sms_text'],0,20)."....."; ?></td>
    <?php 
	$sql_name="SELECT firstname,lastname FROM sdms_staff WHERE staff_id='".$row['staff_id']."'";
  $res_name=db_query($sql_name);
  $row_name=db_fetch_array($res_name);
	 ?>
    <td><?php echo $row_name['firstname']." ".$row_name['lastname']; ?></td>
    
  </tr>
  <?php } ?>
</table>
  
        </div>
 </div>    
 <div class="dr"><span></span></div>                    
</div>                        
 
</div><!--WorkPlace End-->  
</div>   


