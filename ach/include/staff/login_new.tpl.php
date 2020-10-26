<?php 
defined('OSTSCPINC') or die('Invalid path');

$info = ($_POST && $errors)?Format::htmlchars($_POST):array();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>        
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <!--[if gt IE 8]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <![endif]-->
    
    <title>SECP GRS Complaint System:: SCP</title>

    <link rel="icon" type="image/ico" href="favicon.ico"/>
    
    <link href="css/stylesheets.css" rel="stylesheet" type="text/css" />
    <!--[if lt IE 8]>
        <link href="css/ie7.css" rel="stylesheet" type="text/css" />
    <![endif]-->    
    <link rel='stylesheet' type='text/css' href='css/fullcalendar.print.css' media='print' />
    
    <script type='text/javascript' src='js/plugins/jquery/jquery-1.10.2.min.js'></script>
    <script type='text/javascript' src='js/plugins/jquery/jquery-ui-1.10.1.custom.min.js'></script>
    <script type='text/javascript' src='js/plugins/jquery/jquery-migrate-1.2.1.min.js'></script>
    <script type='text/javascript' src='js/plugins/jquery/jquery.mousewheel.min.js'></script>
    
    <script type='text/javascript' src='js/plugins/cookie/jquery.cookies.2.2.0.min.js'></script>
    
    <script type='text/javascript' src='js/plugins/bootstrap.min.js'></script>
    
    <script type='text/javascript' src='js/plugins/charts/excanvas.min.js'></script>
    <script type='text/javascript' src='js/plugins/charts/jquery.flot.js'></script>    
    <script type='text/javascript' src='js/plugins/charts/jquery.flot.stack.js'></script>    
    <script type='text/javascript' src='js/plugins/charts/jquery.flot.pie.js'></script>
    <script type='text/javascript' src='js/plugins/charts/jquery.flot.resize.js'></script>
    
    <script type='text/javascript' src='js/plugins/sparklines/jquery.sparkline.min.js'></script>
    
    <script type='text/javascript' src='js/plugins/fullcalendar/fullcalendar.min.js'></script>
    
    <script type='text/javascript' src='js/plugins/select2/select2.min.js'></script>
    
    <script type='text/javascript' src='js/plugins/uniform/uniform.js'></script>
    
    <script type='text/javascript' src='js/plugins/maskedinput/jquery.maskedinput-1.3.min.js'></script>
    
    <script type='text/javascript' src='js/plugins/validation/languages/jquery.validationEngine-en.js' charset='utf-8'></script>
    <script type='text/javascript' src='js/plugins/validation/jquery.validationEngine.js' charset='utf-8'></script>
    
    <script type='text/javascript' src='js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'></script>
    <script type='text/javascript' src='js/plugins/animatedprogressbar/animated_progressbar.js'></script>
    
    <script type='text/javascript' src='js/plugins/qtip/jquery.qtip-1.0.0-rc3.min.js'></script>
    
    <script type='text/javascript' src='js/plugins/cleditor/jquery.cleditor.js'></script>
    
    <script type='text/javascript' src='js/plugins/dataTables/jquery.dataTables.min.js'></script>    
    
    <script type='text/javascript' src='js/plugins/fancybox/jquery.fancybox.pack.js'></script>
    
    <script type='text/javascript' src='js/plugins/pnotify/jquery.pnotify.min.js'></script>
    <script type='text/javascript' src='js/plugins/ibutton/jquery.ibutton.min.js'></script>
    
    <script type='text/javascript' src='js/plugins/scrollup/jquery.scrollUp.min.js'></script>
    
    <script type='text/javascript' src='js/cookies.js'></script>
    <script type='text/javascript' src='js/actions.js'></script>
    <script type='text/javascript' src='js/charts.js'></script>
    <script type='text/javascript' src='js/plugins.js'></script>
    
</head>
<body>
 <div class="wrapper fixed green"> 
    <!--Header-->
    <div class="header">
        <a class="logo" href="index.php"><img src="img/logo.png" alt="E-Citizen Grievance Redressal System" title="E-Citizen Grievance Redressal System"/></a>
        <ul class="header_menu">
            <li class="list_icon"><a href="#">&nbsp;</a></li>
            
        </ul>    
    <div class="header_banner">
       
    </div>   
    </div>
    <!--Content-->
    
    <div class="content" style="min-height:330px; margin-left: 333px; padding-top: 230px;">
    	<?php if($msg) { ?>
<div class="alert alert-error" style="width: 622px;"><h4>Warning!</h4><?php echo Format::htmlchars($msg); ?></div>
<?php } ?><!--WorkPlace Start-->
        <div class="workplace">
        <div class="row-fluid">
             <div class="span8">
                        <div class="head clearfix">
                            <div class="isw-ok"></div>
                            <h1>Login Form</h1>
                        </div>
                        <div class="block-fluid">                        
                            <form class="form-horizontal" action="login_new.php" method="POST" id="validation">
                            <?php csrf_token(); ?>
                            <input type="hidden" name="do" value="scplogin">
                            <div class="row-form clearfix">
                                <div class="span3">User name:</div>
                                <div class="span9">        
                                    <input type="text" class="validate[required,maxSize[50]]" name="username" id="inputEmail"  value="<?php echo $info['username']; ?>" placeholder="username" autocorrect="off" autocapitalize="off"/>
                                   <!-- <span>Maximum 15 characters</span>-->
                                </div>
                            </div>      
    
                            <div class="row-form clearfix">
                                <div class="span3">Password:</div>
                                <div class="span9">    
                                <input type="password" class="validate[required,minSize[1]]"  id="inputPassword" name="passwd" placeholder="password" autocorrect="off" autocapitalize="off"/>    
                                    <!--<span>Minimum 15 characters</span>-->
                                </div>
                            </div>                 
                                
                            <div class="footer tar">
                                <button  type="submit" name="submit" class="btn">Submit</button>
                            </div>                 
                                
                            </form>
                        </div>
    
                    </div>
        </div>
    
   </div>
   </div>
   	<div class="dr"><span></span></div>
   <!--Content End-->
    <!--Footer-->
    <div class="footer_main">
                            <a class="logo" style="color:#FFF;" href="index.php"><img src="img/logo.png" alt="SECP::Service Desk Management System" title="E-Citizen Grievance Redressal System FATA"/></a>
                            <ul class="footer_menu"  style="color:#FFF;">
                                <li>2017 Copyright by SECP SDMS Powered by <a style="color:#FFF; href='http://multibizservices.com/' target="_blank">Multi-biz Services</a></li>
                            </ul>    
                        </div> 
    </div><!--wrapper End--> 
</body>
</html>
