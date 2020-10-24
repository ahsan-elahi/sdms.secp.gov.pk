<?php include('includes/header.php'); ?>
<!-- navbar end -->
<!-- complaint statu start -->
<style type="text/css">
	#mask {
  position: absolute;
  left: 0;
  top: 0;
  z-index: 9000;
  background-color: #000;
  display: none;
}
#boxes .window {
  position: absolute;
  left: 0;
  top: 0;
  width: 440px;
  height: 200px;
  display: none;
  z-index: 9999;
  padding: 20px;
  border-radius: 15px;
  text-align: center;
}
 
#boxes #dialog {
  width: 750px;
  height: 300px;
  padding: 10px;
  background-color: #ffffff;
  font-family: 'Segoe UI Light', sans-serif;
  font-size: 15pt;
}
 
#popupfoot {
  font-size: 16pt;
  position: absolute;
  bottom: 0px;
  width: 250px;
  left: 250px;
}

</style>
<div id="boxes">
  <div id="dialog" class="window">
    Are You Sure To Launch Complaint
    <div class="row">
    <div class="col-xs-offset-3 col-xs-6">
    	<input type="button" name="close" class="btn btn-primary" id="close" value="close" style="margin-top: 60px;">
    </div>
    </div>
    <div id="popupfoot"> </div>
  </div>
  <div id="mask"></div>
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


</body>
<script type="text/javascript">
	$(document).ready(function() { 
 
			var id = '#dialog';
			//Get the screen height and width
			var maskHeight = $(document).height();
			var maskWidth = $(window).width();
			   
			//Set heigth and width to mask to fill up the whole screen
			$('#mask').css({'width':maskWidth,'height':maskHeight});
			//transition effect
			$('#mask').fadeIn(500);
			$('#mask').fadeTo("slow",0.9); 
			//Get the window height and width
			var winH = $(window).height();
			var winW = $(window).width();
			               
			//Set the popup window to center
			$(id).css('top',  winH/2-$(id).height()/2);
			$(id).css('left', winW/2-$(id).width()/2);
			   
			//transition effect
			$(id).fadeIn(2000);  
			//if close button is clicked
			$('.window .close').click(function (e) {
			//Cancel the link behavior
			e.preventDefault();
			$('#mask').hide();
			$('.window').hide();
			});
			 
			//if mask is clicked
			$('#mask').click(function () {
			$(this).hide();
			$('.window').hide();
			});

			$('#close').on('click', function(){
				$('#boxes').hide();
			});

		});

</script>