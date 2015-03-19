<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta http-equiv="Content-Language" content="en-uk">
<meta name="keywords" content="web, design, Web design, Web site design, PDA software, web site design and hosting, Website design, hosting, Derbyshire, Heanor, Ripley, Amber Valley, web site, designing, designer, developer, e-shop, e-commerce, e commerce, online, store, software development, cheap web sites, e-commerce development, e-commerce software, mobile software development, bespoke software development, web site rental, renting web site, cheap e-commerce site, e-commerce web site">
<meta name="description" content="We provide high quality web site design, hosting, maintenance, E-commerce services to business throughout the UK. Based in Derbyshire, we are ideally located to serve the Midlands.">

<META name="ROBOTS" content="INDEX,FOLLOW">
<TITLE>Prodigy Works!</TITLE>

<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
<link rel="stylesheet" type="text/css" href="css/jquery.css" />
  
<link rel="stylesheet" href="css/reset.css" type="text/css" media="all"></link>
<link rel="stylesheet" href="css/layout.css" type="text/css" media="all"></link>
<link rel="stylesheet" href="css/style.css" type="text/css" media="all"></link>
<link rel="stylesheet" href="css/prettyPhoto.css" type="text/css" media="all"></link>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery.nivo.slider.pack.js"></script> 
<script type="text/javascript" src="js/jquery.prettyPhoto.js"></script> 
<script type="text/javascript" src="js/cufon-yui.js"></script>
<script type="text/javascript" src="js/hover-image.js"></script>
<script type="text/javascript" src="js/cufon-replace.js"></script>  
<script type="text/javascript" src="js/CabinSketch_700.font.js"></script>
<script type="text/javascript" src="js/EB_Garamond_400.font.js"></script>
<script src="script/jquery.treeview.js"></script>
<script src="http://widgets.twimg.com/j/2/widget.js"></script>

<script>
 $(document).ready(
		 function() {
$(".menuitem").hover( 
				function () { 
					var child = $(this).find('ul');
					
					child.css("left", $(this).attr("offsetLeft"));
					child.show();
			  	},  
			  	function () { 
					var child = $(this).find('ul');
					
			  		child.hide();
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

	
<?php
	//Include database connection details
	require_once('system-config.php');
?>

</HEAD>
<body id="page1">
	<!--[if lt IE 7]>
		<div style=' clear: both; text-align:center; position: relative;'>
			<a href="http://www.microsoft.com/windows/internet-explorer/default.aspx?ocid=ie6_countdown_bannercode"><img src="http://www.theie6countdown.com/images/upgrade.jpg" border="0"  alt="" /></a>
		</div>
	<![endif]-->
<!-- wrapper end -->
<?php	
	
	if (isAuthenticated()) {
		include("system-maintainSave.php") ; 
	}
?>
		<div class="main">
			<!--header -->
			<header>
				<div class="wrapper">
				<nav>
					<?php
						showMenu();
						
						if (isAuthenticated()) {
							echo "<a title='Log out' href='system-logout.php'><img class='logout' width=30 height=30 src='images/exit.png' /></a>";
						}
					?>
				</nav>
				</div>
				
				<h1><a href="index.php" id="logo">Prodigy<em>Works!</em></a></h1>
				
			</header>
			<h1 class="subline">Web software<br><span id="spacer">&nbsp;</span>development<img src="images/artwork.png" /></h1>
			<!--header end-->
			<section id="content">
				<?php
					BreadCrumbManager::showBreadcrumbTrail();
				?>
				<div class="wrapper">
					<article class="col1">
