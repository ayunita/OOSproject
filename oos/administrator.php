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
				<input type="submit" name="submit_sensor" value="Submit">
			</fieldset>
		</form>
	</div>
	<div id="del_sensor_btn">Delete Sensor</div>
	<div id="del_sensor_panel">
	<form action="" method="post">
			<b>Delete sensor</b><br />
			(Deleting a sensor will also delete data corresponding to this sensor) <br />
			Sensor id: <input type="text" name="del_sensor">
			<input type="submit" name="delete_sensor" value="Delete">
	</form>
	</div>
	<h2>User Management</h2>
	<div id="add_user_btn">Add New User</div>
	<div id="add_user_panel">
		<form action="" method="post">
			<fieldset>
				<legend>New User Information:</legend>
				Person id: <input type="text" name="user_id"> <br /> <br />
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
	<?php 
		if (isset($_POST["search_person"]) || isset($_POST["search_user"])){
			echo '<div id="edit_user_panel" style="display:inherit">';
		} else {
			echo '<div id="edit_user_panel">';
		}
	?>
		<form action="" method="post">
			<b>Edit person information</b><br />
			Search person_id: <input type="text" name="edit_personid">
			<input type="submit" name="search_person" value="Search">
		</form>
	<?php 
		if (isset($_POST["search_person"])){
			$edit_personid=$_POST['edit_personid'];
			$sql = "SELECT * FROM persons WHERE person_id=".$edit_personid;
			$stid = oci_parse($conn, $sql );
			$res=oci_execute($stid);

			$isFound = false;
			while(oci_fetch($stid)){
				$person_firstname =oci_result($stid, 'FIRST_NAME');
				$person_lastname =oci_result($stid, 'LAST_NAME');
				$person_address =oci_result($stid, 'ADDRESS');
				$person_email =oci_result($stid, 'EMAIL');
				$person_phone =oci_result($stid, 'PHONE');
				$isFound = true;
			}
			
			if($isFound == false){
				echo 'Person id: '.$edit_personid.' does not exist.';
			} else {
				echo '<br /><form action="" method="post">';
				echo 'Person id: <input type="text" name="dis_personid" value="'.$edit_personid.'" readonly> <br /><br />';
				echo 'Firstname: <input type="text" name="edit_firstname" value="'.$person_firstname.'"> <br /><br />';
				echo 'Lastname: <input type="text" name="edit_lastname" value="'.$person_lastname.'"> <br /><br />';
				echo 'Address: <input type="text" name="edit_address" value="'.$person_address.'"> <br /><br />';
				echo 'Email: <input type="text" name="edit_email" value="'.$person_email.'"> <br /><br />';
				echo 'Phone: <input type="text" name="edit_phone" value="'.$person_phone.'"> <br /><br />';
				echo '<input type="submit" name="edit_person" value="Edit">';
				echo '</form>';
			}
			
			// Free the statement identifier when closing the connection
			oci_free_statement($stid);
			oci_close($conn);
		}
	?>

		<br /><form action="" method="post">
			<b>Edit user information</b><br />
			Search username: <input type="text" name="search_username">
			<input type="submit" name="search_user" value="Search">
		</form>

	<?php 
		if (isset($_POST["search_user"])){
			$search_username=$_POST['search_username'];
			$sql = "SELECT * FROM users WHERE user_name='".$search_username."'";
			$stid = oci_parse($conn, $sql );
			$res=oci_execute($stid);

			$isFound = false;
			while(oci_fetch($stid)){
				$user_username =oci_result($stid, 'USER_NAME');
				$user_password =oci_result($stid, 'PASSWORD');
				$user_role =oci_result($stid, 'ROLE');
				$user_id =oci_result($stid, 'PERSON_ID');
				$isFound = true;
			}
			
			if($isFound == false){
				echo 'Username: '.$search_username.' does not exist.';
			} else {
				echo '<br /><form action="" method="post">';
				echo 'Username: <input type="text" name="edit_username" value="'.$user_username.'" readonly> <br /><br />';
				echo 'Password: <input type="text" name="edit_password" value="'.$user_password.'"> <br /><br />';
				echo 'Role: <input type="text" name="edit_role" value="'.$user_role.'"> <br /><br />';
				echo 'Person id: <input type="text" name="edit_personid" value="'.$user_id.'"> <br /><br />';
				echo '<input type="submit" name="edit_user" value="Edit">';
				echo '</form>';
			}
			
			// Free the statement identifier when closing the connection
			oci_free_statement($stid);
			oci_close($conn);
		}
	?>

	</div>
	<div id="del_user_btn">Delete User</div>
	<div id="del_user_panel">
		<form action="" method="post">
			<b>Delete user</b><br />
			Username: <input type="text" name="del_username">
			<input type="submit" name="delete_user" value="Delete">
		</form>
		<form action="" method="post">
			<b>Delete person</b><br />
			(Deleting a person will also delete the user and data corresponding to this id) <br />
			Person id: <input type="text" name="del_person">
			<input type="submit" name="delete_person" value="Delete">
		</form>
	</div>
	<br />
	
	<span style="color:green">
	<?php
		if (isset($_POST["submit_person"])){
			$firstname=$_POST['firstname'];
			$lastname=$_POST['lastname'];
			$address=$_POST['address'];
			$email=$_POST['email'];
			$phone=$_POST['phone'];
			$person_id=rand(1000, 9999);
			$_SESSION['person_id'] = $person_id;
			$sql = "INSERT INTO persons VALUES (".$person_id.", '".$firstname."', '".$lastname."', '".$address."', '".$email."', '".$phone."')";
			$stid = oci_parse($conn, $sql );
			$res=oci_execute($stid);
			
			echo 'New person is added.<br />';
			echo $person_id.", ".$firstname.", ".$lastname.", ".$address.", ".$email.", ".$phone;
			
			// Free the statement identifier when closing the connection
			oci_free_statement($stid);
			oci_close($conn);
		}
		
		if (isset($_POST["submit_user"])){
			$user_id=$_POST['user_id'];
			$username=$_POST['username'];
			$password=$_POST['password'];
			$roles=$_POST['roles'];
			$date = date("y-m-d");
			$sql = "INSERT INTO users VALUES ('".$username."', '".$password."', '".$roles."', ".$user_id.", TO_DATE('".$date."', 'YY-MM-DD'))";
			$stid = oci_parse($conn, $sql );
			$res=oci_execute($stid);
			
			echo 'New user is added.<br />';
			echo $username.", ".$password.", ".$roles.", ".$user_id.", ".$date;
			
			// Free the statement identifier when closing the connection
			oci_free_statement($stid);
			oci_close($conn);
		}
		
		if (isset($_POST["submit_sensor"])){
			$location=$_POST['location'];
			$types=$_POST['types'];
			$description=$_POST['description'];
			$sensor_id=rand(1000, 9999);
			$sql = "INSERT INTO sensors VALUES (".$sensor_id.", '".$location."', '".$types."', '".$description."')";
			$stid = oci_parse($conn, $sql );
			$res=oci_execute($stid);
			
			echo 'New sensor is added.<br />';
			echo $sensor_id.", ".$location.", ".$types.", ".$description;
			
			// Free the statement identifier when closing the connection
			oci_free_statement($stid);
			oci_close($conn);
		}
		
		if (isset($_POST["delete_user"])){
			$del_username=$_POST['del_username'];
			$sql = "DELETE FROM users WHERE user_name='".$del_username."'";
			$stid = oci_parse($conn, $sql );
			$res=oci_execute($stid);
			
			echo $del_username." is deleted from users.";
			
			// Free the statement identifier when closing the connection
			oci_free_statement($stid);
			oci_close($conn);
		}
		
		if (isset($_POST["delete_person"])){
			$del_person=$_POST['del_person'];
			$sql = "SELECT * FROM users WHERE person_id=".$del_person." AND role='s'";
			$stid = oci_parse($conn, $sql );
			$res=oci_execute($stid);
			
			$isScientist = false;
			while(oci_fetch($stid)){
				$isScientist=true;
			}
			
			if($isScientist == true){
				$sql2 = "DELETE FROM subscriptions WHERE person_id=".$del_person;
				$stid = oci_parse($conn, $sql2 );
				$res=oci_execute($stid);
				echo 'Subscriptions is deleted.<br />';
			}

			$sql3 = "DELETE FROM users WHERE person_id=".$del_person;
			$stid = oci_parse($conn, $sql3 );
			$res=oci_execute($stid);
			
			echo 'User is deleted.<br />';
			
			$sql4 = "DELETE FROM persons WHERE person_id=".$del_person;
			$stid = oci_parse($conn, $sql4 );
			$res=oci_execute($stid);
			
			echo "Person ".$del_person." is deleted.";

			// Free the statement identifier when closing the connection
			oci_free_statement($stid);
			oci_close($conn);
		}
		
		if (isset($_POST["delete_sensor"])){
			$del_sensor=$_POST['del_sensor'];
			$sql = "SELECT * FROM sensors WHERE sensor_id=".$del_sensor;
			$stid = oci_parse($conn, $sql );
			$res=oci_execute($stid);

			while(oci_fetch($stid)){
				$del_type=oci_result($stid, 'SENSOR_TYPE');
			}
			
			if($del_type=='a'){
					$sql = "DELETE FROM audio_recordings WHERE sensor_id=".$del_sensor;
					$stid = oci_parse($conn, $sql );
					$res=oci_execute($stid);
					echo 'Audio recording is deleted.<br />';
			} else if($del_type=='i'){
					$sql = "DELETE FROM images WHERE sensor_id=".$del_sensor;
					$stid = oci_parse($conn, $sql );
					$res=oci_execute($stid);
					echo 'Image is deleted.<br />';
			} else {
					$sql = "DELETE FROM scalar_data WHERE sensor_id=".$del_sensor;
					$stid = oci_parse($conn, $sql );
					$res=oci_execute($stid);
					echo 'Scalar data is deleted.<br />';
			}

			$sql = "DELETE FROM sensors WHERE sensor_id=".$del_sensor;
			$stid = oci_parse($conn, $sql );
			$res=oci_execute($stid);
			echo 'Sensor '.$del_sensor.' is deleted.<br />';

			// Free the statement identifier when closing the connection
			oci_free_statement($stid);
			oci_close($conn);
		}
		
		if (isset($_POST["edit_person"])){
			$dis_personid=$_POST['dis_personid'];
			$edit_firstname=$_POST['edit_firstname'];
			$edit_lastname=$_POST['edit_lastname'];
			$edit_address=$_POST['edit_address'];
			$edit_email=$_POST['edit_email'];
			$edit_phone=$_POST['edit_phone'];

			$sql = "UPDATE persons SET first_name='".$edit_firstname."', last_name='".$edit_lastname.
				"', address='".$edit_address."', email='".$edit_email.
				"', phone='".$edit_phone."' WHERE person_id=".$dis_personid;
			$stid = oci_parse($conn, $sql );
			$res=oci_execute($stid);
			
			echo "UPDATED: ".$dis_personid.", ".$edit_firstname." ".$edit_lastname.", ".$edit_address.", ".$edit_email.", ".$edit_phone;

			// Free the statement identifier when closing the connection
			oci_free_statement($stid);
			oci_close($conn);
		}

		if (isset($_POST["edit_user"])){
			$user_username=$_POST['edit_username'];
			$user_password=$_POST['edit_password'];
			$user_role=$_POST['edit_role'];
			$user_id=$_POST['edit_personid'];
			
			$sql = "UPDATE users SET password='".$user_password."', role='".$user_role.
						"', person_id=".$user_id." WHERE user_name='".$user_username."'";
			
			$stid = oci_parse($conn, $sql );
			$res=oci_execute($stid);
			
			echo "UPDATED: ".$user_username.", ".$user_password." ".$user_role.", ".$user_id;

			// Free the statement identifier when closing the connection
			oci_free_statement($stid);
			oci_close($conn);
		}
	?>
	</span>
		
</body>
</html>
