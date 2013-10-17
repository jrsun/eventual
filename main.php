<!DOCTYPE HTML>
<?php
session_start();
include 'db.php';
include 'user_header.php';
?>


<?php

if (isset($_GET['eventid'])){
	$eventid = $_GET['eventid'];
?>

	<script type='text/javascript' src='js/jquery.min.js'></script>
	<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
	<script src='js/bootstrap.js'></script>
	<link rel="stylesheet" href="css/bootstrap.css" />
	<link rel="stylesheet" href="styles.css" />
	<script type='text/javascript'>
		$(document).ready(function(){
			$('#join_success').hide();
			$('#unjoin_success').hide();
			
			function join_toggle(){
				var attendees = parseInt($('#attendees').html());
				if ($(this).attr('id') == 'join'){
					$(this).attr('disabled','disabled');
					setTimeout(function() {
						$('#unjoin').removeAttr('disabled');
						$('#unjoin').attr('class','btn btn-warning');
						$('#unjoin').html('Unjoin!');
					}, 1000);
					
					$(this).attr('id','unjoin');
					$.post('join.php', {eventid: <?php echo $eventid; ?>});
					$('#attendees').html(attendees + 1);
					$('#join_success').show('fast').fadeOut(1000);
					return false;
				}
				if ($(this).attr('id') == 'unjoin'){
					if (attendees > 1){
						$(this).attr('disabled','disabled');
						setTimeout(function() {
							$('#join').removeAttr('disabled');
							$('#join').attr('class', 'btn btn-success');
							$('#join').html('Join!');
						}, 1000);
						
						$(this).attr('id','join');
						$.post('unjoin.php', {eventid: <?php echo $eventid; ?>});
						$('#attendees').html(attendees - 1);
						$('#unjoin_success').show('fast').fadeOut(1000);
						return false;
					} else {
						$(this).attr('disabled','disabled');
						setTimeout(function() {
							$('#delete').removeAttr('disabled');
							$('#delete').attr('class', 'btn btn-danger');
							$('#delete').attr('onClick', 'window.location.href = "main.php"');
							$('#delete').html('Delete Event?');
						}, 1000);
						$(this).attr('id', 'delete');
					}
				}
				if ($(this).attr('id') == 'delete'){
					$.post('event_del.php', {eventid: <?php echo $eventid; ?>});
				}
			}
			$("button").click(join_toggle);
		});
	</script>
<?php
	$query1 = mysql_query("SELECT * FROM events WHERE eventid = '$eventid'");
	$row = mysql_fetch_assoc($query1); //event info
	
	$attendees = mysql_query("SELECT userid FROM events_users WHERE eventid = '$eventid'");
	echo("<body>");
	echo("<div class='container'>");
		echo("<div class='row'>");
			echo("<div class='span6'>");
				echo("<h3>".$row['eventname']."</h3>");
			echo("</div>");
		echo("</div>");
		echo("<div class='row'>");
			echo("<div class='span12'>");
				echo("<span id='attendees'>".mysql_num_rows($attendees)."</span> attending<hr><br>");
			echo("</div>");
		echo("</div>");
		echo("<div class='row'>");
			echo("<div class='span8'>");
				echo("Location: ".$row['location']."<br>");
				echo("Date and Time: ".$row['datetime']."<br>");
				echo("Category: ".$row['cat']."<br><br><br>");
				echo($row['des']."<br><br>");
	
	if (isset($_SESSION['loggedin']) && ($_SESSION['loggedin'] == True)){
		$userid = $_SESSION['userid'];
		$query = mysql_query("SELECT * FROM events_users WHERE userid = $userid AND eventid = $eventid");
		if (!$query){
			die(mysql_error());
		}
		$signup_exists = mysql_num_rows($query);
		if ($signup_exists > 0): ?>
			<button class="btn btn-warning" id="unjoin">Unjoin!</button>
		<?php else: ?>
			<button class="btn btn-success" id="join">Join!</button>
		<?php endif;
		echo '<div id="unjoin_success" style="color:orange">You have left this event!</div>';
		echo '<div id="join_success" style="color:green">You have joined this event!</div>';
	} else {
		echo("<div style='color:red'>You must <span class='loginbutton'>sign in</span> to join events!</div>");
	}
	
			echo("</div>"); #span8
			echo("<div class='span4'>");
	echo("<a href='map.php'><img src='http://maps.googleapis.com/maps/api/staticmap?center=".$row['lat'].",".$row['lng']."&zoom=14&size=300x300&maptype=roadmap \
&markers=color:red%7Clabel:%7C".$row['lat'].",".$row['lng']."&sensor=false'></a>");
			echo("</div>");
		echo("</div>"); #row
	echo("</div>"); //container
	echo("</body>");
	exit();
}?>

<?php
$result = mysql_query("SELECT * FROM events ORDER BY datetime ASC");
if (!$result){
	die(mysql_error());
}
?>

