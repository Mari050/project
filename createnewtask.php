<?php
	//Открытие сессии и проверка на то, что человек уже авторизован
	session_start();
	if (! isset($_SESSION["login"])) {
		//Если сессия закончилась, то редиректнуть пользователя на страницу авторизации
		$url = 'choice.php';
		header("Location: " . $url);
	}

	//Подключаем необходимые файлы
	require "db.php"; //Подключение к БД и библиотеке rb
	require "functions/foreverypage.php"; //Функции с кодом html, который используется на всех страницах

	//Обработчик формы
	$data = $_POST;
	if (isset($data['dologin']))  {

		$errors = array(); //В данном массиве будут хранится ошибки, связанные с неверно введенными (или вообще не введенными) данными

		// !!! Здесь должна быть проверка на наличие ошибок !!!

		//Если ошибок не обнаружено, то передаем данные в бд
		if (isset($errors)) {

			//Преобразование данных, полученных из чекбокса формы, связанного с приоритетом, в строку необходимого формата
			if (isset($data['priority'])) {
				foreach ($data['priority'] as $value) {
					if ($data['priority'] == 1) {
						$priority[0] = 1;
					} else {
						$priority[0] = 0;
					}
					if ($data['priority'] == 2) {
						$priority[1] = 1;
					} else {
						$priority[1] = 0;
					}
				}
				$priority = implode('|', $priority); //На выходе могут быть следующие значения: 0|0, 0|1, 1|0, 1|1
			}

			//Заполняем строку таблицы
			$task = R::dispense('tasks');
			$task->login = $_SESSION['login']; //Логин пользователя

			$task->name = $data['name']; //Наименование задачи. Обязательное поле для заполнения

			if (isset($data['description'])) {
				$task->description = $data['description']; //Описание задачи. Необязательное поле
			}

			$task->created = getdate(); //Дата и время создания задачи. Заполняется автоматически и изменению не подлежит

			if (isset($data['start'])) {
				$task->start = $data['start']; //Дата и время начала задачи. Необязательное поле
			}

			if (isset($data['finish'])) {
				$task->finish = $data['finish']; //Дата и время окончания задачи. Необязательное поле
			}

			if (isset($priority)) {
				$task->priority = $priority; //Приоритет задачи. Необязательное поле
			}

			//if (isset($data['rerun'])) {
			//	$task->rerun = $data['rerun']; //Повтор задачи. Необязательное поле
			//}

			//Здесь должны быть категории и комментрарии
		}
	}

	head('Новая задача'); //Верхняяя часть html. В качестве аргумента передается имя страницы
	print <<<FORM
		<form method="POST">
			<input type="text" name="name" placeholder="Наименование задачи"><br>
			<textarea name="description" placeholder="Описание задачи"></textarea><br>
			<input type="date" name="startd">
			<input type="time" name="startt"><br>
			<input type="date" name="finishd">
			<input type="time" name="finisht"><br>
			<p>
				<input type="checkbox" name="priority" value="1">Срочно<br>
				<input type="checkbox" name="priority" value="2">Важно<br>
			</p>
			<!-- Закомменчено в связи с ооочень размытой реализацией. Сначала необходимо продумать,
				как реализовать повторы, а также набросать категории и комментарии. Пока что их нет,
				но скоро будут
			<p>
				<input type="radio" name="rerun" value="0">no<br>
				<input type="radio" name="rerun" value="1">1<br>
				<input type="radio" name="rerun" value="2">2<br>
				<input type="radio" name="rerun" value="3">3<br>
				<input type="radio" name="rerun" value="4">4<br>
			</p>
			<input type="datetime" name="remind" placeholder="Напомнить"><br>
			<input type="text" name="categories" placeholder="Категории"><br>
			<input type="text" name="comments" placeholder="Комментарии"><br> -->
		</form>
	FORM;
	add();
	foot();
?>