<!DOCTYPE html>
<html lang="en">
<head>        
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <!--[if gt IE 8]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <![endif]-->
    <title><?php echo ($ost && ($title=$ost->getPageTitle()))?$title:'SECP SDMS :: Staff Control Panel'; ?></title>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="favicon.ico" type="image/x-icon">
    
    <link href="css/stylesheets.css" rel="stylesheet" type="text/css" />  
    <!--[if lt IE 8]>
        <link href="css/ie7.css" rel="stylesheet" type="text/css" />
    <![endif]-->            
    <link rel='stylesheet' type='text/css' href='css/fullcalendar.print.css' media='print' />
     <link href="css/uploadfilemulti.css" rel="stylesheet"> 
<script type='text/javascript' src='js/plugins/jquery/jquery-1.10.2.min.js'></script>
<script type='text/javascript' src='js/plugins/jquery/jquery-ui-1.10.1.custom.min.js'></script>
<script type='text/javascript' src='js/plugins/jquery/jquery-migrate-1.2.1.min.js'></script>
<script type='text/javascript' src='js/plugins/jquery/jquery.mousewheel.min.js'></script>    
<script type='text/javascript' src='js/plugins/cookie/jquery.cookies.2.2.0.min.js'></script>    
<script type='text/javascript' src='js/plugins/bootstrap.min.js'></script>    
<script type='text/javascript' src='js/plugins/charts/chart.min.js'></script>
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
    
<script type='text/javascript' src='https://bp.yahooapis.com/2.4.21/browserplus-min.js'></script>

<script type='text/javascript' src='js/plugins/plupload/plupload.js'></script>
<script type='text/javascript' src='js/plugins/plupload/plupload.gears.js'></script>
<script type='text/javascript' src='js/plugins/plupload/plupload.silverlight.js'></script>
<script type='text/javascript' src='js/plugins/plupload/plupload.flash.js'></script>
<script type='text/javascript' src='js/plugins/plupload/plupload.browserplus.js'></script>
<script type='text/javascript' src='js/plugins/plupload/plupload.html4.js'></script>
<script type='text/javascript' src='js/plugins/plupload/plupload.html5.js'></script>
<script type='text/javascript' src='js/plugins/plupload/jquery.plupload.queue/jquery.plupload.queue.js'></script>    
    
    
<script type='text/javascript' src='js/plugins/pnotify/jquery.pnotify.min.js'></script>
<script type='text/javascript' src='js/plugins/ibutton/jquery.ibutton.min.js'></script>    
<script type='text/javascript' src='js/plugins/scrollup/jquery.scrollUp.min.js'></script>    
<script type='text/javascript' src='js/cookies.js'></script>
<script type='text/javascript' src='js/actions.js'></script>
<script type='text/javascript' src='js/plugins.js'></script>
<script type='text/javascript' src='js/settings.js'></script>
<script type='text/javascript' src='js/faq.js'></script>

<!--<script src="js/jquery.blinker.min.js"></script>-->
<!--<script src="js/highcharts/highcharts.js"></script>
<script src="js/highcharts/exporting.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>-->

<script src="js/highcharts/code/highcharts.js"></script>
<script src="js/highcharts/code/modules/data.js"></script>
<script src="js/highcharts/code/modules/drilldown.js"></script>

<script type="text/javascript" src="../js/jquery.multifile.js"></script>
<script type="text/javascript" src="./js_old/scp.js"></script>
<link rel="stylesheet" type="text/css" href="js_old/jquery.jqplot.min.css" />
<link type="text/css" rel="stylesheet" href="js_old/shThemejqPlot.min.css" />
<script type="text/javascript" src="js_old/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="js_old/jqplot.barRenderer.min.js"></script>
<script type="text/javascript" src="js_old/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="js_old/jqplot.pointLabels.min.js"></script>
<script type="text/javascript" src="js_old/jqplot.pieRenderer.min.js"></script>
<script src="js/jquery.fileuploadmulti.min.js"></script>

<!--
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
<script type="text/javascript">
$(document).ready(function(){
 $('#actions input:submit#button').bind('click', function(e) {
        var formObj = $(this).closest('form');
        e.preventDefault();
        if($('.dialog#confirm-action p#'+this.name+'-confirm').length == 0) {
            alert('Unknown action '+this.name+' - get technical help.');
        } else if(checkbox_checker(formObj, 1)) {
            var action = this.name;
            $('.dialog#confirm-action').undelegate('.confirm');
            $('.dialog#confirm-action').delegate('input.confirm','click.confirm', function(e) {
				e.preventDefault();
                $('.dialog#confirm-action').hide();
                $('#overlay').hide();
                $('input#action', formObj).val(action);
				formObj.submit();
                return false;
             });
            $('#overlay').show();
            $('.dialog#confirm-action .confirm-action').hide();
            $('.dialog#confirm-action p#'+this.name+'-confirm').show().parent('div').show().trigger('click');
			
        }
        return false;
     });
	 });
