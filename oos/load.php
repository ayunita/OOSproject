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

    $sql = "SELECT image FROM test WHERE id = 9";
    $stid = oci_parse($conn, $sql);
    oci_execute($stid);
    $row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
    if (!$row) {
        header('Status: 404 Not Found');
    } else {
        $img = $row['IMAGE']->load();
        header("Content-type: image/jpeg");
        
        // decode the stream
        print base64_decode($img);
    }

?>