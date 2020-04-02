<?php
session_start();
	

require 'db.php';
require 'functions/write_html.php';
require 'functions/php.php';


if (empty($_SESSION['login'])) {
	redirectToAnotherPage('index.php');
}

	
if (isset($_POST['taskname']))  {
	$_SESSION['task_id'] = $_POST['task'];
	redirectToAnotherPage('task_page.php');
}

if (isset($_POST['done'])) {
	$task = R::findOne('tasks', '`login` = ? AND `id` = ?', array($_SESSION['login'], $_POST['task']));
		$task->ready = 'done';
	R::store($task);
}
if (isset($_POST['delete'])) {
	$task = R::findOne('tasks', '`login` = ? AND `id` = ?', array($_SESSION['login'], $_POST['task']));
	R::trash($task);
}
if (isset($_POST['freeze'])) {
	$task = R::findOne('tasks', '`login` = ? AND `id` = ?', array($_SESSION['login'], $_POST['task']));
	$task->ready = 'freezen';
	R::store($task);
}

if (isset($_POST['change_date'])) {
	$selectedDate = $_POST['selected_date'];
	$_SESSION['date'] = $selectedDate;
} elseif (isset($_SESSION['date'])) {
	$selectedDate = $_SESSION['date'];
} else {
	$selectedDate = NULL;
}

if (isset($_POST['unfreeze'])) {
	$task = R::findOne('tasks', '`login` = ? AND `id` = ?', array($_SESSION['login'], $_POST['task']));
	$task->ready = 'inProgress';
	R::store($task);
}

$neccesaryTasks = '';
if (isset($_POST['time_task'])) {
	$selectedDate = $_POST['hidden_date'];
	$neccesaryTasks = 'with_time';
} elseif (isset($_POST['not_time_task'])) {
	$selectedDate = $_POST['hidden_date'];
	$neccesaryTasks = 'without_time';
} elseif (isset($_POST['freezen'])) {
	$selectedDate = $_POST['hidden_date'];
	$neccesaryTasks = 'freezen';
} else {
	if (isset($_SESSION['tasks'])) {
		$neccesaryTasks = $_SESSION['tasks'];
	} else {
		$neccesaryTasks = 'all';
	}
}

if ($neccesaryTasks == 'with_time') {
	$withTimeClass = 'active';
	$withoutTimeClass = '';
	$freezenClass = '';
} elseif ($neccesaryTasks == 'without_time') {
	$withTimeClass = '';
	$withoutTimeClass = 'active';
	$freezenClass = '';
} else {
	$withTimeClass = '';
	$withoutTimeClass = '';
	$freezenClass = 'active';
}


$_SESSION['tasks'] = $neccesaryTasks;


if ($selectedDate != '*') {
	if ($neccesaryTasks == 'with_time') {
		$tasks = R::find('tasks', '`login` = ? AND `start_date` = ? AND `start_time` IS NOT NULL AND `ready` = ? ORDER BY `start_time`', array($_SESSION['login'], $selectedDate, 'inProgress'));
		$pageName = NULL;
	} elseif ($neccesaryTasks == 'without_time') {
		$tasks = R::find('tasks', '`login` = ? AND `start_date` = ? AND `start_time` IS NULL AND `priority` = ? AND `ready` = ?', array($_SESSION['login'], $selectedDate, 'urgent|important', 'inProgress'));
		$tasks += R::find('tasks', '`login` = ? AND `start_date` = ? AND `start_time` IS NULL AND `priority` = ? AND `ready` = ?', array($_SESSION['login'], $selectedDate, 'urgent|notImportant', 'inProgress'));
		$tasks += R::find('tasks', '`login` = ? AND `start_date` = ? AND `start_time` IS NULL AND `priority` = ? AND `ready` = ?', array($_SESSION['login'], $selectedDate, 'notUrgent|important', 'inProgress'));
		$tasks += R::find('tasks', '`login` = ? AND `start_date` = ? AND `start_time` IS NULL AND `priority` = ? AND `ready` = ?', array($_SESSION['login'], $selectedDate, 'notUrgent|notImportant', 'freinProgressezen'));
		$pageName = NULL;
	} elseif ($neccesaryTasks == 'freezen') {
		$tasks = R::find('tasks', '`login` = ? AND `ready` = ?', array($_SESSION['login'], 'freezen'));
		$pageName = 'Замороженные задачи';
	} else {
		$tasks = R::find('tasks', '`login` = ? AND `ready` = ?', array($_SESSION['login'], 'inProgress'));
		$pageName = NULL;
	}
} else {
	if ($neccesaryTasks == 'with_time') {
		$tasks = R::find('tasks', '`login` = ? AND `start_time` IS NOT NULL AND `ready` = ? ORDER BY `start_time`', array($_SESSION['login'], 'inProgress'));
		$pageName = NULL;
	} elseif ($neccesaryTasks == 'without_time') {
		$tasks = R::find('tasks', '`login` = ? AND  `start_time` IS NULL AND `priority` = ? AND `ready` = ?', array($_SESSION['login'], 'urgent|important', 'inProgress'));
		$tasks += R::find('tasks', '`login` = ? AND `start_time` IS NULL AND `priority` = ? AND `ready` = ?', array($_SESSION['login'], 'urgent|notImportant', 'inProgress'));
		$tasks += R::find('tasks', '`login` = ? AND `start_time` IS NULL AND `priority` = ? AND `ready` = ?', array($_SESSION['login'], 'notUrgent|important', 'inProgress'));
		$tasks += R::find('tasks', '`login` = ? AND `start_time` IS NULL AND `priority` = ? AND `ready` = ?', array($_SESSION['login'], 'notUrgent|notImportant', 'inProgress'));
		$pageName = NULL;
	} elseif ($neccesaryTasks == 'freezen') {
		$tasks = R::find('tasks', '`login` = ? AND `ready` = ?', array($_SESSION['login'], 'freezen'));
		$pageName = 'Замороженные задачи';
	} else {
		$tasks = R::find('tasks', '`login` = ? AND `ready` = ?', array($_SESSION['login'], 'inProgress'));
		$pageName = NULL;
	}
}

