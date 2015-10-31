<?php
	session_start();
	if($_SESSION['role'] != 's'{
		header("Location: restriction.html");
		exit();
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

