<?php 	
	session_start();
	error_reporting(E_ALL);
	require_once "config.php";
	require_once "sql.php";

	if (!isset($_SESSION['isAuthorized']) || $_SESSION['isAuthorized']!=true) {
		header("HTTP/1.0 403 Forribean");
		exit("Доступ запрещен");
	}
	
	if (isset($_GET['task_id']) && isset($_POST['assigned_user_id'])) {
		$id = $_GET['task_id'];		
		$assigned_user_id = $_POST['assigned_user_id'];	
		$sql = <<<SQL
UPDATE `tasks` SET `assigned_user_id`='$assigned_user_id' WHERE `id`='$id'
SQL;
		$pdo->query($sql);	
		header("Location: tasks.php?msg=edit");
		die;
	}
	header("Location: tasks.php");	
	die;
	