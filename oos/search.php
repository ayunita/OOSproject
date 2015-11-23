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
	include ("Search.php");
	//establish connection
	$conn=connect();

?>

<body>
	<h1>Search Page</h1>
	<button onclick="location.href = 'logout.php';">Logout</button>
	<button onclick="location.href = 'scientist.php';">Back</button>
	<br>
	<p>Search Conditions:</p>
	<div id="search_panel">
		<form action="" method="post">
			<fieldset>
				Key Words: <input type="text" name="description"> <br />
				<br /> Sensor Type: <select name="type" value="">Sensor Type</option>
				<?php
					echo "<option value=>All Sensor Types</option>";
					echo "<option value=a>Audio</option>"; 
					echo "<option value=i>Image</option>"; 
					echo "<option value=s>Scalar</option>"; 
				?>
				</select><br />
		
				<br /> Sensor Location: <input type="text" name="location"> <br />
				<br /> Date Range: <br>From: <input type="text" name="from">
				To: <input type="text" name="to"> <br />
				<br>
				<button type="reset">Reset</button>
				<input type="submit" name="submit_search" value="Search">
			</fieldset>
		</form>
	</div>
	
	
	<br /><br />
	<a href="http://consort.cs.ualberta.ca/~yunita/OOSproject/document/help.html#search-module">Help</a>

	<?php
		search($conn);
	?>	
	
</body>

</html>
