<?php error_reporting(0); ?>
<script>

function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("report_complaint_source_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
window.location.reload();
}
</script>
<style>
#text th {text-align:center;vertical-align:middle;}
#color{background:#999;color:#FFF;}
</style>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

if($_REQUEST['staffid']) {
$staffid = $_REQUEST['staffid'];
}
?>

<div class="page-header"><h1>Complaint Source<small>Reports</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">        
<input style="margin-left:160px;" id="report_create" onclick="openWin();" class="btn" type="button" value="Print">  
</div>
</div>
<div class="row-fluid" style="min-height:770px;">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Complaint Source Reports'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
          <table width="100%" cellpadding="0" cellspacing="0" class="table">
           <thead>
  <tr id="text">
 
    <th>Month</th>





       
    <th>Through Counter</th>
    <th>Through Post</th>
    <th>Through Call</th>
    <th>Through Mail</th>
    <th>Online Registered (Self)</th>
    <th>By Fax</th>
    <th>From Chief Justice</th>
     <th id="color">Total</th>
  </tr>
  </thead>
 <tr>
    <td colspan="9">Year:2014</td>
    
  </tr>
     <?php 
   $year=2014;
	 for ($i = 1; $i <= date('m'); $i++) { 
	 $count_total=0;
	 ?>
    <tr>
    	<td><?php echo date("M",strtotime('2014-'.$i)); ?></td>
        
		<?php 
        $query="SELECT count(ticket_id) as counter,source FROM sdms_ticket Where source='At Counter' AND Year(created)='".$year."' AND Month(Created)='".$i."'";
        $result = db_query($query);
        $row = db_fetch_array($result);?>
    	<td><?php echo $row['counter']; $count_total = $row['counter']; ?></td>
        
        <?php 
        $query="SELECT count(ticket_id) as post,source FROM sdms_ticket Where source='By Post' AND Year(created)='".$year."' AND Month(Created)='".$i."'";
        $result = db_query($query);
        $row = db_fetch_array($result);?>
    	<td><?php echo $row['post']; $count_total += $row['post']; ?></td>
        
        <?php 
        $query="SELECT count(ticket_id) as calls,source FROM sdms_ticket Where source='Through Call' AND Year(created)='".$year."' AND Month(Created)='".$i."'";
        $result = db_query($query);
        $row = db_fetch_array($result);?>
    	<td><?php echo $row['calls']; $count_total += $row['calls']; ?></td>
        
        <?php 
        $query="SELECT count(ticket_id) as email,source FROM sdms_ticket Where source='By Email' AND Year(created)='".$year."' AND Month(Created)='".$i."'";
        $result = db_query($query);
        $row = db_fetch_array($result);?>
    	<td><?php echo $row['email']; $count_total += $row['email']; ?></td>
        
		<?php 
        $query="SELECT count(ticket_id) as online,source FROM sdms_ticket Where source='Online Complaint' AND Year(created)='".$year."' AND Month(Created)='".$i."'";
        $result = db_query($query);
        $row = db_fetch_array($result);?>
    	<td><?php echo $row['online']; $count_total += $row['online']; ?></td>
        
        <?php 
        $query="SELECT count(ticket_id) as online,source FROM sdms_ticket Where source='By Fax' AND Year(created)='".$year."' AND Month(Created)='".$i."'";
        $result = db_query($query);
        $row = db_fetch_array($result);?>
    	<td><?php echo $row['online']; $count_total += $row['online']; ?></td>
        
        <?php 
        $query="SELECT count(ticket_id) as online,source FROM sdms_ticket Where source='From Chief Justice' AND Year(created)='".$year."' AND Month(Created)='".$i."'";
        $result = db_query($query);
        $row = db_fetch_array($result);?>
    	<td><?php echo $row['online']; $count_total += $row['online']; ?></td>
        
        <td id="color"><?php echo $count_total; ?></td>
    </tr>
  <?php }?>
</table>
  
        </div>
 </div>  
 <div class="dr"><span></span></div>                      
</div>                        
 
</div><!--WorkPlace End-->  
</div>   


