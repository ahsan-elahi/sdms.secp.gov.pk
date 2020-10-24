<script>
function openWin()
{
//window.open(URL,name,specs,replace)
myWindow=window.open("comlaintstatus_new_print.php","Print Report","toolbar=yes,width=800px,height=14031px");
myWindow.print() ;
//myWindow.close();
}
</script>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');
?>
<?php 
if($thisstaff->isFocalPerson() == '1' || $thisstaff->getGroupId()=='8')
{
	$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	$dept_id = $thisstaff->getDeptId();
	$_POST['dept_id'] = $thisstaff->getDeptId();
}
elseif(!$thisstaff->isAdmin() &&  $thisstaff->onChairman() == '1' )
{
	$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	$dept_id = $thisstaff->getDeptId();
	$_POST['dept_id'] = $thisstaff->getDeptId();
	//$dept_add .= ' AND dept_id = '.$_POST['dept_id'].'';
	//$dept_id = $_POST['dept_id'];
}
elseif($thisstaff->isAdmin() && $_POST['dept_id']!='')
{
$dept_add .= ' AND dept_id = '.$_POST['dept_id'].'';
$dept_id = $_POST['dept_id'];
}
if($_POST['dept_id']=='')
{
	$dept_add .= ' AND dept_id = 1';
	$dept_id = 1;
	$_POST['dept_id'] = 1;
}
if($_POST['from_date']!='' && $_POST['to_date']!='')
{
$from_to_date = ' AND DATE(created) >= "'.date('Y-m-d',strtotime($_POST['from_date'])).'" AND DATE(created) <= "'.date('Y-m-d',strtotime($_POST['to_date'])).'"  ';
$date_range = '&startDate='.date('m/d/Y',strtotime($_REQUEST['from_date'])).'&endDate='.date('m/d/Y',strtotime($_REQUEST['to_date']));

}else{
$from_to_date ='';
$date_range = '';
}

function get_complaints_by_topic_id($topic_id){
	global $dept_add;
	global $from_to_date;

	$sql_topic_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND topic_id='".$topic_id."' ".$dept_add." ".$from_to_date."";
$res_topic_comp = mysql_query($sql_topic_comp);
$num_topic_comp = mysql_num_rows($res_topic_comp);
return $num_topic_comp;
}
function get_complaints_by_deparment(){
	global $dept_add;
	global $from_to_date;
	$sql_topic_deprt = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' ".$dept_add."  ".$from_to_date."";
$res_topic_deprt = mysql_query($sql_topic_deprt);
$num_topic_deprt = mysql_num_rows($res_topic_deprt);
return $num_topic_deprt;
}

?>
<div class="page-header">
  <h1>Nature of complaints <small>Summary</small></h1>
</div>

<div class="row-fluid">
<div class="span12">                    
<div class="head clearfix">
<div class="isw-grid"></div>
<h1>Search</h1>                          
</div>
<div class="block-fluid table-sorting clearfix">
<form action="" method="post" id="save" enctype="multipart/form-data">
 <?php csrf_token(); ?>
<table cellpadding="0" cellspacing="0" width="100%" class="table"  >
<tr>
<?php if($thisstaff->isAdmin() ){ ?>
<th width="20%" style="padding-top:12px;">By Department</th>
<td  >
<select name="dept_id" >
<option value="">--Select Department--</option>
<?php 
$sql_get_dept='SELECT * from  sdms_department WHERE 1 ';
$res_get_dept = mysql_query($sql_get_dept);
while($row_dept = mysql_fetch_array($res_get_dept)){
?>
<option value="<?php echo $row_dept['dept_id'] ;?>" <?php if($row_dept['dept_id']==$_POST['dept_id']){ ?> selected <?php }?>><?php echo $row_dept['dept_name'] ;?></option>
<?php } ?>
</select>
</td>
<?php }?>
<th width="20%" style="padding-top:12px;">From Date</th>
<td>
<input type="text" name="from_date" id="Datepicker" value="<?php echo $_POST['from_date']; ?>" >
</td>
<th width="20%" style="padding-top:12px;">To Date</th>
<td>
<input type="text" name="to_date" id="Datepicker1" value="<?php echo $_POST['to_date']; ?>" >
</td>

</tr>        
<tr>
<td style="background-color: #FFFFFF;text-align: right;" colspan="6" align="right">
<input class="btn" type="submit" name="search" value="Search">
</td>
</tr>
</table>
</form>


</div>
</div>     
</div>

<?php if($_POST['dept_id']=='1'){
	//Service Desk
?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Service Desk Category Summary</h1>
    </div>
    <div class="block-fluid ">
      
        
        
       
        <table cellpadding="0" cellspacing="0" width="100%" class="table">

  <col width="221">
  <col width="304">
  <col width="22">
  <col width="73">
  <thead>
  <tr>
    <td width="221">Primary    Category </td>
    <td width="304">Sub Category </td>
    <td width="22"></td>
    <td width="73">Total  <br>
      Complaints</td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td>Service Desk    Staff</td>
    <td>Behavior </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1425<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1425); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Inaccurate Information</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1426<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1426); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Other</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1427<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1427); ?></a></td>
  </tr>
  <tr>
    <td>Helpline</td>
    <td>Non-Responding </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1428<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1428); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Call waiting duration </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1429<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1429); ?></a></td>
  </tr>
  <tr>
    <td width="221">&nbsp;</td>
    <td>Other Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1430<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1430); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1424<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1424); ?></a></td>
  </tr>
  <tr>
  
    <td colspan="3">Not Categorized</td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=43<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(43); ?></a></td>
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></a></td>
  </tr>
</tbody>
</table>

      
    </div>
  </div>
