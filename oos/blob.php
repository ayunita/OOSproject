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
        $image2 = file_get_contents($_FILES['file']['tmp_name']);
        // encode the stream
        $image = base64_encode($image2);
        
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

<!--
for uploading audio:
change "image" to "audio"
-->