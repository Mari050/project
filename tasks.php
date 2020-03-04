<?php
	session_start();
	if (! isset($_SESSION["login"])) {
		$url = 'choice.php';
		header("Location: " . $url);
	}

	require "db.php";
    require "functions/foreverypage.php";
    
	head('Задачи');
    nav();

    if (isset($_POST['task']))  {
		$_SESSION['task'] = $_POST['task'];
		$url = 'taskpage.php';
		header("Location: " . $url);
    };
    
    $login = $_SESSION['login'];
    print "<table>
				<tr>
					<th>Menu</th>
					<th>Name</th>
                    <th>Description</th>
                    <th>Startd</th>
                    <th>Startt</th>
                    <th>Finishd</th>
                    <th>Finisht</th>
                    <th>Priority</th>
				</tr>";
	$tasks = R::find('tasks', 'login LIKE ?', array($login)); #Получаем данные от БД
	$i = 0;
	foreach ($tasks as $c => $value) { #Выводим полученные данные в виде таблицы
		print "<tr>
					<td>
						<form action=\"\" method=\"POST\">
							<p><input type=\"submit\" name=\"C\" value=\"C\"></p>
							<p><input type=\"submit\" name=\"D\" value=\"D\"></p>
						</form>
					</td>
					<td>
						<form action=\"\" method=\"POST\">
							<p><input type=\"submit\" name=\"task\" value=\"$value->name\"></p>
						</form>
					</td>
					<td>
                        $value->description
                    </td>
                    <td>
                        $value->startd
                    </td>
                    <td>
                        $value->startt
                    </td>
                    <td>
                        $value->finishd
                    </td>
                    <td>
                        $value->finisht
                    </td>
                    <td>
                        $value->priority
                    </td>
				</tr><br>";
	};
	print "</table>";

    add();
    foot();
?>