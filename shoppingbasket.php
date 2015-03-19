<?php
	include("system-header.php"); 
	
	createConfirmDialog("confirmdialog", "Remove item ?", "deleteItem");
	
	function remove() {
		ShoppingBasket::remove($_POST['pk1']);
	}
?>
	<script>
		var item;
		
		function removeItem(index) {
			item = index;
			
			$("#confirmdialog .confirmdialogbody").html("You are about to remove this item.<br>Are you sure ?");
			$("#confirmdialog").dialog("open");
		}
		
		function deleteItem() {
			call("remove", {pk1: item});
		}
	</script>
	<div class="fright">
	<?php
	$choseoutstock = false;
	
	if (! isAuthenticated()) {
	?>
		<p class="createaccount">
			You are not logged in.
		</p>
		<p class="createaccount">
			To view prices and order online, <a href="system-register.php">create an account.<a><br><br>
		</p>
	<?php
	}
	?>
	</div>
	<table class="checkout">
		<thead>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>Product</td>
			<?php
				if (isAuthenticated()) {
			?>
				<td align=right>Price</td>
			<?php					
				}
			?>
				<td>Quantity</td>
				<td>Availability</td>
			<?php
				if (isAuthenticated()) {
			?>
				<td align=right>Total</td>
			<?php					
				}
			?>
			</tr>
		</thead>
		<?php
		$total = 0;
		$totalweight = 0;
		$index = 0;
		
		foreach (ShoppingBasket::items() as $item) {

			echo "<tr>";

			$qry = "SELECT B.name, B.imageid, A.name AS uom, A.weight, A.price, A.stockstatus, A.poa " .
					"FROM {$_SESSION['DB_PREFIX']}productuom A " .
					"INNER JOIN {$_SESSION['DB_PREFIX']}product B " .
					"ON B.id = A.productid " .
					"WHERE A.id = " . $item->productuomid;
			$result=mysql_query($qry);
			
			//Check whether the query was successful or not
			if($result) {
				
				while (($member = mysql_fetch_assoc($result))) {
					echo "<td><img onclick='removeItem(" . ($index++) . ")' src='images/delete.png' /></td>";
					
					if ($member['imageid'] != null && $member['imageid'] != 0) {
						echo "<td><img height=32 src='system-imageviewer.php?id=" . $member['imageid'] . "' /></td> ";
						
					} else {
						echo "<td><img height=32 src='images/noimage.png' /></td> ";
					}
					
					echo "<td>" . $member['name'] . " (" . $member['uom'] . ")" . "</td>";
					
					if (isAuthenticated()) {
						echo "<td align=right>" . "&pound; " . number_format($member['price'], 2). "</td>";
					}
					
					echo "<td><input type='text' id='qty' name='qty' value='" . $item->qty . "' size=5 /></td>";
					
					if ( $member['stockstatus'] == "O" ) {
						echo "<td class='red'>OUT OF STOCK</td>";
						$choseoutstock = true;
						
					} else {
						echo "<td>In stock</td>";
					}
					
					if (isAuthenticated()) {
						echo "<td align=right>" . "&pound; " . number_format($member['price'] * $item->qty, 2) . "</td>";
					}
					
					$total = $total + ($member['price'] * $item->qty);
					$totalweight = $totalweight + ($member['weight'] * $item->qty);
				}
			}

			echo "</tr>";
		}
		
		if (isAuthenticated()) {
			$deliverycharge = getSiteConfigData()->deliverycharge;
			$ordersurcharge = getSiteConfigData()->ordersurcharge;
			
			if ($totalweight >= getSiteConfigData()->noapplydeliverychargelitres) {
				$deliverycharge = 0;
			}
			
			if ($totalweight >= getSiteConfigData()->noapplyorderchargelitres) {
				$ordersurcharge = 0;
			}
			
			if ($total >= getSiteConfigData()->noapplydeliverychargecost) {
				$deliverycharge = 0;
			}
			
			if ($total >= getSiteConfigData()->noapplyorderchargecost) {
				$ordersurcharge = 0;
			}
			
			$vat = getSiteConfigData()->vat;
			$total += $ordersurcharge;
			$total += $deliverycharge;
			$total = $total + ($total * ($vat / 100));
			
			echo "<tr class='noline'>";
			echo "<td class='bold' colspan=6 align=right>Shipping : </td>";
			echo "<td class='bold' colspan=7 align=right>" . "&pound; " . number_format($deliverycharge, 2) . "</td>";
			echo "</tr>";
			echo "<tr class='noline'>";
			echo "<td class='bold' colspan=6 align=right>Surcharge : </td>";
			echo "<td class='bold' colspan=7 align=right>" . "&pound; " . number_format($ordersurcharge, 2) . "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td class='bold' colspan=6 align=right>VAT : </td>";
			echo "<td class='bold' colspan=7 align=right>" . "% " . number_format($vat, 2) . "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td class='bold' colspan=6 align=right>Total : </td>";
			echo "<td class='bold' colspan=7 align=right>" . "&pound; " . number_format($total, 2) . "</td>";
			echo "</tr>";
		}
		?>
	</table>
	
	<?php
	if ($choseoutstock) {
	?>
	<p class="margintop5">
	You have selected one or more products that are out of stock. Should you proceed with the order, we will advise the quickest delivery date.<br>
	To obtain an delivery estimate prior to ordering, please contact us.
	</p>
	<?php
	}
	?>
	<p class="">
	Shipping Charges. We charge a flat rate of <b>&pound;<?php echo getSiteConfigData()->deliverycharge; ?></b> for all shipping within the UK. For orders over <b>&pound;<?php echo getSiteConfigData()->noapplydeliverychargecost; ?></b> or exceeding <b><?php echo getSiteConfigData()->noapplydeliverychargelitres; ?> litres</b>, shipping is free.<br>
	<b>For international shipping, please contact us for a quotation.</b>
	</p>

	<p class="">
	Order Surcharges. We charge a flat rate of <b>&pound;<?php echo getSiteConfigData()->ordersurcharge; ?></b> for all orders. For orders over <b>&pound;<?php echo getSiteConfigData()->noapplyorderchargecost; ?></b> or exceeding <b><?php echo getSiteConfigData()->noapplyorderchargelitres; ?> litres</b>, shipping is free.<br>
	</p>

	<?php
	if (! isAuthenticated()) {
	?>
	<a href="purchaseorderreport.php" class='link1'><em><b>Create Purchase Order</b></em></a>
	<?php
	} else {
	?>
	<a href="checkout.php" class='link1'><em><b>Proceed to Checkout</b></em></a>
	<?php
	}
	?>
<?php
	include("system-footer.php"); 
?>
