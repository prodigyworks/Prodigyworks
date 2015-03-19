<?php
	include("system-header.php"); 
	
	$fromrow = 0;
	$torow = 20;
	
	if (isset($_GET['from'])) {
		$fromrow = $_GET['from'];
	}
	
	if (isset($_GET['to'])) {
		$torow = $_GET['to'];
	}
	
	$pagesize = ($torow - $fromrow);
?>
<div class='articles'>
	<table width=100% class='grid list' id="xx" cellspacing=0 cellpadding=0>
	    <thead>
	      <tr>
	        <td width='20px'>&nbsp;</td>
	        <td width='80px'>Date</td>
	        <td>Title</td>
	        <td width='120px'>Author</td>
	        <td width='80px'>Show From</td>
	        <td width='80px'>Show To</td>
	        <td width='80px'>Group</td>
	        <td width='20px'>&nbsp;</td>
	      </tr>
	    </thead>
	    <tbody>
	    	<?php
	    		$row = 1;
	    		$nextpage = false;
	    		$prevpage = ($fromrow > 0);
	    		$endrow = $torow + 1;
	    		$qry = "SELECT DISTINCT A.roleid, A.id, A.title, " .
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
	    				"WHERE A.published = 'Y' " .
	    				"ORDER BY A.id DESC " .
	    				"LIMIT $fromrow, $endrow";
				$result = mysql_query($qry);
				
				if ($result) {
					while (($member = mysql_fetch_assoc($result))) {
						if ($row++ > ($pagesize)) {
							$nextpage = true;
							break;
						};
						
						echo "<tr>";
						
						if ($member['active'] > 0) {
							echo "<td width='20px'><img src='images/allowed.png'  title='Permission Allowed' /></td>";
						
						} else {
							echo "<td width='20px'><img src='images/denied.png' title='Preview Only' /></td>";
						}
						
						echo "<td>" . $member['createddate'] . "</td>";
						echo "<td><a href='viewadvert.php?id=" . $member['id'] . "'>" . $member['title'] . "</a></td>";
						echo "<td width='120px'>" . $member['login'] . "</td>";
						echo "<td width='80px'>" . $member['publisheddate'] . "</td>";
						echo "<td width='80px'>" . $member['expirydate'] . "</td>";
						echo "<td width='80px'>" . $member['name'] . "</td>";
						echo "<td width='20px'><a href='viewadvert.php?id=" . $member['id'] . "'><img src='images/view.png'  title='View advert' /></a></td>";
						echo "</tr>\n";
					}
				} else {
					die($qry . " = " . mysql_error());
				}
	    	?>
	    </tbody>
	</table>
	
	<?php
		if ($prevpage) {
			echo "<img src='images/previouspage.png' onclick='window.location.href = \"articles.php?from=" . ($fromrow - $pagesize) . "&to=" . ($torow - $pagesize) . "\"' />";
		}
		
		if ($nextpage) {
			echo "<img src='images/nextpage.png' onclick='window.location.href = \"articles.php?from=" . ($fromrow + $pagesize) . "&to=" . ($torow + $pagesize) . "\"' />";
		}
	?>
</div>
<?php
	include("system-footer.php"); 
?>