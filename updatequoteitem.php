<?php 
	require_once("system-db.php");
	require_once("quotationitem.php");
	
	start_db();
	initialise_db();
	
	if (isset($_GET['item'])) {
		$item = $_SESSION['QUOTATION'][$_GET['item']];
		
		$item->qty = $_GET['qty'];
		
		if ($item->type == "Copper Panels" ||
		    $item->type == "Copper") {
			$item->price = 0;
			
			$qry = "SELECT price " .
					"FROM datatech_pricebreaks " .
					"WHERE productlengthid = " . $item->productlengthid . " " .
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
			
		} else if ($item->type == "Fibre Panels" ||
		           $item->type == "Fibre") {
			$item->price = 0;
			
			$qry = "SELECT price " .
					"FROM datatech_pricebreaks " .
					"WHERE productlengthid = " . $item->productlengthid . " " .
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
			
		} else if ($item->type == "Labour Task") {
				   	
			$item->price = 0;
			$name = $item->productdesc;
			
			$qry = "SELECT * " .
					"FROM datatech_technicianrates " .
					"WHERE name = '$name'" ;
			$result = mysql_query($qry);
			
			if ($result) {
				while (($member = mysql_fetch_assoc($result))) {
					if ($item->inout == "IN") {
						$item->price = $member['inhourrate'];
						
					} else {
						$item->price = $member['outhourrate'];
					}
				}
			} else {
				die("Error:" . mysql_error());
			}
			
		} else if ($item->type == "Sundry Items") {
			/* Do nothing */
		}
		
		$item->total = $item->qty * $item->price;
		
		$grandTotal = 0;
		
		if (isset($_SESSION['QUOTATION'])) {
			for ($i = 0; $i < count($_SESSION['QUOTATION']); $i++) {
				$item = $_SESSION['QUOTATION'][$i];
				
				if (! $item->deleted) {
					$grandTotal = $grandTotal + $item->total;
				}
			}
		}
		
		echo "[\n";
		echo "{\"price\": \"" . number_format($item->price, 2) . "\", " .
				"\"total\": \"" . number_format($item->total, 2) . "\", " .
				"\"qty\": \"" . number_format($item->qty, 2) . "\", " .
				"\"grandtotal\": \"" . number_format($grandTotal, 2) . "\"}";
		echo "\n]\n";
	}
	
?>
