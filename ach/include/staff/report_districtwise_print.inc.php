<link href="css/stylesheets.css" rel="stylesheet" type="text/css" /> 
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
       		
$sql_ticket=$_SESSION['print'];
$_SESSION['print']="";
$pre_dis="";
            $res_ticket=db_query($sql_ticket);
			$num=db_num_rows($res_ticket);
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
			<?php 
			 }?>
</table>
        </div>
 </div>                      
</div>                        
<div class="dr"><span></span></div>   
</div><!--WorkPlace End-->  
</div>     
<script>
window.print();
window.close();
</script>


