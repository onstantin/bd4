<?php 	
	session_start();
	error_reporting(E_ALL);
	require_once "config.php";
	require_once "functions.php";
	require_once "sql.php";
	
	if (isset($_SESSION['isAuthorized']) && $_SESSION['isAuthorized']==true) {
		header("Location: tasks.php");
		die;
	}
	
	if (isset($_POST['login']) && $_POST['login']!="" && $_POST['password']!="") {
		$login = htmlspecialchars($_POST['login']);
		$password = md5(htmlspecialchars($_POST['password']));
		
		$sql = <<<SQL
SELECT `login` FROM `user` WHERE `login`='$login'
SQL;

		$count = 0;
		foreach ($pdo->query($sql) as $row) {
			$count++;
		}
	
		if ($count>=1) {
			header("Location: registration.php?msg=na");
			die;
		} else {
			$sql = <<<SQL
INSERT INTO `user` (`login`, `password`) VALUES ('$login', '$password')
SQL;

			$pdo->query($sql);	
			header("Location: index.php?msg=welcome");
			die;
		}	
	} else {
		header("Location: registration.php?msg=nl");
		die;
	}
