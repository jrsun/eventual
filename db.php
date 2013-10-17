<?php

/* function try_query($str){
	$query = mysql_query($str);
	if (!$query){
		die(mysql_error());
	}
	return $query;
} */

$db = mysql_connect("localhost:3306", "root", "");
if(!$db){
	die(mysql_error());
}
mysql_select_db("6470", $db);

if(!(isset($_SESSION['userid'])) && isset($_SESSION['loggedin'])){ //set userid as session variable if not set
	$query = mysql_query("SELECT userid FROM users WHERE username = '".$_SESSION['username']."'");
	$user_data = mysql_fetch_assoc($query);
	$_SESSION['userid'] = $user_data['userid'];
}
?>