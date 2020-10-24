<style>
#tSortable_paginate{
	display:none;
	}
#tSortable_filter{
display:none;
}	
#tSortable_length{
	display:none;}
#tSortable_9_paginate{
	display:none;
	}
#tSortable_9_filter{
display:none;
}	
#tSortable_9_length{
	display:none;}	
</style>
<?php error_reporting(0); ?>
<script>

function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("report_districtwise_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
window.location.reload();
}
</script>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
 $_GET['limit']=5;
  if($_GET['limit'])
    $qstr.='&limit='.urlencode($_GET['limit']);
//pagenate
$pagelimit=($_GET['limit'] && is_numeric($_GET['limit']))?$_GET['limit']:PAGE_LIMIT;
if($_REQUEST['staffid']) {
$staffid = $_REQUEST['staffid'];
}
if(!isset($_GET['dashboard'])){
?>
<div class="row-fluid">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1>Search</h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
          <form action="report_districtwise.php" method="GET">
          <table width="100%" cellpadding="0" cellspacing="0" class="table" style="line-height:35px;">
          <tr>
          <td>Districts</td>
            <td><select name="district">
            <option value="" selected="selected">All District</option>
            <?php 
            $districts=$all_districts->getDistricts();
            foreach($districts as $key=>$row){
             ?>
            <option value="<?php echo $key; ?>" <?php if ($_GET['district'] == $key) { ?> selected="selected" <?php } ?>><?php echo $row; ?></option>
            <?php } ?>
            </select></td>
            <td style="padding-left:3px;padding-right:2px;padding-top:10px;">Compare From Date</td>
            <td><input style="height:0px; top:-1000px; position:absolute" type="text" value=""><input type="text" name="CompFDate" placeholder="CompFDate" id="Datepicker" value="<?php if (isset($_GET['CompFDate'])){ echo $_GET['CompFDate']; }else echo date("m/d/Y"); ?>" /></td>
            <td>Gender</td>
            <td><select name="gender">
            <option value="" selected="selected">All Gender</option>
            <option value="male" <?php if ($_GET['gender'] == 'male') { ?> selected="selected" <?php } ?>>Male</option>
            <option value="female" <?php if ($_GET['gender'] == 'female') { ?> selected="selected" <?php } ?>>Female</option>
            </select></td>
          </tr>
          <tr>
          <td>Category</td>
            <td><select name="category">
                    <option value="" selected="selected">All Categories</option>
                     <?php
					 $sql_cat="Select * from sdms_help_topic where topic_pid='0'";
					 $res_cat=db_query($sql_cat);
				
                    //$category=$all_categories->getsubTopics();
                    while($row_cat= db_fetch_array($res_cat)){
                     ?>
                      <option value="<?php echo $row_cat['topic_id']; ?>" <?php if ($_GET['category'] == $row_cat['topic_id']) { ?> selected="selected" <?php } ?>><?php echo $row_cat['topic']; ?></option>
                    <?php } ?>
                    </select></td>
          <td>Compare To Date</td>
            <td><input type="text" value="<?php if (isset($_GET['CompTDate'])){ echo $_GET['CompTDate']; }else echo date("m/d/Y"); ?>" name="CompTDate" placeholder="CompTDate" id="Datepicker1"/></td>
            <td>Status</td>
            <td><select name="status">
            <option value="" selected="selected">All Status</option>
            
              <option value="open" <?php if ($_GET['status'] == 'open') { ?> selected="selected" <?php } ?> >open</option>
            <option value="closed" <?php if ($_GET['status'] == 'closed') { ?> selected="selected" <?php } ?> >closed</option>
            </select></td>
          </tr>
          <tr>
          	<td colspan="6" style="text-align:right">
            <input type="submit" value="Search" class="btn" name="Search" />
            &nbsp;&nbsp;
            <input type="button" value="Print" onclick="openWin();" class="btn"/></td>
          </tr>
		  </table> 
		  </form> 
        </div>
 </div>                      
</div>
<?php } ?>
<div class="page-header"><h1>District Wise<small> Report</small></h1></div>
<div class="row-fluid">
<div class="span3" style="float:right;">        
</div>
</div>
<div class="row-fluid" style="min-height:550px;">
   <div class="span12">                    
        <div class="head clearfix">
            <div class="isw-grid"></div>
            <h1><?php echo 'District Wise Report Listing'; ?></h1>                               
        </div>
        <div class="block-fluid table-sorting clearfix">
          <table width="100%" cellpadding="0" cellspacing="0" class="table">
           <thead>
              <tr>
                <th>Complaint No</th>
                <th>Application Name</th>
                <th>Gender</th>
                <th>Complaint Date</th>
                <th>Status</th>
                <th>Category</th>
                <th>Complaint Club</th>
                <th>Last Proceding Date</th>
                <th>Last Proceding </th>
              </tr>
  		   </thead>
			<?php 
			$count=0;
            $i=0;
       		$sql_ticket="select * from sdms_ticket order by district ASC"; 
            $i=0;
            if(isset($_GET['Search'])){
			$sql_ticket="select * from sdms_ticket where ticket_id!='' ";
			if($_GET['district']!=''){
			$sql_ticket.=" AND district='".$_GET['district']."' ";
			}
            if($_GET['CompFDate']!='' && $_GET['CompTDate']!='')
            {
            $sql_ticket.=" AND date(created) >= '".date('Y-m-d',strtotime($_GET['CompFDate']))."' 
			AND date(created) <= '".date('Y-m-d',strtotime($_GET['CompTDate']))."'";	
            }
			if($_GET['gender']!=''){				
            $sql_ticket.=" AND gender='".$_GET['gender']."'";
            }
			if($_GET['category']!=''){
            $sql_ticket.=" AND topic_id IN (select topic_id from sdms_help_topic where topic_pid='".$_GET['category']."')";
            }
			if($_GET['status']!=''){
            $sql_ticket.=" AND status='".$_GET['status']."'";
            }
            $sql_ticket.=" Order By district ASC ";
            
			}
