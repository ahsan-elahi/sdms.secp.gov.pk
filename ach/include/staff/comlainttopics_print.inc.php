<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

echo '<div style="margin-bottom:20px; padding-top:0px;">';
echo '<table width="100%" border="0" cellspacing=1 cellpadding=2>';
echo '<tr>
		<td>
       		<table width="100%" border="0" cellspacing=0 cellpadding=2 class="logs" align="center">
       			<tr>
				<th style="font-size:22px; color:blue;">&nbsp;&nbsp;Aera Complaints Count</th>
				</tr>
				</table>';
				
$qselect='SELECT count(ticket_id) as ticketno ,t.topic_id, d.topic ';
$qfrom='FROM '.TICKET_TABLE.' t, sdms_help_topic d ';
$qwhere=' WHERE t.topic_id=d.topic_id group by t.topic_id ';


$total=db_count("SELECT count(*) $qfrom $qwhere");
$pagelimit=30;

$pageNav=new Pagenate($total,$page,$pagelimit);
$pageNav->setURL('admin.php',$qstr);
$query="$qselect $qfrom $qwhere ORDER BY t.created DESC LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();

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
            <th>Department Name</th>
            <th>Totals<img src="images/Opened.png"/></th>
            <th>Opened<img src="images/Opened.png"/></th>
            <th>Assigned<img src="images/Assigned.png"></th>
            <th>Overdue<img src="images/Overdue.png"></th>
            <th>Closed<img src="images/Closed.png"/></th>
            </tr>
            </thead>
    <tbody class="" page="1">
		<?php
        $class = "row1";
        $total=0;
        $i=1;
        if($result && ($num=db_num_rows($result))):
            while ($row = db_fetch_array($result)) {
                ?>
            <tr>
                <th>
                <a href="javascript:toggleMessage('<?php echo $i?>');">
                <img border="0" align="left" id="img_2" src="images/plus.gif">
                <span style="color:#666; float: left; width:350px;"><?php if($row['topic']!="") echo $row['topic']; else echo "No Topic";?></span></a></th>
               &nbsp;&nbsp;
                <td><span class="Icon <?php echo $icon?>" align="right"><?php echo '<b><span align="right">'.$row['ticketno'].'</span></b><br />'; ?></span></td>
                <?php 
            $query1="SELECT count(status) as open FROM ".TICKET_TABLE."  where status = 'open' and topic_id='".$row['topic_id']."'";	
            $result1 = db_query($query1);
            $row1 = db_fetch_array($result1);?>
            <td><?php echo '<b><span align="right">'.$row1['open'].'</span></b><br />'; ?></td>
            
                <?php 
				
				$query2="SELECT  t.* FROM ".TICKET_TABLE." t WHERE t.staff_id != '0' AND t.topic_id='".$row['topic_id']."'";			
            $result2 = db_query($query2);
            $num2=db_num_rows($result2);
            ?>
            <td><?php echo '<b><span align="right">'.$num2.'</span></b><br />'; ?></td>
            
                <?php 
			$query3="SELECT * FROM ".TICKET_TABLE." where isoverdue != '1' AND topic_id='".$row['topic_id']."'";				
            $result3 = db_query($query3);
            $num3=db_num_rows($result3);?>
                <td><?php echo '<b><span align="right">'.$num3.'</span></b><br />'; ?></td>
                
                <?php 
			$query4="SELECT count(status) as close FROM ".TICKET_TABLE."  where status = 'closed' and topic_id='".$row['topic_id']."'";		
            $result4 = db_query($query4);
            $row4 = db_fetch_array($result4);?>
                <td><?php echo '<b><span align="right">'.$row4['close'].'</span></b><br />'; ?></td>                    
               &nbsp;&nbsp;
                <div id="msg_<?php echo $i?>" class="hide">
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
                              <span style="text-align:left;float:left;"><i><?php echo $strt?>&nbsp;&nbsp;</i></span>
                              </div>
               <?php $abc[$row['username']]=$numn; ?>
            </tr>
            <?php
            $class = ($class =='row2') ?'row1':'row2';
            $i++;
            } 
        else: //not tickets found!! ?>
            <tr class="<?php echo $class?>"><td><b>Query returned 0 results.</b></td></tr>
        <?php
        endif; ?>
        </tbody>                    
		</table>
        </form>
	</div>
<tr>
<td>
</td>
</tr>
</table>
</td>
</tr>
</table>
</div> 
