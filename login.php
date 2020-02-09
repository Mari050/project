<?php
	require 'db.php';
	$data = $_POST;
	if (isset($_SESSION["login"])) {
		$url = main.php;
		header('Location: ' . $url);
	} else {
		if (isset($data['dologin']))  {
			$errors = array();
			$user = R::findOne('users', 'login = ?', array($data['login']));
			if ($user) {
				//логин существует!
				if (! password_verify($data['password'], $user->password)) {
					$errors[] = 'Пароль введен неверно!';
				}
			} else {
				$errors[] = 'Пользователь с таким логином не найден!';
			}

			if (!empty($errors)) {
				echo '<div style="color: red;">'.array_shift($errors).'</div><hr>';
			} else {
				$_SESSION['login'] = $data['login'];

				$url='main.php';
				header("Location: ".$url);
				exit;
			}
		} 
	}
?>

<!DOCTYPE html>
<html lang = "ru">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
		<title>Вход</title>
	</head>
	<body>
		<form action="" method="POST" class="login">
			<input type="text" name="login" placeholder="Логин" class="logpass"> <br>
			<input type="password" name="password" placeholder="Пароль" class="logpass"> <br>
			<input type="submit" name="dologin" value="Войти" class="logpassin">
		</form>
	</body>
</html>