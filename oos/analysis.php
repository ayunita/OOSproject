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
<title>Data Analysis</title>
</head>

<?php
	include ("PHPconnectionDB.php");
	include ("Analysis.php");
	//establish connection
	$conn=connect();
?>
<html>
<body>
	<h1>Data Analysis Page</h1>
	<button onclick="location.href = 'logout.php';">Logout</button>
	<h2>Data Analysis</h2>
	<div id="data_analysis_btn">Data Analysis</div>
	<div id="data_analysis_panel">
		<form action="" method="post">
			<fieldset>
				<legend>Scalar Information:</legend>
				<button type = "submit" name = "yearly" value = "yearly">Yearly</button>
				<button type = "submit" name = "quarterly" value = "quarterly">Quarterly</button>
				<button type = "submit" name = "monthly" value = "monthly">Monthly</button>
				<button type = "submit" name = "weekly" value = "weekly">Weekly</button>
				<button type = "submit" name = "daily" value = "daily">Daily</button>
			</fieldset>
		</form>
	</div>
	<br /><a href="http://consort.cs.ualberta.ca/~yunita/OOSproject/document/help.html#data-analysis-module">Help</a>
	<br /><br />
</body>
</html>
<?php
	generateReport($conn);
?>