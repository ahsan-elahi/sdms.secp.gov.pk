<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

if($_REQUEST['staffid']) {
  $staffid = $_REQUEST['staffid'];
}

echo '<div class="msg">Ticket Aging</div>';

$numtotalstaff=0;
$sqltotalstaff='SELECT DISTINCT(t.staff_id), s.username, s.firstname, s.lastname '.
               'FROM '.TICKET_TABLE.' t, '.STAFF_TABLE.' s '.
               'WHERE t.staff_id <> "0" AND t.staff_id=s.staff_id';
if(($restotalstaff=db_query($sqltotalstaff)) && ($numtotalstaff=db_num_rows($restotalstaff))) {
 $strtickettotalstaff = '';
 $itotalstaff = 1;
?>
<form action="admin.php?t=ticketaging" method="post">
 <input type=hidden name='a' value='staff'>
 Staff Name:&nbsp;<select name="staffid" >
  <option value="" selected >Select One</option>
<?
 while ($rowtotalstaff = db_fetch_array($restotalstaff)) {
   $selected = ($_REQUEST['staffid']==$rowtotalstaff['staff_id'])?'selected':''; ?>
   <option value="<?=$rowtotalstaff['staff_id']?>"<?=$selected?>><? echo $rowtotalstaff['username'].' ('.$rowtotalstaff['firstname'].' '.$rowtotalstaff['lastname'].')'; ?></option>
<?
 }
?>
 </select>
 <input class="button" type="submit" value="View">
</form>
<?
}

echo '<div style="margin-bottom:20px; padding-top:0px;">';
echo '<table width="100%" border="0" cellspacing=1 cellpadding=2>';
echo '<tr><td>
       <table width="100%" border="0" cellspacing=0 cellpadding=2 class="logs" align="center">
       <tr><th>&nbsp;&nbsp;Ticket Aging and Its Count</th></tr>';

$numtotal=0;
if ($staffid) {
  $sqltotal='SELECT * FROM '.TICKET_TABLE.' WHERE staff_id = '.$staffid.' AND status = "open"';
} else {
  $sqltotal='SELECT * FROM '.TICKET_TABLE.' WHERE staff_id <> "0" AND status = "open"';
}
if(($restotal=db_query($sqltotal)) && ($numtotal=db_num_rows($restotal))) {
 $strtickettotal = '';
 $itotal = 1;
 while ($rowtotal = db_fetch_array($restotal)) {
   if ($itotal<$numtotal) {
     $strtickettotal .= '<a href="tickets.php?id='.$rowtotal['ticket_id'].'" target="_blank">'.$rowtotal['ticketID'].'</a>, ';
   } elseif ($itotal>=$numtotal) {
     $strtickettotal .=  '<a href="tickets.php?id='.$rowtotal['ticket_id'].'" target="_blank">'.$rowtotal['ticketID'].'</a>';
   }
   $itotal++;
 }
}
?>
<tr class="row1" id="1">
<td>
<a href="javascript:toggleMessage('1');">
<img border="0" align="left" id="img_1" src="images/plus.gif">
<span style="color:000; float: left; width:350px;"><? echo "Total Number of Active Tickets: "; ?></span>
&nbsp;&nbsp;
 <span class="Icon <?=$icon?>" align="right"><? echo '<b>'.$numtotal.'</b><br />'; ?></span></a>
   <div id="msg_1" class="hide">
       <hr>
       <span style="text-align:left;float:left;">Ticket Item(s): <i><? echo ($strtickettotal)?$strtickettotal:'-'; ?></i></span><br />
   </div>
</td>
</tr>