//echo $sql_ticket;exit;
$_SESSION['print']=$sql_ticket;
$page_result=mysql_query($sql_ticket);
$total=mysql_num_rows($page_result);
$page=($_GET['p'] && is_numeric($_GET['p']))?$_GET['p']:1;
$pageNav=new Pagenate($total,$page,$pagelimit=25);
$pageNav->setURL('report_districtwise.php',$qstr.'&sort='.urlencode($_REQUEST['sort']).'&order='.urlencode($_REQUEST3['order']).'&'.$_SERVER['QUERY_STRING']);
$_SESSION['limit']=" LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
$sql_ticket .=" LIMIT ".$pageNav->getStart().",".$pageNav->getLimit();
$pre_dis="";
            $res_ticket=db_query($sql_ticket);
			$num=db_num_rows($res_ticket);
			$showing=db_num_rows($res_ticket)?$pageNav->showing():"";
            while ($row_ticket = db_fetch_array($res_ticket)) { 
			if($pre_dis!=$row_ticket['district']){
				$sql_district_name="select * from sdms_districts where District_ID='".$row_ticket['district']."'";
				$res_district_name=db_query($sql_district_name);
				$row_district_name= db_fetch_array($res_district_name);
              ?>
              <tr>
              <td colspan="10"><b><?php echo $row_district_name['District']; ?></b></td>
              </tr>
              <?php 
			  //$i=1;
			  $pre_dis=$row_ticket['district'];
			  }?>
              <tr>
                <td><?php echo $row_ticket['ticketID'];?></td>
                <td><?php echo $row_ticket['name_title']." ".$row_ticket['name'];;?></td>
                <td><?php if($row_ticket['gender']=='')
                echo "Other";
                else
                echo $row_ticket['gender'];?></td>
                <td><?php echo  date("d-M-Y",strtotime($row_ticket['created']));?></td>
                <td><?php echo $row_ticket['status'];?></td>
                <?php 
                $sql_topic="select * from sdms_help_topic where topic_id = '".$row_ticket['topic_id']."'";
                $res_topic=db_query($sql_topic);
                $row_topic= db_fetch_array($res_topic);?>
                <td><?php echo $row_topic['topic'];?></td>
                <?php $sql_nic="select count(ticketID) as nic_count from sdms_ticket where nic='".$row_ticket['nic']."' AND nic!='00000-0000000-0'" ;
				$res_nic=db_query($sql_nic);
                $row_nic= db_fetch_array($res_nic);
				  ?>
                <td><?php echo $row_nic['nic_count'];$count +=$row_nic['nic_count']; ?></td>
               <td><?php echo  date("d-M-Y",strtotime($row_ticket['updated']));?></td>
                <?php 
                $sql_status="select * from sdms_status where status_id = '".$row_ticket['complaint_status']."'";
                $res_status=db_query($sql_status);
                $row_status= db_fetch_array($res_status);?>
                <td><?php echo $row_status['status_title'];?></td>
              </tr>
			<?php    //} $i=0;
			 }?>
             <?php if($num>0){  ?>      
        <tfoot>
        <tr>
        <td colspan="4"><?php echo '<div align="left" style="width:40%;float:left;">'.$showing .'&nbsp;'.'&nbsp;'.'&nbsp;'.'</div>';?></td>
        <td colspan="9" align="right"><?php echo '<div class="dataTables_paginate paging_full_numbers">Page:'.$pageNav->getPageLinks().'</div>'; ?></td>
        </tr>
        </tfoot>                            
<?php }?>
</table>
        </div>
 </div>                      
</div>                        
<div class="dr"><span></span></div>   
</div><!--WorkPlace End-->  
</div>   


