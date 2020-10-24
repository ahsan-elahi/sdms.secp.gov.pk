<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

if($_REQUEST['staffid']) {
  $staffid = $_REQUEST['staffid'];
}

echo '<div class="msg">Ticket Summary</div>';

$numtotalstaff=0;
$sqltotalstaff='SELECT DISTINCT(t.staff_id), s.username, s.firstname, s.lastname '.
               'FROM '.TICKET_TABLE.' t, '.STAFF_TABLE.' s '.
               'WHERE t.staff_id <> "0" AND t.staff_id=s.staff_id';
if(($restotalstaff=db_query($sqltotalstaff)) && ($numtotalstaff=db_num_rows($restotalstaff))) {
 $strtickettotalstaff = '';
 $itotalstaff = 1;
?>

<?
}

echo '<div style="margin-bottom:20px; padding-top:0px;">';
?>
<table class="table table-condensed table-striped"><thead><tr><th>Daily Reports</th><th>Totals</th><th>Opened</th><th>Assigned</th><th>Overdue</th><th>Closed</th></tr></thead><tbody class="" page="1"><tr>

<?php
$numstaff=0;
if ($staffid) {
  $sqlstaff='SELECT DISTINCT(t.staff_id), s.username FROM '.TICKET_TABLE.' t, '.STAFF_TABLE.' s '.
          'WHERE t.staff_id = '.$staffid.' AND t.staff_id=s.staff_id';
} else {
  $sqlstaff='SELECT DISTINCT(t.staff_id), s.username FROM '.TICKET_TABLE.' t, '.STAFF_TABLE.' s '.
          'WHERE t.staff_id <> "0" AND t.staff_id=s.staff_id';
}

if(($resstaff=db_query($sqlstaff)) && ($numstaff=db_num_rows($resstaff))) {
 $strticketstaff = '';
 $istaff = 1;
 while ($rowstaff = db_fetch_array($resstaff)) {
   if ($istaff<$numstaff) {
	   ?><th><?php
     $strticketstaff .= '<a href="admin.php?t=staff&id='.$rowstaff['staff_id'].'" target="_blank">'.$rowstaff['username'].'</a>, ';?></th><td><img src="images/Assigned.png"/></td><th><img src="images/Assigned.png"/></th><th><img src="images/Assigned.png"/></th><th><img src="images/Assigned.png"/></th><th><img src="images/Assigned.png"/></th><th><img src="images/Assigned.png"/></th>
     <?php
   } elseif ($istaff>=$numstaff) {
	   ?>
      
       <?php
     $strticketstaff .=  '<a href="admin.php?t=staff&id='.$rowstaff['staff_id'].'" target="_blank">'.$rowstaff['username'].'</a>';?>
    
     <?php
   }
   $istaff++;
 }
}
?>
</tr>
<tr class="row1" id="1">
<td>
<a href="javascript:toggleMessage('1');">
<img border="0" align="left" id="img_1" src="images/plus.gif">
<span style="color:000; float: left; width:250px;"><? echo "Staff in Charge: "; ?></span>
&nbsp;&nbsp;
 <span class="Icon <?=$icon?>" align="right"><? echo '<b>'.$numstaff.'</b><br />'; ?></span></a>
   <div id="msg_1" class="hide">
       <hr>
       <span style="text-align:left;float:left;">Staff in Charge Username: <i><? echo ($strticketstaff)?$strticketstaff:'-'; ?></i></span><br />
   </div>
</td>
</tr>

<?
$numtotal=0;
if ($staffid) {
  $sqltotal='SELECT * FROM '.TICKET_TABLE.' WHERE staff_id = '.$staffid.'';
} else {
  $sqltotal='SELECT * FROM '.TICKET_TABLE.' WHERE staff_id <> "0"';
}
if(($restotal=db_query($sqltotal)) && ($numtotal=db_num_rows($restotal))) {
 $strtickettotal = '';
 $itotal = 1;
 while ($rowtotal = db_fetch_array($restotal)) {
   if (($itotal<$numtotal)) {
     $strtickettotal .= '<a href="tickets.php?id='.$rowtotal['ticket_id'].'" target="_blank">'.$rowtotal['ticketID'].'</a>, ';
   } elseif ($itotal>=$numtotal) {
     $strtickettotal .=  '<a href="tickets.php?id='.$rowtotal['ticket_id'].'" target="_blank">'.$rowtotal['ticketID'].'</a>';
   }
   $itotal++;
 }
}
?>
<tr class="row2" id="2">
<td>
<a href="javascript:toggleMessage('2');">
<img border="0" align="left" id="img_2" src="images/plus.gif">
<span style="color:000; float: left; width:250px;"><? echo "Total Number of Active Tickets: "; ?></span>
&nbsp;&nbsp;
 <span class="Icon <?=$icon?>" align="right"><? echo '<b>'.$numtotal.'</b><br />'; ?></span></a>
   <div id="msg_2" class="hide">
       <hr>
       <span style="text-align:left;float:left;">Ticket Item(s): <i><? echo ($strtickettotal)?$strtickettotal:'-'; ?></i></span><br />
   </div>
