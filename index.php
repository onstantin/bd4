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
?>

<!DOCTYPE html>
<html>
<head>
	<title>Войти</title>
	<meta charset="utf-8">
	<link type="text/css" href="style.css" rel="stylesheet" charset="utf-8"> 
</head>
<body> 
	<h1>Войти или <a href="registration.php">зарегистрироваться</a></h1>
<?php	
	if (isset($_GET['msg'])) {
		echo postMsg($_GET['msg']);
	} 
	else {
		echo postMsg();
	}	
?>		
	<form action="login.php" method="post">
		<input type="text" placeholder="Введите имя" name="login" required>
		<input type="password" placeholder="Введите пароль" name="password" required>
		<button>OK</button>
	</form>	
</body> 	
</html>