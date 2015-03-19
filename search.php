<?php
	if ($_POST['fulltextmode'] == "1") {
		include("productview.php"); 
	
		$search = $_POST['searchbox'];
		$crud = new ProductView();

		$crud->rowsql = "SELECT COUNT(*) AS a " .
					 "FROM {$_SESSION['DB_PREFIX']}product A " .
					 "WHERE MATCH (A.name, A.nopricedescription, A.maindescription, A.additionalinfo) " .
					 "AGAINST('$search' IN BOOLEAN MODE) " .
					 "UNION ALL " .
					 "SELECT COUNT(*) AS a " .
					 "FROM {$_SESSION['DB_PREFIX']}documentindex A " .
					 "INNER JOIN {$_SESSION['DB_PREFIX']}documents B " .
					 "ON B.id = A.documentid " .
					 "WHERE MATCH (A.text, B.name) " .
					 "AGAINST('$search' IN BOOLEAN MODE) ";
		$crud->sql = "SELECT A.id, A.maindescription, A.imageid, A.name, 'P' AS type " .
					 "FROM {$_SESSION['DB_PREFIX']}product A " .
					 "WHERE MATCH (A.name, A.nopricedescription, A.maindescription, A.additionalinfo) " .
					 "AGAINST('$search' IN BOOLEAN MODE) " .
					 "UNION ALL " .
					 "SELECT B.id, B.name AS maindescription, 0 AS imageid, B.name, 'D' AS type " .
					 "FROM {$_SESSION['DB_PREFIX']}documentindex A " .
					 "INNER JOIN {$_SESSION['DB_PREFIX']}documents B " .
					 "ON B.id = A.documentid " .
					 "WHERE MATCH (A.text, B.name) " .
					 "AGAINST('$search' IN BOOLEAN MODE) " .
					 "ORDER BY 4";
	
		$crud->run();
					 
	} else {
		include("system-db.php");
		
		start_db();
		initialise_db();
		
		$search = $_POST['searchbox'];
		$qry = "SELECT A.id FROM {$_SESSION['DB_PREFIX']}product A WHERE A.name = '$search'";
		$result = mysql_query($qry);
		
		if (! $result) {
			logError($qry . " = " . mysql_error());
		}
		
		while (($member = mysql_fetch_assoc($result))) {
			header("location: product.php?id=" . $member['id']);
		}
	}
?>
