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
elseif(!$thisstaff->isAdmin() &&  $thisstaff->onChairman() == '1')
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
}else{
$from_to_date ='';
}
function get_complaints_by_topic_id($topic_id){
	global $dept_add;
		global $from_to_date;
	$sql_topic_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '1' AND topic_id='".$topic_id."' ".$dept_add." ".$from_to_date."";
$res_topic_comp = mysql_query($sql_topic_comp);
$num_topic_comp = mysql_num_rows($res_topic_comp);
return $num_topic_comp;
}
function get_complaints_by_deparment(){
	global $dept_add;
		global $from_to_date;
	$sql_topic_deprt = "SELECT * FROM `sdms_ticket` WHERE isquery = '1' ".$dept_add." ".$from_to_date."";
$res_topic_deprt = mysql_query($sql_topic_deprt);
$num_topic_deprt = mysql_num_rows($res_topic_deprt);
return $num_topic_deprt;
}

?>
`	..
<div class="page-header">
  <h1>Nature of queries<small>Summary</small></h1>
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
<?php if($thisstaff->isAdmin()){ ?>
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
<input type="text" name="from_date" id="Datepicker" required value="<?php echo $_POST['from_date']; ?>" >
</td>
<th width="20%" style="padding-top:12px;">To Date</th>
<td>
<input type="text" name="to_date" id="Datepicker1" required value="<?php echo $_POST['to_date']; ?>" >
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
      Queries</td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td>Technical Issues</td>
    <td>Filing issues </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1494); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Website Issues</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1495); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Other</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1496); ?></td>
  </tr>
  <tr>
    <td>Non-technical Issues</td>
    <td>Non-Jurisdiction</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1499); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Anonymous</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1500); ?></td>
  </tr>
  <tr>
    <td width="221">&nbsp;</td>
    <td>Challan Verification</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1501); ?></td>
  </tr>
   <tr>
    <td width="221">&nbsp;</td>
    <td>General Information</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1502); ?></td>
  </tr>
   <tr>
    <td width="221">&nbsp;</td>
    <td>JamaPunji/Investor Education Information</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1503); ?></td>
  </tr>
   <tr>
    <td width="221">&nbsp;</td>
    <td>Other</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1504); ?></td>
  </tr>
  <tr>
    <td>Misc.</td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1498); ?></td>
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
    <th width="98" height="44">Department </th>
    <th width="94">Total  <br>
      Queries</th>
  </tr>
  </thead>
  <tbody>
  <tr>
  
    <td>Beneficial Ownerships</td>
    <td><?php echo get_complaints_by_topic_id(903); ?></td>
  </tr>
  <tr>
  
    <td>Takeovers</td>
    <td><?php echo get_complaints_by_topic_id(904); ?></td>
  </tr>
  <tr>
  
    <td>Brokers &amp; Agents (PSX)</td>
    <td><?php echo get_complaints_by_topic_id(905); ?></td>
  </tr>
  <tr>
  
    <td>Brokers (PMEX)</td>
    <td><?php echo get_complaints_by_topic_id(906); ?></td>
  </tr>
  <tr>
  
    <td>Share Registrars</td>
    <td><?php echo get_complaints_by_topic_id(907); ?></td>
  </tr>
  <tr>
  
    <td>Debt Securities Trustees</td>
    <td><?php echo get_complaints_by_topic_id(908); ?></td>
  </tr>
  <tr>
  
    <td>Underwriters</td>
    <td><?php echo get_complaints_by_topic_id(909); ?></td>
  </tr>
  <tr>
  
    <td>Book Runners</td>
    <td><?php echo get_complaints_by_topic_id(910); ?></td>
  </tr>
  <tr>
  
    <td>Initial Public Offering</td>
    <td><?php echo get_complaints_by_topic_id(911); ?></td>
  </tr>
  <tr>
  
    <td>Suspension of Trading</td>
    <td><?php echo get_complaints_by_topic_id(912); ?></td>
  </tr>
  <tr>
  
    <td>Trading Prices/ Scrip Prices</td>
    <td><?php echo get_complaints_by_topic_id(913); ?></td>
  </tr>
  <tr>
  
    <td>Credit Rating Companies</td>
    <td><?php echo get_complaints_by_topic_id(914); ?></td>
  </tr>
  <tr>
  
    <td>CDC</td>
    <td><?php echo get_complaints_by_topic_id(915); ?></td>
  </tr>
  <tr>
  
    <td>NCCPL</td>
    <td><?php echo get_complaints_by_topic_id(916); ?></td>
  </tr>
  <tr>
  
    <td>PSX</td>
    <td><?php echo get_complaints_by_topic_id(917); ?></td>
  </tr>
  <tr>
  
    <td>Miscellaneous</td>
    <td><?php echo get_complaints_by_topic_id(918); ?></td>
  </tr>
  <tr>
  
    <td>Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
   <tr>
    <td><b>Total</b></td>
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
    <th width="94">Total  <br>
      Queries</th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td>Licensing/ Renewals/Surveyors and others)</td>
    <td><?php echo get_complaints_by_topic_id(583); ?></td>
  </tr>
  <tr>
    <td>Policyholder’s queries related to their Insurance Policies</td>
    <td><?php echo get_complaints_by_topic_id(584); ?></td>
  </tr>
  <tr>
    <td>Shareholder’s queries</td>
    <td><?php echo get_complaints_by_topic_id(585); ?></td>
  </tr>
  <tr>
    <td>Miscellaneous</td>
    <td><?php echo get_complaints_by_topic_id(586); ?></td>
  </tr>
   <tr>
  
    <td>Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
   <tr>
    <td><b>Total</b></td>
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
     <td width="221">Primary    Category </td>
    <td width="304">Sub Category </td>
   
    <td width="73">Total  <br>
      Queries</td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td>AMC Wing</td>
    <td>Opening of investor account</td>
    <td><?php echo get_complaints_by_topic_id(613); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>status of AMC/Investment advisor</td>
    <td><?php echo get_complaints_by_topic_id(614); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Status of companies under liquidation/winding up</td>
    <td><?php echo get_complaints_by_topic_id(615); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Licensing Fee</td>
    <td><?php echo get_complaints_by_topic_id(616); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>clarification on Regulatory framework</td>
    <td><?php echo get_complaints_by_topic_id(617); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Licensing and incorporation procedure of AMC</td>
    <td><?php echo get_complaints_by_topic_id(618); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Information about category of fund/structure of fund</td>
    <td><?php echo get_complaints_by_topic_id(619); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>website of AMC</td>
    <td><?php echo get_complaints_by_topic_id(620); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Information about financial statements</td>
    <td><?php echo get_complaints_by_topic_id(621); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Dividend not received</td>
    <td><?php echo get_complaints_by_topic_id(622); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Tax benefits</td>
    <td><?php echo get_complaints_by_topic_id(623); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Others </td>
    <td><?php echo get_complaints_by_topic_id(624); ?></td>
  </tr>
  <tr>
    <td>Non Banking Finance Companies (NBFC)</td>
    <td>Claim of depositor</td>
    <td><?php echo get_complaints_by_topic_id(625); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Status of companies in liquidation / winding up</td>
    <td><?php echo get_complaints_by_topic_id(626); ?></td>
  </tr>
  <tr>
    <td width="221">&nbsp;</td>
    <td>Issue of share transfer</td>
    <td><?php echo get_complaints_by_topic_id(627); ?></td>
  </tr>
   <tr>
    <td width="221">&nbsp;</td>
    <td>Observations in periodic account</td>
    <td><?php echo get_complaints_by_topic_id(628); ?></td>
  </tr>
   <tr>
    <td width="221">&nbsp;</td>
    <td>Tax deduction</td>
    <td><?php echo get_complaints_by_topic_id(629); ?></td>
  </tr>
   <tr>
    <td width="221">&nbsp;</td>
    <td>others</td>
    <td><?php echo get_complaints_by_topic_id(630); ?></td>
  </tr>
  <tr>
    <td>Real Estate Investment Trust (REIT)</td>
    <td>Dividend not received</td>
    <td><?php echo get_complaints_by_topic_id(631); ?></td>
  </tr>
   <tr>
    <td width="221">&nbsp;</td>
    <td>Tax benefits</td>
    <td><?php echo get_complaints_by_topic_id(632); ?></td>
  </tr>
   <tr>
    <td width="221">&nbsp;</td>
    <td>Units not received</td>
    <td><?php echo get_complaints_by_topic_id(633); ?></td>
  </tr>
  <tr>
    <td width="221">&nbsp;</td>
    <td>Status of license of RMCs</td>
    <td><?php echo get_complaints_by_topic_id(634); ?></td>
  </tr>
  <tr>
  <tr>
    <td width="221">&nbsp;</td>
    <td>others</td>
    <td><?php echo get_complaints_by_topic_id(635); ?></td>
  </tr>
  <tr>
  <tr>
   <tr>
    <td>Voluntary Pension Schemes</td>
    <td>Queries about sub-funds of pension funds</td>
    <td><?php echo get_complaints_by_topic_id(636); ?></td>
  </tr>
  <tr>
   <tr>
    <td></td>
    <td>Status of registration of Pension Fund Manager (PFM)</td>
    <td><?php echo get_complaints_by_topic_id(637); ?></td>
  </tr>
    <tr>
    <td></td>
    <td>Benefits of investments</td>
    <td><?php echo get_complaints_by_topic_id(638); ?></td>
  </tr>
    <tr>
    <td></td>
    <td>Expected return on pension fund</td>
    <td><?php echo get_complaints_by_topic_id(639); ?></td>
  </tr>
    <tr>
    <td></td>
    <td>Queries about tax on withdrawal</td>
    <td><?php echo get_complaints_by_topic_id(640); ?></td>
  </tr>
    <tr>
    <td></td>
    <td>others</td>
    <td><?php echo get_complaints_by_topic_id(641); ?></td>
  </tr>
  <tr>
    <td>Private Equity and Venture Capital Fund</td>
    <td>Others</td>
    <td><?php echo get_complaints_by_topic_id(642); ?></td>
  </tr>
  <tr>
   <tr>
    <td>Modaraba</td>
    <td>Issues of Modaraba certificates transfer</td>
    <td><?php echo get_complaints_by_topic_id(643); ?></td>
  </tr>
   <tr>
    <td></td>
    <td>Status of companies in liquidation / winding up</td>
    <td><?php echo get_complaints_by_topic_id(644); ?></td>
  </tr>
   <tr>
    <td></td>
    <td>Inquiry about dividend</td>
    <td><?php echo get_complaints_by_topic_id(645); ?></td>
  </tr>
   <tr>
    <td></td>
    <td>Inquiry about shares</td>
    <td><?php echo get_complaints_by_topic_id(646); ?></td>
  </tr>
   <tr>
    <td></td>
    <td>Non issuance of NOCs after payment of lease/ loans</td>
    <td><?php echo get_complaints_by_topic_id(647); ?></td>
  </tr>
    <tr>
    <td></td>
    <td>others</td>
    <td><?php echo get_complaints_by_topic_id(648); ?></td>
  </tr>
  <tr>
   <tr>
    <td>Laws/Acts/Ordinances/Bill/Rules</td>
    <td>Clarification on Regulatory framework</td>
    <td><?php echo get_complaints_by_topic_id(649); ?></td>
  </tr>
   <tr>
    <td>Other</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(612); ?></td>
  </tr>
    <td colspan="2">Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
    <td colspan="2"><b>Total</b></td>
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
    <td width="73">Total  <br>
      Queries</td>
  </tr>
  </thead>
  <tbody>
    <tr>
    <td  colspan="2">Appointment/Change of Other Officers</td>
    <td><?php echo get_complaints_by_topic_id(1461); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Appointment/Change of All Officers</td>
    <td><?php echo get_complaints_by_topic_id(1462); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Filing of Form 27</td>
    <td><?php echo get_complaints_by_topic_id(1463); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Filing of Form 28</td>
    <td><?php echo get_complaints_by_topic_id(1464); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Filing of Form 29</td>
    <td><?php echo get_complaints_by_topic_id(1465); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Filing of Form 27_28</td>
    <td><?php echo get_complaints_by_topic_id(1466); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Filing of Form 28_29</td>
    <td><?php echo get_complaints_by_topic_id(1467); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Insurance Surveyor Licensing</td>
    <td><?php echo get_complaints_by_topic_id(1468); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Licensing of Insurance Surveyors Companies</td>
    <td><?php echo get_complaints_by_topic_id(1469); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Licensing of Authorized Surveying Officer</td>
    <td><?php echo get_complaints_by_topic_id(1470); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Renewal of Insurance Authorized Surveying Officer</td>
    <td><?php echo get_complaints_by_topic_id(1471); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Filing of Returns-FRS (SMBH)</td>
    <td><?php echo get_complaints_by_topic_id(1472); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Filing of Returns - Insurance</td>
    <td><?php echo get_complaints_by_topic_id(1473); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Annual Return by Listed Companies SMD-BO-107</td>
    <td><?php echo get_complaints_by_topic_id(1474); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Periodic Reporting of Redemption and Status of Covenant Compliance</td>
    <td><?php echo get_complaints_by_topic_id(1475); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Filing of Form 4 (BO) under u/s 102 of Securities Act, 2015</td>
    <td><?php echo get_complaints_by_topic_id(1476); ?></td>
  </tr>
   <tr>
    <td  colspan="2">User Registration and PIN Generation</td>
    <td><?php echo get_complaints_by_topic_id(1477); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Establishment of Foreign Company Business Places and Submission of Accounts</td>
    <td><?php echo get_complaints_by_topic_id(1478); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Renewal of Insurance Surveyors Companies License</td>
    <td><?php echo get_complaints_by_topic_id(1479); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Reporting of Features of Debt Instrument</td>
    <td><?php echo get_complaints_by_topic_id(1480); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Change in Company Objects</td>
    <td><?php echo get_complaints_by_topic_id(1481); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Process Unavailable</td>
    <td><?php echo get_complaints_by_topic_id(1482); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Other</td>
    <td><?php echo get_complaints_by_topic_id(1483); ?></td>
  </tr>
   <tr>
    <td  colspan="2">Mortgage Regstration etc.</td>
    <td><?php echo get_complaints_by_topic_id(1486); ?></td>
  </tr>
   <tr>
    <td>Restoration of Company</td>
    <td>Attachment</td>
    <td><?php echo get_complaints_by_topic_id(1488); ?></td>
  </tr>
   <tr>
    <td></td>
    <td>Unable to continue</td>
    <td><?php echo get_complaints_by_topic_id(1489); ?></td>
  </tr>
   </tr>
   <tr>
    <td></td>
    <td>Signing Issues</td>
    <td><?php echo get_complaints_by_topic_id(1490); ?></td>
  </tr>
   </tr>
   <tr>
    <td></td>
    <td>Online payment</td>
    <td><?php echo get_complaints_by_topic_id(1491); ?></td>
  </tr>
   </tr>
   <tr>
    <td></td>
    <td>Challan related</td>
    <td><?php echo get_complaints_by_topic_id(1492); ?></td>
  </tr>
   <tr>
  
    <td colspan="2">Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
    <td colspan="2"><b>Total</b></td>
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
      <h1>Company Registration/Compliance Category Summary</h1>
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
    <th width="73">Total  <br>
      Queries</th>
  </tr>
  </thead>
 <tbody>
   <tr>
    <td colspan="">Queries regarding applications, approvals, appeals etc.</td>
    <td><?php echo get_complaints_by_topic_id(596); ?></td>
  </tr>
   <tr>
    <td colspan="">Queries regarding Public Sector Companies.</td>
    <td><?php echo get_complaints_by_topic_id(597); ?></td>
  </tr>
   <tr>
    <td colspan="">Queries regarding Housing and Real Estate Companies.</td>
    <td><?php echo get_complaints_by_topic_id(598); ?></td>
  </tr>
   <tr>
    <td colspan="">Queries regarding not for profit companies (u/S 42 of the Companies Ordinance, 1984) NGOs.</td>
    <td><?php echo get_complaints_by_topic_id(599); ?></td>
  </tr>
   <tr>
    <td colspan="">Queries regarding Legal & Regulatory Framework.</td>
    <td><?php echo get_complaints_by_topic_id(600); ?></td>
  </tr>
   <tr>
    <td colspan="">Queries regarding operational matters of the companies.</td>
    <td><?php echo get_complaints_by_topic_id(601); ?></td>
  </tr>
   <tr>
    <td colspan="">Queries regarding eServices/MIS related issues.</td>
    <td><?php echo get_complaints_by_topic_id(602); ?></td>
  </tr>
   <tr>
    <td colspan="">Queries regarding Non-corporate entities.</td>
    <td><?php echo get_complaints_by_topic_id(603); ?></td>
  </tr>
   <tr>
    <td colspan="">Others (not classifiable elsewhere).</td>
    <td><?php echo get_complaints_by_topic_id(604); ?></td>
  </tr>
   <tr>
  
    <td colspan="">Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
    <td colspan=""><b>Total</b></td>
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
      Queries</td>
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
      <h1>Human Resource Queries</h1>
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
      Queries</td>
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
      <h1>Finance Queries</h1>
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
      <h1>PLAD Queries</h1>
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
      <h1>IS&T Queries</h1>
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
      Queries</td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td>Technical Issues<!--750--></td>
    <td>Chalan Verification</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1092); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Information Update Requests </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1094); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>User ID and Password </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1097); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td>Server not Responding </td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1096); ?></td>
  </tr>
  <tr>
    <td width="323">&nbsp;</td>
    <td>Misc.</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1095); ?></td>
  </tr>
  <tr>
    <td>Other <!--751--></td>
    <td>&nbsp;</td>
    <td></td>
    <td><?php echo get_complaints_by_topic_id(1090); ?></td>
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
      <h1>CRO-Karachi Queries</h1>
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
      Queries</td>
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
      <h1>CRO-Islamabad Queries</h1>
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
    <td width="73">Total  <br>
      Queries</td>
  </tr>
  </thead>
<tbody>
 <tr>
    <td colspan="">.Queries regarding registration/incorporation of companies</td>
    <td><?php echo get_complaints_by_topic_id(707); ?></td>
  </tr>
   <tr>
    <td colspan="">.Queries regarding name of company (availability of name, change of name,  rectification of name)</td>
    <td><?php echo get_complaints_by_topic_id(708); ?></td>
  </tr>
   <tr>
    <td colspan="">.Queries regarding Process/Form pending at CRO</td>
    <td><?php echo get_complaints_by_topic_id(709); ?></td>
  </tr>
   <tr>
    <td colspan="">.Queries regarding Issuance of CTCs/ financial statements/information</td>
    <td><?php echo get_complaints_by_topic_id(710); ?></td>
  </tr>
   <tr>
    <td colspan="">.Queries regarding de-registration/easy exit scheme/ winding up of companies</td>
    <td><?php echo get_complaints_by_topic_id(711); ?></td>
  </tr>
   <tr>
    <td colspan="">.Queries regarding eServices/MIS related issues</td>
    <td><?php echo get_complaints_by_topic_id(712); ?></td>
  </tr>
   <tr>
    <td colspan="">.Queries regarding Transfer / Allotment of Shares</td>
    <td><?php echo get_complaints_by_topic_id(713); ?></td>
  </tr>
   <tr>
    <td colspan="">.Queries regarding Non-corporate entities</td>
    <td><?php echo get_complaints_by_topic_id(714); ?></td>
  </tr>
   <tr>
    <td colspan="">.Others (not classifiable elsewhere)</td>
    <td><?php echo get_complaints_by_topic_id(715); ?></td>
  </tr>
   <tr>
  
    <td colspan="">Not Categorized</td>
    <td><?php echo get_complaints_by_topic_id(43); ?></td>
  </tr>
  <tr>
    <td colspan=""><b>Total</b></td>
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
      <h1>CRO-Lahore Queries</h1>
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
      Queries</td>
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
      <h1>CRO-Karachi Queries</h1>
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
      Queries</td>
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
      <h1>CRO Faisalabad Queries</h1>
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
      Queries</td>
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
      <h1>CRO Multan Queries</h1>
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
      Queries</td>
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
      <h1>CRO Gilgit Queries</h1>
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
      Queries</td>
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
      <h1>CRO Peshawar Queries</h1>
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
      Queries</td>
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
      <h1>CRO Quetta Queries</h1>
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
      Queries</td>
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
      <h1>CRO Sukkur Queries</h1>
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
      Queries</td>
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
$sql_dept_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '1' AND dept_id='".$row_dept['dept_id']."' ".$from_to_date."";
$res_dept_comp = mysql_query($sql_dept_comp);
$num_dept_comp = mysql_num_rows($res_dept_comp);

$primary_status .= "{ name: '".$row_dept['dept_name']."', y: ".$num_dept_comp.",
            drilldown: '".$row_dept['dept_name']."'},";

$sub_status .="{
			name: '".$row_dept['dept_name']."',
            id: '".$row_dept['dept_name']."',
            data: [ ";
			

$sql_comp_topic = "SELECT * FROM `sdms_ticket` WHERE isquery = '1' AND dept_id='".$row_dept['dept_id']."' ".$from_to_date." group by topic_id";
$res_comp_topic = mysql_query($sql_comp_topic);
while($row_comp_topic = mysql_fetch_array($res_comp_topic)){
	
$sql_topic_data = "SELECT * FROM `sdms_help_topic` WHERE topic_id = '".$row_comp_topic['topic_id']."'";
$res_topic_data = mysql_query($sql_topic_data);
$row_topic_data = mysql_fetch_array($res_topic_data);

$sql_topic_pdata = "SELECT * FROM `sdms_help_topic` WHERE topic_id = '".$row_topic_data['topic_pid']."'";
$res_topic_pdata = mysql_query($sql_topic_pdata);
$row_topic_pdata = mysql_fetch_array($res_topic_pdata);
	
$sql_topic_comp = "SELECT * FROM `sdms_ticket` WHERE isquery = '1' AND topic_id='".$row_comp_topic['topic_id']."' AND dept_id='".$row_dept['dept_id']."' ".$from_to_date."";
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
