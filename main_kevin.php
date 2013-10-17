<?php
session_start();
include 'db.php';
include 'user_header.php';
?>

<?php //LOGIN
$username = (isset($_POST['username']))? $_POST['username'] : null;
$password = (isset($_POST['password']))? $_POST['password'] : null;

if(isset($_POST['submit'])){
	if(($username == "") || ($password == "")){
		echo "<div style='color:red'>Please fill in all required fields!</div>";
	} else {
		$result = mysql_query("SELECT * FROM users WHERE USERNAME='".$username."'");
		$row = mysql_fetch_array($result);
		if (sha1($password) == $row["PASSWORD_HASH"]){
			echo "<div style='color:green'>Login succeeded! Welcome, $username.</div>";
		} else {
			echo "<div style='color:red'>Login failed!</div>";
		}
	}
}
?>

<?php //RESET
include 'db.php';
$username = (isset($_POST['username']))? $_POST['username'] : null;
$phone = (isset($_POST['phone']))? $_POST['phone'] : null;

if(isset($_POST['submit'])){
	if(($username == "") || ($phone == "")){
		echo "<div style='color:red'>Please fill in all required fields!</div>";
	} else {
		$result = mysql_query("SELECT * FROM users WHERE USERNAME='".$username."'");
		$row = mysql_fetch_array($result);
		if ($result){
			if ($phone === $row["PHONE"]){
				$new_password = generateRandomString();
				mysql_query("UPDATE users SET PASSWORD_HASH = '".sha1($new_password)."' WHERE USERNAME='".$username."'");
				echo "<div style='color:green'>Your new password is ".$new_password."</div>";
			} else {
				echo "<div style='color:red'>Password reset failed!</div>";
			}	
		}
	}
 
}

function generateRandomString() {    
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
}

?>

<?php //REGISTER
include 'db.php';
$username = (isset($_POST['username']))? $_POST['username'] : null;
$password = (isset($_POST['password']))? $_POST['password'] : null;
$phone = (isset($_POST['phone']))? $_POST['phone'] : null;

if(isset($_POST['submit'])){
	if(($username == "") || ($password == "")){
		echo "<div style='color:red'>Please fill in all required fields!</div>";
	} else {
		$user_exists = mysql_query("SELECT USERNAME FROM users WHERE USERNAME='".$username."'");
		$num_rows = mysql_num_rows($user_exists);
		if ($num_rows > 0){
			echo "<div style='color:red'>Username already exists!</div>";
		} else {
			mysql_query("INSERT INTO users VALUES ('".$username."', 
			'".sha1($password)."', '".$phone."')");
			echo "<div style='color:green'>Welcome, $username!</div>";
		}
	}
}
?>




<?php

if (isset($_GET['eventid'])){
	$eventid = $_GET['eventid'];
?>
	<script type='text/javascript'>
		$(document).ready(function(){
			$('#join_success').hide();
			$('#unjoin_success').hide();
			
			function join_toggle(){
				if ($(this).attr('id') == 'join'){
					$(this).attr('disabled','disabled');
					setTimeout(function() {
						$('#unjoin').removeAttr('disabled');
					}, 1000);
					$.post('join.php', {eventid: <?php echo $eventid; ?>});
					$(this).attr('id','unjoin');
					$(this).html('Unjoin!');
					var attendees = parseInt($('#attendees').html());
					$('#attendees').html(attendees + 1);
					$('#join_success').show('fast').fadeOut(1000);
					return false;
				}
				if ($(this).attr('id') == 'unjoin'){
					$(this).attr('disabled','disabled');
					setTimeout(function() {
						$('#join').removeAttr('disabled');
					}, 1000);
					$.post('unjoin.php', {eventid: <?php echo $eventid; ?>});
					$(this).attr('id','join');
					$(this).html('Join!');
					var attendees = parseInt($('#attendees').html());
					$('#attendees').html(attendees - 1);
					$('#unjoin_success').show('fast').fadeOut(1000);
					return false;
				}
			}
			$("button").click(join_toggle);
		});
	</script>
<?php
	$query1 = mysql_query("SELECT * FROM events WHERE eventid = '$eventid'");
	$row = mysql_fetch_assoc($query1); //event info
	
	$attendees = mysql_query("SELECT userid FROM events_users WHERE eventid = '$eventid'");
	echo("<h3>".$row['eventname']."</h3><span id='attendees'>".mysql_num_rows($attendees)."</span> people attending<hr><br>");
	echo("<div style='float:left;width:300px;padding:10px'>");
	echo("Location: ".$row['location']."<br>");
	echo("Date and Time: ".$row['datetime']."<br>");
	echo("Category: ".$row['cat']."<br><br><br>");
	echo($row['des']."<br><br>");
	
	if (isset($_SESSION['loggedin']) && ($_SESSION['loggedin'] == True)){
		$userid = $_SESSION['userid'];
		$query = try_query("SELECT * FROM events_users WHERE userid = $userid AND eventid = $eventid");
		$signup_exists = mysql_num_rows($query);
		if ($signup_exists > 0): ?>
			<button id="unjoin">Unjoin!</button>
		<?php else: ?>
			<button id="join">Join!</button>	
		<?php endif;
		echo '<div id="unjoin_success" style="color:orange">You have left this event!</div>';
		echo '<div id="join_success" style="color:green">You have joined this event!</div>';
	} else {
		echo("<div style='color:red'>You must <a href='login.php'>sign in</a> to join events!</div>");
	}
	echo("</div>"); #close info div
	echo("<div style='float:right;width:300px;padding:10px'><img src='http://maps.googleapis.com/maps/api/staticmap?center=".$row['lat'].",".$row['lng']."&zoom=14&size=300x300&maptype=roadmap \
&markers=color:blue%7Clabel:%7C".$row['lat'].",".$row['lng']."&sensor=false'></div>");
	exit();
}
$result = mysql_query("SELECT * FROM events WHERE datetime > CURRENT_TIMESTAMP ORDER BY datetime ASC");
if (!$result){
	die(mysql_error());
}
?>

