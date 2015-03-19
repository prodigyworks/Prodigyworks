<?php
	include("system-db.php");
	
	start_db();
	initialise_db();
	
	ShoppingBasket::initialise();
	ShoppingBasket::add(
			$_POST['uom'], 
			$_POST['qty'], 
			$_POST['price']
		);

	header("location: shoppingbasket.php"); 
?>
