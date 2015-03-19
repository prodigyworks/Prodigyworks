<?php
	require_once("system-index-header.php");
	require_once("tinymce.php");
?>
   	<table width='100%'> 
   		<tr  valign=top>
   			<td width='200px'>
				<?php include("portlet-search.php"); ?>
			</td>
			<td align=left width='100%'>
				<!--  Start of content -->
				<?php
					if (isset($_POST['domainurl'])) {
						$domainurl = mysql_escape_string($_POST['domainurl']) ;
						$emailfooter = mysql_escape_string($_POST['emailfooter']);
						$paypaluser = $_POST['paypaluser'];
						$paypalpassword = $_POST['paypalpassword'];
						$paypalsignature = $_POST['paypalsignature'];
						$sandbox = $_POST['sandbox'];
						$deliverycharge = $_POST['deliverycharge'];
						$ordersurcharge = $_POST['ordersurcharge'];
						$noapplydeliverychargelitres = $_POST['noapplydeliverychargelitres'];
						$noapplydeliverychargecost = $_POST['noapplydeliverychargecost'];
						$noapplyorderchargelitres = $_POST['noapplyorderchargelitres'];
						$noapplyorderchargecost = $_POST['noapplyorderchargecost'];
						$vat = $_POST['vat'];
						
						$qry = "UPDATE {$_SESSION['DB_PREFIX']}siteconfig SET " .
								"domainurl = '$domainurl', " .
								"paypaluser = '$paypaluser', " .
								"paypalpassword = '$paypalpassword', " .
								"paypalsignature = '$paypalsignature', " .
								"deliverycharge = '$deliverycharge', " .
								"ordersurcharge = '$ordersurcharge', " .
								"vat = '$vat', " .
								"noapplydeliverychargelitres = '$noapplydeliverychargelitres', " .
								"noapplydeliverychargecost = '$noapplydeliverychargecost', " .
								"noapplyorderchargelitres = '$noapplyorderchargelitres', " .
								"noapplyorderchargecost = '$noapplyorderchargecost', " .
								"sandbox = $sandbox, " .
								"emailfooter = '$emailfooter'";
						$result = mysql_query($qry);
				
					   	if (! $result) {
					   		logError("UPDATE {$_SESSION['DB_PREFIX']}siteconfig:" . $qry . " - " . mysql_error());
					   	}
					   	
					   	unset($_SESSION['SITE_CONFIG']);
					}
					
					$qry = "SELECT * FROM {$_SESSION['DB_PREFIX']}siteconfig";
					$result = mysql_query($qry);
					
					if ($result) {
						while (($member = mysql_fetch_assoc($result))) {
				?>
				<form id="contentForm" name="contentForm" method="post" class="entryform">
					<label>Domain URL</label>
					<input required="true" type="text" class="textbox90" id="domainurl" name="domainurl" value="<?php echo $member['domainurl']; ?>" />
					
					<label>Paypal User Name</label>
					<input type="text" required="true" class="textbox70" id="paypaluser" name="paypaluser" value="<?php echo $member['paypaluser']; ?>" />
					
					<label>Paypal Password</label>
					<input type="text" required="true" class="textbox70" id="paypalpassword" name="paypalpassword" value="<?php echo $member['paypalpassword']; ?>" />
					
					<label>Paypal Signature</label>
					<input type="text" required="true" class="textbox90" id="paypalsignature" name="paypalsignature" value="<?php echo $member['paypalsignature']; ?>" />
					
					<label>Order Surcharge</label>
					<input type="text" required="true" id="ordersurcharge" name="ordersurcharge" value="<?php echo $member['ordersurcharge']; ?>" />
					
					<label>No Order Surcharge Exceeding (Litres)</label>
					<input type="text" required="true" id="noapplyorderchargelitres" name="noapplyorderchargelitres" value="<?php echo $member['noapplyorderchargelitres']; ?>" />
					
					<label>Free Order Surcharge Exceeding (Cost)</label>
					<input type="text" required="true" id="noapplyorderchargecost" name="noapplyorderchargecost" value="<?php echo $member['noapplyorderchargecost']; ?>" />
					
					<label>Delivery Surcharge</label>
					<input type="text" required="true" id="deliverycharge" name="deliverycharge" value="<?php echo $member['deliverycharge']; ?>" />
					
					<label>Free Delivery Exceeding (Litres)</label>
					<input type="text" required="true" id="noapplydeliverychargelitres" name="noapplydeliverychargelitres" value="<?php echo $member['noapplydeliverychargelitres']; ?>" />
					
					<label>Free Delivery Exceeding (Cost)</label>
					<input type="text" required="true" id="noapplydeliverychargecost" name="noapplydeliverychargecost" value="<?php echo $member['noapplydeliverychargecost']; ?>" />
					
					<label>VAT Percentage</label>
					<input type="text" required="true" id="vat" name="vat" value="<?php echo $member['vat']; ?>" />
					
					<label>Paypal Sandbox</label>
					<select required="true" id="sandbox" name="sandbox">
						<option value=0>No</option>
						<option value=1>Yes</option>
					</select>
					
					<label>E-mail Footer</label>
					<textarea id="emailfooter" name="emailfooter" rows="15" cols="60" style="height:340px;width: 340px" class="tinyMCE"></textarea>
					<br>
					<br>
					<span class="wrapper"><a class='link1' href="javascript:if (verifyStandardForm('#contentForm')) $('#contentForm').submit();"><em><b>Update</b></em></a></span>
				</form>
				<script type="text/javascript">
					$(document).ready(function() {
							$("#emailfooter").val("<?php echo escape_notes($member['emailfooter']); ?>");
							$("#sandbox").val("<?php echo escape_notes($member['sandbox']); ?>");
						});
				</script>
					<?php
							}
						}
					?>
				<!--  End of content -->
			
			</td>
		</tr>
	</table>
<?php
	require_once("system-index-footer.php");
?>