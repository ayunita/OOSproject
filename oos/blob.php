<?php

    /*
     *  taken from:
     *  http://php.net/manual/en/function.base64-encode.php
     *  http://php.net/manual/en/function.oci-new-descriptor.php
     *  modified by yunita
     */

	include ("PHPconnectionDB.php");        
	//establish connection
	$connection=connect();

    if (isset($_POST["upload"])){
		$thumb = resizeImage($_FILES['file']['tmp_name'],300, 287);
		
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
		
		$image2= file_get_contents($_FILES['file']['tmp_name']);
        // encode the stream
        $image = base64_encode($image_data);
        
        // change this sql query, this is just an example
		$id = rand(1000, 9999);
        $sql = "INSERT INTO test2 (id, image) VALUES(".$id.", empty_blob()) RETURNING image INTO :image";
        $result = oci_parse($connection, $sql);
        $blob = oci_new_descriptor($connection, OCI_D_LOB);
        oci_bind_by_name($result, ":image", $blob, -1, OCI_B_BLOB);
        oci_execute($result, OCI_DEFAULT) or die ("Unable to execute query");
        
        if(!$blob->save($image)) {
            oci_rollback($connection);
        }
        else {
            oci_commit($connection);
        }
        
        oci_free_statement($result);
        $blob->free();
    }
?>

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

<!--
for uploading audio:
change "image" to "audio"
-->