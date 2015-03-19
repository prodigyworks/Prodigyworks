<?php
	//Include database connection details
	require_once('system-config.php');
	
	//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;
	
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	
	//Sanitize the POST values
	$fname = clean($_POST['fname']);
	$lname = clean($_POST['lname']);
	$login = clean($_POST['login']);
	$password = clean($_POST['password']);
	$cpassword = clean($_POST['cpassword']);
	$building = clean($_POST['building']);
	$street = clean($_POST['street']);
	$town = clean($_POST['town']);
	$city = clean($_POST['city']);
	$county = clean($_POST['county']);
	$postcode = clean($_POST['postcode']);
	
	//Input Validations
	if($fname == '') {
		$errmsg_arr[] = 'First name missing';
		$errflag = true;
	}
	if($lname == '') {
		$errmsg_arr[] = 'Last name missing';
		$errflag = true;
	}
	if($login == '') {
		$errmsg_arr[] = 'Login ID missing';
		$errflag = true;
	}
	if($password == '') {
		$errmsg_arr[] = 'Password missing';
		$errflag = true;
	}
	if($cpassword == '') {
		$errmsg_arr[] = 'Confirm password missing';
		$errflag = true;
	}
	if($postcode == '') {
		$errmsg_arr[] = 'Post code missing';
		$errflag = true;
	}
	if( strcmp($password, $cpassword) != 0 ) {
		$errmsg_arr[] = 'Passwords do not match';
		$errflag = true;
	}
	
	//Check for duplicate login ID
	if($login != '') {
		$qry = "SELECT * FROM members WHERE login='$login'";
		$result = mysql_query($qry);
		if($result) {
			if(mysql_num_rows($result) > 0) {
				$errmsg_arr[] = 'Login ID already in use';
				$errflag = true;
			}
			@mysql_free_result($result);
		}
		else {
			die("Query failed");
		}
	}
	
	//If there are input validations, redirect back to the registration form
	if($errflag) {
		$_SESSION['PW_ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: system-register.php");
		exit();
	}

	//Create INSERT query
	$qry = "INSERT INTO members " .
			"(firstname, lastname, login, passwd, building, street, town, city, county, postcode) " .
			"VALUES" .
			"('$fname','$lname','$login','".md5($_POST['password'])."', '$building', '$street', '$town', '$city', '$county', '$postcode')";
	$result = @mysql_query($qry);
	$memberid = mysql_insert_id();

	//Create INSERT query
	$qry = "INSERT INTO userroles(memberid, roleid) VALUES($memberid, 'PUBLIC')";
	$result = @mysql_query($qry);
	
	$qry = "INSERT INTO userroles(memberid, roleid) VALUES($memberid, 'USER')";
	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		header("location: system-register-success.php");
		exit();
	}else {
		die("Query failed");
	}
?>