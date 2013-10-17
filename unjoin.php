<?php
session_start();
include 'db.php';

$eventid = $_POST['eventid'];
$userid = $_SESSION['userid'];
$query = mysql_query("DELETE FROM events_users WHERE userid = $userid AND eventid = $eventid");
	
?>