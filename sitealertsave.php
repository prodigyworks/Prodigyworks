<?php
	include("system-header.php"); 
	
	if (isset($_POST['checked'])) {
		$counter = count($_POST['checked']);
		
		for ($i = 0; $i < $counter; $i++) {
			sendUserMessage(
					$_POST['checked'][$i],
					"Oracle Logs Alert",
					$_POST['deploynotes']
				);
		}

		echo "<h2>Email alert has been sent.</h2>";
	}

	include("system-footer.php"); 
?>