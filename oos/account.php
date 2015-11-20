<?php
	include ("PHPconnectionDB.php");
	include ("Account.php");
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
				Search Username: <input type="text" name="username">
				<input type="submit" name="search" value="Search"><br />
                <?php
                    showAccount($conn);
                    updateUser($conn);
                ?>
			</fieldset>
		</form>
		<a href="login.html">Back</a>
    </body>
</html>