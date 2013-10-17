<?php

session_start();
include 'db.php';

if (isset($_COOKIE['login_cookie'])){	//if cookie exists, use cookie to log in
	$cookie = explode("|", urldecode($_COOKIE['login_cookie']));
	$username = $cookie[0];
	$password_hash = $cookie[1];
	$result = mysql_query("SELECT * FROM users WHERE USERNAME='".$username."'");
	$row = mysql_fetch_array($result);
	if ($row['PASSWORD_HASH'] == $password_hash){
		$_SESSION['loggedin'] = TRUE; //successful login by cookie
		$_SESSION['username'] = $username;
	}
} else {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$remember = $_POST['remember'];
	$result = mysql_query("SELECT * FROM users WHERE USERNAME='".$username."'");
	$row = mysql_fetch_array($result);
	if (sha1($password) == $row["PASSWORD_HASH"]){
		$_SESSION['loggedin'] = TRUE; //successful login by password
		$_SESSION['username'] = $username;
		echo "success";
		if ($remember == 'true'){
			setcookie("login_cookie", $username . "|" . sha1($password));	
			//set cookie if remember is checked
		}
	} else {
		echo "fail";
	}
}
?>