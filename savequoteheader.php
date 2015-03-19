<?php 
	require_once("quotationitem.php");
	require_once("system-db.php");
	
	start_db();
	initialise_db();

	$header = $_SESSION['QUOTATIONHEADER'];
	$prefix = "SG101";
	$customerid = $header->customer;
	$ccf = $header->ccf;
	$costcode = $header->costcode;
	$notes = $header->notes;
	$siteid = $header->siteid;	
	$contactid = $header->contactid;
	$ccfvalue = $header->ccfvalue;
	$cabinstalldate = substr($header->cabinstalldate, 6, 4 ) . "-" . substr($header->cabinstalldate, 3, 2 ) . "-" . substr($header->cabinstalldate, 0, 2 );
	$requiredby = substr($header->requiredbydate, 6, 4 ) . "-" . substr($header->requiredbydate, 3, 2 ) . "-" . substr($header->requiredbydate, 0, 2 );
	
	$qry = "INSERT INTO datatech_quoteheader  " .
			"(prefix, status, siteid, customerid, ccf, ccfvalue, contactid, " .
			"cabinstalldate, requiredby, costcode, notes, createdby, createddate) " .
			"VALUES " .
			"('$prefix', 'N', $siteid, $customerid, '$ccf', '$ccfvalue', $contactid, " .
			"'$cabinstalldate', '$requiredby', '$costcode', '$notes', " . $_SESSION['SESS_MEMBER_ID'] . ", NOW())";
	$result = mysql_query($qry);
   	$headerid =  mysql_insert_id();
   	
   	if (! $result) {
   		die("Error:" . $qry . " - " . mysql_error());
   	}
   	
   	$linenumber = 0;

	for ($i = 0; $i < count($_SESSION['QUOTATION']); $i++) {
		$item = $_SESSION['QUOTATION'][$i];
		
		if (! $item->deleted) {
			$linenumber++;
			$description = $item->productdesc;
			$qty = $item->qty;
			$price=  $item->price;
			$total = $item->total;
			$notes = $item->notes;
			
			$qry = "INSERT INTO datatech_quoteitem  " .
					"(headerid, linenumber, description, qty, price, total, notes, createdby, createddate) " .
					"VALUES " .
					"($headerid, $linenumber, '$description', $qty, $price, $total, '$notes', " . $_SESSION['SESS_MEMBER_ID'] . ", NOW())";
			$result = mysql_query($qry);
   	
		   	if (! $result) {
		   		die("Error:" . mysql_error());
		   	}
		}
	}
	
	$qry = "SELECT A.prefix, A.status, A.notes, A.id, A.ccf, B.login, C.name AS clientname, " .
			"D.name AS sitename, SUM(E.total) AS ordervalue " .
			"FROM datatech_quoteheader A " .
			"INNER JOIN datatech_members B " .
			"ON B.member_id = A.createdby " .
			"INNER JOIN datatech_clients C " .
			"ON C.id = A.customerid " .
			"INNER JOIN datatech_sites D " .
			"ON D.id = A.siteid " .
			"INNER JOIN datatech_quoteitem E " .
			"ON E.headerid = A.id " .
			"WHERE A.id = $headerid " .
			"GROUP BY A.prefix, A.status, A.notes, A.id, A.ccf, B.login, C.name, D.name ";
	$result = mysql_query($qry);
	$body = "";

	//Check whether the query was successful or not
	if($result) {
		while (($member = mysql_fetch_assoc($result))) {
			$status = $member['status'];
			$url = "http://" . $_SERVER['SERVER_NAME'] . str_replace("savequoteheader", "approval", $_SERVER['REQUEST_URI']) . "?id=" . $headerid;
			
			$body =
				"<h2>Approval required for the following quotation</h2>" .
				"<table class='headinggroup'>\n" .
				"<tr>\n" .
				"<td class='label'>Raised by</td>\n" .
				"<td>" . $member['login'] . "</td>\n" .
				"</tr>\n" .
				"<tr>\n" .
				"<td class='label'>Quote number</td>\n" .
				"<td>" . $member['prefix'] . " " . $member['id'] . "</td>\n" .
				"</tr>\n" .
				"<tr>\n" .
				"<td class='label'>CCF / Customer PO Number</td>\n" .
				"<td>" . $member['ccf'] . "</td>\n" .
				"</tr>\n" .
				"<tr>\n" .
				"<td class='label'>Client</td>\n" .
				"<td>" . $member['clientname'] . "</td>\n" .
				"</tr>\n" .
				"<tr>\n" .
				"<td class='label'>Site</td>\n" .
				"<td>" . $member['sitename'] . "</td>\n" .
				"</tr>\n" .
				"<tr>\n" .
				"<td class='label'>Order Value</td>\n" .
				"<td>" . $member['ordervalue'] . "</td>\n" .
				"</tr>\n" .
				"</table>\n" .
				"<br><br><label class='noteslabel'>NOTES</label><br>" .
				str_replace("\n", "<br>", $member['notes']) . "<br><br>" .
				"<a href='$url'>Please Click here to Approve or Reject this quotation</a><br>";
	
				sendRoleMessage(
						"APPROVAL",
						"Quotation Approval", 
						$body
					);
		}
	}
		
	unset($_SESSION['QUOTATIONHEADER']);
	unset($_SESSION['QUOTATION']);

	header("location: " . $_POST['callback']);	
?>
