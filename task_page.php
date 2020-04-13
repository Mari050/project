<?php
session_start();
	

require 'db.php';
require 'functions/write_html.php';
require 'functions/php.php';
    

if (empty($_SESSION['login'])) {
	redirectToAnotherPage('index.php');
}

if (empty($_SESSION['task_id'])) {
	redirectToAnotherPage('tasks.php');
}


if (isset($_POST['done'])) {
	$task = R::findOne('tasks', '`id` = ?', array($_POST['task_id']));
	$task->ready = 'done';
	R::store($task);
	redirectToAnotherPage('main.php');
}
if (isset($_POST['delete'])) {
	$task = R::findOne('tasks', '`id` = ?', array($_POST['task_id']));
	if ($task->goal_id) {
		$goal = R::findOne('goals', '`id` = ?', array($task->goal_id));
		$goalTasks = $goals->tasks_ids;
		$tasksIDs = explode('|', $goalTasks);
		$deletedTask = array_search($task->id, $tasksIDs);
		unset($tasksIDs[$deletedTask]);
		sort($tasksIDs);
		$tasksIDs = implode('|', $tasksIDs);
		$goal->tasks_ids = $tasksIDs;
		R::store($goal);
	}
	R::trash($task);
	redirectToAnotherPage('main.php');
}
if (isset($_POST['freeze'])) {
	$task = R::findOne('tasks', '`id` = ?', array($_POST['task_id']));
	if ($task->ready == 'freezen') {
		$task->ready = 'inProgress';
	} else {
		$task->ready = 'freezen';
	}
	R::store($task);
}

if (isset($_POST['change_task'])) {
	redirectToAnotherPage('change_task.php');
}

if (isset($_POST['create_comment'])) {
	$error = NULL;
	if (trim($_POST['comment'] == '')) {
		$error = 'Вы не ввели текст комментария!';
	}

	if (! $error) {
		$comment = R::dispense('comments');
		$comment->login = $_SESSION['login'];
		$comment->created_date = date('Y-m-d', time());
		$comment->created_time = date('H:i', time());
		$comment->value = trim($_POST['comment']);
		R::store($comment);

		$commentID = $comment->id;

		$task = R::findOne('tasks', '`id` = ?', array($_SESSION['task_id']));
		if ($task->comments_ids) {
			$task->comments_ids .= '|' . $commentID;
		} else {
			$task->comments_ids = $commentID;
		}
		R::store($task);
	}
}


$task = R::findOne('tasks', '`id` = ?', array($_SESSION['task_id']));
$taskID = $task->id;
$taskName = $task->name;
$taskDescription = $task->description;
$taskGoalID = $task->goal_id;
$taskCreated = $task->created;
$taskStartDate = $task->start_date;
$taskStartTime = $task->start_time;
$taskFinishDate = $task->finish_date;
$taskFinishTime = $task->finish_time;
$taskRepeat = $task->repeat;
$taskPriority = $task->priority;
$taskCategories = $task->categories;
$taskComments = $task->comments_ids;
$taskReady = $task->ready;


if (isset($taskGoalID)) {
	$goal = R::findOne('goals', '`id` = ?', array($taskGoalID));
}

if (isset($taskRepeat)) {
	if ($taskRepeat == 'd') {
		$repeat = 'Каждый день';
	} elseif ($taskRepeat == 'w') {
		$repeat = 'Каждую неделю';
	} elseif ($taskRepeat == 'm') {
		$repeat = 'Каждый месяц';
	}
}

if ($taskPriority == 'urgent|important') {
	$urgent = 'checked';
	$important = 'checked';
} elseif ($taskPriority == 'notUrgent|important') {
	$urgent = NULL;
	$important = 'checked';
} elseif ($taskPriority == 'urgent|notImportant') {
	$urgent = 'checked';
	$important = NULL;
} else {
	$urgent = NULL;
	$important = NULL;
}

$categories = [];
$categoriesIDs = explode('|', $taskCategories);
$numberCategories = count($categoriesIDs);
foreach ($categoriesIDs as $categoryID) {
	$categories += R::find('categories', '`id` LIKE ?', array($categoryID));
}

$comments = [];
if ($taskComments) {
	$commentsIDs = array_reverse(explode('|', $taskComments));
	foreach ($commentsIDs as $commentID) {
		$comments += R::find('comments', '`id` = ?', array($commentID));
	}
}


if ($urgent and $important) {
	$priority = "\t\t<div>
			<p class=\"cat urgent_and_important\">Важно и срочно!</p>
		</div>\n";
} elseif ($urgent) {
	$priority = "\t\t<div>
			<p class=\"cat\">Срочно, но не важно</p>
		</div>\n";
} elseif ($important) {
	$priority = "\t\t<div>
			<p class=\"cat\">Важно, но не срочно</p>
		</div>\n";
} else {
	$priority = "\t\t<div>
			<p class=\"cat\">Не важно и не срочно</p>
		</div>\n";
}


writeHead("$taskName");
nav('tasks');
print "\t<div class=\"main task_page\">\n";
print "\t\t<div>
			<form action=\"\" method=\"POST\" class=\"createsmtnew\">
				<input type=\"hidden\" name=\"task_id\" value=\"$taskID\">
				<input type=\"submit\" name=\"change_task\" value=\"Изменить\" class=\"createsmtnew create\">
			</form>
		</div>\n";
