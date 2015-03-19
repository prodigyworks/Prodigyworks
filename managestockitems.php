<?php
	require_once("crud.php");
	require_once("confirmdialog.php");
	
	function checkout() {
		$id = $_POST['checkout_stockitemid'];
		$customerid = $_POST['checkout_customerid'];
		$addressid = $_POST['checkout_addressid'];
		$expecteddate = convertStringToDate($_POST['checkout_expecteddate']);
		
		$qry = "UPDATE {$_SESSION['DB_PREFIX']}stockitem SET " .
				"customerid = $customerid, " .
				"addressid = $addressid, " .
				"expectedreturndate = '$expecteddate'," .
				"checkedoutdate = NOW() " .
				"WHERE id = $id";
		$result = mysql_query($qry);
		
		if (! $result) {
			logError($qry . " - " . mysql_error());
		}
		
		$qry = "SELECT A.* FROM {$_SESSION['DB_PREFIX']}stockitem A " .
				"WHERE A.id = $id";
		$result = mysql_query($qry);
		
		if ($result) {
			while (($member = mysql_fetch_assoc($result))) {
				$customerid = $member['customerid'];
				$addressid = $member['addressid'];
				$customerid = $member['customerid'];
				$reason = $member['reason'];
				$instructions = $member['instructions'];
				$stockitemid = $member['id'];
				$expectedreturndate = $member['expectedreturndate'];
				
				$qry = "INSERT INTO {$_SESSION['DB_PREFIX']}despatchheader " .
						"(" .
						"customerid, addressid, memberid, despatchdate, reason, instructions, signed, despatched " .
						") " .
						" VALUES " .
						"(" .
						"'$customerid', '$addressid', '$memberid', NOW(), '$reason', '$instructions', 'N', 'N' " .
						")";
				$insertresult = mysql_query($qry);
	
				if (! $insertresult) {
					logError($qry . " - " . mysql_error());
				}
				
				$deliveryheaderid = mysql_insert_id();
				
				$qry = "INSERT INTO {$_SESSION['DB_PREFIX']}despatchitem " .
						"(" .
						"despatchid, stockitemid, expectedreturndate " .
						") " .
						" VALUES " .
						"(" .
						"$deliveryheaderid, '$stockitemid', '$expectedreturndate' " .
						")";
				$insertresult = mysql_query($qry);
	
				if (! $insertresult) {
					logError($qry . " - " . mysql_error());
				}
			}
			
		} else {
			logError($qry . " - " . mysql_error());
		}
	}
	
	function checkin() {
		$id = $_POST['checkin_stockitemid'];
		
		$qry = "UPDATE {$_SESSION['DB_PREFIX']}stockitem SET " .
				"customerid = null, " .
				"addressid = null, " .
				"expectedreturndate = null," .
				"checkedoutdate = null, " .
				"checkedindate = NOW() " .
				"WHERE id = $id";
		$result = mysql_query($qry);
		
		if (! $result) {
			logError($qry . " - " . mysql_error());
		}
	}
	
	function move() {
		$id = $_POST['move_stockitemid'];
		$warehouseid = $_POST['move_warehouseid'];
		
		$qry = "UPDATE {$_SESSION['DB_PREFIX']}warehousestock SET " .
				"warehouseid = $warehouseid " .
				"WHERE stockitemid = $id";
		$result = mysql_query($qry);
		
		if (! $result) {
			logError($qry . " - " . mysql_error());
		}
	}
	
	class StockItemCrud extends Crud {
		public function postHeaderEvent() {
			createConfirmDialog("confirmcheckoutdialog", "Confirm check out ?", "confirmcheckout");
			createConfirmDialog("confirmmovedialog", "Confirm stock movement ?", "confirmstockmovement");
			createConfirmDialog("confirmcheckindialog", "Confirm check in ?", "confirmcheckin");
			
			?>
				<div id="checkoutdialog" class="modal">
					<label>Customer</label><br>
					<?php createCombo("cocustomerid", "id", "name", "{$_SESSION['DB_PREFIX']}customers"); ?><br><br>
					<label>Expected Return Date</label><br>
					<input type="text" class="datepicker" id="coexpecteddate" name="coexpecteddate" value="<?php echo date("d/m/Y"); ?>" /><br><br>
					<label>Deliver To</label><br>
					<?php
					createCombo("coaddressid", "id", "name", "{$_SESSION['DB_PREFIX']}customeraddresses", "", false); 
					?><br>
					<div id="address"></div>
				</td>
			</tr>
				</div>
				<div id="movedialog" class="modal">
					<label>Warehouse</label><br>
					<?php createCombo("mowarehouseid", "id", "name", "{$_SESSION['DB_PREFIX']}warehouses"); ?>
				</div>
			<?php
		}
		
		public function postScriptEvent() {
?>
			var currentID = 0;
			
			/* Derived address callback. */
			function fullAddress(node) {
				var address = "";
				
				if ((node.street) != "" && node.street != null) {
					address = address + node.street;
				} 
				
				if ((node.town) != "" && node.town != null) {
					if (address != "") {
						address = address + ", ";
					}
					
					address = address + node.town;
				} 
				
				if ((node.city) != "" && node.city != null) {
					if (address != "") {
						address = address + ", ";
					}
					
					address = address + node.city;
				} 
				
				if ((node.county) != "" && node.county != null) {
					if (address != "") {
						address = address + ", ";
					}
					
					address = address + node.county;
				} 
				
				if ((node.postcode) != "" && node.postcode != null) {
					if (address != "") {
						address = address + ", ";
					}
					
					address = address + node.postcode;
				} 
				
				return address;
			}
			
			$(document).ready(
					function() {
						$("#coaddressid").change(
								function() {
									callAjax(
											"finddata.php", 
											{ 
												sql: "SELECT * FROM <?php echo $_SESSION['DB_PREFIX'];?>customeraddresses WHERE id = " + $("#coaddressid").val()
											},
											function(data) {
												if (data.length > 0) {
													var node = data[0];
													var address = "";
													
													if ((node.street) != "") {
														address = address + node.street;
													} 
													
													if ((node.town) != "") {
														if (address != "") {
															address = address + ", ";
														}
														
														address = address + node.town;
													} 
													
													if ((node.city) != "") {
														if (address != "") {
															address = address + ", ";
														}
														
														address = address + node.city;
													} 
													
													if ((node.county) != "") {
														if (address != "") {
															address = address + ", ";
														}
														
														address = address + node.county;
													} 
													
													if ((node.postcode) != "") {
														if (address != "") {
															address = address + ", ";
														}
														
														address = address + node.postcode;
													} 
													
													$("#address").html(address);
												}
											}
										);
								}
							);
						
						$("#checkoutdialog").dialog({
								modal: true,
								autoOpen: false,
								title: "Customer Check Out",
								width: 110,
								height: 280,
								buttons: {
									Ok: function() {
										$(this).dialog("close");
										
										$("#confirmcheckoutdialog .confirmdialogbody").html("You are about to check out this stock item.<br>Are you sure ?");
										$("#confirmcheckoutdialog").dialog("open");
									},
									Cancel: function() {
										$(this).dialog("close");
									}
								}
							});
							
						$("#movedialog").dialog({
								modal: true,
								autoOpen: false,
								title: "Move Stock Item",
								width: 110,
								height: 180,
								buttons: {
									Ok: function() {
										$(this).dialog("close");
										
										$("#confirmmovedialog .confirmdialogbody").html("You are about to move this stock item.<br>Are you sure ?");
										$("#confirmmovedialog").dialog("open");
									},
									Cancel: function() {
										$(this).dialog("close");
									}
								}
							});
					}
				);
				
			function checkin(pk) {
				currentID = pk;
				
				$("#confirmcheckindialog .confirmdialogbody").html("You are about to check in this stock item.<br>Are you sure ?");
				$("#confirmcheckindialog").dialog("open");
			}
				
			function checkout(pk) {
				currentID = pk;
				
				$("#checkoutdialog").dialog("open");
		    } 	
				
			function movestock(pk) {
				currentID = pk;
				
				$("#movedialog").dialog("open");
		    } 	
		    
		    function despatch(pk) {
		    	window.open("createdespatch.php?id=" + pk);
		    }
		    
		    function confirmcheckout() {
		    	$("#confirmcheckoutdialog").dialog("close");

				post("editform", "checkout", "submitframe", 
						{ 
							checkout_stockitemid: currentID, 
							checkout_customerid: $("#cocustomerid").val(),
							checkout_addressid: $("#coaddressid").val(),
							checkout_expecteddate: $("#coexpecteddate").val()
						}
					);
		    }
		    
		    function confirmcheckin() {
		    	$("#confirmcheckindialog").dialog("close");
		    	
				post("editform", "checkin", "submitframe", 
						{ 
							checkin_stockitemid: currentID, 
						}
					);
		    }
		    
		    function confirmstockmovement() {
		    	$("#confirmmovedialog").dialog("close");
		    	
				post("editform", "move", "submitframe", 
						{ 
							move_stockitemid: currentID, 
							move_warehouseid: $("#mowarehouseid").val()
						}
					);
		    }
<?php
		}
	}
	
	$crud = new StockItemCrud();
	$crud->title = "Stock";
	$crud->table = "{$_SESSION['DB_PREFIX']}stockitem";
	$crud->dialogwidth = 400;
	$crud->sql = 
			"SELECT A.*, B.name AS customername, D.name AS warehousename, " .
			"E.street, E.town, E.city, E.county, E.postcode " .
			"FROM {$_SESSION['DB_PREFIX']}stockitem A " .
			"LEFT OUTER JOIN {$_SESSION['DB_PREFIX']}customers B " .
			"ON B.id = A.customerid " .
			"INNER JOIN {$_SESSION['DB_PREFIX']}warehousestock C " .
			"ON C.stockitemid = A.id " .
			"INNER JOIN {$_SESSION['DB_PREFIX']}warehouses D " .
			"ON D.id = C.warehouseid " .
			"LEFT OUTER JOIN {$_SESSION['DB_PREFIX']}customeraddresses E " .
			"ON E.id = A.addressid " .
			"WHERE A.stockid = " . $_GET['id'] . " " .
			"ORDER BY A.serialnumber";
			
	$crud->messages = array(
			array('id'		  => 'checkin_stockitemid'),
			array('id'		  => 'checkout_stockitemid'),
			array('id'		  => 'checkout_customerid'),
			array('id'		  => 'checkout_addressid'),
			array('id'		  => 'checkout_expecteddate'),
			array('id'		  => 'move_stockitemid'),
			array('id'		  => 'move_warehouseid')
		);
		
	$crud->subapplications = array(
			array(
				'title'		  => 'Check Out',
				'imageurl'	  => 'images/stock.png',
				'script' 	  => 'checkout'
			),
			array(
				'title'		  => 'Check In',
				'imageurl'	  => 'images/stock.png',
				'script' 	  => 'checkin'
			),
			array(
				'title'		  => 'Move Stock',
				'imageurl'	  => 'images/stock.png',
				'script' 	  => 'movestock'
			)
		);
	$crud->columns = array(
			array(
				'name'       => 'id',
				'viewname'   => 'uniqueid',
				'length' 	 => 6,
				'showInView' => false,
				'filter'	 => false,
				'bind' 	 	 => false,
				'editable' 	 => false,
				'pk'		 => true,
				'label' 	 => 'ID'
			),
			array(
				'name'       => 'stockid',
				'length' 	 => 6,
				'showInView' => false,
				'filter'	 => false,
				'editable' 	 => false,
				'default'	 => $_GET['id'],
				'label' 	 => 'Stock ID'
			),
			array(
				'name'       => 'serialnumber',
				'length' 	 => 30,
				'label' 	 => 'Serial Number'
			),
			array(
				'name'       => 'warehousename',
				'length' 	 => 30,
				'label' 	 => 'Current Location'
			),
			array(
				'name'       => 'customername',
				'length' 	 => 30,
				'label' 	 => 'Customer'
			),
			array(
				'name'       => 'checkedoutdate',
				'datatype'   => 'timestamp',
				'length' 	 => 20,
				'label' 	 => 'Last Checked Out Date'
			),
			array(
				'name'       => 'expectedreturndate',
				'datatype'   => 'timestamp',
				'length' 	 => 20,
				'label' 	 => 'Expected Return Date'
			),
			array(
				'name'       => 'checkedindate',
				'datatype'   => 'timestamp',
				'length' 	 => 20,
				'label' 	 => 'Last Checked In Date'
			),
			array(
				'name'       => 'straddress',
				'length' 	 => 70,
				'editable'   => false,
				'bind'		 => false,
				'type'		 => 'DERIVED',
				'function'	 => 'fullAddress',
				'label' 	 => 'Address'
			)
		);
		
	$crud->run();
	
?>