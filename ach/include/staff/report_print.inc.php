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

echo '<div style="margin-bottom:20px; padding-top:0px;">';
echo '<table width="100%" border="0" cellspacing=1 cellpadding=2>';
echo '<tr>
		<td>
       		<table width="100%" border="0" cellspacing=0 cellpadding=2 class="logs" align="center">
       			<tr>
				<th style="font-size:22px; color:blue;">&nbsp;&nbsp;Complaints Aging and Its Count</th>
				</tr>
			</table>';

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
    <div id="table-here">
        <table class="table table-condensed table-striped">
            <thead>
            <tr>
            <th>Daily Reports</th>
            <th>Totals</th>
            <th>Opened</th>
            <th>Assigned</th>
            <th>Overdue</th>
            <th>Closed</th>
            </tr>
            </thead>
            <tbody class="" page="1">
                    <tr>
                        <th><span style=" color:#666; float: left; width:350px;"><?php echo " Active Complaints: "; ?></span></th>
                        <th>
                         <span class="Icon <?php echo $icon?>" align="right"><?php echo '<b>'.$numtotal.'</b>'; ?>&nbsp;<img src="images/Opened.png"/></span>
                           <div id="msg_1" class="hide" >
                               <hr>
                               <span style="text-align:left;float:left;">Ticket Item(s): <i><?php echo ($strtickettotal)?$strtickettotal:'-'; ?></i></span><br />
                           </div>
                        </th>
                        <th><?php echo $numact3daysopen;?>&nbsp;<img src="images/Opened.png"/></th>
                        <th><?php echo $numact3daysassing;?>&nbsp;<img src="images/Assigned.png"/></th>
                        <th><?php echo $numact3daysover;?>&nbsp;<img src="images/Closed.png"/></th>
                        <th><?php echo $numact3daysclosed;?>&nbsp;<img src="images/Overdue.png"/></th>
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

if(($resact3days=db_query($sqlact3days)) && ($numact3days=db_num_rows($resact3days))) {
	$numact1days=$numact3days;
}
?>
                    <tr>
                    <th><span style="color:#666; float: left; width:350px;"><?php echo " Active Complaints (Today): "; ?></span></th>
                    &nbsp;&nbsp;
                    <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$numact3days.'</span></b><br />'; ?></span></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysopen.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysassing.'</span></b><br />'; ?></td>
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
                    <th><span style="color:#666; float: left; width:350px;"><?php echo " Active Complaints (Week): "; ?></span></th>
                    <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$numact3days.'</span></b><br />'; ?></span></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysopen.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysassing.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysover.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysclosed.'</span></b><br />'; ?></td>
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
                    <th><span style="color:#666; float: left; width:350px;"><?php echo " Active Complaints (Months): "; ?></span></th>
                    <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$numact3days.'</span></b><br />'; ?></span></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysopen.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysassing.'</span></b><br />'; ?></td>
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

if(($resact3days=db_query($sqlact3days)) && ($numact3days=db_num_rows($resact3days))) {
	$numactmore14days=$numact3days;
}
?>
                    <tr>
                    <th><span style="color:#666; float: left; width:350px;"><?php echo " Active Complaints (Years): "; ?></span></th>
                    <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$numact3days.'</span></b><br />'; ?></span></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysopen.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysassing.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysover.'</span></b><br />'; ?></td>
                    <td><?php echo '<b><span align="right">'.$numact3daysclosed.'</span></b><br />'; ?></td>
                    </tr>
                    <div id="msg_5" class="hide">
                       <hr>
                       <span style="text-align:left;float:left;">Ticket Item(s): <i><?php echo ($strticketactmore14days)?$strticketactmore14days:'-'; ?></i></span><br />
                   </div>
</tbody>                    
		</table>
	</div>
</td>
</tr>
<tr>
<td> 
</td>
</tr>
</table>
</div> 
