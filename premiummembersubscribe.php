<?php
	require_once( 'system-db.php' );
	require_once( 'paypal-digital-goods.class.php' );
	require_once( 'paypal-purchase.class.php' );

	if (! isset($_SESSION)) {
		start_db();
		initialise_db();
	}	
	
	$data = getSiteConfigData();
	
	PayPal_Digital_Goods_Configuration::username( $data->paypaluser );
	PayPal_Digital_Goods_Configuration::password( $data->paypalpassword);
	PayPal_Digital_Goods_Configuration::signature( $data->paypalsignature);
	PayPal_Digital_Goods_Configuration::return_url( $data->domainurl . '/premiummembersubscribe.php' );
	PayPal_Digital_Goods_Configuration::notify_url( $data->domainurl . '/premiummembersubscribe.php' );
	PayPal_Digital_Goods_Configuration::cancel_url( $data->domainurl . '/premiummembercancel.php' );
	
	if ($data->sandbox == 1) {
		PayPal_Digital_Goods_Configuration::environment( "sandbox" );
		
	} else {
		PayPal_Digital_Goods_Configuration::environment( "live" );
	}
	
	$itemarray = array();

	foreach (ShoppingBasket::items() as $item) {

		echo "<tr>";

		$qry = "SELECT B.name, B.imageid, B.maindescription, A.name AS uom, A.price " .
				"FROM {$_SESSION['DB_PREFIX']}productuom A " .
				"INNER JOIN {$_SESSION['DB_PREFIX']}product B " .
				"ON B.id = A.productid " .
				"WHERE A.id = " . $item->productuomid;
		$result=mysql_query($qry);
		
		//Check whether the query was successful or not
		if($result) {
			
			while (($member = mysql_fetch_assoc($result))) {
				$arr = array(
						'item_name'        => $member['name'] . " (" . $member['uom'] . ")",				
						'item_description' => $member['maindescription'],				
						'item_amount'      => $member['price'] * $item->qty, 2,				
						'item_tax'         => '0.00',				
						'item_quantity'    => $item->qty,				
						'item_number'      => $item->productuomid,			
					);
					
				array_push($itemarray, $arr);
			}
		}
	}
	
	$purchase_details = array( 
			'name'           => 'Luboil Goods',
			'description'    => 'Luboil Goods',
			'amount'         => '5.00',
			'tax_amount'     => '0.00',
			'invoice_number' => '111111',
			'number'         => '',
			'items'          => $itemarray,
			'custom'         => ''
	);
	
/*	$qry = "INSERT INTO ols_paymentgatewayhistory ( " .
			"description, initial_amount, amount, period, frequency, total_cycles, expirydate, createddate " .
			") VALUES (" .
			"'" . $_POST['description'] . "', " . $_POST['initial_amount'] . ", " . $_POST['amount'] . ", " .
			"'" . $_POST['period'] . "', " . $_POST['frequency'] . ", " . $_POST['total_cycles'] . ", NOW(), NOW()" .
			")";
	
	$result = mysql_query($qry);
	$gatewayid = mysql_insert_id();
	
	if (! $result) {
		logError($qry . " = " . mysql_error());
	}
	$qry = "UPDATE {$_SESSION['DB_PREFIX']}members " .
			"SET paymentgatewayid = $gatewayid " .
			"WHERE member_id = " . getLoggedOnMemberID();
	$result = mysql_query($qry);
	
	if (! $result) {
		logError($qry . " = " . mysql_error());
	}
	*/
	
	$paypal_subscription = new PayPal_Purchase( $purchase_details );
	$paypal_subscription->purchase(); 
	
//	sendUserMessage($memberid, "Premium Subscription", "<h1>Premium Subscription</h1><p>Welcome to premium membership for Oracle Logs</p>");
//	sendRoleMessage("ADMIN", "Premium Subscription", "<h1>Premium Subscription for member " . $_SESSION['SESS_FIRST_NAME'] . " " . $_SESSION['SESS_LAST_NAME'] . "</h1><p>Welcome to premium membership for Oracle Logs</p>");
	
?>
<html>
<head>
<script>
	if (window.parent) {
		window.parent.location.href = "premiummemberconfirm.php";
		
	} else {
		navigate("premiummemberconfirm.php");
	}
</script>
</head>
</html>