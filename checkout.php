<?php
	include("system-header.php"); 
	require_once( 'paypal-digital-goods.class.php' );
	require_once( 'paypal-purchase.class.php' );
	
	$data = getSiteConfigData();
	
	PayPal_Digital_Goods_Configuration::username( $data->paypaluser );
	PayPal_Digital_Goods_Configuration::password( $data->paypalpassword);
	PayPal_Digital_Goods_Configuration::signature( $data->paypalsignature);
	PayPal_Digital_Goods_Configuration::return_url( $data->domainurl . '/premiummembersubscribe.php' );
	PayPal_Digital_Goods_Configuration::notify_url( $data->domainurl . '/premiummembersubscribe.php' );
	PayPal_Digital_Goods_Configuration::cancel_url( $data->domainurl . '/premiummembercancel.php' );
	PayPal_Digital_Goods_Configuration::business_name( 'Luboil' );
	
	if ($data->sandbox == 1) {
		PayPal_Digital_Goods_Configuration::environment( "sandbox" );
		
	} else {
		PayPal_Digital_Goods_Configuration::environment( "live" );
	}
?>
<h4>Payment terms</h4>
<table width=100% cellspacing=5 style='table-layout:fixed' class="grid">
	<thead>
		<tr>
			<td width='200px' >Product</td>
			<td width='360px' >Description</td>
			<td width='60px'  nowrap align=right>Unit Price</td>
			<td width='60px'  nowrap align=right>Quantity</td>
			<td width='60px'  nowrap align=right>Cost</td>
		</tr>
	</thead>
<?php
	$total = 0;
			
	foreach (ShoppingBasket::items() as $item) {

		$qry = "SELECT B.name, B.imageid, B.maindescription, A.name AS uom, A.price " .
				"FROM {$_SESSION['DB_PREFIX']}productuom A " .
				"INNER JOIN {$_SESSION['DB_PREFIX']}product B " .
				"ON B.id = A.productid " .
				"WHERE A.id = " . $item->productuomid;
		$result=mysql_query($qry);
		
		//Check whether the query was successful or not
		if($result) {
			
			while (($member = mysql_fetch_assoc($result))) {
				echo "<tr>";

				echo "<td width='200px' >" . $member['name'] . " (" . $member['uom'] . ")" . "</td>";
				echo "<td width='360px'><b>" . $member['maindescription'] . "</b></td>";
				echo "<td width='60px' nowrap align=right>" . "&pound; " . number_format($member['price'], 2). "</td>";
				echo "<td width='60px' nowrap align=right>" . $item->qty . "</td>";
				echo "<td width='60px' nowrap align=right>" . "&pound; " . number_format($member['price'] * $item->qty, 2) . "</td>";
				
				$total = $total + ($member['price'] * $item->qty);
				
				echo "</tr>";
			}
		}
	}
	
	echo "<tr style='border-top:1px solid black'>";

	echo "<td colspan=4 align=right><b>Total</b></td>";
	echo "<td width='60px' nowrap align=right><b>" . "&pound; " . number_format($total, 2) . "</b></td>";
	
	echo "</tr>";
?>
</table>
<br>
<br>
<?php
	$itemarray = array();
	$total = 0;

	foreach (ShoppingBasket::items() as $item) {

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
						'item_amount'      => ($member['price'] * $item->qty),				
						'item_tax'         => '0.00',				
						'item_quantity'    => $item->qty,				
						'item_number'      => $item->productuomid,			
					);
					
				$total = $total + ($member['price'] * $item->qty);
				array_push($itemarray, $arr);
			}
		}
	}
	
	$purchase_details = array( 
			'name'           => 'Luboil Goods',
			'description'    => 'Luboil Goods',
			'amount'         => $total,
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
	$paypal_subscription->print_buy_button(); 
?>
<?php
	include("system-footer.php"); 
?>