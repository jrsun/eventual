<!DOCTYPE HTML>
<?php
session_start();

/* if (!(isset($_SESSION['loggedin']) && ($_SESSION['loggedin'] == True))){
	header('Location: login.php');
} */
include 'db.php';
include 'user_header.php';
?>

<html>
	<head>
		<title>New Event</title>
		<meta charset="utf-8" />
		<script type='text/javascript' src='js/jquery.min.js'></script>
		<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
		<script src='js/bootstrap.js'></script>
		<link rel="stylesheet" href="css/bootstrap.css" />
		<link rel="stylesheet" href="styles.css" />
		<script type="text/javascript">
		/* $(function() {
		$( "#datepicker" ).datepicker();
		}); */
		</script> 
		<script type="text/javascript">
		
		$(document).ready(function(){
			
			$('#event_form').submit(function(e) {
				$('#create').attr('disabled','disabled')
				setTimeout(function(){
					$('#create').removeAttr('disabled');
				}, 1000);
				var form = this;
				e.preventDefault();
				var encode = encodeURIComponent($('#location').val());
				var url = "http://maps.googleapis.com/maps/api/geocode/json?address=" + encode + "&sensor=false";
				var $inputs = $('form :input');
				var values = {};
				$inputs.each(function() {
					values[this.name] = $(this).val();
				});
				console.log(values);
				required = [values['eventname'], values['location'], values['date'], values['hour'], values['description']];
				blank_fields = false;
				$.each(required, function(i, l) {
					if (l == ''){
						blank_fields = true;
					};
				});
				$.each($inputs, function() {
					if (this.value == ''){
						$(this).effect('highlight', {color: '#FFAAAA'}, 1000);
					};
				});
				if (blank_fields){
					return false;
				}
				
				console.log(values);
				$.ajax({
					url: url,
					success: function(result){
						try{
						var lat = result['results'][0]['geometry']['location']['lat'];
						var lng = result['results'][0]['geometry']['location']['lng'];
						$('#lat').val(lat);
						$('#lng').val(lng);
						form.submit();
						} catch(e){
							$('#location_error').show('fast').fadeOut(1000);
							$('#location').effect('highlight', {color: '#FFAAAA'}, 1000);
							return false;
						}
					},
					dataType: 'json'
				});
			});
			$('#creator').css('height',($(window).height()-100) + "px");
			$(window).resize(function(){
				$("#creator").css("height", ($(window).height()-100)+"px");
			});
		});
		</script>
	<style>
		#location_error {display:none}
		DIV.ui-datepicker {
			background: inherit !important;
		}
		
		.span12 h1{color:#FE6E4C; font-weight: bold; padding: 5px;}
		h3 {margin: 10px 0 10px 0;}
		.input-time {width: 55px;}
		.ampm {width: 100px;}
		#creator{overflow:auto;float:none}
	</style>
	</head>
	<body>
	
	<div class="container" id="creator">
	
		<h2> Create an Event </h2><hr><br>
		<form class="form-horizontal" action="event_add.php" method="POST" id='event_form'>
		<div class="control-group">
			<label class="control-label" for="eventname">Event Name</label>
			<div class="controls">
				<input type="text" id="eventname" name="eventname" value="" placeholder="Event Name"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="location">Location</label>
			<div class="controls">
				<input type="text" name="location" id="location" value="" placeholder="Location"/>
				<div style="color:red" id="location_error">Location not found!</div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="datepicker">Date</label>
			<div class="controls">
				<input type="text" name="date" id="datepicker" placeholder="Date (MM/DD/YY)"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="hour">Time</label>
			<div class="controls">
				<input class="input-time" type="text" name="hour" value="" size = "1" maxlength="2" placeholder="Hour"/>
			:
				<input class="input-time" type="text" name="minutes" value="" size="1" maxlength="2" placeholder="Minutes"/>
				<select class="ampm" name="ampm">
				<option value="AM">AM</option>
				<option value="PM">PM</option>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="cat">Category</label>
			<div class="controls">
			<select name="cat">
				<option value="Food">Food</option>
				<option value="Entertainment">Entertainment</option>
				<option value="Sports">Sports</option>
				<option value="Study Breaks">Study Breaks</option>
				<option value="Studying">Studying</option>
				<option value="Misc">Misc</option>
			</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="description">Description</label>
			<div class="controls">
				<textarea name="description"  placeholder="Description" wrap=virtual></textarea>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<input type="submit" value="Create" id="create" class="btn btn-primary btn-large"/>
			</div>
		</div>
			<input type="hidden" value="" name="lat" id="lat" />
			<input type="hidden" value="" name="lng" id="lng" />
			<a href="main.php">Return to Main</a>
		</form>
	</div>
	</body>
</html>