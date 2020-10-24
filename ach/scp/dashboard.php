<?php

require('staff.inc.php');

$nav->setTabActive('dashboard');

require(STAFFINC_DIR.'header.inc.php');

?>  

<script language="javascript">

 var d1 = [];

  var d2 = [];

   var d3 = [];

</script>

<?php 

if($_REQUEST['staffid']) {

  $staffid = $_REQUEST['staffid'];

}

if ($staffid)

{

	

$sqlact3days='SELECT * FROM '.TICKET_TABLE.' '.

'WHERE staff_id <> "0" AND 30 >= TIMESTAMPDIFF(DAY,created,NOW()) '.

'AND status = "open" and staff_id = '.$staffid.'';

}

else

{	

	for($i=0;$i<=3;$i++)    

	{

		   

	$month=date("m", mktime(0,0,0, date('m') - $i));  

	$sqlopen='SELECT * FROM '.TICKET_TABLE.' '.'WHERE  month(created)='.$month.' '.'AND status = "open"';

	$sqlopen_query=db_query($sqlopen);

	$row_sqlopen = db_fetch_array($sqlopen_query);

	$six_open_month .=  '"'.date ("F", mktime(0,0,0,$month,1,0)).'"'.',';

	$open_month_ticket .=  db_num_rows($sqlopen_query).',';	

	//$open_month .= date('m',strtotime($row_sqlopen['created'])).','. db_num_rows($sqlopen_query).',';	

	?>

	<script language="javascript">

	//d1.push([<?php //echo $month; ?>, <?php //echo db_num_rows($sqlopen_query); ?>]);

	</script>

	<?php

    $sqlcloded='SELECT * FROM '.TICKET_TABLE.' '.'WHERE  month(created)='.$month.' '.'AND status = "closed"';

    $sqlcloded_query=db_query($sqlcloded);

    $row_sqlcloded = db_fetch_array($sqlcloded_query);

	$close_month_ticket .=  db_num_rows($sqlcloded_query).',';

    //$close_month .=date('m',strtotime($row_sqlcloded['created'])).','.db_num_rows($sqlcloded_query).',';

    ?>

    <script language="javascript">

   // d2.push([<?php //echo $month; ?>, <?php //echo db_num_rows($sqlcloded_query); ?>]);

    </script>

	<?php 

    $sqlover='SELECT * FROM '.TICKET_TABLE.' '.'WHERE  month(created)='.$month.' '.'AND isoverdue = "1"';

    $sqlover_query=db_query($sqlover);

    $sqlover_row = db_fetch_array($sqlover_query);

	$over_month_ticket .=  db_num_rows($sqlover_query).',';

    //$overdue_month .=date('m',strtotime($sqlover_row['created'])).','.db_num_rows($sqlover_query).',';

    ?>

    <script language="javascript">

    //d3.push([<?php //echo $month; ?>, <?php //echo db_num_rows($resact3days); ?>]);

    </script>

<?php 

	}				   		   	

} 

?>

<div class="page-header"><h1>Complaints  <small> Activity</small></h1></div>

<div class="dr"><span></span></div>  



                <div class="row-fluid">

                    

                    <div class="span12">

                        <div class="head clearfix">                            

                            <h1>Bar chart</h1>

                            <div align="right">

                <table style="margin-top:10px;right:5px;font-size: 11px; color:#333">

                    <tbody>

                        <!--<tr>

                        <td class="legendColorBox">

                            <div style="#DDD">

                            <div style="width:15px;height:0;border:3px solid rgb(247,164,0);overflow:hidden"></div>

                            </div>

                            </td>

                            <td class="legendLabel">

                            <span>OPEN</span>

                            </td>

                             <td class="legendColorBox">

                            <div style="#DDD">

                            <div style="width:15px;height:0;border:3px solid rgb(60,191,234);overflow:hidden"></div>

                            </div>

                            </td>

                            <td class="legendLabel">

                            <span>OVERDUE</span>

                            </td>

                            <td class="legendColorBox">

                            <div style="#DDD">

                            <div style="width:15px;height:0;border:3px solid rgb(88,124,160);overflow:hidden"></div>

                            </div>

                            </td>

                            <td class="legendLabel">

                            <span>CLOSE</span>

                            </td>

                           

                            

                        </tr>-->
                        <tr>
                        
                            <td class="legendLabel"><img src="img/open.png"  />
                            <span style="color:#FFF;">Under Process</span>
                            </td>
                            <td></td>
                             
                            
                           
                            
                            <td class="legendLabel"><img src="img/close.png"  />
                            <span style="color:#FFF;">Overdue</span>
                            </td>
                           <td></td>
                            
                             <td class="legendLabel"><img src="img/resolved.png"  />
                            <span style="color:#FFF;">Closed</span>
                            </td>
                            <td></td>
                        </tr>

                    </tbody>

                </table>

                </div>

                        </div>

                        <div class="block">

                            <canvas id="barChart"/>

                            <script type="text/javascript"> 

           var bctx = $("#barChart").get(0).getContext("2d");

           $("#barChart").attr('width',$("#barChart").parent('div').width()).attr('height',300);

           

           barChart = new Chart(bctx).Bar({

                

                labels :[<?php echo $six_open_month;?> ],

                series: [{label: 'Beans'},{label: 'Oranges'},{label: 'Crackers'}],

                datasets : [

                        {

                                
								 fillColor : "rgba(0,102,204,0.6)",
                                strokeColor : "rgba(220,220,220,1)",
                                data : [<?php echo $open_month_ticket; ?>]

                        },

                        {

                                fillColor : "rgba(255,0,0,0.6)",
                                strokeColor : "rgba(151,187,205,1)",
                                data : [<?php echo $over_month_ticket; ?>]

                        },

                        {

                                 
								fillColor : "rgba(0,153,0,0.6)",
                                strokeColor : "rgba(151,187,205,1)",
                                data : [<?php echo $close_month_ticket; ?>]

                        }

                ]

            });
			
			

                </script>

                        </div>                        

                    </div>                    

                </div>

</div></div>

<?php include(STAFFINC_DIR.'footer.inc.php');?>



<script type="text/javascript" src="js/raphael-min.js"></script>

<script type="text/javascript" src="js/g.raphael.js"></script>

<script type="text/javascript" src="js/g.line-min.js"></script>

<script type="text/javascript" src="js/g.dot-min.js"></script>

<script type="text/javascript" src="js/bootstrap-tab.js"></script>

<script type="text/javascript" src="js/dashboard.inc.js"></script>



<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>

<link rel="stylesheet" type="text/css" href="css/dashboard.css"/>

