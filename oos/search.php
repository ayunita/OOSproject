<?php
	session_start();
	if($_SESSION['role'] != 's'){
		header("Location: restriction.html");
		exit();
	}	
?>

<html>
<script src="jquery-1.11.3.js" type="text/javascript"></script>
<script src="style.js" type="text/javascript"></script>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Search Records</title>
</head>
<?php
	include ("PHPconnectionDB.php");        
	//establish connection
	$conn=connect();
?>

<body>
	<h1>Search Page</h1>
	<button onclick="location.href = 'logout.php';">Logout</button>
	<br>
	<p>Search Conditions:</p>
	<div id="search_panel">
		<form action="" method="post">
			<fieldset>
				Key Words: <input type="text" name="description"> <br />
				<br /> Sensor Type: <input type="text" name="type"> <br />
				<br /> Sensor Location: <input type="text" name="location"> <br />
				<br /> Date Range: <br>From: <input type="text" name="from">
				To: <input type="text" name="to"> <br />
				<br>
				<button type="reset">Reset</button>
				<input type="submit" name="submit_search" value="Submit">
			</fieldset>
		</form>
	</div>
	
	
	<?php 
		if (isset($_POST["submit_search"])){	
		}
	?>	
	
</body>

</html>