</div>
<?php }
elseif($_POST['dept_id']=='2'){
	//Capital Markets
?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Capital Markets Category Summary</h1>
    </div>
    <div class="block-fluid ">
      
        
        
       
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
  <col width="80">
  <col width="169">
  <col width="449">
  <col width="436">
  <col width="22">
  <col width="74">
  <thead>
  <tr>
    <th width="80">Department </th>
    <th width="169">Wing </th>
    <th width="449">Primary    Nature </th>
    <th width="436">Sub Nature</th>
    <th width="22"></th>
    <th width="74">Total  <br>
      Complaints</th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td rowspan="29">PRPD</td>
    <td rowspan="13">Broker Registration Wing</td>
    <td rowspan="11">Active Broker </td>
    <td>Nontransfer of shares/nonpayment of    funds</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=108<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(108); ?></a></td>
  </tr>
  <tr>
    <td>Unauthorized trading </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=109<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(109); ?></a></td>
  </tr>
  <tr>
    <td>Fraudulant entries, incorrect commission/charges</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=110<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(110); ?></a></td>
  </tr>
  <tr>
    <td>Nonprovision of statements/information</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=111<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(111); ?></a></td>
  </tr>
  <tr>
    <td>Trading the wrong scripts-not following the instructions of the client</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=112<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(112); ?></a></td>
  </tr>
  <tr>
    <td>Dealing with employees and clients (under the name of member/broker)</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=113<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(113); ?></a></td>
  </tr>
  <tr>
    <td>Holding, other member's account for dues of family members </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=114<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(114); ?></a></td>
  </tr>
  <tr>
    <td>Dispute with another investor</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=115<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(115); ?></a></td>
  </tr>
  <tr>
    <td>Private financing arrangements</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=116<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(116); ?></a></td>
  </tr>
  <tr>
    <td>Account Closure</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=117<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(117); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=118<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(118); ?></a></td>
  </tr>
  <tr>
    <td>Non-Active Broker</td>
    <td>Settlement of Claim</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=119<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(119); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=92<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(92); ?></a></td>
  </tr>
  <tr>
    <td rowspan="11">Capital    Issue Wing</td>
    <td rowspan="7">Share Registrar/Transfer    Agent</td>
    <td>Nonreceipt of    dividend/bonus shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=120<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(120); ?></a></td>
  </tr>
  <tr>
    <td>Non-verification of transfer deed</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=121<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(121); ?></a></td>
  </tr>
  <tr>
    <td>Non-transfer of shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=122<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(122); ?></a></td>
  </tr>
  <tr>
    <td>Excess/wrong deduction of witholding tax on dividend amount</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=123<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(123); ?></a></td>
  </tr>
  <tr>
    <td>No issuance of duplicate shares certificates</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=124<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(124); ?></a></td>
  </tr>
  <tr>
    <td>Noninduction of shares in CDS</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=125<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(125); ?></a></td>
  </tr>
  <tr>
    <td>Illegal transfer of shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=126<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(126); ?></a></td>
  </tr>
  <tr>
    <td>Debt security Trustee</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=94<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(94); ?></a></td>
  </tr>
  <tr>
    <td>Book Runner</td>
    <td>IPO related</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=127<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(127); ?></a></td>
  </tr>
  <tr>
    <td>Underwriters </td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=96<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(96); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=97<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(97); ?></a></td>
  </tr>
  <tr>
    <td rowspan="2">Take    Over Wing</td>
    <td>Non-disclosure of    substantial share holding/Control</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=98<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(98); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=99<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(99); ?></a></td>
  </tr>
  <tr>
    <td rowspan="3">Beneficial    Ownership Wing</td>
    <td>Technical Issues on    filing of returns </td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=100<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(100); ?></a></td>
  </tr>
  <tr>
    <td>Non-filing of disclosure of shareholding by directors of listed    companies </td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=101<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(101); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=102<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(102); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">SSED</td>
    <td rowspan="5">&nbsp;</td>
    <td>Market Rumours</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=721<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(721); ?></a></td>
  </tr>
  <tr>
    <td width="449">Abnormal Price movement as a <br>
      result of trading activity <br>
      in the listed scips </td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=722<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(722); ?></a></td>
  </tr>
  <tr>
    <td>Market abuses/manipulation</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=723<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(723); ?></a></td>
  </tr>
  <tr>
    <td>Insider Trading/Manipulation</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=724<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(724); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=727<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(727); ?></a></td>
  </tr>
  <tr>
    <td rowspan="8">PRDD</td>
    <td rowspan="8">&nbsp;</td>
    <td rowspan="3">Stock Exchange</td>
    <td>Pertainig to    suspended scrips</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=142<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(142); ?></a></td>
  </tr>
  <tr>
    <td>Listing Fees</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=143<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(143); ?></a></td>
  </tr>
  <tr>
    <td>Service Related Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=144<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(144); ?></a></td>
  </tr>
  <tr>
    <td rowspan="2">CDC</td>
    <td>Regarding CDS Service</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=145<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(145); ?></a></td>
  </tr>
  <tr>
    <td>Suspended scrips (Investor Account Services)</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=146<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(146); ?></a></td>
  </tr>
  <tr>
    <td rowspan="2">NCCPL</td>
    <td>CGT related    complaints </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=147<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(147); ?></a></td>
  </tr>
  <tr>
    <td>Service Related Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=150<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(150); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=141<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(141); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">CMD</td>
    <td rowspan="5">&nbsp;</td>
    <td>Unauthorized trades    executed by broker without permission</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=129<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(129); ?></a></td>
  </tr>
  <tr>
    <td>Delay in refund of money</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=131<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(131); ?></a></td>
  </tr>
  <tr>
    <td>Non-receipt of account trading statements/trade confirmations</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=132<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(132); ?></a></td>
  </tr>
  <tr>
    <td>Problem in execution of commodity/currency contract</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=133<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(133); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=134<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(134); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>e-Services</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=135<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(135); ?></a></td>
  </tr>
  <tr>
   <td colspan="5">Not Categorized</td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=43<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(43); ?></a></td>
  </tr>
  <tr>
    <td colspan="5"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></a></td>
  </tr>
</tbody>
</table>
        <?php //echo get_complaints_by_topic_id(1430); ?>

    </div>
  </div>
</div>
<?php }
elseif($_POST['dept_id']=='3'){
	//Insurance
?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Insurance Category Summary</h1>
    </div>
    <div class="block-fluid ">
      
        
        
        <table e cellpadding="0" cellspacing="0" width="100%" class="table">
  <col width="98">
  <col width="139">
  <col width="223">
  <col width="273">
  <col width="26">
  <col width="94">
  <thead>
  <tr>
    <th width="98">Department</th>
    <th width="139">Primary Nature</th>
    <th width="223">Sub Nature</th>
    <th width="273">Sub Sub Nature</th>
    <th width="26"></th>
    <th width="94">Total  <br>
      Complaints</th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td rowspan="21">PRDD</td>
    <td rowspan="15" width="139">Licensing, <br>
      Approvals &amp; <br>
      Complaints Resolution</td>
    <td rowspan="6">Policy Holder</td>
    <td>Refund of Premium/Policy cancellation</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=68<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(68); ?></a></td>
  </tr>
  <tr>
    <td>Insurance Claim on Maturity</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=69<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(69); ?></a></td>
  </tr>
  <tr>
    <td>Nonpayment of Death Claim</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=70<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(70); ?></a></td>
  </tr>
  <tr>
    <td>Nonpayment of nonlife/general claims</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=71<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(71); ?></a></td>
  </tr>
  <tr>
    <td>Bancassurance</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=72<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(72); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=73<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(73); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>TOTAL</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1430<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1430); ?></a></td>
  </tr>
  <tr>
    <td rowspan="2">Shareholder</td>
    <td>Non-payment of    Dividend</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=74<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(74); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=75<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(75); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>TOTAL</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1430<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1430); ?></a></td>
  </tr>
  <tr>
    <td rowspan="2">Broker/Agents </td>
    <td>Brokerage Commission</td>
    <td></td>
   <!-- 
   ###################################################################################################################
   Missing id
   ####################################################################################################################
   -->
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(76); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=77<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(77); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>TOTAL</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1430<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1430); ?></a></td>
  </tr>
  <tr>
    <td>Adjudication</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=60<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(60); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=61<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(61); ?></a></td>
  </tr>
  <tr>
    <td>Policy Wing</td>
    <td width="223">Product Related    Complaint/<br>
      Policy Documents/<br>
      Illustrations/<br>
      Proposals</td>
    <td>&nbsp;</td>
    <td></td>
     <!-- 
   ###################################################################################################################
   Missing id
   ####################################################################################################################
   -->
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(62); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5" width="139">Off-site Surveillance &amp; <br>
      Monitoring</td>
    <td width="223">Observations in <br>
      Annual Audited Accounts/<br>
      Half yearly Accounts/<br>
      Quarterly Accounts</td>
    <td>&nbsp;</td>
    <td></td>
   <!-- ###################################################################################################################
   Missing id
   #################################################################################################################### -->
  
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(63); ?></a></td>
  </tr>
  <tr>
    <td width="223">Mismanagement by <br>
      Employees/BoD</td>
    <td>&nbsp;</td>
    <td></td>
    <!-- ###################################################################################################################
   Missing id
   #################################################################################################################### -->
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=108<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(64); ?></a></td>
  </tr>
  <tr>
    <td>Insurance    Surveyor</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=65<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(65); ?></a></td>
  </tr>
  <tr>
    <td>Authorized    Surveying Officer</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=66<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(66); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=67<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(67); ?></a></td>
  </tr>
  <tr>
    <td rowspan="3">S&amp;ED</td>
    <td rowspan="3">Onsite Inspection</td>
    <td width="223">Observations    requiring <br>
      Inspection/Enquiry</td>
    <td>&nbsp;</td>
    <td></td>
    <!-- ###################################################################################################################
   Missing id
   #################################################################################################################### -->
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(81); ?></a></td>
  </tr>
  <tr>
    <td width="223">Observations requiring <br>
      Investigations</td>
    <td>&nbsp;</td>
    <td></td>
    <!-- ###################################################################################################################
   Missing id
   #################################################################################################################### -->
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(82); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=83<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(83); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>e-Services</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=80<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(80); ?></a></td>
  </tr>
  <tr>
    <td colspan="5">Not Categorized</td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=43<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(43); ?></a></td>
  </tr>
  <tr>
   <td colspan="5"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></a></td>
  </tr>
  </tbody>
</table>

      
    </div>
  </div>
