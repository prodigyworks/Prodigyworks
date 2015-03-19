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
	$login = clean($_POST['login']);
	$password = clean($_POST['password']);
	
	//Input Validations
	if($login == '') {
		$errmsg_arr[] = 'Login ID missing';
		$errflag = true;
	}
	if($password == '') {
		$errmsg_arr[] = 'Password missing';
		$errflag = true;
	}
	
	//If there are input validations, redirect back to the login form
	if($errflag) {
		$_SESSION['PW_ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: system-login-failed.php");
		exit();
	}
	
	//Create query
	$qry="SELECT * FROM members WHERE login='$login' AND passwd='".md5($_POST['password'])."'";
	$result=mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		if(mysql_num_rows($result) == 1) {
			//Login Successful
			session_regenerate_id();
			$member = mysql_fetch_assoc($result);
			
			$_SESSION['PW_SESS_MEMBER_ID'] = $member['member_id'];
			$_SESSION['PW_SESS_FIRST_NAME'] = $member['firstname'];
			$_SESSION['PW_SESS_LAST_NAME'] = $member['lastname'];
			
			$qry = "SELECT * FROM userroles WHERE memberid = " . $_SESSION['PW_SESS_MEMBER_ID'] . "";
			$result=mysql_query($qry);
			$index = 0;
			
			$arr = array();
			$arr[$index++] = "PUBLIC";
			
			//Check whether the query was successful or not
			if($result) {
				while($member = mysql_fetch_assoc($result)) {
					$arr[$index++] = $member['roleid'];
				}
			} else {
				die('Failed to connect to server: ' . mysql_error());
			}
			
			$_SESSION['PW_ROLES'] = $arr;
			
			session_write_close();
			header("location: index.php");
			exit();
		}else {
			//Login failed
			header("location: system-login-failed.php");
			exit();
		}
	}else {
		die("Query failed");
	}
?>