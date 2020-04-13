<?php
session_start();

require 'db.php';
require 'functions/write_html.php';
require 'functions/php.php';


if (empty($_SESSION['login'])) {
	redirectToAnotherPage('index.php');
}

if (empty($_SESSION['goal_id'])) {
	redirectToAnotherPage('goals.php');
}


if (isset($_POST['done'])) {
	$goal = R::findOne('goals', '`id` = ?', array($_POST['task']));
	$goal->ready = 'done';
	R::store($goal);
	redirectToAnotherPage('goals.php');
}
if (isset($_POST['delete'])) {
	$goal = R::findOne('goals', '`id` = ?', array($_POST['task']));
	R::trash($goal);
	redirectToAnotherPage('goals.php');
}
if (isset($_POST['freeze'])) {
	$goal = R::findOne('goals', '`id` = ?', array($_POST['task']));
	$goal->ready = 'freezen';
	R::store($goal);
}

if (isset($_POST['create_new_goal_task'])) {
	$_SESSION['parent_goal'] = $_SESSION['goal_id'];
	redirectToAnotherPage('create_new_task.php');
}


$goal = R::findOne('goals', '`id` = ?', array($_SESSION['goal_id']));
$goalID = $goal->id;
$goalName = $goal->name;
$goalDescription = $goal->description;
$goalPriority = $goal->priority;
$goalCreated = $goal->created_date;
$goalFinishDate = $goal->finish_date;
$goalTasksIDs = $goal->tasks_ids;
$goalCategoriesIDs = $goal->categories_ids;
$goalReady = $goal->ready;


if (isset($goalTasksIDs)) {
	$tasksIDs = explode('|', $goalTasksIDs);
}

if ($goalPriority == 'urgent|important') {
	$priority = "\t\t<div>
			<p class=\"cat urgent_and_important\">Важно и срочно!</p>
		</div>\n";
} elseif ($goalPriority == 'urgent|notImportant') {
	$priority = "\t\t<div>
			<p class=\"cat\">Срочно, но не важно</p>
		</div>\n";
} elseif ($goalPriority == 'notUrgent|important') {
	$priority = "\t\t<div>
			<p class=\"cat\">Важно, но не срочно</p>
		</div>\n";
} else {
	$priority = "\t\t<div>
			<p class=\"cat\">Не важно и не срочно</p>
		</div>\n";
}


writeHead('Новая цель');
nav('goals');
print "\t<div class=\"main task_page\">\n";
print "\t\t<div>
			<form action=\"\" method=\"POST\" class=\"createsmtnew\">
				<input type=\"hidden\" name=\"task_id\" value=\"$goalID\">
				<input type=\"submit\" name=\"change_task\" value=\"Изменить\" class=\"createsmtnew create\">
			</form>
		</div>\n";
if ($goalReady == ('freezen' OR 'inProgress')) {
	print "\t\t<div>
			<form action=\"\" method=\"POST\">
				<input type=\"hidden\" name=\"task_id\" value=\"$goalID\">
				<input type=\"submit\" name=\"done\" value=\"✔\" class=\"taskmenu\">
				<input type=\"submit\" name=\"delete\" value=\"✘\" class=\"taskmenu\">
				<input type=\"submit\" name=\"freeze\" value=\"✱\" class=\"taskmenu\">
			</form>
		</div>\n";
} else {
	print "\t\t<div>
			<form action=\"\" method=\"POST\">
				<input type=\"hidden\" name=\"task_id\" value=\"$goalID\">
				<input type=\"submit\" name=\"delete\" value=\"✘\" class=\"taskmenu\">
			</form>
		</div>\n";
}
if ($goalReady == 'freezen') {
	print "\t\t<div>
			<span class=\"label_parameter\">Заморожено</span>
		</div>\n";
} elseif ($goalReady == 'done') {
	print "\t\t<div>
			<span class=\"label_parameter\">Завершено!</span>
		</div>\n";
}
print $priority;
print "\t\t<div>
			<p>
				<span class=\"label_parameter\">Наименование: </span>
				<span class=\"value_parameter\">$goalName</span>
			</p>
		</div>\n";
if ($goalDescription != '' AND $goalDescription != NULL) {
	print "\t\t<div>
			<span class=\"label_parameter\">Описание:</span><br>
			<textarea class=\"description\" disabled>$goalDescription</textarea>
		</div>\n";
}
if (isset($goalFinishDate)) {
	print "\t\t<div>
			<p>
				<span class=\"label_parameter\">Завершить к: </span>
				<span class=\"value_parameter\">$goalFinishDate</span>
			</p>
		</div>\n";
}
if (isset($tasksIDs)) {
	print "\t\t<div>
			<span class=\"label_parameter\">Задачи:</span>
			<form action=\"\" method=\"POST\">
				<input type=\"submit\" name=\"create_new_goal_task\" value=\"+\" class=\"taskmenu\">
			</form>
			<table>
				<tr>
					<th></th>
					<th>Наименование</th>
					<th>Дата</th>
				</tr>\n";
	foreach ($tasksIDs as $taskID) {
		if ($task->start_date) {
			$startAndFinishDate = $task->start_date . '<br>';
		} else {
			$startAndFinishDate = '---<br>';
		}
		if ($task->finish_date) {
			$startAndFinishDate .= $task->finish_date;
		} else {
			$startAndFinishDate .= '---';
		}
    
		$task = R::findOne('tasks', '`id` = ?', array($taskID));
		print "\t\t\t\t<tr>
					<td>
						<form action=\"\" method=\"POST\">
							<input type=\"submit\" name=\"done\" value=\"✔\" class=\"taskmenu ready\">
							<input type=\"submit\" name=\"delete\" value=\"✘\" class=\"taskmenu delete\">
							<input type=\"submit\" name=\"unfreeze\" value=\"✱\" class=\"taskmenu freeze\">
						</form>
					</td>
					<td>$task->name</td>
					<td>$startAndFinishDate</td>
				</tr>\n";
	}
	print "\t\t\t</table>
		</div>\n";
} else {
	print "\t\t<div>
			<span class=\"label_parameter\">Задачи:</span>
			<form action=\"\" method=\"POST\">
				<label class=\"add\">
					<span class=\"label_parameter\">Добавить новую:</span>
					<input type=\"submit\" name=\"create_new_goal_task\" value=\"+\" class=\"taskmenu add\">
				</label>
			</form>
		</div>\n";
}
print "\t</div>\n";
writeFoot();
?>
