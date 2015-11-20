<?php
	session_start();
	if($_SESSION['role'] != 's'){
		header("Location: restriction.html");
		exit();
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<script src="jquery-1.11.3.js" type="text/javascript"></script>
<script src="style.js" type="text/javascript"></script>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Scientist</title>
</head>
<?php
	include ("PHPconnectionDB.php");        
	//establish connection
	$conn=connect();
?>

<body>
	<button onclick="location.href = 'subscribe.php';">Sensor Subscription</button>
	<br><br>
	<button onclick="location.href = 'search.php';">Search Records</button>
	<br><br>	
	<button onclick="location.href = 'analysis.php';">Data Analysis</button>
	<br><br>
	<button onclick="location.href = 'logout.php';">Logout</button>


</body>
</html>
