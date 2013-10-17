<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/bootstrap-responsive.css">
<link rel="stylesheet" href="user_header.css">
<script type='text/javascript' src='http://code.jquery.com/jquery-latest.js'></script>
<link rel="stylesheet" type="text/css" href="main.css">

<script type='text/javascript'>
$(document).ready(function(){
	$('#home').hover(
	function() {$('#home').attr('src','img/home_click.png');}, 
	function() {$('#home').attr('src','img/home.png');}
	);

	$(".loginbutton").click(function(){
		$("#login").css("display","block");
		$("#gray_shadow").css("display","block");
		$("#register").css("display","none");
	});
	
	$("#login_form").submit(function(){
		var $inputs = $("#login_form :input");
		var values = {};
		$inputs.each(function(){
			values[this.name] = $(this).val();
		});
		if ((values['username'] == '') || (values['password'] == '')){
			$('#login_error').html('Please fill in missing fields!').show('fast').fadeOut(1000);
		} else {
			$.post("login.php", {username: values['username'], password: values['password'], remember: $('#remember').is(':checked')}, function(data){
				if (data == 'success'){
					location.reload();
				} else if (data == 'fail'){
					$('#login_error').html('Invalid username or password!').show('fast').fadeOut(1000);
				}
			});
		}
		return false;
	});
	
	$("#register_form").submit(function(){
		var $inputs = $("#register_form :input");
		var values = {};
		$inputs.each(function(){
			values[this.name] = $(this).val();
		});
		console.log(values);
		if ((values['username'] == "") || (values['password'] == "") || (values['email'] == "")){
			$('#reg_error').html('Please fill in missing fields!').show('fast').fadeOut(1000);
		} else {
			$.post("register.php", {username: values['username'], password: values['password'], email: values['email']}, function(data){
				if (data == 'exists'){
					$('#reg_error').html('Username or E-mail already taken!').show('fast').fadeOut(1000);
				} else {
					$.post('login.php', {username: values['username'], password: values['password']});
					location.reload();
				}
			});
		};
		return false;
	});				
	
	$(".registerbutton").click(function(){
		$("#register").css("display","block");
		$("#gray_shadow").css("display","block");
		$("#login").css("display","none");;
	});
	
	$("#gray_shadow").click(function(){
		$("#login").css("display","none");
		$("#register").css("display","none");
		$("#gray_shadow").css("display","none");
	});
});
</script>

<?php //Register
$username = (isset($_POST['username']))? $_POST['username'] : null;
$password = (isset($_POST['password']))? $_POST['password'] : null;
$email = (isset($_POST['email']))? $_POST['email'] : null;

?>
<?php //LOGIN


$username = (isset($_POST['username']))? $_POST['username'] : null;
$password = (isset($_POST['password']))? $_POST['password'] : null;
?>

<?php
	echo "<div class = 'navbar navbar-fixed-top'> <div class = 'navbar-inner'> <div class = 'container'>
	<ul class = 'nav'>";
	echo "<li class = 'active'><a href='main.php'><img border='0' 
	src='img/home.png' alt='Home' width='32' height='32' id='home'></a></li>";
	echo "<div id = 'rightnav'>";
	if (isset($_SESSION['loggedin']) && ($_SESSION['loggedin'] == True)){
		echo "<li class = 'nav pull-right itemnav'><a href='logout.php'>Logout</a></li>
		<li class = 'nav pull-right itemnav'>Welcome, ".$_SESSION['username']."! &nbsp;</li>";
	} else {
		echo "<li class = 'nav pull-right itemnav li-right'><p class='loginbutton'>Login</p></li>";
		echo "<li class = 'nav pull-right itemnav li-right'><p class='registerbutton'>Register &nbsp;&nbsp;&nbsp;</p></li>";
	}
	echo "</div>";
	echo "</ul>";
	echo "</div></div></div>";
?>

	<div id="gray_shadow"> </div>
	
	<div id="login">
		<form action="login.php" method="POST" name="login_form" id="login_form">
			<h2> Login </h2><hr><br>
			Username: <input type="text" name="username" value="<?php echo $username;?>" />
			Password: <input type="password" name="password" />
			<input type="submit" />
			<span id="newuser" class="registerbutton">New User?</span><br>
			Remember me: <input type="checkbox" name="remember" value="remember" id="remember"/>
			<div id="login_error" style="color:red"></div>
		</form>
	</div>
	<div id="register"> 
		<form action="register.php" method="POST" id="register_form">
			<h2>Register</h2><hr><br>
			Username: <input type="text" name="username" value="<?php echo $username;?>"/><br>
			Password: <input type="password" name="password" /><br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Email: <input type="text" name="email" value="<?php echo $email;?>"/><br>
			<input type="submit" />
			<span class="loginbutton">Existing User?</span>
			<div id="reg_error" style="color:red"></div>
		</form>
	</div>