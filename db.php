<?php
	session_start();
	if (isset($_SESSION["login"])) {
		$url = 'main.php';
		header("Location: " . $url);
	}
	require "libs/rb-mysql.php";
	R::setup( 'mysql:host=localhost;dbname=times',
        'root', '' );
?>
