<?php
	set_time_limit(100);
	include("system-header.php"); 

	function startsWith($haystack, $needle) {
	    return !strncmp($haystack, $needle, strlen($needle));
	}
	
	if (isset($_FILES['productscsv']) && $_FILES['productscsv']['tmp_name'] != "") {
		if ($_FILES["productscsv"]["error"] > 0) {
			echo "Error: " . $_FILES["productscsv"]["error"] . "<br />";
			
		} else {
		  	echo "Upload: " . $_FILES["productscsv"]["name"] . "<br />";
		  	echo "Type: " . $_FILES["productscsv"]["type"] . "<br />";
		  	echo "Size: " . ($_FILES["productscsv"]["size"] / 1024) . " Kb<br />";
		  	echo "Stored in: " . $_FILES["productscsv"]["tmp_name"] . "<br>";
		}
		
		$row = 1;
		
		if (($handle = fopen($_FILES['productscsv']['tmp_name'], "r")) !== FALSE) {
		    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		        if ($row++ == 1) {
		        	continue;
		        }
		        
		        $num = count($data);
		        
		        $poa = 0;
		        $luboildescription = mysql_escape_string($data[0]);
		        $nopricedescription = mysql_escape_string($data[1]);
		        $consideredproduct = mysql_escape_string($data[2]);
		        $maindescription = mysql_escape_string($data[3]);
		        $additionalinfo = mysql_escape_string($data[4]);
		        $luboildescriptionforpricing = mysql_escape_string($data[5]);
		        $price = $data[6];
		        $weight = $data[7];
		        $category = ucfirst($data[8]);
		        $subcategory = ucfirst($data[9]);
		        $stockstatus = $data[10];
		        
		        if ($stockstatus == "oos") {
		        	$stockstatus = "O";
		        	
		        } else {
		        	$stockstatus = "I";
		        }
		        
		        if ($category == "") {
		        	$category = "Other Oils";
		        }
		        
		        if ($subcategory == "") {
		        	$subcategory = "Other";
		        }
		        
		        if (trim($price) == "") {
		        	$price = "NULL";
		        	
		        } else if (trim($price) == "POA") {
		        	$price = "NULL";
		        	$poa = 1;
		        	
		        } else {
		        	$price = str_replace(",", "", $price);
		        }
		        
		        if (trim($weight) == "") {
		        	$weight = "NULL";
		        }
		        
				$qry = "INSERT INTO {$_SESSION['DB_PREFIX']}category (name, parentcategoryid) " .
						"VALUES " .
						"('$category', 0)";
				$result = mysql_query($qry);
	        	$categoryid =  mysql_insert_id();

				if (mysql_errno() == 1062) {
					$qry = "SELECT id " .
							"FROM {$_SESSION['DB_PREFIX']}category " .
							"WHERE name = '$category'";
					$result = mysql_query($qry);
					
					//Check whether the query was successful or not
					if ($result) {
						while (($member = mysql_fetch_assoc($result))) {
							$categoryid = $member['id'];
						}
					}
					
				} else {
					if (! $result) die("Error :" . mysql_errno() . " : " . mysql_error() . " : " .  $qry);
				}					
	        	
				$qry = "INSERT INTO {$_SESSION['DB_PREFIX']}category (name, parentcategoryid) " .
						"VALUES " .
						"('$subcategory', $categoryid)";
				$result = mysql_query($qry);
	        	$subcatid =  mysql_insert_id();
	        	
				if (mysql_errno() == 1062) {
					$qry = "SELECT id " .
							"FROM {$_SESSION['DB_PREFIX']}category " .
							"WHERE name = '$subcategory' " .
							"AND parentcategoryid = $categoryid";
					$result = mysql_query($qry);
					
					//Check whether the query was successful or not
					if ($result) {
						while (($member = mysql_fetch_assoc($result))) {
							$subcatid = $member['id'];
						}
					}
					
				} else {
					if (! $result) die("Error :" . mysql_errno() . " : " . mysql_error() . " : " .  $qry);
				}
	        	
				$qry = "INSERT INTO {$_SESSION['DB_PREFIX']}product " .
						"(categoryid, name, nopricedescription, maindescription, additionalinfo, consideredproduct) " .
						"VALUES " .
						"($subcatid, '$luboildescription', '$nopricedescription', '$maindescription', '$additionalinfo', '$consideredproduct')";
						
				$result = mysql_query($qry);
				
				if (mysql_errno() == 1062) {
					$qry = "UPDATE {$_SESSION['DB_PREFIX']}product SET " .
							"name = '$luboildescription', " .
							"nopricedescription = '$nopricedescription', " .
							"maindescription = '$maindescription', " .
							"additionalinfo = '$additionalinfo', " .
							"consideredproduct = '$consideredproduct' " .
							"WHERE categoryid = $subcatid " .
							"AND name = '$luboildescription'";
							
					$result = mysql_query($qry);
					
					$qry = "SELECT id " .
							"FROM {$_SESSION['DB_PREFIX']}product " .
							"WHERE name = '$luboildescription'";
					$result = mysql_query($qry);
					
					//Check whether the query was successful or not
					if ($result) {
						while (($member = mysql_fetch_assoc($result))) {
							$productid = $member['id'];
						}
					}
					
				} else {
					$productid = mysql_insert_id();
				}
				
				if (! $result) {
					logError($qry . " - " . mysql_error());
				}
				
				$uom = "Each";
				
				if ($luboildescriptionforpricing != "") {
					if (startsWith($luboildescriptionforpricing, $luboildescription)) {
						$uom = str_replace($luboildescription . " ", "", $luboildescriptionforpricing);
						$uom = str_replace("(", "", $uom);
						$uom = str_replace(")", "", $uom);
					}
				}
				
				$qry = "INSERT INTO {$_SESSION['DB_PREFIX']}productuom " .
						"(productid, name, price, weight, poa, stockstatus) " .
						"VALUES " .
						"($productid, '$uom', $price, $weight, $poa, '$stockstatus')";
						
				$result = mysql_query($qry);
				
				if (mysql_errno() == 1062) {
					$qry = "UPDATE {$_SESSION['DB_PREFIX']}productuom SET " .
							"price = $price, " .
							"weight = $weight," .
							"poa = $poa," .
							"stockstatus = '$stockstatus' " .
							"WHERE productid = $productid AND name = '$uom'";
							
					$result = mysql_query($qry);
				}
				
				if (! $result) {
					logError($qry . " - " . mysql_error());
				}
		    }
		    
		    
		    fclose($handle);
			echo "<h1>" . $row . " downloaded</h1>";
		}
	}
	
	if (! isset($_FILES['productscsv'])) {
?>	
		
<form class="contentform" method="post" enctype="multipart/form-data">
	<label>Upload Product List CSV file </label>
	<input type="file" name="productscsv" id="productscsv" /> 
	
	<br />
	<div id="submit" class="show">
		<input type="submit" value="Upload" />
	</div>
</form>
<?php
	}
	
	include("system-footer.php"); 
?>