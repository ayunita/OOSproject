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

    $sql = "SELECT audio FROM test WHERE id = 8869";
    $stid = oci_parse($conn, $sql);
    oci_execute($stid);
    $row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
    if (!$row) {
        header('Status: 404 Not Found');
    } else {
        $wav = $row['AUDIO']->load();
        // header("Content-type: audio/wav");
        
        // decode the stream
	$wav_source = base64_decode($wav);
	
    }
?>
<html>
<head><title>Load</title></head>
<body>
<!-- .wav player works in Chrome -->
<audio controls src="data:audio/wav;base64,<?php print $wav; ?>"/>
</body>
</html>