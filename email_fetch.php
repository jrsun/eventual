<?php
session_start();
include 'db.php';

function generate_random_string($name_length = 8) {
	$alpha_numeric = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	return substr(str_shuffle($alpha_numeric), 0, $name_length);
}

$email = mysql_real_escape_string($_POST['email']);
$qstr = "SELECT * FROM users WHERE email = ".$email;
$query = mysql_query("SELECT * FROM users WHERE email = '".$email."'");
if (!$query){
	die(mysql_error());
}
$arr = array();
while ($row = mysql_fetch_assoc($query)){
	$arr[] = $row;
}
if (count($arr) > 0){
	$new_pass = generate_random_string();
	if (mail($email, 'Reset Your Password', 'Your new password is '.$new_pass)){
		$query = mysql_query("UPDATE users SET PASSWORD_HASH = '".sha1($new_pass)."' WHERE email = '".$email."'");
		echo "sent";
	} else {
		echo "notsent";
	}
} else {
	echo "notfound";
}

?>