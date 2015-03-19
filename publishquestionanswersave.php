<?php
	include("system-header.php"); 
	
	$answerid = $_POST['answerid'];	
	$expirydate = $_POST['expirydate'];	
	$publisheddate = $_POST['publishdate'];	
	$publishedrole = $_POST['roleid'];	
	$mysql_publisheddate = substr($publisheddate, 6, 4 ) . "-" . substr($publisheddate, 3, 2 ) . "-" . substr($publisheddate, 0, 2 );
	$mysql_expirydate = substr($expirydate, 6, 4 ) . "-" . substr($expirydate, 3, 2 ) . "-" . substr($expirydate, 0, 2 );
	
	$qry = "UPDATE ols_questionanswers SET " .
		   "expirydate = '$mysql_expirydate', " .
		   "publisheddate = '$mysql_publisheddate', " .
		   "published = 'Y', " .
		   "publishedrole = '$publishedrole' " .
		   "WHERE id = $answerid";
	$result = mysql_query($qry);
	
	if (! $result) {
		die($qry . " = " . mysql_error());
	}
	
	if (! $result) {
		die($qry . " = " . mysql_error());
	}
	
	$qry = "SELECT A.memberid, B.title " .
			"FROM ols_questionanswers A " .
			"INNER JOIN ols_question B " .
			"ON B.id = A.questionid " .
			"WHERE A.id = $answerid";
	$result = mysql_query($qry);
	
	if ($result) {
		while (($member = mysql_fetch_assoc($result))) {
			sendUserMessage($member['memberid'], "Answered questioned publication", "<h2>Answered question : " . $member['title'] . "</h2><p>Has been published</p>");
			sendRoleMessage("ADMIN", "Answered questioned publication", "<h2>Article: " . $member['title'] . "</h2><p>Has been published</p>");
			
    		$qry = "SELECT * FROM ols_members A " .
    				"WHERE A.discussionalerts = 1";
			$itemresult = mysql_query($qry);
			
			if ($itemresult) {
				while (($itemmember = mysql_fetch_assoc($itemresult))) {
					sendUserMessage(
							$itemmember['member'],
							"New Forum Alert",
							"<p>A new forum response has been published</p><h3>Title: " . $member['title'] . "</h3>"
						);
				}
				
			} else {
				die($qry . " = " . mysql_error());
			}
		}
		
	} else {
		die($qry . " = " . mysql_error());
	}
?>
<h1>Answer has been published and will be live from <?php echo $publisheddate; ?> and will expire on <?php echo $expirydate; ?>.</h1>
<?php
	include("system-footer.php"); 
?>