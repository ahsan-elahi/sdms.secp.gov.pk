<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

echo '<div style="margin-bottom:20px; padding-top:0px;">';
echo '<table width="100%" border="0" cellspacing=1 cellpadding=2>';
echo '<tr>
		<td>
       		<table width="100%" border="0" cellspacing=0 cellpadding=2 class="logs" align="center">
       			<tr>
				<th style="font-size:22px; color:blue;">&nbsp;&nbsp;Complaints Source Data</th>
				</tr>
				</table>';
$qselect='SELECT count(ticket_id) as ticketno,source';
$qfrom='FROM '.TICKET_TABLE.' t';
$qwhere='group by source';
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
   <div id="table-here">
    <form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
    <input type="hidden" name="a" value="mass_process" >
    <input type="hidden" name="status" value="<?php echo $statusss?>" >
    <table class="table table-condensed table-striped">
    <thead>
            <tr>
            <th>Complaints Source</th>
            <th>Complaints Numbers</th>
            </tr>
            </thead>
    <tbody class="" page="1">
        <?
        $class = "row1";
        $total=0;
        $i=1;
		
        if($result && ($num=db_num_rows($result))):
		  while ($row = db_fetch_array($result)) {
                ?>
        <tr class="<?php echo $class?> " id="<?php echo $i?>">
        <th>
                <a href="javascript:toggleMessage('<?php echo $i?>');">
                <span style="color:#666; float: left; width:350px;"><?php if($row['source']!="") echo $row['source']; else echo "No Source";?></span></a></th>
                &nbsp;&nbsp;
        <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$row['ticketno'].'</span></b><br />'; ?></span></td> 
        <?php $count_total +=$row['ticketno']; ?>   
			 <div id="msg_<?=$i?>" class="hide">
                <hr>
                <?php
                $sqlt='SELECT ticketID, ticket_id FROM '.TICKET_TABLE.' WHERE location='.$row['location'].'';
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
                  <span style="text-align:left;float:left;"><i><?=$strt?>&nbsp;&nbsp;</i></span>
                  </div>
                  <?php
				$abc[$row['username']]=$numn;
			     ?>
                  
                </tr>
            
            <?
            $class = ($class =='row2') ?'row1':'row2';
            $i++;
            } //end of while.
        else: //not tickets found!! ?>
            <tr class="<?=$class?>"><td><b>Query returned 0 results.</b></td></tr>
        <?
        endif; ?>
         <tr >
        <th><span style="color:#666; float: left; width:350px;">Total</span></th>
                &nbsp;&nbsp;
        <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$count_total.'</span></b><br />'; ?></span></td>
        </tr>
        </tbody>                    
		</table>
        </form>
        </div>
 </table>
<tr>
<td>    
<div id="pie1" style="margin-top:20px; margin-left:210px; width:500px; height:500px;"></div>
</div>
</td>
</tr>
</table>
</td>
</tr>
</table>
</div> 
