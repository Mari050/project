<?php
session_start();

require 'db.php';
require 'functions/write_html.php';
require 'functions/php.php';


if (empty($_SESSION['login'])) {
	redirectToAnotherPage('index.php');
}


if (isset($_POST['goal_name'])) {
	$_SESSION['goal_id'] = $_POST['goal_id'];
	redirectToAnotherPage('goal_page.php');
}

if (isset($_POST['done'])) {
	$goal = R::findOne('goals', '`id` = ?', array($_POST['goal_id']));
	$goal->ready = 'done';
	R::store($goal);
}


$goals = R::find('goals', '`login` = ?', array($_SESSION['login']));


writeHead('Цели');
nav('goals');
print "\t<div class=\"main\">
		<table class=\"tasks\">
			<tr class=\"tasks\">
				<th class=\"tasks menu\"></th>
				<th class=\"tasks namet\">Наименование</th>
				<th class=\"tasks datet\">Дата завершения</th>
				<th class=\"tasks priority\">Приоритет</th>
			</tr>\n";
foreach ($goals as $goal => $value) {
	$priority = getPriority($value->priority);
	print "\t\t\t<tr>
				<td class=\"tasks\">
					<form action=\"\" method=\"POST\">
						<input type=\"hidden\" name=\"goal_id\" value=\"$value->id\">
						<input type=\"submit\" name=\"done\" value=\"✔\" class=\"taskmenu ready\">
						<input type=\"submit\" name=\"delete\" value=\"✘\" class=\"taskmenu delete\">
						<input type=\"submit\" name=\"freeze\" value=\"✱\" class=\"taskmenu freeze\">
					</form>
				</td>
				<td class=\"tasks\">
					<form action=\"\" method=\"POST\">
						<input type=\"hidden\" name=\"goal_id\" value=\"$value->id\">
						<input type=\"submit\" name=\"goal_name\" value=\"$value->name\" class=\"taskname\">
					</form>
				</td>
				<td class=\"tasks\">$value->finish_date</td>
				<td class=\"tasks\">$priority</td>
			</tr>\n";
}
add();
writeFoot();
?>
