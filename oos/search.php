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
		$valid = false;
		if ($_POST["from"] != "" && $_POST["to"] != ""){
			$valid = true;		
		}
	
		if (isset($_POST["submit_search"]) && !$valid){
			echo "Please enter the complete time period";
		}
		if (isset($_POST["submit_search"]) && $valid){	
		
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
			$match = false;
			if ($i > 0 ){
				$lists = array();
				
				
				for ($i2 = 0; $i2 < $i; $i2++){
					echo "<br />Sensors that satisfy the conditions:<br>Sensor ID: ".$sensor_ids[$i2]."<br />";
					$match  = true;
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
						$i3 = 0;
						while (oci_fetch($stid)) {
							echo "<br>".oci_result($stid, 'ID'),",", oci_result($stid, 'SENSOR_ID'),",",oci_result($stid, 'DATE_CREATED'),",",oci_result($stid, 'VALUE');
							$list = array(oci_result($stid, 'ID'), oci_result($stid, 'SENSOR_ID'),oci_result($stid, 'DATE_CREATED'),oci_result($stid, 'VALUE'));
							array_push($lists,$list);
							$i3 = $i3+1;
						}
						oci_free_statement($stid);

						//fetch image
						$stid = oci_parse($conn,$search_string_image);
						oci_execute($stid);
												
						while($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS )){	
						
							if ($row['THUMBNAIL'] != null && $row['RECOREDED_DATA'] != null){
								$img = $row['THUMBNAIL']->load();
			
								// display thumbnail (no need decoded)
								echo '<br><img src="data:image/jpg;base64,'.$img.'" />';
			
								$img2 = $row['RECOREDED_DATA']->load();

								// download the full size image
								echo '<br /><a href="data:image/jpg;base64,'.$img2.'" download="'.$sensor_ids[$i2].'.jpg">Download image</a>';
								//oci_free_statement($stid);
							
							} else if($row['RECOREDED_DATA'] == null){
								//echo "<br />THIS SENSOR HAS NO IMAGE<br />";
							}
						}
						$num = oci_num_rows($stid);							
						
						//fetch audio
						$stid = oci_parse($conn, $search_string_audio);
						oci_execute($stid);
							
						while($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)){
							if (!$row) {
								//echo '<br>Status: 404 Not Found<br>';
							}
							if ($row['RECORDED_DATA'] != null){
								$wav = $row['RECORDED_DATA']->load();
								$decoded = base64_decode($wav);
			
								file_put_contents('audio.wav', $decoded);
								
								// play the audio
								echo '<audio controls>';
								// change src to "data:audio/wav;base64,'.$wav.'"
								echo '<source src="data:audio/wav;base64,'.$wav.'">';
								echo '</audio>';
				
								// download the audio
								echo '<br /><a href="data:audio/wav;base64,'.$wav.'" download="'.$sensor_ids[$i2].'.wav">Download audio</a></br>';
								//Audio
						
							}else if ($row['RECORDED_DATA'] == null){
								//echo "<br />THIS SENSOR HAS NO AUDIO<br />";
							}	
						}
						$num = oci_num_rows($stid);		
						echo "<hr>";
					
					}

					if ($lists != null){
						$fp = fopen('file.csv', 'w');
						foreach ($lists as $fields) {
	   					fputcsv($fp, $fields, ", ");
						}
						fclose($fp);

						echo "<br><a href=\"file.csv\" download=\"scalar_data.csv\">Download Scalar</a>";	
					}				
				}
			

			if ($i == 0 ){

				// select sensors that satify ID, type, location
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
					$num = oci_num_rows($stid);	

					if ($num == 0) {
						//echo "<br />THIS SENSOR HAS NO IMAGE<br />";
					} 
					
					while($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)){
						if ($row['THUMBNAIL'] != null && $row['RECOREDED_DATA'] != null){
							$match  = true;
							$img = $row['THUMBNAIL']->load();
			
							$decoded = base64_decode($img);
			
							// display thumbnail (no need decoded)
							echo '<br><img src="data:image/jpg;base64,'.$img.'" />';
			
							$img2 = $row['RECOREDED_DATA']->load();

							// download the full size image
							echo '<br /><a href="data:image/jpg;base64,'.$img2.'" download="'.$sensor_ids[$i2].'.jpg">Download image</a>';
							

						}else if($row['THUMBNAIL'] == null){
							//echo "<br />THIS SENSOR HAS NO IMAGE<br />";
						}
					}
					
					$num = oci_num_rows($stid);	
					if ($num == 0) {
						//echo "<br />THIS SENSOR HAS NO IMAGE<br />";
					} 
					//oci_free_statement($stid);
					
					//audio
					$stid = oci_parse($conn, $search_string_audio);
					oci_execute($stid);
					$num = oci_num_rows($stid);	
					
					while($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)){
					
						if (!$row) {
							//echo '<br>Status: 404 Not Found<br>';
						}
						if ($row['RECORDED_DATA'] != null){
							$match  = true;
							$wav = $row['RECORDED_DATA']->load();
							$decoded = base64_decode($wav);
				
							file_put_contents('audio.wav', $decoded);
				
							// play the audio
							echo '<audio controls>';
							// change src to "data:audio/wav;base64,'.$wav.'"
							echo '<source src="data:audio/wav;base64,'.$wav.'">';
							echo '</audio>';
			
							// download the audio
							echo '<br /><a href="data:audio/wav;base64,'.$wav.'" download="'.$sensor_ids[$i2].'.wav">Download audio</a></br>';
							//Audio
						
						}else if ($row['RECORDED_DATA'] == null){
							//echo "<br />THIS SENSOR HAS NO AUDIO<br />";
						}
					
					}
				
					$num = oci_num_rows($stid);	
						if ($num == 0) {
							//echo "<br />THIS SENSOR HAS NO AUDIO<br />";
						} 
				
					if($match){echo "<hr>";}
				}
			}
			
			if (!$match){
				echo "NO MATCHING RESULT";		
			}
		}
	
		
	?>	
	
</body>

</html>