$tasksForOutput = [];
foreach ($tasks as $task) {
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

	if ($task->start_time) {
		$startAndFinishTime = $task->start_time . '<br>';
	} else {
		$startAndFinishTime = '---<br>';
	}
	if ($task->finish_time) {
		$startAndFinishTime .= $task->finish_time;
	} else {
		$startAndFinishTime .= '---';
	}

	$priority = '';
	if ($task->priority) {
		if ($task->priority == 'notUrgent|notImportant') {
			$priority = 'Не срочно<br>Не важно';
		}
		if ($task->priority == 'urgent|notImportant') {
			$priority = 'Не срочно<br>Важно';
		}
		if ($task->priority == 'notUrgent|important') {
			$priority = 'Срочно<br>Не важно';
		}
		if ($task->priority == 'urgent|important') {
			$priority = 'Срочно<br>Важно';
		}
	}


	$taskForOutput = [ $task->id, $task->name, $startAndFinishDate, $startAndFinishTime, $priority];
	array_push($tasksForOutput, $taskForOutput);
}


writeHead('Задачи');
nav('tasks');
print '<div class="main">
	';
print "    <div class=\"change_date\">
			<p>
			<form action=\"\" method=\"POST\" class=\"change_date\">
				<input type=\"date\" name=\"selected_date\" class=\"change_date\" value=\"$selectedDate\">
				<input type=\"submit\" name=\"change_date\" value=\"✔\" class=\"change_date\">
			</form>
			<form action=\"\" method=\"POST\" class=\"other_tasks\">
				<input type=\"hidden\" name=\"hidden_date\" value=\"$selectedDate\">
				<input type=\"submit\" name=\"time_task\" value=\"⌚\" class=\"other_tasks $withTimeClass\">
				<input type=\"submit\" name=\"not_time_task\" value=\"...\" class=\"other_tasks $withoutTimeClass\">
				<input type=\"submit\" name=\"freezen\" value=\"✱\" class=\"other_tasks $freezenClass\">
			</form></p>
		</div>
		";
print '<table class="tasks">
			<tr class="tasks">
				<th class="tasks menu"></th>
				<th class="tasks namet">Наименование</th>
				<th class="tasks datet">Дата</th>
            	<th class="tasks time">Время</th>
				<th class="tasks priority">Приоритет</th>
			</tr>
			';
if ($tasksForOutput) {
	foreach ($tasksForOutput as $task) {
		print "            <tr class=\"tasks\">
				<td class=\"tasks menu\">
					<form action=\"\" method=\"POST\">
						<p><input type=\"hidden\" name=\"task\" value=\"$task[0]\">
						";
		if ($neccesaryTasks == 'freezen') {
			print "<div class=\"task\"><input type=\"submit\" name=\"done\" value=\"✔\" class=\"taskmenu ready\">
			<input type=\"submit\" name=\"delete\" value=\"✘\" class=\"taskmenu delete\">
			<input type=\"submit\" name=\"unfreeze\" value=\"✱\" class=\"taskmenu freeze\"></div></p>
			";
		} else {
			print "<div class=\"task\"><input type=\"submit\" name=\"done\" value=\"✔\" class=\"taskmenu ready\">
			<input type=\"submit\" name=\"delete\" value=\"✘\" class=\"taskmenu delete\">
			<input type=\"submit\" name=\"freeze\" value=\"✱\" class=\"taskmenu freeze\"></div></p>
			";
		}
		print "			</form>
				</td>
				<td class=\"tasks\">
					<form action=\"\" method=\"POST\">
						<input type=\"hidden\" name=\"task\" value=\"$task[0]\">
						<p><input type=\"submit\" name=\"taskname\" value=\"$task[1]\" class=\"taskname\"></p>
					</form>
				</td>
				<td class=\"tasks\">
					<p>$task[2]</p>
				</td>
				<td class=\"tasks\">
					<p>$task[3]</p>
				</td>
				<td class=\"tasks\">
					<p>$task[4]</p>
				</td>
			</tr>
			";
	}
} else {
	print '<tr>
				<td colspan="4" class="no_tasks">У Вас нет задач! Отдохните =)</td>
			</tr>
		';
}
print '</table>
	';
print '</div>
	';
add();
writeFoot();
?>