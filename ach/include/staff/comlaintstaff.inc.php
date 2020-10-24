<?php error_reporting(0); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="js_old/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="js_old/jqplot.pieRenderer.min.js"></script>
<link rel="stylesheet" type="text/css" href="js_old/jquery.jqplot.min.css" />
<link type="text/css" rel="stylesheet" href="js_old/shThemejqPlot.min.css" />
<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("comlaintstaff_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
//myWindow.close();
}
</script>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');		
$qselect='SELECT DISTINCT(t.staff_id), s.username, s.firstname, s.lastname ';
$qfrom='FROM '.TICKET_TABLE.' t, '.STAFF_TABLE.' s ';
$qwhere=' WHERE t.staff_id <> "0" AND t.staff_id=s.staff_id ';
//get log count based on the query so far..
$total=db_count("SELECT count(*) $qfrom $qwhere");
$pagelimit=30;
//pagenate
$pageNav=new Pagenate($total,$page,$pagelimit);
$pageNav->setURL('admin.php',$qstr);
$query="$qselect $qfrom $qwhere ORDER BY t.created DESC LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
//echo $query;
$result = db_query($query);
$showing=db_num_rows($result)?$pageNav->showing():"";

?>



<div class="page-header"><h1>Staff  <small> Complaints Count</small></h1></div>
<div class="span12" style="width:100%;">
    <p align="right" style="width:30%;float:right;">
     <a id="ticket-print" class="action-button" href="" onclick="openWin();">
     <button class="btn" type="button"><i class="icon-print"></i> Print</button></a>                              
    </p>             
