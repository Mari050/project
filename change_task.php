<?php
session_start();
	

require 'db.php';
require 'functions/write_html.php';
require 'functions/php.php';
    

if (empty($_SESSION['login'])) {
    redirectToAnotherPage('index.php');
}
if (empty($_SESSION['task_id'])) {
    redirectToAnotherPage('main.php');
}


if (isset($_POST['save_changes'])) {
    $errors = [];
	if (empty($_POST['name'])) {
		$errors[0] = 'Вы попытались удалить название задачи!<br>Не надо так =/';
	}


	if (empty($errors)) {
		if (isset($_POST['priority'])) {
			if ($_POST['priority'][0] == 'urgent' AND $_POST['priority'][1] == 'important') {
				$priority = 'urgent|important';
			} elseif ($_POST['priority'][0] == 'urgent') {
				$priority = 'urgent|notImportant';
			} elseif ($_POST['priority'][0] == 'important') {
				$priority = 'notUrgent|important';
			}
		} else {
			$priority = 'notUrgent|notImportant';
		}

		$categories = '';
		if (isset($_POST['category'])) {
			$categories = implode('|', $_POST['category']);
		}

		$task = R::findOne('tasks', '`id` = ?', array($_SESSION['task_id']));
			$task->name = $_POST['name'];
			if ($_POST['description']) {
				$task->description = $_POST['description'];
			}
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
				$task->finish_time = $_POST['finish_time'];
			}
			$task->priority = $priority;
			if ($categories) {
				$task->categories = $categories;
			}
		R::store($task);
		redirectToAnotherPage('task_page.php');
    } else {
		$error = array_shift($errors);
	}
}


$task = R::findOne('tasks', '`id` = ?', array($_SESSION['task_id']));
    $taskID = $task->id;
    $taskName = $task->name;
    $taskDescriprion = $task->description;
    $taskCreated = $task->created;
    $taskStartDate = $task->start_date;
    $taskStartTime = $task->start_time;
    $taskFinishDate = $task->finish_date;
    $taskFinishTime = $task->finish_time;
    $taskPriority = $task->priority;
	$taskCategories = $task->categories;
	$taskGoalID = $task->goal_id;
	$taskReady = $task->ready;


if ($taskPriority == 'urgent|important') {
    $urgent = 'checked';
    $important = 'checked';
} elseif ($taskPriority == 'notUrgent|important') {
    $urgent = '';
    $important = 'checked';
} elseif ($taskPriority == 'urgent|notImportant') {
    $urgent = 'checked';
    $important = '';
} else {
    $urgent = '';
    $important = '';
}

$categories = [];
$categoriesIDs = explode('|', $taskCategories);
$numberCategories = count($categoriesIDs);
$categories += R::find('categories', '`login` = ?', array($_SESSION['login']));

if (isset($taskGoalID)) {
	$goal = R::findOne('goals', '`id` = ?', array($taskGoalID));
		$goalName = $goal->name;
}


writeHead("$taskName");
nav('tasks');
if (isset($error)) {
	print "<p class=\"error\">$error</p>\n";
}
print "\t<form action=\"\" method=\"POST\" class=\"createsmtnew\">\n";
print "\t\t<div>
        	<input type=\"submit\" name=\"save_changes\" value=\"Сохранить изменения\" class=\"createsmtnew create\">
        	<input type=\"hidden\" name=\"task\" value=\"$taskID\">
		</div>\n";
if (isset($goalName)) {
	print "\t\t<div>
			<span class=\"label_parameter\">Цель: </span>
			<span class=\"goal_name\">$goalName</span>
		</div>\n";
	/*
	 * После span добавить выпадающую менюшку:
	 * Удалить цель (т.е. из данной цели убрать id задачи, а из задачи удалить id цели, тем самым разделив их)
	 * Поменять цель (т.е. перетащить эту задачу в другую цель)
	 */
}
print "\t\t<div>
			<p>
				<input type=\"text\" name=\"name\" placeholder=\"Наименование задачи\" value=\"$taskName\" class=\"createsmtnew name\">
			</p>
			<p>
				<textarea name=\"description\" placeholder=\"Описание задачи\" class=\"createsmtnew description\">$taskDescriprion</textarea>
			</p>
		</div>\n";
print "\t\t<div>
			<label>
            	<span class=\"cat\">Дата и время начала:</span><br>
				<input type=\"date\" name=\"start_date\" value=\"$taskStartDate\" class=\"createsmtnew date\">
				<input type=\"time\" name=\"start_time\" value=\"$taskStartTime\" class=\"createsmtnew time\">
			</label>
		</div><br>\n";
print "\t\t<div>
			<lable>
				<span class=\"cat\">Дата и время завершения:</span><br>
				<input type=\"date\" name=\"finish_date\" value=\"$taskFinishDate\" class=\"createsmtnew date\">
				<input type=\"time\" name=\"finish_time\" value=\"$taskFinishTime\" class=\"createsmtnew time\">
			</lable>
		</div><br>\n";
print "\t\t<div>
			<label>
            	<span class=\"cat\">Приоритет:</span><br>
				<div class=\"value_parameter\">
					<label class=\"priority\">
						<input type=\"checkbox\" name=\"priority[]\" value=\"urgent\" class=\"createsmtnew priority\" $urgent>
						<span class=\"priority\">Срочно</span>
					</label>
					<label class=\"priority\">
						<input type=\"checkbox\" name=\"priority[]\" value=\"important\" class=\"createsmtnew priority\" $important>
						<span class=\"priority\">Важно</span>
					</label>
				</div>
			</label>
		</div><br>\n";
print "\t\t<div>
			<label>
				<span class=\"cat\">Категории:</span><br>
				<div class=\"near_cat\">\n";
foreach ($categories as $cat => $value) {
	$isActive =  in_array($value->id, $categoriesIDs);
	if ($isActive) {
		print "\t\t\t\t\t<div>
						<label>
							<input type=\"checkbox\" name=\"category[]\" value=\"$value->id\" class=\"createsmtnew category\" checked>
							<span class=\"category\">$value->name</span>
						</label>
					</div><br>\n";
    } else {
		print "\t\t\t\t\t<div>
						<label>
							<input type=\"checkbox\" name=\"category[]\" value=\"$value->id\" class=\"createsmtnew category\">
							<span class=\"category\">$value->name</span>
						</label>
					</div><br>\n";
    }
}
print "\t\t\t\t</div>
			</label>
		</div>
	</form>\n";
writeFoot();
?>