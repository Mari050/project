<?php
session_start();
	

require 'db.php';
require 'functions/write_html.php';
require 'functions/php.php';


if (empty($_SESSION['login'])) {
	redirectToAnotherPage();
}


if (isset($_POST['change_note'])) {
    $_SESSION['noteID'] = $_POST['noteID'];
    redirectToAnotherPage('change_note.php');
}

if (isset($_POST['delete'])) {
    $note = R::findOne('notes', '`login` = ? AND `id` = ?', array($_SESSION['login'], $_POST['categoryID']));
    R::trash($note);
    redirectToAnotherPage('main.php');
}


$note = R::findOne('notes', '`id` = ?', array($_SESSION['noteID']));
    $noteID = $note->id;
    $noteName = $note->name;
	$noteValue = $note->value;


writeHead($noteName);
nav('notes');
print "<div class=\"main_category\">
			<form action=\"\" method=\"POST\">
				<input type=\"submit\" name=\"delete\" value=\"✘\" class=\"taskmenu\">
				<input type=\"submit\" name=\"change_note\" value=\"Изменить\" class=\"createsmtnew create\"><br>
        		<input type=\"hidden\" name=\"noteID\" value=\"$noteID\">
                <input type=\"text\" name=\"name\" placeholder=\"Название\" value=\"$noteName\" class=\"category_name\" disabled><br>
                <textarea name=\"description\" placeholder=\"Описание\" class=\"category_description\" disabled>$noteValue</textarea>
            </form>
        </div>";
writeFoot();
?>