<?
$numact3days=0;
if ($staffid) {
  $sqlact3days='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE staff_id <> "0" AND 3 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND status = "open" and staff_id = '.$staffid.'';
} else {
  $sqlact3days='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE staff_id <> "0" AND 3 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND status = "open"';
}
if(($resact3days=db_query($sqlact3days)) && ($numact3days=db_num_rows($resact3days))) {
 $strticketact3days = '';
 $iact3days = 1;
 while ($rowact3days = db_fetch_array($resact3days)) {
   if ($iact3days<$numact3days) {
     $strticketact3days .= '<a href="tickets.php?id='.$rowact3days['ticket_id'].'" target="_blank">'.$rowact3days['ticketID'].'</a>, ';
   } elseif ($iact3days>=$numact3days) {
     $strticketact3days .=  '<a href="tickets.php?id='.$rowact3days['ticket_id'].'" target="_blank">'.$rowact3days['ticketID'].'</a>';
   }
   $iact3days++;
 }
}
?>
<tr class="row2" id="2">
<td>
<a href="javascript:toggleMessage('2');">
<img border="0" align="left" id="img_2" src="images/plus.gif">
<span style="color:000; float: left; width:350px;"><? echo "Total Number of Active Tickets (<= 3 Days): "; ?></span>
&nbsp;&nbsp;
 <span class="Icon <?=$icon?>" align="right"><? echo '<b><span align="right">'.$numact3days.'</span></b><br />'; ?></span></a>
   <div id="msg_2" class="hide">
       <hr>
       <span style="text-align:left;float:left;">Ticket Item(s): <i><? echo ($strticketact3days)?$strticketact3days:'-'; ?></i></span><br />
   </div>
</td>
</tr>

<?
$numact7days=0;
if ($staffid) {
  $sqlact7days='SELECT * FROM '.TICKET_TABLE.' WHERE staff_id = '.$staffid.' AND (7 >= TIMESTAMPDIFF(DAY,created,NOW()) AND 3 < TIMESTAMPDIFF(DAY,created,NOW())) AND status = "open"';
} else {
  $sqlact7days='SELECT * FROM '.TICKET_TABLE.' WHERE staff_id <> "0" AND (7 >= TIMESTAMPDIFF(DAY,created,NOW()) AND 3 < TIMESTAMPDIFF(DAY,created,NOW())) AND status = "open"';
}

if(($resact7days=db_query($sqlact7days)) && ($numact7days=db_num_rows($resact7days))) {
 $strticketact7days = '';
 $iact7days = 1;
 while ($rowact7days = db_fetch_array($resact7days)) {
   if ($iact7days<$numact7days) {
     $strticketact7days .= '<a href="tickets.php?id='.$rowact7days['ticket_id'].'" target="_blank">'.$rowact7days['ticketID'].'</a>, ';
   } elseif ($iact7days>=$numact7days) {
     $strticketact7days .=  '<a href="tickets.php?id='.$rowact7days['ticket_id'].'" target="_blank">'.$rowact7days['ticketID'].'</a>';
   }
   $iact7days++;
 }
}
?>
<tr class="row1" id="3">
<td>
<a href="javascript:toggleMessage('3');">
<img border="0" align="left" id="img_3" src="images/plus.gif">
<span style="color:000; float: left; width:350px;"><? echo "Total Number of Active Tickets (> 3 Days and <= 7 Days): "; ?></span>
&nbsp;&nbsp;
 <span class="Icon <?=$icon?>" align="right"><? echo '<b><span align="right">'.$numact7days.'</span></b><br />'; ?></span></a>
   <div id="msg_3" class="hide">
       <hr>
       <span style="text-align:left;float:left;">Ticket Item(s): <i><? echo ($strticketact7days)?$strticketact7days:'-'; ?></i></span><br />
   </div>
</td>
</tr>

<?
$numact14days=0;
if ($staffid) {
  $sqlact14days='SELECT * FROM '.TICKET_TABLE.' WHERE staff_id = '.$staffid.' AND (14 >= TIMESTAMPDIFF(DAY,created,NOW()) AND 7 < TIMESTAMPDIFF(DAY,created,NOW())) AND status = "open"';
} else {
  $sqlact14days='SELECT * FROM '.TICKET_TABLE.' WHERE staff_id <> "0" AND (14 >= TIMESTAMPDIFF(DAY,created,NOW()) AND 7 < TIMESTAMPDIFF(DAY,created,NOW())) AND status = "open"';
}

