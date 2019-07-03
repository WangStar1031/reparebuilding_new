<?php
	session_start();
	if( !isset( $_SESSION['reparationUserName']))
		header("Location: login.php");
	if( strcasecmp( $_SESSION['reparationUserName'], "admin") == 0){
		header("Location: admin.php");
	} else{
		header("Location: main.php");
	}
?>