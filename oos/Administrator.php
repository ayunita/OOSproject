<?php
		/**
		 * This method is used to add a new person into database.
		 */
		
		function addPerson($conn){
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
                
                echo '<META HTTP-EQUIV="Refresh" Content="1">';
			}
		}
        
        /**
		 * This method is used to add a new user into database.
		 */
		
		function addUser($conn){
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
                
                echo '<META HTTP-EQUIV="Refresh" Content="1">';
			}
		}
        
        /**
		 * This method is used to add a new sensor into database.
		 */
		
		function addSensor($conn){
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
                
                echo '<META HTTP-EQUIV="Refresh" Content="1">';
			}
		}
        
        /**
		 * This method is used to remove a user from database.
		 */
		
		function deleteUser($conn){
			if (isset($_POST["delete_user"])){
				$del_username=$_POST['del_username'];
				
				// if scientist, delete the subscription
				$sql = "SELECT person_id FROM users WHERE user_name='".$del_username."' AND role='s'";
				$stid = oci_parse($conn, $sql );
				$res=oci_execute($stid);
				
				$isScientist = false;
				while(oci_fetch($stid)){
					$isScientist=true;
					$person_id = oci_result($stid, 'PERSON_ID');
				}
				
				if($isScientist == true){
					// delete subscription if person is a scientist.
					$sql2 = "DELETE FROM subscriptions WHERE person_id=".$person_id;
					$stid = oci_parse($conn, $sql2 );
					$res=oci_execute($stid);
					echo 'Subscriptions is deleted.<br />';
				}
				
				
				$sql = "DELETE FROM users WHERE user_name='".$del_username."'";
				$stid = oci_parse($conn, $sql );
				$res=oci_execute($stid);
				
				echo $del_username." is deleted from users.";
				
				// Free the statement identifier when closing the connection
				oci_free_statement($stid);
				oci_close($conn);
                
                echo '<META HTTP-EQUIV="Refresh" Content="2">';
			}
		}
        
        		
		/**
		 * This method is used to remove a person from database.
		 */
		
		function deletePerson($conn){
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
					// delete subscription if person is a scientist.
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
                
                echo '<META HTTP-EQUIV="Refresh" Content="2">';
			}
		}
        
        		
		/**
		 * This method is used to delete a sensor from database.
		 */
		
		function deleteSensor($conn){
			if (isset($_POST["delete_sensor"])){
				$del_sensor=$_POST['del_sensor'];
				$sql = "SELECT * FROM sensors WHERE sensor_id=".$del_sensor;
				$stid = oci_parse($conn, $sql );
				$res=oci_execute($stid);
	
				while(oci_fetch($stid)){
					$del_type=oci_result($stid, 'SENSOR_TYPE');
				}
				
				if($del_type=='a'){
						// delete an audio recording
						$sql = "DELETE FROM audio_recordings WHERE sensor_id=".$del_sensor;
						$stid = oci_parse($conn, $sql );
						$res=oci_execute($stid);
						echo 'Audio recording is deleted.<br />';
				} else if($del_type=='i'){
						// delete an image
						$sql = "DELETE FROM images WHERE sensor_id=".$del_sensor;
						$stid = oci_parse($conn, $sql );
						$res=oci_execute($stid);
						echo 'Image is deleted.<br />';
				} else {
						// delete scalar data
						$sql = "DELETE FROM scalar_data WHERE sensor_id=".$del_sensor;
						$stid = oci_parse($conn, $sql );
						$res=oci_execute($stid);
						echo 'Scalar data is deleted.<br />';
						
						// delete subscriptions
						$sql = "DELETE FROM subscriptions WHERE sensor_id=".$del_sensor;
						$stid = oci_parse($conn, $sql );
						$res=oci_execute($stid);
						echo 'Subscriptions with this sensor id is deleted.<br />';
				}
	
				$sql = "DELETE FROM sensors WHERE sensor_id=".$del_sensor;
				$stid = oci_parse($conn, $sql );
				$res=oci_execute($stid);
				echo 'Sensor '.$del_sensor.' is deleted.<br />';
	
				// Free the statement identifier when closing the connection
				oci_free_statement($stid);
				oci_close($conn);
                
                echo '<META HTTP-EQUIV="Refresh" Content="1">';
			}
		}
        
        		
		/**
		 * This method is used to update the person.
		 */
		function updatePerson($conn){
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
                
                echo '<META HTTP-EQUIV="Refresh" Content="1">';
			}
		}
        
        /**
		 * This method is used to update the user.
		 */
		
		function updateUser($conn){
			if (isset($_POST["edit_user"])){
				$prev_id = $_SESSION['search_pid'];
				$prev_role = $_SESSION['search_ur'];
				
				$user_username=$_POST['edit_username'];
				$user_password=$_POST['edit_password'];
				$user_role=$_POST['edit_role'];
				$user_id=$_POST['edit_personid'];	
				
				// check if the role changed (case scientist)
				// if role changed from s -> a/d, remove the subscriptions if exists
				if($prev_role == 's' && $prev_role != $user_role){
					// delete subscription if person is a scientist.
					$sql2 = "DELETE FROM subscriptions WHERE person_id=".$prev_id;
					$stid = oci_parse($conn, $sql2 );
					$res=oci_execute($stid);
					echo 'Subscriptions is deleted.<br />';
				}
		
		/* BUG!!
				// if the person id changed but the role is scientist
				if($prev_role == 's' && $prev_role == $user_role && $prev_id != $user_id){
					$sql = "SELECT * FROM subscriptions WHERE person_id=".$prev_id;
					$stid = oci_parse($conn, $sql );
					$res=oci_execute($stid);
					
					while(oci_fetch($stid)){
						
						$person_id = oci_result($stid, 'PERSON_ID');
						$sensor_id = oci_result($stid, 'SENSOR_ID');
						
						// modify person id in subscriptions
						$sql3 = "INSERT INTO subscriptions VALUES (".$sensor_id.", ".$person_id.")";
						$stid3 = oci_parse($conn, $sql3 );
						$res3=oci_execute($stid3);
						
						oci_free_statement($stid3);
					}
					$sql2 = "DELETE FROM subscriptions WHERE person_id=".$prev_id;
					$stid2 = oci_parse($conn, $sql2 );
					$res=oci_execute($stid2);
					
					oci_free_statement($stid2);
				}
			*/
		
				$sql = "UPDATE users SET password='".$user_password."', role='".$user_role.
							"', person_id=".$user_id." WHERE user_name='".$user_username."'";
				
				$stid = oci_parse($conn, $sql );
				$res=oci_execute($stid);
	
				// if person id = scientist? and change to data curator, remove the person from subscription.
				// sql = "SELECT person id from users"
				// check subscription with that person_id, then remove
				
				
				echo "UPDATED: ".$user_username.", ".$user_password." ".$user_role.", ".$user_id;
	
				// Free the statement identifier when closing the connection
				oci_free_statement($stid);
				oci_close($conn);
                
                //echo '<META HTTP-EQUIV="Refresh" Content="1">';
			}
		}
        
        	
		/**
		 * This method is used to search a user by username.
		 */
		
		function searchUser($conn){
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
				
				$_SESSION['search_pid'] = $user_id;
				$_SESSION['search_ur'] = $user_role;
				
				if($isFound == false){
					echo 'Username: '.$search_username.' does not exist.';
				} else {
					echo '<br /><table class="_form"><form action="" method="post">';
					echo '<tr><td>Username:</td><td><input type="text" name="edit_username" value="'.$user_username.'" readonly></td></tr>';
					echo '<tr><td>Password:</td><td><input type="text" name="edit_password" value="'.$user_password.'"></td></tr>';
					echo '<tr><td>Role:</td><td><input type="text" name="edit_role" value="'.$user_role.'"></td></tr>';
					echo '<tr><td>Person id:</td><td><input type="text" name="edit_personid" value="'.$user_id.'"></td></tr>';
					echo '<tr align="right"><td></td><td><input type="submit" name="edit_user" value="Edit"></td></tr>';
					echo '</form></table>';
				}
				
				// Free the statement identifier when closing the connection
				oci_free_statement($stid);
				oci_close($conn);

				return $search_username;
			}
		}
        
        	
		/**
		 * This method is used to search a person by person id.
		 */
		
		function searchPerson($conn){
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
					echo '<br /><table class="_form"><form action="" method="post">';
					echo '<tr><td>Person id:</td><td><input type="text" name="dis_personid" value="'.$edit_personid.'" readonly></td></tr>';
					echo '<tr><td>Firstname:</td><td><input type="text" name="edit_firstname" value="'.$person_firstname.'"></td></tr>';
					echo '<tr><td>Lastname:</td><td><input type="text" name="edit_lastname" value="'.$person_lastname.'"></td></tr>';
					echo '<tr><td>Address:</td><td><input type="text" name="edit_address" value="'.$person_address.'"></td></tr>';
					echo '<tr><td>Email:</td><td><input type="text" name="edit_email" value="'.$person_email.'"></td></tr>';
					echo '<tr><td>Phone:</td><td><input type="text" name="edit_phone" value="'.$person_phone.'"></td></tr>';
					echo '<tr align="right"><td></td><td><input type="submit" name="edit_person" value="Edit"></td></tr>';
					echo '</form></table>';
				}
				
				// Free the statement identifier when closing the connection
				oci_free_statement($stid);
				oci_close($conn);
                
			}
		}
?>