</div>
<?php }
elseif($_POST['dept_id']=='4'){
	//AMC/Mutual Funds/Modarabah/Leasing/Investment Banking/REIT
?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>MC/Mutual Funds/Modarabah/Leasing/Investment Banking/REIT Category Summary</h1>
    </div>
    <div class="block-fluid ">
      
        
         <table cellpadding="0" cellspacing="0" width="100%" class="table">
        
  <col width="88">
  <col width="297">
  <col width="332">
  <col width="452">
  <col width="31">
  <col width="227">
 <thead>
  <tr>
    <th width="88">Territory </th>
    <th width="297">Wing</th>
    <th width="332">Nature</th>
    <th width="452">Sub Category</th>
    <th width="31"></th>
    <th width="227">Total  <br>
      Complaints</th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td rowspan="41">North</td>
    <td rowspan="18">AMC Wing</td>
    <td rowspan="4">Asset Management Companies    (AMC)</td>
    <td>Accounts of AMC</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=393<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(393); ?></a></td>
  </tr>
  <tr>
    <td>Holding of Mandatory Meetings</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=394<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(394); ?></a></td>
  </tr>
  <tr>
    <td>Issues Pertaining to License</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=395<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(395); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=396<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(396); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">Mutual    Fund</td>
    <td>Dividend not received</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=397<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(397); ?></a></td>
  </tr>
  <tr>
    <td width="452">Issue and redemption of <br>
      units/shares</td>
    <td></td>
     <!-- ###################################################################################################################
   Missing id
   #################################################################################################################### -->
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td>Issues of sales load</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=399<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(399); ?></a></td>
  </tr>
  <tr>
    <td>Accounts of funds</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=400<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(400); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=401<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(401); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">Pension    Funds</td>
    <td>Dividend not received</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=402<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(402); ?></a></td>
  </tr>
  <tr>
    <td width="452">Issue and redemption of <br>
      units/shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=403<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(403); ?></a></td>
  </tr>
  <tr>
    <td>Charging of sales load</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=404<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(404); ?></a></td>
  </tr>
  <tr>
    <td>Accounts of funds</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=405<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(405); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=406<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(406); ?></a></td>
  </tr>
  <tr>
    <td rowspan="4">Investment    Advisor</td>
    <td width="452">Dispute    Regarding <br>
      Portfolio Agreement</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=407<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(407); ?></a></td>
  </tr>
  <tr>
    <td>Issues Related to License </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=408<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(408); ?></a></td>
  </tr>
  <tr>
    <td width="452">Issues regarding compliance with <br>
      regulatory framework</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=409<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(409); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=410<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(410); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">Non    Banking Finance Companies (NBFC)</td>
    <td rowspan="5" width="332">Leasing    Companies/<br>
      Investment Banks/<br>
      Microfinance Companies/<br>
      Housing Finance Companies and <br>
      Discount Houses</td>
    <td>Dividend not received</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=411<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(411); ?></a></td>
  </tr>
  <tr>
    <td>Issues of share transfer</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=412<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(412); ?></a></td>
  </tr>
  <tr>
    <td>Claim of depositor</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=413<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(413); ?></a></td>
  </tr>
  <tr>
    <td width="452">Status of companies in <br>
      liquidation/winding up</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=414<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(414); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=415<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(415); ?></a></td>
  </tr>
  <tr>
    <td rowspan="7">Real    Estate Investment Trust (REIT)</td>
    <td rowspan="4">REIT Management Company </td>
    <td>Accounts of RMC</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=416<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(416); ?></a></td>
  </tr>
  <tr>
    <td>Holding of Mandatory Meetings</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=417<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(417); ?></a></td>
  </tr>
  <tr>
    <td>Issues Pertaining to License</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=418<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(418); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=419<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(419); ?></a></td>
  </tr>
  <tr>
    <td rowspan="3">REIT    Funds</td>
    <td>Units not received</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=420<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(420); ?></a></td>
  </tr>
  <tr>
    <td>Dividend not received</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=421<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(421); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=422<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(422); ?></a></td>
  </tr>
  <tr>
    <td rowspan="2">Private    Equity and Venture Capital Fund</td>
    <td width="332">Private    Fund <br>
      Managament Company</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=374<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(374); ?></a></td>

  </tr>
  <tr>
    <td width="332">Private Equity Fund/<br>
      Venture Capital Fund/<br>
      Alternate Fund</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=375<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(375); ?></a></td>
  </tr>
  <tr>
    <td rowspan="7">Modaraba</td>
    <td rowspan="2" width="332">Modaraba Management <br>
      Companies</td>
    <td>Holding of Mandatory    Meetings</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=423<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(423); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=424<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(424); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">Modaraba</td>
    <td>Dividend not received</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=425<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(425); ?></a></td>
  </tr>
  <tr>
    <td width="452">Issues of <br>
      Modaraba Certificates transfer</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=426<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(426); ?></a></td>
  </tr>
  <tr>
    <td width="452">Status of companies in <br>
      liquidation/winding up</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=427<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(427); ?></a></td>
  </tr>
  <tr>
    <td width="452">Mismanagement by the <br>
      BOD/Management</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=428<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(428); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=429<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(429); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=379<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(379); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=380<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(380); ?></a></td>
  </tr>
  <tr>
    <td rowspan="47">South</td>
    <td rowspan="22">Offsite Wing I</td>
    <td rowspan="6" width="332">Asset Management Companies (AMC)/<br>
      Mutual Funds</td>
    <td>Dividend not declared/calculation of    dividend </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=430<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(430); ?></a></td>
  </tr>
  <tr>
    <td>Observations pertaining to clients account statements </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=431<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(431); ?></a></td>
  </tr>
  <tr>
    <td>Tax deductions</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=432<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(432); ?></a></td>
  </tr>
  <tr>
    <td>Observations in the periodic accounts</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=433<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(433); ?></a></td>
  </tr>
  <tr>
    <td>Mismanagement by employees/BOD</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=434<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(434); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=435<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(435); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">Investment Advisors</td>
    <td>Observations    pertaining to clients account statements </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=436<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(436); ?></a></td>
  </tr>
  <tr>
    <td>Tax deductions</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=437<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(437); ?></a></td>
  </tr>
  <tr>
    <td>Observations in the periodic accounts</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=438<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(438); ?></a></td>
  </tr>
  <tr>
    <td>Mismanagement by employees/BOD</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=439<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(439); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=440<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(440); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">Pension Funds</td>
    <td>Observations    pertaining to clients account statements </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=442<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(442); ?></a></td>
  </tr>
  <tr>
    <td>Tax deductions</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=443<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(443); ?></a></td>
  </tr>
  <tr>
    <td>Observations in the periodic accounts</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=444<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(444); ?></a></td>
  </tr>
  <tr>
    <td>Mismanagement by employees/BOD</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=445<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(445); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=446<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(446); ?></a></td>
  </tr>
  <tr>
    <td rowspan="6">REITs</td>
    <td>Dividend not    declared/calculation of dividend </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=447<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(447); ?></a></td>
  </tr>
  <tr>
    <td>Observations pertaining to clients account statements </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=448<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(448); ?></a></td>
  </tr>
  <tr>
    <td>Tax deductions</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=449<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(449); ?></a></td>
  </tr>
  <tr>
    <td>Observations in the periodic accounts</td>

    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=450<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(450); ?></a></td>
  </tr>
  <tr>
    <td>Mismanagement by employees/BOD</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=451<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(451); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=452<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(452); ?></a></td>
  </tr>
  <tr>
    <td rowspan="17">Offsite Wing II</td>
    <td rowspan="6">Leasing Companies </td>
    <td>Dividend not    declared/calculation of dividend/dividend not received </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=453<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(453); ?></a></td>
  </tr>
  <tr>
    <td>Tax deductions</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=454<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(454); ?></a></td>
  </tr>
  <tr>
    <td>Observations in the periodic accounts</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=455<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(455); ?></a></td>
  </tr>
  <tr>
    <td>Mismanagement by employees/BOD</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=456<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(456); ?></a></td>
  </tr>
  <tr>
    <td>Non issuance of NOCs after payment of lease/loans</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=457<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(457); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=458<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(458); ?></a></td>
  </tr>
  <tr>
    <td rowspan="6">Investment Banks/MicroFinance NBFCs</td>
    <td>Dividend not    declared/calculation of dividend/dividend not received </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=459<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(459); ?></a></td>
  </tr>
  <tr>
    <td>Tax deductions</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=460<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(460); ?></a></td>
  </tr>
  <tr>
    <td>Observations in the periodic accounts</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=461<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(461); ?></a></td>
  </tr>
  <tr>
    <td>Mismanagement by employees/BOD</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=462<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(462); ?></a></td>
  </tr>
  <tr>
    <td>Non issuance of NOCs after payment of lease/loans</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=463<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(463); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=464<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(464); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5" width="332">Modaraba Management    Companies/<br>
      Modarabas</td>
    <td>Dividend not    declared/calculation of dividend/dividend not received </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=744<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(744); ?></a></td>
  </tr>
  <tr>
    <td>Observations in the periodic accounts</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=466<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(466); ?></a></td>
  </tr>
  <tr>
    <td>Mismanagement by employees/BOD</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=467<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(467); ?></a></td>
  </tr>
  <tr>
    <td>Non issuance of NOCs after payment of lease/loans</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=468<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(468); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=469<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(469); ?></a></td>
  </tr>
  <tr>
    <td rowspan="6">Onsite    Inspection</td>
    <td rowspan="3" width="332">Asset    Management Companies/Mutual Funds/<br>
      Investment Advisors/Pension Funds/REITs</td>
    <td>Observations in the    periodic accounts requiring inspection/inquiry</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=471<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(471); ?></a></td>
  </tr>
  <tr>
    <td>Observations requiring investigations</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=472<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(472); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=473<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(473); ?></a></td>
  </tr>
  <tr>
    <td rowspan="3" width="332">Leasing Invetsment Bank/<br>
      MicroFinance NBFCs and Modarabs</td>
    <td>Observations in the    periodic accounts requiring inspection/inquiry</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=474<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(474); ?></a></td>
  </tr>
  <tr>
    <td>Observations requiring investigations</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=475<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(475); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=476<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(476); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="332">eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=391<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(391); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=392<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(392); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>Enquiries</td>
    <td></td>
    <td width="452"></td>
    <td></td>
    <!--
    
   ################ Missing ID ##################### 
    -->
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>Nature</td>
    <td>Sub Nature</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td rowspan="12">AMC Wing</td>
    <td>Opening of investor account</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>status of AMC/Investment advisor</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td width="332">Status of companies under    liquidation/winding up</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>Licensing Fee</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td width="332">clarification on Regulatory    framework</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>Licensing and incorporation procedure of AMC</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td width="332">information about category of    fund/structure of fund</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>website of AMC</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>Information about financial statements</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>Dividend not received</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>Tax benefits</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>Others</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td rowspan="7">Non Banking Finance Companies (NBFC)</td>
    <td width="332">Claim of depositor</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td width="332">Status of companies in    liquidation / winding up</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td width="332">Issue of share transfer</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td width="332">Observations in periodic account</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td width="332">Tax deduction</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td width="332">others</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td width="332">&nbsp;</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td rowspan="6">Real Estate Investment Trust (REIT)</td>
    <td rowspan="2">Dividend not received</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>Tax benefits</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>Units not received</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>Status of license of RMCs</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>others</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td rowspan="6">Voluntary Pension Schemes</td>
    <td>Queries about sub-funds of pension funds</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>Status of registration of Pension Fund Manager (PFM)</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>Benefits of investments</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>Expected return on pension funds</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>Queries about tax on withdrawal</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>others</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>Private Equity and Venture Capital Fund</td>
    <td width="332">Others</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=108<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td rowspan="7">Modaraba</td>
    <td rowspan="2" width="332">Issues of Modaraba certificates transfer</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>Status of companies in liquidation / winding up</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>Inquiry about dividend</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>inquiry about shares</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td width="332">Non issuance of NOCs after    payment of lease/ loans</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>others</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>Laws/Acts/Ordinances/Bill/Rules </td>
    <td>clarification on Regulatory framework</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1430<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1430); ?></a></td>
  </tr>
  <tr>
    <td></td>
    <td>Other</td>
    <td>&nbsp;</td>
    <td width="452"></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1430<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1430); ?></a></td>
  </tr>
  <tr>
    <td colspan="5">Not Categorized</td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=43<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(43); ?></a></td>
  </tr>
  <tr>
    <td colspan="5"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></a></td>
  </tr>
  </tbody>
</table>
      
    </div>
  </div>
