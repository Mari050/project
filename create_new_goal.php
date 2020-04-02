<?php
session_start();

require 'db.php';
require 'functions/write_html.php';
require 'functions/php.php';


if (empty($_SESSION['login'])) {
    redirectToAnotherPage('index.php');
}


$priorityArr = [];
if (isset($_POST['priority'])) {
	if ($_POST['priority']['0']) {
		$priorityArr[0] = 'urgent';
	} else {
		$priorityArr[0] = 'notUrgent';
	}
	if ($_POST['priority']['1']) {
		$priorityArr[1] = 'important';
	} else {
		$priorityArr[1] = 'notImportant';
	}
	$priority = $priorityArr[0] . '|' . $priorityArr[1];
}


$error = NULL;
if (isset($_POST['new_goal'])) {
    $errors = [];
    if (empty(trim($_POST['name']))) {
        $errors[] = 'Не введено название цели!';
    }

    if (empty($errors)) {
        $categories = '';
		if (isset($_POST['category'])) {
			$categories = implode('|', $_POST['category']);
		}
        
        $goal = R::dispense('goals');
            $goal->login = $_SESSION['login'];
            $goal->name = trim($_POST['name']);
            $goal->description = trim($_POST['description']);
            $goal->created_date = date('Y-m-d', time());
            $goal->finish_date = $_POST['finish_date'];
            $goal->ready = 'inProgress';
            $goal->priority = $priority;
            $goal->categories = $categories;
        R::store($goal);
        $goal = R::findOne('goals', '`login` = ? ORDER BY `id` DESC', array($_SESSION['login']));
        $goalID = $goal->id;
        $_SESSION['goal_id'] = $goalID;
        redirectToAnotherPage('goal_page.php');
    } else {
        $error = array_shift($errors);
    }
}


if (isset($_POST['priority'])) {
    if ($priorityArr[0] == 'urgent') {
        $urgent = 'selected';
    } else {
        $urgent = NULL;
    }
    if ($priorityArr[1] == 'important') {
        $important = 'selected';
    } else {
        $important = NULL;
    }
} else {
    $urgent = NULL;
    $important = NULL;
}

if (isset($_POST['description'])) {
    $description = $_POST['description'];
} else {
    $description = NULL;
}
if (isset($_POST['finish_date'])) {
    $finishDate = $_POST['finish_date'];
} else {
    $finishDate = NULL;
}


$categories = R::find('categories', 'login LIKE ?', array($_SESSION['login']));


writeHead('Новая цель');
nav('goals');
if ($error) {
    print "<p class=\"error\">$error</p>";
}
print "<div class=\"main_category\">
        <form action=\"\" method=\"POST\">
            <div>
                <p>
                    <input type=\"submit\" name=\"new_goal\" value=\"Создать\" class=\"createsmtnew create\">
                </p>
            </div>
            <div>
                <p>
                    <input type=\"text\" name=\"name\" placeholder=\"Наименование цели\" class=\"createsmtnew name\">
                </p>
                <p>
                    <textarea name=\"description\" placeholder=\"Описание цели\" class=\"createsmtnew description\">$description</textarea>
                </p>
            </div>
            <div>
                <p>
                    <input type=\"date\" name=\"finish_date\" class=\"createsmtnew date\" value=\"$finishDate\">
                </p>
            </div>
            <div class=\"priority\">
                <p>
                    <label class=\"priority\">
                        <input type=\"checkbox\" name=\"priority[]\" value=\"0\" class=\"createsmtnew priority\" $urgent>
                        <span class=\"priority\">Срочно</span>
                    </label>
                    <label class=\"priority\">
                        <input type=\"checkbox\" name=\"priority[]\" value=\"1\" class=\"createsmtnew priority\" $important>
                        <span class=\"priority\">Важно</span>
                    </label>
                </p>
            </div>
            <div>
                <p class=\"cat\">Категории:</p>
            ";
foreach ($categories as $category) {
    print "    <div class=\"category\">
                    <label class=\"category\">
                        <input type=\"checkbox\" name=\"category[]\" value=\"$category->id\" class=\"createsmtnew category\">
                        <span class=\"category\">$category->name</span>
                    </label>
                </div>
                <br>
	        ";
}        
print '</div>
        </form>
    </div>
';
writeFoot();
?>