<html>
	<head>
		<title>Event Listing</title>
		<link rel="stylesheet" type="text/css" href="styles.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<script type="text/javascript">
		
		tablearr = new Array();
		
		<?php while($row = mysql_fetch_assoc($result)){
				echo "elem = new Array();";
				foreach ($row as $value){
						echo "elem.push('".mysql_real_escape_string($value)."');";
					}
				echo "tablearr.push(elem);";
			
			}
		?>

		window.onload=function() {
			makeTable("All", "ALL EVENTS", false);
		}

		function makeTable(value, ename, pastEvent) {
		
			while (document.getElementById('eventstable').hasChildNodes()) {
				document.getElementById('eventstable').removeChild(document.getElementById('eventstable').lastChild);
			}

			row=new Array();
			item=new Array();
			eventsout = new Array();
			eventsin = new Array();
			events = new Array();
			hcont= new Array();
			hitem= new Array();

			navout = document.createElement('div');
			navout.setAttribute('class', 'navbar');
			
			navin = document.createElement('div');
			navin.setAttribute('class', 'navbar-inner');
			
			navout.appendChild(navin);
					
			hd=document.createElement('div');
			hd.setAttribute('class','container');
			
			navin.appendChild(hd);
			
			hr = document.createElement('ul');
			hr.setAttribute('class','nav');
		
			hcont[0] = document.createTextNode('Event\t');
			hcont[1] = document.createTextNode('Location\t');
			hcont[2] = document.createTextNode('Time\t');
			hcont[3] = document.createTextNode('Category\t');
			for(i=0;i<4;i++){
				hitem[i] = document.createElement('li');
				hitem[i].appendChild(hcont[i]);
				hitem[i].setAttribute('class', 'eventitem');
				hr.appendChild(hitem[i]);
			}
			hd.appendChild(hr);
			
			document.getElementById('eventstable').appendChild(navout);

			time = Date.now();
			for(c=0;c<tablearr.length;c++){
				eventTime = new Date(tablearr[c][3].replace(/-/g,"/"));
				if((value =="All" || value == tablearr[c][4]) && (ename == "ALL EVENTS" || tablearr[c][1].toLowerCase().indexOf(ename.toLowerCase())!= -1) && (pastEvent == true || eventTime >= time)){
					eventsout[c] = document.createElement('div');
					eventsout[c].setAttribute('class', 'navbar');
					eventsin[c] = document.createElement('div');
					eventsin[c].setAttribute('class', 'navbar-inner');
					eventsout[c].appendChild(eventsin[c]);
					events[c] = document.createElement('div');
					events[c].setAttribute('class', 'container');
					eventsin[c].appendChild(events[c]);
					row[c]=document.createElement('ul');
					row[c].setAttribute('class','nav');
					
					for(k=0;k<4;k++) {
						item[k]=document.createElement('li');
						if(k==0){
						item[k].setAttribute('class', 'eventname');
							cont=document.createElement('a');
							cont.setAttribute('href', 'main.php?eventid='+tablearr[c][0]);
							cont1=document.createTextNode(tablearr[c][1]);
							cont.appendChild(cont1);
						}else{
						cont=document.createTextNode(tablearr[c][k+1]);
						item[k].setAttribute('class', 'eventitem');
						}
						item[k].appendChild(cont);
						row[c].appendChild(item[k]);
					}
					events[c].appendChild(row[c]);
					document.getElementById('eventstable').appendChild(eventsout[c]);
				}
			}
		}
		
		$(document).ready(function(){
			$("#search").keyup(function(){
				makeTable($("#filter_cat option:selected").val(), search.value, $("#pastcheckbox").is(":checked"));
			});
			
			$("#pastcheckbox").change(function(){
				makeTable($("#filter_cat option:selected").val(), search.value, $("#pastcheckbox").is(":checked"));
			});
			
			$("#filter_cat").change(function(){
				makeTable($("#filter_cat option:selected").val(), search.value, $("#pastcheckbox").is(":checked"));
			});
			
			$("#scroll_table").css("overflow", "auto").css("width", "1000px").css("float", "none").css("height", ($(window).height()-300)+"px");
			$(window).resize(function(){
				$("#scroll_table").css("height", ($(window).height()-300)+"px");
			});
			
			$("#create").click(function(){
			<?php if (!(isset($_SESSION['loggedin']) && ($_SESSION['loggedin'] == True))):?>
				$("#login").css("display","block");
				$("#gray_shadow").css("display","block");
				$("#register").css("display","none");
			<?php else: ?>
				window.location.href='create.php';
			<?php endif; ?>
			});
		});
		

		</script>
		<link rel="stylesheet" type="text/css" href="main.css">
		<style type='text/css'>
		
			#gray_shadow
			{
				display: none;
				opacity: 0.7;
				background-color: black;
				position: fixed;
				top:0;
				left:0;
				width: 100%;
				height: 100%;
				z-index:2;
				
			}
			#login_error {display:none}
			#reg_error {display:none}
		</style>
	</head>
	<body>
		<div id="mainpage">
			<div class="container"><div class = "row"><div class="span6">
			<form class = 'well'>
				<input id = "search" type = "text" placeholder = "Search for an event"><br>
				<span id = "filterspan">Filter by Category:</span> <select id="filter_cat" name="filter_cat">
					<option value="All">All Categories</option>
					<option value="Food">Food</option>
					<option value="Entertainment">Entertainment</option>
					<option value="Sports">Sports</option>
					<option value="Study Breaks">Study Breaks</option>
					<option value="Studying">Studying</option>
					<option value="Misc">Miscellaneous</option>
				</select>
				<label id = 'pastlabel'>Show past events: <input type="checkbox" id = 'pastcheckbox'/></label>
			</form>
			</div>
			<div class="span6">
			<button style="height:157px;font-size:50px;" class="btn btn-block btn-large btn-primary" 
			id="create">Create!</button>
			</div>
			</div></div>
			<div class="container" id="scroll_table"><div class="row"><div class="span12">
			<div id="eventstable"> </div>
		</div></div></div></div>
	</body>
</html>
