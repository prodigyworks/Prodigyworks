<?php
	include("system-header.php"); 
	
	$advertid = $_GET['id'];
	$qry = "SELECT DISTINCT A.imageid, A.roleid, A.id, A.title, A.url, " .
			"DATE_FORMAT(A.createddate, '%d/%m/%Y') AS createddate, " .
			"DATE_FORMAT(A.publisheddate, '%d/%m/%Y') AS publisheddate, " .
			"DATE_FORMAT(A.expirydate, '%d/%m/%Y') AS expirydate, " .
			"B.login, C.name, " .
			"(SELECT COUNT(*) FROM ols_advert X WHERE X.id = A.id AND X.publisheddate <= NOW() AND X.expirydate >= NOW()) AS active  " .
			"FROM ols_advert A " .
			"INNER JOIN ols_members B " .
			"ON B.member_id = A.memberid " .
			"INNER JOIN ols_advertgroup C " .
			"ON C.id = A.groupid " .
			"WHERE A.id = $advertid";
	$result = mysql_query($qry);
	
	if ($result) {
		while (($member = mysql_fetch_assoc($result))) {
			echo "<h2>Title: " . $member['title'] . "</h2>";
			echo "<h4>Author : " . $member['login'] . "</h4>";
			echo "<h4>URL : " . $member['url'] . "</h4>";
			echo "<h5>Posted on " . $member['createddate'] . "</h5>";
			echo "<h5>Show From " . $member['publisheddate'] . "</h5>";
			echo "<h5>Show To " . $member['expirydate'] . "</h5>";
			echo "<p><br><label>Image</label><br><img src='system-imageviewer.php?id=" . $member['imageid'] . "' /></p>";
		}
		
	} else {
		die($qry . " = " . mysql_error());
	}

	include("system-footer.php"); 
?>