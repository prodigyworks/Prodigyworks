<?php
	include("system-db.php");
	
	start_db();
	initialise_db();

	if (isset($_POST['jobid'])) {
		$id = $_POST['jobid'];
		
		$qry = "SELECT B.firstname, B.website, B.lastname, B.imageid, A.* " .
			   "FROM ols_job A " .
			   "INNER JOIN ols_members B " .
			   "ON B.member_id = A.memberid " .
			   "WHERE A.id = $id";
		$result = mysql_query($qry);
		
		if ($result) {
			while (($member = mysql_fetch_assoc($result))) {
				$html = "<h1>Job Application</h1>" .
						"<h3>Reference: " . $member['reference'] . "</h3>" .
						"<p>From: " . $_POST['firstname'] . " " . $_POST['lastname'] . " </p>" .
						"<p>Email: " . $_POST['email'] . " </p>" .
						"<p>Contact: " . $_POST['number'] . " </p>";
						
				sendUserMessage($member['memberid'], "Job Application", $html);
				
				if (isAuthenticated()) {
					$memberid = $_SESSION['SESS_MEMBER_ID'];
	
					$qry = "INSERT INTO ols_jobapplications " .
							"(jobid, memberid, createddate) " .
							"VALUES " .
							"($id, $memberid, NOW())";
							
					$itemresult = mysql_query($qry);
					
					if (! $itemresult) {
						die($qry . " = " . mysql_error());
					}
					
					sendUserMessage($memberid, "Job Application", $html);
				}
			}
		} else {
			die($qry . " - " . mysql_error());
		}
	}

	header("location: jobapplyok.php"); 
?>