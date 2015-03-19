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
	        <td width='20px'>&nbsp;</td>
	        <td width='80px'>Date</td>
	        <td>Title</td>
	        <td width='120px'>Author</td>
	        <td>Technology</td>
	        <td width='20px'>&nbsp;</td>
	      </tr>
	    </thead>
	    <tbody>
	    	<?php
				$memberid = $_SESSION['SESS_MEMBER_ID'];
	    		$row = 1;
	    		$nextpage = false;
	    		$prevpage = ($fromrow > 0);
	    		$endrow = $torow + 1;
	    		$qry = "SELECT DISTINCT A.id, A.title, A.published, " .
	    				"DATE_FORMAT(A.createddate, '%d/%m/%Y') AS createddate, " .
	    				"B.login, " .
	    				"C.name, " .
						"(SELECT COUNT(*) FROM ols_questionanswers X  WHERE X.questionid = A.id AND published = 'Y') AS answered " .
	    				"FROM ols_question A " .
	    				"INNER JOIN ols_members B " .
	    				"ON B.member_id = A.memberid " .
	    				"INNER JOIN ols_technology C " .
	    				"ON C.id = A.technologyid " .
	    				"WHERE A.memberid = $memberid " .
	    				"ORDER BY A.id DESC " .
	    				"LIMIT $fromrow, $endrow";
				$result = mysql_query($qry);
				
				if ($result) {
					while (($member = mysql_fetch_assoc($result))) {
						if ($row++ > ($pagesize)) {
							$nextpage = true;
							break;
						}

						echo "<tr>\n";
						
						if ($member['answered'] > 0) {
							echo "<td><img src='images/answered.png' title='Answered' /></td>";
							
						} else {
							echo "<td><img src='images/unanswered.png' /></td>";
						}
						
						if ($member['published'] == "Y") {
							echo "<td><img src='images/published.png' title='Published' /></td>";
							
						} else if ($member['published'] == "X") {
							echo "<td><img src='images/cancel.png' title='Cancelled' /></td>";
							
						} else {
							echo "<td><img src='images/unpublished.png' /></td>";
						}
						
						echo "<td>" . $member['createddate'] . "</td>";
						echo "<td><a href='viewquestion.php?id=" . $member['id'] . "'>" . $member['title'] . "</a></td>";
						echo "<td width='120px'>" . $member['login'] . "</td>";
						echo "<td>" . $member['name'] . "</td>";
						echo "<td width='20px'><a href='viewquestion.php?id=" . $member['id'] . "'><img src='images/view.png'  title='View article' /></a></td>";
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
			echo "<img src='images/previouspage.png' onclick='window.location.href = \"myquestions.php?from=" . ($fromrow - $pagesize) . "&to=" . ($torow - $pagesize) . "\"' />";
		}
		
		if ($nextpage) {
			echo "<img src='images/nextpage.png' onclick='window.location.href = \"myquestions.php?from=" . ($fromrow + $pagesize) . "&to=" . ($torow + $pagesize) . "\"' />";
		}
	?>
</div>
<?php
	include("system-footer.php"); 
?>