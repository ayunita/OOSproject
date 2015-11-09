<?php
	include ("PHPconnectionDB.php");        
	//establish connection
	$connection=connect();

    if (isset($_POST["upload"])){
        $image2 = file_get_contents($_FILES['file']['tmp_name']);
        // encode the stream
        $image = base64_encode($image2);
        
        // change this sql query, this is just an example
        $sql = "INSERT INTO test (id, image) VALUES(1, empty_blob()) RETURNING image INTO :image";
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