if(($resact14days=db_query($sqlact14days)) && ($numact14days=db_num_rows($resact14days))) {
 $strticketact14days = '';
 $iact14days = 1;
 while ($rowact14days = db_fetch_array($resact14days)) {
   if ($iact14days<$numact14days) {
     $strticketact14days .= '<a href="tickets.php?id='.$rowact14days['ticket_id'].'" target="_blank">'.$rowact14days['ticketID'].'</a>, ';
   } elseif ($iact14days>=$numact14days) {
     $strticketact14days .=  '<a href="tickets.php?id='.$rowact14days['ticket_id'].'" target="_blank">'.$rowact14days['ticketID'].'</a>';
   }
   $iact14days++;
 }
}
?>
<tr class="row2" id="4">
<td>
<a href="javascript:toggleMessage('4');">
<img border="0" align="left" id="img_4" src="images/plus.gif">
<span style="color:000; float: left; width:350px;"><? echo "Total Number of Active Tickets (> 7 Days and <= 14 Days): "; ?></span>
&nbsp;&nbsp;
 <span class="Icon <?=$icon?>" align="right"><? echo '<b><span align="right">'.$numact14days.'</span></b><br />'; ?></span></a>
   <div id="msg_4" class="hide">
       <hr>
       <span style="text-align:left;float:left;">Ticket Item(s): <i><? echo ($strticketact14days)?$strticketact14days:'-'; ?></i></span><br />
   </div>
</td>
</tr>

<?
$numactmore14days=0;
if ($staffid) {
  $sqlactmore14days='SELECT * FROM '.TICKET_TABLE.' WHERE staff_id = '.$staffid.' AND 14 < TIMESTAMPDIFF(DAY,created,NOW()) AND status = "open"';
} else {
  $sqlactmore14days='SELECT * FROM '.TICKET_TABLE.' WHERE staff_id <> "0" AND 14 < TIMESTAMPDIFF(DAY,created,NOW()) AND status = "open"';
}

if(($resactmore14days=db_query($sqlactmore14days)) && ($numactmore14days=db_num_rows($resactmore14days))) {
 $strticketactmore14days = '';
 $iactmore14days = 1;
 while ($rowactmore14days = db_fetch_array($resactmore14days)) {
   if ($iactmore14days<$numactmore14days) {
     $strticketactmore14days .= '<a href="tickets.php?id='.$rowactmore14days['ticket_id'].'" target="_blank">'.$rowactmore14days['ticketID'].'</a>, ';
   } elseif ($iactmore14days>=$numactmore14days) {
     $strticketactmore14days .=  '<a href="tickets.php?id='.$rowactmore14days['ticket_id'].'" target="_blank">'.$rowactmore14days['ticketID'].'</a>';
   }
   $iactmore14days++;
 }
}
?>
<tr class="row1" id="5">
<td>
<a href="javascript:toggleMessage('5');">
<img border="0" align="left" id="img_5" src="images/plus.gif">
<span style="color:000; float: left; width:350px;"><? echo "Total Number of Active Tickets (> 14 Days): "; ?></span>
&nbsp;&nbsp;
 <span class="Icon <?=$icon?>" align="right"><? echo '<b><span align="right">'.$numactmore14days.'</span></b><br />'; ?></span></a>
   <div id="msg_5" class="hide">
       <hr>
       <span style="text-align:left;float:left;">Ticket Item(s): <i><? echo ($strticketactmore14days)?$strticketactmore14days:'-'; ?></i></span><br />
   </div>
</td>
</tr>

</table>
</td></tr>
</table>