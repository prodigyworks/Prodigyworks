<?php 
	require_once("quotationitem.php");
	include("system-header.php"); 
	
	function delete() {
		$_SESSION['QUOTATION'][$_POST['pk1']]->deleted = true;
	}
	
	function getItems() {
		$items = 0;
		
		if (isset($_SESSION['QUOTATION'])) {
			for ($i = 0; $i < count($_SESSION['QUOTATION']); $i++) {
				if (! $_SESSION['QUOTATION'][$i]->deleted) {
					$items++;
				}
			}
		}
		
		return $items;
	}
	
	if (isset($_GET['fromheader'])) {
		$header = new QuotationHeader();
		
		if (isset($_POST['siteid'])) {
			$header->siteid = $_POST['siteid'];
			
		} else {
			$header->siteid = $_SESSION['QUOTATIONHEADER']->siteid;
		}
		
		$header->customer = $_POST['customerid'];
		$header->ccf = $_POST['ccfpo'];
		$header->ccfvalue = $_POST['ccfpovalue'];
		$header->cabinstalldate = $_POST['cabinstalldate'];
		$header->contactid = $_POST['contactid'];
		$header->requiredbydate = $_POST['requiredby'];
		$header->costcode = $_POST['costcode'];
		$header->notes = $_POST['notes'];

		
		$_SESSION['QUOTATIONHEADER'] = $header;
	}
?>
<form method="post">
	<table style="<?php if (getItems() > 3) echo 'width:97%'; else echo 'width:100%'; ?>" class="grid" cellspacing=0 cellpadding=0>
		<thead>
			<tr>
				<td width="3%"></td>
				<td width="10%">Qty</td>
				<td width="58%">Description</td>
				<td width="15%" align=right style="padding-right:18px">Price</td>
				<td width="15%" align=right style="padding-right:18px">Total</td>
			</tr>
		</thead>
	</table>
	<div style="<?php if (getItems() <= 3) echo 'width:97%'; else echo 'width:100%'; ?>; height:150px; overflow-y: auto">
		<table style="<?php if (getItems() > 3) echo 'width:97%'; else echo 'width:100%'; ?>" class="grid" cellspacing=0 cellpadding=0>
			<?php
				$grandTotal = 0;
				
				if (isset($_SESSION['QUOTATION'])) {
					for ($i = 0; $i < count($_SESSION['QUOTATION']); $i++) {
						$item = $_SESSION['QUOTATION'][$i];
						
						if (! $item->deleted) {
							$grandTotal = $grandTotal + $item->total;
							
							echo "<tr>\n";
							echo "<td width='3%'><img height=16 src='images/delete.png' onclick='if (confirm(\"Are you sure you want to remove this item?\")) call(\"delete\", {pk1: \"" . $i . "\"})' /></td>\n";
							echo "<td width='10%'><input style='width:40px' type='text' value='" . $item->qty . "' onchange='updateQty(" . $i . ", $(this));'></input></td>\n";
							echo "<td width='58%'>" . $item->productdesc . "</td>\n";
							echo "<td width='15%' class='price' align=right>" . number_format($item->price, 2) . "</td>\n";
							echo "<td width='15%' class='total' align=right>" . number_format($item->total, 2) . "</td>\n";
							echo "</tr>\n";
						}
					}
				}
			?>
		</table>
	</div>
	<table style="<?php if (getItems() > 3) echo 'width:97%'; else echo 'width:100%'; ?>" class="grid" cellspacing=0 cellpadding=0>
		<tfoot>
			<tr>
				<td colspan=5 align=right style="padding-right:18px">Grand total: <span id='grandtotal'><?php echo number_format($grandTotal, 2); ?></span></td>
			</tr>
		</tfoot>
	</table>
</form>

