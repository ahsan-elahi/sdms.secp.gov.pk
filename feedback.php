<?php include('includes/header.php'); ?>
<?php include('config/connection.php'); ?>

<!-- navbar end -->
<!-- complaint statu start -->
<?php 
	if(isset($_POST['feedback'])){

		$complainant_id= $_POST['id'];
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$address = $_POST['address'];
		$contact = $_POST['contact'];
		$complaint =$_POST['complaint'];
		$experiance =  $_POST['experiance'];
		$comments = $_POST['massage'];

$sql_getcomplaintinfo = "Select * from sdms_ticket where ticket_id = '".$complainant_id."'";
$run_getcomplaintinfo = mysqli_query($conn ,$sql_getcomplaintinfo);
$num_getcomplaintinfo = mysqli_num_rows($run_getcomplaintinfo);
if(mysqli_num_rows($run_getcomplaintinfo) > 0) 
{
$row_getcomplaintinfo = mysqli_fetch_assoc($run_getcomplaintinfo);

$message = "
<html>
<head>
<title>Escalation Alert SDMS</title>
</head>
<body>
<p>Dear ".$row_getcomplaintinfo['name'].",</p>
<p>Feedback of Complaint #".$row_getcomplaintinfo['ticket_id']." COMPLAINT NUMBER has been received for your attention and necessary action.  Please review and act accordingly.</p>
<p>For access to the feedback, please click on: <a href='http://sdms.secp.gov.pk/ach/scp/tickets.php?id=".$row_getcomplaintinfo['ticket_id']."' >VIEW FEEDBACK</a></p><p>
----------------------<br />
".$comments."<br />
----------------------<br />
Regards,<br />
".$fname.' '.$lname."</p>

</body>
</html>";


$assigneresult = mysqli_query($conn, "select * from sdms_staff where staff_id='".$row_getcomplaintinfo['staff_id']."'");
$assignerow = mysqli_fetch_assoc($assigneresult);		
$to = $assignerow['email'];
//$to = 'ahsan@crazenators.com';


$depoocresult = mysqli_query($conn, "select * from sdms_staff where dept_id ='".$row_getcomplaintinfo['dept_id']."' AND isfocalperson = '1'");
$deppochrow = mysqli_fetch_assoc($deppocresult);
if($deppochrow['staff_id']!=$row_getcomplaintinfo['staff_id'])
{
	$deptpoc = $deppochrow['email'];
}else
{
	$deptpoc = '';
}


$dephodresult = mysqli_query($conn, "select * from sdms_staff where dept_id='".$row_getcomplaintinfo['dept_id']."' AND group_id = '4' AND onchairman = '1'");
while($dephodhrow = mysqli_fetch_assoc($dephodresult)){
$hodemail .= ','.$dephodrow['email'];
}

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
// More headers
$headers .= 'From: Notification Alert<notification.servicedesk@secp.gov.pk>' . "\r\n";
//$headers .= 'Cc: '.$deptpoc.$hodemail.'' . "\r\n";
$checkresult = mysqli_query($conn,"select ticket_id from sdms_notify where ticket_id ='".$row['ticket_id']."' AND notify_days='3' ");
	
echo mail($to,$subject,$message,$headers);
$insert_feed = "INSERT INTO `sdms_feedback`( `fname`,`lname`, `address`, `contact`, `complaint`, `experiance`, `massage`,`complainant_id`,`dept_id`, `date`) VALUES ('".$fname."','".$lname."','".$address."','".$contact."','".$complaint."','".$experiance."','".$comments."','".$complainant_id."','".$row_getcomplaintinfo['dept_id']."',Now())";
$run_feed = mysqli_query($conn ,$insert_feed);


}

}
?>
<div class="container-fluid">
	<div class="container" style="background-color: white;color: #767271;">
			<div class="row">
				<div class="col-xs-12 padding-top-10">
					<h4 style="padding-top: 30px;">SECP SERVICE DESK FEEDBACK FORM</h4>
				</div>
