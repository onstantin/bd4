<?php 	
	function editForm($id, $description) {
		return <<<HTML
	<form action="edit.php?id=$id" method="post" class="editform">
		<input type="text" name="description" value="$description">
		<button>OK</button>
	</form>
HTML;
	}
	
	function editLink($id) {
		return <<<HTML
		<a href="tasks.php?edit=$id">Изменить</a>
HTML;
	}
	
	function delLink($id) {
		return <<<HTML
		<a href="del.php?id=$id">Удалить</a>
HTML;
	}

	function isDoneLink($id) {
		return <<<HTML
		<a href="is_done.php?id=$id">Выполнить</a>
HTML;
	}	
	
	function postMsg($msg = false) {
		switch ($msg) {
		case "add":
			return "<p class=\"add\">Задача добавлена</p>";
			break;
		case "del":
			return "<p class=\"del\">Задача удалена</p>";
			break;	
		case "edit":
			return "<p class=\"edit\">Задача изменена</p>";
			break;		
		case "na":
			return "<p class=\"del\">Логин занят</p>";
			break;	
		case "nl":
			return "<p class=\"del\">Нужно ввести имя</p>";
			break;			
		case "welcome":
			return "<p class=\"add\">Вы зарегистрированы, теперь можно войти!</p>";
			break;		
		case "hello":
			return "<p class=\"add\">Привет!</p>";
			break;		
		case "wrong":
			return "<p class=\"del\">Неверный логин или пароль!</p>";
			break;				
		default:
			return "";
			break;	
		}
	}
	
	function transferForm($id, $options) {
		return <<<HTML
<form action="transfer.php?task_id=$id" method="post">
<select name="assigned_user_id">			
$options
</select>
<button>Назначить</button>
</form>		
HTML;

	}
	