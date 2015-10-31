<?php
	session_start();
	if($_SESSION['role'] != 'a'){
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
<title>Administrator</title>
</head>
<?php
	include ("PHPconnectionDB.php");        
	//establish connection
	$conn=connect();
?>
<body>
	<h1>Administrator Page</h1>
	<button onclick="location.href = 'logout.php';">Logout</button>
	<h2>Sensor Management</h2>
	<div id="add_sensor_btn">Add New Sensor</div>
	<div id="add_sensor_panel">
		<form action="" method="post">
			<fieldset>
				<legend>Sensor Information:</legend>
				Location: <input type="text" name="location"> <br /> <br />
				Type: <input type="radio" name="types" value="a"> Audio</input> <input
					type="radio" name="types" value="i"> Image</input> <input
					type="radio" name="types" value="s"> Scalar</input> <br /> <br />
				Description: <input type="text" name="description"> <br />
				<br />
				<button type="reset">Reset</button>
				<input type="submit" name="submit" value="Submit">
			</fieldset>
		</form>
	</div>
	<div id="del_sensor_btn">Delete Sensor</div>
	<div id="del_sensor_panel">Delete sensor</div>
	<h2>User Management</h2>
	<div id="add_user_btn">Add New User</div>
	<div id="add_user_panel">
		<form action="" method="post">
			<fieldset>
				<legend>New User Information:</legend>
				Username: <input type="text" name="username"> <br /> <br />
				Password: <input type="text" name="password"> <br /> <br />
				Role: <input type="radio" name="roles" value="a">
				Administrator</input> <input type="radio" name="roles" value="d">
				Data Curator</input> <input type="radio" name="roles" value="s">
				Scientist</input> <br /> <br />
				<button type="reset">Reset</button>
				<input type="submit" name="submit_user" value="Submit">
			</fieldset>
		</form>		
		<form action="" method="post">
			<fieldset>
				<legend>New Person Information:</legend>
				Firstname: <input type="text" name="firstname"> <br />
				<br /> Lastname: <input type="text" name="lastname"> <br />
				<br /> Address: <input type="text" name="address"> <br />
				<br /> Email: <input type="text" name="email"> <br /> <br />
				Phone: <input type="text" name="phone"> <br /> <br />
				<button type="reset">Reset</button>
				<input type="submit" name="submit_person" value="Submit">
			</fieldset>
		</form>
	</div>
	<div id="edit_user_btn">Manage User</div>
	<div id="edit_user_panel">Edit user</div>
	<div id="del_user_btn">Delete User</div>
	<div id="del_user_panel">Delete user</div>
	<br />
	
	<span style="color:green">
	<?php
		if (isset($_POST["submit_person"])){
			$firstname=$_POST['firstname'];
			$lastname=$_POST['lastname'];
			$address=$_POST['address'];
			$email=$_POST['email'];
			$phone=$_POST['phone'];
			$person_id=rand(10000000, 99999999);
			$_SESSION['person_id'] = $person_id;
			$sql = "INSERT INTO persons VALUES (".$person_id.", '".$firstname."', '".$lastname."', '".$address."', '".$email."', '".$phone."')";
			$stid = oci_parse($conn, $sql );
			$res=oci_execute($stid);
			echo 'New person is added.';
			
			// Free the statement identifier when closing the connection
			oci_free_statement($stid);
			oci_close($conn);
		}
		

	?>
	</span>
		
</body>
</html>