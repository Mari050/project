<?php
session_start();
	

require 'db.php';
require 'functions/write_html.php';
require 'functions/php.php';


if (empty($_SESSION['login'])) {
	redirectToAnotherPage('index.php');
}


if (isset($_POST['notename']))  {
	$_SESSION['noteID'] = $_POST['noteID'];
	redirectToAnotherPage('note_page.php');
}

if (isset($_POST['delete'])) {
	$task = R::findOne('notes', '`login` = ? AND `id` = ?', array($_SESSION['login'], $_POST['note']));
	R::trash($task);
}


$notes = R::find('notes', '`login` LIKE ?', array($_SESSION['login']));


writeHead('Заметки');
nav('notes');
print '<div class="main"><table class="tasks">
<tr class="tasks">
	<th class="tasks delete_note"></th>
	<th class="tasks note_name">Наименование</th>
	<th class="tasks" note_created>Дата/время<br>создания</th>
</tr>';
foreach ($notes as $note) {
	print "<tr>
<td class=\"tasks\">
	<form action=\"\" method=\"POST\">
		<input type=\"hidden\" name=\"note\" value=\"$note->id\">
		<input type=\"submit\" name=\"delete\" value=\"✘\" class=\"taskmenu\">
	</form>
</td>
<td class=\"tasks\">
		<form action=\"\" method=\"POST\">
		<input type=\"submit\" name=\"notename\" value=\"$note->name\" class=\"taskname\"><br>
		<input type=\"hidden\" name=\"noteID\" value=\"$note->id\">
		</form>
		<div class=\"notevalue\">$note->value</div>

</td>
<td class=\"tasks\">
	<p>$note->date<br>$note->time</p>
</td>
</tr>";
}
print '</table></div>';
add();
writeFoot();
?>