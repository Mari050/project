<?php
session_start();


require 'db.php';
require 'functions/write_html.php';
require 'functions/php.php';


if (empty($_SESSION['login'])) {
    redirectToAnotherPage('index.php');
}


if (isset($_POST)) {
    $errors = array();
    if (empty($_POST['name'])) {
        $errors = 'Не введено название категории!';
    }
    if (empty($errors)) {
        $cat = R::findOne('categories', '`login` = ? AND `id` = ?', array($_SESSION['login'], $_SESSION['categoryID']));
        $cat->name = $_POST['name'];
		$cat->description = $_POST['description'];
        R::store($cat);
        redirectToAnotherPage('categories.php');
    }
}

$category = R::findOne('categories', '`login` = ? AND `id` = ?', array($_SESSION['login'], $_SESSION['categoryID']));
    $categoryID = $category->id;
    $categoryName = $category->name;
	$categoryDescriprion = $category->description;
    $categoryTasks = $category->tasks;


writeHead("$categoryName");
nav('categories');
print "<div class=\"main_category\">
			<form action=\"\" method=\"POST\">
				<input type=\"submit\" name=\"change_category\" value=\"Сохранить изменения\" class=\"createsmtnew create\"><br>
        		<input type=\"hidden\" name=\"categoryID\" value=\"$categoryID\">
                <input type=\"text\" name=\"name\" placeholder=\"Название\" value=\"$categoryName\" class=\"category_name\"><br>
                <textarea name=\"description\" placeholder=\"Описание\" class=\"category_description\">$categoryDescriprion</textarea>
            </form>
        </div>";
writeFoot();
?>