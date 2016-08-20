<?php 	
	session_start();
	error_reporting(E_ALL);
	require_once "config.php";
	require_once "functions.php";
	require_once "sql.php";

	if (!isset($_SESSION['isAuthorized']) || $_SESSION['isAuthorized']!=true) {
		header("HTTP/1.0 403 Forribean");
		exit("Доступ запрещен");
	}
	
	if (isset($_POST['sort'])) {
		$_SESSION['sort'] = $_POST['sort'];
		$order = $_SESSION['sort'];
	}
	elseif (isset($_SESSION['sort'])) {
		$order = $_SESSION['sort'];
	}
	else {
		$order = "date_added";
	}
	
	if ($order=="is_done") {
		$selected = array("", "", "selected");
	} 
	elseif ($order=="description") {
		$selected = array("", "selected", "");
	}
	else {
		$selected = array("selected", "", "");
	}

	$user_id = $_SESSION['user_id'];
	
	$sql_my_tasks = <<<SQL
SELECT `t`.`id`, `t`.`user_id`, `u`.`login`, `t`.`assigned_user_id`, `l`.`login` AS `assigned_login`, `t`.`description`, `t`.`date_added`, `t`.`is_done`
FROM `tasks` AS `t` 
JOIN `user` AS `u` ON `u`.`id`=`t`.`user_id`
JOIN `user` AS `l` ON `l`.`id`=`t`.`assigned_user_id`
WHERE `t`.`user_id`='$user_id'
ORDER BY $order ASC
SQL;

	$sql_tasks_for_me = <<<SQL
SELECT `t`.`id`, `t`.`user_id`, `u`.`login`, `t`.`assigned_user_id`, `l`.`login` AS `assigned_login`, `t`.`description`, `t`.`date_added`, `t`.`is_done`
FROM `tasks` AS `t` 
JOIN `user` AS `u` ON `u`.`id`=`t`.`user_id`
JOIN `user` AS `l` ON `l`.`id`=`t`.`assigned_user_id`
WHERE `t`.`assigned_user_id`='$user_id' AND `t`.`user_id`!='$user_id'
ORDER BY $order ASC
SQL;


	$sql_user_list = "SELECT `id`,`login` FROM `user`";	
	$options = "";	
	foreach ($pdo->query($sql_user_list) as $row) {
		$options .= "<option value=\"".$row['id']."\">".$row['login']."</option>".PHP_EOL;
	}	
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Список дел</title>
	<meta charset="utf-8">
	<link type="text/css" href="style.css" rel="stylesheet" charset="utf-8"> 
</head>
<body> 
	<h1>Список дел</h1>
	<h2>Задачи, созданные мной</h2>
<?php	
	if (isset($_GET['msg'])) {
		echo postMsg($_GET['msg']);
	} 
	else {
		echo postMsg();
	}	
?>
	
	<form action="add.php" method="post">
		<input type="text" name="description" placeholder="Напишите задачу" required>
		<button>Добавить задачу</button>
	</form>
	<form action="" method="post">
		<select name="sort">
            <option value="date_added" <?= $selected['0'] ?> >Дате создания</option>
            <option value="description" <?= $selected['1'] ?> >Описанию</option>				
            <option value="is_done" <?= $selected['2'] ?> >Статусу</option>
		</select>
		<button>Отсортировать</button>
	</form>	
	<table>	
	<tr><th>Задача</th><th>Дата</th><th>Статус</th><th>Действия</th><th>Кто делает</th><th>Кто создал</th><th>Передать другому</th></tr>
<?php	
	foreach ($pdo->query($sql_my_tasks) as $row) 
	{
		$id = $row['id'];
		$date = $row['date_added'];
		$description = $row['description'];
		
		if($row['login']==$_SESSION['login']) {
			$login = "<b>Я</b>";
		} 
		else {
			$login = $row['login'];
		}		
		
		if($row['assigned_login']==$_SESSION['login']) {
			$assigned_login = "<b>Я</b>";
		} 
		else {
			$assigned_login = $row['assigned_login'];
		}
		
		if ($row['is_done']==1) {
			$is_done = "<span>Выполнено</span>";
		} 
		elseif ($row['is_done']!=1 && $row['assigned_login']!=$_SESSION['login']) {
			$is_done = "<span>В процессе</span>";
		}
		else {
			$is_done = isDoneLink($id);
		}
		
		if (isset($_GET['edit']) && $_GET['edit']==$row['id']) {
			$task = editForm($id, $description);
			$edit = "<span>Изменить</span>";
		} 
		else {
			$task = $description;
			$edit = editLink($id);
		}
?>
		<tr>
			<td><?= $task ?></td>
			<td><?= $date ?></td>
			<td><?= $is_done ?></td>
			<td><?= $edit ?> <?= delLink($id) ?></td>
			<td><?= $assigned_login ?></td>
			<td><?= $login ?></td>
			<td><?= transferForm($id, $options); ?></td>
		</tr>
<?php	}	?>	
	</table>
	
	<h2>Задачи, назначенные мне другими</h2>
	<table>	
	<tr><th>Задача</th><th>Дата</th><th>Статус</th><th>Действия</th><th>Кто делает</th><th>Кто создал</th></tr>
<?php	
	foreach ($pdo->query($sql_tasks_for_me) as $row) 
	{
		$id = $row['id'];
		$date = $row['date_added'];
		$description = $row['description'];
		
		if($row['login']==$_SESSION['login']) {
			$login = "<b>Я</b>";
		} 
		else {
			$login = $row['login'];
		}		
		
		if($row['assigned_login']==$_SESSION['login']) {
			$assigned_login = "<b>Я</b>";
		} 
		else {
			$assigned_login = $row['assigned_login'];
		}
		
		if ($row['is_done']==1) {
			$is_done = "<span>Выполнено</span>";
		} 
		else {
			$is_done = isDoneLink($id);
		}
		
		if (isset($_GET['edit']) && $_GET['edit']==$row['id']) {
			$task = editForm($id, $description);
			$edit = "<span>Изменить</span>";
		} 
		else {
			$task = $description;
			$edit = editLink($id);
		}
?>
		<tr>
			<td><?= $task ?></td>
			<td><?= $date ?></td>
			<td><?= $is_done ?></td>
			<td><?= $edit ?> <?= delLink($id) ?></td>
			<td><?= $assigned_login ?></td>
			<td><?= $login ?></td>
		</tr>
<?php	}	?>		
	</table>	
	<p><a href="logout.php"><b>Выйти</b></a></p>
</body> 	
</html>