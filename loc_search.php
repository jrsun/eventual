<?php
session_start();
include 'db.php';
include 'user_header.php';

?>

<DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Google Search API Sample</title>
    <script src="http://www.google.com/jsapi?key=AIzaSyAWuwPwhX4cuPIsErK8k3jQn0FB9tbXWGU"></script>
    <script type="text/javascript">
	</script>
  </head>
  <body>
	<div id='result'>Hi!</div>
	<form>
	<input type="text" id="search" />
	<input type="button" id="search_submit" value="Search!" />
	</form>
  <script type='text/javascript'>
	$(document).ready(function(){
		$('#search_submit').click( function(){
			var encode = encodeURIComponent($('#search').val());
			var url = "http://maps.googleapis.com/maps/api/geocode/json?address=" + encode + "&sensor=false";
			$.ajax({
				url: url,
				success: function(result){
				var lat = result['results'][0]['geometry']['location']['lat'];
				var lng = result['results'][0]['geometry']['location']['lng'];
				$('#result').html(lat + "," + lng);
				},
				dataType: 'json'
			});
		});
	});
	
	/*
	
				var encode = encodeURIComponent($('#location').val());
				var url = "http://maps.googleapis.com/maps/api/geocode/json?address=" + encode + "&sensor=false";
				$.ajax({
						url: url,
						success: function(result){
						var lat = result['results'][0]['geometry']['location']['lat'];
						var lng = result['results'][0]['geometry']['location']['lng'];
						$('#latlong').val(lat+","+lng);
						$('form').submit();
						},
						dataType: 'json'
						*/
  </script>
  </body>
</head>