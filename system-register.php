<?php include("system-header.php"); ?>

<!--  Start of content -->
<?php showErrors(); ?>
<div class="registerPage">
	<form id="loginForm" name="loginForm" method="post" action="system-register-exec.php">
	  <table border="0" align="left" cellpadding="2" cellspacing="0">
	    <tr>
	      <td>First Name </td>
	      <td><input name="fname" type="text" class="textfield" id="fname" /></td>
	    </tr>
	    <tr>
	      <td>Last Name </td>
	      <td><input name="lname" type="text" class="textfield" id="lname" /></td>
	    </tr>
	    <tr>
	      <td>Login</td>
	      <td><input name="login" type="text" class="textfield" id="login" /></td>
	    </tr>
	    <tr>
	      <td>Password</td>
	      <td><input name="password" type="password" class="textfield" id="password" /></td>
	    </tr>
	    <tr>
	      <td>Confirm Password </td>
	      <td><input name="cpassword" type="password" class="textfield" id="cpassword" /></td>
	    </tr>
	    <tr>
	    	<td colspan="2">
	    		<br />
	    		<h3>Address</h3>
	    		<hr />
	    	</td>
	    </tr>
	    <tr>
	      <td>Building</td>
	      <td><input name="building" type="text" class="textfield" id="building" /></td>
	    </tr>
	    <tr>
	      <td>Street</td>
	      <td><input name="street" type="text" class="textfield" id="street" /></td>
	    </tr>
	    <tr>
	      <td>Town</td>
	      <td><input name="town" type="text" class="textfield" id="town" /></td>
	    </tr>
	    <tr>
	      <td>City</td>
	      <td><input name="city" type="text" class="textfield" id="city" /></td>
	    </tr>
	    <tr>
	      <td>County</td>
	      <td><input name="county" type="text" class="textfield" id="county" /></td>
	    </tr>
	    <tr>
	      <td>Post Code</td>
	      <td><input name="postcode" type="text" class="textfield" id="postcode" /></td>
	    </tr>
	    <tr>
	      <td>&nbsp;</td>
	      <td><input type="submit" name="Submit" id="Submit" value="Register" /></td>
	    </tr>
	  </table>
	</form>
</div>
<script>
	$(document).ready(function() {
		$("#fname").focus();
	});
</script>
<!--  End of content -->

<?php include("system-footer.php"); ?>