if ($taskReady == ('freezen' OR 'inProgress')) {
	print "\t\t<div>
			<form action=\"\" method=\"POST\">
				<input type=\"hidden\" name=\"task_id\" value=\"$taskID\">
				<input type=\"submit\" name=\"done\" value=\"✔\" class=\"taskmenu\">
				<input type=\"submit\" name=\"delete\" value=\"✘\" class=\"taskmenu\">
				<input type=\"submit\" name=\"freeze\" value=\"✱\" class=\"taskmenu\">
			</form>
		</div>\n";
} else {
    print "\t\t<div>
			<form action=\"\" method=\"POST\">
				<input type=\"hidden\" name=\"task_id\" value=\"$taskID\">
				<input type=\"submit\" name=\"delete\" value=\"✘\" class=\"taskmenu\">
			</form>
		</div>\n";
}
if ($taskReady == 'freezen') {
	print "\t\t<div>
			<span class=\"label_parameter\">Заморожено</span>
		</div>\n";
} elseif ($taskReady == 'done') {
	print "\t\t<div>
			<span class=\"label_parameter\">Завершено!</span>
		</div>\n";
}
print $priority;
if (isset($goalName)) {
	print "\t\t<div>
			<span class=\"label_parameter\">Цель: </span>
			<span class=\"goal_name\">$goalName</span>
		</div>\n";
	/*
	 * После span добавить выпадающую менюшку:
	 * Удалить цель (т.е. из данной цели убрать id задачи, а из задачи удалить id цели, тем самым разделив их)
	 * Поменять цель (т.е. перетащить эту задачу в другую цель)
	 * Но это желательно. Пока что можно оставить так, как есть
	 */
}
print "\t\t<div>
			<p>
				<span class=\"label_parameter\">Наименование: </span>
				<span class=\"value_parameter\">$taskName</span>
			</p>
		</div>\n";
if ($taskDescription != '') {
	print "\t\t<div>
			<span class=\"label_parameter\">Описание:</span><br>
			<textarea class=\"description\" disabled>$taskDescription</textarea>
		</div>\n";
}
if (isset($taskStartDate)) {
	print "\t\t<div>\n";
	if (isset($taskStartTime)) {
		print "\t\t\t<span class=\"label_parameter\">Дата и время начала:</span>
				<div class=\"value_parameter\">
					<span class=\"value_parameter\">$taskStartDate</span>
					<span> </span>
					<span class=\"value_parameter\">$taskStartTime</span>\n";
	} else {
		print "\t\t\t<span class=\"label_parameter\">Дата начала: </span><br>
				<div class=\"value_parameter\">
					<span class=\"value_parameter\">$taskStartDate</span>\n";
	}
	print "\t\t\t</div>
		</div>\n";
} elseif (isset($taskStartTime)) {
	print "\t\t<div>
			<span class=\"label_parameter\">Время начала: </span><br>
			<div class=\"value_parameter\">
				<span class=\"value_parameter\">$taskStartTime</span>
			</div> 
		</div>\n";
}
if (isset($taskFinishDate)) {
	print "\t\t<div>\n";
	if (isset($taskFinishTime)) {
		print "\t\t\t<span class=\"label_parameter\">Дата и время завршения: </span><br>
				<div class=\"value_parameter\">
					<span class=\"value_parameter\">$taskFinishDate</span>
					<span> </span>
					<span class=\"value_parameter\">$taskFinishTime</span>\n";
	} else {
		print "\t\t\t<span class=\"label_parameter\">Дата завершения: </span><br>
				<div class=\"value_parameter\">
					<span class=\"value_parameter\">$taskFinishDate</span>\n";
	}
	print "\t\t\t</div>
				</div>\n";
} elseif (isset($taskFinishTime)) {
	print "\t\t<div>
			<span class=\"label_parameter\">Время завершения: </span><br>
			<div class=\"value_parameter\">
				<span class=\"value_parameter\">$taskFinishTime</span>
			</div>
		</div>\n";
}
if (isset($repeat)) {
	print "\t\t<div>
			<p>
				<span class=\"label_parameter\">$repeat</span>
			</p>
		</div>\n";
}
if ($categories) {
	print "\t\t<div>
			<p><span class=\"label_parameter\">Категории:</span></p>
			<ul>\n";
	foreach ($categories as $cat => $value) {
		print "\t\t\t\t<li class=\"categories\"><span class=\"value_parameter\">$value->name</span></li>\n";
	}
	print "\t\t\t</ul>
				</div>\n";
}
print "\t\t<div>
			<span class=\"label_parameter\">Дата создания: </span><br>
			<div class=\"value_parameter\">
				<span class=\"value_parameter\">$taskCreated</span>
			</div>
		</div>\n";
print "\t\t<div>
			<span class=\"label_parameter\">Комментарии:</span><br>
			<div class=\"comment\">
				<form action=\"\" method=\"POST\">
					<label>
						<span class=\"comment\">Добавить новый:</span>
						<input type=\"submit\" name=\"create_comment\" value=\"✔\" class=\"create_comment\">
						<textarea name=\"comment\" class=\"description\"></textarea>
					</label>
				</form>
			</div>\n";
if (isset($error)) {
	print "\t\t<p class=\"error\">$error</p>\n";
}
if (isset($comments)) {
	foreach ($comments as $comment => $value) {
		print "\t\t<div>
			<span class=\"comment\">$value->created_date</span>
			<span> </span>
			<span class=\"comment\">$value->created_time</span><br>
			<textarea class=\"description comment\" disabled>$value->value</textarea>
		</div>\n";
	}
}
print "\t</div>\n";
writeFoot();
?>
