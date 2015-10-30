<?php
	session_start();
?>
<html>
    <body>
	<?php		
		include ("PHPconnectionDB.php");        
	   	//establish connection
		$conn=connect();
           	
		if(isset($_POST['validate'])){        	
			$username=$_POST['username'];            		
			$password=$_POST['password'];      
		}	
		

		//sql command
		$sql = "SELECT * FROM users WHERE user_name = '".$username."' AND password = '".$password."'";
		   
		//Prepare sql using conn and returns the statement identifier
		$stid = oci_parse($conn, $sql );
		
		oci_define_by_name($stid, 'ROLE', $role);

		//Execute a statement returned from oci_parse()
		$res=oci_execute($stid); 
		 
		//if error, retrieve the error using the oci_error() function & output an error
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
		} 

		$isFound = false;

		while (oci_fetch($stid)) {
		    $isFound = true;
		}

		if($isFound == false){
			echo 'Username and password do not match.';
		} else {
			echo 'Login successful...';
			$_SESSION['role'] = $role;
			if($role == 'a'){
				header("Location: administrator.php"); 
			} else if ($role == 's'){
				header("Location: scientist.php"); 
			} else {
				header("Location: datacurator.php"); 
			}
			
		}

	
		// Free the statement identifier when closing the connection
		oci_free_statement($stid);
		oci_close($conn);
	?>
	
    </body>
</html>

