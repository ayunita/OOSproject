<?php
	session_start();
	include ("PHPconnectionDB.php");        
   	//establish connection
	$conn=connect();
	echo $_SESSION['role']."<br/>";
	echo $_SESSION['password'];
	foreach($_SESSION as $key => $value)
	{
		echo $key.'='.$value;
	}

	
?>
<html>

<body>
<button onclick="location.href = 'logout.php';">Logout</button>
</body>
</html>
