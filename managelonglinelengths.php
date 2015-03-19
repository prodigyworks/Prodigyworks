<?php
	require_once("liblengths.php");
		
	class LongLineLengthCrud extends LibLengthCrud {
		public function getUnitApplication() {
			return "managelonglineunits.php";
		}
	}
	
	$crud = new LongLineLengthCrud();
	$crud->title = "Long Lines";
	$crud->run();
?>