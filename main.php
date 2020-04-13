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
	$_SESSION['date'] = NULL;
	$_SESSION['tasks'] = NULL;
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
	$selectedDate = date('Y-m-d', time());
}

$neccesaryTasks = '';
if (isset($_POST['time_task'])) {
	$selectedDate = $_POST['hidden_date'];
	$neccesaryTasks = 'with_time';
} elseif (isset($_POST['not_time_task'])) {
	$selectedDate = $_POST['hidden_date'];
	$neccesaryTasks = 'without_time';
} else {
	if (isset($_SESSION['tasks'])) {
		$neccesaryTasks = $_SESSION['tasks'];
	} else {
		$neccesaryTasks = 'with_time';
	}
}

if ($neccesaryTasks == 'with_time') {
	$withTimeClass = 'active';
	$withoutTimeClass = '';
} else {
	$withTimeClass = '';
	$withoutTimeClass = 'active';
}

$_SESSION['tasks'] = $neccesaryTasks;

if ($neccesaryTasks == 'with_time') {
	$tasks = R::find( 'tasks', '`login` = ? AND `start_date` = ? AND `start_time` IS NOT NULL ORDER BY `start_time`', array( $_SESSION['login'], $selectedDate ) );
} else {
	$tasks = R::find( 'tasks', '`login` = ? AND `start_date` = ? AND `start_time` IS NULL AND `priority` = ?', array( $_SESSION['login'], $selectedDate, 'urgent|important' ) );
	$tasks += R::find( 'tasks', '`login` = ? AND `start_date` = ? AND `start_time` IS NULL AND `priority` = ?', array( $_SESSION['login'], $selectedDate, 'urgent|notImportant' ) );
	$tasks += R::find( 'tasks', '`login` = ? AND `start_date` = ? AND `start_time` IS NULL AND `priority` = ?', array( $_SESSION['login'], $selectedDate, 'notUrgent|important' ) );
	$tasks += R::find( 'tasks', '`login` = ? AND `start_date` = ? AND `start_time` IS NULL AND `priority` = ?', array( $_SESSION['login'], $selectedDate, 'notUrgent|notImportant' ) );
}

$tasksForOutput = [];
foreach ($tasks as $task) {
	if ($task->ready == 'done' or $task->ready == 'freezen') {
		continue;
	}

	$startAndFinishTime = getStartAndFinishTime($task->start_time, $task->finish_time);
	
	$priority = getpriority($task->priority);

	$taskForOutput = [ $task->id, $task->name, $startAndFinishTime, $priority];
	array_push($tasksForOutput, $taskForOutput);
}
 

writeHead('Главная');
nav('main');
print '<div class="main">
	';
print "    <div class=\"change_date\">
			<form action=\"\" method=\"POST\" class=\"change_date\">
				<input type=\"date\" name=\"selected_date\" class=\"change_date\" value=\"$selectedDate\">
				<input type=\"submit\" name=\"change_date\" value=\"✔\" class=\"change_date\">
			</form>
			<form action=\"\" method=\"POST\" class=\"other_tasks\">
				<input type=\"hidden\" name=\"hidden_date\" value=\"$selectedDate\">
				<input type=\"submit\" name=\"time_task\" value=\"⌚\" class=\"other_tasks $withTimeClass\">
				<input type=\"submit\" name=\"not_time_task\" value=\"...\" class=\"other_tasks $withoutTimeClass\">
			</form>
		</div>
		";
print '<table class="tasks">
			<tr class="tasks">
				<th class="tasks menu"></th>
				<th class="tasks name">Наименование</th>
            	<th class="tasks time">Время</th>
				<th class="tasks priority">Приоритет</th>
			</tr>
			';
if ($tasksForOutput) {
	foreach ($tasksForOutput as $task) {
		print "            <tr class=\"tasks\">
				<td class=\"tasks\" class=\"menu\">
					<form action=\"\" method=\"POST\">
						<p><input type=\"hidden\" name=\"task\" value=\"$task[0]\">
						<input type=\"submit\" name=\"done\" value=\"✔\" class=\"taskmenu\" class=\"ready\">
						<input type=\"submit\" name=\"delete\" value=\"✘\" class=\"taskmenu\" class\"delete\">
						<input type=\"submit\" name=\"freeze\" value=\"✱\" class=\"taskmenu\" class=\"freeze\"></p>
					</form>
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