</div>
<?php }
elseif($_POST['dept_id']=='5'){
	//e-Services
?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>eServices Category Summary</h1>
    </div>
    <div class="block-fluid ">
      
        
        
       
        <table cellpadding="0" cellspacing="0" width="100%" class="table">

  <col width="323" span="2">
  <col width="22">
  <col width="73">
<thead>
  <tr>
    <td width="323">Primary    Category </td>
    <td width="323">Sub Nature</td>
    <td width="22"></td>
    <td width="73">Total  <br>
      Complaints</td>
  </tr>
  </thead>
  
  <tbody>
  <tr>
    <td>Process Name</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1430<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1430); ?></a></td>
  </tr>
  <tr>
    <td rowspan="2" width="323">Company Name Reservation</td>
    <td width="323">Attachment</td>
    <td>&nbsp;</td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=788<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(788); ?></a></td>
  </tr>
  <tr>
    <td width="323">Unable to continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=789<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(789); ?></a></td>
  </tr>
  <tr>
    <td rowspan="6" width="323">Company Incorporation</td>
    <td>Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=790<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(790); ?></a></td>
  </tr>
  <tr>
    <td width="323">Unable to continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=791<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(791); ?></a></td>
  </tr>
  <tr>
    <td width="323">Manage Company Users </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=792<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(792); ?></a></td>
  </tr>
  <tr>
    <td width="323">Challan related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=793<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(793); ?></a></td>
  </tr>
  <tr>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=794<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(794); ?></a></td>
  </tr>
  <tr>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=795<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(795); ?></a></td>
  </tr>
  <tr>
    <td width="323">Change of Company Name</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=796<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(796); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=797<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(797); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=798<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(798); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=799<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(799); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=800<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(800); ?></a></td>
  </tr>
  <tr>
    <td width="323">Change of Company Address</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=801<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(801); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=802<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(802); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=803<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(803); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=804<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(804); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=805<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(805); ?></a></td>
  </tr>
  <tr>
    <td width="323">Companies Easy Exit Scheme (CEER)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(807); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(808); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(809); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(810); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(811) ?></a></td>
  </tr>
  <tr>
    <td width="323">Change of Company Address (different province)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=812<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(812); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=813<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(813); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=814<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(814); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=815<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(815); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=816<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(816); ?></a></td>
  </tr>
  <tr>
    <td width="323">Change of Company Status (Private to Public)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=817<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(817); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=818<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(818); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=819<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(819); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=820<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(820); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=821<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(821); ?></a></td>
  </tr>
  <tr>
    <td width="323">Change of Company Status (Public to Private)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=822<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(822); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=823<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(823); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=824<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(824); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=825<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(825); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=826<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(826); ?></a></td>
  </tr>
  <tr>
    <td width="323">Change of Company Status (Private to Single Member)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=827<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(827); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=828<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(828); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=829<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(829); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=830<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(830); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=831<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(831); ?></a></td>
  </tr>
  <tr>
    <td width="323">Change of Company Status (Single Member to Private)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=832<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(832); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=833<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(833); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=834<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(834); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=835<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(835); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=836<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(836); ?></a></td>
  </tr>
  <tr>
    <td width="323">Change in Company Objects</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=838<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(838); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=839<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(839); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=840<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(840); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=841<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(841); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=842<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(842); ?></a></td>
  </tr>
  <tr>
    <td width="323">Filing of Statutory Return</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=845<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(845); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=846<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(846); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=847<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(847); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=848<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(848); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=849<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(849); ?></a></td>
  </tr>
  <tr>
    <td width="323">Miscellaneous</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=850<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(850); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=851<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(851); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=852<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(852); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=853<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(853); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=854<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(854); ?></a></td>
  </tr>
  <tr>
    <td width="323">Certified True Copy</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=855<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(855); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=856<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(856); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=857<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(857); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=858<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(858); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Dropdown Menu</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=859<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(859); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=860<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(860); ?></a></td>
  </tr>
  <tr>
    <td width="323">Penalty</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=861<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(861); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=862<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(862); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=863<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(863); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=864<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(864); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=865<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(865); ?></a></td>
  </tr>
  <tr>
    <td width="323">Inspection</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=866<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(866); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=867<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(867); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=868<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(868); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=869<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(869); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=870<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(870); ?></a></td>
  </tr>
  <tr>
    <td width="323">Miscellaneous(Without Payment)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=872<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(872); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=873<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(873); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=874<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(874); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=875<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(875); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=876<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(876); ?></a></td>
  </tr>
  <tr>
    <td width="323">Filing of Statutory Return (Multiple Forms)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=877<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(877); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=878<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(878); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=879<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(879); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=880<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(880); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=881<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(881); ?></a></td>
  </tr>
  <tr>
    <td width="323">Foreign Company Incorporation</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=882<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(882); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=883<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(883); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=884<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(884); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=885<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(885); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=886<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(886); ?></a></td>
  </tr>
  <tr>
    <td width="323">Change of Registered / Principal Office of Foreign Company (In    Country of Origin)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=888<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(888); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=889<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(889); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=890<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(890); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=891<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(891); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=892<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(892); ?></a></td>
  </tr>
  <tr>
    <td width="323">Change in Particulars of Persons (Directors/Chief    Executives/Secretaries)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=893<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(893); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=894<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(894); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=895<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(895); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=896<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(896); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=897<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(897); ?></a></td>
  </tr>
  <tr>
    <td width="323">Change in Foreign Company Charter /Statute /Memorandum and    Article of Association)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=898<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(898); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=899<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(899); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=900<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(900); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=901<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(901); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=935<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(935); ?></a></td>
  </tr>
  <tr>
    <td width="323">Establishment of Foreign Company Business Places and Submission    of Accounts</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=902<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(902); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=936<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(936); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=937<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(937); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=968<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(968); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=939<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(939); ?></a></td>
  </tr>
  <tr>
    <td width="323">Foreign Company Wind Up (Ceasing)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=940<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(940); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=941<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(941); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=942<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(942); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=943<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(943); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=944<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(944); ?></a></td>
  </tr>
  <tr>
    <td width="323">Change In Foreign Company Place of Business in Pak</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=945<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(945); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=946<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(946); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=947<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(947); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=948<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(948); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=949<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(949); ?></a></td>
  </tr>
  <tr>
    <td width="323">Filing of Returns-NBFC</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=950<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(950); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=951<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(951); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=952<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(952); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=953<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(953); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=954<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(954); ?></a></td>
  </tr>
  <tr>
    <td width="323">Change in Particulars of Persons (Principal Officer)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=955<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(955); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=956<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(956); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=957<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(957); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=958<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(958); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=959<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(959); ?></a></td>
  </tr>
  <tr>
    <td width="323">Change in Particulars of Persons (Authorized Person)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=960<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(960); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=961<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(961); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=962<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(962); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=963<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(963); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=964<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(964); ?></a></td>
  </tr>
  <tr>
    <td width="323">Form 29 - Multiple</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=965<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(965); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=966<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(966); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=967<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(967); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=968<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(968); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=969<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(969); ?></a></td>
  </tr>
  <tr>
    <td width="323">Appointment/Change of Company Officers</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=970<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(970); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=971<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(971); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=972<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(972); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=973<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(973); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=974<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(974); ?></a></td>
  </tr>
  <tr>
    <td width="323">Appointment/Change of Director</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=975<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(975); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=976<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(976); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=977<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(977); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=978<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(978); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=979<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(979); ?></a></td>
  </tr>
  <tr>
    <td width="323">Appointment/Change of CEO</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=984<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(984); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=983<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(983); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=982<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(982); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=981<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(981); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=980<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(980); ?></a></td>
  </tr>
  <tr>
    <td width="323">Appointment/Change of Other Officers</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=985<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(985); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=986<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(986); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=987<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(987); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=988<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(988); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=989<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(989); ?></a></td>
  </tr>
  <tr>
    <td width="323">Appointment/Change of All Officers</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=990<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(990); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=991<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(991); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=992<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(992); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=993<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(993); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=994<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(994); ?></a></td>
  </tr>
  <tr>
    <td width="323">Filing of Form 27</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=995<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(995); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=996<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(996); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=997<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(997); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=998<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(998); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=999<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(999); ?></a></td>
  </tr>
  <tr>
    <td width="323">Filing of Form 28</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1000<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1000); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1001<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1001); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1002<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1002); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1003<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1003); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1004<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1004); ?></a></td>
  </tr>
  <tr>
    <td width="323">Filing of Form 29</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1005<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1005); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1006<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1006); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1007<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1007); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1008<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1008); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1009<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1009); ?></a></td>
  </tr>
  <tr>
    <td width="323">Filing of Form 27_28</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1010<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1010); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1011<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1011); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1012<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1012); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1013<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1013); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1014<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1014); ?></a></td>
  </tr>
  <tr>
    <td width="323">Filing of Form 28_29</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=108<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1015); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1016<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1016); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1017<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1017); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1018<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1018); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1019<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1019); ?></a></td>
  </tr>
  <tr>
    <td width="323">Insurance Surveyor Licensing</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1020<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1020); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1021<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1021); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1022<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1022); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1023<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1023); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1024<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1024); ?></a></td>
  </tr>
  <tr>
    <td width="323">Licensing of Insurance Surveyors Companies</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1025<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1025); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1026<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1026); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1027<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1027); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1028<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1028); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1029<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1029); ?></a></td>
  </tr>
  <tr>
    <td width="323">Renewal of Insurance Surveyors Companies License</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1030<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1030); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1031<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1031); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1032<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1032); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1033<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1033); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1034<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1034); ?></a></td>
  </tr>
  <tr>
    <td width="323">Licensing of Authorized Surveying Officer</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1035<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1035); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1036<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1036); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=108<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1037); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1038<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1038); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1039<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1039); ?></a></td>
  </tr>
  <tr>
    <td width="323">Renewal of Insurance Authorized Surveying Officer</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1040<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1040); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1041<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1041); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1042<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1042); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1043<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1043); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1044<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1044); ?></a></td>
  </tr>
  <tr>
    <td width="323">Filing of Returns-FRS (SMBH)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1045<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1045); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1046<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1046); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1047<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1047); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1048<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1048); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1049<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1049); ?></a></td>
  </tr>
  <tr>
    <td width="323">Filing of Returns - Insurance</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1050<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1050); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1051<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1051); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1052<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1052); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1053<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1053); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1054<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1054); ?></a></td>
  </tr>
  <tr>
    <td width="323">Annual Return by Listed Companies SMD-BO-107</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1055<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1055); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1056<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1056); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1057<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1057); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1058<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1058); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1059<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1059); ?></a></td>
  </tr>
  <tr>
    <td width="323">Reporting of Features of Debt Instrument</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1060<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1060); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1061<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1061); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1062<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1062); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1063<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1063); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1064<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1064); ?></a></td>
  </tr>
  <tr>
    <td width="323">Periodic Reporting of Redemption and Status of Covenant    Compliance</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1065<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1065); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1066<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1066); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1067<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1067); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1068<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1068); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1069<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1069); ?></a></td>
  </tr>
  <tr>
    <td width="323">Filing of Form 4 (BO) under u/s 102 of Securities Act, 2015</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1070<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1070); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1071<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1071); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1072<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1072); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1073<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1073); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1074<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1074); ?></a></td>
  </tr>
  <tr>
    <td>User    Registration and PIN Generation</td>
    <td width="323">Unable to    generate </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1075<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1075); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="323">Inactive/Invalid</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1076<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1076); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="323">Forgot password </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1077<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1077); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="323">PIN not received</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1078<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1078); ?></a></td>
  </tr>
  <tr>
    <td>Restoration of Company</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1080); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1081); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1082); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1083); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1084); ?></a></td>
  </tr>
  <tr>
    <td>Mortgage Regstration etc.</td>
    <td width="323">&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1485); ?></a></td>
  </tr>
  <tr>
    <td>Process Unavailable</td>
    <td width="323">&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1086<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1086); ?></a></td>
  </tr>
  <tr>
    <td>Other</td>
    <td></td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1087<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1087); ?></a></td>
  </tr>
  <tr>
    <td colspan="3">Not Categorized</td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=43<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(43); ?></a></td>
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><a><?php echo get_complaints_by_deparment(); ?></a></td>
  </tr>
</tbody>
</table>

      
    </div>
  </div>
