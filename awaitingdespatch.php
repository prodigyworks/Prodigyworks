<?php
	require_once("crud.php");
	require_once("confirmdialog.php");
	
	class AwaitingDespatchCrud extends Crud {
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
					}
				);
				
		    function despatch(pk) {
		    	window.open("createdespatch.php?id=" + pk);
		    }
		    
<?php
		}
	}
	
	$crud = new AwaitingDespatchCrud();
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
			"WHERE A.signed = 'Y' " .
			"AND A.despatched = 'N' " .
			"ORDER BY AB.serialnumber";
			
	$crud->subapplications = array(
			array(
				'title'		  => 'Despatch',
				'imageurl'	  => 'images/stock.png',
				'script' 	  => 'despatch'
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
				'name'       => 'signeddate',
				'datatype'   => 'timestamp',
				'length' 	 => 20,
				'label' 	 => 'Signed Date'
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