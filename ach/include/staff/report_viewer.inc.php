<!DOCTYPE html>
<html lang="en">
<head>        
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <!--[if gt IE 8]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <![endif]-->
    <title><?php echo ($ost && ($title=$ost->getPageTitle()))?$title:'SECP GRS :: Staff Control Panel'; ?></title>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="favicon.ico" type="image/x-icon">
    
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
<script type='text/javascript' src='js/plugins/pnotify/jquery.pnotify.min.js'></script>
<script type='text/javascript' src='js/plugins/ibutton/jquery.ibutton.min.js'></script>    
<script type='text/javascript' src='js/plugins/scrollup/jquery.scrollUp.min.js'></script>    
<script type='text/javascript' src='js/cookies.js'></script>
<script type='text/javascript' src='js/actions.js'></script>
<script type='text/javascript' src='js/plugins.js'></script>
<script type='text/javascript' src='js/settings.js'></script>
<script type='text/javascript' src='js/faq.js'></script>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="js/highcharts/highcharts.js"></script>
<script src="js/highcharts/highcharts-more.js"></script>
<script src="js/highcharts/exporting.js"></script>

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
</head>
<body>
    <div class="wrapper fixed green"> 
		<!--Header-->        <!--Banner-->
        <div class="header">
            <a class="logo" href="index.php"><img src="img/logo.png" alt="E-Citizen Grievance Redressal System" title="E-Citizen Grievance Redressal System"/></a>
           <ul class="header_menu">
                <li class="list_icon"><a href="#">&nbsp;</a></li>
           </ul>  
       <div class="header_banner"></div> 
        </div>
		<!--Menu-->
       
		<!--Content-->
          <div class="content" style="margin-left:0px;">
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
            <div class="row-fluid">
                <div class="span16">
                    <div class="widgetButtons">
                        <div class="bb">
                            <a class="tipb" title="" href="admin_dashboard.php" data-original-title="Dashboard">
                            	<span class="ibw-new_Complaint"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
<style>
#tSortable_paginate{
display:none;
}
#tSortable_filter{
display:none;
}	
#tSortable_length{
display:none;}

#tSortable_7_paginate{
display:none;
}
#tSortable_7_filter{
display:none;
}	
#tSortable_7_length{
display:none;}	
</style>
<script language="javascript">
var d1 = [];
var d2 = [];
var d3 = [];
var data =[];
</script>
<?php
if(!defined('OSTSTAFFINC') || !$thisstaff || !$thisstaff->isStaff()) die('Access Denied');

$numtotalstaff=0;

$sqltotalstaff='SELECT DISTINCT(t.staff_id), s.username, s.firstname, s.lastname '.
               'FROM '.TICKET_TABLE.' t, '.STAFF_TABLE.' s '.
               'WHERE t.staff_id <> "0" AND t.staff_id=s.staff_id AND s.username='.$_SESSION['_staff']['userID'].'  ';
		if(!$thisstaff->onChairman()){	   
$new_query_added =" AND ticket_id IN (SELECT DISTINCT(`ticket_id`) as ticket_id FROM ".TICKET_EVENT_TABLE." WHERE staff_id='".db_input($thisstaff->getId())." 
	       ' AND `state` = 'assigned') ";
		}else
		{
			$new_query_added ="";
			}
// Today -> Open | Closed | Overdue
$numact3days=0;
 $day_sqlact3days='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  0 >= TIMESTAMPDIFF(DAY,created,NOW())';
 $day_sqlact3daysopen='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  0 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND status = "open"';	
 $day_sqlact3dayscloded='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  0 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND status = "closed"';	
 $day_sqlact3daysover='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  0 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND isoverdue = "1"';					   			   		   	   
$day_resact3days=db_query($day_sqlact3daysopen.$new_query_added);
$day_numact3daysopen=db_num_rows($day_resact3days);//open days
$day_resact3days=db_query($day_sqlact3dayscloded.$new_query_added);
$day_numact3daysclosed=db_num_rows($day_resact3days);//closed days
$day_resact3days=db_query($day_sqlact3daysover.$new_query_added);
$day_numact3daysover=db_num_rows($day_resact3days);//overdue days