</div>
<?php }
elseif($_POST['dept_id']=='6'){
	//Company Registration/Compliance
?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1><?php echo 'Company Registration/Compliance Category Summary'; ?></h1>
    </div>
    <div class="block-fluid ">
      
        
        
       
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
  <col width="323">
  <col width="304">
  <col width="22">
  <col width="73">
  <thead>
  <tr>
    <th width="323">Primary    Category </th>
    <th width="304">Sub    Category </th>
    <th width="22"></th>
    <th width="73">Total  <br>
      Complaints</th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td>Complaint against non-corporate entities</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=152<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(152); ?></a></td>
  </tr>
  <tr>
    <td>Fraudulant    activities of corporate entities</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=153<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(153); ?></a></td>
  </tr>
  <tr>
    <td>Investigation    against the company</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=154<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(154); ?></a></td>
  </tr>
  <tr>
    <td width="323">Complaints against <br>
      applications, approvals, appeals etc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=155<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(155); ?></a></td>
  </tr>
  <tr>
    <td width="323">Complaints against public sector companies for <br>
      noncompliance of public sector companies <br>
      (corporate governance) rules, 2013</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=157<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(157); ?></a></td>
  </tr>
  <tr>
    <td width="323">Complaints against housing and <br>
      real estate companies registered <br>
      with the commission</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=158<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(158); ?></a></td>
  </tr>
  <tr>
    <td rowspan="9" width="323">Complaints pertaining to <br>
      not for profit companies (u/S 42 of <br>
      the Companies Ordinance 1984</td>
    <td>Issuance of license</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=743<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(743); ?></a></td>
  </tr>
  <tr>
    <td>Renewal of license</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=166<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(166); ?></a></td>
  </tr>
  <tr>
    <td>Quittal/admission of member</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=167<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(167); ?></a></td>
  </tr>
  <tr>
    <td>Alteration in memorandum and articles </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=168<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(168); ?></a></td>
  </tr>
  <tr>
    <td>Change of name</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=108<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(169); ?></a></td>
  </tr>
  <tr>
    <td width="304">Extension in time period for incorporation of <br>
      the company after grant of license </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=170<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(170); ?></a></td>
  </tr>
  <tr>
    <td width="304">Violation of licensing conditions of <br>
      not for profit companies</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=171<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(171); ?></a></td>
  </tr>
  <tr>
    <td>Illegal activities of not for profit companies</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=172<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(172); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=173<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(173); ?></a></td>
  </tr>
  <tr>
    <td width="323">Irrelevant, including operatonal matters of <br>
      the companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=161<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(161); ?></a></td>
  </tr>
  <tr>
    <td>Provident Fund    related complaints</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=162<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(162); ?></a></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=163<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(163); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=164<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(164); ?></a></td>
  </tr>
  <tr>
   <td colspan="3">Not Categorized</td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=43<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(43); ?></a></td>
  </tr>
  <tr>
   <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></a></td>
  </tr>
</tbody>
</table>

      
    </div>
  </div>
</div>
<?php }
elseif($_POST['dept_id']=='18'){
	//Supervision of Listed Companies
?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Supervision of Listed Companies Category Summary</h1>
    </div>
    <div class="block-fluid">
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
  <col width="83">
  <col width="377">
  <col width="515">
  <col width="86">
  <col width="172">
  <thead>
  <tr>
    <td width="83">Primary  
    <br>
      Category</td>
    <td width="377">Sub    Catgeory </td>
    <td width="515">Sub-Sub    Category</td>
    <td width="86"></td>
    <td width="172">Total  <br>
      Complaints</td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td rowspan="12">Shares</td>
    <td rowspan="3">Allttment of    Shares(Right/Bonus)</td>
    <td>Exchange/Conversion of Shares    certificates (in case of merger/takeover of companies)</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=692<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(692); ?></a></td>
  </tr>
  <tr>
    <td>Late dispatch of Shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=693<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(693); ?></a></td>
  </tr>
  <tr>
    <td>Refund to rejected applicants in case of public offers</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=694<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(694); ?></a></td>
  </tr>
  <tr>
    <td>Duplicate issue of Shares </td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">Refusal of Transfer of Shares</td>
    <td>Refusal of tranfer of    purchased shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=695<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(695); ?></a></td>
  </tr>
  <tr>
    <td>Refusal of Transfer of shares to successor in interest </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=696<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(696); ?></a></td>
  </tr>
  <tr>
    <td>Refusal of nominee of deceased member or legal represetative</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=697<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(697); ?></a></td>
  </tr>
  <tr>
    <td>Non Issue of Notice for refusal of transfer</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=698<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(698); ?></a></td>
  </tr>
  <tr>
    <td>Appeal against refusal for registration of transfer </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=699<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(699); ?></a></td>
  </tr>
  <tr>
    <td>Rectification of shares register </td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=675<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(675); ?></a></td>
  </tr>
  <tr>
    <td>Verification of signatures/tansfer deed</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=676<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(676); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=677<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(677); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">Dividend </td>
    <td>Declaration of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=678<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(678); ?></a></td>
  </tr>
  <tr>
    <td width="377">non-payment/receipt f dividend within <br>
      stipulated period</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=679<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(679); ?></a></td>
  </tr>
  <tr>
    <td>Issuance of duplicate dividend warrants</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=680<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(680); ?></a></td>
  </tr>
  <tr>
    <td width="377">Non=credit of dividend <br>
      as per bank mandate</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=681<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(681); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=682<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(682); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">Accounts</td>
    <td width="377">Non-receipt    of accounts <br>
      (annually and quarterly)</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=746<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(746); ?></a></td>
  </tr>
  <tr>
    <td width="377">Non provision of copies of minutes/<br>
      extracts/other information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=684<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(684); ?></a></td>
  </tr>
  <tr>
    <td>Nonreceipt of notices of AGM/EOGM</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=685<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(685); ?></a></td>
  </tr>
  <tr>
    <td>Disclosure in director's report/auditors report</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=686<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(686); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=687<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(687); ?></a></td>
  </tr>
  <tr>
    <td rowspan="4">Management</td>
    <td width="377">Fraud/Mis-management    by <br>
      management of company</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=688<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(688); ?></a></td>
  </tr>
  <tr>
    <td>Siphoning/misuse of company assets </td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=689<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(689); ?></a></td>
  </tr>
  <tr>
    <td>Dispute between directors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=690<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(690); ?></a></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=691<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(691); ?></a></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=670<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(670); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=671<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(671); ?></a></td>
  </tr>
  <tr>
    <td colspan="4">Not Categorized</td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=43<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(43); ?></a></td>
  </tr>
  <tr>
    <td colspan="4"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></a></td>
  </tr>
</tbody>
</table>

      
    </div>
  </div>
</div>
<?php }
elseif($_POST['dept_id']=='19'){
	//Human Resource
?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Human Resource Complaints</h1>
    </div>
    <div class="block-fluid ">
      
        
        
       
        <table cellpadding="0" cellspacing="0" width="100%" class="table">

  <col width="323">
  <col width="304">
  <col width="22">
  <col width="73">
  <thead>
  <tr>
    <td width="323">Primary    Category </td>
    <td width="304">Sub Category </td>
    <td width="22"></td>
    <td width="73">Total  <br>
      Complaints</td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td>Complaint against employee/s</td>
    <td>SMD</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Insurance</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>SCD</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td>CSD</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td>CCD</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td>CRO-Islamabad</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td>CRO-Karachi</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td>CRO-Lahore</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td>CRO-Peshawar</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td>CRO-Quetta</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td>CRO-Sukkur</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="304">CRO-Multan</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="304">CRO-Faisalabad</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td>CRO-Gilgit</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td>IS&amp;T</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td>HR&amp;T</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Adminsitration</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Finance</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>PLAD</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>IEIRD</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Other Issues</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Complaint    against process/es</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  </tbody>
</table>

      
    </div>
  </div>
</div>
<?php }
elseif($_POST['dept_id']=='20'){
	//Finance
?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Finance Complaints</h1>
    </div>
    <div class="block-fluid ">    
        <table cellpadding="0" cellspacing="0" width="100%" class="table">

</table>

      
    </div>
  </div>
</div>
<?php }
elseif($_POST['dept_id']=='21'){
	//PLAD
?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>PLAD Complaints</h1>
    </div>
    <div class="block-fluid ">    
        <table cellpadding="0" cellspacing="0" width="100%" class="table">

</table>

      
    </div>
  </div>
</div>
<?php }
elseif($_POST['dept_id']=='22'){
	//IS&T
?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>IS&T Complaints</h1>
    </div>
    <div class="block-fluid ">    
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
  <col width="323">
  <col width="304">
  <col width="22">
  <col width="73">
  <thead>
  <tr>
    <td width="323">Primary    Category </td>
    <td width="304">Sub Category </td>
    <td width="22"></td>
    <td width="73">Total  <br>
      Complaints</td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td>Technical Issues<!--750--></td>
    <td>Chalan Verification</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=752<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(752); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Information Update Requests </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=753<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(753); ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>User ID and Password </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=754<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(754); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td>Server not Responding </td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=755<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(755); ?></a></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td>Misc.</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=756<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(756); ?></a></td>
  </tr>
  <tr>
    <td>Other <!--751--></td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=751<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(751); ?></a></td>
  </tr>
     
  <tr>
    <td colspan="3">Not Categorized</td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=43<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(43); ?></a></td>
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></a></td>
  </tr>
  </tbody>
</table>

      
    </div>
  </div>
</div>
<?php }
elseif($_POST['dept_id']=='23'){
	//Media
?>
<?php }
elseif($_POST['dept_id']=='24'){
	//IE&IR
?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>Investor Education & International Relations Complaints</h1>
    </div>
    <div class="block-fluid ">    
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
  <col width="323">
  <col width="304">
  <col width="22">
  <col width="73">
  <thead>
  <tr>
    <td width="323">Primary    Category </td>
    <td width="304"> </td>
    <td width="22"></td>
    <td width="73">Total  <br>
      Complaints</td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td>Unclaimed Dividend<!--751--></td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1534<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1534); ?></a></td>
  </tr>
  <tr>
    <td>International Relations</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1529<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1529); ?></a></td>
  </tr>
  <tr>
    <td>Investor Education</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1528<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1528); ?></a></td>
  </tr>
  <tr>
    <td>Anti-Money Laundering</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1527<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1527); ?></a></td>
  </tr>
  <tr>
    <td>General</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=1526<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(1526); ?></a></td>
  </tr>
  <tr>
    <td colspan="3">Not Categorized</td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=43<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(43); ?></a></td>
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></a></td>
  </tr>
  </tbody>
</table>

      
    </div>
  </div>
