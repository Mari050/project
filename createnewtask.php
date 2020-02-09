<?php
	require "db.php";
	require "functions/header.php";
	$name = 'Новая задача';
	head($name);
?>


		<header>
			<nav>
				<ul class="nav">
					<li class="nav"><a href="/" class="active">Главная</a></li>
					<li class="nav"><a href="/goals.php" class="nav">Цели</a></li>
					<li class="nav"><a href="/tasks.php" class="nav">Задачи</a></li>
					<li class="nav"><a href="/notes.php" class="nav">Заметки</a></li>
					<li class="nav"><a href="/trackers.php" class="nav">Трекеры</a></li>
					<li class="nav"><a href="/categories.php" class="nav">Категории</a></li>
					<ul class="account">
						<li class="acc">
							<button class="account"><img src="img/acc.jpg" class="acc"></button>
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

		<form method="POST">
			<input type="text" name="name" placeholder="Наименование задачи"><br>
			<textarea name="description" placeholder="Описание задачи"></textarea><br>
			<input type="datetime" name="start" placeholder="Дата и время начала"><br>
			<input type="datetime" name="finish" placeholder="дата и время завершения"><br>
			<p>
				<input type="checkbox" name="priority" value="1">Срочно<br>
				<input type="checkbox" name="priority" value="2">Важно<br>
			</p>
			<p>
				<input type="radio" name="rerun" value="0">no<br>
				<input type="radio" name="rerun" value="1">1<br>
				<input type="radio" name="rerun" value="2">2<br>
				<input type="radio" name="rerun" value="3">3<br>
				<input type="radio" name="rerun" value="4">4<br>
			</p>
			<input type="datetime" name="remind" placeholder="Напомнить"><br>
			<input type="text" name="name" placeholder="Наименование задачи"><br>
			<input type="text" name="name" placeholder="Наименование задачи"><br>
		</form>
		
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
	</body>
</html>