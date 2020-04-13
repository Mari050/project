<?php
session_start();

require "db.php";
require "functions/write_html.php";
require "functions/php.php";

if ( isset($_SESSION["login"])) {
	redirectToAnotherPage('main.php');
}

$pageName = 'Войти/Зарегистрироваться';
writeHead($pageName);
print "    <div class=\"choice\">
		<p>
			<button class=\"choice\">
				<a href=\"singin.php\" class=\"choice\">Вход</a>
			</button>
		</p>
		<p>
			<button class=\"choice\">
				<a href=\"signup.php\" class=\"choice\">Регистрация</a>
			</button>
		</p>
	</div>
</body>
</html>";
?>
