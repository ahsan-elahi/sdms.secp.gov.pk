 <!--[if lt IE 9]><script language="javascript" type="text/javascript" src="../excanvas.js"></script><![endif]-->
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
require_once 'phoogle.php';
$map = new PhoogleMap();
if($_REQUEST['staffid']) {
  $staffid = $_REQUEST['staffid'];
}

//echo '<div class="msg">Ticket Aging</div>';

$numtotalstaff=0;
$sqltotalstaff='SELECT DISTINCT(t.staff_id), s.username, s.firstname, s.lastname '.
               'FROM '.TICKET_TABLE.' t, '.STAFF_TABLE.' s '.
               'WHERE t.staff_id <> "0" AND t.staff_id=s.staff_id';


echo '<div style="margin-bottom:20px; padding-top:0px;">';
echo '<table width="100%" border="0" cellspacing=1 cellpadding=2>';
echo '<tr ><td>
       <table width="100%" border="0" cellspacing=0 cellpadding=2 class="logs" align="center">
       <tr><th style="font-size:22px; color:blue;">&nbsp;&nbsp;Complaints Geographical Report</th><th><a id="ticket-print" class="action-button" href="tickets.php?id=66&a=print">
<i class="icon-print"></i>
Print
</a></th></tr>';

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
?><div id="table-here">
<script src="http://maps.google.com/maps?file=api&v=2&key=AIzaSyAyC7H-SZsUi1smj9PdPCrp5n8gkhf3TF8"  type="text/javascript"></script>
<?php

$map->addAddress('208 Dingler Ave, Mooresville, NC 28115',$showmsg);
//$map->addAddress($addres,$showmsg);
echo $map->showMap();
?>
<table class="table table-condensed table-striped"><thead><tr><th>Daily Reports</th><th>Totals</th><th>Opened</th><th>Assigned</th><th>Overdue</th><th>Closed</th></tr></thead><tbody class="" page="1"><tr>
<th>
<a href="#" id="txt1">
<img border="0" align="left" id="img_1" src="images/plus.gif">
<span style=" color:#666; float: left; width:350px;"><?php echo " Active Complaints: "; ?></span></th>
&nbsp;&nbsp;
<th>
 <span class="Icon <?php echo $icon?>" align="right"><?php echo '<b>'.$numtotal.'</b>'; ?>&nbsp;<img src="images/Opened.png"/></span>
   <div id="msg_1" class="hide" >
       <hr>
       <span style="text-align:left;float:left;">Ticket Item(s): <i><?php echo ($strtickettotal)?$strtickettotal:'-'; ?></i></span><br />
   </div>
</th><th><?php echo $numact3daysopen;?>&nbsp;<img src="images/Opened.png"/></th><th><?php echo $numact3daysassing;?>&nbsp;<img src="images/Assigned.png"/></th><th><?php echo $numact3daysover;?>&nbsp;<img src="images/Closed.png"/></th><th><?php echo $numact3daysclosed;?>&nbsp;<img src="images/Overdue.png"/></th>
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
<tr><th>
<a href="#" id="txt2">
<img border="0" align="left" id="img_2" src="images/plus.gif">
<span style="color:#666; float: left; width:350px;"><?php echo " Active Complaints (Today): "; ?></span></th>
&nbsp;&nbsp;
 <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$numact3days.'</span></b><br />'; ?></span></td>
 <td><?php echo '<b><span align="right">'.$numact3daysopen.'</span></b><br />'; ?></td><td><?php echo '<b><span align="right">'.$numact3daysassing.'</span></b><br />'; ?></td><td><?php echo '<b><span align="right">'.$numact3daysover.'</span></b><br />'; ?></td><td><?php echo '<b><span align="right">'.$numact3daysclosed.'</span></b><br />'; ?></td></tr>
   <div id="msg_2" class="hide">
       <hr>
       <span style="text-align:left;float:left;">Complaints Item(s): <i><?php echo ($strticketact3days)?$strticketact3days:'-'; ?></i></span><br />
   </div>
</td>
</tr>
<script language="javascript" >
$("#txt2").click(function () {
$("#msg_2").toggle("slow");
});
</script>
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
<tr><th>
<a href="#" id="txt2">
<img border="0" align="left" id="img_2" src="images/plus.gif">
<span style="color:#666; float: left; width:350px;"><?php echo " Active Complaints (Week): "; ?></span></th>
&nbsp;&nbsp;
 <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$numact3days.'</span></b><br />'; ?></span></td>
 <td><?php echo '<b><span align="right">'.$numact3daysopen.'</span></b><br />'; ?></td><td><?php echo '<b><span align="right">'.$numact3daysassing.'</span></b><br />'; ?></td><td><?php echo '<b><span align="right">'.$numact3daysover.'</span></b><br />'; ?></td><td><?php echo '<b><span align="right">'.$numact3daysclosed.'</span></b><br />'; ?></td></tr>
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
<tr><th>
<a href="#" id="txt2">
<img border="0" align="left" id="img_2" src="images/plus.gif">
<span style="color:#666; float: left; width:350px;"><?php echo " Active Complaints (Months): "; ?></span></th>
&nbsp;&nbsp;
 <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$numact3days.'</span></b><br />'; ?></span></td>
 <td><?php echo '<b><span align="right">'.$numact3daysopen.'</span></b><br />'; ?></td><td><?php echo '<b><span align="right">'.$numact3daysassing.'</span></b><br />'; ?></td><td><?php echo '<b><span align="right">'.$numact3daysover.'</span></b><br />'; ?></td><td><?php echo '<b><span align="right">'.$numact3daysclosed.'</span></b><br />'; ?></td></tr>
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
<tr><th>
<a href="#" id="txt2">
<img border="0" align="left" id="img_2" src="images/plus.gif">
<span style="color:#666; float: left; width:350px;"><?php echo " Active Complaints (Years): "; ?></span></th>
&nbsp;&nbsp;
 <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$numact3days.'</span></b><br />'; ?></span></td>
 <td><?php echo '<b><span align="right">'.$numact3daysopen.'</span></b><br />'; ?></td><td><?php echo '<b><span align="right">'.$numact3daysassing.'</span></b><br />'; ?></td><td><?php echo '<b><span align="right">'.$numact3daysover.'</span></b><br />'; ?></td><td><?php echo '<b><span align="right">'.$numact3daysclosed.'</span></b><br />'; ?></td></tr>
   <div id="msg_5" class="hide">
       <hr>
       <span style="text-align:left;float:left;">Ticket Item(s): <i><?php echo ($strticketactmore14days)?$strticketactmore14days:'-'; ?></i></span><br />
   </div>
</td>
</tr>
<script language="javascript" >
$("#txt5").click(function () {
$("#msg_5").toggle("slow");
});
</script>
</table>
</td></tr>
<tr>
<td>
 
<div id="chart1" style="margin-top:20px; margin-left:20px; width:400px; height:400px;"></div>
<script class="code" type="text/javascript">$(document).ready(function(){
        $.jqplot.config.enablePlugins = true;
        var s1 = [<?php echo $numact1days;?>, <?php echo $numact7days;?>, <?php echo $numact14days;?>, <?php echo $numactmore14days;?>];
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
    });</script>

</td>
</tr>
</table>


</div> 
