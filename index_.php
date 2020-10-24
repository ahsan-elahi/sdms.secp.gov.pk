<link rel="stylesheet" type="text/css" href="assets/js/bootstrap.js">
<?php include('includes/header.php'); ?>
<div class="container-fluid">
	<div class="container" style="background-color: white;">
		<div class="row">
		<div class="col-lg-12 col-xs-12 text-center nav_links" style="color: #a2a2a2;">
			<div class="col-xs-12">
			<h3 class="padding-top-10">WELCOME TO SECP'S SERVICE DESK MANAGEMENT SYSTEM</h3>
			  	<div class="col-xs-12">
			  		<img class="img-responsive" src="assets/images/urdu_para.png" style="margin: auto;">
			  	</div>
			  	
				<?php if(!isset($_REQUEST['action'])) {?>
			    <div class="col-xs-12 padding-top-10">
				    <div class="padding-top-10">
                    
							<div class="modal-body">
          <p style="color: red;
    font-size: 16px;
    text-align: center;" >Please make desired selection from the above menu before proceeding</p>
          <p style="color: red;
    font-size: 16px;
    text-align: center;">ابراہ کرم آگے بڑھنے سے پہلے مندرجہ بالا مینو میں سے مطلوبہ انتخاب کریں</p>
        </div>
					
                        
                    </div>
			    </div>
                <?php }else{?>
                <div class="col-xs-12 padding-top-10">
			  		<h3 class="padding-top-10"> Select Language: </h3>
			  	</div>
			  	<div class="col-xs-12">
			    	<img class="img-responsive" src="assets/images/select_urdu.png" style="margin: auto;">
			    </div>
                <div class="col-xs-12 padding-top-10">
				    <div class="padding-top-10">
                  
							<?php if($_REQUEST['action']=='complaint'){?>
                            <a href="ach/open.php">
         <input class="btn btn-primary btn-lg btn-lang" id="example-vertical-h-1" type="button" name="english" value="English">
        </a>
                            <?php }
							elseif($_REQUEST['action']=='query'){ ?>
                            <a href="ach/open_query.php">
         <input class="btn btn-primary btn-lg btn-lang" id="example-vertical-h-1" type="button" name="english" value="English">
        </a>
							<?php }?>
				 
                        
                    </div>
			    </div> 
			    <div class="col-xs-12 padding-top-10">
				    <div class="padding-top-10">
                  
							<?php if($_REQUEST['action']=='complaint'){?>
                            <a href="ach/open.php?lang=urdu">
         <input class="btn btn-success btn-lg btn-lang"  type="button" name="urdu" value="اردو">
        </a>
                            <?php }elseif($_REQUEST['action']=='query'){ ?>
                            <a href="ach/open_query.php?lang=urdu">
         <input class="btn btn-success btn-lg btn-lang" type="button" name="urdu" value="اردو">
        </a>
							<?php }?>
					
                    </div>
			    </div>
                <div class="padding-top-10">&nbsp;</div>
				<?php }?> 
                
			</div>
		</div>
				
			</div>
		</div>
	</div>
</div>

	 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">ALERT</h4>
        </div>
        <div class="modal-body">
          <p style="text-align:center" >“Before proceeding, please select between Query OR Complaint from the above menu” </p>
          <p style="text-align:center">آگے بڑھنے سے پہلے، اوپر کے مینو سے سوال یا شکایت کے درمیان کا انتخاب کریں</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<?php include('includes/footer.php') ?>