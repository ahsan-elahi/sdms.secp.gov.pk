<?php
if(!defined('OSTCLIENTINC')) die('Access Denied');
$email=Format::input($_POST['lemail']?$_POST['lemail']:$_GET['e']);
$ticketid=Format::input($_POST['lticket']?$_POST['lticket']:$_GET['t']);
?>
<div class="container-fluid">
	<div class="container" style="background-color: white;color: #767271;">
			<div class="row">		
			<div class="col-lg-offset-3 col-lg-5 col-xs-12">
				<h2 class="text-center" style="padding-top: 50px;">Check Query Status</h2>
				<p class="text-center">
					To view the status of a Query,provide us with the login details below.
				</p>
				  <form action="login_query.php" method="post" class="form-horizontal">
				    <div class="form-group">
				    <div class="col-lg-2 col-xs-12">
				      <label for="email">Query Number:</label>
				    </div>
				    <div class="col-lg-10 col-xs-12">
				      <input type="text" class="form-control" id="lticket" name="lticket" style="background-color: #F7F7F7;">
				    </div>
				    </div>
				    <div class="form-group">
				    <div class="col-lg-2 col-xs-12">
				      <label for="pwd">Pincode:</label>
				    </div>
				    <div class="col-lg-10 col-xs-12" >  
				      <input type="password" class="form-control" id="lpincode" name="lpincode" style="background-color: #F7F7F7;">
				    </div>  
				    </div>
				    <div class="text-center">
				    <button type="submit" class="btn btn-success btn-lg padding-top-10" style="height: 40px;font-size: 16px;">View Status</button>
				    </div>
				  </form>

			</div>
			<div class="col-xs-12" style="min-height: 60px;"></div>
			<div class="col-xs-12">
				<p class="text-center">
					if you have lost your PIN Code or Query Number, please call us at 0800 88008 TOLL FREE for assistance
				</p>
			</div>
			</div>
	</div>
</div>