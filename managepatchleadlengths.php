<?php
	require_once("liblengths.php");
		
	class PatchLeadLengthCrud extends LibLengthCrud {
		public function getUnitApplication() {
			return "managepatchleadunits.php";
		}
	}
	
	$crud = new PatchLeadLengthCrud();
	$crud->title = "Patch Leads";
	$crud->run();
?>