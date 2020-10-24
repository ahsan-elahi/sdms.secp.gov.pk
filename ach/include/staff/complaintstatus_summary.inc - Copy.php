
<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("complaintstatus_summary_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
//myWindow.close();
}
</script>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
?>
<div class="page-header"><h1>Area Report by  <small> Types</small></h1></div>
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
            <h1><?php echo 'Area Report by Types'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
            <table cellpadding="0" cellspacing="0" width="100%" class="table">	

<?php 				
$qselect='SELECT count(ticket_id) as ticketno ,t.topic_id, d.topic ';
$qfrom='FROM '.TICKET_TABLE.' t, sdms_help_topic d ';
$qwhere=' WHERE t.topic_id=d.topic_id group by t.topic_id ';

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

    <form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
    <input type="hidden" name="a" value="mass_process" >
    <input type="hidden" name="status" value="<?php echo $statusss?>" >
       <table cellpadding="0" cellspacing="0" width="100%" class="table">	
  <tr id="headerStyle">
    <td width="148">Agencies/FR/Departments</td>
    <td colspan="7"> </td>
  </tr>
  
  

  <tr id="headerStyle">
    <td>a) Agencies</td>
      <?php
    $query="SELECT * FROM sdms_status stat WHERE stat.status_id!=0";
  $result = db_query($query);
  while ($row = db_fetch_array($result)) {
	  ?>
      <td><?php if($row['status_title']!="") echo $row['status_title']; else echo "No Status";?></td>
	  <?php	}?>
  </tr>
  <?php
    $query="SELECT * FROM sdms_help_topic topic WHERE topic.topic_id!=0";
  $result = db_query($query);
  while ($row = db_fetch_array($result)) {
   ?>
  <tr>
  <th>
                <a href="javascript:toggleMessage('<?php echo $i;?>');">
                <span style="float:left; width:350px;margin-left:30px;"><?php if($row['topic']!="") echo $row['topic']; else echo "No Topic";?></span></a></th>
    <td>0</td>
	<td>1</td>	
    <td>2</td>
    <td>4</td>
	<td>2</td>

  </tr>
  <?php }  ?>


  <tr id="headerStyle">
    <td>b) FR</td>
 <?php
    $query="SELECT * FROM sdms_status stat WHERE stat.status_id!=0";
  $result = db_query($query);
  while ($row = db_fetch_array($result)) {
	  ?>
      <td><?php if($row['status_title']!="") echo $row['status_title']; else echo "No Status";?></td>
	  <?php	}?>
  </tr>
    <?php
    $query="SELECT * FROM sdms_help_topic topic WHERE topic.topic_id!=0";
  $result = db_query($query);
  while ($row = db_fetch_array($result)) {
   ?>
  <tr>
  <th>
                <a href="javascript:toggleMessage('<?php echo $i;?>');">
                <span style="float:left; width:350px;margin-left:30px;"><?php if($row['topic']!="") echo $row['topic']; else echo "No Topic";?></span></a></th>
    <td>0</td>
	<td>1</td>	
    <td>2</td>
    <td>4</td>
	<td>2</td>
  </tr>
  <?php }  ?>
 
  <tr id="headerStyle">
    <td>c) Department</td>
     <?php
    $query="SELECT * FROM sdms_status stat WHERE stat.status_id!=0";
  $result = db_query($query);
  while ($row = db_fetch_array($result)) {
	  ?>
      <td><?php if($row['status_title']!="") echo $row['status_title']; else echo "No Status";?></td>
	  <?php	}?>
  </tr>
    <?php
    $query="SELECT * FROM sdms_help_topic topic WHERE topic.topic_id!=0";
  $result = db_query($query);
  while ($row = db_fetch_array($result)) {
   ?><tr>
  	<th>
                <a href="javascript:toggleMessage('<?php echo $i;?>');">
                <span style="float:left; width:350px;margin-left:30px;"><?php if($row['topic']!="") echo $row['topic']; else echo "No Topic";?></span></a></th>
    <td>0</td>
	<td>1</td>	
    <td>2</td>
    <td>4</td>
	<td>2</td>
  </tr>
  <?php }  ?>
</table>
        </form>
	  </div>
 </div>                      
</div>                        
<div class="dr"><span></span></div>   
</div><!--WorkPlace End-->  
</div>   
