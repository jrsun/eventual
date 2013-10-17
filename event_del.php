<?php
session_start();
include 'db.php';
$eventid = $_POST['eventid'];

$query = mysql_query("DELETE FROM events WHERE eventid = '$eventid' LIMIT 1");
?>