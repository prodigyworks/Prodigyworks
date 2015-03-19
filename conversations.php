<?php 
	include("system-header.php");
	
	$sessionid = session_id();
	$qry = "SELECT A.id, A.requestsessionid, C.firstname, C.lastname, " .
			"DATE_FORMAT(A.createddate, '%d/%m/%Y %T') AS createddate, A.questionid " .
			"FROM {$_SESSION['DB_PREFIX']}chatsession A " .
			"INNER JOIN {$_SESSION['DB_PREFIX']}members C " .
			"ON C.member_id = A.requestmemberid " .
			"WHERE A.responsesessionid = '$sessionid' " .
			"AND A.status = 'R' " .
			"ORDER BY A.id";
	$result = mysql_query($qry);
	
	if (! $result) {
		logError($qry . " = " . mysql_error());
	}
?>
<table class='grid list' width='100%'>
	<thead>
		<tr>
			<td width='120px'>Requested By</td>
			<td width='120px'>Last Contact</td>
			<td width='320px'>Last Message</td>
			<td width='20px'>&nbsp;</td>
		</tr>
	</thead>
<?php
	/* Show children. */
	while (($member = mysql_fetch_assoc($result))) {
		$message = "";
		$createddate = "";
		
		$qry = "SELECT message, DATE_FORMAT(createddate, '%d/%m/%Y %T') AS createddate " .
				"FROM {$_SESSION['DB_PREFIX']}chatmessages " .
				"WHERE chatsessionid = " . $member['id'] . " " .
				"ORDER BY id DESC " .
				"LIMIT 0, 1";
		$itemresult = mysql_query($qry);
		
		if (! $itemresult) {
			logError($qry . " = " . mysql_error());
		}
	
		while (($itemmember = mysql_fetch_assoc($itemresult))) {
			$message = $itemmember['message'];
			$createddate = $itemmember['createddate'];
		}
?>
	<tr>
		<td width='120px'><?php echo $member['firstname'] . " " . $member['lastname']; ?></td>
		<td width='120px'><?php echo $createddate; ?></td>
		<td width='120px'><?php echo $message; ?></td>
		<td width='20px'><img src='images/answer.png' title='Respond' onclick='navigate("responseexpert.php?id=<?php echo $member['id']; ?>");'</td>
	</tr>
<?php
	}
?>
</table>
<!--  End of content -->
<?php include("system-footer.php") ?>
		