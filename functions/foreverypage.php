<?php
	function head($name) {
		print <<<HEADER
			<!DOCTYPE html>
			<html>
			<head>
				<meta charset="utf-8">
				<link rel="stylesheet" type="text/css" href="style.css">
				<title>{$name}</title>
			</head>
			<body>
		HEADER;
	}

	function nav() {
		print <<<NAV
				<header>
					<nav>
						<ul class="nav">
							<li class="nav"><a href="/project/main.php" class="active">Главная</a></li>
							<li class="nav"><a href="/goals.php" class="nav">Цели</a></li>
							<li class="nav"><a href="/tasks.php" class="nav">Задачи</a></li>
							<li class="nav"><a href="/notes.php" class="nav">Заметки</a></li>
							<li class="nav"><a href="/trackers.php" class="nav">Трекеры</a></li>
							<li class="nav"><a href="/project/categories.php" class="nav">Категории</a></li>
							<ul class="account">
								<li class="acc">
									<button class="account"><img src="img/acc.jpg" class="acc"></button>
									<ul class="submenuacc">
										<li class="account"><a href="#">Настройки</a></li>
										<li class="account"><a href="#">Помощь</a></li>
										<li class="account"><a href="../exit.php">Выход</a></li>
									</ul>
								</li>
					
							</ul>
						</ul>
				
					</nav>
				</header>
		NAV;
	}

	function foot() {
		print <<<FOOT
				</body>
			</html>
		FOOT;
	}

	function add() {
		print <<<ADD
				<ul class="addul">
					<li class="addli">
						<ul class="submenu">
							<li><p class="addtext">Добавить...</p></li>
							<li><p><a href="#" class="add">категорию</a></p></li>
							<li><p><a href="#" class="add">трекер</a></p></li>
							<li><p><a href="#" class="add">заметку</a></p></li>
							<li><p><a href="#" class="add">цель</a></p></li>
							<li><p><a href="createnewtask.php" class="add">задачу</a></p></li>
						</ul>
						<button class="addbutton"><img src="img/plus.png"></button>
					</li>
				</ul>
		ADD;
	}
?>