<?php
	include("system-header.php"); 
	
	$memberid = $_SESSION['SESS_MEMBER_ID'];
	$body = mysql_escape_string($_POST['answerbody']);
	$questionid = $_POST['questionid'];
	
	$qry = "INSERT INTO ols_questionanswers " .
			"(questionid, memberid, createddate, body, published) " .
			"VALUES " .
			"($questionid, $memberid, NOW(), '$body', 'N')";
			
	$result = mysql_query($qry);
	$answerid = mysql_insert_id();
	
	if (! $result) {
		die($qry . " = " . mysql_error());
	}
	
	
	$qry = "SELECT id FROM ols_documents " .
		   "WHERE sessionid = '" . session_id() . "' " .
		   "ORDER BY id";
	$result = mysql_query($qry);
	
	if (! $result) {
		die($qry . " = " . mysql_error());
	}
	
	while (($member = mysql_fetch_assoc($result))) {
		$qry = "INSERT INTO ols_questionanswerdocuments " .
				"(answerid, documentid, createddate) " .
				"VALUES " .
				"($answerid, " . $member['id'] . ", NOW())";
				
		$itemresult = mysql_query($qry);
		
		if (! $itemresult) {
			die($qry . " = " . mysql_error());
		}
	}
	
	$qry = "UPDATE ols_documents " .
		   "SET sessionid = NULL " .
		   "WHERE sessionid = '" . session_id() . "'";
	$result = mysql_query($qry);
	
	if (! $result) {
		die($qry . " = " . mysql_error());
	}
	
	
	$qry = "SELECT title FROM ols_question " .
		   "WHERE id = $questionid";
	$result = mysql_query($qry);
	
	if (! $result) {
		die($qry . " = " . mysql_error());
	}
	
	while (($member = mysql_fetch_assoc($result))) {
		sendRoleMessage("ADMIN", "Verification request", "<p>Verification required for answer regarding question " . $member['title'] . "</p>");
	}
?>
<h4>Answer has been submitted and will be verified for publication.</h4>
<?php
	include("system-footer.php"); 
?>