<?php
	session_start();
	if (! isset($_SESSION["login"])) {
		$url = 'choice.php';
		header("Location: " . $url);
	}

	require "db.php";
	require "functions/foreverypage.php";
	head('Главная');
	nav();
	add();

?>