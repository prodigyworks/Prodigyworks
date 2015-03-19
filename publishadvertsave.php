<?php
	include("system-header.php"); 
	
	$advertid = $_POST['advertid'];	
	$expirydate = $_POST['expirydate'];	
	$publisheddate = $_POST['publishdate'];	
	$publishedrole = $_POST['roleid'];	
	$mysql_publisheddate = substr($publisheddate, 6, 4 ) . "-" . substr($publisheddate, 3, 2 ) . "-" . substr($publisheddate, 0, 2 );
	$mysql_expirydate = substr($expirydate, 6, 4 ) . "-" . substr($expirydate, 3, 2 ) . "-" . substr($expirydate, 0, 2 );
	
	$qry = "UPDATE ols_advert SET " .
		   "expirydate = '$mysql_expirydate', " .
		   "publisheddate = '$mysql_publisheddate', " .
		   "published = 'Y', " .
		   "roleid = '$publishedrole' " .
		   "WHERE id = $advertid";
	$result = mysql_query($qry);
	
	if (! $result) {
		die($qry . " = " . mysql_error());
	}
	
	$qry = "SELECT A.memberid, A.title " .
			"FROM ols_advert A " .
			"WHERE A.id = $advertid";
	$result = mysql_query($qry);
	
	if ($result) {
		while (($member = mysql_fetch_assoc($result))) {
			sendUserMessage($member['memberid'], "Advert publication", "<h2>Advert publication : " . $member['title'] . "</h2><p>Has been published</p>");
			sendRoleMessage("ADMIN", "Advert publication", "<h2>Article: " . $member['title'] . "</h2><p>Has been published</p>");
		}
		
	} else {
		die($qry . " = " . mysql_error());
	}
?>
<h1>Advert has been published and will be live from <?php echo $publisheddate; ?> and will expire on <?php echo $expirydate; ?>.</h1>
<?php
	include("system-footer.php"); 
?>