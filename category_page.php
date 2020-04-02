<?php
session_start();
	

require 'db.php';
require 'functions/write_html.php';
require 'functions/php.php';


if (empty($_SESSION["login"])) {
	redirectToAnotherPage();
}


if (isset($_POST['change_category'])) {
    $_SESSION['categoryID'] = $_POST['categoryID'];
    redirectToAnotherPage('change_category.php');
}

if (isset($_POST['delete'])) {
    $category = R::findOne('categories', '`login` = ? AND `id` = ?', array($_SESSION['login'], $_POST['categoryID']));
    R::trash($category);
    redirectToAnotherPage('main.php');
}


$category = R::findOne('categories', '`login` = ? AND `id` = ?', array($_SESSION['login'], $_SESSION['categoryID']));
    $categoryID = $category->id;
    $categoryName = $category->name;
	$categoryDescriprion = $category->description;
	$categoryTasks = $category->tasks;


writeHead($categoryName);
nav('categories');
print "<div class=\"main_category\">
			<form action=\"\" method=\"POST\">
				<input type=\"submit\" name=\"delete\" value=\"✘\" class=\"taskmenu\">
				<input type=\"submit\" name=\"change_category\" value=\"Изменить\" class=\"createsmtnew create\"><br>
        		<input type=\"hidden\" name=\"categoryID\" value=\"$categoryID\">
                <input type=\"text\" name=\"name\" placeholder=\"Название\" value=\"$categoryName\" class=\"category_name\" disabled><br>
                <textarea name=\"description\" placeholder=\"Описание\" class=\"category_description\" disabled>$categoryDescriprion</textarea>
            </form>
        </div>";
writeFoot();
?>