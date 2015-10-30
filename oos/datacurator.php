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
<body>
	<h1>Data Curator Page</h1>
	<button onclick="location.href = 'logout.php';">Logout</button>
	<h2>Upload Module</h2>
	<div id="upload_audio_btn">Upload Audio</div>
	<div id="upload_audio_panel">
		<form action="" method="post">
			<fieldset>
				<legend>Audio Information:</legend>
				Sensor id: <input type="text" name="sensor_audio_id"> <br /> <br />
				Date created: <input type="text" name="date_audio"> <br /><br />
				Length: <input type="text" name="length_audio"> <br /><br />
				Description: <input type="text" name="desc_audio"> <br /><br />
				File: <input type="file" name="file_audio"> <br /><br />
				<button type="reset">Reset</button>
				<input type="submit" name="submit" value="Submit">
			</fieldset>
		</form>
	</div>
	<div id="upload_images_btn">Upload Image</div>
	<div id="upload_images_panel">
		<form action="" method="post">
			<fieldset>
				<legend>Image Information:</legend>
				Sensor id: <input type="text" name="sensor_image_id"> <br /> <br />
				Date created: <input type="text" name="date_image"> <br /><br />
				Description: <input type="text" name="desc_image"> <br /><br />
				File: <input type="file" name="file_image"> <br /><br />
				<button type="reset">Reset</button>
				<input type="submit" name="submit" value="Submit">
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
				<input type="submit" name="submit" value="Submit">
			</fieldset>
		</form>
	</div>
</body>
</html>