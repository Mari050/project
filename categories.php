<?php
session_start();
	

require 'db.php';
require 'functions/write_html.php';
require 'functions/php.php';


if (empty($_SESSION["login"])) {
	redirectToAnotherPage('index.php');
}


if (isset($_POST['category']))  {
	$_SESSION['categoryID'] = $_POST['categoryID'];
	redirectToAnotherPage('category_page.php');
}

if (isset($_POST['delete'])) {
	$task = R::findOne('tasks', '`login` = ? AND `id` = ?', array($_SESSION['login'], $_POST['task']));
	R::trash($task);
}

if (isset($_POST['change'])) {
    $_SESSION['categoryID'] = $_POST['categoryID'];
    redirectToAnotherPage('change_category.php');
}


$categories = R::find('categories', '`login` = ? ORDER BY `name`', array($_SESSION['login']));


writeHead('Категории');
nav('categories');
print '<div class="main">
';
print '<table class="tasks">
	<tr>
		<th class="tasks category_menu"></th>
		<th class="tasks category_name">Наименование</th>
		<th class="tasks category_description">Описание</th>
	</tr>
	';
foreach ($categories as $cac => $value) { #Выводим полученные данные в виде таблицы
	print "<tr>
			<td class=\"tasks\">
				<form action=\"\" method=\"POST\">
					<p><input type=\"hidden\" name=\"categoryID\" value=\"$value->id\">
					<input type=\"submit\" name=\"change\" value=\"✎\" class=\"taskmenu\">
					<input type=\"submit\" name=\"delete\" value=\"✘\" class=\"taskmenu\"></p>
				</form>
			</td>
			<td class=\"tasks\">
				<form action=\"\" method=\"POST\">
					<input type=\"hidden\" name=\"categoryID\" value=\"$value->id\">
					<p><input type=\"submit\" name=\"category\" value=\"$value->name\" class=\"taskname\"></p>
				</form>
			</td>
			<td class=\"tasks\">
				$value->description
			</td>
		</tr>
		";
}
print '</table>
</div>
';
add();
writeFoot();
?>