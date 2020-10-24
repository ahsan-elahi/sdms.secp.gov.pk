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
elseif(!$thisstaff->isAdmin() &&  $thisstaff->onChairman() == '1' && $_POST['dept_id']!='')
{
	/*$dept_add .= ' AND dept_id = '.$thisstaff->getDeptId().'';
	$dept_id = $thisstaff->getDeptId();
	$_POST['dept_id'] = $thisstaff->getDeptId();*/
	$dept_add .= ' AND dept_id = '.$_POST['dept_id'].'';
	$dept_id = $_POST['dept_id'];
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
}else{
$from_to_date ='';
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
<?php if($thisstaff->isAdmin() || $thisstaff->onChairman() == '1'){ ?>
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
    <td><?php echo get_complaints_by_deparment(); ?></td>
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
    <td><?php echo get_complaints_by_topic_id(109); ?></td>
  </tr>
  <tr>
    <td>Fraudulant entries, incorrect commission/charges</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(110); ?></td>
  </tr>
  <tr>
    <td>Nonprovision of statements/information</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(111); ?></td>
  </tr>
  <tr>
    <td>Trading the wrong scripts-not following the instructions of the client</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(112); ?></td>
  </tr>
  <tr>
    <td>Dealing with employees and clients (under the name of member/broker)</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(113); ?></td>
  </tr>
  <tr>
    <td>Holding, other member's account for dues of family members </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(114); ?></td>
  </tr>
  <tr>
    <td>Dispute with another investor</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(115); ?></td>
  </tr>
  <tr>
    <td>Private financing arrangements</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(116); ?></td>
  </tr>
  <tr>
    <td>Account Closure</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(117); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(118); ?></td>
  </tr>
  <tr>
    <td>Non-Active Broker</td>
    <td>Settlement of Claim</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(119); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(92); ?></td>
  </tr>
  <tr>
    <td rowspan="11">Capital    Issue Wing</td>
    <td rowspan="7">Share Registrar/Transfer    Agent</td>
    <td>Nonreceipt of    dividend/bonus shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(120); ?></td>
  </tr>
  <tr>
    <td>Non-verification of transfer deed</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(121); ?></td>
  </tr>
  <tr>
    <td>Non-transfer of shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(122); ?></td>
  </tr>
  <tr>
    <td>Excess/wrong deduction of witholding tax on dividend amount</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(123); ?></td>
  </tr>
  <tr>
    <td>No issuance of duplicate shares certificates</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(124); ?></td>
  </tr>
  <tr>
    <td>Noninduction of shares in CDS</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(125); ?></td>
  </tr>
  <tr>
    <td>Illegal transfer of shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(126); ?></td>
  </tr>
  <tr>
    <td>Debt security Trustee</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(94); ?></td>
  </tr>
  <tr>
    <td>Book Runner</td>
    <td>IPO related</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(127); ?></td>
  </tr>
  <tr>
    <td>Underwriters </td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(96); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(97); ?></td>
  </tr>
  <tr>
    <td rowspan="2">Take    Over Wing</td>
    <td>Non-disclosure of    substantial share holding/Control</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(98); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(99); ?></td>
  </tr>
  <tr>
    <td rowspan="3">Beneficial    Ownership Wing</td>
    <td>Technical Issues on    filing of returns </td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(100); ?></td>
  </tr>
  <tr>
    <td>Non-filing of disclosure of shareholding by directors of listed    companies </td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(101); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(102); ?></td>
  </tr>
  <tr>
    <td rowspan="5">SSED</td>
    <td rowspan="5">&nbsp;</td>
    <td>Market Rumours</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(721); ?></td>
  </tr>
  <tr>
    <td width="449">Abnormal Price movement as a <br>
      result of trading activity <br>
      in the listed scips </td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(722); ?></td>
  </tr>
  <tr>
    <td>Market abuses/manipulation</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(723); ?></td>
  </tr>
  <tr>
    <td>Insider Trading/Manipulation</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(724); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(727); ?></td>
  </tr>
  <tr>
    <td rowspan="8">PRDD</td>
    <td rowspan="8">&nbsp;</td>
    <td rowspan="3">Stock Exchange</td>
    <td>Pertainig to    suspended scrips</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(142); ?></td>
  </tr>
  <tr>
    <td>Listing Fees</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(143); ?></td>
  </tr>
  <tr>
    <td>Service Related Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(144); ?></td>
  </tr>
  <tr>
    <td rowspan="2">CDC</td>
    <td>Regarding CDS Service</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(145); ?></td>
  </tr>
  <tr>
    <td>Suspended scrips (Investor Account Services)</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(146); ?></td>
  </tr>
  <tr>
    <td rowspan="2">NCCPL</td>
    <td>CGT related    complaints </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(147); ?></td>
  </tr>
  <tr>
    <td>Service Related Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(150); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(141); ?></td>
  </tr>
  <tr>
    <td rowspan="5">CMD</td>
    <td rowspan="5">&nbsp;</td>
    <td>Unauthorized trades    executed by broker without permission</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(129); ?></td>
  </tr>
  <tr>
    <td>Delay in refund of money</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(131); ?></td>
  </tr>
  <tr>
    <td>Non-receipt of account trading statements/trade confirmations</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(132); ?></td>
  </tr>
  <tr>
    <td>Problem in execution of commodity/currency contract</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(133); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(134); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>e-Services</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(135); ?></td>
  </tr>
  <tr>
   <td colspan="5">Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
    <td colspan="5"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></td>
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
    <td><?php echo get_complaints_by_topic_id(68); ?></td>
  </tr>
  <tr>
    <td>Insurance Claim on Maturity</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(69); ?></td>
  </tr>
  <tr>
    <td>Nonpayment of Death Claim</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(70); ?></td>
  </tr>
  <tr>
    <td>Nonpayment of nonlife/general claims</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(71); ?></td>
  </tr>
  <tr>
    <td>Bancassurance</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(72); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(73); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>TOTAL</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1430); ?></td>
  </tr>
  <tr>
    <td rowspan="2">Shareholder</td>
    <td>Non-payment of    Dividend</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(74); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(75); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>TOTAL</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1430); ?></td>
  </tr>
  <tr>
    <td rowspan="2">Broker/Agents </td>
    <td>Brokerage Commission</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(77); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>TOTAL</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1430); ?></td>
  </tr>
  <tr>
    <td>Adjudication</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(60); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(61); ?></td>
  </tr>
  <tr>
    <td>Policy Wing</td>
    <td width="223">Product Related    Complaint/<br>
      Policy Documents/<br>
      Illustrations/<br>
      Proposals</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(); ?></td>
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
    <td><?php echo get_complaints_by_topic_id(); ?></td>
  </tr>
  <tr>
    <td width="223">Mismanagement by <br>
      Employees/BoD</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(); ?></td>
  </tr>
  <tr>
    <td>Insurance    Surveyor</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(65); ?></td>
  </tr>
  <tr>
    <td>Authorized    Surveying Officer</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(66); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(67); ?></td>
  </tr>
  <tr>
    <td rowspan="3">S&amp;ED</td>
    <td rowspan="3">Onsite Inspection</td>
    <td width="223">Observations    requiring <br>
      Inspection/Enquiry</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(); ?></td>
  </tr>
  <tr>
    <td width="223">Observations requiring <br>
      Investigations</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(83); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>e-Services</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(80); ?></td>
  </tr>
  <tr>
    <td colspan="5">Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
   <td colspan="5"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></td>
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
    <td><?php echo get_complaints_by_topic_id(393); ?></td>
  </tr>
  <tr>
    <td>Holding of Mandatory Meetings</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(394); ?></td>
  </tr>
  <tr>
    <td>Issues Pertaining to License</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(395); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(396); ?></td>
  </tr>
  <tr>
    <td rowspan="5">Mutual    Fund</td>
    <td>Dividend not received</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(397); ?></td>
  </tr>
  <tr>
    <td width="452">Issue and redemption of <br>
      units/shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td>Issues of sales load</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(399); ?></td>
  </tr>
  <tr>
    <td>Accounts of funds</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(400); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(401); ?></td>
  </tr>
  <tr>
    <td rowspan="5">Pension    Funds</td>
    <td>Dividend not received</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(402); ?></td>
  </tr>
  <tr>
    <td width="452">Issue and redemption of <br>
      units/shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(403); ?></td>
  </tr>
  <tr>
    <td>Charging of sales load</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(404); ?></td>
  </tr>
  <tr>
    <td>Accounts of funds</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(405); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(406); ?></td>
  </tr>
  <tr>
    <td rowspan="4">Investment    Advisor</td>
    <td width="452">Dispute    Regarding <br>
      Portfolio Agreement</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(407); ?></td>
  </tr>
  <tr>
    <td>Issues Related to License </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(408); ?></td>
  </tr>
  <tr>
    <td width="452">Issues regarding compliance with <br>
      regulatory framework</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(409); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(410); ?></td>
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
    <td><?php echo get_complaints_by_topic_id(411); ?></td>
  </tr>
  <tr>
    <td>Issues of share transfer</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(412); ?></td>
  </tr>
  <tr>
    <td>Claim of depositor</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(413); ?></td>
  </tr>
  <tr>
    <td width="452">Status of companies in <br>
      liquidation/winding up</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(414); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(415); ?></td>
  </tr>
  <tr>
    <td rowspan="7">Real    Estate Investment Trust (REIT)</td>
    <td rowspan="4">REIT Management Company </td>
    <td>Accounts of RMC</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(416); ?></td>
  </tr>
  <tr>
    <td>Holding of Mandatory Meetings</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(417); ?></td>
  </tr>
  <tr>
    <td>Issues Pertaining to License</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(418); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(419); ?></td>
  </tr>
  <tr>
    <td rowspan="3">REIT    Funds</td>
    <td>Units not received</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(420); ?></td>
  </tr>
  <tr>
    <td>Dividend not received</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(421); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(422); ?></td>
  </tr>
  <tr>
    <td rowspan="2">Private    Equity and Venture Capital Fund</td>
    <td width="332">Private    Fund <br>
      Managament Company</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(374); ?></td>

  </tr>
  <tr>
    <td width="332">Private Equity Fund/<br>
      Venture Capital Fund/<br>
      Alternate Fund</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(375); ?></td>
  </tr>
  <tr>
    <td rowspan="7">Modaraba</td>
    <td rowspan="2" width="332">Modaraba Management <br>
      Companies</td>
    <td>Holding of Mandatory    Meetings</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(423); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(424); ?></td>
  </tr>
  <tr>
    <td rowspan="5">Modaraba</td>
    <td>Dividend not received</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(425); ?></td>
  </tr>
  <tr>
    <td width="452">Issues of <br>
      Modaraba Certificates transfer</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(426); ?></td>
  </tr>
  <tr>
    <td width="452">Status of companies in <br>
      liquidation/winding up</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(427); ?></td>
  </tr>
  <tr>
    <td width="452">Mismanagement by the <br>
      BOD/Management</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(428); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(429); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(379); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(380); ?></td>
  </tr>
  <tr>
    <td rowspan="47">South</td>
    <td rowspan="22">Offsite Wing I</td>
    <td rowspan="6" width="332">Asset Management Companies (AMC)/<br>
      Mutual Funds</td>
    <td>Dividend not declared/calculation of    dividend </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(430); ?></td>
  </tr>
  <tr>
    <td>Observations pertaining to clients account statements </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(431); ?></td>
  </tr>
  <tr>
    <td>Tax deductions</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(432); ?></td>
  </tr>
  <tr>
    <td>Observations in the periodic accounts</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(433); ?></td>
  </tr>
  <tr>
    <td>Mismanagement by employees/BOD</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(434); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(435); ?></td>
  </tr>
  <tr>
    <td rowspan="5">Investment Advisors</td>
    <td>Observations    pertaining to clients account statements </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(436); ?></td>
  </tr>
  <tr>
    <td>Tax deductions</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(437); ?></td>
  </tr>
  <tr>
    <td>Observations in the periodic accounts</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(438); ?></td>
  </tr>
  <tr>
    <td>Mismanagement by employees/BOD</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(439); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(440); ?></td>
  </tr>
  <tr>
    <td rowspan="5">Pension Funds</td>
    <td>Observations    pertaining to clients account statements </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(442); ?></td>
  </tr>
  <tr>
    <td>Tax deductions</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(443); ?></td>
  </tr>
  <tr>
    <td>Observations in the periodic accounts</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(444); ?></td>
  </tr>
  <tr>
    <td>Mismanagement by employees/BOD</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(445); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(446); ?></td>
  </tr>
  <tr>
    <td rowspan="6">REITs</td>
    <td>Dividend not    declared/calculation of dividend </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(447); ?></td>
  </tr>
  <tr>
    <td>Observations pertaining to clients account statements </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(448); ?></td>
  </tr>
  <tr>
    <td>Tax deductions</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(449); ?></td>
  </tr>
  <tr>
    <td>Observations in the periodic accounts</td>

    <td></td>
    <td><?php echo get_complaints_by_topic_id(450); ?></td>
  </tr>
  <tr>
    <td>Mismanagement by employees/BOD</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(451); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(452); ?></td>
  </tr>
  <tr>
    <td rowspan="17">Offsite Wing II</td>
    <td rowspan="6">Leasing Companies </td>
    <td>Dividend not    declared/calculation of dividend/dividend not received </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(453); ?></td>
  </tr>
  <tr>
    <td>Tax deductions</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(454); ?></td>
  </tr>
  <tr>
    <td>Observations in the periodic accounts</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(455); ?></td>
  </tr>
  <tr>
    <td>Mismanagement by employees/BOD</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(456); ?></td>
  </tr>
  <tr>
    <td>Non issuance of NOCs after payment of lease/loans</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(457); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(458); ?></td>
  </tr>
  <tr>
    <td rowspan="6">Investment Banks/MicroFinance NBFCs</td>
    <td>Dividend not    declared/calculation of dividend/dividend not received </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(459); ?></td>
  </tr>
  <tr>
    <td>Tax deductions</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(460); ?></td>
  </tr>
  <tr>
    <td>Observations in the periodic accounts</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(461); ?></td>
  </tr>
  <tr>
    <td>Mismanagement by employees/BOD</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(462); ?></td>
  </tr>
  <tr>
    <td>Non issuance of NOCs after payment of lease/loans</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(463); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(464); ?></td>
  </tr>
  <tr>
    <td rowspan="5" width="332">Modaraba Management    Companies/<br>
      Modarabas</td>
    <td>Dividend not    declared/calculation of dividend/dividend not received </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(744); ?></td>
  </tr>
  <tr>
    <td>Observations in the periodic accounts</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(466); ?></td>
  </tr>
  <tr>
    <td>Mismanagement by employees/BOD</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(467); ?></td>
  </tr>
  <tr>
    <td>Non issuance of NOCs after payment of lease/loans</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(468); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(469); ?></td>
  </tr>
  <tr>
    <td rowspan="6">Onsite    Inspection</td>
    <td rowspan="3" width="332">Asset    Management Companies/Mutual Funds/<br>
      Investment Advisors/Pension Funds/REITs</td>
    <td>Observations in the    periodic accounts requiring inspection/inquiry</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(471); ?></td>
  </tr>
  <tr>
    <td>Observations requiring investigations</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(472); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(473); ?></td>
  </tr>
  <tr>
    <td rowspan="3" width="332">Leasing Invetsment Bank/<br>
      MicroFinance NBFCs and Modarabs</td>
    <td>Observations in the    periodic accounts requiring inspection/inquiry</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(474); ?></td>
  </tr>
  <tr>
    <td>Observations requiring investigations</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(475); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(476); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="332">eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(391); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(392); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>Enquiries</td>
    <td></td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>Nature</td>
    <td>Sub Nature</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td rowspan="12">AMC Wing</td>
    <td>Opening of investor account</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>status of AMC/Investment advisor</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td width="332">Status of companies under    liquidation/winding up</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>Licensing Fee</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td width="332">clarification on Regulatory    framework</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>Licensing and incorporation procedure of AMC</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td width="332">information about category of    fund/structure of fund</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>website of AMC</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>Information about financial statements</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>Dividend not received</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>Tax benefits</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>Others</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td rowspan="7">Non Banking Finance Companies (NBFC)</td>
    <td width="332">Claim of depositor</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td width="332">Status of companies in    liquidation / winding up</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td width="332">Issue of share transfer</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td width="332">Observations in periodic account</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td width="332">Tax deduction</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td width="332">others</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td width="332">&nbsp;</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td rowspan="6">Real Estate Investment Trust (REIT)</td>
    <td rowspan="2">Dividend not received</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>Tax benefits</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>Units not received</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>Status of license of RMCs</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>others</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td rowspan="6">Voluntary Pension Schemes</td>
    <td>Queries about sub-funds of pension funds</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>Status of registration of Pension Fund Manager (PFM)</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>Benefits of investments</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>Expected return on pension funds</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>Queries about tax on withdrawal</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>others</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>Private Equity and Venture Capital Fund</td>
    <td width="332">Others</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td rowspan="7">Modaraba</td>
    <td rowspan="2" width="332">Issues of Modaraba certificates transfer</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>Status of companies in liquidation / winding up</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>Inquiry about dividend</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>inquiry about shares</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td width="332">Non issuance of NOCs after    payment of lease/ loans</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>others</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>Laws/Acts/Ordinances/Bill/Rules </td>
    <td>clarification on Regulatory framework</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1430); ?></td>
  </tr>
  <tr>
    <td></td>
    <td>Other</td>
    <td>&nbsp;</td>
    <td width="452"></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1430); ?></td>
  </tr>
  <tr>
    <td colspan="5">Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
    <td colspan="5"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></td>
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
    <td><?php echo get_complaints_by_topic_id(1430); ?></td>
  </tr>
  <tr>
    <td rowspan="2" width="323">Company Name Reservation</td>
    <td width="323">Attachment</td>
    <td>&nbsp;</td>
    <td><?php echo get_complaints_by_topic_id(788); ?></td>
  </tr>
  <tr>
    <td width="323">Unable to continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(789); ?></td>
  </tr>
  <tr>
    <td rowspan="6" width="323">Company Incorporation</td>
    <td>Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(790); ?></td>
  </tr>
  <tr>
    <td width="323">Unable to continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(791); ?></td>
  </tr>
  <tr>
    <td width="323">Manage Company Users </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(792); ?></td>
  </tr>
  <tr>
    <td width="323">Challan related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(793); ?></td>
  </tr>
  <tr>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(794); ?></td>
  </tr>
  <tr>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(795); ?></td>
  </tr>
  <tr>
    <td width="323">Change of Company Name</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(796); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(797); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(798); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(799); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(800); ?></td>
  </tr>
  <tr>
    <td width="323">Change of Company Address</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(801); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(802); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(803); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(804); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(805); ?></td>
  </tr>
  <tr>
    <td width="323">Companies Easy Exit Scheme (CEER)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id() ?></td>
  </tr>
  <tr>
    <td width="323">Change of Company Address (different province)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(812); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(813); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(814); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(815); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(816); ?></td>
  </tr>
  <tr>
    <td width="323">Change of Company Status (Private to Public)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(817); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(818); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(819); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(820); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(821); ?></td>
  </tr>
  <tr>
    <td width="323">Change of Company Status (Public to Private)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(822); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(823); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(824); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(825); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(826); ?></td>
  </tr>
  <tr>
    <td width="323">Change of Company Status (Private to Single Member)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(827); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(828); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(829); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(830); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(831); ?></td>
  </tr>
  <tr>
    <td width="323">Change of Company Status (Single Member to Private)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(832); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(833); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(834); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(835); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(836); ?></td>
  </tr>
  <tr>
    <td width="323">Change in Company Objects</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(838); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(839); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(840); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(841); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(842); ?></td>
  </tr>
  <tr>
    <td width="323">Filing of Statutory Return</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(845); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(846); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(847); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(848); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(849); ?></td>
  </tr>
  <tr>
    <td width="323">Miscellaneous</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(850); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(851); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(852); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(853); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(854); ?></td>
  </tr>
  <tr>
    <td width="323">Certified True Copy</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(855); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(856); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(857); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(858); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Dropdown Menu</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(859); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(860); ?></td>
  </tr>
  <tr>
    <td width="323">Penalty</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(861); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(862); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(863); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(864); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(865); ?></td>
  </tr>
  <tr>
    <td width="323">Inspection</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(866); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(867); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(868); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(869); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(870); ?></td>
  </tr>
  <tr>
    <td width="323">Miscellaneous(Without Payment)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(872); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(873); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(874); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(875); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(876); ?></td>
  </tr>
  <tr>
    <td width="323">Filing of Statutory Return (Multiple Forms)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(877); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(878); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(879); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(880); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(881); ?></td>
  </tr>
  <tr>
    <td width="323">Foreign Company Incorporation</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(882); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(883); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(884); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(885); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(886); ?></td>
  </tr>
  <tr>
    <td width="323">Change of Registered / Principal Office of Foreign Company (In    Country of Origin)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(888); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(889); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(890); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(891); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(892); ?></td>
  </tr>
  <tr>
    <td width="323">Change in Particulars of Persons (Directors/Chief    Executives/Secretaries)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(893); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(894); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(895); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(896); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(897); ?></td>
  </tr>
  <tr>
    <td width="323">Change in Foreign Company Charter /Statute /Memorandum and    Article of Association)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(898); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(899); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(900); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(901); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(935); ?></td>
  </tr>
  <tr>
    <td width="323">Establishment of Foreign Company Business Places and Submission    of Accounts</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(902); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(936); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(937); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(968); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(939); ?></td>
  </tr>
  <tr>
    <td width="323">Foreign Company Wind Up (Ceasing)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(940); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(941); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(942); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(943); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(944); ?></td>
  </tr>
  <tr>
    <td width="323">Change In Foreign Company Place of Business in Pak</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(945); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(946); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(947); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(948); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(949); ?></td>
  </tr>
  <tr>
    <td width="323">Filing of Returns-NBFC</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(950); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(951); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(952); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(953); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(954); ?></td>
  </tr>
  <tr>
    <td width="323">Change in Particulars of Persons (Principal Officer)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(955); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(956); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(957); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(958); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(959); ?></td>
  </tr>
  <tr>
    <td width="323">Change in Particulars of Persons (Authorized Person)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(960); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(961); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(962); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(963); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(964); ?></td>
  </tr>
  <tr>
    <td width="323">Form 29 - Multiple</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(965); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(966); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(967); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(968); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(969); ?></td>
  </tr>
  <tr>
    <td width="323">Appointment/Change of Company Officers</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(970); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(971); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(972); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(973); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(974); ?></td>
  </tr>
  <tr>
    <td width="323">Appointment/Change of Director</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(975); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(976); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(977); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(978); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(979); ?></td>
  </tr>
  <tr>
    <td width="323">Appointment/Change of CEO</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(984); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(983); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(982); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(981); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(980); ?></td>
  </tr>
  <tr>
    <td width="323">Appointment/Change of Other Officers</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(985); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(986); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(987); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(988); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(989); ?></td>
  </tr>
  <tr>
    <td width="323">Appointment/Change of All Officers</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(990); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(991); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(992); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(993); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(994); ?></td>
  </tr>
  <tr>
    <td width="323">Filing of Form 27</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(995); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(996); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(997); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(998); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(999); ?></td>
  </tr>
  <tr>
    <td width="323">Filing of Form 28</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1000); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1001); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1002); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1003); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1004); ?></td>
  </tr>
  <tr>
    <td width="323">Filing of Form 29</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1005); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1006); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1007); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1008); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1009); ?></td>
  </tr>
  <tr>
    <td width="323">Filing of Form 27_28</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1010); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1011); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1012); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1013); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1014); ?></td>
  </tr>
  <tr>
    <td width="323">Filing of Form 28_29</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1015); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1016); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1017); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1018); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1019); ?></td>
  </tr>
  <tr>
    <td width="323">Insurance Surveyor Licensing</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1020); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1021); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1022); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1023); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1024); ?></td>
  </tr>
  <tr>
    <td width="323">Licensing of Insurance Surveyors Companies</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1025); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1026); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1027); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1028); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1029); ?></td>
  </tr>
  <tr>
    <td width="323">Renewal of Insurance Surveyors Companies License</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1030); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1031); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1032); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1033); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1034); ?></td>
  </tr>
  <tr>
    <td width="323">Licensing of Authorized Surveying Officer</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1035); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1036); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1037); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1038); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1039); ?></td>
  </tr>
  <tr>
    <td width="323">Renewal of Insurance Authorized Surveying Officer</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1040); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1041); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1042); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1043); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1044); ?></td>
  </tr>
  <tr>
    <td width="323">Filing of Returns-FRS (SMBH)</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1045); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1046); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1047); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1048); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1049); ?></td>
  </tr>
  <tr>
    <td width="323">Filing of Returns - Insurance</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1050); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1051); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1052); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1053); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1054); ?></td>
  </tr>
  <tr>
    <td width="323">Annual Return by Listed Companies SMD-BO-107</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1055); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1056); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1057); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1058); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1059); ?></td>
  </tr>
  <tr>
    <td width="323">Reporting of Features of Debt Instrument</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1060); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1061); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1062); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1063); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1064); ?></td>
  </tr>
  <tr>
    <td width="323">Periodic Reporting of Redemption and Status of Covenant    Compliance</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1065); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1066); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1067); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1068); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1069); ?></td>
  </tr>
  <tr>
    <td width="323">Filing of Form 4 (BO) under u/s 102 of Securities Act, 2015</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1070); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1071); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1072); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1073); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1074); ?></td>
  </tr>
  <tr>
    <td>User    Registration and PIN Generation</td>
    <td width="323">Unable to    generate </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1075); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="323">Inactive/Invalid</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1076); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="323">Forgot password </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1077); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="323">PIN not received</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1078); ?></td>
  </tr>
  <tr>
    <td>Restoration of Company</td>
    <td width="323">Attachment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="323">Unable to    continue </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="323">Signing Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="323">Online payment</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="323">Challan    related </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(); ?></td>
  </tr>
  <tr>
    <td>Mortgage Regstration etc.</td>
    <td width="323">&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(); ?></td>
  </tr>
  <tr>
    <td>Process Unavailable</td>
    <td width="323">&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1086); ?></td>
  </tr>
  <tr>
    <td>Other</td>
    <td></td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1087); ?></td>
  </tr>
  <tr>
    <td colspan="3">Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></td>
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
    <td><?php echo get_complaints_by_topic_id(152); ?></td>
  </tr>
  <tr>
    <td>Fraudulant    activities of corporate entities</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(153); ?></td>
  </tr>
  <tr>
    <td>Investigation    against the company</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(154); ?></td>
  </tr>
  <tr>
    <td width="323">Complaints against <br>
      applications, approvals, appeals etc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(155); ?></td>
  </tr>
  <tr>
    <td width="323">Complaints against public sector companies for <br>
      noncompliance of public sector companies <br>
      (corporate governance) rules, 2013</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(157); ?></td>
  </tr>
  <tr>
    <td width="323">Complaints against housing and <br>
      real estate companies registered <br>
      with the commission</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(158); ?></td>
  </tr>
  <tr>
    <td rowspan="9" width="323">Complaints pertaining to <br>
      not for profit companies (u/S 42 of <br>
      the Companies Ordinance 1984</td>
    <td>Issuance of license</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(743); ?></td>
  </tr>
  <tr>
    <td>Renewal of license</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(166); ?></td>
  </tr>
  <tr>
    <td>Quittal/admission of member</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(167); ?></td>
  </tr>
  <tr>
    <td>Alteration in memorandum and articles </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(168); ?></td>
  </tr>
  <tr>
    <td>Change of name</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(169); ?></td>
  </tr>
  <tr>
    <td width="304">Extension in time period for incorporation of <br>
      the company after grant of license </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(170); ?></td>
  </tr>
  <tr>
    <td width="304">Violation of licensing conditions of <br>
      not for profit companies</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(171); ?></td>
  </tr>
  <tr>
    <td>Illegal activities of not for profit companies</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(172); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(173); ?></td>
  </tr>
  <tr>
    <td width="323">Irrelevant, including operatonal matters of <br>
      the companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(161); ?></td>
  </tr>
  <tr>
    <td>Provident Fund    related complaints</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(162); ?></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(163); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(164); ?></td>
  </tr>
  <tr>
   <td colspan="3">Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
   <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></td>
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
    <div class="block-fluid ">
      
        
        
       
        <table cellpadding="0" cellspacing="0" width="100%" class="table">
        

  <col width="83">
  <col width="377">
  <col width="515">
  <col width="86">
  <col width="172">
  <thead>
  <tr>
    <td width="83">Primary <br>
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
    <td><?php echo get_complaints_by_topic_id(692); ?></td>
  </tr>
  <tr>
    <td>Late dispatch of Shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(693); ?></td>
  </tr>
  <tr>
    <td>Refund to rejected applicants in case of public offers</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(694); ?></td>
  </tr>
  <tr>
    <td>Duplicate issue of Shares </td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td rowspan="5">Refusal of Transfer of Shares</td>
    <td>Refusal of tranfer of    purchased shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(695); ?></td>
  </tr>
  <tr>
    <td>Refusal of Transfer of shares to successor in interest </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(696); ?></td>
  </tr>
  <tr>
    <td>Refusal of nominee of deceased member or legal represetative</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(697); ?></td>
  </tr>
  <tr>
    <td>Non Issue of Notice for refusal of transfer</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(698); ?></td>
  </tr>
  <tr>
    <td>Appeal against refusal for registration of transfer </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(699); ?></td>
  </tr>
  <tr>
    <td>Rectification of shares register </td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(675); ?></td>
  </tr>
  <tr>
    <td>Verification of signatures/tansfer deed</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(676); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(677); ?></td>
  </tr>
  <tr>
    <td rowspan="5">Dividend </td>
    <td>Declaration of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(678); ?></td>
  </tr>
  <tr>
    <td width="377">non-payment/receipt f dividend within <br>
      stipulated period</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(679); ?></td>
  </tr>
  <tr>
    <td>Issuance of duplicate dividend warrants</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(680); ?></td>
  </tr>
  <tr>
    <td width="377">Non=credit of dividend <br>
      as per bank mandate</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(681); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(682); ?></td>
  </tr>
  <tr>
    <td rowspan="5">Accounts</td>
    <td width="377">Non-receipt    of accounts <br>
      (annually and quarterly)</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(746); ?></td>
  </tr>
  <tr>
    <td width="377">Non provision of copies of minutes/<br>
      extracts/other information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(684); ?></td>
  </tr>
  <tr>
    <td>Nonreceipt of notices of AGM/EOGM</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(685); ?></td>
  </tr>
  <tr>
    <td>Disclosure in director's report/auditors report</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(686); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(687); ?></td>
  </tr>
  <tr>
    <td rowspan="4">Management</td>
    <td width="377">Fraud/Mis-management    by <br>
      management of company</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(688); ?></td>
  </tr>
  <tr>
    <td>Siphoning/misuse of company assets </td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(689); ?></td>
  </tr>
  <tr>
    <td>Dispute between directors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(690); ?></td>
  </tr>
  <tr>
    <td>Other Issues</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(691); ?></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(670); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(671); ?></td>
  </tr>
  <tr>
    <td colspan="4">Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
    <td colspan="4"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></td>
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
    <td><?php echo get_complaints_by_topic_id(752); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Information Update Requests </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(753); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>User ID and Password </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(754); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td>Server not Responding </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(755); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td>Misc.</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(756); ?></td>
  </tr>
  <tr>
    <td>Other <!--751--></td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(751); ?></td>
  </tr>
     
  <tr>
    <td colspan="3">Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></td>
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
    <td><?php echo get_complaints_by_topic_id(253); ?></td>
  </tr>
  <tr>
    <td>Data    updation/Rectification</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(254); ?></td>
  </tr>
  <tr>
    <td>Delay in    deciding name availability</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(255); ?></td>
  </tr>
  <tr>
    <td>Delay in    issuance of CTCs</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(256); ?></td>
  </tr>
  <tr>
    <td>Delay in    registration of companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(257); ?></td>
  </tr>
  <tr>
    <td>Dispute    among/with the management</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(258); ?></td>
  </tr>
  <tr>
    <td rowspan="2">Transfer    of shares</td>
    <td>Nontransfer of shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(269); ?></td>
  </tr>
  <tr>
    <td>Illegal Transfer of Shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(270); ?></td>
  </tr>
  <tr>
    <td>Irregularities    in further allottment of shares</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(260); ?></td>
  </tr>
  <tr>
    <td rowspan="3">Election    of directors</td>
    <td>Improper election of    directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(271); ?></td>
  </tr>
  <tr>
    <td>Non-holding of election of directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(272); ?></td>
  </tr>
  <tr>
    <td>Delay in election of directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(273); ?></td>
  </tr>
  <tr>
    <td rowspan="5">Irregularities    in holding meetings</td>
    <td>AGM</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(274); ?></td>
  </tr>
  <tr>
    <td>EOGM</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(275); ?></td>
  </tr>
  <tr>
    <td>STATUTORY</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(276); ?></td>
  </tr>
  <tr>
    <td>BOARD</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(277); ?></td>
  </tr>
  <tr>
    <td width="242">Non-issuance of notices for <br>
      holding member's meetings</td>
    <td width="22"></td>
    <td><?php echo get_complaints_by_topic_id(278); ?></td>
  </tr>
  <tr>
    <td>Non-payment of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(263); ?></td>
  </tr>
  <tr>
    <td width="282">Non Provision of <br>
      financial statements/information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td>Process/Form    pending at CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(265); ?></td>
  </tr>
  <tr>
    <td>Non    appointment of auditors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(266); ?></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(267); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(268); ?></td>
  </tr>
  <tr>
    <td colspan="3">Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></td>
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
    <td><?php echo get_complaints_by_topic_id(227); ?></td>
  </tr>
  <tr>
    <td>Data    updation/Rectification</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(228); ?></td>
  </tr>
  <tr>
    <td>Delay in    deciding name availability</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(229); ?></td>
  </tr>
  <tr>
    <td>Delay in    issuance of CTCs</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(230); ?></td>
  </tr>
  <tr>
    <td>Delay in    registration of companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(231); ?></td>
  </tr>
  <tr>
    <td>Dispute    among/with the management</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(232); ?></td>
  </tr>
  <tr>
    <td rowspan="2">Transfer    of shares</td>
    <td>Nontransfer of shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(243); ?></td>
  </tr>
  <tr>
    <td>Illegal Transfer of Shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(244); ?></td>
  </tr>
  <tr>
    <td>Irregularities    in further allottment of shares</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(234); ?></td>
  </tr>
  <tr>
    <td rowspan="3">Election    of directors</td>
    <td>Improper election of    directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(245); ?></td>
  </tr>
  <tr>
    <td>Non-holding of election of directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(246); ?></td>
  </tr>
  <tr>
    <td>Delay in election of directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(247); ?></td>
  </tr>
  <tr>
    <td rowspan="5">Irregularities    in holding meetings</td>
    <td>AGM</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(248); ?></td>
  </tr>
  <tr>
    <td>EOGM</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(249); ?></td>
  </tr>
  <tr>
    <td>STATUTORY</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(250); ?></td>
  </tr>
  <tr>
    <td>BOARD</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(251); ?></td>
  </tr>
  <tr>
    <td width="242">Non-issuance of notices for <br>
      holding member's meetings</td>
    <td width="22"></td>
    <td><?php echo get_complaints_by_topic_id(252); ?></td>
  </tr>
  <tr>
    <td>Non-payment of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(237); ?></td>
  </tr>
  <tr>
    <td width="282">Non Provision of <br>
      financial statements/information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(238); ?></td>
  </tr>
  <tr>
    <td>Process/Form    pending at CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(239); ?></td>
  </tr>
  <tr>
    <td>Non    appointment of auditors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(240); ?></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(241); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(242); ?></td>
  </tr>
  <tr>
   <td colspan="3">Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></td>
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
    <td><?php echo get_complaints_by_topic_id(279); ?></td>
  </tr>
  <tr>
    <td>Data    updation/Rectification</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(280); ?></td>
  </tr>
  <tr>
    <td>Delay in    deciding name availability</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(281); ?></td>
  </tr>
  <tr>
    <td>Delay in    issuance of CTCs</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(282); ?></td>
  </tr>
  <tr>
    <td>Delay in    registration of companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(283); ?></td>
  </tr>
  <tr>
    <td>Dispute    among/with the management</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(284); ?></td>
  </tr>
  <tr>
    <td rowspan="2">Transfer    of shares</td>
    <td>Nontransfer of shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(295); ?></td>
  </tr>
  <tr>
    <td>Illegal Transfer of Shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(296); ?></td>
  </tr>
  <tr>
    <td>Irregularities    in further allottment of shares</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(286); ?></td>
  </tr>
  <tr>
    <td rowspan="3">Election    of directors</td>
    <td>Improper election of    directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(297); ?></td>
  </tr>
  <tr>
    <td>Non-holding of election of directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(298); ?></td>
  </tr>
  <tr>
    <td>Delay in election of directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(299); ?></td>
  </tr>
  <tr>
    <td rowspan="5">Irregularities    in holding meetings</td>
    <td>AGM</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(300); ?></td>
  </tr>
  <tr>
    <td>EOGM</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(301); ?></td>
  </tr>
  <tr>
    <td>STATUTORY</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(302); ?></td>
  </tr>
  <tr>
    <td>BOARD</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(303); ?></td>
  </tr>
  <tr>
    <td width="242">Non-issuance of notices for <br>
      holding member's meetings</td>
    <td width="22"></td>
    <td><?php echo get_complaints_by_topic_id(304); ?></td>
  </tr>
  <tr>
    <td>Non-payment of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(289); ?></td>
  </tr>
  <tr>
    <td width="282">Non Provision of <br>
      financial statements/information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(290); ?></td>
  </tr>
  <tr>
    <td>Process/Form    pending at CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(291); ?></td>
  </tr>
  <tr>
    <td>Non    appointment of auditors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(292); ?></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(293); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(294); ?></td>
  </tr>
  <tr>
    <td colspan="3">Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></td>
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
    <td><?php echo get_complaints_by_topic_id(253); ?></td>
  </tr>
  <tr>
    <td>Data    updation/Rectification</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(254); ?></td>
  </tr>
  <tr>
    <td>Delay in    deciding name availability</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(255); ?></td>
  </tr>
  <tr>
    <td>Delay in    issuance of CTCs</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(256); ?></td>
  </tr>
  <tr>
    <td>Delay in    registration of companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(257); ?></td>
  </tr>
  <tr>
    <td>Dispute    among/with the management</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(258); ?></td>
  </tr>
  <tr>
    <td rowspan="2">Transfer    of shares</td>
    <td>Nontransfer of shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(269); ?></td>
  </tr>
  <tr>
    <td>Illegal Transfer of Shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(270); ?></td>
  </tr>
  <tr>
    <td>Irregularities    in further allottment of shares</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(260); ?></td>
  </tr>
  <tr>
    <td rowspan="3">Election    of directors</td>
    <td>Improper election of    directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(271); ?></td>
  </tr>
  <tr>
    <td>Non-holding of election of directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(272); ?></td>
  </tr>
  <tr>
    <td>Delay in election of directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(273); ?></td>
  </tr>
  <tr>
    <td rowspan="5">Irregularities    in holding meetings</td>
    <td>AGM</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(274); ?></td>
  </tr>
  <tr>
    <td>EOGM</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(275); ?></td>
  </tr>
  <tr>
    <td>STATUTORY</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(276); ?></td>
  </tr>
  <tr>
    <td>BOARD</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(277); ?></td>
  </tr>
  <tr>
    <td width="242">Non-issuance of notices for <br>
      holding member's meetings</td>
    <td width="22"></td>
    <td><?php echo get_complaints_by_topic_id(278); ?></td>
  </tr>
  <tr>
    <td>Non-payment of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(263); ?></td>
  </tr>
  <tr>
    <td width="282">Non Provision of <br>
      financial statements/information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td>Process/Form    pending at CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(265); ?></td>
  </tr>
  <tr>
    <td>Non    appointment of auditors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(266); ?></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(267); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(268); ?></td>
  </tr>
  <tr>
   <td colspan="3">Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
   <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></td>
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
    <td><?php echo get_complaints_by_topic_id(174); ?></td>
  </tr>
  <tr>
    <td>Data    updation/Rectification</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(175); ?></td>
  </tr>
  <tr>
    <td>Delay in    deciding name availability</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(176); ?></td>
  </tr>
  <tr>
    <td>Delay in    issuance of CTCs</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(177); ?></td>
  </tr>
  <tr>
    <td>Delay in    registration of companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(178); ?></td>
  </tr>
  <tr>
    <td>Dispute    among/with the management</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(179); ?></td>
  </tr>
  <tr>
    <td rowspan="2">Transfer    of shares</td>
    <td>Nontransfer of shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(190); ?></td>
  </tr>
  <tr>
    <td>Illegal Transfer of Shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(191); ?></td>
  </tr>
  <tr>
    <td>Irregularities    in further allottment of shares</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(181); ?></td>
  </tr>
  <tr>
    <td rowspan="3">Election    of directors</td>
    <td>Improper election of    directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(192); ?></td>
  </tr>
  <tr>
    <td>Non-holding of election of directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(193); ?></td>
  </tr>
  <tr>
    <td>Delay in election of directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(194); ?></td>
  </tr>
  <tr>
    <td rowspan="5">Irregularities    in holding meetings</td>
    <td>AGM</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(195); ?></td>
  </tr>
  <tr>
    <td>EOGM</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(196); ?></td>
  </tr>
  <tr>
    <td>STATUTORY</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(197); ?></td>
  </tr>
  <tr>
    <td>BOARD</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(198); ?></td>
  </tr>
  <tr>
    <td width="242">Non-issuance of notices for <br>
      holding member's meetings</td>
    <td width="22"></td>
    <td><?php echo get_complaints_by_topic_id(199); ?></td>
  </tr>
  <tr>
    <td>Non-payment of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(184); ?></td>
  </tr>
  <tr>
    <td width="282">Non Provision of <br>
      financial statements/information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(185); ?></td>
  </tr>
  <tr>
    <td>Process/Form    pending at CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(186); ?></td>
  </tr>
  <tr>
    <td>Non    appointment of auditors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(187); ?></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(188); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(189); ?></td>
  </tr>
  <tr>
    <td colspan="3">Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></td>
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
    <td><?php echo get_complaints_by_topic_id(305); ?></td>
  </tr>
  <tr>
    <td>Data    updation/Rectification</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(306); ?></td>
  </tr>
  <tr>
    <td>Delay in    deciding name availability</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(477); ?></td>
  </tr>
  <tr>
    <td>Delay in    issuance of CTCs</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td>Delay in    registration of companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(479); ?></td>
  </tr>
  <tr>
    <td>Dispute    among/with the management</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(480); ?></td>
  </tr>
  <tr>
    <td rowspan="2">Transfer    of shares</td>
    <td>Nontransfer of shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(492); ?></td>
  </tr>
  <tr>
    <td>Illegal Transfer of Shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(493); ?></td>
  </tr>
  <tr>
    <td>Irregularities    in further allottment of shares</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(482); ?></td>
  </tr>
  <tr>
    <td rowspan="3">Election    of directors</td>
    <td>Improper election of    directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(494); ?></td>
  </tr>
  <tr>
    <td>Non-holding of election of directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(495); ?></td>
  </tr>
  <tr>
    <td>Delay in election of directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(496); ?></td>
  </tr>
  <tr>
    <td rowspan="5">Irregularities    in holding meetings</td>
    <td>AGM</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td>EOGM</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td>STATUTORY</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td>BOARD</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td width="242">Non-issuance of notices for <br>
      holding member's meetings</td>
    <td width="22"></td>
    <td><?php echo get_complaints_by_topic_id('?'); ?></td>
  </tr>
  <tr>
    <td>Non-payment of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(486); ?></td>
  </tr>
  <tr>
    <td width="282">Non Provision of <br>
      financial statements/information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(487); ?></td>
  </tr>
  <tr>
    <td>Process/Form    pending at CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(488); ?></td>
  </tr>
  <tr>
    <td>Non    appointment of auditors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(489); ?></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(490); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(491); ?></td>
  </tr>
  <tr>
    <td colspan="3">Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></td>
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
    <td><?php echo get_complaints_by_topic_id(200); ?></td>
  </tr>
  <tr>
    <td>Data    updation/Rectification</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(201); ?></td>
  </tr>
  <tr>
    <td>Delay in    deciding name availability</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(202); ?></td>
  </tr>
  <tr>
    <td>Delay in    issuance of CTCs</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(203); ?></td>
  </tr>
  <tr>
    <td>Delay in    registration of companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(204); ?></td>
  </tr>
  <tr>
    <td>Dispute    among/with the management</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(205); ?></td>
  </tr>
  <tr>
    <td rowspan="2">Transfer    of shares</td>
    <td>Nontransfer of shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(216); ?></td>
  </tr>
  <tr>
    <td>Illegal Transfer of Shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(217); ?></td>
  </tr>
  <tr>
    <td>Irregularities    in further allottment of shares</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(207); ?></td>
  </tr>
  <tr>
    <td rowspan="3">Election    of directors</td>
    <td>Improper election of    directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(218); ?></td>
  </tr>
  <tr>
    <td>Non-holding of election of directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(219); ?></td>
  </tr>
  <tr>
    <td>Delay in election of directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(220); ?></td>
  </tr>
  <tr>
    <td rowspan="5">Irregularities    in holding meetings</td>
    <td>AGM</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(222); ?></td>
  </tr>
  <tr>
    <td>EOGM</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(223); ?></td>
  </tr>
  <tr>
    <td>STATUTORY</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(224); ?></td>
  </tr>
  <tr>
    <td>BOARD</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(225); ?></td>
  </tr>
  <tr>
    <td width="242">Non-issuance of notices for <br>
      holding member's meetings</td>
    <td width="22"></td>
    <td><?php echo get_complaints_by_topic_id(226); ?></td>
  </tr>
  <tr>
    <td>Non-payment of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(210); ?></td>
  </tr>
  <tr>
    <td width="282">Non Provision of <br>
      financial statements/information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(211); ?></td>
  </tr>
  <tr>
    <td>Process/Form    pending at CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(212); ?></td>
  </tr>
  <tr>
    <td>Non    appointment of auditors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(213); ?></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(214); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(215); ?></td>
  </tr>
  <tr>
    <td colspan="3">Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></td>
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
    <td><?php echo get_complaints_by_topic_id(502); ?></td>
  </tr>
  <tr>
    <td>Data    updation/Rectification</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(503); ?></td>
  </tr>
  <tr>
    <td>Delay in    deciding name availability</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(504); ?></td>
  </tr>
  <tr>
    <td>Delay in    issuance of CTCs</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(506); ?></td>
  </tr>
  <tr>
    <td>Delay in    registration of companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(507); ?></td>
  </tr>
  <tr>
    <td>Dispute    among/with the management</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(508); ?></td>
  </tr>
  <tr>
    <td rowspan="2">Transfer    of shares</td>
    <td>Nontransfer of shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(519); ?></td>
  </tr>
  <tr>
    <td>Illegal Transfer of Shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(520); ?></td>
  </tr>
  <tr>
    <td>Irregularities    in further allottment of shares</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(510); ?></td>
  </tr>
  <tr>
    <td rowspan="3">Election    of directors</td>
    <td>Improper election of    directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(521); ?></td>
  </tr>
  <tr>
    <td>Non-holding of election of directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(522); ?></td>
  </tr>
  <tr>
    <td>Delay in election of directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(523); ?></td>
  </tr>
  <tr>
    <td rowspan="5">Irregularities    in holding meetings</td>
    <td>AGM</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(524); ?></td>
  </tr>
  <tr>
    <td>EOGM</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(525); ?></td>
  </tr>
  <tr>
    <td>STATUTORY</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(526); ?></td>
  </tr>
  <tr>
    <td>BOARD</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(527); ?></td>
  </tr>
  <tr>
    <td width="242">Non-issuance of notices for <br>
      holding member's meetings</td>
    <td width="22"></td>
    <td><?php echo get_complaints_by_topic_id(528); ?></td>
  </tr>
  <tr>
    <td>Non-payment of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(513); ?></td>
  </tr>
  <tr>
    <td width="282">Non Provision of <br>
      financial statements/information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(514); ?></td>
  </tr>
  <tr>
    <td>Process/Form    pending at CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(515); ?></td>
  </tr>
  <tr>
    <td>Non    appointment of auditors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(516); ?></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(517); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(518); ?></td>
  </tr>
  <tr>
    <td colspan="3">Not Categorized</td>
   
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
   <td colspan="3"><b>Total</b></td>
   
    <td><?php echo get_complaints_by_deparment(); ?></td>
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
    <td><?php echo get_complaints_by_topic_id(529); ?></td>
  </tr>
  <tr>
    <td>Data    updation/Rectification</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(530); ?></td>
  </tr>
  <tr>
    <td>Delay in    deciding name availability</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(531); ?></td>
  </tr>
  <tr>
    <td>Delay in    issuance of CTCs</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(532); ?></td>
  </tr>
  <tr>
    <td>Delay in    registration of companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(533); ?></td>
  </tr>
  <tr>
    <td>Dispute    among/with the management</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(534); ?></td>
  </tr>
  <tr>
    <td rowspan="2">Transfer    of shares</td>
    <td>Nontransfer of shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(545); ?></td>
  </tr>
  <tr>
    <td>Illegal Transfer of Shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(546); ?></td>
  </tr>
  <tr>
    <td>Irregularities    in further allottment of shares</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(536); ?></td>
  </tr>
  <tr>
    <td rowspan="3">Election    of directors</td>
    <td>Improper election of    directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(547); ?></td>
  </tr>
  <tr>
    <td>Non-holding of election of directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(548); ?></td>
  </tr>
  <tr>
    <td>Delay in election of directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(549); ?></td>
  </tr>
  <tr>
    <td rowspan="5">Irregularities    in holding meetings</td>
    <td>AGM</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(552); ?></td>
  </tr>
  <tr>
    <td>EOGM</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(553); ?></td>
  </tr>
  <tr>
    <td>STATUTORY</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(554); ?></td>
  </tr>
  <tr>
    <td>BOARD</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(555); ?></td>
  </tr>
  <tr>
    <td width="242">Non-issuance of notices for <br>
      holding member's meetings</td>
    <td width="22"></td>
    <td><?php echo get_complaints_by_topic_id(556); ?></td>
  </tr>
  <tr>
    <td>Non-payment of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(539); ?></td>
  </tr>
  <tr>
    <td width="282">Non Provision of <br>
      financial statements/information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(540); ?></td>
  </tr>
  <tr>
    <td>Process/Form    pending at CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(541); ?></td>
  </tr>
  <tr>
    <td>Non    appointment of auditors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(542); ?></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(543); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(544); ?></td>
  </tr>
  <tr>
   <td colspan="3">Not Categorized</td>
   
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
    <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></td>
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
    <td><?php echo get_complaints_by_topic_id(557); ?></td>
  </tr>
  <tr>
    <td>Data    updation/Rectification</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(558); ?></td>
  </tr>
  <tr>
    <td>Delay in    deciding name availability</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(559); ?></td>
  </tr>
  <tr>
    <td>Delay in    issuance of CTCs</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(560); ?></td>
  </tr>
  <tr>
    <td>Delay in    registration of companies</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(561); ?></td>
  </tr>
  <tr>
    <td>Dispute    among/with the management</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(562); ?></td>
  </tr>
  <tr>
    <td rowspan="2">Transfer    of shares</td>
    <td>Nontransfer of shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(573); ?></td>
  </tr>
  <tr>
    <td>Illegal Transfer of Shares</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(574); ?></td>
  </tr>
  <tr>
    <td>Irregularities    in further allottment of shares</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(564); ?></td>
  </tr>
  <tr>
    <td rowspan="3">Election    of directors</td>
    <td>Improper election of    directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(575); ?></td>
  </tr>
  <tr>
    <td>Non-holding of election of directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(576); ?></td>
  </tr>
  <tr>
    <td>Delay in election of directors</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(577); ?></td>
  </tr>
  <tr>
    <td rowspan="5">Irregularities    in holding meetings</td>
    <td>AGM</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(578); ?></td>
  </tr>
  <tr>
    <td>EOGM</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(579); ?></td>
  </tr>
  <tr>
    <td>STATUTORY</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(580); ?></td>
  </tr>
  <tr>
    <td>BOARD</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(581); ?></td>
  </tr>
  <tr>
    <td width="242">Non-issuance of notices for <br>
      holding member's meetings</td>
    <td width="22"></td>
    <td><?php echo get_complaints_by_topic_id(582); ?></td>
  </tr>
  <tr>
    <td>Non-payment of    dividend</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(567); ?></td>
  </tr>
  <tr>
    <td width="282">Non Provision of <br>
      financial statements/information</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(568); ?></td>
  </tr>
  <tr>
    <td>Process/Form    pending at CRO</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(569); ?></td>
  </tr>
  <tr>
    <td>Non    appointment of auditors</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(570); ?></td>
  </tr>
  <tr>
    <td>eServices</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(571); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(572); ?></td>
  </tr>
  <tr>
   <td colspan="3">Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
   <td colspan="3"><b>Total</b></td>
    <td><?php echo get_complaints_by_deparment(); ?></td>
  </tr>
  </tbody>
</table>
      
    </div>
  </div>
</div>
<?php }
?>
<div class="dr"><span></span></div>
</div>
</div>
