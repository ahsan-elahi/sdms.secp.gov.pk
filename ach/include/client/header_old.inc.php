<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../styles.css">
<title>E-Grievance Redressal and Citizen Feedback System</title>
    <script src="<?php echo ROOT_PATH; ?>js/jquery-1.7.2.min.js"></script>
    <script src="<?php echo ROOT_PATH; ?>js/jquery.multifile.js"></script>
    <script src="<?php echo ROOT_PATH; ?>js/osticket.js"></script>
    <style type="text/css">

<!--

.style2 {

	color: #1d6710;

	font-size: 17px;

	letter-spacing: 1px;

	font-weight: bold;

}

.style4 {

	font-size: 20px;

	font-weight: bold;

}

.style5 {

	color: #be0000;

	font-size: 14px;

}

.style6 {

	font-size: 36px;

	color: #206b94;

	font-weight: bold;

}

.style7 {

	font-size: 16pt;

	color: #3b7a9e;

	font-weight: bold;

}

.style8 {

	font-size: 13pt;

	font-weight: bold;

}

.style9 {

	color: #1f506f;

	font-size: 13pt;

	font-weight: bold;

}

.style10 {

	color: #2a6d97;

	font-weight: bold;

	font-size: 14px;

}

.style12 {color: #666666}

.style13 {

	background-color: #000000;

}

-->

</style>
</head>
<body>
	<div class="main_container">
    	<div class="header">	
    		<div class="logo">
            	<a href="../index.php"><img src="images/logo.png" style="width:734px"></a>
            </div>	
    		<div class="header_content">
            <h1 class="feedback">Feedback Hotline</h1>
            <p class="call_us">  Call us Toll Free Number </p>
            <h1 class="call_no">0800-82800</h1>
            <p class="email">E-mail - feedback@khyber.gov.pk</p>
          </div><!----header_content---->
 		</div><!-----header----->   
        
        <div class="banner">
        	<p>E-Grievance Redressal System</p>
        	<ul>
            	<li class="li1">About us Feedback System</li>	
                <a style="color: #fff;text-decoration:none;" href="open.php"><li class="li2">Register New Complaint</li></a>
            	<a  style="color: #fff;text-decoration:none;"  href="view.php"><li class="li3">Check Complaint/Status</li></a>
            </ul>
            <form action="login.php" method="post" id="clientLogin">
 <?php csrf_token(); ?>
         	<div class="login">
            	<p>UCN:</p>
                <input type="text" name="lticket"  required="required" value="<?php echo $ticketid; ?>">
                <p>Pincode:</p>
                <input type="password" name="lpincode"  required="required" > 
                <input type="submit" name="submit" class="submit" value="View Status"> 
            </div><!---login--->
            </form>    
        </div><!----banner----->



       <!--<div class="form">-->

         <?php if($errors['err']) { ?>

            <div id="msg_error"><?php echo $errors['err']; ?></div>

         <?php }elseif($msg) { ?>

            <div id="msg_notice"><h3><?php echo $msg; ?></h3></div>

         <?php }elseif($warn) { ?>

            <div id="msg_warning"><?php echo $warn; ?></div>

         <?php } ?>

