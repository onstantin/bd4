<?php 	
	session_start();
	error_reporting(E_ALL);

	session_unset();
	session_destroy();
	header("Location: index.php");	
	die;
