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
	
	if (isset($_POST['login']) && $_POST['login']!="" && isset($_POST['password'])) {
		$login = htmlspecialchars($_POST['login']);
		$password = md5(htmlspecialchars($_POST['password']));
		
		$sql = <<<SQL
SELECT `id`, `login`, `password` FROM `user` WHERE `login`='$login' AND `password`='$password'
SQL;

		$count = 0;
		foreach ($pdo->query($sql) as $row) {
			$count++;
			$user_id = $row['id'];
		}
		echo $count;
		if ($count>=1) {
			$_SESSION['isAuthorized'] = true;
			$_SESSION['login'] = $login;
			$_SESSION['user_id'] = $user_id;
			header("Location: tasks.php?msg=hello");
		} else {
			header("Location: index.php?msg=wrong");
		}	
	} else {
		header("Location: index.php?msg=nl");	
	}
