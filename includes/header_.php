<!DOCTYPE html>
<html>
<head>
<title>Service Desk Management System</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="assets/css/jquery.simplewizard.css">
<!-- fonts cdn -->

<!-- fonts cdn -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/style.css">
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="https://cdn.cai.tools.sap/webchat/webchat.js" channelId="b3b5d52d-f839-471e-af1f-d5ab8b5abe7c" token="8edfe32857e3bc6a7f60c53123e06f40" id="cai-webchat" ></script>
<meta name="viewport" content="width=device-width">
</head>
<style type="text/css">
.dropbtn {
	background-color: #03BEA3;
	color: white;
	padding: 14px;
	font-size: 14px;
	border: none;
	cursor: pointer;
}
@font-face {
	font-family: 'Raleway-Regular';
	src: url("../fonts/raleway-regular-webfont.eot");
	src: url("../fonts/raleway-regular-webfont.eot?#iefix") format("embedded-opentype"), url("../fonts/raleway-regular-webfont.woff2") format("woff2"), url("../fonts/raleway-regular-webfont.woff") format("woff"), url("../fonts/raleway-regular-webfont.ttf") format("truetype");
	font-weight: normal;
	font-style: normal;
}
.dropdown {
	position: relative;
	display: inline-block;
}
.dropdown-content {
	display: none;
	position: absolute;
	background-color: #03BEA3;
	min-width: 200px;
	box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
	z-index: 1;
}
.dropdown-content a {
	color: black;
	padding: 12px 16px;
	text-decoration: none;
	display: block;
}
.dropdown-content a:hover {
	background-color: #f1f1f1
}
.dropdown:hover .dropdown-content {
	display: block;
}
.dropdown:hover .dropbtn {
	background-color: #05b49b;
}
</style>
<body>
<!-- header start -->
<div class="container">
  <div class="row" style="margin-right: -27px;margin-left: -27px;">
    <div class="col-xs-12"> <img class="img-responsive" src="assets/images/top.jpg"> </div>
  </div>
</div>
<!-- header end --> 
<!-- navbar start -->
<nav class="navbar custom_nav navbar_background">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse" style="background-color: #05A78F;margin-top: 25px;"> <span class="sr-only">toggle navigation</span> <span class="icon-bar" style="background-color: #ffffff;"></span> <span class="icon-bar" style="background-color: #ffffff;"></span> <span class="icon-bar" style="background-color: #ffffff;"></span> </button>
    </div>
    <ul class="nav navbar-nav navbar-collapse collapse nav_links">
      <li ><a href="http://sdms.secp.gov.pk/">Home <span>ہوم</span> </a></li>
      <li>
        <div class="dropdown">
          <button class="dropbtn">Query <span>سوال</span> </button>
          <div class="dropdown-content dropdownnav"> <a href="index.php?action=query">New Query <span>سوال</span> </a> <a href="ach/login_query.php">Check Status <span>جانچ</span> </a> </div>
        </div>
      </li>
      <li>
        <div class="dropdown">
          <button class="dropbtn">Complaint <span>شکایت</span> </button>
          <div class="dropdown-content dropdownnav"> <a href="index.php?action=complaint" >New Complaint <span>نیا شکایت</span> </a> <a href="ach/login.php">Check Status <span>جانچ</span> </a> </div>
        </div>
      </li>
      <li><a href="http://jamapunji.pk/" target="_blank">Investor Education <span>انویسٹر ایجوکیشن</span> </a></li>
      <li><a href="faq.php">FAQ`s <span>عمومی سوالات</span> </a></li>
      <li><a href="https://www.secp.gov.pk/media-center/guide-books/general-guide-books/">Guides&nbsp;گائڈز</a></li>
      <li><a href="https://www.secp.gov.pk/contact-us/">Contact Us <span>ہم سے رابطہ کریں</span> </a></li>
    </ul>
  </div>
</nav>
<marquee style="font-weight: bold;">
Respected Citizen, Our standard business time for taking calls is from 9 am to 5 pm Monday to Friday.  From 1 pm to 2 pm lunch break is observed. For lodging complaints and queries, the SDMS portal (www.sdms.secp.gov.pk/) should be your first choice.  Alternatively you may write to us at complaints@secp.gov.pk and queries@secp.gov.pk
</marquee>
<!-- navbar end --> 
<!-- complaint statu start --> 

