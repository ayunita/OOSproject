<!-- test -->
<?php
	session_start();
	if($_SESSION['role'] != 'd'){
		header("Location: restriction.html"); 
		exit();
	}

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
<?php
	include ("PHPconnectionDB.php");
	//establish connection
	$conn=connect();
?>
<body>
	<h1>Data Curator Page</h1>
	<button onclick="location.href = 'logout.php';">Logout</button>
	<h2>Upload Module</h2>
	<div id="upload_audio_btn">Upload Audio</div>
	<div id="upload_audio_panel">
		<form action="" method="post">
			<fieldset>
				<legend>Audio Information:</legend>
				Sensor id: <input type="number" name="sensor_audio_id"> <br /> <br />
				Date created: <input type="text" name="date_audio"> <br /><br />
				Length: <input type="text" name="length_audio"> <br /><br />
				Description: <input type="text" name="desc_audio"> <br /><br />
				File: <input type="file" name="file_audio"> <br /><br />
				<button type="reset">Reset</button>
				<input type="submit" name="upload_audio" value="Submit">
			</fieldset>
		</form>
	</div>
	<div id="upload_images_btn">Upload Image</div>
	<div id="upload_images_panel">
		<form action="" method="post">
			<fieldset>
				<legend>Image Information:</legend>
				Sensor id: <input type="number" name="sensor_image_id"> <br /> <br />
				Date created: <input type="text" name="date_image"> dd-mm-yyyy <br /><br /><!--type = "date" (???)-->
				Description: <input type="text" name="desc_image"> <br /><br />
				File: <input type="file" name="file_image"> <br /><br />
				<button type="reset">Reset</button>
				<input type="submit" name="upload_image" value="Submit">
			</fieldset>
		</form>
	</div>
	<div id="upload_scalar_btn">Upload Scalar</div>
	<div id="upload_scalar_panel">
		<form action="" method="post">
			<fieldset>
				<legend>Scalar Information:</legend>
				Sensor id: <input type="text" name="sensor_scalar_id"> <br /> <br />
				Date created: <input type="text" name="date_scalar"> <br /><br />
				Value: <input type="text" name="scalar_value"> <br /><br />
				<button type="reset">Reset</button>
				<input type="submit" name="upload_scalar" value="Submit">
			</fieldset>
		</form>
	</div>
		<?php
		// TO DO: upload audio
		
		// TO DO: upload image
		// http://forum.codecall.net/topic/40286-tutorial-storing-images-in-mysql-with-php/
		// by: Guest_Jordan_*
		if (isset($_POST["upload_image"])){
			echo "File Type: \n\n\n\n\n\n";
			// to insert data into table IMAGES, we need (total 6 variables):
			// IMAGE_ID, SENSOR_ID, DATE_CREATED, DESCRIPTION, THUMBNAIL, RECORDED_DATA
			$imageId = rand(1000, 9999);
			
			// TO DO: check if the imageId is in the database. If yes then generate another one
			$sensorId = $_POST['sensor_image_id']; // TO DO: check if the sensorId is in table sensors (???)
			$date = $_POST['date_image'];
			$description = $_POST['desc_image'];
			
			// upload an image file
			
			$imageFilename = $_FILES['file_image']; //read the image and insert into the database as thumbnail and recordedData
			/*
			$fp = fopen('beermug.jpg', 'r');
			//$data = fread($fp, filesize($imageFilename));
			$data = fread($imageFilename, filesize($imageFilename['size']));
			$data = addslashes($data);
			fclose($fp);
			*/
			
			/*
			// http://www.codingcage.com/2014/12/file-upload-and-view-with-php-and-mysql.html
			$file = rand(1000,100000)."-".$_FILES['file_image']['name'];
			$file_loc = $_FILES['file_image']['tmp_name'];
			$file_size = $_FILES['file_image']['size'];
			$file_type = $_FILES['file_image']['type'];
 			$folder="uploads/";
 			move_uploaded_file($file_loc,$folder.$file);
 			*/
			
			
			//TO DO: $thumbnail // similar to $data, but to resize it
			//TO DO: $recordedData // same to $data
			
			// insert into the database
			try {
				//$sql = "INSERT INTO IMAGES VALUES (".$imageId.", ".$sensorId.", ".$date.", ".strval($description).", ".$data.", ".$data.")";
				$sql = "INSERT INTO IMAGES VALUES (".$imageId.", ".$sensorId.", '".$date."', '".$description."', ".$imageFilename.", ".$imageFilename.")";
				echo "INSERT INTO IMAGES VALUES (".$imageId.", ".$sensorId.", ".$date.", '".$description."', "."NULL".", "."NULL".")";
				//$sql = "INSERT INTO IMAGES VALUES (".$imageId.", '".$sensorId."', '".$date."', '".$description."', '".$_POST['file_image']."', '".$_POST['file_image']."')";
				$stid = oci_parse($conn, $sql);
				echo "stid = ".intval($stid)."<br><br><br>";
				oci_execute($stid);
			
				echo 'New image is added.<br />';
				echo $imageId.", ".$sebsirId.", ".$date.", ".$desctiption.", ".$imageFilename;
				oci_free_statement($stid);
				oci_close($conn);
			} catch (Exception $e) {
				echo 'Caught Exception: ', $e->getMessage(), "\n";
			}
			/*
			//$sql = "INSERT INTO IMAGES VALUES (".$imageId.", '".$sensorId."', '".$date."', '".$description."', '".$data."', '".$data."')";
			$sql = "INSERT INTO IMAGES VALUES (".$imageId.", '".$sensorId."', '".$date."', '".$description."', '".$_POST['file_image']."', '".$_POST['file_image']."')";
			$stid = oci_parse($conn, $sql );
			$res = oci_execute($stid);
			
			echo 'New image is added.<br />';
			echo $imageId.", ".$sebsirId.", ".$date.", ".$desctiption.", ".$imageFilename;
			oci_free_statement($stid);
			oci_close($conn);
			*/
			
			
			/*
			$firstname=$_POST['firstname'];
			$lastname=$_POST['lastname'];
			$address=$_POST['address'];
			$email=$_POST['email'];
			$phone=$_POST['phone'];
			$person_id=rand(1000, 9999);
			$_SESSION['person_id'] = $person_id;
			$sql = "INSERT INTO persons VALUES (".$person_id.", '".$firstname."', '".$lastname."', '".$address."', '".$email."', '".$phone."')";
			$stid = oci_parse($conn, $sql );
			$res=oci_execute($stid);
			
			echo 'New person is added.<br />';
			echo $person_id.", ".$firstname.", ".$lastname.", ".$address.", ".$email.", ".$phone;
			
			// Free the statement identifier when closing the connection
			oci_free_statement($stid);
			oci_close($conn);
			*/
		}
		
		//TO DO: upload scalar
		
		?>
</body>
</html>
