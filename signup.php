<?php
	session_start();


	require 'db.php';
	require 'functions/write_html.php';
	require 'functions/php.php';


	if ( isset( $_SESSION['login'] ) ) {
		redirectToAnotherPage('main.php');
	}


	if ( isset( $_POST['signup'] ) ) {
		$errors = array();

		if ( trim( $_POST['login'] ) == '' ) {
			$errors[] = 'Введите логин!';
		}
		if ( trim( $_POST['email'] ) == '' ) {	
			$errors[] = 'Введите Email!';
		}
		if ( $_POST['password'] == '' ) {
			$errors[] = 'Введите пароль!';
		}
		if ( $_POST['passwordReplay'] != $_POST['password'] ) {
			$errors[] = 'Введенные пароли не совпадают!';
		}
		if ( R::count( "users", "`login` = ?", array( $_POST['login'] ) ) > 0 ) {
			$errors[] = 'Пользователь с таким логином уже существует!';
		}
		if ( R::count( "users", "`email` = ?", array( $_POST['email'] ) ) > 0 ) {
			$errors[] = 'Пользователь с таким Email уже существует!';
		}

		if ( empty( $errors ) ) {
			$user = R::dispense('strangers');
				$user->login = $_POST['login'];
				$user->email = $_POST['email'];
				$user->password = password_hash( $_POST['password'], PASSWORD_DEFAULT );
			R::store($user);
			$_SESSION['login'] = $_POST['login'];
			redirectToAnotherPage('main.php');
		}
	}


	if ( isset ( $_POST['login'])) {
		$login = $_POST['login'];
	} else {
		$login = '';
	}
	if ( isset ( $_POST['email'])) {
		$email = $_POST['email'];
	} else {
		$email = '';
	}

	writeHead('Регистрация');
	if ( isset ( $errors ) ) {
		$oneError = array_shift( $errors );
		print "<p class=\"regError\">$oneError</p>";
	}
	print '    <form action="" method="POST" class="signup">';
	print "
	    <input type=\"text\" name=\"login\" placeholder=\"Логин\" value=\"$login\" class=\"sipass\">
        <input type=\"email\" name=\"email\" placeholder=\"Email\" value=\"$email\" class=\"sipass\">";
	print '
		<input type="password" name="password" placeholder="Пароль" class="sipass">
		<input type="password" name="passwordReplay" placeholder="Повторите пароль" class="sipass">
		<input type="submit" name="signup" value="Регистрация" class="sipassin">
	</form>
</body>
</html>';
?>