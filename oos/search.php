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
<title>Search Records</title>
</head>
<?php
	include ("PHPconnectionDB.php");        
	//establish connection
	$conn=connect();
?>

<body>
	<h1>Search Page</h1>
	<button onclick="location.href = 'logout.php';">Logout</button>
	<br>
	<p>Search Conditions:</p>
	<div id="search_panel">
		<form action="" method="post">
			<fieldset>
				Key Words: <input type="text" name="description"> <br />
				<br /> Sensor Type: <select name="type" value="">Sensor Type</option>
				<?php
					echo "<option value=>All Sensor Types</option>";
					echo "<option value=a>Audio</option>"; 
					echo "<option value=i>Image</option>"; 
					echo "<option value=s>Scalar</option>"; 
				?>
				</select><br />
		
				<br /> Sensor Location: <input type="text" name="location"> <br />
				<br /> Date Range: <br>From: <input type="text" name="from">
				To: <input type="text" name="to"> <br />
				<br>
				<button type="reset">Reset</button>
				<input type="submit" name="submit_search" value="Submit">
			</fieldset>
		</form>
	</div>
	
	
	<?php 
		if (isset($_POST["submit_search"])){	
		
			// select sensors that satify ID, type, location and description
			$sql = "SELECT * FROM subscriptions NATURAL JOIN sensors WHERE PERSON_ID = ".$_SESSION['person_id'];
			
			if(strval($_POST['type']) != "") {
				$sql = $sql." AND SENSOR_TYPE = '".$_POST['type']."'";
			}
			if(strval($_POST['location']) != "") {
				$sql = $sql." AND LOCATION = '".$_POST['location']."'";
			}			
			if(strval($_POST['description']) != "") {
				$sql = $sql." AND description like '%".$_POST['description']."%'";
			}
			
			//echo $sql."<br>";
			
			$stid = oci_parse($conn, $sql );
			oci_execute($stid);
			
			$sensor_ids = (array)null;
			$i = 0;
			while (oci_fetch($stid)) {
  				$sensor_ids[$i] = oci_result($stid, 'SENSOR_ID');
  				$i = $i + 1;
			}
			oci_free_statement($stid);

			//echo $i."<br>";

			if ($i > 0 ){
				$lists = array();
				
				
				for ($i2 = 0; $i2 < $i; $i2++){
					echo "<br />Sensors that satisfy the conditions:<br>Sensor ID: ".$sensor_ids[$i2]."<br />";
					$search_string_image = "select thumbnail, recoreded_data from images where SENSOR_ID = ".$sensor_ids[$i2];
					$search_string_audio = "select recorded_data from audio_recordings where SENSOR_ID = ".$sensor_ids[$i2];
					$search_string_scalar = "select * from scalar_data where SENSOR_ID = ".$sensor_ids[$i2];
			
					if(strval($_POST['from']) != "") {
						$search_string_image = $search_string_image." AND date_created >=  TO_DATE('".$_POST['from']."','DD-MM-YYYY')";
						$search_string_audio = $search_string_audio." AND date_created >=  TO_DATE('".$_POST['from']."','DD-MM-YYYY')";
						$search_string_scalar = $search_string_scalar." AND date_created >=  TO_DATE('".$_POST['from']."','DD-MM-YYYY')";
					}
					if(strval($_POST['to']) != "") {
						$search_string_image = $search_string_image." AND date_created <=  TO_DATE('".$_POST['to']."','DD-MM-YYYY')";
						$search_string_audio = $search_string_audio." AND date_created <=  TO_DATE('".$_POST['to']."','DD-MM-YYYY')";
						$search_string_scalar = $search_string_scalar." AND date_created <=  TO_DATE('".$_POST['to']."','DD-MM-YYYY')";
					}
						//echo "<br>".$search_string_image."<br>";
						//echo "<br>".$search_string_audio."<br>";
						//echo "<br>".$search_string_scalar."<br>";
					

						$stid = oci_parse($conn, $search_string_scalar);
						oci_execute($stid);
						
						//scalar
						echo "Scalar Data:";
						
						$i3 = 0;
						while (oci_fetch($stid)) {
							echo "<br>".oci_result($stid, 'ID'),",", oci_result($stid, 'SENSOR_ID'),",",oci_result($stid, 'DATE_CREATED'),",",oci_result($stid, 'VALUE');
							$list = array(oci_result($stid, 'ID'), oci_result($stid, 'SENSOR_ID'),oci_result($stid, 'DATE_CREATED'),oci_result($stid, 'VALUE'));
							array_push($lists,$list);
							$i3 = $i3+1;
						}
						oci_free_statement($stid);
						if ($i3==0){
							echo "THIS SENSOR HAS NO SCALAR DATA";						
						}
						
						//fetch image
						$stid = oci_parse($conn,$search_string_image);
						oci_execute($stid);
						$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
						
						echo "<br />IMAGE THUMBNAIL:";
						if (!$row) {
							//echo '<br>Status: 404 Not Found<br>';
						} 
						if ($row['THUMBNAIL'] != null && $row['RECOREDED_DATA'] != null){
							$img = $row['THUMBNAIL']->load();
			
							// display image (no need decoded)
							echo '<img src="data:image/gif;base64,'.$img.'" />';
			
							$img = $row['RECOREDED_DATA']->load();
							$decoded = base64_decode($img);

							file_put_contents('sensor.jpg', $decoded);

			
							// download the audio
							echo '<br /><a href="sensor.jpg" download="'.$sensor_ids[$i2].'.jpg">Download image</a>';
							oci_free_statement($stid);
						} else if($row['RECOREDED_DATA'] == null){
							echo "<br />THIS SENSOR HAS NO IMAGE<br />";
						}
						//oci_free_statement($stid);
						
						//audio
						$stid = oci_parse($conn, $search_string_audio);
						oci_execute($stid);
						$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
						
						echo "Audio:";
						if (!$row) {
							//echo '<br>Status: 404 Not Found<br>';
						}
						if ($row['RECORDED_DATA'] != null){
						$wav = $row['RECORDED_DATA']->load();
						$decoded = base64_decode($wav);
			
						file_put_contents('audio.wav', $decoded);
				
						// play the audio
						echo '<audio controls>';
						echo '<source src="audio.wav" type="audio/wav">';
						echo '</audio>';
			
						// download the audio
						echo '<br /><a href="audio.wav" download="'.$sensor_ids[$i2].'.wav">Download audio</a>';
						//Audio
						}else if ($row['RECORDED_DATA'] == null){
							echo "<br />THIS SENSOR HAS NO AUDIO<br />";
						}
						//oci_free_statement($stid);
					}

					
					$fp = fopen('file.csv', 'w');
					foreach ($lists as $fields) {
	   				fputcsv($fp, $fields, ", ");
					}
					fclose($fp);

					echo "<br><a href=\"file.csv\" download=\"scalar_data.csv\">Download Scalar</a>";
				}
			

			if ($i == 0 ){

				// select sensors that satify ID, type, location and description
				$sql = "SELECT * FROM subscriptions NATURAL JOIN sensors WHERE PERSON_ID = ".$_SESSION['person_id'];
				
				if(strval($_POST['type']) != "") {
					$sql = $sql." AND SENSOR_TYPE = '".$_POST['type']."'";
				}
				if(strval($_POST['location']) != "") {
					$sql = $sql." AND LOCATION = '".$_POST['location']."'";
				}
				
				//echo $sql."<br>";
				
				
				$stid = oci_parse($conn, $sql );
				oci_execute($stid);
				
				$sensor_ids = (array)null;
				$i = 0;
				while (oci_fetch($stid)) {
  					$sensor_ids[$i] = oci_result($stid, 'SENSOR_ID');
  					$i = $i + 1;
				}
				oci_free_statement($stid);
				$lists = array();
			
				for ($i2 = 0; $i2 < $i; $i2++){
					echo "<br />Sensors that satisfy the conditions:<br>Sensor ID: ".$sensor_ids[$i2]."<br />";
					$search_string_image = "select thumbnail, recoreded_data from images where SENSOR_ID = ".$sensor_ids[$i2];
					$search_string_audio = "select recorded_data from audio_recordings where SENSOR_ID = ".$sensor_ids[$i2];
			
					if(strval($_POST['from']) != "") {
						$search_string_image = $search_string_image." AND date_created >=  TO_DATE('".$_POST['from']."','DD-MM-YYYY')";
						$search_string_audio = $search_string_audio." AND date_created >=  TO_DATE('".$_POST['from']."','DD-MM-YYYY')";
					}
					if(strval($_POST['to']) != "") {
						$search_string_image = $search_string_image." AND date_created <=  TO_DATE('".$_POST['to']."','DD-MM-YYYY')";
						$search_string_audio = $search_string_audio." AND date_created <=  TO_DATE('".$_POST['to']."','DD-MM-YYYY')";
					}
					if(strval($_POST['description']) != "") {
						$search_string_image = $search_string_image." AND description like '%".$_POST['description']."%'";
						$search_string_audio = $search_string_audio." AND description like '%".$_POST['description']."%'";
					}
					
					//echo "<br>".$search_string_image."<br>";
					//echo "<br>".$search_string_audio."<br>";
					//echo "<br>".$search_string_scalar."<br>";
					
					$stid = oci_parse($conn,$search_string_image);
					oci_execute($stid);
					$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
					
					echo "<br />IMAGE THUMBNAIL:";
					if (!$row) {
						//echo '<br>Status: 404 Not Found<br>';
					} 
					if ($row['RECOREDED_DATA'] != null){
							
							$img = $row['RECOREDED_DATA']->load();
							$decoded = base64_decode($img);
			
							// display image (no need decoded)
							echo '<img src="data:image/gif;base64,'.$img.'" />';
			
			
							$img = $row['RECOREDED_DATA']->load();
							$decoded = base64_decode($img);
		
				
							file_put_contents('sensor.jpg', $decoded);
							//oci_free_statement($stid);

					}else if($row['THUMBNAIL'] == null){
							echo "<br />THIS SENSOR HAS NO IMAGE<br />";
					}
					//oci_free_statement($stid);
					
					//audio
					$stid = oci_parse($conn, $search_string_audio);
					oci_execute($stid);
					$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
					
					echo "AUDIO:";
					if (!$row) {
						//echo '<br>Status: 404 Not Found<br>';
					}
					if ($row['RECORDED_DATA'] != null){
					$wav = $row['RECORDED_DATA']->load();
					$decoded = base64_decode($wav);
			
					file_put_contents('audio.wav', $decoded);
			
					// play the audio
					echo '<audio controls>';
					echo '<source src="audio.wav" type="audio/wav">';
					echo '</audio>';
			
					// download the audio
					echo '<br /><a href="audio.wav" download="'.$sensor_ids[$i2].'.wav">Download audio</a>';
					//Audio
					}else if ($row['RECORDED_DATA'] == null){
						echo "<br />THIS SENSOR HAS NO AUDIO<br />";
					}
				}
			}
		}
	
		
	?>	
	
</body>

</html>