<form class="contentform" id="quoteForm" name="quoteForm" method="post" action="savequote.php" onsubmit="return validate()">
	<input type="hidden" id="callback" name="callback" value="newquoteitem.php" />
	<input type="hidden" id="type" name="type" value="" />
	<input type="hidden" id="topleveltype" name="topleveltype" value="" />
	<input type="button" class="navButton" id="btnHeader" value="BACK" />
	<input type="button" class="dataButton" value="NOTES" id="btnNotes" name="btnNotes" />
	<input type="button" class="dataButton" id="btnAdditionalInfo" value="ADDITIONAL INFO" />
	<input type="button" class="commandButton" id="btnAddToQuote" value="ADD TO QUOTE" />
	
	<div style="margin-top: -160px">
		<label>NEW ITEM<label>
		<?php createCombo("category", "id", "name", "datatech_categories", "WHERE parentcategoryid = 0"); ?>
		
		<div id="longlinepanel" style="display:none">
			<label>TYPE<label>
			<select id="longlinepanelcategory" name="longlinepanelcategory" style="width:200px">
				<option value="0"></option>
			</select>
			
			<div id="copper" style="display:none">
				<label>Category</label>
				<select id="coppercat1" name="coppercat1" style="width:200px">
					<option value="0"></option>
				</select>
				
				<br>
				
				<label>product</label>
				<select id="copperproduct" name="copperproduct" style="width:400px">
					<option value="0"></option>
				</select>
				<input type="hidden" id="copperproductdesc" style="width:800px" name="copperproductdesc" value="" />
				
				<br>
				
				<label>Length</label>
				<select id="copperlength" name="copperlength">
					<option value="0"></option>
				</select>
				
				<label>Quantity</label>
				<input type="text" id="copperqty" name="copperqty" value="0" style="width:100px" align=right />
			</div>
			
			<div id="fibre" style="display:none">
				<label>Category</label>
				<select id="fibrecat1" name="fibrecat1" style="width:200px" onchange="showFibreProducts()">
					<option value="0"></option>
				</select>
				
				<br>
				
				<label>product</label>
				<select id="fibreproduct" name="fibreproduct" style="width:400px" onchange="showFibreLength()">
					<option value="0"></option>
				</select>
				<input type="hidden" id="fibreproductdesc" style="width:800px" name="fibreproductdesc" value="" />
				
				<br>
				
				<label>Length</label>
				<select id="fibrelength" name="fibrelength">
					<option value="0"></option>
				</select>
				
				<label>Quantity</label>
				<input type="text" id="fibreqty" name="fibreqty" value="0" style="width:100px" align=right />
			</div>
		</div>
		
		<div id="ancillary" style="display:none">
			<label>description</label>
			<input type="text" id="ancillaryproductdesc" style="width:300px" name="ancillaryproductdesc" value="" />
			
			<br>
			
			<label>Quantity</label>
			<input type="text" id="ancillaryqty" name="ancillaryqty" value="0" style="width:100px" align=right />
			
			<br>
			
			<label>Price</label>
			<input type="text" id="ancillaryprice" name="ancillaryprice" value="0" style="width:100px" align=right />
		</div>
		
		<div id="labourtask" style="display:none">
			<label>task</label>
			<select id="labourtaskproduct" name="labourtaskproduct" style="width:400px" onchange="$('#labourtaskproductdesc').val($(this).find('option:selected').text());">
				<option value="0"></option>
			</select>
			<input type="hidden" id="labourtaskproductdesc" style="width:800px" name="labourtaskproductdesc" value="" />
			
			<br>
			
			<label>man days</label>
			<input type="text" id="labourtaskqty" name="labourtaskqty" value="0" style="width:100px" align=right />
			
			<br>
			
			<label>In / out of hours</label>
			<select id="labourratehours" name="labourratehours">
				<option value="IN">In</option>
				<option value="OUT">Out</option>
				<option value="EXP">Expedite</option>
			</select>
		</div>
		
		<div class="modal" id="notesDialog">
			<label>NOTES</label>
			<textarea id="notes" name="notes" cols=80 rows=5></textarea>
		</div>
		
		<div class="modal" id="additionalInfoDialog">
			<label>additional information</label>
			<div id="additionalInfoPanel">
			</div>
		</div>
		
		<br>
	</div>
	<input class="proceed actionButton" type="button" value="PROCEED" onclick="window.location.href = 'savequoteheader.php';" />
</form>