</script>
<script>
$(document).ready(function () {    
    $('#link_to_content').trigger('click');
});
</script>  
<script>
function show_item(id)
{
    if ($('#show_temp_item_'+id).prop("click")) {
		if ($('#item_temp_section_'+id).css("display") == 'none') {
         $('#item_temp_section_'+id).show();
    	}
    else {
		$('#item_temp_section_'+id).hide();
    }
	}
}
function show_substatus(id)
{
    if ($('#show_temp_status_'+id).prop("click")) {
		if ($('.status_'+id).css("display") == 'none') {
         $('.status_'+id).show();
    	}
    else {
		$('.status_'+id).hide();
    }
	}
}


function update_priority(ticket_id,priority_id){
	$.ajax({
	url:"update_priority_id.php",
	data: "ticket_id="+ticket_id+"&priority_id="+priority_id+"",
	success: function(msg){
		alert('Priority Succesfully Updated.')
}
});
}
</script>
<script>
/*jQuery(function($){
$(".modal-body").blinker({
timeHidden: 1000, // Defines how much time the hidden state will last.
intervalRangeStart: 500, // Defines how much time the visible state will last.
intervalRangeStop: 2000, // Defaults to a random value from 500 to 2000ms.
    
mouseenter: function(){ // pause blinking on mouseenter
$(this).data("blinker").pause();
},
    
mouseleave: function(){ // resume blinking on mouseleave
$(this).data("blinker").blinkagain();
}
});
});*/
</script>
<style>
.report_sub_table{
display:none;
}
</style>
</head>
<body>
    <div class="wrapper fixed green"> 
		<!--Header-->
        <div class="header">
            <a class="logo" href="index.php"><img src="img/logo.png" alt="E-Citizen Grievance Redressal System" title="E-Citizen Grievance Redressal System"/></a>
           <ul class="header_menu">
                <li class="list_icon"><a href="#">&nbsp;</a></li>
           </ul>  
       <div class="header_banner"></div> 
        </div>
		<!--Menu-->
        <div class="menu">
          <div class="breadLine">   
                <div class="arrow"></div>
                <div class="adminControl active">
                    Hello, <?php echo substr($thisstaff->getFirstName().' '.$thisstaff->getLastName(), 0,20);
					 ?>
                </div>
            </div>
            <div class="admin">
                <div class="image">
                    <img src="img/users/profile.png" class="img-polaroid"/>   
                </div>
                <ul class="control">
                    <?php /*?><li><span class="icon-comment"></span> <a href="tickets.php?status=assigned">Notification</a> <a href="" class="caption red">
					<?php 
				$stats= $thisstaff->getTicketsStats();
				echo $stats['assigned'];?></a>                    
                   </li><?php */?>
                   
                    <li><span class="icon-user"></span> <?php echo $thisstaff->getUserName();//.'<br>'.'('.$thisstaff->getGroup().')';
					
					 ?></li>
                    <li><span class="icon-cog"></span> <a href="profile.php">Settings</a></li>
                    <li><span class="icon-share-alt"></span> <a href="logout.php?auth=<?php echo $ost->getLinkToken(); ?>">Logout</a></li>
                </ul>
                <div class="info">
					<?php  
					$query_lastlogin='SELECT  * FROM '.STAFF_TABLE.'  WHERE username = "'.$_SESSION['_staff']['userID'].'"';
					$res_lastlogin=db_query($query_lastlogin) or die('error');
					$row_lastlogin = db_fetch_array($res_lastlogin);
					?>
                    <span>Welcom back! Your last visit: <?php echo $row_lastlogin['lastlogin']; ?></span>
               </div>
            </div>
            <ul class="navigation"> 
			<?php //src="images/small_icon'.$nos.'.png 1)isw-grid
            $nos=1;
			if(($tabs=$nav->getTabs()) && is_array($tabs)){
                foreach($tabs as $name =>$tab) {
					if($name=='tickets')
					{
						 echo sprintf('<li class="openable %s"><a href="%s"><span class="isw-grid"></span><span class="text">%s</span></a>',
   $tab['active']?'active':'',$tab['href'],$tab['desc']);
						}
					else{
						 echo sprintf('<li class="openable %s" ><a href="%s"><span class="isw-grid"></span><span class="text">%s</span></a>',
   $tab['active']?'active':'',$tab['href'],$tab['desc']);
						}
					$str = explode('.',$tab['href']);
                        if(($subnav=$nav->getSubMenu($str[0])) && is_array($subnav)){
                        echo "<ul>";
                            $activeMenu=$nav->getActiveMenu();
                           if($activeMenu>0 && !isset($subnav[$activeMenu-1]))
						   $activeMenu=0;
                                foreach($subnav as $k=> $item) {
                                    $number="pic".$nos;
                                    if($item['droponly']) continue;
                                    $class=$item['iconclass'];
                                    if ($activeMenu && $k+1==$activeMenu or 
									(!$activeMenu && 
									(strpos(strtoupper($item['href']),strtoupper(basename($_SERVER['SCRIPT_NAME']))) !== false or ($item['urls'] && in_array(basename($_SERVER['SCRIPT_NAME']),$item['urls']))
									)
									)
									)
                                    $class="$class active";
echo sprintf('<li><a href="%s" ><span class="icon-th"></span><span class="text">%s</span></a></li>',$item['href'],$item['desc']);					
								}
						echo "</ul>";
                        }
                    echo "</li>";
                    $nos++;	
                }
            } ?>  
            </ul>
			 <div class="dr"><span></span></div>
		 <div class="widget-fluid">
	 		<div id="menuDatepicker"></div>
            </div>
            <div class="dr"><span></span></div> 
           </div>
		<!--Content-->
          <div class="content">
            <div class="breadLine">
                <ul class="breadcrumb">
                <?php  if($thisstaff->isAdmin() && !defined('ADMINPAGE')) { ?>
        <li><a href="admin.php">Admin Panel</a> <span class="divider">></span></li>       
        <?php }else{ ?>
        <li><a href="index.php">Staff Panel</a> <span class="divider">></span></li>                
        <?php } ?>
                </ul>
              </div>
			<!--WorkPlace Start-->
            <div class="workplace">
            <?php
