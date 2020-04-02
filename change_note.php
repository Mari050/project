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
        $errors = 'Не введено название заметки!';
    }
    if (empty($errors)) {
        $note = R::findOne('notes', '`id` = ?', array($_SESSION['categoryID']));
        $note->name = $_POST['name'];
		$note->value = $_POST['description'];
        R::store($note);
        redirectToAnotherPage('note_page.php');
    }
}


$note = R::findOne('notes', '`id` = ?', array($_SESSION['noteID']));
    $noteID = $note->id;
    $noteName = $note->name;
	$noteValue = $note->value;


writeHead("$noteName");
nav('notes');
print "<div class=\"main_category\">
			<form action=\"\" method=\"POST\">
				<input type=\"submit\" name=\"change_category\" value=\"Сохранить изменения\" class=\"createsmtnew create\"><br>
        		<input type=\"hidden\" name=\"categoryID\" value=\"$noteID\">
                <input type=\"text\" name=\"name\" placeholder=\"Название\" value=\"$noteName\" class=\"category_name\"><br>
                <textarea name=\"description\" placeholder=\"Описание\" class=\"category_description\">$noteValue</textarea>
            </form>
        </div>";
writeFoot();
?>