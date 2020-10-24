<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
?>
<div style="margin-bottom:20px; padding-top:0px;">
<table width="100%" border="0" cellspacing=1 cellpadding=2>
<tr>
		<td>
       		<table width="100%" border="0" cellspacing=0 cellpadding=2 class="logs" align="center">
       			<tr>
				<th style="font-size:22px; color:blue;">&nbsp;&nbsp;Department Communication</th>
				</tr>
				</table>
</table>
</td>
</tr>
</table>
<h1><?php echo $_SESSION['respone_title'];?></h1>
<div><?php	echo 	$_SESSION['respone_note'];?></div>
</div> 
