<?php
session_start();

require 'db.php';
require 'functions/write_html.php';
require 'functions/php.php';


if (empty($_SESSION['login'])) {
    redirectToAnotherPage('index.php');
}


if (isset($_POST['change_goal'])) {
    $_SESSION['goal_id'] = $_POST['goal_id'];
    redirectToAnotherPage('change_goal.php');
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


$goal = R::findOne('goals', '`id` = ?', array($_SESSION['goal_id']));
    $goalID = $goal->id;
    $goalName = $goal->name;
    $goalDescription = $goal->description;
    $goalPriority = $goal->priority;
    $goalCreated = $goal->created_date;
    $goalFinishDate = $goal->finish_date;
    $goalTasksIDs = $goal->tasks_ids;
    $goalCategoriesIDs = $goal->categories_ids;


$tasksIDs = explode('|', $goalTasksIDs);


writeHead('Новая цель');
nav('goals');
print "<div class=\"main_category\">
        <div>
            <form action=\"\" method=\"POST\" class=\"createsmtnew\">
                <input type=\"hidden\" name=\"task\" value=\"$goalID\">
                <input type=\"submit\" name=\"changetask\" value=\"Изменить\" class=\"createsmtnew create\">
                <input type=\"submit\" name=\"done\" value=\"✔\" class=\"taskmenu\">
                <input type=\"submit\" name=\"delete\" value=\"✘\" class=\"taskmenu\">
                <input type=\"submit\" name=\"freeze\" value=\"✱\" class=\"taskmenu\">            
            </form>
        </div>
        ";
print "<div>
            <p>
                <span>Наименование: <a href=\"change_goal.php\">$goalName</a></span><br>
                <span>$goalPriority</span><br>
                <span>Завершить к: $goalFinishDate</span>
            </p>
        </div>";

print "    <p>Задачи:</p>
    <table>
        <tr>
            <th></th>
            <th>Наименование</th>
            <th>Дата</th>
        </tr>
        ";
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
    print "<tr>
    <td><form action=\"\" method=\"POST\"><input type=\"submit\" name=\"done\" value=\"✔\" class=\"taskmenu ready\">
    <input type=\"submit\" name=\"delete\" value=\"✘\" class=\"taskmenu delete\">
    <input type=\"submit\" name=\"unfreeze\" value=\"✱\" class=\"taskmenu freeze\"></form>
    </td>
    <td>$task->name</td>
    <td>$startAndFinishDate</td>
    </tr>
    ";
}
        
print "</table>
</div>";
writeFoot();
?>