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
	include ("Administrator.php");
	//establish connection
	$conn=connect();
?>
<body>
	<h1>Administrator Page</h1>
	<button onclick="location.href = 'logout.php';">Logout</button>
	<button onclick="location.href = 'administrator.php';">Refresh</button>
	<h2>Sensor Management</h2>
	<div id="add_sensor_btn">Add New Sensor</div>
	<div id="add_sensor_panel">
		<form action="" method="post">
			<fieldset>
				<legend>Sensor Information:</legend>
				<table class = "_form">
				<tr><td>Location:</td><td><input type="text" name="location"></td></tr>
				<tr><td>Type:</td><td><input type="radio" name="types" value="a"> Audio</input>
							<br /><input type="radio" name="types" value="i"> Image</input>
							<br /><input type="radio" name="types" value="s"> Scalar</input></td></tr>
				<tr><td>Description:</td><td><input type="text" name="description"></td></tr>
				<tr align="right"><td></td><td><button type="reset">Reset</button>
				<input type="submit" name="submit_sensor" value="Submit"></td></tr>
				</table>
			</fieldset>
		</form>
	</div>
	<div id="del_sensor_btn">Delete Sensor</div>
	<div id="del_sensor_panel">
	<form action="" method="post">
			<b>Delete sensor</b><br />
			(Deleting a sensor will also delete data corresponding to this sensor) <br />
			Sensor id: <select name="del_sensor">
				
			<?php
				$sql = "SELECT sensor_id FROM sensors";
				$stid = oci_parse($conn, $sql );
				$res = oci_execute($stid);
					
				while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
					foreach ($row as $id) {
						echo "<option value=$id>$id</option>"; 
					}
				}
			?>
			</select>
			<input type="submit" name="delete_sensor" value="Delete">
	</form>
	</div>
	<h2>User Management</h2>
	<div id="add_user_btn">Add New User</div>
	<div id="add_user_panel">
		<form action="" method="post">
			<fieldset>
				<legend>New User Information:</legend>
				<table class= "_form">
					<tr><td>Person id:</td><td><select name="user_id">
					
					<?php
						$sql = "SELECT person_id FROM persons";
						$stid = oci_parse($conn, $sql );
						$res = oci_execute($stid);
						
						while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
							foreach ($row as $id) {
								echo "<option value=$id>$id</option>"; 
							}
						}
					?>
					
					</select></td></tr>
					<tr><td>Username:</td><td><input type="text" name="username"></td></tr>
					<tr><td>Password:</td><td><input type="text" name="password"></td></tr>
					<tr><td>Role:</td><td><input type="radio" name="roles" value="a">Administrator
								<br /></input> <input type="radio" name="roles" value="d">Data Curator
								<br /></input> <input type="radio" name="roles" value="s">Scientist
								</input></td></tr>
					<tr align="right"><td></td><td><button type="reset">Reset</button>
					<input type="submit" name="submit_user" value="Submit"></td></tr>
				</table>
			</fieldset>
		</form>		
		<form action="" method="post">
			<fieldset>
				<legend>New Person Information:</legend>
				<table class = "_form">
					<tr><td>Firstname:</td><td><input type="text" name="firstname"></td></tr>
					<tr><td>Lastname:</td><td><input type="text" name="lastname"></td></tr>
					<tr><td>Address:</td><td><input type="text" name="address"></td></tr>
					<tr><td>Email:</td><td><input type="text" name="email"></td></tr>
					<tr><td>Phone:</td><td><input type="text" name="phone"></td></tr>
					<tr align="right"><td></td><td><button type="reset">Reset</button>
					<input type="submit" name="submit_person" value="Submit"></td></tr>
				</table>
			</fieldset>
		</form>
	</div>
	<div id="edit_user_btn">Manage User</div>
	
	<?php 
		if (isset($_POST["search_person"]) || isset($_POST["search_user"])){
			echo '<div id="edit_user_panel" style="display:inherit">';
		} else {
			echo '<div id="edit_user_panel">';
		}
	?>
	
		<form action="" method="post">
			<b>Edit person information</b><br />			
			Search person_id: <select name="edit_personid">
			
			<?php
				$sql = "SELECT person_id FROM persons";
				$stid = oci_parse($conn, $sql );
				$res = oci_execute($stid);
					
				while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
					foreach ($row as $id) {
						echo "<option value=".$id.">$id</option>"; 
					}
				}
			?>
			</select>
			<input type="submit" name="search_person" value="Search">
		</form>
		
	<?php
		searchPerson($conn);
	?>
		<br /><form action="" method="post">
			<b>Edit user information</b><br />
			Search username: <select name="search_username">
			
			<?php
				$sql= "SELECT user_name FROM users";
				$stid = oci_parse($conn, $sql );
				$res = oci_execute($stid);
					
				while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
					foreach ($row as $name) {
						echo "<option value='".$name."'>$name</option>"; 
					}
				}
			?>
			</select>
			<input type="submit" name="search_user" value="Search">
		</form>
		
	<?php
		searchUser($conn);
	?>
	
	</div>
	<div id="del_user_btn">Delete User</div>
	<div id="del_user_panel">
		<form action="" method="post">
			<b>Delete user</b><br />
			Username: <select name="del_username">
			
			<?php
				$sql = "SELECT user_name FROM users";
				$stid = oci_parse($conn, $sql );
				$res = oci_execute($stid);
					
				while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
					foreach ($row as $name) {
						echo "<option value='".$name."'>$name</option>"; 
					}
				}
			?>
			</select>
			<input type="submit" name="delete_user" value="Delete">
			
		</form>
		<form action="" method="post">
			<b>Delete person</b><br />
			(Deleting a person will also delete the user and data corresponding to this id) <br />
			Person_id: <select name="del_person">
			
			<?php
				$sql = "SELECT person_id FROM persons";
				$stid = oci_parse($conn, $sql );
				$res = oci_execute($stid);
					
				while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
					foreach ($row as $id) {
						echo "<option value=".$id.">$id</option>"; 
					}
				}
			?>
			</select>
			<input type="submit" name="delete_person" value="Delete">
		</form>
	</div>
	<br />
	<span style="color:green">
	
	<?php
		addPerson($conn);
		addUser($conn);
		addSensor($conn);		
		deleteUser($conn);
		deletePerson($conn);
		deleteSensor($conn);
		updatePerson($conn);
		updateUser($conn);		
	?>
	
	</span>
	<br /><br />
	<a href="http://consort.cs.ualberta.ca/~yunita/OOSproject/document/help.html#sensor-and-user-management-module">Help</a>
		
</body>
</html>
