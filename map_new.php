<?php
include 'db.php';
?>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { height: 100% }
    </style>
    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjPxnynVgZs7SgVsCFTyzVautB5DkePwU&sensor=false">
    </script>
    <script type="text/javascript">
      function initialize() {
        var mapOptions = {
          center: new google.maps.LatLng(42.359738,-71.093788),
          zoom: 14,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map_canvas"),
            mapOptions);
			
		$.get('map_fetch.php', function(data){
			var json = $.parseJSON(data);
			$.each(json, function(){
				var lat = this['lat'];
				var lng = this['lng'];
				var name = this['eventname'];
				console.log(lat + ',' + lng + ':' + name);
				var mLatLng = new google.maps.LatLng(lat, lng);
				var marker = new google.maps.Marker({
					position: mLatLng,
					title: name
				});
				marker.setMap(map);
			});
		});
      }
    </script>
  </head>
  <body onload="initialize()">
    <div id="map_canvas" style="width:100%; height:100%"></div>
	<script type="text/javascript" src="js/jquery.min.js"></script>
  </body>
</html>
