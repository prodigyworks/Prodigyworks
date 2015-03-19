<?php
	//Start session
	session_start();
	
	//Unset the variables stored in session
	unset($_SESSION['PW_SESS_MEMBER_ID']);
	unset($_SESSION['PW_SESS_FIRST_NAME']);
	unset($_SESSION['PW_SESS_LAST_NAME']);
	unset($_SESSION['PW_ROLES']);
	unset($_SESSION['PW_breadcrumb']);
	unset($_SESSION['PW_breadcrumbPage']);
	
header("location: index.php");
?>
