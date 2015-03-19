<?php
	require_once("liblengths.php");
	
	class PanelLinkLengthCrud extends LibLengthCrud {
		public function getUnitApplication() {
			return "managepanellinkunits.php";
		}
	}
	
	$crud = new PanelLinkLengthCrud();	
	$crud->title = "Panel Link";
	$crud->run();
?>