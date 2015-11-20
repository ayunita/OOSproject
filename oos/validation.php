<?php
	session_start();
?>
<html>
    <body>
	<?php		
		include ("PHPconnectionDB.php");
		include ("Validation.php");
	   	//establish connection
		$conn=connect();
		
		validate($conn);
	?>
	
    </body>
</html>

