<?php
	session_start();
	require "db.php";
	if (isset($_SESSION["login"])) {
		$url = main.php;
		header('Location: ' . $url);
	} else {
		$data = $_POST;
		if (isset($_POST["signup"])) {
			$errors = array();

			if (trim($data['login']) == '') {
				$errors[] = 'Введите логин!';
			}
			if (trim($data['email']) == '') {	
				$errors[] = 'Введите Email!';
			}
			if ($data['password'] == '') {
				$errors[] = 'Введите пароль!';
			}
			if ($data['password2'] != $data['password']) {
				$errors[] = 'Введенные пароли не совпадают!';
			}
			if (R::count('users', "login = ?", array($data['login'])) > 0) {
				$errors[] = 'Пользователь с таким логином уже существует!';
			}

			if (R::count('users', "email = ?", array($data['email'])) > 0) {
				$errors[] = 'Пользователь с таким Email уже существует!';
			}

			if (empty($errors)) {
				$user = R::dispense('users');
				$user->login = $data["login"];
				$user->email = $data["email"];
				$user->password = password_hash($data["password"], PASSWORD_DEFAULT);
				R::store($user);
				$_SESSION["login"] = $data["login"];
				$url = main.php;
				header ('Location: main.php');
			} else {
				echo '<div style="color: red;">'.array_shift($errors).'</div><hr>';
			}
		}
	}
?>

<!DOCTYPE html>
<html lang = "ru">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
		<title>Регистрация</title>
	</head>
	<body>
		<form action="" method="POST" class="signup">
			<input type="text" name="login" placeholder="Логин" value="<?php echo @$data["login"]; ?>" class="sipass">
			<input type="email" name="email" placeholder="Email" value="<?php echo @$data["email"]; ?>" class="sipass">
			<input type="password" name="password" placeholder="Пароль" class="sipass">
			<input type="password" name="password2" placeholder="Повторите пароль" class="sipass">
			<input type="submit" name="signup" value="Регистрация" class="sipassin">
		</form>
	</body>
</html>
