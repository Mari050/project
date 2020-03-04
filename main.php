<?php
	session_start();
	if (! isset($_SESSION["login"])) {
		$url = 'choice.php';
		header("Location: " . $url);
	}

	require "db.php";
	require "functions/foreverypage.php";

	
	# Этот обработчик формы отвечает за нажатие по имени задачи
	if (isset($_POST['taskname']))  {
		$_SESSION['task'] = $_POST['task'];
		$url = 'taskpage.php';
		header("Location: " . $url);
    };
	# Этот обработчик формы отвечает за нажатие кнопки "V" (готово)
	if (isset($_POST['V'])) {
		$task = R::findOne('tasks', '`login` = ? AND `name` = ?', array($_SESSION['login'], $_POST['task']));
		$task->ready = '1';
		R::store($task);
	};
	# Этот обработчик формы отвечает за нажатие кнопки "D" (удалить)
	if (isset($_POST['D'])) {
		$task = R::findOne('tasks', '`login` = ? AND `name` = ?', array($_SESSION['login'], $_POST['task']));
		R::trash($task);
	};
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Главная</title>
</head>
<body>
	<header>
		<nav>
			<ul class="nav">
				<li class="nav"><a href="/project/main.php" class="active">Главная</a></li>
				<li class="nav"><a href="/goals.php" class="nav">Цели</a></li>
				<li class="nav"><a href="/project/tasks.php" class="nav">Задачи</a></li>
				<li class="nav"><a href="/notes.php" class="nav">Заметки</a></li>
				<li class="nav"><a href="/trackers.php" class="nav">Трекеры</a></li>
				<li class="nav"><a href="/project/categories.php" class="nav">Категории</a></li>
				<ul class="account">
					<li class="acc">
						<button class="account"></button>
						<ul class="submenuacc">
							<li class="account"><a href="#">Настройки</a></li>
							<li class="account"><a href="#">Помощь</a></li>
							<li class="account"><a href="exit.php">Выход</a></li>
						</ul>
					</li>
				</ul>
			</ul>
		</nav>
	</header>
	<table class="tasks">
		<tr class="tasks">
			<th class="tasks">Menu</th>
			<th class="tasks">Name</th>
            <th class="tasks">SFd</th>
            <th class="tasks">SFt</th>
            <th class="tasks">Priority</th>
		</tr>
		<?php 
		$login = $_SESSION['login'];
		$tasks = R::find('tasks', 'login LIKE ?', array($login)); # Получаем данные от БД
		foreach ($tasks as $c => $value) { # Выводим полученные данные в виде таблицы
			if ($value->ready != '1') { # Выводим только те задачи, которые не отмечены, как сделанные
				print "<tr>
			<td>
				<form action=\"\" method=\"POST\">
					<p><input type=\"hidden\" name=\"task\" value=\"$value->name\">
					<input type=\"submit\" name=\"V\" value=\"V\">
					<input type=\"submit\" name=\"D\" value=\"D\"></p>
				</form>
			</td>
			<td>
				<form action=\"\" method=\"POST\">
					<p><input type=\"submit\" name=\"taskname\" value=\"$value->name\"></p>
				</form>
			</td>
			<td>
				<p><?php if (isset($value->startd)) {print $value->startd;} else {print '---';}; ?><br>
				<?php if (isset($value->finishd)) {print $value->finishd;} else {print '---';}: ?></p>
			</td>
			<td>
				<p>$value->startt<br><p>$value->finisht</p>
			</td>
			<td>
				<!-- Возникла проблема с преобразованием строки в массив=(
				<?php
				$value = $value->export();
				print $value;
				";//$imp = explode('|', $value['priority']);
				//if ($imp[0] == '1' and $imp[1] == '1') {
				//	print 'Срочно и важно';
				//} elseif ($imp[0] == '0' and $imp[1] == '1') {
				//	print 'Не срочно, но важно';
				//} elseif ($imp[0] == '1' and $imp[1] == '0') {
				//	print 'Срочно, но не важно';
				//} else {
				//	print 'Не срочно и не важно';
				//};
				print "?>-->
			</td>
		</tr><br>
		";
			};
		};
		?>
	</table>
	<ul class="addul">
		<li class="addli">
			<ul class="submenu">
				<li><p class="addtext">Добавить...</p></li>
				<li><p><a href="createnewcategory.php" class="add">категорию</a></p></li>
				<li><p><a href="#" class="add">трекер</a></p></li>
				<li><p><a href="#" class="add">заметку</a></p></li>
				<li><p><a href="#" class="add">цель</a></p></li>
				<li><p><a href="createnewtask.php" class="add">задачу</a></p></li>
			</ul>
			<button class="addbutton"></button>
		</li>
	</ul>
</body>
</html>