<?php include("system-header.php") ?>

<!--  Start of content -->
 <script>
 $(document).ready(
		 function() {
			   $('#nivo_slider').nivoSlider({
			        effect:'fold', //Specify sets like: 'fold,fade,sliceDown, sliceDownLeft, sliceUp, sliceUpLeft, sliceUpDown, sliceUpDownLeft' 
			        slices:7,
			        animSpeed:500,
			        pauseTime:6000,
			        startSlide:0, //Set starting Slide (0 index)
			        directionNav:true, //Next & Prev
			        directionNavHide:false, //Only show on hover
			        controlNav:true, //1,2,3...
			        controlNavThumbs:false, //Use thumbnails for Control Nav
			        controlNavThumbsFromRel:false, //Use image rel for thumbs
			        controlNavThumbsSearch: '.jpg', //Replace this with...
			        controlNavThumbsReplace: '_thumb.jpg', //...this in thumb Image src
			        keyboardNav:true, //Use left & right arrows
			        pauseOnHover:true, //Stop animation while hovering
			        manualAdvance:false, //Force manual transitions
			        captionOpacity:1, //Universal caption opacity
			        beforeChange: function(){
			        	var index = $('#nivo_slider').data('nivo:vars').currentSlide;

			           	$("#slideDescription0").fadeOut();
			           	$("#slideDescription1").fadeOut();
			           	$("#slideDescription2").fadeOut();
			           	$("#slideDescription3").fadeOut();
			        },
			        afterChange: function(){
			           	var index = $('#nivo_slider').data('nivo:vars').currentSlide;

			           	if (index == 0) {
			               	$("#slideDescription0").fadeIn();
			               	
			           	} else if (index == 1) {
			               	$("#slideDescription1").fadeIn();
			               	
			           	} else if (index == 2) {
			               	$("#slideDescription2").fadeIn();
			               	
			           	} else if (index == 3) {
			               	$("#slideDescription3").fadeIn();
			           	}
			        },
			        slideshowEnd: function(){} //Triggers after all slides have been shown
			    });
		 }
	 );
					 
 </script>
<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	try{ 
	var pageTracker = _gat._getTracker("UA-25713610");
	pageTracker._trackPageview();
	} catch(err) {} 
</script>
<div id="newLogo"></div>

<h2>About Us</h2>
<div class="pad_left1 pad_bot1">
	<div id="slider">
		<div id="for_img">
			<div id="nivo_slider">
				<img src="images/www2.png" alt="">	
				<img src="images/software.png" alt="">	
				<img src="images/ecommerce.png" alt="">	
				<img src="images/mobile.png" alt="">	
				
			</div>
		</div>
	</div>
	<div class="pad">
		<div id="slideDescriptionContainer">
			<div id="slideDescription0">
				<p>
					At <strong>Prodigy Works</strong> we understand that your web site is the public portal to the world. We specialise in the creative process of designing, creation and hosting of your site.
				</p>
			</div>
			<div id="slideDescription1">
				<p>
					We traditionally deliver web-based software products for an array of clients. However as our engineers have over 20 years of experience, we can turn our hand at almost all environments.
				</p>
			</div>
			<div id="slideDescription2">
				<p>
					Build and manage your online shop with Prodigy Works! <br>E-commerce solution. <br>
				</p>
			</div>
			<div id="slideDescription3">
				<p>
					Our mobile development division are dedicated to Windows Mobile development within a .NET environment.
				</p>
			</div>
		</div>
	</div>
</div>
<h2><span>Our Solutions</span></h2>
<div class="pad_left1">
	<div class="pad_left1">
		<div class="wrapper pad_top1">
			<div class="col3">
				<div class="wrapper">
					<figure class="left marg_right1"><img src="images/page1_img3.png" alt=""></figure>
					<p class="cols"><strong class="font1">Development</strong><br>
						We have over 20 years of experience of development within the IT industry, which covers multiple environments.
					</p>
				</div>	
			</div>
			<div class="col3 pad_left2">
				<div class="wrapper">
					<figure class="left marg_right1"><img src="images/page1_img5.png" alt=""></figure>
					<p class="cols"><strong class="font1">Web Site Rental</strong><br>
						If your company would prefer. We will design, create, optimise and host your web site
						for a monthly <a href="rentalcharges.html">fee.</a>
					</p>
				</div>
			</div>
		</div>
		<div class="wrapper">
			<div class="col3">
				<div class="wrapper">
					<figure class="left marg_right1"><img src="images/page1_img4.png" alt=""></figure>
					<div class="cols"><strong class="font1">Planning</strong><br>
						Consistent development framework providing repeatable process for estimating and planning IT projects.
					</div>
				</div>	
			</div>
			<div class="col3 pad_left2">
				<div class="wrapper">
					<figure class="left marg_right1"><img src="images/page1_img6.png" alt=""></figure>
					<div class="cols">
						<strong class="font1">E-Commerce</strong><br>
							Build and manage your online shop with Prodigy Works! <br>E-commerce solution. <br>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--  End of content -->

<?php include("system-footer.php") ?>
		