</div>
<?php }
elseif($_POST['dept_id']=='11'){
	//CRO Karachi ?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>CRO-Karachi Complaints</h1>
    </div>
    <div class="block-fluid ">
      
        
        
       
        <table cellpadding="0" cellspacing="0" width="100%" class="table">

  <col width="282">
  <col width="242">
  <col width="22">
  <col width="73">
  <thead>
  <tr>
    <td width="282">Primary    Category </td>
    <td width="242">Sub    Category </td>
    <td width="22"></td>
    <td width="73">Total  <br>
      Complaints</td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td width="282">Clarification   required regarding <br>
      observations conveyed by CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=253<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(253); ?></a></td>
  </tr>
  <tr>
    <td>Data    updation/Rectification</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=254<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(254); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    deciding name availability</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=255<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(255); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    issuance of CTCs</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=256<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(256); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    registration of companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=257<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(257); ?></a></td>
  </tr>
  <tr>
    <td>Dispute    among/with the management</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=258<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(258); ?></a></td>
  </tr>
  <tr>
    <td rowspan="2">Transfer    of shares</td>
    <td>Nontransfer of shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=269<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(269); ?></a></td>
  </tr>
  <tr>
    <td>Illegal Transfer of Shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=270<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(270); ?></a></td>
  </tr>
  <tr>
    <td>Irregularities    in further allottment of shares</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=260<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(260); ?></a></td>
  </tr>
  <tr>
    <td rowspan="3">Election    of directors</td>
    <td>Improper election of    directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=271<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(271); ?></a></td>
  </tr>
  <tr>
    <td>Non-holding of election of directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=272<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(272); ?></a></td>
  </tr>
  <tr>
    <td>Delay in election of directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=273<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(273); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">Irregularities    in holding meetings</td>
    <td>AGM</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=274<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(274); ?></a></td>
  </tr>
  <tr>
    <td>EOGM</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=275<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(275); ?></a></td>
  </tr>
  <tr>
    <td>STATUTORY</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=276<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(276); ?></a></td>
  </tr>
  <tr>
    <td>BOARD</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=277<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(277); ?></a></td>
  </tr>
  <tr>
    <td width="242">Non-issuance of notices for <br>
      holding member's meetings</td>
    <td width="22"></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=278<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(278); ?></a></td>
  </tr>
  <tr>
    <td>Non-payment of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=263<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(263); ?></a></td>
  </tr>
  <tr>
    <td width="282">Non Provision of <br>
      financial statements/information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td>Process/Form    pending at CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=265<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(265); ?></a></td>
  </tr>
  <tr>
    <td>Non    appointment of auditors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=266<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(266); ?></a></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=267<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(267); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=268<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(268); ?></a></td>
  </tr>
  <tr>
    <td colspan="3">Not Categorized</td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=43<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(43); ?></a></td>
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></a></td>
  </tr>
  </tbody>
</table>
      
    </div>
  </div>
</div>

<?php }
elseif($_POST['dept_id']=='7'){
	//CRO Islamabad ?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>CRO-Islamabad Complaints</h1>
    </div>
    <div class="block-fluid ">
      
        
        
       
        <table cellpadding="0" cellspacing="0" width="100%" class="table">

  <col width="282">
  <col width="242">
  <col width="22">
  <col width="73">
  <thead>
  <tr>
    <td width="282">Primary    Category </td>
    <td width="242">Sub    Category </td>
    <td width="22"></td>
    <td width="73">Total  <br>
      Complaints</td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td width="282">Clarification   required regarding <br>
      observations conveyed by CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=227<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(227); ?></a></td>
  </tr>
  <tr>
    <td>Data    updation/Rectification</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=228<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(228); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    deciding name availability</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=229<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(229); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    issuance of CTCs</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=230<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(230); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    registration of companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=231<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(231); ?></a></td>
  </tr>
  <tr>
    <td>Dispute    among/with the management</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=232<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(232); ?></a></td>
  </tr>
  <tr>
    <td rowspan="2">Transfer    of shares</td>
    <td>Nontransfer of shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=243<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(243); ?></a></td>
  </tr>
  <tr>
    <td>Illegal Transfer of Shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=244<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(244); ?></a></td>
  </tr>
  <tr>
    <td>Irregularities    in further allottment of shares</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=234<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(234); ?></a></td>
  </tr>
  <tr>
    <td rowspan="3">Election    of directors</td>
    <td>Improper election of    directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=245<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(245); ?></a></td>
  </tr>
  <tr>
    <td>Non-holding of election of directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=246<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(246); ?></a></td>
  </tr>
  <tr>
    <td>Delay in election of directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=247<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(247); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">Irregularities    in holding meetings</td>
    <td>AGM</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=248<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(248); ?></a></td>
  </tr>
  <tr>
    <td>EOGM</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=249<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(249); ?></a></td>
  </tr>
  <tr>
    <td>STATUTORY</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=250<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(250); ?></a></td>
  </tr>
  <tr>
    <td>BOARD</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=251<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(251); ?></a></td>
  </tr>
  <tr>
    <td width="242">Non-issuance of notices for <br>
      holding member's meetings</td>
    <td width="22"></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=252<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(252); ?></a></td>
  </tr>
  <tr>
    <td>Non-payment of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=237<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(237); ?></a></td>
  </tr>
  <tr>
    <td width="282">Non Provision of <br>
      financial statements/information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=238<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(238); ?></a></td>
  </tr>
  <tr>
    <td>Process/Form    pending at CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=239<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(239); ?></a></td>
  </tr>
  <tr>
    <td>Non    appointment of auditors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=240<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(240); ?></a></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=241<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(241); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=242<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(242); ?></a></td>
  </tr>
  <tr>
   <td colspan="3">Not Categorized</td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=43<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(43); ?></a></td>
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></a></td>
  </tr>
  </tbody>
</table>
      
    </div>
  </div>
</div>
<?php }
elseif($_POST['dept_id']=='10'){
	//CRO Lahore ?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>CRO-Lahore Complaints</h1>
    </div>
    <div class="block-fluid ">
      
        
        
       
        <table cellpadding="0" cellspacing="0" width="100%" class="table">

  <col width="282">
  <col width="242">
  <col width="22">
  <col width="73">
  <thead>
  <tr>
    <td width="282">Primary    Category </td>
    <td width="242">Sub    Category </td>
    <td width="22"></td>
    <td width="73">Total  <br>
      Complaints</td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td width="282">Clarification   required regarding <br>
      observations conveyed by CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=279<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(279); ?></a></td>
  </tr>
  <tr>
    <td>Data    updation/Rectification</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=280<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(280); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    deciding name availability</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=281<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(281); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    issuance of CTCs</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=282<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(282); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    registration of companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=283<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(283); ?></a></td>
  </tr>
  <tr>
    <td>Dispute    among/with the management</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=284<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(284); ?></a></td>
  </tr>
  <tr>
    <td rowspan="2">Transfer    of shares</td>
    <td>Nontransfer of shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=295<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(295); ?></a></td>
  </tr>
  <tr>
    <td>Illegal Transfer of Shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=296<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(296); ?></a></td>
  </tr>
  <tr>
    <td>Irregularities    in further allottment of shares</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=286<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(286); ?></a></td>
  </tr>
  <tr>
    <td rowspan="3">Election    of directors</td>
    <td>Improper election of    directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=297<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(297); ?></a></td>
  </tr>
  <tr>
    <td>Non-holding of election of directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=298<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(298); ?></a></td>
  </tr>
  <tr>
    <td>Delay in election of directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=299<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(299); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">Irregularities    in holding meetings</td>
    <td>AGM</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=300<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(300); ?></a></td>
  </tr>
  <tr>
    <td>EOGM</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=301<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(301); ?></a></td>
  </tr>
  <tr>
    <td>STATUTORY</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=302<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(302); ?></a></td>
  </tr>
  <tr>
    <td>BOARD</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=303<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(303); ?></a></td>
  </tr>
  <tr>
    <td width="242">Non-issuance of notices for <br>
      holding member's meetings</td>
    <td width="22"></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=304<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(304); ?></a></td>
  </tr>
  <tr>
    <td>Non-payment of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=289<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(289); ?></a></td>
  </tr>
  <tr>
    <td width="282">Non Provision of <br>
      financial statements/information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=290<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(290); ?></a></td>
  </tr>
  <tr>
    <td>Process/Form    pending at CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=291<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(291); ?></a></td>
  </tr>
  <tr>
    <td>Non    appointment of auditors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=292<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(292); ?></a></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=293<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(293); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=294<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(294); ?></a></td>
  </tr>
  <tr>
    <td colspan="3">Not Categorized</td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=43<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(43); ?></a></td>
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></a></td>
  </tr>
  </tbody>
</table>
      
    </div>
  </div>
</div>

<?php }
elseif($_POST['dept_id']=='11'){
	//CRO Karachi ?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>CRO-Karachi Complaints</h1>
    </div>
    <div class="block-fluid ">
      
        
        
       
        <table cellpadding="0" cellspacing="0" width="100%" class="table">

  <col width="282">
  <col width="242">
  <col width="22">
  <col width="73">
  <thead>
  <tr>
    <td width="282">Primary    Category </td>
    <td width="242">Sub    Category </td>
    <td width="22"></td>
    <td width="73">Total  <br>
      Complaints</td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td width="282">Clarification   required regarding <br>
      observations conveyed by CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=253<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(253); ?></a></td>
  </tr>
  <tr>
    <td>Data    updation/Rectification</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=254<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(254); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    deciding name availability</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=255<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(255); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    issuance of CTCs</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=256<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(256); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    registration of companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=257<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(257); ?></a></td>
  </tr>
  <tr>
    <td>Dispute    among/with the management</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=258<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(258); ?></a></td>
  </tr>
  <tr>
    <td rowspan="2">Transfer    of shares</td>
    <td>Nontransfer of shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=269<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(269); ?></a></td>
  </tr>
  <tr>
    <td>Illegal Transfer of Shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=270<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(270); ?></a></td>
  </tr>
  <tr>
    <td>Irregularities    in further allottment of shares</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=260<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(260); ?></a></td>
  </tr>
  <tr>
    <td rowspan="3">Election    of directors</td>
    <td>Improper election of    directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=271<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(271); ?></a></td>
  </tr>
  <tr>
    <td>Non-holding of election of directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=272<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(272); ?></a></td>
  </tr>
  <tr>
    <td>Delay in election of directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=273<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(273); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">Irregularities    in holding meetings</td>
    <td>AGM</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=274<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(274); ?></a></td>
  </tr>
  <tr>
    <td>EOGM</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=275<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(275); ?></a></td>
  </tr>
  <tr>
    <td>STATUTORY</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=276<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(276); ?></a></td>
  </tr>
  <tr>
    <td>BOARD</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=277<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(277); ?></a></td>
  </tr>
  <tr>
    <td width="242">Non-issuance of notices for <br>
      holding member's meetings</td>
    <td width="22"></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=278<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(278); ?></a></td>
  </tr>
  <tr>
    <td>Non-payment of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=263<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(263); ?></a></td>
  </tr>
  <tr>
    <td width="282">Non Provision of <br>
      financial statements/information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td>Process/Form    pending at CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=265<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(265); ?></a></td>
  </tr>
  <tr>
    <td>Non    appointment of auditors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=266<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(266); ?></a></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=267<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(267); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=268<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(268); ?></a></td>
  </tr>
  <tr>
   <td colspan="3">Not Categorized</td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=43<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(43); ?></a></td>
  </tr>
  <tr>
   <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></a></td>
  </tr>
  
  </tbody>
</table>
      
    </div>
  </div>
</div>

<?php }
elseif($_POST['dept_id']=='12'){
	//CRO Faisalabad reaminging ?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>CRO Faisalabad Complaints</h1>
    </div>
    <div class="block-fluid ">
      
        
        
       
        <table cellpadding="0" cellspacing="0" width="100%" class="table">

  <col width="282">
  <col width="242">
  <col width="22">
  <col width="73">
  <thead>
  <tr>
    <td width="282">Primary    Category </td>
    <td width="242">Sub    Category </td>
    <td width="22"></td>
    <td width="73">Total  <br>
      Complaints</td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td width="282">Clarification   required regarding <br>
      observations conveyed by CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=174<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(174); ?></a></td>
  </tr>
  <tr>
    <td>Data    updation/Rectification</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=175<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(175); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    deciding name availability</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=176<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(176); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    issuance of CTCs</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=177<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(177); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    registration of companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=178<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(178); ?></a></td>
  </tr>
  <tr>
    <td>Dispute    among/with the management</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=179<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(179); ?></a></td>
  </tr>
  <tr>
    <td rowspan="2">Transfer    of shares</td>
    <td>Nontransfer of shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=190<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(190); ?></a></td>
  </tr>
  <tr>
    <td>Illegal Transfer of Shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=191<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(191); ?></a></td>
  </tr>
  <tr>
    <td>Irregularities    in further allottment of shares</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=181<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(181); ?></a></td>
  </tr>
  <tr>
    <td rowspan="3">Election    of directors</td>
    <td>Improper election of    directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=192<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(192); ?></a></td>
  </tr>
  <tr>
    <td>Non-holding of election of directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=193<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(193); ?></a></td>
  </tr>
  <tr>
    <td>Delay in election of directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=194<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(194); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">Irregularities    in holding meetings</td>
    <td>AGM</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=195<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(195); ?></a></td>
  </tr>
  <tr>
    <td>EOGM</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=196<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(196); ?></a></td>
  </tr>
  <tr>
    <td>STATUTORY</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=108<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(197); ?></a></td>
  </tr>
  <tr>
    <td>BOARD</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=198<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(198); ?></a></td>
  </tr>
  <tr>
    <td width="242">Non-issuance of notices for <br>
      holding member's meetings</td>
    <td width="22"></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=199<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(199); ?></a></td>
  </tr>
  <tr>
    <td>Non-payment of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=184<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(184); ?></a></td>
  </tr>
  <tr>
    <td width="282">Non Provision of <br>
      financial statements/information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=185<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(185); ?></a></td>
  </tr>
  <tr>
    <td>Process/Form    pending at CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=186<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(186); ?></a></td>
  </tr>
  <tr>
    <td>Non    appointment of auditors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=187<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(187); ?></a></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=188<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(188); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=189<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(189); ?></a></td>
  </tr>
  <tr>
    <td colspan="3">Not Categorized</td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=43<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(43); ?></a></td>
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></a></td>
  </tr>
  </tbody>
</table>
      
    </div>
  </div>
</div>
<?php }
elseif($_POST['dept_id']=='13'){
	//CRO Multab reaminging ?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>CRO Multan Complaints</h1>
    </div>
    <div class="block-fluid ">
      
        
        
       
        <table cellpadding="0" cellspacing="0" width="100%" class="table">

  <col width="282">
  <col width="242">
  <col width="22">
  <col width="73">
  <thead>
  <tr>
    <td width="282">Primary    Category </td>
    <td width="242">Sub    Category </td>
    <td width="22"></td>
    <td width="73">Total  <br>
      Complaints</td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td width="282">Clarification   required regarding <br>
      observations conveyed by CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=305<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(305); ?></a></td>
  </tr>
  <tr>
    <td>Data    updation/Rectification</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=306<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(306); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    deciding name availability</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=477<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(477); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    issuance of CTCs</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    registration of companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=479<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(479); ?></a></td>
  </tr>
  <tr>
    <td>Dispute    among/with the management</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=480<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(480); ?></a></td>
  </tr>
  <tr>
    <td rowspan="2">Transfer    of shares</td>
    <td>Nontransfer of shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=492<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(492); ?></a></td>
  </tr>
  <tr>
    <td>Illegal Transfer of Shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=493<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(493); ?></a></td>
  </tr>
  <tr>
    <td>Irregularities    in further allottment of shares</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=482<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(482); ?></a></td>
  </tr>
  <tr>
    <td rowspan="3">Election    of directors</td>
    <td>Improper election of    directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=494<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(494); ?></a></td>
  </tr>
  <tr>
    <td>Non-holding of election of directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=495<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(495); ?></a></td>
  </tr>
  <tr>
    <td>Delay in election of directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=496<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(496); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">Irregularities    in holding meetings</td>
    <td>AGM</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td>EOGM</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td>STATUTORY</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td>BOARD</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td width="242">Non-issuance of notices for <br>
      holding member's meetings</td>
    <td width="22"></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id('?'); ?></a></td>
  </tr>
  <tr>
    <td>Non-payment of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=486<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(486); ?></a></td>
  </tr>
  <tr>
    <td width="282">Non Provision of <br>
      financial statements/information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=487<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(487); ?></a></td>
  </tr>
  <tr>
    <td>Process/Form    pending at CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=488<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(488); ?></a></td>
  </tr>
  <tr>
    <td>Non    appointment of auditors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=489<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(489); ?></a></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=490<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(490); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=491<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(491); ?></a></td>
  </tr>
  <tr>
    <td colspan="3">Not Categorized</td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=43<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(43); ?></a></td>
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></a></td>
  </tr>
  </tbody>
</table>
      
    </div>
  </div>
</div>
<?php }
elseif($_POST['dept_id']=='14'){
	//CRO Multab reaminging ?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>CRO Gilgit Complaints</h1>
    </div>
    <div class="block-fluid ">
      
        
        
       
        <table cellpadding="0" cellspacing="0" width="100%" class="table">

  <col width="282">
  <col width="242">
  <col width="22">
  <col width="73">
  <thead>
  <tr>
    <td width="282">Primary    Category </td>
    <td width="242">Sub    Category </td>
    <td width="22"></td>
    <td width="73">Total  <br>
      Complaints</td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td width="282">Clarification   required regarding <br>
      observations conveyed by CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=200<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(200); ?></a></td>
  </tr>
  <tr>
    <td>Data    updation/Rectification</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=201<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(201); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    deciding name availability</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=202<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(202); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    issuance of CTCs</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=203<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(203); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    registration of companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=204<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(204); ?></a></td>
  </tr>
  <tr>
    <td>Dispute    among/with the management</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=205<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(205); ?></a></td>
  </tr>
  <tr>
    <td rowspan="2">Transfer    of shares</td>
    <td>Nontransfer of shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=216<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(216); ?></a></td>
  </tr>
  <tr>
    <td>Illegal Transfer of Shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=217<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(217); ?></a></td>
  </tr>
  <tr>
    <td>Irregularities    in further allottment of shares</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=207<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(207); ?></a></td>
  </tr>
  <tr>
    <td rowspan="3">Election    of directors</td>
    <td>Improper election of    directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=218<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(218); ?></a></td>
  </tr>
  <tr>
    <td>Non-holding of election of directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=219<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(219); ?></a></td>
  </tr>
  <tr>
    <td>Delay in election of directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=220<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(220); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">Irregularities    in holding meetings</td>
    <td>AGM</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=222<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(222); ?></a></td>
  </tr>
  <tr>
    <td>EOGM</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=223<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(223); ?></a></td>
  </tr>
  <tr>
    <td>STATUTORY</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=224<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(224); ?></a></td>
  </tr>
  <tr>
    <td>BOARD</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=225<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(225); ?></a></td>
  </tr>
  <tr>
    <td width="242">Non-issuance of notices for <br>
      holding member's meetings</td>
    <td width="22"></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=226<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(226); ?></a></td>
  </tr>
  <tr>
    <td>Non-payment of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=210<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(210); ?></a></td>
  </tr>
  <tr>
    <td width="282">Non Provision of <br>
      financial statements/information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=211<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(211); ?></a></td>
  </tr>
  <tr>
    <td>Process/Form    pending at CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=212<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(212); ?></a></td>
  </tr>
  <tr>
    <td>Non    appointment of auditors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=213<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(213); ?></a></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=214<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(214); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=215<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(215); ?></a></td>
  </tr>
  <tr>
    <td colspan="3">Not Categorized</td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=43<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(43); ?></a></td>
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></a></td>
  </tr>
  </tbody>
</table>
      
    </div>
  </div>
</div>
<?php }
elseif($_POST['dept_id']=='15'){
	//CRO Multab reaminging ?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>CRO Peshawar Complaints</h1>
    </div>
    <div class="block-fluid ">
      
        
        
       
        <table cellpadding="0" cellspacing="0" width="100%" class="table">

  <col width="282">
  <col width="242">
  <col width="22">
  <col width="73">
  <thead>
  <tr>
    <td width="282">Primary    Category </td>
    <td width="242">Sub    Category </td>
    <td width="22"></td>
    <td width="73">Total  <br>
      Complaints</td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td width="282">Clarification   required regarding <br>
      observations conveyed by CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=502<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(502); ?></a></td>
  </tr>
  <tr>
    <td>Data    updation/Rectification</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=503<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(503); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    deciding name availability</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=504<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(504); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    issuance of CTCs</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=506<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(506); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    registration of companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=507<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(507); ?></a></td>
  </tr>
  <tr>
    <td>Dispute    among/with the management</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=508<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(508); ?></a></td>
  </tr>
  <tr>
    <td rowspan="2">Transfer    of shares</td>
    <td>Nontransfer of shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=519<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(519); ?></a></td>
  </tr>
  <tr>
    <td>Illegal Transfer of Shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=520<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(520); ?></a></td>
  </tr>
  <tr>
    <td>Irregularities    in further allottment of shares</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=510<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(510); ?></a></td>
  </tr>
  <tr>
    <td rowspan="3">Election    of directors</td>
    <td>Improper election of    directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=521<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(521); ?></a></td>
  </tr>
  <tr>
    <td>Non-holding of election of directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=522<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(522); ?></a></td>
  </tr>
  <tr>
    <td>Delay in election of directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=523<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(523); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">Irregularities    in holding meetings</td>
    <td>AGM</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=524<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(524); ?></a></td>
  </tr>
  <tr>
    <td>EOGM</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=525<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(525); ?></a></td>
  </tr>
  <tr>
    <td>STATUTORY</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=526<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(526); ?></a></td>
  </tr>
  <tr>
    <td>BOARD</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=527<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(527); ?></a></td>
  </tr>
  <tr>
    <td width="242">Non-issuance of notices for <br>
      holding member's meetings</td>
    <td width="22"></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=528<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(528); ?></a></td>
  </tr>
  <tr>
    <td>Non-payment of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=513<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(513); ?></a></td>
  </tr>
  <tr>
    <td width="282">Non Provision of <br>
      financial statements/information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=514<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(514); ?></a></td>
  </tr>
  <tr>
    <td>Process/Form    pending at CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=515<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(515); ?></a></td>
  </tr>
  <tr>
    <td>Non    appointment of auditors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=516<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(516); ?></a></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=517<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(517); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=518<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(518); ?></a></td>
  </tr>
  <tr>
    <td colspan="3">Not Categorized</td>
   
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=43<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(43); ?></a></td>
  </tr>
  <tr>
   <td colspan="3"><b>Total</b></td>
   
    <td><?php echo get_complaints_by_deparment(); ?></a></td>
  </tr>
  </tbody>
</table>
      
    </div>
  </div>
</div>
<?php }
elseif($_POST['dept_id']=='16'){
	//CRO Multab reaminging ?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>CRO Quetta Complaints</h1>
    </div>
    <div class="block-fluid ">
      
        
        
       
        <table cellpadding="0" cellspacing="0" width="100%" class="table">

  <col width="282">
  <col width="242">
  <col width="22">
  <col width="73">
  <thead>
  <tr>
    <td width="282">Primary    Category </td>
    <td width="242">Sub    Category </td>
    <td width="22"></td>
    <td width="73">Total  <br>
      Complaints</td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td width="282">Clarification   required regarding <br>
      observations conveyed by CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=529<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(529); ?></a></td>
  </tr>
  <tr>
    <td>Data    updation/Rectification</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=530<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(530); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    deciding name availability</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=531<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(531); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    issuance of CTCs</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=532<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(532); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    registration of companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=533<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(533); ?></a></td>
  </tr>
  <tr>
    <td>Dispute    among/with the management</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=534<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(534); ?></a></td>
  </tr>
  <tr>
    <td rowspan="2">Transfer    of shares</td>
    <td>Nontransfer of shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=545<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(545); ?></a></td>
  </tr>
  <tr>
    <td>Illegal Transfer of Shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=546<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(546); ?></a></td>
  </tr>
  <tr>
    <td>Irregularities    in further allottment of shares</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=536<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(536); ?></a></td>
  </tr>
  <tr>
    <td rowspan="3">Election    of directors</td>
    <td>Improper election of    directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=547<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(547); ?></a></td>
  </tr>
  <tr>
    <td>Non-holding of election of directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=548<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(548); ?></a></td>
  </tr>
  <tr>
    <td>Delay in election of directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=549<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(549); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">Irregularities    in holding meetings</td>
    <td>AGM</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=552<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(552); ?></a></td>
  </tr>
  <tr>
    <td>EOGM</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=553<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(553); ?></a></td>
  </tr>
  <tr>
    <td>STATUTORY</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=554<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(554); ?></a></td>
  </tr>
  <tr>
    <td>BOARD</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=555<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(555); ?></a></td>
  </tr>
  <tr>
    <td width="242">Non-issuance of notices for <br>
      holding member's meetings</td>
    <td width="22"></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=556<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(556); ?></a></td>
  </tr>
  <tr>
    <td>Non-payment of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=539<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(539); ?></a></td>
  </tr>
  <tr>
    <td width="282">Non Provision of <br>
      financial statements/information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=540<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(540); ?></a></td>
  </tr>
  <tr>
    <td>Process/Form    pending at CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=541<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(541); ?></a></td>
  </tr>
  <tr>
    <td>Non    appointment of auditors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=542<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(542); ?></a></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=543<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(543); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=544<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(544); ?></a></td>
  </tr>
  <tr>
   <td colspan="3">Not Categorized</td>
   
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=43<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(43); ?></a></td>
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></a></td>
  </tr>
  </tbody>
</table>
      
    </div>
  </div>
</div>
<?php }
elseif($_POST['dept_id']=='17'){
	//CRO Multab reaminging ?>
<div class="row-fluid">
  <div class="span12">
    <div class="head clearfix">
      <div class="isw-grid"></div>
      <h1>CRO Sukkur Complaints</h1>
    </div>
    <div class="block-fluid ">
      
        
        
       
        <table cellpadding="0" cellspacing="0" width="100%" class="table">

  <col width="282">
  <col width="242">
  <col width="22">
  <col width="73">
  <thead>
  <tr>
    <td width="282">Primary    Category </td>
    <td width="242">Sub    Category </td>
    <td width="22"></td>
    <td width="73">Total  <br>
      Complaints</td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td width="282">Clarification   required regarding <br>
      observations conveyed by CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=557<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(557); ?></a></td>
  </tr>
  <tr>
    <td>Data    updation/Rectification</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=558<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(558); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    deciding name availability</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=559<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(559); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    issuance of CTCs</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=560<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(560); ?></a></td>
  </tr>
  <tr>
    <td>Delay in    registration of companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=561<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(561); ?></a></td>
  </tr>
  <tr>
    <td>Dispute    among/with the management</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=562<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(562); ?></a></td>
  </tr>
  <tr>
    <td rowspan="2">Transfer    of shares</td>
    <td>Nontransfer of shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=573<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(573); ?></a></td>
  </tr>
  <tr>
    <td>Illegal Transfer of Shares</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=574<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(574); ?></a></td>
  </tr>
  <tr>
    <td>Irregularities    in further allottment of shares</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=564<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(564); ?></a></td>
  </tr>
  <tr>
    <td rowspan="3">Election    of directors</td>
    <td>Improper election of    directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=575<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(575); ?></a></td>
  </tr>
  <tr>
    <td>Non-holding of election of directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=576<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(576); ?></a></td>
  </tr>
  <tr>
    <td>Delay in election of directors</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=577<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(577); ?></a></td>
  </tr>
  <tr>
    <td rowspan="5">Irregularities    in holding meetings</td>
    <td>AGM</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=578<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(578); ?></a></td>
  </tr>
  <tr>
    <td>EOGM</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=579<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(579); ?></a></td>
  </tr>
  <tr>
    <td>STATUTORY</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=580<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(580); ?></a></td>
  </tr>
  <tr>
    <td>BOARD</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=581<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(581); ?></a></td>
  </tr>
  <tr>
    <td width="242">Non-issuance of notices for <br>
      holding member's meetings</td>
    <td width="22"></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=582<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(582); ?></a></td>
  </tr>
  <tr>
    <td>Non-payment of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=567<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(567); ?></a></td>
  </tr>
  <tr>
    <td width="282">Non Provision of <br>
      financial statements/information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=568<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(568); ?></a></td>
  </tr>
  <tr>
    <td>Process/Form    pending at CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=569<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(569); ?></a></td>
  </tr>
  <tr>
    <td>Non    appointment of auditors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=570<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(570); ?></a></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=571<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(571); ?></a></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=572<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(572); ?></a></td>
  </tr>
  <tr>
   <td colspan="3">Not Categorized</td>
    <td><a href="tickets.php?a=search&deptId=<?php echo $dept_id; ?>&topicId=43<?php echo $date_range; ?>"><?php echo get_complaints_by_topic_id(43); ?></a></td>
  </tr>
  <tr>
   <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></a></td>
  </tr>
  </tbody>
</table>
      
    </div>
  </div>
</div>
<?php }


