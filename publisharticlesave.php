<?php
	include("system-header.php"); 
	
	$articleid = $_POST['articleid'];	
	$expirydate = $_POST['expirydate'];	
	$publisheddate = $_POST['publishdate'];	
	$publishedrole = $_POST['roleid'];	
	$featured = $_POST['featured'];	
	$mysql_publisheddate = substr($publisheddate, 6, 4 ) . "-" . substr($publisheddate, 3, 2 ) . "-" . substr($publisheddate, 0, 2 );
	$mysql_expirydate = substr($expirydate, 6, 4 ) . "-" . substr($expirydate, 3, 2 ) . "-" . substr($expirydate, 0, 2 );
	
	$qry = "UPDATE ols_article SET " .
		   "expirydate = '$mysql_expirydate', " .
		   "publisheddate = '$mysql_publisheddate', " .
		   "published = 'Y', " .
		   "featured = '$featured', " .
		   "publishedrole = '$publishedrole' " .
		   "WHERE id = $articleid";
	$result = mysql_query($qry);
	
	if (! $result) {
		die($qry . " = " . mysql_error());
	}
	
	$qry = "SELECT A.memberid, A.title " .
			"FROM ols_article A " .
			"WHERE A.id = $articleid";
	$result = mysql_query($qry);
	
	if ($result) {
		while (($member = mysql_fetch_assoc($result))) {
			sendUserMessage($member['memberid'], "Article publication", "<h2>Article: " . $member['title'] . "</h2><p>Has been published</p>");
			sendRoleMessage("ADMIN", "Article publication", "<h2>Article: " . $member['title'] . "</h2><p>Has been published</p>");
			
    		$qry = "SELECT * FROM ols_members A " .
    				"WHERE A.articlealerts = 1";
			$itemresult = mysql_query($qry);
			
			if ($itemresult) {
				while (($itemmember = mysql_fetch_assoc($itemresult))) {
					sendUserMessage(
							$itemmember['member'],
							"New Discussion Alert",
							"<p>A new discussion has been published</p><h3>Title: " . $member['title'] . "</h3>"
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
<h1>Article has been published and will be live from <?php echo $publisheddate; ?> and will expire on <?php echo $expirydate; ?>.</h1>
<?php
	include("system-footer.php"); 
?>