<?php
session_start();
	

require 'db.php';
require 'functions/write_html.php';
require 'functions/php.php';


if (empty($_SESSION['login'])) {
	redirectToAnotherPage('index.php');
}
    
if (isset($_POST['newnote'])) {
    $errors = [];
    if (empty($_POST['name'])) {
        $errors = 'Введите название заметки!';
    }
    if (empty($errors)) {
        $note = R::dispense('notes');
        $note->login = $_SESSION['login'];
        if (isset($_POST['name'])) {
            $note->name = $_POST['name'];
        }
        $note->value = $_POST['value'];
        $note->date = date('Y-m-d', time());
        $note->time = date('G:i');
        R::store($note);
        redirectToAnotherPage('notes.php');
    } else {
        print $errors;
    }
}



writeHead('Заметки');
nav('notes');
print '<div class="main_category">
            <form action="" method="POST">
                <input type="submit" name="newnote" value="Создать" class="create"><br>
                <input type="text" name="name" placeholder="Название" class="category_name"><br>
                <textarea name="value" placeholder="Описание" class="category_description"></textarea>
            </form>
        </div>';
writeFoot();
?>