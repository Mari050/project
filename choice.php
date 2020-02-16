<?php
	session_start();
	require "db.php";
	require "functions/foreverypage.php";
	if (isset($_SESSION["login"])) {
		$url = 'main.php';
		header("Location: " . $url);
	}
	$name = 'Авторизация';
	head($name);
?>

	<div class="choice"><p><button class="choice"><a href="login.php" class="choice">Вход</a></button></p>
	<p><button class="choice"><a href="signup.php" class="choice">Регистрация</a></button></p></div>
</body>
</html>
