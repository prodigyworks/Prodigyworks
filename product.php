<?php
	include("system-header.php"); 
	
	$id = $_GET['id'];
	$qry = "SELECT * " .
			"FROM {$_SESSION['DB_PREFIX']}product " .
			"WHERE id = $id";
	$result = mysql_query($qry);
	
	if ($result) {
		while (($member = mysql_fetch_assoc($result))) {
			echo "<h1>" . $member['name'] . "</h1>\n";
			
			if ($member['nopricedescription'] != null && $member['nopricedescription'] != "") {
				$found = false;
				$qry = "SELECT id " .
						"FROM {$_SESSION['DB_PREFIX']}product " .
						"WHERE name = '" . $member['nopricedescription'] . "'";
				$itemresult = mysql_query($qry);
				
				if ($itemresult) {
					while (($itemmember = mysql_fetch_assoc($itemresult))) {
						$found = true;
						echo "<br><h2>Replaced by <a href='product.php?id=" . $itemmember['id'] . "'>" . $member['nopricedescription'] . "</a></h2>";
					}
				}
				
				if (! $found) {
					echo "<br><h2>Replaced by " . $member['nopricedescription'] . "</h2>";
				}
				
			} else {
			?>
			
			<script>
				function showPrice() {
					$("#price").val($($("#uom").attr("options")[$("#uom").attr("selectedIndex")]).attr("price"));
					$("#pricediv").html($($("#uom").attr("options")[$("#uom").attr("selectedIndex")]).attr("price"));
				}
			</script>
			<form id="shoppingform" name="shoppingform" action="addtocart.php" method="POST">
				<table width='100%'>
					<tr>
						<td>
							<?php 
							if ($member['imageid'] != null && $member['imageid'] != 0) {
								echo "<img src='system-imageviewer.php?id=" . $member['imageid'] . "' /> ";
								
							} else {
								echo "<img src='images/noimage.png' /> ";
							}
							?>
						</td>
						<td>
							<table cellspacing=10 cellpadding=10 width='100%'>
								<tr>
									<td class='item'>
										<label>Pack size</label>
									</td>
									<td>
							<?php
							
							$qry = "SELECT * " .
									"FROM {$_SESSION['DB_PREFIX']}productuom " .
									"WHERE productid = " . $member['id'];
							$itemresult = mysql_query($qry);
							
							if ($itemresult) {
								$price = "";
								
								echo "<SELECT id='uom' name='uom' onchange='showPrice()'>\n";
								
								while (($itemmember = mysql_fetch_assoc($itemresult))) {
									if ($price == "") {
										$price = "&pound; " . number_format($itemmember['price'], 2);
									}
									
									echo "<OPTION price='&pound; " . number_format($itemmember['price'], 2) . "' value='" . $itemmember['id'] . "'>" . $itemmember['name'] . "</OPTION>\n";
								}
								
								echo "</SELECT>\n";
							}
							?>
									</td>
								</tr>
							<?php
								if (isUserInRole("USER")) {
							?>
								<tr>
									<td>
										<label>Price</label>
									</td>
									<td>
										<div id='pricediv'><?php echo $price; ?></label>
									</td>
								</tr>
							<?php
								}
							?>
								<tr>
									<td>
										<label>Qty</label>
									</td>
									<td>
										<input type="text" id="qty" name="qty" value="1" />
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>
										<input type="submit" value="Add To Cart"></input>
									</td>
								</tr>
								<tr>
									<td colspan=2>
										<strong>Description</strong><br>
										<?php echo $member['maindescription']; ?><br><br>
									</td>
								</tr>
								<tr>
									<td colspan=2>
										<strong>Additional info</strong><br>
										<?php echo $member['additionalinfo']; ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<?php
				if ($member['consideredproduct'] != null && $member['consideredproduct'] != "") {
					$qry = "SELECT id " .
							"FROM {$_SESSION['DB_PREFIX']}product " .
							"WHERE name = '" . $member['consideredproduct'] . "'";
					$itemresult = mysql_query($qry);
					
					if ($itemresult) {
						while (($itemmember = mysql_fetch_assoc($itemresult))) {
							$found = true;
							echo "<br><h2>Customers also considered this product : <a href='product.php?id=" . $itemmember['id'] . "'>" . $member['consideredproduct'] . "</a></h2>";
						}
					}
				}
					
				?>
				<input type="hidden" id="price" name="price" value="<?php echo $price; ?>" />
			</form>
		<?php
			}
		}
	}

	include("system-footer.php"); 
?>
