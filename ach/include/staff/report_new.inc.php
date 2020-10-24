

<?php error_reporting(0); ?>
<?php
$total_all=0;
$total_assign=0;
$total_ontime=0;
$total_delay=0;
$total_closed=0;
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

if($_REQUEST['staffid']) {
  $staffid = $_REQUEST['staffid'];
}
$numtotalstaff=0;
$sqltotalstaff='SELECT DISTINCT(t.staff_id), s.username, s.firstname, s.lastname '.
               'FROM '.TICKET_TABLE.' t, '.STAFF_TABLE.' s '.
               'WHERE t.staff_id <> "0" AND t.staff_id=s.staff_id';
?>
<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("report_new_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
myWindow.close();
}
</script>

<div class="page-header"><h1>Complaints  <small> Processing Status Summary</small></h1></div>
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
            <h1><?php echo 'Complaints Processing Status Summary'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
        <?php 
            $numtotal=0;
            if ($staffid) {
              $sqltotal='SELECT * FROM '.TICKET_TABLE.' WHERE staff_id = '.$staffid.' AND status = "open"';
            } else {
              $sqltotal='SELECT * FROM '.TICKET_TABLE.'';
               $sqlact3daysopen='SELECT * FROM '.TICKET_TABLE.' '.
                           'WHERE '.
                           'status = "open"';	
             $sqlact3dayscloded='SELECT * FROM '.TICKET_TABLE.' '.
                           'WHERE  '.
                           ' status = "closed"';	
             $sqlact3daysover='SELECT * FROM '.TICKET_TABLE.' '.
                           'WHERE  '.
                           ' isoverdue = "1"';	
             $sqlact3daysassing='SELECT * FROM '.TICKET_TABLE.' '.
                           'WHERE '.
                           ' staff_id != "0"';				   			  
            }
            
            $resact3days=db_query($sqlact3daysopen);
            $numact3daysopen=db_num_rows($resact3days);
            
            $resact3days=db_query($sqlact3dayscloded);
            $numact3daysclosed=db_num_rows($resact3days);
            
            $resact3days=db_query($sqlact3daysover);
            $numact3daysover=db_num_rows($resact3days);
            
            $resact3days=db_query($sqlact3daysassing);
            $numact3daysassing=db_num_rows($resact3days);
            
            if(($restotal=db_query($sqltotal)) && ($numtotal=db_num_rows($restotal))) {
            }
            ?>
            <table cellpadding="0" cellspacing="0" width="100%" class="table">							
                <thead>
                    <tr>
                     <th>Legend</th>
                <th>Totals</th>
                <th>Total Assigned</th>
                <th>On Time</th>                
                <th>Delayed</th>
                <th>Closed</th>               
                        </tr>
                </thead>
                <tbody role="alert" aria-live="polite" aria-relevant="all">    
       <?php
                    $numact3days=0;
                    if ($staffid) {
                      $sqlact3days='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE staff_id <> "0" AND 1 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
                                   'AND status = "open" and staff_id = '.$staffid.' AND 7 >= TIMESTAMPDIFF(DAY,created,NOW())';
                    } else {
                     $sqlact3days='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE  0 >= TIMESTAMPDIFF(DAY,created,NOW())';
                     $sqlact3daysopen='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE  0 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
                                   'AND status = "open"';	
                     $sqlact3dayscloded='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE  0 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
                                   'AND status = "closed"';	
                     $sqlact3daysover='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE  0 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
                                   'AND isoverdue = "1"';	
                     $sqlact3daysassing='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE  0 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
                                   'AND staff_id != "0"';				   			   		   	   
                    }
                    $resact3days=db_query($sqlact3daysopen);
                    $numact3daysopen=db_num_rows($resact3days);
                    
                    $resact3days=db_query($sqlact3dayscloded);
                    $numact3daysclosed=db_num_rows($resact3days);
                    
                    $resact3days=db_query($sqlact3daysover);
                    $numact3daysover=db_num_rows($resact3days);
                    
                    $resact3days=db_query($sqlact3daysassing);
                    $numact3daysassing=db_num_rows($resact3days);
                    
                    if(($resact3days=db_query($sqlact3days)) && ($numact3days=db_num_rows($resact3days))) {$numact1days=$numact3days;
                    }
                    ?>  
                    <tr>
                    <th>
                    <a href="#" id="txt2">
                    <span style="float: left; width:350px;"><?php echo "Today"; ?></span></a></th>
                    &nbsp;&nbsp;
                    <td><span class="Icon <?php echo $icon?>" align="right"><?php 
					if($numact3days==0)
					$numact3days="-";
					?>
					<?php echo '<b><span align="right">'.$numact3days.'</span></b><br />'; ?></span>
                    <?php $total_all=$total_all+$numact3days;
						  $total_assign=$total_assign+$numact3daysassing;
						  $total_ontime=$total_ontime+$numact3daysopen;
						  $total_delay=$total_delay+$numact3daysover;
						  $total_closed=$total_closed+$numact3daysclosed;
					?></td>
					<?php 
					if($numact3daysassing==0)
					$numact3daysassing="-";
					if($numact3daysopen==0)
					$numact3daysopen="-";
					if($numact3daysover==0)
					$numact3daysover="-";
					if($numact3daysclosed==0)
					$numact3daysclosed="-";
					?>
                    <td><?php echo '<b><span align="right">'.$numact3daysassing.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysopen.'</span></b><br />'; ?></td>                    
                    <td><?php echo '<b><span align="right">'.$numact3daysover.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysclosed.'</span></b><br />'; ?></td>
                    </tr>
                    
                    <div id="msg_2" class="hide">
                           <hr>
                           <span style="text-align:left;float:left;">Complaints Item(s): <i><?php echo ($strticketact3days)?$strticketact3days:'-'; ?></i></span><br />
                       </div>  
					<?php
                    $numact7days=0;
                    if ($staffid) {
                       $sqlact3days='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE staff_id <> "0" AND 7 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
                                   'AND status = "open" and staff_id = '.$staffid.'';
                    } else {
                      $sqlact3days='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE  7 >= TIMESTAMPDIFF(DAY,created,NOW())';
                     $sqlact3daysopen='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE  7 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
                                   'AND status = "open"';	
                     $sqlact3dayscloded='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE  7 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
                                   'AND status = "closed"';	
                     $sqlact3daysover='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE  7 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
                                   'AND isoverdue = "1"';	
                     $sqlact3daysassing='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE  7 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
                                   'AND staff_id != "0"';				   			   		   	   
                    }
                    $resact3days=db_query($sqlact3daysopen);
                    $numact3daysopen=db_num_rows($resact3days);
                    $numact7days=$numact3daysopen;
                    
                    $resact3days=db_query($sqlact3dayscloded);
                    $numact3daysclosed=db_num_rows($resact3days);
                    
                    $resact3days=db_query($sqlact3daysover);
                    $numact3daysover=db_num_rows($resact3days);
                    
                    $resact3days=db_query($sqlact3daysassing);
                    $numact3daysassing=db_num_rows($resact3days);
                    
                    if(($resact3days=db_query($sqlact3days)) && ($numact3days=db_num_rows($resact3days))) {
                    }
                    ?>
                    
                    <tr>
                    <th>
                    <a href="#" id="txt2">
                    <span style="float: left; width:350px;"><?php echo " This Week "; ?></span></a></th>
                    &nbsp;&nbsp;
                    <?php 
					if($numact3days==0)
					$numact3days="-";
					?>
                    <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$numact3days.'</span></b><br />'; ?></span></td>
                    
                    <?php $total_all=$total_all+$numact3days;
						  $total_assign=$total_assign+$numact3daysassing;
						  $total_ontime=$total_ontime+$numact3daysopen;
						  $total_delay=$total_delay+$numact3daysover;
						  $total_closed=$total_closed+$numact3daysclosed;
						  
					if($numact3daysassing==0)
					$numact3daysassing="-";
					if($numact3daysopen==0)
					$numact3daysopen="-";
					if($numact3daysover==0)
					$numact3daysover="-";
					if($numact3daysclosed==0)
					$numact3daysclosed="-";
					?>
                    
                    <td><?php echo '<b><span align="right">'.$numact3daysassing.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysopen.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysover.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysclosed.'</span></b><br />'; ?></td>
                    </tr>                    
					<?php
                    $numact14days=0;
                    if ($staffid) {
                      $sqlact3days='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE staff_id <> "0" AND 30 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
                                   'AND status = "open" and staff_id = '.$staffid.'';
                    } else {
                      $sqlact3days='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE  30 >= TIMESTAMPDIFF(DAY,created,NOW())';
                     $sqlact3daysopen='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE  30 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
                                   'AND status = "open"';	
                     $sqlact3dayscloded='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE  30 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
                                   'AND status = "closed"';	
                     $sqlact3daysover='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE  30 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
                                   'AND isoverdue = "1"';	
                     $sqlact3daysassing='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE  30 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
                                   'AND staff_id != "0"';				   			   		   	   
                    }
                    $resact3days=db_query($sqlact3daysopen);
                    $numact3daysopen=db_num_rows($resact3days);
                    
                    $resact3days=db_query($sqlact3dayscloded);
                    $numact3daysclosed=db_num_rows($resact3days);
                    
                    $resact3days=db_query($sqlact3daysover);
                    $numact3daysover=db_num_rows($resact3days);
                    
                    $resact3days=db_query($sqlact3daysassing);
                    $numact3daysassing=db_num_rows($resact3days);
                    
                    if(($resact3days=db_query($sqlact3days)) && ($numact3days=db_num_rows($resact3days))) {$numact14days=$numact3days;
                    }
                    ?>
                    
                    <tr>
                    <th>
                    <a href="#" id="txt2">
                    <span style="float: left; width:350px;"><?php echo " This Months "; ?></span></a></th>
                    &nbsp;&nbsp;
                     <?php 
					if($numact3days==0)
					$numact3days="-";
					?>
                    <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$numact3days.'</span></b><br />'; ?></span></td>
                    
                    <?php $total_all=$total_all+$numact3days;
						  $total_assign=$total_assign+$numact3daysassing;
						  $total_ontime=$total_ontime+$numact3daysopen;
						  $total_delay=$total_delay+$numact3daysover;
						  $total_closed=$total_closed+$numact3daysclosed;
					if($numact3daysassing==0)
					$numact3daysassing="-";
					if($numact3daysopen==0)
					$numact3daysopen="-";
					if($numact3daysover==0)
					$numact3daysover="-";
					if($numact3daysclosed==0)
					$numact3daysclosed="-";
					?>
                    
                    <td><?php echo '<b><span align="right">'.$numact3daysassing.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysopen.'</span></b><br />'; ?></td>                    
                    <td><?php echo '<b><span align="right">'.$numact3daysover.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysclosed.'</span></b><br />'; ?></td>
                    </tr>
					<?php
                    $numactmore14days=0;
                    if ($staffid) {
                      $sqlact3days='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE staff_id <> "0" AND 365 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
                                   'AND status = "open" and staff_id = '.$staffid.'';
                    } else {
                      $sqlact3days='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE  365 >= TIMESTAMPDIFF(DAY,created,NOW())';
                     $sqlact3daysopen='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE  365 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
                                   'AND status = "open"';	
                     $sqlact3dayscloded='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE  365 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
                                   'AND status = "closed"';	
                     $sqlact3daysover='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE  365 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
                                   'AND isoverdue = "1"';	
                     $sqlact3daysassing='SELECT * FROM '.TICKET_TABLE.' '.
                                   'WHERE  365 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
                                   'AND staff_id != "0"';				   			   		   	   
                    }
                    $resact3days=db_query($sqlact3daysopen);
                    $numact3daysopen=db_num_rows($resact3days);
                    
                    $resact3days=db_query($sqlact3dayscloded);
                    $numact3daysclosed=db_num_rows($resact3days);
                    
                    $resact3days=db_query($sqlact3daysover);
                    $numact3daysover=db_num_rows($resact3days);
                    
                    $resact3days=db_query($sqlact3daysassing);
                    $numact3daysassing=db_num_rows($resact3days);
                    
                    if(($resact3days=db_query($sqlact3days)) && ($numact3days=db_num_rows($resact3days))) {$numactmore14days=$numact3days;
                    }
                    ?>      
                    <tr>
                    <th>
                    <a href="#" id="txt2">
                    <span style="float: left; width:350px;"><?php echo " This Years "; ?></span></a></th>
                    &nbsp;&nbsp;
                     <?php 
					if($numact3days==0)
					$numact3days="-";
					?>
                    <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$numact3days.'</span></b><br />'; ?></span></td>	
                    
                    <?php $total_all=$total_all+$numact3days;
						  $total_assign=$total_assign+$numact3daysassing;
						  $total_ontime=$total_ontime+$numact3daysopen;
						  $total_delay=$total_delay+$numact3daysover;
						  $total_closed=$total_closed+$numact3daysclosed;
					if($numact3daysassing==0)
					$numact3daysassing="-";
					if($numact3daysopen==0)
					$numact3daysopen="-";
					if($numact3daysover==0)
					$numact3daysover="-";
					if($numact3daysclosed==0)
					$numact3daysclosed="-";
					?>
                    				
                    <td><?php echo '<b><span align="right">'.$numact3daysassing.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysopen.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysover.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysclosed.'</span></b><br />'; ?></td>
                    </tr>
                    <tr id="total">
                    <th>
                    <a href="#" id="txt2">
                    <span style="float: left; width:350px;"><?php echo " Total "; ?></span></a></th>
                    &nbsp;&nbsp;
                    <?php
                    if($total_all==0)
					$total_all="-";
					if($total_assign==0)
					$total_assign="-";
					if($total_ontime==0)
					$total_ontime="-";
					if($total_delay==0)
					$total_delay="-";
					if($total_closed==0)
					$total_closed="-";
					
					?>
                    <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$total_all.'</span></b><br />'; ?></span></td>					
                    <td><?php echo '<b><span align="right">'.$total_assign.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$total_ontime.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$total_delay.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$total_closed.'</span></b><br />'; ?></td>
                    </tr>
                      
            </tbody>                              
            </table>
        </div>
 </div>                      
</div>                        
<div class="dr"><span></span></div>   
</div><!--WorkPlace End-->  
</div>   



