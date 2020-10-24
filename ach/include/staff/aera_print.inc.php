<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');				
$qselect='SELECT count(ticket_id) as ticketno,t.district,d.District';
$qfrom='FROM '.TICKET_TABLE.' t,sdms_districts d';
$qwhere=' Where t.district=d.District_ID group by t.district';
$total=db_count("SELECT count(*) $qfrom $qwhere");
$pagelimit=30;
$pageNav=new Pagenate($total,$page,$pagelimit);
$pageNav->setURL('admin.php',$qstr);
$query="$qselect $qfrom $qwhere ORDER BY t.created DESC";

$result = db_query($query);
$showing=db_num_rows($result)?$pageNav->showing():"";?>

<div class="page-header"><h1>Complaints  <small> Geographical Count</small></h1></div>
<form action="tickets.php" method="POST" name='tickets' onSubmit="return checkbox_checker(this,1,0);">
<input type="hidden" name="a" value="mass_process" >
<input type="hidden" name="status" value="<?php echo $statusss?>" >
    <div class="row-fluid">
        <div class="span12">                    
            <div class="head clearfix">
                <div class="isw-grid"></div>
                <h1><?php echo 'Complaints Geographical Count'; ?></h1>                               
            </div>
            <div class="block-fluid table-sorting clearfix">
            <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable">							
                <thead>
                <tr>
                    <th>Department Name</th>
                    <!--<th>Totals<img src="images/Opened.png"/></th>-->
                    <th>Opened<img src="images/Opened.png"/></th>
                    <th>Overdue<img src="images/Overdue.png"></th>
                    <th>Closed<img src="images/Closed.png"/></th>     
                </tr>
                </thead>
                <tbody role="alert" aria-live="polite" aria-relevant="all">
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
                <span style="color:#666; float: left; width:350px;"><?php if($row['District']!="") echo $row['District']; else echo "No District";?></span></a></th>
               &nbsp;&nbsp;
                <!--<td>
                <span class="Icon <?php //echo $icon?>" align="right"><?php //echo '<b><span align="right">'.$row['ticketno'].'</span></b><br />'; ?></span></td>-->
                <?php $report .= " [ '" . $row['District'] . "' , ". $row['ticketno']. " ], " ; ?>
                <?php 
			$query1="SELECT count(status) as open,district FROM ".TICKET_TABLE." where status = 'open' and district='".$row['district']."' group by district";			
			$result1 = db_query($query1);
			$row1 = db_fetch_array($result1);?>
			<td><?php echo '<b><span align="right">'.$row1['open'].'</span></b><br />'; ?></td>
			<?php 
			$query3="SELECT * FROM ".TICKET_TABLE." where isoverdue != '1' and district='".$row['district']."'  group by '".$row['district']."'";			
			$result3 = db_query($query3);
			$num3=db_num_rows($result3);?>
			<td><?php echo '<b><span align="right">'.$num3.'</span></b><br />'; ?></td>
            <?php 
          $query4="SELECT count(status) as close,district FROM ".TICKET_TABLE." where status = 'closed' and district='".$row['district']."' group by district";			
          $result4 = db_query($query4);
          $row4 = db_fetch_array($result4);?>
                <td>
				<?php echo '<b><span align="right">'.$row4['close'].'</span></b><br />'; ?></td>                     
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
            } //end of while.
        else: //not tickets found!! ?>
            <tr class="<?php echo $class?>"><td><b>Query returned 0 results.</b></td></tr>
        <?php endif; ?>              
                </tbody>                              
            </table>
            </div>
        </div>                      
    </div>
</form>                        
		<div class="dr"><span></span></div>   
   </div><!--WorkPlace End-->  
   </div> 

