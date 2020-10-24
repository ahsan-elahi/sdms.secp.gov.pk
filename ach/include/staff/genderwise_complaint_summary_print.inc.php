<?php error_reporting(0); ?>
<link href="css/stylesheets.css" rel="stylesheet" type="text/css" /> 
<style>
#bg_color td{background:#CCC;}
#text th {text-align:center;vertical-align:middle;}
</style>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

if($_REQUEST['staffid']) {
$staffid = $_REQUEST['staffid'];
}
?>

<div class="page-header"><h1>Summary Gender Wise<small>Report</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;"> 
</div>
</div>
<div class="row-fluid" style="min-height:700px;">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'Complaint Summary Gender Wise'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
          <table width="100%" cellpadding="0" cellspacing="0" class="table">
           <thead>
  <tr id="text">
 
    <th rowspan="2">Daily Reports</th>
    <th colspan="2">Opened</th>
    <th colspan="2">Overdue</th>
    <th colspan="2">Closed</th>
  </tr>
  <tr id="bg_color">
  <td>Male</td>
  <td>Female</td>
  <td>Male</td>
  <td>Female</td>
  <td>Male</td>
  <td>Female</td>
  </tr>
  </thead>
 
  <tr>
    <td>Active Complaints</td>
	<?php 
    //open male complaint
    $sql_open_male='SELECT * FROM '.TICKET_TABLE.' '.'WHERE '.'status = "open" AND gender="male" ';	
    $res_open_male=db_query($sql_open_male);
    $row_open_male=db_num_rows($res_open_male);
    //open female complaint
    $sql_open_female='SELECT * FROM '.TICKET_TABLE.' '.'WHERE '.'status = "open" AND gender="female" ';	
    $res_open_female=db_query($sql_open_female);
    $row_open_female=db_num_rows($res_open_female);
    ?>
    <td><?php echo $row_open_male; ?></td>
    <td><?php echo $row_open_female; ?></td>
    <?php 
    //closed female complaint
    $sql_closed_male='SELECT * FROM '.TICKET_TABLE.' '.'WHERE '.'status = "closed" AND gender="male" ';	
    $res_closed_male=db_query($sql_closed_male);
    $row_closed_male=db_num_rows($res_closed_male);
    //closed female complaint
    $sql_closed_female='SELECT * FROM '.TICKET_TABLE.' '.'WHERE '.'status = "closed" AND gender="female" ';	
    $res_closed_female=db_query($sql_closed_female);
    $row_closed_female=db_num_rows($res_closed_female);
    ?>
    <td><?php echo $row_closed_male; ?></td>
    <td><?php echo $row_closed_female; ?></td>
    <?php 
    //isoverdue female complaint
    $sql_isoverdue_male='SELECT * FROM '.TICKET_TABLE.' '.'WHERE '.'isoverdue = "1" AND gender="male" ';	
    $res_isoverdue_male=db_query($sql_closed_male);
    $row_isoverdue_male=db_num_rows($res_isoverdue_male);
    //isoverdue female complaint
    $sql_closed_female='SELECT * FROM '.TICKET_TABLE.' '.'WHERE '.'isoverdue = "1" AND gender="female" ';	
    $res_closed_female=db_query($sql_isoverdue_female);
    $row_closed_female=db_num_rows($res_isoverdue_female);
    ?>    
    <td><?php echo $row_isoverdue_male; ?></td>
    <td><?php echo $row_closed_female; ?></td>
  </tr>
  <tr>
    <td>Active Complaints (Today)</td>
    <?php 
    //open male complaint
    $sql_open_male='SELECT * FROM '.TICKET_TABLE.' '.'WHERE  0 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND status = "open" AND gender="male" ';	
    $res_open_male=db_query($sql_open_male);
    $row_open_male=db_num_rows($res_open_male);
    //open female complaint
    $sql_open_female='SELECT * FROM '.TICKET_TABLE.' '.'WHERE 0 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND status = "open" AND gender="female" ';	
    $res_open_female=db_query($sql_open_female);
    $row_open_female=db_num_rows($res_open_female);
    ?>
       <td><?php echo $row_open_male; ?></td>
    <td><?php echo $row_open_female; ?></td>

    
      
    
    <?php 
    //closed female complaint
    $sql_closed_male='SELECT * FROM '.TICKET_TABLE.' '.'WHERE 0 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND status = "closed" AND gender="male" ';	
    $res_closed_male=db_query($sql_closed_male);
    $row_closed_male=db_num_rows($res_closed_male);
    //closed female complaint
    $sql_closed_female='SELECT * FROM '.TICKET_TABLE.' '.'WHERE 0 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND status = "closed" AND gender="female" ';	
    $res_closed_female=db_query($sql_closed_female);
    $row_closed_female=db_num_rows($res_closed_female);
    ?>
  <td><?php echo $row_closed_male; ?></td>
    <td><?php echo $row_closed_female; ?></td>
    <?php 
    //isoverdue female complaint
    $sql_isoverdue_male='SELECT * FROM '.TICKET_TABLE.' '.'WHERE  0 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND isoverdue = "1" AND gender="male" ';	
    $res_isoverdue_male=db_query($sql_closed_male);
    $row_isoverdue_male=db_num_rows($res_isoverdue_male);
    //isoverdue female complaint
    $sql_closed_female='SELECT * FROM '.TICKET_TABLE.' '.'WHERE  0 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND isoverdue = "1" AND gender="female" ';	
    $res_closed_female=db_query($sql_isoverdue_female);
    $row_closed_female=db_num_rows($res_isoverdue_female);
    ?>  
   <td><?php echo $row_isoverdue_male; ?></td>
    <td><?php echo $row_closed_female; ?></td>
  </tr>
  <tr>
    <td>Active Complaints(Current Week)</td>
    <?php 
    //open male complaint
    $sql_open_male='SELECT * FROM '.TICKET_TABLE.' '.'WHERE  7 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND status = "open" AND gender="male" ';	
    $res_open_male=db_query($sql_open_male);
    $row_open_male=db_num_rows($res_open_male);
    //open female complaint
    $sql_open_female='SELECT * FROM '.TICKET_TABLE.' '.'WHERE 7 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND status = "open" AND gender="female" ';	
    $res_open_female=db_query($sql_open_female);
    $row_open_female=db_num_rows($res_open_female);
    ?>
       <td><?php echo $row_open_male; ?></td>
    <td><?php echo $row_open_female; ?></td>

    
      
    
    <?php 
    //closed female complaint
    $sql_closed_male='SELECT * FROM '.TICKET_TABLE.' '.'WHERE 7 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND status = "closed" AND gender="male" ';	
    $res_closed_male=db_query($sql_closed_male);
    $row_closed_male=db_num_rows($res_closed_male);
    //closed female complaint
    $sql_closed_female='SELECT * FROM '.TICKET_TABLE.' '.'WHERE 7 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND status = "closed" AND gender="female" ';	
    $res_closed_female=db_query($sql_closed_female);
    $row_closed_female=db_num_rows($res_closed_female);
    ?>
  <td><?php echo $row_closed_male; ?></td>
    <td><?php echo $row_closed_female; ?></td>
    <?php 
    //isoverdue female complaint
    $sql_isoverdue_male='SELECT * FROM '.TICKET_TABLE.' '.'WHERE  7 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND isoverdue = "1" AND gender="male" ';	
    $res_isoverdue_male=db_query($sql_closed_male);
    $row_isoverdue_male=db_num_rows($res_isoverdue_male);
    //isoverdue female complaint
    $sql_closed_female='SELECT * FROM '.TICKET_TABLE.' '.'WHERE  7 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND isoverdue = "1" AND gender="female" ';	
    $res_closed_female=db_query($sql_isoverdue_female);
    $row_closed_female=db_num_rows($res_isoverdue_female);
    ?>  
   <td><?php echo $row_isoverdue_male; ?></td>
    <td><?php echo $row_closed_female; ?></td>
  </tr>
  
  <tr>
    <td>Active Complaints(Current Month)</td>
    <?php 
    //open male complaint
    $sql_open_male='SELECT * FROM '.TICKET_TABLE.' '.'WHERE  30 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND status = "open" AND gender="male" ';	
    $res_open_male=db_query($sql_open_male);
    $row_open_male=db_num_rows($res_open_male);
    //open female complaint
    $sql_open_female='SELECT * FROM '.TICKET_TABLE.' '.'WHERE 30 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND status = "open" AND gender="female" ';	
    $res_open_female=db_query($sql_open_female);
    $row_open_female=db_num_rows($res_open_female);
    ?>
       <td><?php echo $row_open_male; ?></td>
    <td><?php echo $row_open_female; ?></td>

    
      
    
    <?php 
    //closed female complaint
    $sql_closed_male='SELECT * FROM '.TICKET_TABLE.' '.'WHERE 30 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND status = "closed" AND gender="male" ';	
    $res_closed_male=db_query($sql_closed_male);
    $row_closed_male=db_num_rows($res_closed_male);
    //closed female complaint
    $sql_closed_female='SELECT * FROM '.TICKET_TABLE.' '.'WHERE 30 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND status = "closed" AND gender="female" ';	
    $res_closed_female=db_query($sql_closed_female);
    $row_closed_female=db_num_rows($res_closed_female);
    ?>
  <td><?php echo $row_closed_male; ?></td>
    <td><?php echo $row_closed_female; ?></td>
    <?php 
    //isoverdue female complaint
    $sql_isoverdue_male='SELECT * FROM '.TICKET_TABLE.' '.'WHERE  30 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND isoverdue = "1" AND gender="male" ';	
    $res_isoverdue_male=db_query($sql_closed_male);
    $row_isoverdue_male=db_num_rows($res_isoverdue_male);
    //isoverdue female complaint
    $sql_closed_female='SELECT * FROM '.TICKET_TABLE.' '.'WHERE  30 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND isoverdue = "1" AND gender="female" ';	
    $res_closed_female=db_query($sql_isoverdue_female);
    $row_closed_female=db_num_rows($res_isoverdue_female);
    ?>  
   <td><?php echo $row_isoverdue_male; ?></td>
    <td><?php echo $row_closed_female; ?></td>
  </tr>
  <tr>
    <td>Active Complaints(Current Year)</td>
    <?php 
    //open male complaint
    $sql_open_male='SELECT * FROM '.TICKET_TABLE.' '.'WHERE  365 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND status = "open" AND gender="male" ';	
    $res_open_male=db_query($sql_open_male);
    $row_open_male=db_num_rows($res_open_male);
    //open female complaint
    $sql_open_female='SELECT * FROM '.TICKET_TABLE.' '.'WHERE 365 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND status = "open" AND gender="female" ';	
    $res_open_female=db_query($sql_open_female);
    $row_open_female=db_num_rows($res_open_female);
    ?>
       <td><?php echo $row_open_male; ?></td>
    <td><?php echo $row_open_female; ?></td>

    
      
    
    <?php 
    //closed female complaint
    $sql_closed_male='SELECT * FROM '.TICKET_TABLE.' '.'WHERE 365 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND status = "closed" AND gender="male" ';	
    $res_closed_male=db_query($sql_closed_male);
    $row_closed_male=db_num_rows($res_closed_male);
    //closed female complaint
    $sql_closed_female='SELECT * FROM '.TICKET_TABLE.' '.'WHERE 365 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND status = "closed" AND gender="female" ';	
    $res_closed_female=db_query($sql_closed_female);
    $row_closed_female=db_num_rows($res_closed_female);
    ?>
  <td><?php echo $row_closed_male; ?></td>
    <td><?php echo $row_closed_female; ?></td>
    <?php 
    //isoverdue female complaint
    $sql_isoverdue_male='SELECT * FROM '.TICKET_TABLE.' '.'WHERE  365 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND isoverdue = "1" AND gender="male" ';	
    $res_isoverdue_male=db_query($sql_closed_male);
    $row_isoverdue_male=db_num_rows($res_isoverdue_male);
    //isoverdue female complaint
    $sql_closed_female='SELECT * FROM '.TICKET_TABLE.' '.'WHERE  365 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND isoverdue = "1" AND gender="female" ';	
    $res_closed_female=db_query($sql_isoverdue_female);
    $row_closed_female=db_num_rows($res_isoverdue_female);
    ?>  
   <td><?php echo $row_isoverdue_male; ?></td>
    <td><?php echo $row_closed_female; ?></td>
  </tr>
</table>
  
        </div>
 </div>                      
</div>                        
<div class="dr"><span></span></div>   
</div><!--WorkPlace End-->  
</div>   
<script>
window.print() ;
window.close();
</script>