<?php 
	require_once("system-db.php");
	
	start_db();
	initialise_db();
	
	require_once("quotationitem.php");
	
	if (! isset($_SESSION['QUOTATION'])) {
		$_SESSION['QUOTATION'] = array();
	}
	
	$item = new QuotationItem();
	$item->type = $_POST['type'];
	$item->siteid = $_POST['site'];
	$item->notes = $_POST['notes'];
	
	if ($_POST['type'] == "Copper Panels" ||
	    $_POST['type'] == "Copper") {
		$productlengthid = $_POST['copperlength'];
		$item->productlengthid = $productlengthid;
		$item->qty = $_POST['copperqty'];
		$item->productid = $_POST['copperproduct'];
		$item->price = 0;
		
		$qry = "SELECT length " .
				"FROM datatech_productlengths " .
				"WHERE id = $productlengthid";
		$result = mysql_query($qry);
		
		//Check whether the query was successful or not
		if ($result) {
			while (($member = mysql_fetch_assoc($result))) {
				$item->length = $member['length'];
			}
		} else {
			die("Error:" . mysql_error());
		}
		
		$item->productdesc = $_POST['copperproductdesc'] . " (" . $item->length . "M)";
		
		$qry = "SELECT price " .
				"FROM datatech_pricebreaks " .
				"WHERE productlengthid = $productlengthid " .
				"AND ((fromunit <= " . $item->qty . " " .
				"AND tounit >= " . $item->qty . " ) OR  " .
				"(fromunit <= " . $item->qty . " " .
				"AND tounit = 0))";
		$result = mysql_query($qry);
		
		if ($result) {
			while (($member = mysql_fetch_assoc($result))) {
				$item->price = $member['price'];
			}
		} else {
			die("Error:" . mysql_error());
		}
		
	} else if ($_POST['type'] == "Fibre Panels" ||
	           $_POST['type'] == "Fibre") {
		$productlengthid = $_POST['fibrelength'];
		$item->productlengthid = $productlengthid;
		$item->qty = $_POST['fibreqty'];
		$item->productid = $_POST['fibreproduct'];
		$item->price = 0;
		
		$qry = "SELECT length " .
				"FROM datatech_productlengths " .
				"WHERE id = $productlengthid";
		$result = mysql_query($qry);
		
		//Check whether the query was successful or not
		if ($result) {
			while (($member = mysql_fetch_assoc($result))) {
				$item->length = $member['length'];
			}
		} else {
			die("Error:" . mysql_error());
		}
		
		$item->productdesc = $_POST['fibreproductdesc'] . " (" . $item->length . "M)";
		
		$qry = "SELECT price " .
				"FROM datatech_pricebreaks " .
				"WHERE productlengthid = $productlengthid " .
				"AND ((fromunit <= " . $item->qty . " " .
				"AND tounit >= " . $item->qty . " ) OR  " .
				"(fromunit <= " . $item->qty . " " .
				"AND tounit = 0))";
		$result = mysql_query($qry);
		
		if ($result) {
			while (($member = mysql_fetch_assoc($result))) {
				$item->price = $member['price'];
			}
		} else {
			die("Error:" . mysql_error());
		}
		
	} else if ($_POST['type'] == "Labour Task") {
		$item->productlengthid = 0;
		$item->productdesc = $_POST['labourtaskproductdesc'];
		$item->qty = $_POST['labourtaskqty'];
		$item->productid = 0;
		$item->price = 0;
		$item->inout = $_POST['labourratehours'];
		$technicianid = $_POST['labourtaskproduct'];
		
		$qry = "SELECT * " .
				"FROM datatech_technicianrates " .
				"WHERE id = $technicianid";
		$result = mysql_query($qry);
		
		//Check whether the query was successful or not
		if ($result) {
			while (($member = mysql_fetch_assoc($result))) {
				if ($_POST['labourratehours'] == "IN") {
					$item->price = $member['inhourrate'];
					
				} else {
					$item->price = $member['outhourrate'];
				}
			}
			
		} else {
			die("Error:" . mysql_error());
		}
		
	} else if ($_POST['type'] == "Sundry Items") {
		$item->productlengthid = 0;
		$item->productdesc = $_POST['ancillaryproductdesc'];
		$item->qty = $_POST['ancillaryqty'];
		$item->productid = 0;
		$item->price = $_POST['ancillaryprice'];
	}		
	
	$item->total = $item->qty * $item->price;
	
	$_SESSION['QUOTATION'][count($_SESSION['QUOTATION'])] = $item;

	header("location: " . $_POST['callback']);	
?>
