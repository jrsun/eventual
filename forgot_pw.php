<!DOCTYPE HTML>
<?php
session_start();
include 'db.php';
include 'user_header.php';
?>
<html>
	<head>
		<title>
			Password Reset
		</title>
		<link rel="stylesheet" type="text/css" href="main.css" />
		<script type="text/javascript" src="js/jquery.min.js" />
		<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
		<script src='js/bootstrap.js'></script>
		<link rel="stylesheet" href="css/bootstrap.css" />
		<link rel="stylesheet" href="styles.css" />
		<script type="text/javascript">
		$(document).ready(function(){
			$('#pw_reset').submit(function(e){
				var email = $('#email').val();
				$.post('email_fetch.php', "email=" + email, function(data){
					alert(data);
				});
				return false;
			});
		});
		</script>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="span12">
					<h3>Password Reset</h3>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="span12">
					<form class="form-inline" id='pw_reset' method="POST">
						<input type="text" id="email" placeholder="E-mail" />
						<input type="submit" id="submit" class="btn"/>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>