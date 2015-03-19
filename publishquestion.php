<?php
	include("system-header.php"); 
	
	$questionid = $_GET['id'];
	
	$qry = "SELECT A.id, A.title, A.body, DATE_FORMAT(A.createddate, '%d/%m/%Y') AS createddate, B.login " .
			"FROM ols_question A " .
			"INNER JOIN ols_members B " .
			"ON B.member_id = A.memberid " .
			"WHERE A.id = $questionid";
	$result = mysql_query($qry);
	
	if ($result) {
		while (($member = mysql_fetch_assoc($result))) {
			echo "<h2>Title : " . $member['title'] . "</h2>";
			echo "<h4>Author : " . $member['login'] . "</h4>";
			echo "<h5>Posted on " . $member['createddate'] . "</h5>";
			echo "<p>" . $member['body']. "</p>";
		}
		
	} else {
		die($qry . " = " . mysql_error());
	}
	
	echo "<hr><p>Attached files. Click to view</p>";
	
	$qry = "SELECT B.* " .
			"FROM ols_questiondocuments A " .
			"INNER JOIN ols_documents B " .
			"ON B.id = A.documentid " .
			"WHERE A.questionid = $questionid";
	$result = mysql_query($qry);
	
	if ($result) {
		while (($member = mysql_fetch_assoc($result))) {
			echo "<a target='_new' href='viewdocuments.php?id=" . $member['id'] ."'>" . $member['filename'] . "</a><br>";
		}
	
	} else {
		die($qry . " = " . mysql_error());
	}
?>
	<form id="publishForm" method="POST" action="publishquestionsave.php">
		<div id="dummypanel" style="display:none"></div>
	</form>
	
	<div id="dialog" class="modal">
		<div id="publishpanel">
			<input type="hidden" id="questionid" name="questionid" value="<?php echo $_GET['id']; ?>" />
			<table width='100%'>
				<tr>
					<td>Publish to role</td>
					<td>
						<?php createCombo("roleid", "roleid", "roleid", "ols_roles"); ?>
					</td>
				</tr>
				<tr>
					<td>Show from date</td>
					<td><input type="text" id="publishdate" name="publishdate" class="datepicker" /></td>
				</tr>
				<tr>
					<td>Expiry date</td>
					<td><input type="text" id="expirydate" name="expirydate" class="datepicker" /></td>
				</tr>
			</table>
		</div>
	</div>
	<br>
	<hr>
	<br>
  	<span class="wrapper"><a class='link1 rgap5' href="javascript:publish();"><em><b>Publish</b></em></a></span>
  	<span class="wrapper"><a class='link1' href="javascript:back();"><em><b>Back</b></em></a></span>
  	<script>
		$(document).ready(function() {
				$("#dialog").dialog({
						autoOpen: false,
						modal: true,
						title: "Publish",
						buttons: {
							Ok: function() {
								$(this).dialog("close");
								
								$("#publishpanel").appendTo("#dummypanel");
								$("#publishForm").submit();
							},
							Cancel: function() {
								$(this).dialog("close");
							}
						}
					});
			});
		
  		function publish() {
  			$("#dialog").dialog("open");
  		}
  		
  		function back() {
  			window.location.href = "newquestions.php";
  		}
  	</script>
<?php
	include("system-footer.php"); 
?>