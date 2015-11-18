<!--
	This example used:
	TABLE test, COLUMN id (number), audio (blob)
	TABLE test2, COLUMN id (number), image (blob)
-->
<?php
    
    /*
     * taken from:
     * http://php.net/manual/en/function.imagecreatefromstring.php
     * http://stackoverflow.com/questions/3056287/oracle-blob-as-img-src-in-php-page
     * modified by yunita
     */

	include ("PHPconnectionDB.php");        
	//establish connection
	$conn=connect();

?>

<html>
	<head><title>Load</title></head>
	<body>
		<form action="" method="post">
		Search audio: <select name="audios" value="">audio id</option>
		<?php
			$sql = "SELECT id FROM test";
			$stid = oci_parse($conn, $sql );
			$res = oci_execute($stid);
					
			while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
				foreach ($row as $id) {
					echo "<option value=$id>$id</option>"; 
				}
			}
		?>
			<input type="submit" name="search_audio" value="Search">
		</form>
		
		<form action="" method="post">
		Search image: <select name="images" value="">image id</option>
		<?php
			$sql = "SELECT image_id FROM images";
			$stid = oci_parse($conn, $sql );
			$res = oci_execute($stid);
					
			while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
				foreach ($row as $id) {
					echo "<option value=$id>$id</option>"; 
				}
			}
		?>
			<input type="submit" name="search_image" value="Search">
		</form>

<!-- Search audio example -->
<?php
	if (isset($_POST["search_audio"])){
		$audio_id=$_POST['audios'];
		$sql = "SELECT audio FROM test WHERE id =".$audio_id;
		$stid = oci_parse($conn, $sql);
		oci_execute($stid);
		$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
		if (!$row) {
			header('Status: 404 Not Found');
		} else {
			$wav = $row['AUDIO']->load();
			$decoded = base64_decode($wav);
			
			/*
			* This is the trick for convert string base64 to file and download it:
			* - have one dummy file.type
			* - chmod 777 dummy
			* - then rewrite (file_put_contents) the decoded string to this dummy file.type
			* - downloading file points to dummy file.type url
			*/
			file_put_contents('audio.wav', $decoded);
			
			// play the audio
			echo '<audio controls>';
			echo '<source src="audio.wav" type="audio/wav">';
			echo '</audio>';
			
			// download the audio
			echo '<br /><a href="audio.wav" download="'.$audio_id.'.wav">Download audio</a>';
		}
	}
?>

<!-- Search image example -->	
<?php
	if (isset($_POST["search_image"])){
		$image_id=$_POST['images'];
		$sql = "SELECT thumbnail, recoreded_data FROM images WHERE image_id =".$image_id;
		$stid = oci_parse($conn, $sql);
		oci_execute($stid);
		$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
		if (!$row) {
			header('Status: 404 Not Found');
		} else {
			$img = $row['THUMBNAIL']->load();
			
			// display image (no need decoded)
			echo '<img src="data:image/jpg;base64,'.$img.'" />';

			$img = $row['RECOREDED_DATA']->load();
			$decoded = base64_decode($img);
			
			// display image (no need decoded)
			echo '<img src="data:image/jpg;base64,'.$img.'" />';
			
			/*
			* This is the trick for convert string base64 to file and download it:
			* - have one dummy file.type
			* - chmod 777 dummy
			* - then rewrite (file_put_contents) the decoded string to this dummy file.type
			* - downloading file points to dummy file.type url
			*/
			file_put_contents('sensor.jpg', $decoded);
			
			// download the image in full size
			echo '<br /><a href="sensor.jpg" download="'.$image_id.'.jpg">Download image</a>';
		}
	}
?>
		
	</body>
</html>