</td>
</tr>
<?

$numtotalclosed=0;
if ($staffid) {
  $sqltotalclosed='SELECT * FROM '.TICKET_TABLE.' WHERE status = "closed" and staff_id='.$staffid.'';
} else {
  $sqltotalclosed='SELECT * FROM '.TICKET_TABLE.' WHERE status = "closed"';
}
if(($restotalclosed=db_query($sqltotalclosed)) && ($numtotalclosed=db_num_rows($restotalclosed))) {
 $strtickettotalclosed = '';
 $itotalclosed = 1;
 while ($rowtotalclosed = db_fetch_array($restotalclosed)) {
   if ($itotalclosed<$numtotalclosed) {
     $strtickettotalclosed .= '<a href="tickets.php?id='.$rowtotalclosed['ticket_id'].'" target="_blank">'.$rowtotalclosed['ticketID'].'</a>, ';
   } elseif ($itotalclosed>=$numtotalclosed) {
     $strtickettotalclosed .=  '<a href="tickets.php?id='.$rowtotalclosed['ticket_id'].'" target="_blank">'.$rowtotalclosed['ticketID'].'</a>';
   }
   $itotalclosed++;
 }
}
?>
<tr class="row1" id="3">
<td>
<a href="javascript:toggleMessage('3');">
<img border="0" align="left" id="img_3" src="images/plus.gif">
<span style="color:000; float: left; width:250px;"><? echo "Total Number of Closed Tickets: "; ?></span>
&nbsp;&nbsp;
 <span class="Icon <?=$icon?>" align="right"><? echo '<b>'.$numtotalclosed.'</b><br />'; ?></span></a>
   <div id="msg_3" class="hide">
       <hr>
       <span style="text-align:left;float:left;">Ticket Item(s): <i><? echo ($strtickettotalclosed)?$strtickettotalclosed:'-'; ?></i></span><br />
   </div>
</td>
</tr>
<?

$numtotalunassigned=0;
if ($staffid) {
  $sqltotalunassigned='SELECT * FROM '.TICKET_TABLE.' WHERE staff_id = "0" and staff_id = '.$staffid.'';
} else {
  $sqltotalunassigned='SELECT * FROM '.TICKET_TABLE.' WHERE staff_id = "0"';
}
if(($restotalunassigned=db_query($sqltotalunassigned)) && ($numtotalunassigned=db_num_rows($restotalunassigned))) {
 $strtickettotalunassigned = '';
 $itotalunassigned = 1;
 while ($rowtotalunassigned = db_fetch_array($restotalunassigned)) {
   if ($itotalunassigned<$numtotalunassigned) {
     $strtickettotalunassigned .= '<a href="tickets.php?id='.$rowtotalunassigned['ticket_id'].'" target="_blank">'.$rowtotalunassigned['ticketID'].'</a>, ';
   } elseif ($itotalunassigned>=$numtotalunassigned) {
     $strtickettotalunassigned .=  '<a href="tickets.php?id='.$rowtotalunassigned['ticket_id'].'" target="_blank">'.$rowtotalunassigned['ticketID'].'</a>';
   }
   $itotalunassigned++;
 }
}
?>
<tr class="row2" id="4">
<td>
<a href="javascript:toggleMessage('4');">
<img border="0" align="left" id="img_4" src="images/plus.gif">
<span style="color:000; float: left; width:250px;"><? echo "Total Number of Unassigned Tickets: "; ?></span>
&nbsp;&nbsp;
 <span class="Icon <?=$icon?>" align="right"><? echo '<b>'.$numtotalunassigned.'</b><br />'; ?></span></a>
   <div id="msg_4" class="hide">
       <hr>
       <span style="text-align:left;float:left;">Ticket Item(s): <i><? echo ($strtickettotalunassigned)?$strtickettotalunassigned:'-'; ?></i></span><br />
   </div>
</td>
</tr>

</table>
</td></tr>
</table>
</div>