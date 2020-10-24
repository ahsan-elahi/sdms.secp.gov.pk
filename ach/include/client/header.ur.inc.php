<?php
$title=($cfg && is_object($cfg) && $cfg->getTitle())?$cfg->getTitle():'GIT :: Complaint System';
header("Content-Type: text/html; charset=UTF-8\r\n");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>GIT :: Complaint System</title>
    <meta name="description" content="customer support platform">
    <meta name="keywords" content="GIT,Customer support system,ANTI CORRUPTION HOTLINE FOR GOVERNOR'S INSPECTION TEAM,FATA,0800-82800">
    <link rel="stylesheet" href="style.css" >
   <!-- <script src="<?php //echo ROOT_PATH; ?>js/jquery-1.7.2.min.js"></script>
    <script src="<?php //echo ROOT_PATH; ?>js/jquery.multifile.js"></script>
    <script src="<?php //echo ROOT_PATH; ?>js/osticket.js"></script>-->
    <!--======================= FIELS FOR SLIDER PAGE START  =======================-->

<script src="jquery.js" type="text/javascript"></script>

<script src="jqFancyTransitions.js" type="text/javascript"></script>

<script>

$(document).ready( function()

{

   $('#slideshowHolder').jqFancyTransitions({ width: 944, height: 339 });

});

</script>

<!--======================= FIELS FOR SLIDER PAGE END =======================-->
</head>
<body>
    <div class="wrapper">

  <div class="top"> <img src="images/logo.png" class="logo">
  
  <div style="float:left; height:24px;width:100px; color:#FFF;margin-left:60px; margin-top:43px;"><a href="../index.php" style="color:#FFF; text-decoration:none;"> EN</a> |<a href="../urdu/index.php" style="color:#FFF; text-decoration:none;"> UR</a></div>

   
    <div class="topright">

      <div class="phone" style="background:none;"><img src="images/call%2520no.png"></div>
      

      <div id="sotial"> <a href="#"><img src="images/t_icn.png"></a> <a href="#"><img src="images/tw_icn.png"></a> <a href="#"><img src="images/f_icn.png"></a> &nbsp;<span>E-Citizen Grievance Redressal System FOR Human Rights’ Directorate. </span> </div>

    </div>

    <div class="menu1">
      <ul class="menu1b">
      

        <li><a href="../index.php" title="home"><span>HOME</span></a></li>

        <li><a href="../about_git.php" title="About GIT Hotline"><span>About GIT Hotline</span></a></li>

        <li><a href="../about_secp.php" title="About FATA"><span>About FATA</span></a></li>

        <li><a href="../news.php" title="News &amp; Events"><span>News &amp; Events</span></a></li>

        <li><a href="../contact.php" title="Contact us" class="current"><span>Contact us</span></a></li>

      </ul>

      <a href="http://projects.crazenatorstechnologies.com/git/download.php?id=form"><img src="images/download_btn.png" class="download_btn"></a> </div><!--============================== EMAIL FUNCTION START HERE ====================================-->
 
<!--============================== EMAIL FUNCTION END HERE ====================================-->
  


    <div class="banner">

     
      <div class="banner2" style="border:1px thick; border-radius:8px; margin-top:-10px;">

        <div id="banner_inner"><a href="open.php"><img src="images/hed1.png"><span><p style="color:#FFF; text-decoration:none;"></p><p>
	Any Citizen from FATA can register a complaint through any means of 
communication. The means of communication for this system are 
specifically voice phone call, SMS through phone, fax, Email, We</p></span> </a></div>

        <div id="banner_inner1"><a href="view.php"><img src="images/hed2.png"><span> <p style="color:#FFF; text-decoration:none;"></p><p>
	Complainant shall send his Unique Complaint Number (UCN) and PIN code 
at xxxxx. After complainant sends the required UCN and PIN code, the 
system will check the information and after verifying w </p></span></a><p style="color:#FFF; text-decoration:none;"><a href="http://projects.crazenatorstechnologies.com/git/ach/view.php"></a> </p></div>

      </div>

    </div>  </div>
    <br style="clear:both">
        <div id="content" style="float:left">
          <div class="contentmiddle">
  
       <div class="form">
         <?php if($errors['err']) { ?>
            <div id="msg_error"><?php echo $errors['err']; ?></div>
         <?php }elseif($msg) { ?>
            <div id="msg_notice"><h3><?php echo $msg; ?></h3></div>
         <?php }elseif($warn) { ?>
            <div id="msg_warning"><?php echo $warn; ?></div>
         <?php } ?>
