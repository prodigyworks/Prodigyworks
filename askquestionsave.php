<?php
	include("system-header.php"); 
	
	$memberid = $_SESSION['SESS_MEMBER_ID'];
	$opsystemid = $_POST['operatingsystemid'];
	$opsystemversionid = $_POST['opsystemversionid'];
	$technologyid = $_POST['technologyid'];
	$technologyversionid = $_POST['technologyversionid'];
	$architecture = $_POST['architecture'];
	$title = $_POST['title'];
	$body = mysql_escape_string($_POST['questionbody']);
	$memberid = $_SESSION['SESS_MEMBER_ID'];
	
	$qry = "INSERT INTO ols_question " .
			"(title, body, technologyid, technologyversionid, opsystemid, opsystemversionid, architecture, createddate, memberid, published) " .
			"VALUES " .
			"('$title', '$body', $technologyid, $technologyversionid, $opsystemid, $opsystemversionid, '$architecture', NOW(), $memberid, 'N')";
			
	$result = mysql_query($qry);
	$questionid = mysql_insert_id();
	
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
		$qry = "INSERT INTO ols_questiondocuments " .
				"(questionid, documentid, createddate) " .
				"VALUES " .
				"($questionid, " . $member['id'] . ", NOW())";
				
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
	
	sendRoleMessage("ADMIN", "Verification required", "Verification required for question " . $title);
?>
<h1>Question has been submitted and will be verified for publication.</h1>
<?php
	include("system-footer.php"); 
?>