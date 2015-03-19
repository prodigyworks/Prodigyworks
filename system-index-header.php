<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php 
	//Include database connection details
	require_once('system-config.php');
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Luboil Online Shop</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<link rel="shortcut icon" href="favicon.ico">

<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/jquery-ui-1.9.2.custom.css" rel="stylesheet" type="text/css" />
<link href="css/dcmegamenu.css" rel="stylesheet" type="text/css" />
<link href="css/dcverticalmegamenu.css" rel="stylesheet" type="text/css" />
<link href="css/skins/orange.css" rel="stylesheet" type="text/css" />

<script src="js/jquery-1.4.2.min.js" type="text/javascript"></script>
<script src="js/jquery.min.js" type="text/javascript"></script>
<script src="js/jquery-ui.min.js" type="text/javascript"></script>
<script src="js/jcarousellite.js" type="text/javascript"></script>
<script src='js/jquery.hoverIntent.minified.js' type='text/javascript'></script>
<script src='js/jquery.dcmegamenu.1.3.3.js' type='text/javascript'></script>
<script src="js/jquery.autocomplete.js" type="text/javascript"></script>
<script src="js/oraclelogs.js" language="javascript" ></script> 
<!--[if lt IE 7]>
<script type="text/javascript" src="js/ie_png.js"></script>
<script type="text/javascript">
	ie_png.fix('.png, .carousel-box .next img, .carousel-box .prev img');
</script>
<link href="css/ie6.css" rel="stylesheet" type="text/css" />
<![endif]-->
</head>
<!-- wrapper end -->
<BODY style="MARGIN: 0px; BACKGROUND: url(images/bg1.jpg) #ffffff">
<?php
	require_once("confirmdialog.php");
