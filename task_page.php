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


if (isset($_POST['done'])) {
    $task = R::findOne('tasks', '`login` = ? AND `id` = ?', array($_SESSION['login'], $_POST['task']));
        $task->ready = 'done';
    R::store($task);
    redirectToAnotherPage('main.php');
}
if (isset($_POST['delete'])) {
    $task = R::findOne('tasks', '`login` = ? AND `id` = ?', array($_SESSION['login'], $_POST['task']));
    R::trash($task);
    redirectToAnotherPage('main.php');
}
if (isset($_POST['freeze'])) {
    $task = R::findOne('tasks', '`login` = ? AND `id` = ?', array($_SESSION['login'], $_POST['task']));
    $task->ready = 'freezen';
    R::store($task);
}

if (isset($_POST['change_task'])) {
    $_SESSION['task_id'] = $_POST['task_id'];
    redirectToAnotherPage('change_task.php');
}


$task = R::findOne('tasks', '`id` = ?', array($_SESSION['task_id']));
    $taskID = $task->id;
    $taskName = $task->name;
    $taskDescription = $task->description;
    $taskCreated = $task->created;
    $taskStartDate = $task->start_date;
    $taskStartTime = $task->start_time;
    $taskFinishDate = $task->finish_date;
    $taskFinishTime = $task->finish_time;
    $taskPriority = $task->priority;
    $taskCategories = $task->categories;

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
foreach ($categoriesIDs as $categoryID) {
    $categories += R::find('categories', '`id` LIKE ?', array($categoryID));
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
print "\t\t<div>
            <form action=\"\" method=\"POST\">
                <input type=\"submit\" name=\"done\" value=\"✔\" class=\"taskmenu\">
		        <input type=\"submit\" name=\"delete\" value=\"✘\" class=\"taskmenu\">
                <input type=\"submit\" name=\"freeze\" value=\"✱\" class=\"taskmenu\">
            </form>
        </div>\n";
print $priority;
print "\t\t<div>
            <p>
                <span class=\"label_parameter\">Наименование: </span>
                <span class=\"value_parameter\">$taskName</span>
            </p>
        </div>\n";
if (isset($taskDescription)) {
    print "\t\t<div>
            <p>
                <span class=\"label_parameter\">Описание:</span><br>
                <textarea class=\"description\">$taskDescription</textarea>
            </p>
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
if ($categories) {
    print "\t\t<div>
            <p class=\"cat\">Категории:</p>
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
                <span class=\"value_parameter\">$taskStartTime</span>
            </div>
        </div>\n";
print "\t</div>\n";
writeFoot();
?>