//Week -> Open | Closed | Overdue
 $week_sqlact3days='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  7 >= TIMESTAMPDIFF(DAY,created,NOW())';
 $week_sqlact3daysopen='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  7 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND status = "open"';	
 $week_sqlact3dayscloded='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  7 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND status = "closed"';	
 $week_sqlact3daysover='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  7 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND isoverdue = "1"';					   			   		   	   

$week_resact3days=db_query($week_sqlact3daysopen.$new_query_added);
$week_numact3daysopen=db_num_rows($week_resact3days); //open week
$numact7days=$week_numact3daysopen; 
$week_resact3days=db_query($week_sqlact3dayscloded.$new_query_added);
$week_numact3daysclosed=db_num_rows($week_resact3days); //closed week
$week_resact3days=db_query($week_sqlact3daysover.$new_query_added);
$week_numact3daysover=db_num_rows($week_resact3days); //overdue week

//Montly -> Open | Closed | Overdue 
 $month_sqlact3daysopen='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  30 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND status = "open"';	
 $month_sqlact3dayscloded='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  30 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND status = "closed"';	
 $month_sqlact3daysover='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  30 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND isoverdue = "1"';				   			   		   	   

$month_resact3days=db_query($month_sqlact3daysopen.$new_query_added);
$month_numact3daysopen=db_num_rows($month_resact3days); //open months
$month_resact3days=db_query($month_sqlact3dayscloded.$new_query_added);
$month_numact3daysclosed=db_num_rows($month_resact3days); //closed months
$month_resact3days=db_query($month_sqlact3daysover.$new_query_added);
$month_numact3daysover=db_num_rows($month_resact3days); //overdue months

//Yearly -> Open | Closed | Overdue
$year_sqlact3daysopen='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  365 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND status = "open"';
			    	   
$year_sqlact3dayscloded='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  365 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND status = "closed"';
			   
$year_sqlact3daysover='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  365 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND isoverdue = "1"';
		   	   	   
$year_resact3days=db_query($year_sqlact3daysopen.$new_query_added);
$year_numact3daysopen=db_num_rows($year_resact3days); //open year   	   				   	

$year_resact3days=db_query($year_sqlact3dayscloded.$new_query_added);
$year_numact3daysclosed=db_num_rows($year_resact3days); //closed year

$year_resact3days=db_query($year_sqlact3daysover.$new_query_added);
$year_numact3daysover=db_num_rows($year_resact3days); //overdue year
?>

<?php //Query For Open Compalints
        $open_overall_show='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE   status = "open"';	   
  $year_sqlact3daysopenmale='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  365 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND status = "open" AND gender = "male" ';   
  $year_sqlact3daysopenfemale='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  365 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND status = "open" AND gender = "female"';	
	/*OPEN Complaint In Year Wise*/
$open_overall=db_query($open_overall_show.$new_query_added);
$open_complaints=db_num_rows($open_overall); //open overall

$year_resact3daysmale=db_query($year_sqlact3daysopenmale.$new_query_added);
$year_numact3daysopenmale=db_num_rows($year_resact3daysmale); //open male in year

$year_resact3daysfemale=db_query($year_sqlact3daysopenfemale.$new_query_added);
$year_numact3daysopenfemale=db_num_rows($year_resact3daysfemale); //open female in year ?>
    
<?php //Query For Over Compalints   
       $isoverdue_overall_show='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE   isoverdue = "1"';   

  $year_sqlact3daysovermale='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  365 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND isoverdue = "1" AND gender = "male"';
  $year_sqlact3daysoverfemale='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  365 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND isoverdue = "1" AND gender = "female"';			   			   	   				   			   		   	   

/*OVERDUE Complaint In Year Wise*/
$isoverdue_overall=db_query($isoverdue_overall_show.$new_query_added);
$isoverdue_Complaints=db_num_rows($isoverdue_overall); //overdue year

