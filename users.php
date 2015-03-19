<?php 
	include("system-header.php");
	include("confirmdialog.php");
	
	createConfirmDialog("confirmdialog", "Remove item ?", "deleteUser");
	createConfirmDialog("resetdialog", "Reset password ?", "resetPassword");
?>

<!--  Start of content -->
	<?php
		function deleteUser() {
			$id = $_POST['pk1'];
			
			$qry = "DELETE FROM ols_members WHERE member_id = $id";
			$result = mysql_query($qry);
		}
		
		function resetPassword() {
			$id = $_POST['pk1'];
			$pwd = md5($_POST['pk2']);
			
			$qry = "UPDATE ols_members SET passwd = '$pwd' WHERE member_id = $id";
			$result = mysql_query($qry);
			
			sendUserMessage(
					$id,
					"Password reset",
					"<h1>You password has been reset to <i>" . $_POST['pk2'] . "</i>"
				);
		}
	?>
	<table width="100%" class="grid list" id="userlist" maxrows=20 width=100% cellspacing=0 cellpadding=0>
		<thead>
			<tr>
				<td>First Name</td>
				<td>Last Name</td>
				<td>Login</td>
				<td>Image</td>
				<td width='16px'></td>
				<td width='16px'></td>
				<td width='16px'></td>
			</tr>
		</thead>
		<?php
			$qry = "";
			
			if (isset($_GET['role'])) {
				$roleid = $_GET['role'];
				$qry = "SELECT DISTINCT A.* FROM ols_members A " .
					   "INNER JOIN ols_userroles B " .
					   "ON B.memberid = A.member_id " .
					   "WHERE B.roleid = '$roleid' " .
					   "ORDER by A.firstname, A.lastname";
				
			} else {
				$qry = "SELECT * FROM ols_members " .
					   "ORDER by firstname, lastname";
			}
			
			$result = mysql_query($qry);
			
			//Check whether the query was successful or not
			if ($result) {
				while (($member = mysql_fetch_assoc($result))) {
					echo "<tr>\n";
					echo "<td>" . $member['firstname'] . "</td>\n";
					echo "<td>" . $member['lastname'] . "</td>\n";
					echo "<td>" . $member['login'] . "</td>\n";
					
					if ($member['imageid'] != null && $member['imageid'] != 0) {
						echo "<td title='Image'><img height=20 src='system-imageviewer.php?id=" . $member['imageid'] . "' /></td>\n";
					
					} else {
						echo "<td>&nbsp;</td>\n";
					}
					
					echo "<td width='16px' title='Edit'><img src='images/edit.png' onclick='window.location.href = \"profile.php?id=" . $member['member_id'] . "\";' /></td>\n";
					
					if ($member['systemuser'] == "Y") {
						echo "<td>&nbsp;</td>\n";
						
					} else {
						echo "<td width='16px' title='Delete'><img src='images/delete.png' onclick='removeUser(" . $member['member_id'] . ", \"" . $member['firstname'] . " " . $member['lastname'] . "\")' /></td>\n";
					}
					
					echo "<td width='16px' title='Reset password'><img src='images/password.png' onclick='$(\"#user\").val(\"" . $member['member_id'] . "\"); $(\"#passwordDialog\").dialog(\"open\");' /></td>\n";
					echo "</tr>\n";
				}
				
			} else {
				die($qry . " = " . mysql_error());
			}
		?>
	</table>
	<div id="passwordDialog" class="modal">
		<label>New password</label>
		<input type="hidden" id="user" name="user" />
		<input type="text" id="password" name="password" />
	</div>
	<script>
		var currentUser = null;
		
		function deleteUser() {
			call("deleteUser", {pk1: currentUser });			
		}
		
		function resetPassword() {
			call("resetPassword", { 
					pk1: $("#user").val(),
					pk2: $("#password").val() 
				});
		}
		
		function removeUser(userID, name) {
			currentUser = userID;
			
			$("#confirmdialog .confirmdialogbody").html("You are about to remove user <b><i>'"  + name + "'</i></b>.<br>Are you sure ?");
			$("#confirmdialog").dialog("open");
		}
		
		$(document).ready(function() {
				$("#passwordDialog").dialog({
						modal: true,
						autoOpen: false,
						title: "Reset password",
						buttons: {
							Ok: function() {
								$("#resetdialog .confirmdialogbody").html("You are about to reset the password for this user.<br>Are you sure ?");
								$("#resetdialog").dialog("open");
							},
							Cancel: function() {
								$(this).dialog("close");
							}
						}
					});
			});
		
	</script>
<!--  End of content -->
<?php include("system-footer.php") ?>
		