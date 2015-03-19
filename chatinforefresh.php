<?php
	//Include database connection details
	require_once('system-db.php');
	
	start_db();
	initialise_db();
	$expertsonline = 0;
	$chatrequests = 0;
	$conversations = 0;
	
	$sessionid = session_id();
	$qry = "SELECT COUNT(*) AS conversations " .
			"FROM {$_SESSION['DB_PREFIX']}chatsession A " .
			"INNER JOIN {$_SESSION['DB_PREFIX']}members C " .
			"ON C.member_id = A.requestmemberid " .
			"WHERE A.responsesessionid = '$sessionid' " .
			"AND A.status = 'R' " .
			"ORDER BY A.id";
	$result = mysql_query($qry);
	
	if (! $result) {
		logError($qry . " = " . mysql_error());
		
	} else {
		while (($member = mysql_fetch_assoc($result))) {
			$conversations = $member['conversations'];
		}
	}
	
	$qry = "SELECT COUNT(DISTINCT A.member_id) AS expertsonline " .
			"FROM {$_SESSION['DB_PREFIX']}members A " .
			"INNER JOIN {$_SESSION['DB_PREFIX']}userroles B " .
			"ON B.memberid = A.member_id " .
			"WHERE A.status = 'Y' " .
			"AND A.lastaccessdate >= (NOW() - INTERVAL 5 MINUTE) " .
			"AND B.roleid = 'CONSULTANT'";
	$result = mysql_query($qry);
	
	if (! $result) {
		logError($qry . " = " . mysql_error());
		
	} else {
		while (($member = mysql_fetch_assoc($result))) {
			$expertsonline = $member['expertsonline'];
		}
	}
	
	$qry = "SELECT COUNT(*) AS requestcount " .
			"FROM {$_SESSION['DB_PREFIX']}chatsession " .
			"WHERE status = 'O' " .
			"AND lastaccessdate >= (NOW() - INTERVAL 5 MINUTE) " .
			"AND responsesessionid IS NULL";
	$result = mysql_query($qry);
	
	if (! $result) {
		logError($qry . " = " . mysql_error());
		
	} else {
		while (($member = mysql_fetch_assoc($result))) {
			$chatrequests = $member['requestcount'];
		}
	}
	
	echo "[\n";
	
	echo "{\"chatrequests\": \"" . $chatrequests . "\",\n";
	echo " \"conversations\": \"" . $conversations . "\", ";
	echo " \"expertsonline\": \"" . $expertsonline . "\"}";

	echo "\n]\n";
?>