$year_resact3daysmale=db_query($year_sqlact3daysovermale.$new_query_added);
$year_numact3daysovermale=db_num_rows($year_resact3daysmale); //overdue male year
$year_resact3daysfemale=db_query($year_sqlact3daysoverfemale.$new_query_added);
$year_numact3daysoverfemale=db_num_rows($year_resact3daysfemale); //overdue female year ?>    
               
<?php //Query For Closed Compalints
       $closed_overall_show='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE   status = "closed"';	   

  $year_sqlact3daysclodedmale='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  365 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND status = "closed" AND gender = "male"';
  $year_sqlact3daysclodedfemale='SELECT * FROM '.TICKET_TABLE.' '.
               'WHERE  365 >= TIMESTAMPDIFF(DAY,created,NOW()) '.
               'AND status = "closed" AND gender = "female"';			
/*CLOSED Complaint In Year Wise*/
$closed_overall=db_query($closed_overall_show.$new_query_added);
$closed_Compalints=db_num_rows($closed_overall); //closed year

$year_resact3daysmale=db_query($year_sqlact3daysclodedmale.$new_query_added);
$year_numact3daysclosedmale=db_num_rows($year_resact3daysmale); //closed male in year

$year_resact3daysfemale=db_query($year_sqlact3daysclodedfemale.$new_query_added);
$year_numact3daysclosedfemale=db_num_rows($year_resact3daysfemale); //closed femlae year   ?>    
                          

<div class="row-fluid">
        
        <div class="span6">                    
            <div class="head clearfix">
               <div class="isw-grid"></div><h1><?php echo "Assigned Complaint List"; ?></h1>                                 
            </div>
            <div class="block-fluid table-sorting clearfix">
                        <table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable"  >
                        <thead>
                        <tr>
                        <th width="25%">Total</th>
                        <th width="25%">Under Process</th>  
                        <th width="25%">Resovled</th>
                        <th width="25%">Overdue</th>                                   
                        </tr>
                        
                        </thead>
                        
                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                        <tr>
                     
                        <td><?php echo $open_complaints+$closed_Compalints; ?></td>
                        <td><?php echo $open_complaints; ?></td>
                        <td><?php echo $closed_Compalints; ?></td>
                        <td><?php echo $isoverdue_Complaints; ?></td>
                        
                        </tr>
                        
                        
                        </tbody>          
                        </table>

                    </div>
         </div>
        
        <div class="span6">
            <div class="head clearfix">                            
                <h1>OTHER REPORTS</h1>
                
                </div>

   
            <div style="margin:2px;"><a href="comlaintstatus_summary.php"> <button  class="btn" type="button">
<i class="icon-search"></i>
Compalint Status Summary
</button></a>

               <a href="aera_new.php">  <button  class="btn" type="button">
<i class="icon-search"></i>
Overall Complaints Summary
</button></a>

            <a href="report_districtwise.php">     <button  class="btn" type="button">
<i class="icon-search"></i>
Overall District Wise
</button></a></div>
            
                              
        </div>
         
                  
    </div>
            
<!--Complaints Status , Complaint Categories , Geophrical Summary, Line chart , Bar chart -->

<div class="dr"><span></span></div>
<!--Line Chart/Bar Chart-->
<?php 
//===============================Line chart | Bar chart by Status (OPEN,OVERDUE,CLOSE)============================= 
?>
        <div class="row-fluid">
        
        <div class="span6">
        <div class="head clearfix">                            
            <h1>Nature of Complaints </h1>
            <div align="right">
            
            </div>
        </div>
        <div class="block">
        <style type="text/css">
        ${demo.css}
        </style>
        <div id="chart-3"  style="min-width: 310px; max-width: 800px; height: 400px; margin: 0 auto">
        <?php 
        $qselect_topic='SELECT count(ticket_id) as ticketno ,t.topic_id, d.topic,d.topic_img ';
        $qfrom_topic='FROM '.TICKET_TABLE.' t, sdms_help_topic d ';
        $qwhere_topic=' WHERE t.topic_id=d.topic_id group by t.topic_id '; 
        $query_topic="$qselect_topic $qfrom_topic $qwhere_topic ORDER BY t.created DESC ";
        $result_topic = db_query($query_topic);
        $i=0;
        ?>
        <script type="text/javascript">
        <?php 
        while ($row = db_fetch_array($result_topic)) { 
        $data .= "['".$row['topic']."',   ".$row['ticketno']."],";
        } ?>
        $('#chart-3').highcharts({
        chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false
        },
        title: {
        text: ''
        },
        tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
        },
        series: [{
        type: 'pie',
        name: 'Browser share',
        data: [<?php echo $data; ?>]
        }]
        });
        
        </script>
            </div>
        </div>                        
        </div>
        
        <div class="span6">
        <div class="head clearfix">                            
            <h1>Performance Standard</h1>
            
            </div>
 <?php 
		
        $qselect_topic='SELECT count(ticket_id) as ticketno ,t.topic_id, d.topic,d.topic_img ';