<?php 
if(isset($_REQUEST['id']))
{
$deppocresult = mysqli_query($conn, "select * from sdms_feedback where complainant_id='".$_REQUEST['id']."'");
if (mysqli_num_rows($deppocresult)==0) 
{ ?>
	<div class="col-xs-12">
						<div class="panel panel-success" style="max-height: inherit;padding: 0px;">
						    <div class="panel-heading">SERVICE DESK FEEDBACK</div>
						    <div class="col-lg-12 col-xs-12 padding-top-10">
						    	<form class="form-horizantle" action="" method="post">
                                <input type="hidden" value="<?php echo $_REQUEST['id'] ?>" name="id" > 
						    	<div class="col-lg-6 col-xs-12">
								    <div class="form-group">
								    	<label for="date"> First Name <span style="color:#F00;">*</span></label>
								      	<input type="text" class="form-control" id="fname" name="fname" required>
								    </div>
								    <div class="form-group">
								    <label for="date"> Address </label>
								      <input type="text" class="form-control" id="address" placeholder="Enter Name" name="address">
								    </div>
								    
								</div>
								<div class="col-lg-6 col-xs-12">
								    <div class="form-group">
								    <label for="date"> Last Name <span style="color:#F00;">*</span></label>
								      <input type="text" class="form-control" id="lname" placeholder="Enter Name" name="lname" required>
								    </div>
								    <div class="form-group">
								    <label for="date"> Contact Number </label>
								      <input type="text" class="form-control" id="name" placeholder="Enter Name" name="contact">
								    </div>
								</div>
								    <div class="form-group">
								    <ol type="A" style="margin-left: -15px;">
								    <div class="col-lg-6 col-xs-12">
								    	<li>With regard to your complaint, kindly select any of the below mentioned options:
                                        <span style="color:#F00;">*</span>
									    	<ol type="1">
									    		<li> 
									    			<div class="form-group">
													  <div class="checkbox">
													    <input type="radio" name="complaint" id="chk2" value="1" required />
													    <label class="checkbox-label">Complaint resolved</label>
													  </div>
													</div>
									    		</li>
									    		<li> 
									    			<div class="form-group">
													  <div class="checkbox">
													    <input type="radio" name="complaint" id="chk2" value="2" required />
													    <label class="checkbox-label">Complaint not resolved </label>
													  </div>
													</div>
									    		</li>
									    	</ol>
								    	</li>
								    </div>
								    <div class="col-lg-6 col-xs-12">	
								    	<li>With regard to your experience, kindly select any of the below mentioned options:
									    	<ol type="1">
									    		<li> 
									    			<div class="form-group">
													  <div class="checkbox">
													    <input type="radio" name="experiance" id="chk2" value="1" required />
													    <label class="checkbox-label">Dissatisfied</label>
													  </div>
													</div>
									    		</li>
									    		<li> 
									    			<div class="form-group">
													  <div class="checkbox">
													    <input type="radio" name="experiance" id="chk2" value="2" required />
													    <label class="checkbox-label">Neutral</label>
													  </div>
													</div>
									    		</li>
									    		<li> 
									    			<div class="form-group">
													  <div class="checkbox">
													    <input type="radio" name="experiance" id="chk2" value="3" required />
													    <label class="checkbox-label">Satisfied</label>
													  </div>
													</div>
									    		</li>
									    	</ol>
								    	</li>
								    </div>	
								    <div class="col-lg-12 col-xs-12">
								    	<li>
								    		If you have any additional comments and feedback, please use the box below:<span style="color:#F00;">*</span>
								    		<div class="form-group">
								    		<textarea class="form-control" rows="5" name="massage" required></textarea>
								    		</div>
								    		<div class="form-group">
								    			<input type="submit" name="feedback" value="Send Us Feedback" class="btn btn-primary" style="float: right;">
								    		</div>
								    	</li>
								    </div>	
								    </ol>

								    </div>
								  </form>
							<div class="col-lg-6 col-xs-12">  
								<p>
									Service Desk <br>
									Securities and Exchange Commission of Pakistan<br>
									NIC Building, 63 Jinnah Avenue, Blue Area, Islamabad – 44000, Pakistan<br>
									complaints@secp.gov.pk<br>
									Toll Free No:  080088008 
								</p>
							</div>	  
						    </div>

					  </div>
				</div>
<?php }else{ ?>
<div class="col-xs-12">
<div class="panel panel-success" style="max-height: inherit;padding: 0px;">
<div class="panel-heading">Feedback already provide. Thanks!!!</div>
</div>
</div>
<?php }
}else{
?>
<div class="col-xs-12">
<div class="panel panel-success" style="max-height: inherit;padding: 0px;">
<div class="panel-heading">Please Select your complaint first. Thanks!!!</div>
</div>
</div>
<?php }?>     
				
               
			</div>	
	</div>
</div>


<!-- complaint statu End -->
<!-- footer section -->
<div class="container-fluid footer">
<div class="container">
	<div class="row padding-top-10">
		<div class="col-lg-4 col-xs-12 padding-top-10">
			<img class="img-responsive" src="assets/images/footer_logo.png">
		</div>
		<div class="col-lg-4 col-xs-12"></div>
		<div class="col-lg-4 col-xs-12 padding-top-10">
			<p style="color: #BEC2C3;font-size: 14px;line-height: 21px;">
				@SECP2010, Securities & Exchange Commission of Pakistan.
				Powered & Supported By rar multibiz services (pvt) ltb
				Best Viewed in IE 7/8 and Google Chrome
			</p>
		</div>
	</div>
</div>
</div>
	<!-- footer section -->
<script type="text/javascript">
	   $(function() {
     $("#datepicker").datepicker({
                    defaultDate: "11/1/2013",
                    background:'gray',
                });
   });
</script>

</body>
