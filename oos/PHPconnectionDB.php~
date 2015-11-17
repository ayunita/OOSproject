<?php
function connect(){
	$conn = oci_connect('chen1', '117130Abc');
	if (!$conn) {
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
		
	return $conn;
}
?>
