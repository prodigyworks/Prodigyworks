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
	<table cellspacing=0 cellpadding=0 width='100%' class='grid list' id="articletable">
	    <thead>
	      <tr>
	        <td width='80px'>Date</td>
	        <td width='120px'>Author</td>
	        <td>Title</td>
	        <td width='20px'>&nbsp;</td>
	      </tr>
	    </thead>
	    <tbody>
	    	<?php
	    		$row = 1;
	    		$nextpage = false;
	    		$prevpage = ($fromrow > 0);
	    		$endrow = $torow + 1;
	    		$qry = "SELECT A.id, A.title, DATE_FORMAT(A.createddate, '%d/%m/%Y') AS createddate, B.login " .
	    				"FROM ols_advert A " .
	    				"INNER JOIN ols_members B " .
	    				"ON B.member_id = A.memberid " .
	    				"WHERE A.published = 'X' " .
	    				"ORDER BY A.createddate ASC " .
	    				"LIMIT $fromrow, $endrow";
				$result = mysql_query($qry);
				
				if ($result) {
					while (($member = mysql_fetch_assoc($result))) {
						if ($row++ > ($pagesize)) {
							$nextpage = true;
							break;
						}
						
						echo "<tr><td width='80px'>" . $member['createddate'] . "</td>";
						echo "<td width='120px'>" . $member['login'] . "</td>";
						echo "<td>" . $member['title'] . "</td>";
						echo "<td><a href='viewadvert.php?id=" . $member['id'] . "'><img title='View advert' src='images/view.png' /></a></td>";
					}
				} else {
					die($qry . " = " . mysql_error());
				}
	    	?>
	    </tbody>
	</table>
	
	<?php
		if ($prevpage) {
			echo "<img src='images/previouspage.png' onclick='window.location.href = \"rejectedadverts.php?from=" . ($fromrow - $pagesize) . "&to=" . ($torow - $pagesize) . "\"' />";
		}
		
		if ($nextpage) {
			echo "<img src='images/nextpage.png' onclick='window.location.href = \"rejectedadverts.php?from=" . ($fromrow + $pagesize) . "&to=" . ($torow + $pagesize) . "\"' />";
		}
	?>
</div>
<?php
	include("system-footer.php"); 
?>