<html>
	<head>
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<title>Event Listing</title>
		<link rel="stylesheet" type="text/css" href="styles.css">
		<script type="text/javascript">
		
		tablearr = new Array();
		
		<?php while($row = mysql_fetch_assoc($result)){
				echo "elem = new Array();";
				foreach ($row as $value){
						echo "elem.push('".$value."');";
					}
				echo "tablearr.push(elem);";
			
			}
		?>

		window.onload=function() {
			makeTable("All", "ALL EVENTS");
		}

		function makeTable(value, ename) {
		
			while (document.getElementById('eventstable').hasChildNodes()) {
				document.getElementById('eventstable').removeChild(document.getElementById('eventstable').lastChild);
			}

			row=new Array();
			cell=new Array();
			hcont= new Array();
			hcell= new Array();


			tab=document.createElement('table');
			tab.setAttribute('id','newtable');

			tbo=document.createElement('tbody');
			
			thd = document.createElement('thead');
			hr = document.createElement('tr');
		
			hcont[0] = document.createTextNode('Event');
			hcont[1] = document.createTextNode('Location');
			hcont[2] = document.createTextNode('Time');
			hcont[3] = document.createTextNode('Category');
			for(i=0;i<4;i++){
				hcell[i] = document.createElement('th');
				hcell[i].appendChild(hcont[i]);
				hr.appendChild(hcell[i]);
			}
			
			thd.appendChild(hr);
			tab.appendChild(thd);

			for(c=0;c<tablearr.length;c++){
				if((value =="All" || value == tablearr[c][4]) && (ename == "ALL EVENTS" || tablearr[c][1].toLowerCase().indexOf(ename.toLowerCase())!= -1)){
					row[c]=document.createElement('tr');
					
					for(k=0;k<4;k++) {
						cell[k]=document.createElement('td');
						if(k==0){
							cont=document.createElement('a');
							cont.setAttribute('href', 'main.php?eventid='+tablearr[c][0]);
							cont1=document.createTextNode(tablearr[c][1]);
							cont.appendChild(cont1);
						}else{
						cont=document.createTextNode(tablearr[c][k+1]);
						}
						cell[k].appendChild(cont);
						row[c].appendChild(cell[k]);
					}
					tbo.appendChild(row[c]);
				}
			}
			tab.appendChild(tbo);
			document.getElementById('eventstable').appendChild(tab);
		}
		
		$(document).ready(function(){
			$("#search").keyup(function(){
				makeTable("All", search.value);
			});
			
			$(".loginbutton").click(function(){
				$("#login").css("display","block");
				$("#fade").css("display","block");
				$("#reset").css("display","none");
				$("#register").css("display","none");
			});
			
			$(".resetbutton").click(function(){
				$("#reset").css("display","block");
				$("#fade").css("display","block");
				$("#login").css("display","none");
				$("#register").css("display","none");
			});
			
			$(".registerbutton").click(function(){
				$("#register").css("display","block");
				$("#fade").css("display","block");
				$("#login").css("display","none");
				$("#resetbutton").css("display","none");
			});
			
			$("#fade").click(function(){
				$("#login").css("display","none");
				$("#reset").css("display","none");
				$("#register").css("display","none");
				$("#fade").css("display","none");
			});
			
		});
		
		
		</script>
	
	</head>
	
	
	<body>
		<div id="login">
			<form action="<?php $_SERVER["PHP_SELF"] ?>" method="POST">
			<h2> Login </h2><hr><br>
			Username: <input type="text" name="username" value="<?php echo $username;?>"/><br>
			Password: <input type="password" name="password" /><br>
			<input type="submit" />
			<p class="registerbutton")>New User?</p>
			<p class="resetbutton">Forgot Password?</p>
			<input type="hidden" name="submit" /> <!--allows checking submission-->
			</form>
		</div>
		<div id="reset">
			<form action="<?php $_SERVER["PHP_SELF"] ?>" method="POST">
				<h2> Reset Password </h2><hr><br>
				Username: <input type="text" name="username" value="<?php echo $username;?>"/><br>
				Phone: <input type="text" name="phone" /><br>
				<input type="submit" />
				<p class="loginbutton">Login</p>
				<input type="hidden" name="submit" /> <!--allows checking submission-->
			</form>
		</div>
		<div id="register">
			<form action="<?php $_SERVER["PHP_SELF"] ?>" method="POST">
			<h2>Register</h2><hr><br>
			Username: <input type="text" name="username" value="<?php echo $username;?>"/><br>
			Password: <input type="password" name="password" /><br>
			Phone (Optional): <input type="text" name="phone" value="<?php echo $phone;?>"/><br>
			<input type="submit" />
			<p class="loginbutton">Existing User?</p>
			<input type="hidden" name="submit" /> <!--allows checking submission-->
			</form>
		</div>
		<div id="fade"> </div>
		<div id="mainpage">
			<br>
			Filter by Category:
			<form>
				<select onchange = "makeTable(filter_cat.value, 'ALL EVENTS')" name="filter_cat">
				<option value="All">All Categories</option>
				<option value="Food">Food</option>
				<option value="Entertainment">Entertainment</option>
				<option value="Sports">Sports</option>
				<option value="Study Breaks">Study Breaks</option>
				<option value="Studying">Studying</option>
				<option value="Misc">Miscellaneous</option>
				</select>
			</form>
			Search for Event:
			<input id = "search"></textarea>
			<div id="eventstable"> </div>
		</div>
		
		
	</body>
</html>
