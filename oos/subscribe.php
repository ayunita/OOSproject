<?php
	session_start();
	if($_SESSION['role'] != 's'){
		header("Location: restriction.html");
		exit();
	}	

?>
<html>
<script src="jquery-1.11.3.js" type="text/javascript"></script>
<script src="style.js" type="text/javascript"></script>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Subscription</title>
</head>
<?php
	include ("PHPconnectionDB.php");  
	include ("Subscribe.php");   
	//establish connection
	$conn=connect();
?>
<body>
	<h1>Subscription Page</h1>
	<button onclick="location.href = 'logout.php';">Logout</button>
	<button onclick="location.href = 'scientist.php';">Back</button>
	<br>
	<h2>View Sensors</h2>
	<p>All Sensors:</p>
	<div id="sensor_panel"  style="overflow:auto; height:200px;">
		<form action="" method="post">
			<fieldset>
			<?php
			displayAllSensors($conn);
			?>
			</fieldset>
		</form>
	</div>

	<p>All Subscribed Sensors:</p>
	<div id="sd_sensor_panel"  style="overflow:auto; height:150px;">
		<form action="" method="post">
			<fieldset>
				<?php
				displaySubscribedSensors($conn);
				?>
				
			</fieldset>
		</form>
	</div>

	<h2>Subscribe/Unsubscribe Sensors</h2>
	<p>Subscribe Sensors:</p>
	<div id="s_sensor_panel">
		<form action="" method="post">
			<fieldset>
				Enter Sensor ID: <input type="text" name="sub_sensorid">
				<input type="submit" name="subscribe_sensor" value="Subscribe">

			</fieldset>
		</form>
	</div>

	<p>Unsubscribe Sensors:</p>
	<div id="us_sensor_panel">
		<form action="" method="post">
			<fieldset>
				Enter Sensor ID: <input type="text" name="unsub_sensorid">
				<input type="submit" name="unsubscribe_sensor" value="Unsubscribe">

			</fieldset>
		</form>
	</div>

	<span style="color:green">
	<br /><br />

	<a href="http://consort.cs.ualberta.ca/~yunita/OOSproject/document/help.html#subscribe-module">Help</a>
	
	<?php 
	
		subscribe($conn);		
		unsubscribe($conn);		
		
	?>
	
	</span>
	
	
</body>
</html>


