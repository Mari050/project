<?php
	session_start();
	if (! isset($_SESSION["login"])) {
		$url = 'choice.php';
		header("Location: " . $url);
	}

	require "db.php";
    require "functions/foreverypage.php";
    
	head('Категории');
    nav();
	
	$login = $_SESSION['login'];

	//function dump($what) {
	//	echo '<pre>'; print_r($what); echo '</pre>';
	//}


	#Обработчики форм
	if (isset($_POST['category']))  {
		$_SESSION['category'] = $_POST['category'];
		$url = 'categorypage.php';
		header("Location: " . $url);
	};

	#Создаем таблицу с категориями
	print "<table>
				<tr>
					<th>Menu</th>
					<th>Name</th>
					<th>Description</th>
				</tr>";
	$cat = R::find('categories', 'login LIKE ?', array($login)); #Получаем данные от БД
	$i = 0;
	foreach ($cat as $c => $value) { #Выводим полученные данные в виде таблицы
		print "<tr>
					<td>
						<form action=\"\" method=\"POST\">
							<p><input type=\"submit\" name=\"C\" value=\"C\"></p>
							<p><input type=\"submit\" name=\"D\" value=\"D\"></p>
						</form>
					</td>
					<td>
						<form action=\"\" method=\"POST\">
							<p><input type=\"submit\" name=\"category\" value=\"$value->name\"></p>
						</form>
					</td>
					<td>
						$value->description
					</td>
				</tr><br>";
	};
	print "</table>";

	add();
?>