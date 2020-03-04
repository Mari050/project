<?php
	session_start();
	if (! isset($_SESSION["login"])) {
		$url = 'choice.php';
		header("Location: " . $url);
	}

	require "db.php";
    require "functions/foreverypage.php";
    
	head('Создать новую категорию');
    nav();

    //Обработчик формы
    if (isset($_POST)) {
        $errors = array();
        if (! isset($_POST['name'])) {
            $errors = 'Не введено название категории!';
        };
        if (empty($errors)) {
            $cat = R::dispense('categories');
			$cat->login = $_SESSION['login'];
            $cat->name = $_POST['name'];
			$cat->description = $_POST['description'];
            R::store($cat);
        };
    };

    //Сама форма
    print "<form action=\"\" method=\"POST\" class=\"?\">
                <input type=\"text\" name=\"name\" placeholder=\"Название\" class=\"?\"> <br>
                <input type=\"textarea\" name=\"description\" placeholder=\"Описание\" class=\"?\"> <br>
                <input type=\"submit\" name=\"create\" value=\"Создать\" class=\"?\">
            </form>";
    foot();
?>