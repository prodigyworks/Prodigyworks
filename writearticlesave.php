<?php
	include("system-header.php"); 
	
	$memberid = $_SESSION['SESS_MEMBER_ID'];
	$title = mysql_escape_string($_POST['title']);
	$categoryid = $_POST['categoryid'];
	$tags = mysql_escape_string($_POST['tags']);
	$body = mysql_escape_string($_POST['articlebody']);
	$memberid = $_SESSION['SESS_MEMBER_ID'];
	
	$qry = "INSERT INTO ols_article " .
			"(title, body, tags, createddate, categoryid, viewcount, memberid, featured, published) " .
			"VALUES " .
			"('$title', '$body', '$tags', NOW(), $categoryid, 0, $memberid, 'N', 'N')";
			
	$result = mysql_query($qry);
	$articleid = mysql_insert_id();
	
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
		$qry = "INSERT INTO ols_articledocuments " .
				"(articleid, documentid, createddate) " .
				"VALUES " .
				"($articleid, " . $member['id'] . ", NOW())";
				
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
	
	sendRoleMessage("ADMIN", "Verification required", "Verification required for article " . $title);
?>
<h1>Article has been submitted and will be verified for publication.</h1>
<?php
	include("system-footer.php"); 
?>