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
    if (! isset($_POST['name'])) {
        $errors = 'Не введено название категории!';
    }
    if (empty($errors)) {
        $cat = R::dispense('categories');
		$cat->login = $_SESSION['login'];
        $cat->name = $_POST['name'];
		$cat->description = $_POST['description'];
        R::store($cat);
        redirectToAnotherPage('categories.php');
    }
}

    
writeHead('Новая категория');
nav('categories');
print '<div class="main_category">
            <form action="" method="POST">
                <input type="submit" name="create" value="Создать" class="create"><br>
                <input type="text" name="name" placeholder="Название" class="category_name"><br>
                <textarea name="description" placeholder="Описание" class="category_description"></textarea>
            </form>
        </div>';
writeFoot();
?>