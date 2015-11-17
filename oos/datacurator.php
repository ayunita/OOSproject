<?php
	session_start();
	if($_SESSION['role'] != 'd'){
		header("Location: restriction.html"); 
		exit();
	}
?>
<?php
	include ("PHPconnectionDB.php");        
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
		<form action="" method="post">
			<fieldset>
				<legend>Audio Information:</legend>
				Sensor id: <input type="text" name="sensor_audio_id"> <br /> <br />
				Date created: <input type="text" name="date_audio"> <br /><br />
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
				Sensor id: <input type="text" name="sensor_scalar_id"> <br /> <br />
				Date created: <input type="text" name="date_scalar"> <br /><br />
				Value: <input type="text" name="scalar_value"> <br /><br />
				<button type="reset">Reset</button>
				<input type="submit" name="submit_scalar" value="Submit">
			</fieldset>
		</form>
	</div>
	<?php
	
	    /*
		*  taken from:
		*  http://php.net/manual/en/function.base64-encode.php
		*  http://php.net/manual/en/function.oci-new-descriptor.php
		*  (C) 2015 PHP Group modified by yunita
		*/
		
	    if (isset($_POST["submit_image"])){
			$image_id = rand(1000, 9999);
			$sensor_id = $_POST['sensor_image_id'];
			$date_created = $_POST['date_image'];
			$description = $_POST['desc_image'];
			
			$image2= file_get_contents($_FILES['file_image']['tmp_name']);
			
			// encode the image into string for full size image
			$fullsize_image = base64_encode($image2);
			
			// get image information
			$image_info = getimagesize($_FILES['file_image']['tmp_name']);
			$image_width = $image_info[0];
			$image_height = $image_info[1];

			$thumb = resizeImage($_FILES['file_image']['tmp_name'],$image_width, $image_height);
			
			/*
			 * taken from http://stackoverflow.com/questions/8551754/convert-gd-output-to-base64
			 * (C) 2011 Filip Roséen
			 *
			 * This is the part to convert the image to thumbnail
			 * For the project, we need to display the thumbnail, and
			 * download the full size image.
			 * Run this to see the example:
			 * http://consort.cs.ualberta.ca/~yunita/OOSproject/oos/upload.php
			 * http://consort.cs.ualberta.ca/~yunita/OOSproject/oos/load.php
			 */ 
			ob_start (); 
			imagejpeg ($thumb);
			$image_data = ob_get_contents (); 
			ob_end_clean ();
			
			// encode the image into string for thumbnail
			$image = base64_encode($image_data);
			
			// change this sql query, this is just an example
			$sql = "INSERT INTO images (image_id, sensor_id, date_created, description, thumbnail, recoreded_data)
					VALUES(".$image_id.", ".$sensor_id.", TO_DATE('".$date_created."', 'YY-MM-DD'), '".$description."', empty_blob(), empty_blob())
					RETURNING thumbnail, recoreded_data INTO :thumbnail, :recoreded_data";
			$result = oci_parse($conn, $sql);
			
			$blob1 = oci_new_descriptor($conn, OCI_D_LOB);
			$blob2 = oci_new_descriptor($conn, OCI_D_LOB);
			
			oci_bind_by_name($result, ":thumbnail", $blob1, -1, OCI_B_BLOB);
			oci_bind_by_name($result, ":recoreded_data", $blob2, -1, OCI_B_BLOB);
			
			oci_execute($result, OCI_DEFAULT) or die ("Unable to execute query");
			
			// if blob1 & blob2 are not empty -> save both, then commit
			if($blob1->save($image) && $blob2->save($fullsize_image)) {
				oci_commit($conn);
			}
			else {
				oci_rollback($conn);
			}
			
			
			oci_free_statement($result);
			$blob1->free();
			$blob2->free();
		}
	
	?>
</body>
</html>

<?php
	/**
	* Resize an image and keep the proportions
	* @author Allison Beckwith <allison@planetargon.com>
	* @param string $filename
	* @param integer $max_width
	* @param integer $max_height
	* @return image
	*/
	function resizeImage($filename, $max_width, $max_height)
	{
		list($orig_width, $orig_height) = getimagesize($filename);
	
		$width = $orig_width;
		$height = $orig_height;
	
		# taller
		if ($height > $max_height) {
			$width = ($max_height / $height) * $width;
			$height = $max_height;
		}
	
		# wider
		if ($width > $max_width) {
			$height = ($max_width / $width) * $height;
			$width = $max_width;
		}
	
		$image_p = imagecreatetruecolor($width, $height);
	
		$image = imagecreatefromjpeg($filename);
	
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, 
										 $width, $height, $orig_width, $orig_height);
	
		return $image_p;
	}
?>