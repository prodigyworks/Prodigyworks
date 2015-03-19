<?php
	require_once("crud.php");
	require_once("confirmdialog.php");
	
	function sign() {
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
	}
	
	class DespatchCrud extends Crud {
		public function postHeaderEvent() {
			if (isset($_POST['output'])) {
				require_once('signature-to-image.php');
				
				$img = sigJsonToImage($_POST['output']);
				
				ob_flush();
				ob_clean(); //to be sure there are no other strings in the output buffer
				imagepng($img);
				$imgstring = ob_get_contents();
				ob_end_clean();
				ob_start();
				
				$escimgstring = mysql_escape_string($imgstring);
				$id = $_POST['signatureid'];
				
				$qry = "INSERT INTO {$_SESSION['DB_PREFIX']}images " .
						"(" .
						"mimetype, name, image, createddate" .
						") " .
						"VALUES " .
						"(" .
						"'image/png', 'Signature for despatch $id', '$escimgstring', NOW()" .
						")";
				$result = mysql_query($qry);
				$imageid = mysql_insert_id();
				
				file_put_contents("uploads/signature_" . $imageid . ".png", $imgstring);
				
				if (! $result) {
					logError($qry . " - " . mysql_error());
				}
				
				$qry = "UPDATE {$_SESSION['DB_PREFIX']}despatchheader SET " .
						"signed = 'Y', " .
						"signeddate = NOW(), " .
						"imageid = $imageid " .
						"WHERE id = $id";
				$result = mysql_query($qry);
				
				if (! $result) {
					logError($qry . " - " . mysql_error());
				}
			}
			
			?>
			  <link rel="stylesheet" href="build/jquery.signaturepad.css">
			  <!--[if lt IE 9]><script src="build/flashcanvas.js"></script><![endif]-->
			  <script src="build/jquery.signaturepad.min.js"></script>
			  <script src="build/json2.min.js"></script>
			<?php		  
			createConfirmDialog("confirmcheckoutdialog", "Confirm check out ?", "confirmcheckout");
			createConfirmDialog("confirmmovedialog", "Confirm stock movement ?", "confirmstockmovement");
			createConfirmDialog("confirmcheckindialog", "Confirm check in ?", "confirmcheckin");
			
			?>
				  <form method="post" action="" class="sigPad">
				    <label for="name">Print your name</label>
				    <input type="text" name="name" id="name" class="name">
				    <p class="typeItDesc">Review your signature</p>
				    <p class="drawItDesc">Draw your signature</p>
				    <ul class="sigNav">
				      <li class="typeIt"><a href="#type-it" class="current">Type It</a></li>
				      <li class="drawIt"><a href="#draw-it" >Draw It</a></li>
				      <li class="clearButton"><a href="#clear">Clear</a></li>
				    </ul>
				    <div class="sig sigWrapper">
				      <div class="typed"></div>
				      <canvas class="pad" width="198" height="55"></canvas>
				      <input type="hidden" name="output" class="output">
				      <input type="hidden" id="signatureid" name="signatureid">
				    </div>
				    <button type="submit">I accept the terms of this agreement.</button>
				  </form>
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
							
				      	$('.sigPad').signaturePad();
				      	
					}
				);
				
			function checkin(pk) {
				currentID = pk;
				
				$("#confirmcheckindialog .confirmdialogbody").html("You are about to check in this stock item.<br>Are you sure ?");
				$("#confirmcheckindialog").dialog("open");
			}
				
			function sign(pk) {
				currentID = pk;
				
				$("#signatureid").val(pk);
				$(".sigPad").fadeIn();
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
	
	$crud = new DespatchCrud();
	$crud->title = "Stock";
	$crud->table = "{$_SESSION['DB_PREFIX']}despatchheader";
	$crud->dialogwidth = 400;
	$crud->sql = 
			"SELECT A.*, AB.serialnumber, B.name AS customername, D.name AS warehousename, " .
			"E.street, E.town, E.city, E.county, E.postcode " .
			"FROM {$_SESSION['DB_PREFIX']}despatchheader A " .
			"INNER JOIN {$_SESSION['DB_PREFIX']}despatchitem AA " .
			"ON AA.despatchid = A.id " .
			"INNER JOIN {$_SESSION['DB_PREFIX']}stockitem AB " .
			"ON AB.id = AA.stockitemid " .
			"LEFT OUTER JOIN {$_SESSION['DB_PREFIX']}customers B " .
			"ON B.id = A.customerid " .
			"INNER JOIN {$_SESSION['DB_PREFIX']}warehousestock C " .
			"ON C.stockitemid = AB.id " .
			"INNER JOIN {$_SESSION['DB_PREFIX']}warehouses D " .
			"ON D.id = C.warehouseid " .
			"LEFT OUTER JOIN {$_SESSION['DB_PREFIX']}customeraddresses E " .
			"ON E.id = A.addressid " .
			"WHERE A.signed = 'N' " .
			"AND A.customerid = " . $_SESSION['CUSTOMER_ID'] . " " .
			"ORDER BY AB.serialnumber";
			
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
				'title'		  => 'Sign',
				'imageurl'	  => 'images/stock.png',
				'script' 	  => 'sign'
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