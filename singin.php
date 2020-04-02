<?php
	session_start();


	require 'db.php';
	require 'functions/write_html.php';
	require 'functions/php.php';


	if ( isset( $_SESSION['login'] ) ) {
		redirectToAnotherPage('main.php');
	}

	if ( isset( $_POST['dologin'] ) ) {
		$errors = array();
		$user = R::findOne('strangers', 'login = ?', array( trim( $_POST['login'] ) ) );
		if ( $user ) {
			if (! password_verify( $_POST['password'], $user->password ) ) {
				$errors[] = 'Логин или пароль введен неверно!';
			}
		} else {
			$errors[] = 'Логин или пароль введен неверно!';
		}

		if (empty($errors)) {
			$_SESSION['login'] = $_POST['login'];
			redirectToAnotherPage('main.php');
			
		}
	}


	writeHead('Вход');
	if (isset($errors)) {
		$oneError = array_shift($errors);
		print "<p class=\"logError\">$oneError</p>";
	}
	print '    <form action="" method="POST" class="login">
		<input type="text" name="login" placeholder="Логин" class="logpass"> <br>
		<input type="password" name="password" placeholder="Пароль" class="logpass"> <br>
		<input type="submit" name="dologin" value="Войти" class="logpassin">
	</form>
</body>
</html>';
?>