//if($_POST['dept_id']=='' && !$thisstaff->isAdmin())
//{
$sql_dept="SELECT * FROM `sdms_department` WHERE 1 ".$dept_add."";
//}else{
//$sql_dept="SELECT * FROM `sdms_department` WHERE 1 ";
//}
$res_dept=mysql_query($sql_dept);
$num_dept = mysql_num_rows($res_dept);
if($num_dept>0){
	$subnum_dept_comp = 0;
while($row_dept=mysql_fetch_array($res_dept)){
$num_dept_comp = 0;
$sql_dept_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND dept_id='".$row_dept['dept_id']."' ".$from_to_date."";
$res_dept_comp = mysql_query($sql_dept_comp);
$num_dept_comp = mysql_num_rows($res_dept_comp);

$primary_status .= "{ name: '".$row_dept['dept_name']."', y: ".$num_dept_comp.",
            drilldown: '".$row_dept['dept_name']."'},";

$sub_status .="{
			name: '".$row_dept['dept_name']."',
            id: '".$row_dept['dept_name']."',
            data: [ ";
			

$sql_comp_topic = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND dept_id='".$row_dept['dept_id']."' ".$from_to_date." group by topic_id";
$res_comp_topic = mysql_query($sql_comp_topic);
while($row_comp_topic = mysql_fetch_array($res_comp_topic)){
	
$sql_topic_data = "SELECT * FROM `sdms_help_topic` WHERE topic_id = '".$row_comp_topic['topic_id']."'";
$res_topic_data = mysql_query($sql_topic_data);
$row_topic_data = mysql_fetch_array($res_topic_data);

$sql_topic_pdata = "SELECT * FROM `sdms_help_topic` WHERE topic_id = '".$row_topic_data['topic_pid']."'";
$res_topic_pdata = mysql_query($sql_topic_pdata);
$row_topic_pdata = mysql_fetch_array($res_topic_pdata);
	
$sql_topic_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '0' AND topic_id='".$row_comp_topic['topic_id']."' AND dept_id='".$row_dept['dept_id']."' ".$from_to_date."";
$res_topic_comp = mysql_query($sql_topic_comp);
$num_topic_comp = mysql_num_rows($res_topic_comp);

//$sub_status .="['".$row_topic_pdata['topic'].'=>'.$row_topic_data['topic']."', ".$num_topic_comp."],";
if(strlen($row_topic_data['topic'])>60)
{
	$soon =  '....';
}else{
	$soon =  '';
}
$sub_status .="['".addslashes(substr($row_topic_data['topic'],0,60)).$soon."', ".$num_topic_comp."],";
}
$sub_status .="]
},
";	
}
}
 //echo $primary_status.'<br>'; 
 //echo $sub_status; 
?>
<div class="row-fluid">
            <div class="span12">
            <div class="head clearfix">
            <div class="isw-right_circle"></div>
            <h1>Pie charts</h1>
            </div>
            <div class="block">
            <div id="container" style="height:600px;"></div>
            </div>
            </div>
            <script type="text/javascript">
			// Create the chart
			var defaultTitle = "Nature of complaints";
			var drilldownTitle = "More about ";
            Highcharts.chart('container', {
            chart: {
            type: 'pie',
			events: {
			drilldown: function(e) {
			this.setTitle({ text: drilldownTitle + e.point.name });
			},
			drillup: function(e) {
			this.setTitle({ text: defaultTitle });
			}
			}
            },
			title: {
            text: defaultTitle
            },
            subtitle: {
            text: 'Click the slices to view categories.'
            },
			
            plotOptions: {
            series: {
            dataLabels: {
            enabled: true,
			format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
            }
            },
            tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> of total<br/>'
			},
            series: [{
            name: 'Status',
            colorByPoint: true,
            data: [<?php echo $primary_status; ?>]
            }],
            drilldown: {
            series: [<?php echo $sub_status;  ?>]
            }
            });
			</script>                        
    </div>
<div class="dr"><span></span></div>
</div>
</div>
