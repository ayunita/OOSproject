<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
				Sensor id: <input type="text" name="sensor_analysis_id"> <br /> <br />
				Location: <input type = "text" name = "location_analysis"> <br /> <br />
				Date: <input type="text" name="date_analysis"> <br /><br />
				<button type="reset">Reset</button>
				<input type="submit" name="upload_analysis" value="Submit">
			</fieldset>
		</form>
	</div>
</body>
</html>