<?php
session_start();


require 'db.php'; 
require 'functions/write_html.php';
require 'functions/php.php';


if ( empty($_SESSION['login'])) {
	redirectToAnotherPage('index.php');
}


if (isset($_POST['newtask']))  {
	$errors = array();
	if (empty($_POST['name'])) {
		$errors = 'Не введено название задачи!';
	}

	if (empty($errors)) {
		$priority = [];
		if (isset($_POST['priority'])) {
			foreach ($_POST['priority'] as $value) {
				if ($_POST['priority'][0]) {
					$priority[0] = 'urgent';
				} else {
					$priority[0] = 'notUrgent';
				}
				if ($_POST['priority'][1]) {
					$priority[1] = 'important';
				} else {
					$priority[1] = 'notImportant';
				}
			}
			$priority = implode('|', $priority);
		}

		$categories = '';
		if (isset($_POST['category'])) {
			foreach ($_POST['category'] as $value) {
				$categories = implode('|', $_POST['category']);
			}
		}

		
		$task = R::dispense('tasks');
			$task->login = $_SESSION['login'];
			$task->name = $_POST['name']; 
			if ($_POST['description']) {
				$task->description = $_POST['description'];
			}
			$task->created = date('Y-m-d', time());
			if ($_POST['start_date']) {
				$task->start_date = $_POST['start_date'];
			}
			if ($_POST['start_time']) {
				$task->start_time = $_POST['start_time'];
			}
			if ($_POST['finish_date']) {
				$task->finish_date = $_POST['finish_date'];
			}
			if ($_POST['finish_time']) {
				$task->start_time = $_POST['finish_time'];
			}
			if ($priority) {
				$task->priority = $priority;
			}
			if ($categories) {
				$task->categories = $categories;
			}
		R::store($task);
		redirectToAnotherPage('tasks.php');
	}
}


writeHead('Новая задача');
nav('tasks');
print '<p class="createsmtnew">Новая задача</p>
	';
print '<form action="" method="POST" class="createsmtnew">
	<div>
		<input type="submit" name="newtask" value="Создать" class="createsmtnew create">
	</div>
	<div>
		<p><input type="text" name="name" placeholder="Наименование задачи" class="createsmtnew name"><br></p>
		<p><textarea name="description" placeholder="Описание задачи" class="createsmtnew description"></textarea></p>
	</div>
	<div>
		<p><input type="date" name="start_date" class="createsmtnew date">
		<input type="time" name="start_time" class="createsmtnew time"></p>
	</div>
	<div>
		<p><input type="date" name="finish_date" class="createsmtnew date">
		<input type="time" name="finish_time" class="createsmtnew time"></p>
	</div>
	<div class="priority">
		<label class="priority"><input type="checkbox" name="priority[]" value="1" class="createsmtnew priority"><span class="priority">Срочно</span></label>
		<label class="priority"><input type="checkbox" name="priority[]" value="2" class="createsmtnew priority"><span class="priority">Важно</span></label>
	</div>
	<div><p class="cat">Категории:</p>
	';
$cat = R::find('categories', 'login LIKE ?', array($_SESSION['login']));
$i = 0;
foreach ($cat as $c => $value) { 
	print "        <p class=\"category\"><label class=\"category\"><input type=\"checkbox\" name=\"category[]\" value=\"$value->id\" class=\"createsmtnew category\"><span class=\"category\">$value->name</span></label></p><br>
	";
};
print "    </div>
	</form>
";
writeFoot();
?>