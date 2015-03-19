<?php
	require_once("sqlprocesstoarray.php");
	
	$json = new SQLProcessToArray();
	
	if (isset($_GET['q'])) {
		$term = trim(strip_tags($_GET['q']));//retrieve the search term that autocomplete sends
		
	} else {
		$term = "";
	}

	$qry = "SELECT A.name " .
			"FROM {$_SESSION['DB_PREFIX']}product A " .
			"WHERE A.name LIKE '%$term%' " .
			"UNION " .
			"SELECT A.name " .
			"FROM {$_SESSION['DB_PREFIX']}category A " .
			"WHERE A.name LIKE '%$term%' " .
			"UNION " .
			"SELECT A.name " .
			"FROM {$_SESSION['DB_PREFIX']}documents A " .
			"WHERE A.name LIKE '%$term%' " .
			"ORDER BY 1";
	$result = mysql_query($qry);
	$html = "";
	
	while (($member = mysql_fetch_assoc($result))) {
		$html .= $member['name'] . "\n";
	}
	
	echo $html;
?>