</div>
<form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
<input type="hidden" name="a" value="mass_process" >
<input type="hidden" name="status" value="<?php echo $statusss?>" >
    <div class="row-fluid">
        <div class="span12">                    
            <div class="head clearfix">
                <div class="isw-grid"></div>
                <h1><?php echo 'Staff Name and Tickets Count'; ?></h1>                               
            </div>
            <div class="block-fluid table-sorting clearfix">
            <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable">							
                <thead>
                <!--<tr>
                    <th>Staff Name</th>-->
                    <!--<th>Totals<img src="images/Opened.png"/></th>-->
                    <!--<th>Opened<img src="images/Opened.png"/></th>
                    <th>Assigned<img src="images/Assigned.png"></th>
                    <th>Overdue<img src="images/Overdue.png"></th>
                    <th>Closed<img src="images/Closed.png"/></th>       
                </tr>-->
                <tr>
            <th>Staff Name</th>
		    <th>Total Assigned</th>
            <th>On Time</th>
            <th>Delayed</th>
            <th>Closed</th>
            </tr>
                </thead>
                <tbody role="alert" aria-live="polite" aria-relevant="all">
				    <?php
              $total_assign=0;
              $total_ontime=0;
              $total_delay=0;
              $total_closed=0;
              $class = "row1";
              $total=0;
              $i=1;
		
        if($result && ($num=db_num_rows($result))){
		  while ($row = db_fetch_array($result)) {
                ?>
        <tr class="<?php echo $class?> " id="<?php echo $i?>">
                <th>
                <a href="javascript:toggleMessage('<?php echo $i?>');">
                <span style="color:#666; float: left; width:350px;"><?php echo $row['username']." (".$row['firstname']." ".$row['lastname'].")";?></span></a></th>
                &nbsp;&nbsp;
                <?php
                $sqln='SELECT * FROM '.TICKET_TABLE.' WHERE staff_id='.$row["staff_id"].'';
                if(($resn=db_query($sqln)) && ($numn=db_num_rows($resn))) {
                }
                ?>
                <?php $report .= " [ '" . $row['username'] . "' , ". $numn. " ], " ; ?>
                
                 <!-- <td><span class="Icon <?php // echo $icon?>" align="right"><?php // echo '<b><span align="right">'.$row['ticketno'].'</span></b><br />'; ?></span></td>-->
	             <?php
                /*$sqln='SELECT * FROM '.TICKET_TABLE.' WHERE staff_id='.$row["staff_id"].'';
                if(($resn=db_query($sqln)) && ($numn=db_num_rows($resn))) {

                }*/
                ?>
                  &nbsp;&nbsp;
                  <?php 
				$query2="SELECT  t.* FROM ".TICKET_TABLE." t WHERE t.staff_id != '0'  AND t.staff_id='".$row['staff_id']."'";		
				$result2 = db_query($query2);
                $num2=db_num_rows($result2);
				$check="";
				if($num2==0)
				$check="-";
				else
				$check=$num2;
                  ?>
                 <!-- <td><span class="Icon <?php //echo $icon?>" align="right"><?php //echo '<b><span align="right">'.$numn.'</span></b><br />'; ?></span></td>-->
                 <?php /*$report .= " [ '" . $row['username'] . "' , ". $numn. " ], " ;*/ ?> 
                  
                  <?php
			/*  
            $query1="SELECT count(status) as open FROM ".TICKET_TABLE." where status = 'open' and staff_id='".$row['staff_id']."'";	
			$result1 = db_query($query1);
            $row1 = db_fetch_array($result1);*/?>
                <!--<td><?php //echo '<b><span align="right">'.$row1['open'].'</span></b><br />'; ?></td>-->
                
                <td><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></td>
            
                <?php 
				/*$query2="SELECT  t.* FROM ".TICKET_TABLE." t WHERE t.staff_id != '0'  AND t.staff_id='".$row['staff_id']."'";		
				$result2 = db_query($query2);
                $num2=db_num_rows($result2);*/
                ?>
                
                   <?php  
            $query1="SELECT count(status) as open FROM ".TICKET_TABLE." where status = 'open' and staff_id='".$row['staff_id']."'";	
			$result1 = db_query($query1);
            $row1 = db_fetch_array($result1);
				if($row1['open']==0)
				$check="-";
				else
				$check=$row1['open'];
			?>
            
                <td><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></td>
            
                <!--<td><?php //echo '<b><span align="right">'.$num2.'</span></b><br />'; ?></td>-->
            
                 <?php 
            /*$query3="SELECT t.* FROM ".TICKET_TABLE." where t.isoverdue != '1' AND t.staff_id='".$row['staff_id']."'";			
            $result3 = db_query($query3);
            $num3=db_num_rows($result3);*/?>
            
                 <?php 
            $query3="SELECT t.* FROM ".TICKET_TABLE." where t.isoverdue != '1' AND t.staff_id='".$row['staff_id']."'";			
            $result3 = db_query($query3);
            $num3=db_num_rows($result3);
			if($num3==0)
				$check="-";
				else
				$check=$num3;
			?>
                <td><?php echo '<b><span align="right">'.$check.'</span></b><br />'; ?></td>
               
                 <!--<td><?php //echo '<b><span align="right">'.$num3.'</span></b><br />'; ?></td>-->
               
                <?php 
            /*$query4="SELECT count(status) as close FROM ".TICKET_TABLE." where status = 'closed' and staff_id='".$row['staff_id']."'";			
            $result4 = db_query($query4);
            $row4 = db_fetch_array($result4);*/?>
                <!--<td><?php //echo '<b><span align="right">'.$row4['close'].'</span></b><br />'; ?></td>-->
              
                 <?php 
            $query4="SELECT count(status) as close FROM ".TICKET_TABLE." where status = 'closed' and staff_id='".$row['staff_id']."'";			
            $result4 = db_query($query4);
            $row4 = db_fetch_array($result4);
			if($row4['close']==0)
				$check="-";
				else
				$check=$row4['close'];
			?>
                <td><?php echo '<b><span align="right">'.$row4['close'].'</span></b><br />'; ?></td>
                
                <?php 	  $total_assign=$total_assign+$num2;
						  $total_ontime=$total_ontime+$row1['open'];
						  $total_delay=$total_delay+$num3;
						  $total_closed=$total_closed+$row4['close'];
					?>
                    <div id="msg_<?php echo $i;?>" class="hide">
                        <hr>

                <?php

                $sqlt='SELECT ticketID, ticket_id FROM '.TICKET_TABLE.' WHERE staff_id='.$row["staff_id"].'';
                if(($rest=db_query($sqlt)) && ($numt=db_num_rows($rest))) {
                    $strt='';
                    $it=1;
                    while ($rowt = db_fetch_array($rest)){
                    if ($it<$numt) {
                        $strt.='<a href="tickets.php?id='.$rowt['ticket_id'].'" target="_blank">'.$rowt['ticketID'].'</a>, ';
                    } elseif ($it>=$numt) {
                      $strt.='<a href="tickets.php?id='.$rowt['ticket_id'].'" target="_blank">'.$rowt['ticketID'].'</a>';
                    }
                    $it++;
                    }
                   }
                ?>
                  <span style="text-align:left;float:left;"><i><?php $strt?>&nbsp;&nbsp;</i></span>
                  </div>
                  <?php
				$abc[$row['username']]=$numn;
			     ?>
            </tr>
            <?php
            $class = ($class =='row2') ?'row1':'row2';
            $i++;
			
            } //end of while.
		}else{ //not tickets found!! ?>
            <tr class="<?php $class?>"><td><b>Query returned 0 results.</b></td></tr>
        <?php  } ?>
        <?php
        if($total_assign==0)
		$total_assign="-";
		if($total_ontime==0)
		$total_ontime="-";
		if($total_delay==0)
		$total_delay="-";
		if($total_closed==0)
		$total_closed="-";
		?>
            <tr id="total">
        <th><span style="float: left; width:350px;">Total</span></th>
                &nbsp;&nbsp;
       <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$total_assign.'</span></b><br />'; ?></span></td>
       <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$total_ontime.'</span></b><br />'; ?></span></td>
       <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$total_delay.'</span></b><br />'; ?></span></td>
       <td><span class="Icon <?php echo $icon;?>" align="right"><?php echo '<b><span align="right">'.$total_closed.'</span></b><br />'; ?></span></td>
       </tr>    
                    
                    <!--<tr>
                    <td colspan="6">                    

<div id="pie1" style="margin-top:20px; margin-left:210px; width:500px; height:500px;"></div>
 <script class="code" type="text/javascript">
$(document).ready(function(){
var data = [ <?php //echo $report; ?> ];
  var plot1 = jQuery.jqplot ('pie1', [data], 
    { 
      seriesDefaults: {
        // Make this a pie chart.
        renderer: jQuery.jqplot.PieRenderer, 
        rendererOptions: {
           // Put data labels on the pie slices.
          // By default, labels show the percentage of the slice.
          showDataLabels: true
        }
      }, 
      legend: { show:true, location: 'e' }
    }
  );
  
});
</script> 
                  </td>
                    </tr>-->              
                </tbody>                              
            </table>
            </div>
        </div>                      
    </div>
</form>                        
		<div class="dr"><span></span></div>   
   </div><!--WorkPlace End-->  
   </div> 