<?php 	
	session_start();
	error_reporting(E_ALL);
	require_once "config.php";
	require_once "sql.php";
	
	if (!isset($_SESSION['isAuthorized']) || $_SESSION['isAuthorized']!=true) {
		header("HTTP/1.0 403 Forribean");
		exit("Доступ запрещен");
	}
	
	if (isset($_GET['id'])) {	
		$id = $_GET['id'];		
		$sql = <<<SQL
DELETE FROM `tasks` WHERE `id`='$id'
SQL;
		$pdo->query($sql);	
		header("Location: tasks.php?msg=del");
		die;
	} 	
	header("Location: tasks.php");	
	die;
	