	<?php 
	
	function search($conn){
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
					$search_string_image = "select thumbnail, recoreded_data from images where SENSOR_ID = ".$sensor_ids[$i2]." AND date_created between TO_DATE('".$_POST['from']."','DD/MM/YYYY hh24:mi:ss') and TO_DATE('".$_POST['to']."','DD/MM/YYYY hh24:mi:ss')";
					$search_string_audio = "select recorded_data from audio_recordings where SENSOR_ID = ".$sensor_ids[$i2]." AND date_created between TO_DATE('".$_POST['from']."','DD/MM/YYYY hh24:mi:ss') and TO_DATE('".$_POST['to']."','DD/MM/YYYY hh24:mi:ss')";
					$search_string_scalar = "select SENSOR_ID, TO_CHAR(DATE_CREATED, 'DD/MM/YYYY HH24:MI:SS') as DATE_CREATED, VALUE from scalar_data where SENSOR_ID = ".$sensor_ids[$i2]." AND date_created between TO_DATE('".$_POST['from']."','DD/MM/YYYY hh24:mi:ss') and TO_DATE('".$_POST['to']."','DD/MM/YYYY hh24:mi:ss')";
			

					//echo "<br>".$search_string_image."<br>";
					//echo "<br>".$search_string_audio."<br>";
					//echo "<br>".$search_string_scalar."<br>";
			
					$stid = oci_parse($conn, $search_string_scalar);
					oci_execute($stid);
						
					//scalar
					$i3 = 0;
					while (oci_fetch($stid)) {
						$list = array(oci_result($stid, 'SENSOR_ID'),date(oci_result($stid, 'DATE_CREATED')),oci_result($stid, 'VALUE'));
						$scalar_data = oci_result($stid, 'SENSOR_ID').",".oci_result($stid, 'DATE_CREATED').",".oci_result($stid, 'VALUE');
						echo "<br>".$scalar_data;
						array_push($lists,$list);
						$i3 = $i3+1;
							
					}
					//echo $i3."<br/><br/><br/><br/>";
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
								
							// play the audio
							echo '<audio controls>';
							// change src to "data:audio/wav;base64,'.$wav.'"
							echo '<source src="data:audio/wav;base64,'.$wav.'">';
							echo '</audio>';
				
							// download the audio
							echo '<br /><a href="data:audio/wav;base64,'.$wav.'" download="'.$row['recording_id'].'.wav">Download audio</a></br>';
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
						fputs($fp, implode($fields, ',')."\n");
						
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
					
					$search_string_image = "select thumbnail, recoreded_data from images where SENSOR_ID = ".$sensor_ids[$i2]." AND date_created between TO_DATE('".$_POST['from']."','DD/MM/YYYY hh24:mi:ss') and TO_DATE('".$_POST['to']."','DD/MM/YYYY hh24:mi:ss')";;
					$search_string_audio = "select recorded_data from audio_recordings where SENSOR_ID = ".$sensor_ids[$i2]." AND date_created between TO_DATE('".$_POST['from']."','DD/MM/YYYY hh24:mi:ss') and TO_DATE('".$_POST['to']."','DD/MM/YYYY hh24:mi:ss')";;
			

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
	}
		?>