$qfrom_topic='FROM '.TICKET_TABLE.' t, sdms_help_topic d ';
$qwhere_topic=' WHERE t.topic_id=d.topic_id group by t.topic_id '; 
$query_topic="$qselect_topic $qfrom_topic $qwhere_topic ORDER BY t.created DESC ";
$result_topic = db_query($query_topic);
 while ($row = db_fetch_array($result_topic)) { 
  $topic_title1 .= "'".$row['topic']."',";
  $topic_nos1 .= $row['ticketno'].",";
 }
?> 
        <style type="text/css">
        ${demo.css}
        </style>
        <div id="performance_standard" style="min-width: 310px; max-width: 800px; height: 400px; margin: 0 auto"></div>
        <script type="text/javascript">
        $(function () {
        $('#performance_standard').highcharts({
        chart: {
        type: 'bar'
        },
        title: {
        text: ''
        },
        xAxis: {
        categories: [<?php echo $topic_title1; ?>]
        },
        yAxis: {
        min: 0,
        title: {
            text: ''
        }
        },
        legend: {
        reversed: true
        },
        plotOptions: {
        series: {
            stacking: 'normal'
        }
        },
        series: [{
        name: 'Inprogress',
        data: [5, 3, 4, 7]
        }, {
        name: 'Resolved',
        data: [2, 2, 3, 2]
        }, {
        name: 'Overdue',
        data: [3, 4, 4, 2]
        }]
        });
        });
        </script>
                          
        </div>
        
              
        </div>
        <div class="row-fluid">
        
        <div class="span12">
        <div class="head clearfix">                            
            <h1>Department Wise Complaints </h1>
            <div align="right">
            
            </div>
        </div>
        <div class="block">
        <style type="text/css">
        ${demo.css}
        </style>
        <div id="containerxyz" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
      <?php 
	  
