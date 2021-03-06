<?php
	session_start();
	if($_SESSION['role'] != 'd'){
		header("Location: restriction.html"); 
		exit();
	}
?>
<?php
	include ("PHPconnectionDB.php");   
	include ("datacuratorFunction.php");
	//include ("resizeImage.php");   
	//establish connection
	$conn=connect();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<script src="jquery-1.11.3.js" type="text/javascript"></script>
<script src="style.js" type="text/javascript"></script>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Data Curator</title>
</head>
<body>
	<h1>Data Curator Page</h1>
	<button onclick="location.href = 'logout.php';">Logout</button>
	<h2>Upload Module</h2>
	<div id="upload_audio_btn">Upload Audio</div>
	<div id="upload_audio_panel">
		<form action="" method="post" enctype="multipart/form-data">
			<fieldset>
				<legend>Audio Information:</legend>
				Sensor id: <select name="sensor_audio_id">				
				<?php
					$sql = "SELECT sensor_id FROM sensors WHERE sensor_type = 'a'";
					$stid = oci_parse($conn, $sql );
					$res = oci_execute($stid);
					
					while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
						foreach ($row as $id) {
							echo "<option value=$id>$id</option>"; 
						}
					}	
				?>
				</select>
				<br /><br />				
				Date created: <input type="text" name="date_audio"> DD/MM/YYYY HH:MM:SS<br /><br />
				Length: <input type="text" name="length_audio"> <br /><br />
				Description: <input type="text" name="desc_audio"> <br /><br />
				File: <input type="file" name="file_audio"> <br /><br />
				<button type="reset">Reset</button>
				<input type="submit" name="submit_audio" value="Submit">
			</fieldset>
		</form>
	</div>
	<div id="upload_images_btn">Upload Image</div>
	<div id="upload_images_panel">
		<form action="" method="post" enctype="multipart/form-data">
			<fieldset>
				<legend>Image Information:</legend>
				Sensor id: <select name="sensor_image_id">				
				<?php
					$sql = "SELECT sensor_id FROM sensors WHERE sensor_type = 'i'";
					$stid = oci_parse($conn, $sql );
					$res = oci_execute($stid);
					
					while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
						foreach ($row as $id) {
							echo "<option value=$id>$id</option>"; 
						}
					}	
				?>
				</select>
				<br /><br />
				Date created: <input type="text" name="date_image"> DD/MM/YYYY HH:MM:SS<br /><br />
				Description: <input type="text" name="desc_image"> <br /><br />
				File: <input type="file" name="file_image"> <br /><br />
				<button type="reset">Reset</button>
				<input type="submit" name="submit_image" value="Submit">
			</fieldset>
		</form>
	</div>
	<div id="upload_scalar_btn">Upload Scalar</div>
	<div id="upload_scalar_panel">
		<form action="" method="post" enctype="multipart/form-data">
			<fieldset>
				<legend>Scalar Information:</legend>
				File: <input type="file" name="file_scalar"> <br /><br />
				<button type="reset">Reset</button>
				<input type="submit" name="submit_scalar" value="Submit">
			</fieldset>
		</form>
	</div>
	<?php
	     // ----Upload Audio----
        if (isset($_POST["submit_audio"])) {
        	/*
        		Sensor id: <input type="text" name="sensor_audio_id"> <br /> <br />
				Date created: <input type="text" name="date_audio"> DD/MM/YYYY<br /><br />
				Length: <input type="text" name="length_audio"> <br /><br />
				Description: <input type="text" name="desc_audio"> <br /><br />
				File: <input type="file" name="file_audio"> <br /><br />
        	*/
        	   // sensor_id
	         $sensor_id = $_POST['sensor_audio_id'];
	         if (checkSensorId($conn, $sensor_id) != 0) {
	             return;
	         }
	         // date
	         $date_created = $_POST['date_audio'];
	         // length
	         $length = $_POST['length_audio'];
	         // description
	         $description = $_POST['desc_audio'];
	         // Audio Type Check
	         $audioType = $_FILES['file_audio']['type'];
	         if ($audioType != "audio/wav") {
	             echo "File Not Found/Extension not allowed, please choose a wav file";
	         } else {
	         
	         	$audio_id = generateId($conn, "audio_recordings");
	         	if ($audio_id == 0) {
	             		return;
	        	 }      
           		 $audio2 = file_get_contents($_FILES['file_audio']['tmp_name']);
            	 $audio = base64_encode($audio2);
           		 $sql = "INSERT INTO audio_recordings(recording_id, sensor_id, date_created, length, description, recorded_data)
                         VALUES(".$audio_id.",".$sensor_id.", TO_DATE('".$date_created."', 'DD/MM/YYYY hh24:mi:ss'),".$length.",'".$description."',empty_blob())
                         RETURNING recorded_data INTO :recorded_data";
         
                         
           		 $result = oci_parse($conn, $sql);
           		 $recorded_dataBlob = oci_new_descriptor($conn, OCI_D_LOB);
           		 oci_bind_by_name($result, ":recorded_data", $recorded_dataBlob, -1, OCI_B_BLOB);
            		 $res = oci_execute($result, OCI_DEFAULT) or die ("Unable to execute query");
            		 if ($recorded_dataBlob -> save($audio)) {
               		 	oci_commit($conn);
            		 } else {
            	    	oci_rollback($conn);
            		 }
            		 oci_free_statement($result);
            		 $recorded_dataBlob->free();
            		 echo "New audio is added with image_id ->".$audio_id."<br>";   
	     	}
	    }
	     // ----Upload Image----
        // TO DO: thumbnail -> resize the image and update the database
        if (isset($_POST["submit_image"])){
		      // sensor_id
 		      $sensor_id = $_POST['sensor_image_id'];
 		      /*
 		      $tmp = checkSensorId($conn, $sensor_id);
 		      if ($tmp != 0) {
 		          return;
 		      }
 		      */
 		      if (checkSensorId($conn, $sensor_id) != 0) {
 		          return;
 		      }
		      // date
		      $date_created = $_POST['date_image'];
		      // description
		      $description = $_POST['desc_image'];
		      // Image Type Check
		      $imgType=$_FILES['file_image']['type'];
		      if ($imgType != "image/jpeg") {
		          echo "File Not Found/Extension not allowed, please choose a JPG file";
                return;
		      }
	         // Upload Image
	         uploadImage($conn, $sensor_id, $date_created, $description);
        }
        
        // ----Upload Scalar----
        // separate a string by another string
        // http://php.net/manual/en/function.explode.php
        if (isset($_POST["submit_scalar"])) {
        	   
        	   $scalarType = $_FILES['file_scalar']['type'];
        	   if ($scalarType !=  "text/csv") {
        	       echo "File Not Found/Extension not allowed, please choose a csv file";
        	       return;
        	   }
        	   $fp = fopen($_FILES['file_scalar']['tmp_name'],'r');
        	   /*
        	   while ( ($line = fgets($fp)) !== false) {
                echo $line."<br>";
                $pieces = explode(",", $line);
                echo "Size ->".count($pieces)."<br>";
                echo $pieces[0]."|".$pieces[1]."|".$pieces[2]."|".$pieces[3]."<br>------<br>";
            }
            */
            $i = 0; // number of rows
            while (($line = fgets($fp)) != false) {
            	// check if there are enough values
            	$pieces = explode(",", $line);
            	if (count($pieces) != 3) {
            	    echo "Not enough data<br>";
            	    continue;
            	}
            	// generate an unique scalar_id
            	$scalar_id = generateId($conn, "scalar_data");
            	if ($scalar_id == 0) {
            		 // Unable to generate more unique id, return
            	    return;
            	}
            	// sensor_id
            	$sensor_id = intval($pieces[0]);
            	if (checkSensorId($conn, $sensor_id) != 0) {
            	    continue;
            	}
            	// date
            	$date = $pieces[1];
            	// value
            	$value = intval($pieces[2]);
            	$sql = "INSERT INTO scalar_data VALUES (".$scalar_id.", ".$sensor_id.", TO_DATE('".$date."', 'DD/MM/YYYY hh24:mi:ss'), ".$value.")";
               $stid = oci_parse($conn, $sql);
               $res=oci_execute($stid);
               if (!$res) {
                   $err = oci_error($stid); 
                   echo htmlentities($err['message']);
               }
	            else{
		             echo 'Row inserted with scalar Id -> '.$scalar_id.'<br>';
		             $i++;
	            }
            }
            echo "Total ".$i." rows inserted<br>";
        }
        
        oci_close($conn);
	?>
</body>
</html>
