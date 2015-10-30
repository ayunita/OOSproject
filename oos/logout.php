<?php
	session_start();
	// remove session variables and destroy the session
	session_unset(); 
	session_destroy();
	header("Location: login.html");
?>