<?php 	
	session_start();
	error_reporting(E_ALL);
	require_once "config.php";
	require_once "sql.php";

	if (!isset($_SESSION['isAuthorized']) || $_SESSION['isAuthorized']!=true) {
		header("HTTP/1.0 403 Forribean");
		exit("������ ��������");
	}
	
	if (isset($_GET['id']) && isset($_POST['description']) && $_POST['description']!="") {
		$description = $_POST['description'];
		$id = $_GET['id'];		
		$sql = <<<SQL
UPDATE `tasks` SET `description`='$description' WHERE `id`='$id'
SQL;
		$pdo->query($sql);	
		header("Location: tasks.php?msg=edit");
		die;
	}
	header("Location: tasks.php");	
	die;
	