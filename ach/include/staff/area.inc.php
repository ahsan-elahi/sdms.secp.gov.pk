
<?php error_reporting(0); ?>
<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("report_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
myWindow.close();
}
</script>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

if($_REQUEST['staffid']) {
  $staffid = $_REQUEST['staffid'];
}

//echo '<div class="msg">Ticket Aging</div>';

$numtotalstaff=0;
$sqltotalstaff='SELECT DISTINCT(t.staff_id), s.username, s.firstname, s.lastname '.
               'FROM '.TICKET_TABLE.' t, '.STAFF_TABLE.' s '.
               'WHERE t.staff_id <> "0" AND t.staff_id=s.staff_id';
$numtotal=0;
if($staffid){
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
 /*$strtickettotal = '';
 $itotal = 1;
 while ($rowtotal = db_fetch_array($restotal)) {
   if ($itotal<$numtotal) {
     $strtickettotal .= '<a href="tickets.php?id='.$rowtotal['ticket_id'].'" target="_blank">'.$rowtotal['ticketID'].'</a>, ';
   } elseif ($itotal>=$numtotal) {
     $strtickettotal .=  '<a href="tickets.php?id='.$rowtotal['ticket_id'].'" target="_blank">'.$rowtotal['ticketID'].'</a>';
   }
   $itotal++;
 }*/
}
?>

