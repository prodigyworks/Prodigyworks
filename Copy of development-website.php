<?php include("system-header.php") ?>

<!--  Start of content -->
<h2>Web Development</h2>
<div>
	
	<div id="webDescription">
		<div class="sideMenu">
			<ul>
				<li show="webdesign"><img src="images/WebDesign.png" width=32 height=32 /><a href="#"><span>Web site design</span></a></li>
				<li show="webresurgence"><img src="images/Painting.png" width=32 height=32 /><a href="#"><span>Web site resurgence</span></a></li>
				<li><img src="images/web-hosting.png" width=32 height=32 /><a href="#"><span>Web site hosting</span></a></li>
				<li><img src="images/Billing.png" width=32 height=32 /><a href="#"><span>Web site rental</span></a></li>
			</ul>
			
			<script>
				$(".sideMenu li").hover( 
				  function () { 
				    $(this).addClass("hover"); 
				    $(".surround").hide();
				    $(eval(this.show)).show();
				    
				  },  
				  function () { 
					$(this).removeClass("hover"); 
				  } 
				);
			</script>
			
			<img src="images/SkinArt.png" width=100 />
		</div>	
	
		<div id="webdesign" class="surround">
			<h2 class="subheading">What we will provide for you.</h2>
			<ul>
				<li>Design your web site from scratch.</li>
				<li>With your input decide on the style and layout.</li>
				<li>Integrate your web site to social network sites.</li>
				<li>Increase your sites visibility with search engines with optimised coding standards.</li>
				<li>Host your site for an annual charge. The first year is built into the prices of the site.</li>
				<li>Provide you with £50 worth of Google Adwords vouchers.</li>
				<li>Create you a Adwords campaign.</li>
			</ul>
		  
		  	<div class="webContentContainer">
			  	<div class="topLeft">
			  	</div>
			  
			  	<div class="topRight">
			  	</div>
			  
			  	<div class="bottomLeft">
			  	</div>
			  
			  	<div class="bottomRight">
			  	</div>
		  	</div>
		</div>
	
		<div id="webresurgence" class="surround">
			<h2 class="subheading">What we will provide for you.</h2>
			<ul>
				<li>Give your website anything from a lick of paint to a complete makeover to refocus client attention.</li>
				<li>Update old irrelevant content.</li>
				<li>Integrate full browser compatibility.</li>
			</ul>
		  
		  	<img src="images/paintbrushes.png" />
		</div>
	</div>
</div>
<!--  End of content -->

<?php include("system-footer.php") ?>
