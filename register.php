<?php
include 'db.php';


$username = (isset($_POST['username']))? $_POST['username'] : null;
$password = (isset($_POST['password']))? $_POST['password'] : null;
$email = (isset($_POST['email']))? $_POST['email'] : null;

if(($username == "") || ($password == "") || ($email == "")){
	echo "blanks";
} else {
	$user_exists = mysql_query("SELECT USERNAME FROM users WHERE USERNAME='".$username."' OR email='".$email."'");
	$num_rows = mysql_num_rows($user_exists);
	if ($num_rows > 0){
		echo "exists";
	} else {
		$query = mysql_query("INSERT INTO users (USERNAME, PASSWORD_HASH, email) VALUES ('".$username."', 
		'".sha1($password)."', '".$email."')");
		if (!$query){
			die(mysql_error());
		}
		echo "success";
	}
}
?>