<script>
	
	function showFibreLength() {
		$('#fibreproductdesc').val($("#fibreproduct").find('option:selected').text());
		
		getLengths(
				"#fibrelength",
				$("#fibreproduct").val(), 
				function() {
				}
			);
	}
	
	
	function getTasks(selectid, callback) {
		getJSONData('findtask.php', selectid, callback);
	}
	
	function getCategories(selectid, id, callback) {
		getJSONData('findcategory.php?id=' + id, selectid, callback);
	}
	
	function getProducts(selectid, id, callback) {
		getJSONData('findproduct.php?id=' + id, selectid, callback);
	}
	
	function getLengths(selectid, id, callback) {
		getJSONData('findproductlength.php?id=' + id, selectid, callback);
	}
	
	function updateQty(item, element) {
		callAjax(
				"updatequoteitem.php", 
				{
					item: item, 
					qty: element.val()
				},
				function(data) {
					element.parent().parent().find(".total").html(data[0].total);
					element.parent().parent().find(".price").html(data[0].price);
					$("#grandtotal").html(data[0].grandtotal);
				}
			);
	}
	
	function showLongLineDestinations(qty) {
		var table = "<div id='longlinediv'>";
		var areas = "<?php createComboOptions("id", "name", "datatech_areas"); ?>";
		
		for (var i = 1; i <= qty; i++) {
			table += "<div class='longlineadditionalitem' id='longlinediv_" + i + "'>";
			table += "<label style='float:left; width:150px; display:inline'>From Area</label>";
			
			table += "<select id='from_areaid_" + i + "' name='from_areaid_" + i + "'>" +
					 areas +
					 "</select>";
			
			table += "<label style='padding-left: 40px; width:120px; display:inline'> Cabinet </label>";
			table += "<input type='text' id='from_cabinet_" + i + "' name='from_cabinet_" + i + "' style='width:50px'></input><br>";
			
			table += "<label style='float:left; width:150px; display:inline'>To Area</label>";
			
			table += "<select id='to_areaid_" + i + "' name='to_areaid_" + i + "'>" +
					 areas +
					 "</select>";
					 
			table += "<label style='padding-left: 40px; width:120px; display:inline'> Cabinet </label>";
			table += "<input type='text' id='to_cabinet_" + i + "'  name='to_cabinet_" + i + "' style='width:50px'></input><br>";
			table += "<label style='float:left; width:150px; display:inline'>Installation Notes</label>";
			table += "<input type='text' cols=80 style='width:515px' id='to_presentation_" + i + "' name='to_presentation_" + i + "'></input><br><br>";
			table += "</div>";
		}
		
		table += "</div>";
		
		$("#additionalInfoPanel").html(table);
	}
	
	function showPanelDestinations(qty) {
		var table = "<div id='paneldiv'>";
		var optPosition = "<option value='F'>Front</option><option value='R'>Rear</option>";
		var optLocation = "<option value='T'>Next Available From Top</option><option value='B'>Next Available From Bottom</option><option value='F'>Under The Floor</option><option value='U'>'U' Location</option>";
		var areas = "<?php createComboOptions("id", "name", "datatech_areas"); ?>";
		
		table += "<div class='paneladditionalitem' id='paneldiv_0'><label style='float:left; width:90px; display:inline'>From Area</label>";
		table += "<select id='from_areaid_0' name='from_areaid_0'>" + areas + "</select>";
		table += "<label style='padding-left: 15px; display:inline'>Cabinet</label>";
		table += "<input type='text' id='from_cabinet_0'  name='from_cabinet_0' style='margin-left: 20px; width:50px'></input>";
		table += "<label style='padding-left: 15px;  display:inline'>Positioning </label>";
		table += "<select id='from_position_0' name='from_position_0' style='width:80px'>" + optPosition + "</select>";
		table += "<label style='padding-left: 15px; display:inline'>Location </label>";
		table += "<select onchange='uLocationChange(this)' class='ulocation' id='from_location_0'  name='from_location_0' style='width:210px'>" + optLocation + "</select>";
		table += "<label style='display:none; padding-left: 15px; '> 'U'</label>";
		table += "<input type='text' id='from_uloc_0' name='from_uloc_0' style='display:none; width:30px'></input><br>";
		table += "</div>";
		
		for (var i = 1; i <= qty; i++) {
			
			table += "<div class='paneladditionalitem' id='paneldiv_" + i + "'>";
			
			table += "<label style='float:left; width:90px; display:inline'>To Area</label>";
			table += "<select id='from_areaid_" + i + "' name='from_areaid_" + i + "'>" + areas + "</select>";
			table += "<label style='padding-left: 15px; display:inline'>Cabinet</label>";
			table += "<input type='text' id='to_cabinet_" + i + "'  name='to_cabinet_" + i + "' style='margin-left: 20px; width:50px'></input>";
			table += "<label style='padding-left: 15px; display:inline'>Positioning </label>";
			table += "<select id='from_position_" + i + "'  name='from_position__" + i + "' style='width:80px'>" + optPosition + "</select>";
			table += "<label style='padding-left: 15px; display:inline'>Location </label>";
			table += "<select onchange='uLocationChange(this)' class='ulocation' id='from_location_" + i + "' name='from_location_" + i + "' style='width:210px'>" + optLocation + "</select>";
			table += "<label style='display:none; padding-left: 15px; '> 'U'</label>";
			table += "<input type='text' id='to_uloc_" + i + "' name='to_uloc_" + i + "' style='display:none; width:30px'></input><br>";
			table += "</div>";
		}

		table += "</div>";
		
		$("#additionalInfoPanel").html(table);
	}
	
	function uLocationChange(widget) {
		if ($(widget).val() == "U") {
			$(widget).next().css('display', 'inline');
			$(widget).next().next().show();
			
		} else {
			$(widget).next().hide();
			$(widget).next().next().hide();
		}
	}
	
	function validate() {
		if ($("#category").find('option:selected').text() == "Copper Panels") {
			if($("#coppercat1").val() == 0) {
				alert("Category must be specified");
				$("#coppercat1").focus();
				return false;
			}
		}
		
		return true;
	}
	
	$(document).ready(function() {
			$("#notesDialog").dialog({
					autoOpen: false,
					modal: true,
					width: 400,
					height: 300,
					title: "Notes",
					buttons: {
						Ok: function() {
							$(this).dialog("close");
						}
					}
				});
				
			$("#additionalInfoDialog").dialog({
					autoOpen: false,
					modal: true,
					width: 900,
					height: 480,
					buttons: {
						Ok: function() {
							$(this).dialog("close");
							$("#quoteForm").submit();
						},
						Cancel: function() {
							$(this).dialog("close");
						}
					}
				});
				
			/* Top level category change event. */
			$("#category").change(
					function() {
						/* Close all data type DIVs */
						$("#longlinepanel").hide();
						$("#ancillary").hide();
						$("#labourtask").hide();
						
						$("#type").val($("#category").find('option:selected').text());
						$("#topleveltype").val($("#category").find('option:selected').text());
						
						/* Set the top level type. */
						if ($("#category").find('option:selected').text() == "Panels" ||
						    $("#category").find('option:selected').text() == "Long Lines") {
							/* Panels. */
							getCategories(
									"#longlinepanelcategory",
									$("#category").val(), 
									function() {
										$("#coppercat1").val(0);
										$("#copperproduct").val(0);
										$("#copperlength").val(0);
										$("#copperqty").val("0");
										$("#fibrecat1").val(0);
										$("#fibreproduct").val(0);
										$("#fibrelength").val(0);
										$("#fibreqty").val("0");
										
										$("#longlinepanel").show();
									}
								);
								
						} else if ($("#category").find('option:selected').text() == "Sundry Items") {
							$("#ancillary").show();
							$("#ancillaryqty").focus();
								
						} else if ($("#category").find('option:selected').text() == "Labour Task") {
							getTasks(
									"#labourtaskproduct",
									function() {
										$("#labourtask").show();
									}
								);
						} 	
						
						$("#submit").show();
						$("#btnNotes").show();
					}
				);
				
			/* Long line / Panel category change. */				
			$("#longlinepanelcategory").change(
					function() {
						$("#copper").hide();
						$("#fibre").hide();
						
						$("#type").val($("#longlinepanelcategory").find('option:selected').text());
						
						if ($("#longlinepanelcategory").find('option:selected').text() == "Copper Panels" ||
						    $("#longlinepanelcategory").find('option:selected').text() == "Copper") {
							/* Copper. */
							getCategories(
									"#coppercat1",
									$("#longlinepanelcategory").val(), 
									function() {
										$("#copper").show();
										$("#coppercat1").val(0);
										$("#copperproduct").val(0);
										$("#copperlength").val(0);
										$("#copperqty").val("0");
										$("#coppercat1").focus();
									}
								);
								
						} else if ($("#longlinepanelcategory").find('option:selected').text() == "Fibre Panels" ||
						           $("#longlinepanelcategory").find('option:selected').text() == "Fibre") {
							/* Copper. */
							getCategories(
									"#fibrecat1",
									$("#longlinepanelcategory").val(), 
									function() {
										$("#fibre").show();
										$("#fibrecat1").val("0");
										$("#fibreproduct").val("0");
										$("#fibrelength").val("0");
										$("#fibreqty").val("0");
										$("#fibrecat1").focus();
									}
								);
						}
					}
				);
				
			/* Copper category. */
			$("#coppercat1").change(
					function() {
						getProducts(
								"#copperproduct",
								$("#coppercat1").val(), 
								function() {
								}
							);
					}
				);
				
			/* Copper product change. */
			$("#copperproduct").change(
					function() {
						$('#copperproductdesc').val($("#copperproduct").find('option:selected').text());
//						alert($('#copperproductdesc').val().indexOf("to each)"));
						
						getLengths(
								"#copperlength",
								$("#copperproduct").val(), 
								function() {
								}
							);
					}
				);

			/* Fibre category change. */				
			$("#fibrecat1").change(
					function() {
						getProducts(
								"#fibreproduct",
								$("#fibrecat1").val(), 
								function() {
								}
							);
					}
				);

			/* Labour task product. */				
			$("#labourtaskproduct").change(
					function() {
						$('#labourtaskproductdesc').val($(this).find('option:selected').text());
					}
				);
				
			/* Back to header. */
			$("#btnHeader").click(
					function() {
						window.location.href = 'newquote.php';
					}
				);
				
			/* Notes entry. */				
			$("#btnNotes").click(
					function() {
						$("#notesDialog").dialog("open");
					}
				);
				
			/* Add to Quote. */
			$("#btnAdditionalInfo").click(
					function() {
						if ($("#topleveltype").val() == "Long Lines") {
							/* Show long line destinations. */
							
							if ($("#type").val() == "Copper") {
								showLongLineDestinations($("#copperqty").val());
								
							} else {
								showLongLineDestinations($("#fibreqty").val());
							}
							
							/* Open the dialog. */
							$("#additionalInfoDialog").dialog("open");
							
						} else if ($("#topleveltype").val() == "Panels") {
							/* Show panel destinations. */
							
							if ($("#type").val() == "Copper Panels") {
								if ($('#copperproductdesc').val().indexOf("to eight panels") != -1) {
									showPanelDestinations(8);
									
								} else if ($('#copperproductdesc').val().indexOf("to four panels") != -1) {
									showPanelDestinations(4);
									
								} else if ($('#copperproductdesc').val().indexOf("to two panels") != -1) {
									showPanelDestinations(2);
									
								} else {
									showPanelDestinations(1);
								}
								
							} else {
								if ($('#fibreproductdesc').val().indexOf("to eight panels") != -1) {
									showPanelDestinations(8);
									
								} else if ($('#fibreproductdesc').val().indexOf("to four panels") != -1) {
									showPanelDestinations(4);
									
								} else if ($('#fibreproductdesc').val().indexOf("to two panels") != -1) {
									showPanelDestinations(2);
									
								} else {
									showPanelDestinations(1);
								}
							}
							
							/* Open the dialog. */
							$("#additionalInfoDialog").dialog("open");
						}
					}
				);
				
			/* Add to Quote. */
			$("#btnAddToQuote").click(
					function() {
						$("#quoteForm").submit();
					}
				);
				
		});
</script>
<?php include("system-footer.php"); ?>