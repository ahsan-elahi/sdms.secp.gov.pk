<?php
if(!defined('OSTADMININC') || !$thisstaff || !$thisstaff->isAdmin()) die('Access Denied');

$qstr='';
$sql='SELECT team.*,count(m.staff_id) as members,CONCAT_WS(" ",lead.firstname,lead.lastname) as team_lead '.
     ' FROM '.TEAM_TABLE.' team '.
     ' LEFT JOIN '.TEAM_MEMBER_TABLE.' m ON(m.team_id=team.team_id) '.
     ' LEFT JOIN '.STAFF_TABLE.' lead ON(lead.staff_id=team.lead_id) ';
$sql.=' WHERE 1';
$sortOptions=array('name'=>'team.name','status'=>'team.isenabled','members'=>'members','lead'=>'team_lead','created'=>'team.created');
$orderWays=array('DESC'=>'DESC','ASC'=>'ASC');
$sort=($_REQUEST['sort'] && $sortOptions[strtolower($_REQUEST['sort'])])?strtolower($_REQUEST['sort']):'name';
//Sorting options...
if($sort && $sortOptions[$sort]) {
    $order_column =$sortOptions[$sort];
}
$order_column=$order_column?$order_column:'team.name';

if($_REQUEST['order'] && $orderWays[strtoupper($_REQUEST['order'])]) {
    $order=$orderWays[strtoupper($_REQUEST['order'])];
}
$order=$order?$order:'ASC';

if($order_column && strpos($order_column,',')){
    $order_column=str_replace(','," $order,",$order_column);
}
$x=$sort.'_sort';
$$x=' class="'.strtolower($order).'" ';
$order_by="$order_column $order ";

$qstr.='&order='.($order=='DESC'?'ASC':'DESC');

$query="$sql GROUP BY team.team_id ORDER BY $order_by";
$res=db_query($query);
if($res && ($num=db_num_rows($res)))
    $showing="Showing 1-$num of $num teams";
else
    $showing='No teams found!';

?>
<div class="page-header"><h1>Teams<small>Members</small></h1></div> 
<form action="teams.php" method="POST" name="teams">
 <?php csrf_token(); ?>
 <input type="hidden" name="do" value="mass_process" >
 <input type="hidden" id="action" name="a" value="" >
<div class="row-fluid">
<div class="span3" style="float:right">
    <p style="float:right;">
     <a href="teams.php?a=add" class="Icon newteam">
     <button class="btn" type="button"><i class="icon-plus-sign"></i> Add New Team</button></a>                              
    </p>             
</div>
</div>
<div class="row-fluid">
         <div class="span12">                    
            <div class="head clearfix">
                <div class="isw-grid"></div>
                <h1><?php echo 'Teams'; ?></h1>                               
            </div>
            
            <div class="block-fluid table-sorting clearfix">
                <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable_7">
                    <thead>
                        <tr>
                        <th><input type="checkbox" name="checkall"/></th>
                        <th ><a <?php echo $name_sort; ?> href="teams.php?<?php echo $qstr; ?>&sort=name">Team Name</a></th> 
              
              <th  >
              <a  <?php echo $status_sort; ?> href="teams.php?<?php echo $qstr; ?>&sort=status">Status</a>
              </th> 
              
              <th   >
                <a  <?php echo $members_sort; ?>href="teams.php?<?php echo $qstr; ?>&sort=members">Members</a>
              </th>
              
              <th >
               <a  <?php echo $lead_sort; ?> href="teams.php?<?php echo $qstr; ?>&sort=lead">Team Lead</a>
              </th>
              
              <th nowrap >
               <a  <?php echo $created_sort; ?> href="teams.php?<?php echo $qstr; ?>&sort=created">Created</a>
              </th>
               
              <th nowrap >
              <a  <?php echo $updated_sort; ?> href="teams.php?<?php echo $qstr; ?>&sort=updated">Last Updated</a>
              </th>     
                        </tr>
                    </thead>
                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                     <?php
        $total=0;
        $ids=($errors && is_array($_POST['ids']))?$_POST['ids']:null;
        if($res && db_num_rows($res)):
		$color = 1;
            while ($row = db_fetch_array($res)) {
			if(($color%2) == 0)
			$bg = 'odd';
			else
			$bg = 'even';	
                $sel=false;
                if($ids && in_array($row['team_id'],$ids))
                    $sel=true;
                ?>
               <tr id="<?php echo $row['team_id']; ?>">
                 <td align="center" class="nohover">
                  <input type="checkbox" class="ckb" name="ids[]" value="<?php echo $row['team_id']; ?>" 
                            <?php echo $sel?'checked="checked"':''; ?>> </td>
                <td><a href="teams.php?id=<?php echo $row['team_id']; ?>"><?php echo $row['name']; ?></a> &nbsp;</td>
                <td>&nbsp;<?php echo $row['isenabled']?'Active':'<b>Disabled</b>'; ?></td>
                <td style="text-align:right;padding-right:25px">&nbsp;&nbsp;
                    <?php if($row['members']>0) { ?>
                        <a href="staff.php?tid=<?php echo $row['team_id']; ?>"><?php echo $row['members']; ?></a>
                    <?php }else{ ?> 0
                    <?php } ?>
                    &nbsp;
                </td>
                <td><a href="staff.php?id=<?php echo $row['lead_id']; ?>"><?php echo $row['team_lead']; ?>&nbsp;</a></td>
                <td><?php echo Format::db_date($row['created']); ?>&nbsp;</td>
                <td><?php echo Format::db_datetime($row['updated']); ?>&nbsp;</td>
               </tr>               
            <?php
            } //end of while.
        endif; ?>
                    </tbody>                              
                </table>
            </div>
         </div>                      
    </div>   
    <div class="row-fluid"> 
        <div class="span6" style="width:96.718%;">
         <?php if($res && $num):?>                
            <p style="text-align:center;" id="actions">      
                
                <input class="btn btn-primary" type="submit" name="enable" value="Enable" id="button">
                
                <input class="btn btn-inverse" type="submit" name="disable" value="Disable" id="button" >                    
                
                <input class="btn btn-danger" type="submit" name="delete" value="Delete" id="button">
                      
            </p>   
            <?php endif;?>
        </div> 
    </div>                     
<div class="dr"><span></span></div>   
</form>
       <div style="display:none;" class="dialog" id="confirm-action">
    <h3>Please Confirm</h3>
    <a class="close" href="">&times;</a>
    <hr/>
    <p class="confirm-action" style="display:none;" id="enable-confirm">
        Are you sure want to <b>enable</b> selected teams?
    </p>
    <p class="confirm-action" style="display:none;" id="disable-confirm">
        Are you sure want to <b>disable</b> selected teams?
    </p>
    <p class="confirm-action" style="display:none;" id="delete-confirm">
        <font color="red"><strong>Are you sure you want to DELETE selected teams?</strong></font>
        <br><br>Deleted team CANNOT be recovered.
    </p>
    <div>Please confirm to continue.</div>
    <hr style="margin-top:1em"/>
    <p class="full-width">
        <span class="buttons" style="float:left">
            <input type="button" value="No, Cancel" class="close">
        </span>
        <span class="buttons" style="float:right">
            <input type="button" value="Yes, Do it!" class="confirm">
        </span>
     </p>
    <div class="clear"></div>
</div>       
                 
   </div><!--WorkPlace End-->  
   </div> 


