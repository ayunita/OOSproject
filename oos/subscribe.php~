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
	//establish connection
	$conn=connect();
?>
<body>
	<h1>Subscription Page</h1>
	<button onclick="location.href = 'logout.php';">Logout</button>
	<br>
	<h2>View Sensors</h2>
	<p>All Sensors:</p>
	<div id="sensor_panel"  style="overflow:auto; height:200px;">
		<form action="" method="post">
			<fieldset>
				<?php
				$sql = "SELECT * FROM sensors";
				$stid = oci_parse($conn, $sql );
				oci_execute($stid);

				echo "<style>
					table, th, td {
					    border: 1px solid black;
					    border-collapse: collapse;}
					</style>";
				echo "<table><tr><th>Sensor ID</th><th>Location</th>";
				echo "<th>Sensor Type</th><th>Description</th></tr>";

				while (oci_fetch($stid)) {
    				echo "<tr><td>".oci_result($stid, 'SENSOR_ID') . "</td>";
  					echo "<td>".oci_result($stid, 'LOCATION') . "</td>";
					echo "<td>".oci_result($stid, 'SENSOR_TYPE')."</td>";
					echo "<td>".oci_result($stid, 'DESCRIPTION')."</td></tr>";
				}
				echo "</table>";

				oci_free_statement($stid);

				?>
			</fieldset>
		</form>
	</div>

	<p>All Subscribed Sensors:</p>
	<div id="sd_sensor_panel"  style="overflow:auto; height:200px;">
		<form action="" method="post">
			<fieldset>
				<?php

				$sql = "SELECT * FROM subscriptions WHERE PERSON_ID = ".$_SESSION['person_id'];
				$stid = oci_parse($conn, $sql );
				oci_execute($stid);
				
				echo "<style>
					table, th, td {
					    border: 1px solid black;
					    border-collapse: collapse;}
					</style>";
				echo "<table><tr><th>Sensor ID</th><th>Location</th>";
				echo "<th>Sensor Type</th><th>Description</th></tr>";
								
				$sensor_ids = array();
				$i = 0;
				while (oci_fetch($stid)) {
  					$sensor_ids[$i] = oci_result($stid, 'SENSOR_ID');
  					$i = $i + 1;
				}
				oci_free_statement($stid);
				
				for ($i2 = 0; $i2 < $i; $i2++){
					$sql = "select * from sensors where SENSOR_ID = ".$sensor_ids[$i2];
					$stid = oci_parse($conn, $sql);
					oci_execute($stid);
					while (oci_fetch($stid)) {
    					echo "<tr><td>".oci_result($stid, 'SENSOR_ID') . "</td>";
  						echo "<td>".oci_result($stid, 'LOCATION') . "</td>";
						echo "<td>".oci_result($stid, 'SENSOR_TYPE')."</td>";
						echo "<td>".oci_result($stid, 'DESCRIPTION')."</td></tr>";
				}
					oci_free_statement($stid);
				}

				echo "</table>";


				?>
				
			</fieldset>
		</form>
	</div>

	<h2>Subscribe/Unsubscribe Sensors</h2>
	<p>Subscribe Sensors:</p>
	<div id="s_sensor_panel">
		<form action="" method="post">
			<fieldset>
				

			</fieldset>
		</form>
	</div>

	<p>Unsubscribe Sensors:</p>
	<div id="us_sensor_panel">
		<form action="" method="post">
			<fieldset>
				

			</fieldset>
		</form>
	</div>

</body>
</html>
