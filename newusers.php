<?php
	include("system-header.php"); 
	include("confirmdialog.php");
	
	createConfirmDialog("confirmapprovaldialog", "User approval ?", "approveUser");
	createConfirmDialog("confirmrejectdialog", "User rejection ?", "rejectUser");
	
	function accept() {
		$qry = "UPDATE ols_members " .
			   "SET accepted = 'Y' " .
			   "WHERE member_id = " . $_POST['pk1'];
		$result = mysql_query($qry);
		
		if (! $result) {
			die($qry . " = " . mysql_error());
		}
		
		sendUserMessage($_POST['pk1'], "User Registration", "Welcome to Oracle logs.<br>Your user registration has been accepted.");
	}
	
	function reject() {
		$qry = "UPDATE ols_members " .
			   "SET accepted = 'X' " .
			   "WHERE member_id = " . $_POST['pk1'];
		$result = mysql_query($qry);
		
		if (! $result) {
			die($qry . " = " . mysql_error());
		}
		sendUserMessage($_POST['pk1'], "User Registration", "Welcome to Oracle logs.<br>Unfortunately, your user registration has been rejected.");
	}
?>
<script>
	var currentUser = null;
	
	function approveUser() {
		call("accept", { pk1: currentUser });
	}
	
	function rejectUser() {
		call("reject", { pk1: currentUser });
	}
	
	function canceluser(member) {
		currentUser = member;
		
		$("#confirmrejectdialog").dialog("open");
	}
	
	function publishuser(member) {
		currentUser = member;
		
		$("#confirmapprovaldialog").dialog("open");
	}
	
	$(document).ready(function() {
			$("#confirmapprovaldialog .confirmdialogbody").html("You are about to approve this user.<br>Are you sure ?");
			$("#confirmrejectdialog .confirmdialogbody").html("You are about to reject this user.<br>Are you sure ?");
		});
</script>
<div class='articles'>
	<table cellspacing=0 cellpadding=0 width='100%' class='grid list' id="articletable">
	    <thead>
	      <tr>
	        <td width='80px'>Login</td>
	        <td width='110px'>First Name</td>
	        <td width='110px'>Surname</td>
	        <td>E-Mail</td>
	        <td width='20px'>&nbsp;</td>
	        <td width='20px'>&nbsp;</td>
	        <td width='20px'>&nbsp;</td>
	      </tr>
	    </thead>
	    <tbody>
	    	<?php
	    		$qry = "SELECT * " .
	    				"FROM ols_members " .
	    				"WHERE accepted = 'N' " .
	    				"ORDER BY login ASC ";
				$result = mysql_query($qry);
				
				if ($result) {
					while (($member = mysql_fetch_assoc($result))) {
						echo "<tr>";
						echo "<td width='80px'>" . $member['login'] . "</td>";
						echo "<td width='110px'>" . $member['firstname'] . "</td>";
						echo "<td width='110px'>" . $member['lastname'] . "</td>";
						echo "<td>" . $member['email'] . "</td>";
						echo "<td><a href='profile.php?id=" . $member['member_id'] . "'><img title='View user' src='images/view.png' /></a></td>";
						echo "<td><a href='javascript: publishuser(" . $member['member_id'] . ")'><img title='Publish user' src='images/publish.png' /></a></td>";
						echo "<td><a href='javascript: canceluser(" . $member['member_id'] . ")'><img title='Cancel user' src='images/cancel.png' /></a></td>";
					}
				} else {
					die($qry . " = " . mysql_error());
				}
	    	?>
	    </tbody>
	</table>
</div>
<?php
	include("system-footer.php"); 
?>