$qselect='SELECT count(ticket_id) as ticketno ,t.dept_id, d.dept_name ';
$qfrom='FROM '.TICKET_TABLE.' t, sdms_department d ';
$qwhere=' WHERE t.dept_id = d.dept_id AND d.dept_p_id="26" group by t.dept_id ';
$query="$qselect $qfrom $qwhere ORDER BY d.dept_name ";
$result_category = db_query($query);
 while ($row = db_fetch_array($result_category)) { 
	$data_department .= "['".$row['dept_name']."',   ".$row['ticketno']."],";
	
}
 ?>  
        <script type="text/javascript">
        $(function () {
        $('#containerxyz').highcharts({
        chart: {
        type: 'column'
        },
        title: {
        text: ''
        },
        subtitle: {
        text: ''
        },
        xAxis: {
        type: 'category',
        labels: {
            rotation: -45,
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
        },
        yAxis: {
        min: 0,
        title: {
            text: 'Departments'
        }
        },
        legend: {
        enabled: false
        },
        tooltip: {
        pointFormat: '<b>{point.y:.1f}</b>'
        },
        series: [{
        name: 'Population',
        data: [<?php echo $data_department; ?>],
        dataLabels: {
            enabled: true,
            rotation: -90,
            color: '#FFFFFF',
            align: 'right',
            format: '{point.y:.1f}', // one decimal
            y: 10, // 10 pixels down from the top
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
        }]
        });
        });
        </script>
        </div>
        </div>                        
        </div>
        <div class="row-fluid">
        
        <div class="span12">
        <div class="head clearfix">                            
            <h1>Agencies Wise Complaints </h1>
            <div align="right">
            
            </div>
        </div>
        <div class="block">
        <style type="text/css">
        ${demo.css}
        </style>
        <div id="containeAgencies" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
             <?php 
	  
$qselect='SELECT count(ticket_id) as ticketno ,t.dept_id, d.dept_name ';
$qfrom='FROM '.TICKET_TABLE.' t, sdms_department d ';
$qwhere=' WHERE t.dept_id = d.dept_id AND d.dept_p_id="51" group by t.dept_id ';
$query="$qselect $qfrom $qwhere ORDER BY d.dept_name ";
$result_category = db_query($query);
 while ($row = db_fetch_array($result_category)) { 
	$data_agencies .= "['".$row['dept_name']."',   ".$row['ticketno']."],";
	
}
 ?>    
        <script type="text/javascript">
        $(function () {
        $('#containeAgencies').highcharts({
        chart: {
        type: 'column'
        },
        title: {
        text: ''
        },
        subtitle: {
        text: 'Source: <a href="http://en.wikipedia.org/wiki/List_of_cities_proper_by_population">Wikipedia</a>'
        },
        xAxis: {
        type: 'category',
        labels: {
            rotation: -45,
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
        },
        yAxis: {
        min: 0,
        title: {
            text: ''
        }
        },
        legend: {
        enabled: false
        },
        tooltip: {
        pointFormat: '<b>{point.y:.1f} millions</b>'
        },
        series: [{
        name: 'Population',
        data: [<?php echo $data_agencies; ?>    ],
        dataLabels: {
            enabled: true,
            rotation: -90,
            color: '#FFFFFF',
            align: 'right',
            format: '{point.y:.1f}', // one decimal
            y: 10, // 10 pixels down from the top
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
        }]
        });
        });
        </script>
        </div>
        </div>                        
        </div>
        <div class="row-fluid">
        
        <div class="span12">
        <div class="head clearfix">                            
            <h1>Category Wise Complaints </h1>
            <div align="right">
            
            </div>
        </div>
        <div class="block">
        <style type="text/css">
        ${demo.css}
        </style>
        <div id="compalinttopic" style="min-width: 310px; height: 400px; margin: 0 auto"></div> 
        <?php 
		
        $qselect_topic='SELECT count(ticket_id) as ticketno ,t.topic_id, d.topic,d.topic_img ';
$qfrom_topic='FROM '.TICKET_TABLE.' t, sdms_help_topic d ';
$qwhere_topic=' WHERE t.topic_id=d.topic_id group by t.topic_id '; 
$query_topic="$qselect_topic $qfrom_topic $qwhere_topic ORDER BY t.created DESC ";
$result_topic = db_query($query_topic);
 while ($row = db_fetch_array($result_topic)) { 
  $topic_title .= "'".$row['topic']."',";
  $topic_nos .= $row['ticketno'].",";
 }
?> 
        <script type="text/javascript">
        $(function () {
        $('#compalinttopic').highcharts({
        chart: {
        type: 'bar'
        },
        title: {
        text: ''
        },
        subtitle: {
        text: ''
        },
        xAxis: {
		categories: [<?php echo $topic_title; ?>],
		
        title: {
            text: null
        }
        },
        yAxis: {
        min: 0,
        title: {
            text: '',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        }
        },
        tooltip: {
        valueSuffix: ' complaint'
        },
        plotOptions: {
        bar: {
            dataLabels: {
                enabled: true
            }
        }
        },
        legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -40,
        y: 100,
        floating: true,
        borderWidth: 1,
        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
        shadow: true
        },
        credits: {
        enabled: false
        },
        series: [ {
        name: 'Year 2015',
		color: '#E52984',
        data: [<?php echo $topic_nos; ?>]
        }]
        });
        });
        </script>
        </div>
        </div>                        
        </div> 
    </div>
    
<div class="dr"><span></span></div>

</div><!--WorkPlace End-->  
</div> 
   