<div class="page-header"><h1>Complaints  <small> Aging and Its</small></h1></div>
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
            <h1><?php echo 'Complaints Aging and Its Count'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
            <table cellpadding="0" cellspacing="0" width="100%" class="table">							
                <thead>
                    <tr>
                        <th>Daily Reports </th>
                        <th>Opened</th>
                        <th>Overdue</th> 
                        <th>Closed</th>        
                        <!--<th>Totals</th>-->           
                        </tr>
                </thead>
                <tbody role="alert" aria-live="polite" aria-relevant="all">    
                    <tr>
                    <th>
                    <a href="#" id="txt1"><span style=" color:#666; float: left; width:350px;"><?php echo " Active Complaints: "; ?></span></a></th>                   
                    <td>
					<?php echo $numact3daysopen;?>&nbsp;<img src="images/Opened.png"/></td>                    
                    <td>
					<?php echo $numact3daysover;?>&nbsp;<img src="images/Closed.png"/></td>
                    <td>
					<?php echo $numact3daysclosed;?>&nbsp;<img src="images/Overdue.png"/></td>
                    <!--<td>
                    <span class="Icon <?php //echo $icon?>" align="right"><?php// echo '<b>'.$numtotal.'</b>'; ?>&nbsp;<img src="images/Opened.png"/></span>
                    <div id="msg_1" class="hide" >
                    <hr>
                    <span style="text-align:left;float:left;">Ticket Item(s): <i><?php //echo ($strtickettotal)?$strtickettotal:'-'; ?></i></span>
                    <br />
                    </div>
                    </td>-->
                    </tr>
                    <script language="javascript" >
                    $("#txt1").click(function () {
                    $("#msg_1").toggle("slow");
                    });
                    </script>
                    
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
                    /*$strticketact3days = '';
                    $iact3days = 1;
                    while ($rowact3days = db_fetch_array($resact3days)) {
                    if ($iact3days<$numact3days) {
                    $strticketact3days .= '<a href="tickets.php?id='.$rowact3days['ticket_id'].'" target="_blank">'.$rowact3days['ticketID'].'</a>, ';
                    } elseif ($iact3days>=$numact3days) {
                    $strticketact3days .=  '<a href="tickets.php?id='.$rowact3days['ticket_id'].'" target="_blank">'.$rowact3days['ticketID'].'</a>';
                    }
                    $iact3days++;
                    }*/
                    }
                    ?>
                    <tr>
                    <th>
                    <a href="#" id="txt2">
                    <span style="color:#666; float: left; width:350px;"><?php echo " Active Complaints (Today): "; ?></span></a></th>                   
                    <td><?php echo '<b><span align="right">'.$numact3daysopen.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysover.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysclosed.'</span></b><br />'; ?></td>
                    <!--<td>
                    <span class="Icon <?php //echo $icon?>" align="right"><?php //echo '<b><span align="right">'.$numact3days.'</span></b><br />'; ?></span></td>-->
                    </tr>
                    <div id="msg_2" class="hide">
                    <hr>
                    <span style="text-align:left;float:left;">Complaints Item(s): <i><?php echo ($strticketact3days)?$strticketact3days:'-'; ?></i></span><br />
                    </div>
                    <script language="javascript" >
                    $("#txt2").click(function () {
                    $("#msg_2").toggle("slow");
                    });
                    </script>
                    
					<?php
                    $numact7days=0;
                    if ($staffid) {
                    $sqlact3days='SELECT * FROM '.TICKET_TABLE.' '.'WHERE staff_id <> "0" AND 7 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND status = "open" and staff_id = '.$staffid.'';
                    } else {
                    $sqlact3days='SELECT * FROM '.TICKET_TABLE.' '.'WHERE  7 >= TIMESTAMPDIFF(DAY,created,NOW())';
                    $sqlact3daysopen='SELECT * FROM '.TICKET_TABLE.' '.'WHERE  7 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND status = "open"';	
                    $sqlact3dayscloded='SELECT * FROM '.TICKET_TABLE.' '.'WHERE  7 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND status = "closed"';	
                    $sqlact3daysover='SELECT * FROM '.TICKET_TABLE.' '.'WHERE  7 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND isoverdue = "1"';	
                    $sqlact3daysassing='SELECT * FROM '.TICKET_TABLE.' '.'WHERE  7 >= TIMESTAMPDIFF(DAY,created,NOW()) '.'AND staff_id != "0"';				   			   		   	   
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
                    /*$strticketact3days = '';
                    $iact3days = 1;
                    while ($rowact3days = db_fetch_array($resact3days)) {
                    if ($iact3days<$numact3days) {
                    $strticketact3days .= '<a href="tickets.php?id='.$rowact3days['ticket_id'].'" target="_blank">'.$rowact3days['ticketID'].'</a>, ';
                    } elseif ($iact3days>=$numact3days) {
                    $strticketact3days .=  '<a href="tickets.php?id='.$rowact3days['ticket_id'].'" target="_blank">'.$rowact3days['ticketID'].'</a>';
                    }
                    $iact3days++;
                    }*/
                    }
                    ?>
                    <tr>
                    <th>
                    <a href="#" id="txt2">
                    <span style="color:#666; float: left; width:350px;"><?php echo " Active Complaints (Week): "; ?></span></a></th>                    
                    <td><?php echo '<b><span align="right">'.$numact3daysopen.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysover.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysclosed.'</span></b><br />'; ?></td>
                    <!--<td>
                    <span class="Icon <?php //echo $icon?>" align="right"><?php //echo '<b><span align="right">'.$numact3days.'</span></b><br />'; ?></span></td>-->
                    </tr>
                    <script language="javascript" >
                    $("#txt3").click(function () {
                    $("#msg_3").toggle("slow");
                    });
                    </script>
					
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
                        <span style="color:#666; float: left; width:350px;"><?php echo " Active Complaints (Months): "; ?></span></a></th>                        
                        <td><?php echo '<b><span align="right">'.$numact3daysopen.'</span></b><br />'; ?></td>
                        <td><?php echo '<b><span align="right">'.$numact3daysover.'</span></b><br />'; ?></td>
                        <td><?php echo '<b><span align="right">'.$numact3daysclosed.'</span></b><br />'; ?></td>
                        <!--<td>
                        <span class="Icon <?php //echo $icon?>" align="right"><?php //echo '<b><span align="right">'.$numact3days.'</span></b><br />'; ?></span></td>-->
                        </tr>
                    <script language="javascript" >
                    $("#txt4").click(function () {
                    $("#msg_4").toggle("slow");
                    });
                    </script>
                    
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
                    /*$strticketact3days = '';
                    $iact3days = 1;
                    while ($rowact3days = db_fetch_array($resact3days)) {
                    if ($iact3days<$numact3days) {
                    $strticketact3days .= '<a href="tickets.php?id='.$rowact3days['ticket_id'].'" target="_blank">'.$rowact3days['ticketID'].'</a>, ';
                    } elseif ($iact3days>=$numact3days) {
                    $strticketact3days .=  '<a href="tickets.php?id='.$rowact3days['ticket_id'].'" target="_blank">'.$rowact3days['ticketID'].'</a>';
                    }
                    $iact3days++;
                    }*/
                    }
                    ?>
                    <tr>
                    <th>
                    <a href="#" id="txt2">
                    <span style="color:#666; float: left; width:350px;"><?php echo " Active Complaints (Years): "; ?></span></a></th>                    
                    <td><?php echo '<b><span align="right">'.$numact3daysopen.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysover.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysclosed.'</span></b><br />'; ?></td>
                    <!--<td>
                    <span class="Icon <?php //echo $icon?>" align="right"><?php //echo '<b><span align="right">'.$numact3days.'</span></b><br />'; ?></span></td>-->
                    </tr>
                    <div id="msg_5" class="hide">
                    <hr>
                    <span style="text-align:left;float:left;">Ticket Item(s): <i><?php echo ($strticketactmore14days)?$strticketactmore14days:'-'; ?></i></span><br />
                    </div>                    
                    <script language="javascript" >
                    $("#txt5").click(function () {
                    $("#msg_5").toggle("slow");
                    });
                    </script>
                    <!--Graph-->
                    <tr>
                    <td> 
                    <!--<div id="chart1" style="margin-top:20px; margin-left:20px; width:400px; height:400px;"></div>-->
                    <!--<script class="code" type="text/javascript">
                    $(document).ready(function(){
                    $.jqplot.config.enablePlugins = true;
                    var s1 = [<?php //echo $numact1days;?>, <?php //echo $numact7days;?>, <?php //echo $numact14days;?>, <?php //echo $numactmore14days;?>];
                    var ticks = ['Daily', 'Weekly', 'Montly', 'Yearly'];
                    
                    plot1 = $.jqplot('chart1', [s1], {
                    // Only animate if we're not using excanvas (not in IE 7 or IE 8)..
                    animate: !$.jqplot.use_excanvas,
                    seriesDefaults:{
                    renderer:$.jqplot.BarRenderer,
                    pointLabels: { show: true }
                    },
                    axes: {
                    xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer,
                    ticks: ticks
                    }
                    },
                    highlighter: { show: false }
                    });
                    
                    $('#chart1').bind('jqplotDataClick', 
                    function (ev, seriesIndex, pointIndex, data) {
                    $('#info1').html('series: '+seriesIndex+', point: '+pointIndex+', data: '+data);
                    }
                    );
                    });</script>-->
                    </td>
                    </tr>
            </tbody>                              
            </table>
        </div>
 </div>                      
</div>                        
<div class="dr"><span></span></div>   
</div><!--WorkPlace End-->  
</div>   


