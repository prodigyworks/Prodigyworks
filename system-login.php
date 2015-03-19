<?php include("system-header.php"); ?>

<!--  Start of content -->
<div class="loginPage">
	<form id="loginForm" name="loginForm" method="post" action="system-login-exec.php">
		<table border="0" align="left" cellpadding="2" cellspacing="0">
			<tr>
				<td><b>Login</b></td>
				<td><input name="login" type="text" class="textfield" id="login" /></td>
			</tr>
			<tr>
				<td><b>Password</b></td>
				<td><input name="password" type="password" class="textfield" id="password" /></td>
			</tr>
		</table>
		
		<div class="buttons">
			<input type="submit" value="Ok"></input>
			<input type="button" value="Cancel" onclick='document.location = "index.php";'></input>
		</div>
	</form>  
	<script>
		$(document).ready(function() {
			$("#login").focus();
		});
	</script>
</div>
<!--  End of content -->

<?php include("system-footer.php"); ?>