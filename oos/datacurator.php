<?php
	session_start();
	if($_SESSION['role'] != 'd'){
		header("Location: restriction.html"); 
		exit();
	}
?>
<?php
	include ("PHPconnectionDB.php");   
	include ("Datacurator.php");
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
		<form action="" method="post">
			<fieldset>
				<legend>Scalar Information:</legend>
				Sensor id: <select name="sensor_scalar_id">				
				<?php
					$sql = "SELECT sensor_id FROM sensors WHERE sensor_type = 's'";
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
				Date created: <input type="text" name="date_scalar"> DD/MM/YYYY HH:MM:SS<br /><br />
				Value: <input type="text" name="scalar_value"> <br /><br />
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
	         if ($audioType != "audio.wav") {
	             echo "File Not Found/Extension not allowed, please choose a wav file";
	         }
	         
	         $audio_id = generateId($conn, "audio_recordings");
	         if ($audio_id == 0) {
	             return;
	         }
	         
            $audio2 = file_get_contents($_FILES['file_image']['tmp_name']);
            
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
        if (isset($_POST["submit_scalar"])) {
        	   $id = generateId($conn, "scalar_data");
            // sensorId
            $sensorId = $_POST['sensor_scalar_id'];
 		      if (checkSensorId($conn, $sensorId) != 0) {
 		          return;
 		      }
            
            // date
            $date = $_POST['date_scalar'];
            
            // value
            $value = $_POST['scalar_value'];
            
            $sql = "INSERT INTO scalar_data VALUES (".$scalarId.", ".$sensorId.", TO_DATE('".$date."', 'DD/MM/YYYY'),".$value.")";
            $stid = oci_parse($conn, $sql);
            $res=oci_execute($stid);
            if (!$res) {
                $err = oci_error($stid); 
                echo htmlentities($err['message']);
            }
	         else{
		          echo 'Row inserted <br>scalar Id -> '.$scalarId.'<br>';
	         }
            oci_free_statement($stid);
        }
        
        oci_close($conn);
	?>
</body>
</html>