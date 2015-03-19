<?php
	include("system-db.php"); 
	
	start_db();
	initialise_db();
	
	$memberid = $_SESSION['SESS_MEMBER_ID'];
	$type = $_POST['type'];
	$location = $_POST['location'];
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	$ref = $_POST['ref'];
	$title = $_POST['title'];
	$description = mysql_escape_string($_POST['description']);
	$rate = $_POST['rate'];
	$currency = $_POST['currency'];
	$rateper = $_POST['rateper'];
	$salary = $_POST['salary'];
	
	$qry = "INSERT INTO ols_job " .
			"(jobtype, location, lat, lng, reference, title, description, rate, salary, createddate, memberid, status, currency, rateper) " .
			"VALUES " .
			"('$type', '$location', $lat, $lng, '$ref', '$title', '$description', '$rate', '$salary', NOW(), $memberid, 'O', '$currency', '$rateper')";
			
	$result = mysql_query($qry);
	
	if (! $result) {
		die($qry . " = " . mysql_error());
	}
			
	$qry = "SELECT * FROM ols_members A " .
			"WHERE A.jobalerts = 1";
	$itemresult = mysql_query($qry);
	
	if ($itemresult) {
		while (($itemmember = mysql_fetch_assoc($itemresult))) {
			sendUserMessage(
					$itemmember['member'],
					"New Job Posting",
					"<p>A new job has been posted</p><h3>Title: " . $member['title'] . "</h3><p>Ref: $ref</p><p>$description</p>"
				);
		}
		
		sendRoleMessage(
				"ADMIN",
				"New Job Posting",
				"<p>A new job has been posted</p><h3>Title: " . $member['title'] . "</h3><p>Ref: $ref</p><p>$description</p>"
			);
		
	} else {
		die($qry . " = " . mysql_error());
	}

	header("location: jobs.php");
?>