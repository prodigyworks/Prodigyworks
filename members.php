<?php include("system-header.php") ?>

<!--  Start of content -->
<br>
<form action="system-login-exec.php" method="post">
	<div style="font-size:18px; color:#000000; font-weight:bold; padding:0 0 14px 0">Welcome to our member networks!</div>
	<input class="input" type="text" name="login" id="login" value="username:" onfocus="if (this.value == 'username:') this.value = '';" onblur="if (this.value == '') this.value = 'username:';" />
	<div style="height:8px; font-size:0px"></div>
	<input class="input" type="password" name="password" id="password" value="**************" onfocus="if (this.value == '**************') this.value = '';" onblur="if (this.value == '') this.value = '**************';" />
	<br>
	<input id="go" type="image" src="images/login.png" title="Log in" value="Go" />
</form>
<!--  End of content -->

<?php include("system-footer.php") ?>
		