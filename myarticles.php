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
	        <td width='80px'>Live</td>
	        <td width='80px'>Expires</td>
	        <td width='20px'>&nbsp;</td>
	      </tr>
	    </thead>
	    <tbody>
	    	<?php
	    		$row = 1;
	    		$nextpage = false;
	    		$prevpage = ($fromrow > 0);
	    		$endrow = $torow + 1;
	    		$memberid = $_SESSION['SESS_MEMBER_ID'];
	    		$qry = "SELECT DISTINCT A.published, A.id, A.title, " .
	    				"DATE_FORMAT(A.createddate, '%d/%m/%Y') AS createddate, " .
	    				"DATE_FORMAT(A.publisheddate, '%d/%m/%Y') AS publisheddate, " .
	    				"DATE_FORMAT(A.expirydate, '%d/%m/%Y') AS expirydate, " .
	    				"B.login " .
	    				"FROM ols_article A " .
	    				"INNER JOIN ols_members B " .
	    				"ON B.member_id = A.memberid " .
	    				"WHERE A.memberid = $memberid " .
	    				"ORDER BY A.createddate DESC " .
	    				"LIMIT $fromrow, $endrow";
				$result = mysql_query($qry);
				
				if ($result) {
					while (($member = mysql_fetch_assoc($result))) {
						if ($row++ > ($pagesize)) {
							$nextpage = true;
							break;
						}
						
						echo "<tr>\n";
						
						if ($member['published'] == "Y") {
							echo "<td width='20px'><img src='images/published.png'  title='Published' /></td>";
						
						} else {
							echo "<td width='20px'><img src='images/unpublished.png' title='Awaiting Publish' /></td>";
						}
						
						echo "<td width='80px'>" . $member['createddate'] . "</td>";
						echo "<td><a href='viewarticle.php?id=" . $member['id'] . "'>" . $member['title'] . "</a></td>";
						
						if ($member['published'] == "Y") {
							echo "<td width='80px'>" . $member['publisheddate'] . "</td>";
							echo "<td width='80px'>" . $member['expirydate'] . "</td>";

						} else {
							echo "<td width='80px'>&nbsp;</td>";
							echo "<td width='80px'>&nbsp;</td>";
						}
						
						echo "<td width='20px'><a href='viewarticle.php?id=" . $member['id'] . "'><img src='images/view.png'  title='View article' /></a></td>";
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
			echo "<img src='images/previouspage.png' onclick='window.location.href = \"myarticles.php?from=" . ($fromrow - $pagesize) . "&to=" . ($torow - $pagesize) . "\"' />";
		}
		
		if ($nextpage) {
			echo "<img src='images/nextpage.png' onclick='window.location.href = \"myarticles.php?from=" . ($fromrow + $pagesize) . "&to=" . ($torow + $pagesize) . "\"' />";
		}
	?>
</div>
<?php
	include("system-footer.php"); 
?>