if($ost->getError())
echo sprintf('<div class="alert alert-error"><h4>Error<span style="float:right;margin-right:-7px;margin-top:-3px;pading:0px;"><i class="isb-cancel"></i></span></h4>%s</div>', $ost->getError());
elseif($ost->getWarning())
{
echo sprintf('<div class="alert alert-block"><h4>Alert<span style="float:right;margin-right:-7px;margin-top:-3px;pading:0px;"><i class="isb-cancel"></i></span></h4>%s</div>', $ost->getWarning());
}
elseif($ost->getNotice())
echo sprintf('<div class="alert alert-success"><h4>Success<span style="float:right;margin-right:-7px;margin-top:-3px;pading:0px;"><i class="isb-cancel"></i></span></h4>%s</div>', $ost->getNotice());
?>
<?php if($errors['err']) { ?>
<div class="alert alert-error"><h4>Error!</h4> <?php echo $errors['err']; ?></div>
<?php }elseif($msg) { ?>
<div class="alert alert-success"><h4>Success!</h4><?php echo $msg; ?></div>
<?php }elseif($warn) { ?>
<div class="alert alert-block"><h4><blink>Alert!</blink></h4><?php echo $warn; ?></div>
<?php } ?>
                <div class="row-fluid">
                    <div class="span12">
               <div class="widgetButtons"> 
                         <?php 
                         if(($subnav1=$nav->getSubMenu()) && is_array($subnav1)){
								                             
            $activeMenu=$nav->getActiveMenu();
           if($activeMenu>0 && !isset($subnav[$activeMenu-1]))
           $activeMenu=0;
		        foreach($subnav1 as $k=> $item) {
				if($item['title'] != 'Complaint Dashboard' && $item['title'] != 'Query Dashboard' && $item['title'] != 'My Profile')
				{ 
					if($item['class']!='ibw-r_new')
					{
						$str_item = explode('(',$item['desc']);
						$str_item2 = explode(')',$str_item['1']);
						$number="pic".$nos;
						if($item['droponly']) continue;
						$class=$item['iconclass'];
						if ($activeMenu && $k+1==$activeMenu or 
						(!$activeMenu && 
						(strpos(strtoupper($item['href']),strtoupper(basename($_SERVER['SCRIPT_NAME']))) !== false or ($item['urls'] && in_array(basename($_SERVER['SCRIPT_NAME']),$item['urls']))
						)
						)
						)
						$class="$class active";
						if($str_item2[0]!='')
						echo sprintf('<div class="bb"><a href="%s"class="" ><span class="%s"></span>
						<div class="caption red">%s</div>	</a>								
						</div>',$item['href'],$item['class'],$item['title']);
						else
						echo sprintf('<div class="bb"><a href="%s"  class="" ><span class="%s"></span>
						<div class="caption red">%s</div>	</a>
						</div>',
						$item['href'],$item['class'],$item['title']);
					}
				}
				}
        
						}?>              
                        </div>
                    </div>
                </div>	

<!--<a href="#dModal" role="button" class="btn" id="link_to_content" data-toggle="modal">Default</a>             
<div id="dModal" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" style="top:500px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Default</h3>
            </div>
            <div class="modal-body">
                <p>Sed non urna. Donec et ante. Phasellus eu ligula. Vestibulum sit amet purus. Vivamus hendrerit, dolor at aliquet laoreet, mauris turpis porttitor velit, faucibus interdum tellus libero ac justo. Vivamus non quam. In suscipit faucibus urna.</p>
            </div>
</div>-->