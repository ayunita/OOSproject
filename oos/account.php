<?php
	include ("PHPconnectionDB.php");
	//establish connection
	$conn=connect();
?>
<html>
    <head><title>Modify Account</title></head>
    <body>
        <h1>Modify Account</h1>
        <form action="" method="post">
			<fieldset>
				<legend>Account:</legend>
				Username: <input type="text" name="username">
				<input type="submit" name="search" value="Search"><br />
                <?php
                    showAccount($conn);
                    updateUser($conn);
                ?>
			</fieldset>
		</form>
    </body>
</html>

<?php

    /**
     * This method is used to show the account information, such as
     * password, name, and address.
     */
    function showAccount($conn){
        if(isset($_POST["search"])){
            $username = $_POST['username'];
            $sql = "SELECT u.user_name, u.password, p.first_name, p.last_name, p.address
                    FROM users u, persons p WHERE u.person_id = p.person_id
                    AND u.user_name = '".$username."'";
            $stid = oci_parse($conn, $sql );
            $res=oci_execute($stid);
        
            $isFound = false;
            while(oci_fetch($stid)){
                $user_firstname =oci_result($stid, 'FIRST_NAME');
                $user_lastname =oci_result($stid, 'LAST_NAME');
                $user_address =oci_result($stid, 'ADDRESS');
                $user_password = oci_result($stid, 'PASSWORD');
                $isFound = true;
            }
            
            if($isFound == false){
                echo '<br />Username: '.$username.' does not exist.';
            } else {
                echo '<br /><form action="" method="post">';
                echo 'Username: <input type="text" name="dis_username" value="'.$username.'" readonly> <br /><br />';
                echo 'Password: <input type="text" name="edit_password" value="'.$user_password.'"> <br /><br />';
                echo 'Firstname: <input type="text" name="edit_firstname" value="'.$user_firstname.'"> <br /><br />';
                echo 'Lastname: <input type="text" name="edit_lastname" value="'.$user_lastname.'"> <br /><br />';
                echo 'Address: <input type="text" name="edit_address" value="'.$user_address.'"> <br /><br />';
                echo '<input type="submit" name="modify" value="Modify">';
                echo '</form>';
            }
                    
            // Free the statement identifier when closing the connection
            oci_free_statement($stid);
            oci_close($conn);
            
        }
    }
    
    
    /**
	* This method is used to update the user.
	*/
		
	function updateUser($conn){
		if (isset($_POST["modify"])){
            $username=$_POST['dis_username'];
            $new_password=$_POST['edit_password'];
            $new_fname=$_POST['edit_firstname'];
            $new_lname=$_POST['edit_lastname'];
            $new_address=$_POST['edit_address'];
            
            // update password
            $sql ="UPDATE users SET password = '".$new_password."' WHERE user_name = '".$username."'";
                    
            $stid = oci_parse($conn, $sql );
            $res=oci_execute($stid);
            
            // Free the statement identifier when closing the connection
            oci_free_statement($stid);
            oci_close($conn);
            
            /*
            // update name and address
            $sql = "UPDATE (SELECT u.user_name, u.password, p.first_name, p.last_name, p.address
                    FROM users u, persons p WHERE u.person_id = p.person_id
                    AND u.user_name = '".$username."') 
                    SET first_name = '".$new_fname."', last_name = '".$new_lname."', address = '".$new_address."'";
                    
            $stid = oci_parse($conn, $sql );
            $res=oci_execute($stid);
            
            // Free the statement identifier when closing the connection
            oci_free_statement($stid);
            oci_close($conn);
            */
		}
	}
        
?>