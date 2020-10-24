<?php include('includes/header.php'); ?>
<!-- navbar end -->
<!-- complaint statu start -->

<div class="container-fluid">
	<div class="container" style="background-color: white">
		<div class="row">
			
			<div class="col-xs-12" style="background-color: #E1EAEF;">
				<img class="img-responsive" src="assets/images/guidline.jpg" width="100%">
			</div>
				<div class="col-xs-12">
					<hr>
				</div>
				<div class="col-xs-12">
				<button class="accordion">Capital Markets</button>
					<div class="panel">
					  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
					  <input type="button" name="market" class="btn btn-primary float-right" value="Download" style="border: 3px solid white;">
					</div>
					<button class="accordion">Insurance</button>
					<div class="panel">
					  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
					  <input type="button" name="market" class="btn btn-primary float-right" value="Download" style="border: 3px solid white;">
					</div>
					<button class="accordion">Specialized Companies</button>
					<div class="panel">
					  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
					  <input type="button" name="market" class="btn btn-primary float-right" value="Download" style="border: 3px solid white;">
					</div>
					<button class="accordion">e-Services</button>
					<div class="panel">
					  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
					  <input type="button" name="market" class="btn btn-primary float-right" value="Download" style="border: 3px solid white;">
					</div>
					<button class="accordion">Company Registration</button>
					<div class="panel">
					  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
					  <input type="button" name="market" class="btn btn-primary float-right" value="Download" style="border: 3px solid white;">
					</div>
					<button class="accordion">Company Supervision</button>
					<div class="panel">
					  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
					  <input type="button" name="market" class="btn btn-primary float-right" value="Download" style="border: 3px solid white;">
					</div>
				</div>
		</div>
	</div>
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
	<!-- modal section Start -->

<!-- Javascript -->
<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].onclick = function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  }
}

	$('button').on('click',function(){
		$(this).toggleClass('text-color');
	})

</script>

</body>
