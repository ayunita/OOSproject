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
	<div id="sd_sensor_panel"  style="overflow:auto; height:150px;">
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
	
	
	<?php 
		if (isset($_POST["subscribe_sensor"])){
			$row_num = 0;
			
			if(strval($_POST['sub_sensorid']) != "") {
				$sub_sensorid = intval($_POST['sub_sensorid']);
				$sql = "select * from sensors where SENSOR_ID = ".$sub_sensorid;
				$stid = oci_parse($conn, $sql );
				$res = oci_execute($stid);
			
				while (oci_fetch($stid)) {
    				$row_num = $row_num +1;
				}
				oci_free_statement($stid);
			}
			
			if ($row_num == 0){
				echo "PLEASE ENTER VALID SENSOR ID";
			}
			
			$not_exist = true;	
			
			// check existence of the subscription
			$sub_sensorid = intval($_POST['sub_sensorid']);
			$sql = "select * from subscriptions where SENSOR_ID = ".$sub_sensorid." and PERSON_ID = ".$_SESSION["person_id"];
			$stid = oci_parse($conn, $sql );
			$res = oci_execute($stid);
			while (oci_fetch($stid)) {
    			$not_exist = false;
			}
			oci_free_statement($stid);
			
			if(!$not_exist) {
				echo "SENSOR SUBSCRIPTION ALREADY EXISTS";
			}
			
			if(strval($_POST['sub_sensorid'])  != "" && $row_num != 0 && $not_exist) {
				$sub_sensorid = intval($_POST['sub_sensorid']);
				$sql = "Insert into subscriptions values(".$sub_sensorid.",".$_SESSION["person_id"].")";
				$stid = oci_parse($conn, $sql );
				$res = oci_execute($stid);
				
				header("Refresh:0");
			}
		}
		
		
		
		
		if (isset($_POST["unsubscribe_sensor"])){
			$row_num1 = 0;
			
			if(strval($_POST['unsub_sensorid']) != "") {
				$unsub_sensorid = intval($_POST['unsub_sensorid']);
				$sql = "select * from sensors where SENSOR_ID = ".$unsub_sensorid;
				$stid = oci_parse($conn, $sql );
				$res = oci_execute($stid);
			
				while (oci_fetch($stid)) {
    				$row_num1 = $row_num1 +1;
				}
				oci_free_statement($stid);
			}
			
			
			if ($row_num1 == 0){
				echo "THE SENOSR ID IS INVALID, PLEASE ENTER A VALID ID";
			}		
			
			$exist = false;	
					
			// check existence of the subscription
			if(strval($_POST['unsub_sensorid']) != "") {
				$sub_sensorid = intval($_POST['unsub_sensorid']);
				$sql = "select * from subscriptions where SENSOR_ID = ".$unsub_sensorid." and PERSON_ID = ".$_SESSION["person_id"];
				$stid = oci_parse($conn, $sql );
				$res = oci_execute($stid);
				while (oci_fetch($stid)) {
    				$exist = true;
				}
				oci_free_statement($stid);
			}
			
			
			if($row_num1 != 0 && !$exist) {
				echo "SENSOR SUBSCRIPTION DOES NOT EXIST";
			}
			
			if(strval($_POST['unsub_sensorid'])  != "" && $row_num1 != 0 && $exist) {
				$unsub_sensorid = intval($_POST['unsub_sensorid']);
				$sql = "delete from subscriptions where SENSOR_ID = ".$unsub_sensorid." and "."PERSON_ID = ".$_SESSION["person_id"] ;
				$stid = oci_parse($conn, $sql );
				$res = oci_execute($stid);
				
				header("Refresh:0");
			}
		}
		
	?>
	
	</span>
	
	
</body>
</html>