?>
	<script>
		$(document).ready(function() {
			headerDetailRefresh();
		});
		
		var headerPageDataHandler = function(data) {
				$.each(data, function(i, item) {
						
					<?php
					if (isUserInRole("CONSULTANT")) {
					?>
						if (parseInt(item.chatrequests) > 0) {
							$("#chatrequestalert").show();
							
						} else {
							$("#chatrequestalert").hide();
						}
						
						if (parseInt(item.conversations) > 0) {
							$("#chatredcircle").html(item.conversations);
							$("#chatredcircle").show();
							$("#conversation").show();
							
						} else {
							$("#chatredcircle").hide();
							$("#conversation").hide();
						}
					<?php
					}
					?>
						if (parseInt(item.expertsonline) == 0) {
							$("#chatimage").attr("src", "images/chat-grey.png");	
							$("#chatimage").attr("title", "No experts available");	
							
						} else {
							$("#chatimage").attr("src", "images/chat.png");	
							$("#chatimage").attr("title", "Chat with expert");	
						}

						$(".expertsonline").html(item.expertsonline);
					}); 
			};
			
		function headerDetailRefresh() {
			callAjax(
					"chatinforefresh.php", 
					null,
					headerPageDataHandler,
					true,
					function() {
						/* Ignore stray events. */
					}
				);
				
			setTimeout(headerDetailRefresh, 5000);
		}
		
	</script>
	<DIV id=maindiv1 align=center>
		<DIV id=maindiv2 style="top:0px; WIDTH: 914px; POSITION: relative; text-align:left">
			<TABLE style="BORDER-COLLAPSE: collapse" cellSpacing=0 cellPadding=0 width=914 align=left >
				<TR>
					<TD style="BACKGROUND: url(images/shadow_lft.png)" width=17>
						&nbsp;
					</TD>
					<TD style="BACKGROUND: #076ba7" vAlign=top>
						<TD style="BACKGROUND: #076ba7" vAlign=top>
							<?php
								createConfirmDialog("passworddialog", "Forgot password ?", "forgotPassword");
								
								if (isset($_POST['command'])) {
									$_POST['command']();
								}
							?>
							<form method="POST" id="commandForm" name="commandForm">
								<input type="hidden" id="command" name="command" />
								<input type="hidden" id="pk1" name="pk1" />
								<input type="hidden" id="pk2" name="pk2" />
							</form>
							<div class="tail-top-left"></div>
							<div class="tail-top">
							<!-- header -->
								<div id="header" class='header1'>
			<?php
				if (! isUserInRole("CONSULTANT")) {
			?>
				<div id="expertpanel">
					<a href="requestexpertnew.php">
						<img id="chatimage" src="images/chat.png" title="Chat with expert" />
					</a>
				</div>
			<?php
				} else {
			?>
				<div id="expertpanel">
					<a href="conversations.php">
						<img id="conversation" src="images/conversation.png" title="View conversations" />
					</a>
				</div>
			<?php
				}
			?>
			<div id="chatpanel">
				<label>Experts on line : <label>
				<label class="expertsonline">0</label>
			</div>
			<div onclick='navigate("answerexpertrequest.php");' id="chatrequestalert"></div>
									<a href="conversations.php" id='chatredcircle'></a>
									<?php
										if (count(ShoppingBasket::items()) > 0) {
									?>
									<a href="shoppingbasket.php" class='redcircle'><?php echo count(ShoppingBasket::items());?></a>
									<?php
										}
									?>
									<div class="socialmedia">
										<a href="shoppingbasket.php"><img src='images/cart.png' /></a>
										<a href="http://www.facebook.com"><img src='images/facebook.png' /></a>
										<a href="http://www.twitter.com"><img src='images/twitter.png' /></a>
										<a href="http://www.linkedin.com"><img src='images/linkedin.png' /></a>
									</div>
									<div class="phone">
										<strong>Phone : </strong><span>+44 (555) 555 5555</span>
									</div>
									<?php 
										if (! isAuthenticated()) {
									?>
										<div id='logindialog' class="<?php
													if (! isset($_SESSION['LOGIN_ERRMSG_ARR']) || count($_SESSION['LOGIN_ERRMSG_ARR']) == 0) {
														echo "hide";
													}
												?>">
											<form id='loginform' action="system-login-exec.php" method="post">
												<input type="hidden" id="callback" name="callback" value="<?php if (isset($_GET['callback'])) echo base64_decode($_GET['callback']); else echo "index.php"; ?>" />
												<a id='close' href='#' onclick="navigate('index.php');">Close</a>
												<table cellspacing=3>
													<tr>
														<td>User</td>
														<td><input type='text' id='login' name='login' /></td>
													</tr>
													<tr>
														<td>Password</td>
														<td><input type='password' id='password' name='password' /></td>
													</tr>
													<tr>
														<td>&nbsp;</td>
														<td>
															<img src='images/login-mini.png' onclick="$('#loginform').submit()" />
														</td>
													</tr>
													<tr>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
													</td>
													<tr>
														<td>&nbsp;</td>
														<td>
															<a id='register' href='system-register.php'>Register</a>
														</td>
													</tr>
													<tr>
														<td>&nbsp;</td>
														<td>
															<a href="javascript:void(0)" onclick="checkForgotPassword()">Forgotten password ?</a>
														</td>
													</tr>
													
												</table>
												<p id='errorlogin'>
													<?php
													if (isset($_SESSION['LOGIN_ERRMSG_ARR'])) {
														for ($i = 0; $i < count($_SESSION['LOGIN_ERRMSG_ARR']); $i++) {
															echo $_SESSION['LOGIN_ERRMSG_ARR'][$i] . "<br>\n";
														}
														
														unset($_SESSION['LOGIN_ERRMSG_ARR']);
													}
													?>
												</p>
											</form>
										</div>
									<div class='login'>
										
										<div id='loginbutton'><span>Login</span></div>
										<div id='registerbutton'><span>Register</span></div>
										<script>
											function checkForgotPassword() {
												if ($("#login").val() != "") {
													$("#passworddialog .confirmdialogbody").html("You are about to reset the password.<br>Are you sure ?");
													$("#passworddialog").dialog("open");
													
												} else {
													$("#errorlogin").html("User must be entered for this feature.");
												}
											}
											
											function forgotPassword() {
												$("#loginform").attr("action", "forgotpassword.php");	
												$("#loginform").submit();	
											}
											
											$(document).ready(function() {
													$("#loginbutton").click(
															function() {
																$("#logindialog").show();
																$("#login").focus();
																
																setTimeout(function() {
																			$("#login").focus();
																		}, 
																		100
																	);
															}
														);
															
													$("#registerbutton").click(
															function() {
																navigate("system-register.php");
															}
														);
											});
										</script>
									</div>
									<?php		
										} else {
											$qry = "UPDATE {$_SESSION['DB_PREFIX']}members SET " .
													"lastaccessdate = NOW() " .
													"WHERE member_id = " . $_SESSION['SESS_MEMBER_ID'] . "";
											$result = mysql_query($qry);
									?>
									<div class='login'>
										
										<div id='welcome'><strong>Welcome: </strong><a href="profile.php"><?php echo $_SESSION['SESS_FIRST_NAME'] . " " . $_SESSION['SESS_LAST_NAME']; ?></a></div>
										<div id='logoutbutton'><span>Log out</span></div>
										<script>
											$(document).ready(function() {
												$("#logoutbutton").click(function() {
													navigate("system-logout.php");
												});
											});
										</script>
									</div>
									<?php
										}
									?>
									<div class="logo">
										<img id="logo" src="images/logo.png" alt="" />
									</div>
									<div class="headerad">
										<?php showAdvert(2, 450, 70); ?>
									</div>
								</div>
							<!-- content -->
								<div id="content">
									<div class="row-1">
										<div class="inside">
											<div class="container">
												<div class="menu2">
													<div>
														<?php
															showMenu();
														?>
													</div>
												</div>
												<div class="content">
												