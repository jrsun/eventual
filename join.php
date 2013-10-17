<?php
session_start();
include 'db.php';

$eventid = $_POST['eventid'];
$userid = $_SESSION['userid'];
$query = mysql_query("INSERT INTO events_users VALUES ($eventid, $userid)");


	
?>