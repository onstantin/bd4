<?php 	
	session_start();
	error_reporting(E_ALL);
	require_once "config.php";	
	require_once "sql.php";

	if (!isset($_SESSION['isAuthorized']) || $_SESSION['isAuthorized']!=true) {
		header("HTTP/1.0 403 Forribean");
		exit("Доступ запрещен");
	}
	
	if (isset($_POST['description']) && isset($_POST['description'])!="") {	
		
		$description = htmlspecialchars($_POST['description']);		
		$user_id = $_SESSION['user_id'];
		
		$sql = <<<SQL
INSERT INTO `tasks` (`user_id`, `assigned_user_id`, `description`, `date_added`, `is_done`) VALUES ('$user_id', '$user_id', '$description', NOW(), 0)
SQL;
		$pdo->query($sql);
		
		header("Location: tasks.php?msg=add");
		die;
	} 
	
	header("Location: tasks.php");	
	die;
	