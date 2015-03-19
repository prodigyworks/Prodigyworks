<?php
	include("system-header.php"); 
	
	$articleid = $_GET['id'];
	$allowed = true;
	
	$qry = "SELECT A.id, A.publishedrole, A.title, A.body, A.tags, " .
			"DATE_FORMAT(A.createddate, '%d/%m/%Y') AS createddate, B.login " .
			"FROM ols_article A " .
			"INNER JOIN ols_members B " .
			"ON B.member_id = A.memberid " .
			"WHERE A.id = $articleid";
	$result = mysql_query($qry);
	
	if ($result) {
		while (($member = mysql_fetch_assoc($result))) {
			$allowed = isUserInRole($member['publishedrole']);

			echo "<h2>";
			
			if (! $allowed) {
				echo "<img src='images/denied.png' title='Read Only' />";
?>
<script>
	$(document).ready(function() {
			$("#body").css("height", "100px");
			$("#body").css("overflow", "hidden");
		});
</script>
<?php
			} else {
				echo "<img src='images/allowed.png' title='Permission Allowed' />";
			}
			
			echo "Title: " . $member['title'] . "</h2>";
			echo "<h4>Author : " . $member['login'] . "</h4>";
			echo "<h5>Posted on " . $member['createddate'] . "</h5>";
			echo "<div id='body'>" . $member['body']. "</div>";
			
			if ($allowed) {
				echo "<hr><p>Attached files. Click to view</p>";
			
			} else {
				echo "<hr><h4>You do not have permission to view the whole document. Please upgrade</h4>";
			}
		}
		
	} else {
		die($qry . " = " . mysql_error());
	}
	
	if ($allowed) {
		$qry = "SELECT B.* " .
				"FROM ols_articledocuments A " .
				"INNER JOIN ols_documents B " .
				"ON B.id = A.documentid " .
				"WHERE A.articleid = $articleid";
		$result = mysql_query($qry);
		
		if ($result) {
			while (($member = mysql_fetch_assoc($result))) {
				echo "<a target='_new' href='viewdocuments.php?id=" . $member['id'] ."'>" . $member['filename'] . "</a><br>";
			}
			
		} else {
			die($qry . " = " . mysql_error());
		}
	}
	
	$qry = "UPDATE ols_article " .
			"SET viewcount = viewcount + 1 " .
			"WHERE id = $articleid";
	$result = mysql_query($qry);
	
	if (! $result) {
		die($qry . " = " . mysql_error());
	}

	include("system-footer.php"); 
?>