<?php
include 'db.php';
include 'user_header.php';
?>

<html>
  <head>
  <script type='text/javascript' src='js/jquery.min.js'></script>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      body {margin: 100px 0px 0px 0px;}
      #map_canvas { height: 90% }
    </style>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjPxnynVgZs7SgVsCFTyzVautB5DkePwU&sensor=false">
    </script>
    <script type="text/javascript">
      function initialize() {
        var mapOptions = {
          center: new google.maps.LatLng(42.360616,-71.09024),
          zoom: 15,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map_canvas"),
            mapOptions);
		
		$.get('map_fetch.php', function(data){
			console.log(data);
			var json = $.parseJSON(data);
			$.each(json, function(){
				var lat = this['lat'];
				var lng = this['lng'];
				var eventid = this['eventid'];
				var name = this['eventname'];
				var mLatLng = new google.maps.LatLng(lat, lng);
				var marker = new google.maps.Marker({
					position: mLatLng,
					title: name,
					url: 'main.php?eventid=' + eventid
				});
				google.maps.event.addListener(marker, 'click', function(){
					window.location.href = marker.url;
				});
				marker.setMap(map);
			});
		});
      }
    </script>
  </head>
  <body onload="initialize()">
	<div class="container">
		<div id="map_canvas"></div>
	</div>
  </body>
</html>