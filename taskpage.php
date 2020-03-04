<?php
    session_start();
	if (! isset($_SESSION["login"])) {
		$url = 'choice.php';
		header("Location: " . $url);
	}

	require "db.php";
    require "functions/foreverypage.php";
    
	head($_SESSION['task']);
    nav();
    $task = R::findOne('tasks', '`login` LIKE ? AND `name` LIKE ?', array($_SESSION['login'], $_SESSION['task']));
    print "<form action=\"\" method=\"POST\">
                <input type=\"text\" name=\"name\" placeholder=\"Наименование задачи\" value=\"$task->name\"><br>
                <input type=\"text\" name=\"description\" placeholder=\"Описание задачи\" value=\"$task->description\"></textarea><br>
                <input type=\"date\" name=\"startd\" value=\"$task->startd\">
                <input type=\"time\" name=\"startt\" value=\"$task->startt\"><br>
                <input type=\"date\" name=\"finishd\" value=\"$task->finishd\">
                <input type=\"time\" name=\"finisht\" value=\"$task->finisht\"><br>";
    $priority = explode('|', $task->priority);
    if ($priority[0] == 1) {
        print "<input type=\"checkbox\" name=\"priority[]\" value=\"1\" checked>Срочно<br>";
    } else {
        print "<input type=\"checkbox\" name=\"priority[]\" value=\"1\">Срочно<br>";
    };
    if ($priority[1] == 1) {
        print "<input type=\"checkbox\" name=\"priority[]\" value=\"2\" checked>Важно<br>";
    } else {
        print "<input type=\"checkbox\" name=\"priority[]\" value=\"2\">Важно<br>";
    };
    $i = 0;
    foreach ($task->categories as $c => $value) {
        if ($value != '|') {
            $category[$i] = $value;
            $i++;
        } else {
            continue;
        }
    }
    $cat = R::find('categories', 'login LIKE ? AND categories LIKE ?', array($_SESSION['login'], $categories));
	$i = 0;
	foreach ($cat as $c => $value) { 
        if ($priority[1] == 1) {
            print "<input type=\"checkbox\" name=\"category[]\" value=\"$value->id\" checked>$value->name<br>";
        } else {
            print "<input type=\"checkbox\" name=\"category[]\" value=\"$value->id\">$value->name<br>";
        };
	};
    add();
    foot();
?>