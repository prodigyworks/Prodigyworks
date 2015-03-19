 <script>
 $(document).ready(
		 function() {
			$("#services_menu").hover(
					function () {
						$("#submenu2").slideDown();
				  	},
				  	function () {
				  	}
				);

			$("#submenu2").hover(
					function () {
				  	},
				  	function () {
						$("#submenu2").slideUp();
				  	}
				);

			$(".submenu li").hover(
			  function () {
			    $(this).addClass("hover");

			  },
			  function () {
				$(this).removeClass("hover");
			  }
			);

			if ($("a[rel^='prettyPhoto']").length) {
					// prettyPhoto
					$("a[rel^='prettyPhoto']").prettyPhoto({theme:'facebook'});

					///// codegrabber ////////////
					$(".code a.code-icon").toggle(function(){
						$(this).addClass("minus").next("p").slideDown();
					}, function(){
						$(this).removeClass("minus").next("p").slideUp();
					})
			}
		 }
	 );

 </script>

  <!--[if lt IE 9]>
  	<script type="text/javascript" src="js/html5.js"></script>
	<style type="text/css">
		.bg{ behavior: url(js/PIE.htc); }
	</style>
  <![endif]-->
	<!--[if lt IE 7]>
		<div style=' clear: both; text-align:center; position: relative;'>
			<a href="http://www.microsoft.com/windows/internet-explorer/default.aspx?ocid=ie6_countdown_bannercode"><img src="http://www.theie6countdown.com/images/upgrade.jpg" border="0"  alt="" /></a>
		</div>
	<![endif]-->

	<script src="http://widgets.twimg.com/j/2/widget.js"></script>
</head>

<body id="page1" onmouseup="$('#submenu2').hide();">
		<div class="main">
			<!--header -->
			<header>
				<div class="wrapper">
				<nav>
					<ul id="menu">
						<li id="home_menu"><a href="index.php"><span>H</span>ome</a></li>
						<li id="services_menu"><a href="services.php"><span>S</span>ervices</a></li>
						<li id="contact_menu"><a href="contactus.php"><span>C</span>ontact Us</a></li>

					</ul>

		            <script>
						document.getElementById("<?php echo $activeMenu ?>").className = "menu_active";
					</script>

					<UL id="submenu2" class="submenu">
						<LI><a href="web-design.php"><span>W</span>eb Site Development</a></LI>
						<LI><a href="services.php"><span>B</span>espoke Development</a></LI>
						<LI><a href="services.php"><span>M</span>obile Development</a></LI>
						<LI><a href="services.php"><span>E</span>-Commerce</a></LI>
					</UL>
				</nav>
				</div>
				<h1><a href="index.php" id="logo">Prodigy<em>Works!</em></a></h1>
			</header>
			<h1 class="subline">Web software<br><span id="spacer">&nbsp;</span>development<img src="images/artwork.png" /></h1>
			<!--header end-->
			<section id="content">
				<div class="wrapper">
					<article class="col1">
						<?php include($pageName) ?>
	        		</article>
					<article class="col2">
						<h3 class="latestWorks">Latest Works</h3>
						<ul class="ul_works">
							<li>
								<a class="lightbox-image" rel="prettyPhoto[group1]" href="images/screenshot-choresaway-large.png" >
									<img src="images/choresaway-screenshot.png" alt="" />
								</a>
							</li>
							<li>
								<a class="lightbox-image" rel="prettyPhoto[group1]" href="images/screenshot-chuk-large.png" >
									<img src="images/chuk-screenshot.png" alt="" />
								</a>
							</li>
						</ul>
						<h4 class="tweets">Recent Tweets</h4>
						<div >
							<script>
							new TWTR.Widget({
							  version: 2,
							  type: 'profile',
							  rpp: 11,
							  interval: 6000,
							  width: 'auto',
							  height: 195,
							  theme: {
							    shell: {
							      background: 'transparent',
							      color: '#ffffff'
							    },
							    tweets: {
							      background: 'transparent',
							      color: '#ffffff',
							      links: '#ffffff'
							    }
							  },
							  features: {
							    scrollbar: false,
							    loop: false,
							    live: true,
							    hashtags: false,
							    timestamp: false,
							    avatars: false,
							    behavior: 'all'
							  }
							}).render().setUser('ProdigyWrks').start();
							</script>
						</div>
	        		</article>
				</div>

			</section>
			<!--content end-->
			<!--footer -->
			<footer>
				<div class="wrapper">
					<article class="col">
						<h5>Why Us</h5>
						<ul class="list1">
							<li><a href="#">Commercial expertise</a></li>
							<li><a href="#">Website rental system</a></li>
							<li><a href="#">All under one roof</a></li>
						</ul>
					</article>
					<article class="col pad_left2">
						<h5>Links</h5>
						<ul class="list1">
							<li><a href="http://www.choresaway.co.uk">Chores Away</a></li>
							<li><a href="http://www.denbyequestrian.com">Denby Equestrian</a></li>
							<li><a href="http://www.centralhydraulicsuk.com">Central Hydraulic UK</a></li>
						</ul>
					</article>
					<article class="col pad_left2">
						<h5>Follow Us</h5>
						<ul class="icons">
							<li><a href="#"><img src="images/icon1.jpg" alt="">Facebook</a></li>
							<li><a href="#"><img src="images/icon2.jpg" alt="">LinkedIn</a></li>
							<li><a href="http://www.twitter.com/ProdigyWrks"><img src="images/icon3.jpg" alt="">Twitter</a></li>
						</ul>
					</article>
					<article id="newsletter">
						<h5>Newsletter</h5>
						<form id="newsletter_form">
							<div class="wrapper">
								<input class="input" type="text" value="Enter your email here"  onblur="if(this.value=='') this.value='Enter your email here'" onfocus="if(this.value =='Enter your email here' ) this.value=''" >
							</div>
							<a href="#" onclick="document.getElementById('newsletter_form').submit()">Subscribe</a>
						</form>
					</article>
				</div>
					<span class="copyright">
						Copyright 2011 ProdigyWorks Ltd.
					</span>
					<a href="index.php" class="footer_logo">Prodigy<span>Works</span>.co.uk</a>

<!--					<p><a href="http://www.freedigitalphotos.net/images/view_photog.php?photogid=1970">Image: winnond / FreeDigitalPhotos.net</a></p> -->
			</footer>
			<!--footer end-->
		</div>
<script type="text/javascript"> Cufon.now(); </script>
</body>
</html>