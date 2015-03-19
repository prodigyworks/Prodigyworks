<?php
	if (isUserInRole("ADMIN")) {
		header("location: awaitingdespatches.php");
		
	} else {
		header("location: despatchesforsigning.php");
	}
?>
