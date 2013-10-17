<?php
session_start();
include 'db.php';
		
function escape($s) {
	$s = str_replace('"','&quot;',$s);
	$s = str_replace("'","&#39;",$s);
	$s = str_replace("(","&#40;",$s);
	$s = str_replace(")","&#41;",$s);
	return $s;
}

$lat = $_POST['lat'];
$lng = $_POST['lng'];
$eventname = mysql_real_escape_string($_POST['eventname']);
$location = mysql_real_escape_string($_POST['location']);
$date = $_POST['date'];
$hour = $_POST['hour'];
$minutes = $_POST['minutes'];
$ampm = $_POST['ampm'];
$cat = isset($_POST['cat']) ? $_POST['cat'] : null;
$description = mysql_real_escape_string($_POST['description']);
$old_date = "".$date." ".$hour.":".$minutes.$ampm;
$datetime = date( "Y-m-d H:i:s", strtotime($old_date));

$insert_query = "INSERT INTO events (eventname,location,datetime,cat,des,lat,lng) VALUES ('".$eventname."', '".$location."', '".$datetime."','".$cat."','".$description."','".$lat."','".$lng."')";
mysql_query($insert_query);
if (!$insert_query){
	die(mysql_error());
}

$query = mysql_query("SELECT eventid FROM events WHERE eventname = '$eventname' ORDER BY eventid DESC LIMIT 1");

if (!$query){
	die(mysql_error());
}
$row = mysql_fetch_assoc($query);
$eventid = $row['eventid'];
$userid = $_SESSION['userid'];
$query = mysql_query("INSERT INTO events_users VALUES ($eventid, $userid)");

header('Location: main.php?eventid='.$eventid);
?>