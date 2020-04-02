<?php
session_start();

require 'db.php';
require 'functions/write_html.php';
require 'functions/php.php';


if (empty($_SESSION['login'])) {
    redirectToAnotherPage('index.php');
}


$oneError = NULL;
if (isset($_POST['change_goal'])) {
    $errors = [];
    if (empty($_POST['goal_name'])) {
        $errors = 'Не введено название задачи!';
    }

    if (empty($errors)) {
        $goal = R::findOne('goals', '`id` = ?', array($_SESSION['goalID']));
            $goal->name = $_POST['goal_name'];
            $goal->description = $_POST['goal_description'];
        R::store($goal);
        redirectToAnotherPage('goal_page.php');
    } else {
        $oneError = array_shift($errors);
    }
}


$goal = R::findOne('goals', '`id` = ?', array($_SESSION['goalID']));
    $goalID = $goal->id;
    $goalName = $goal->name;
    $goalDescription = $goal->description;


writeHead('Новая цель');
nav('goals');
if ($oneError) {
    print "<p class=\"logError\">$oneError</p>";
}
print "<div class=\"main_category\">
    <form action=\"\" method=\"POST\">
        <input type=\"hidden\" name=\"gaolID\" value=\"$goalID\">
        <p><input type=\"submit\" name=\"change_goal\" value=\"Сохранить изменения\" class=\"createsmtnew create\"></p>
        <p><input type=\"text\" name=\"goal_name\" placeholder=\"Наименование цели\" value=\"$goalName\" class=\"createsmtnew name\"></p>
        <p><textarea name=\"goal_description\" placeholder=\"Описание цели\" value=\"goalDescription\" class=\"createsmtnew description\">$goalDescription</textarea></p>
    </form>
</div>";
writeFoot();
?>