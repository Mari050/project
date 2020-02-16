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
	print "<table>";
	function dump($what) {
		echo '<pre>'; print_r($what); echo '</pre>';
	}
	
	$login = $_SESSION['login'];
	$cat = R::findAll('categories');
	//$cat = R::findlike('categories', ['login' => [$login]]);
	//dump($cat);
	//$cat = R::exec('SELECT * FROM categories WHERE login LIKE ?;', [$_SESSION['login']]);
	//$cats = [];
	//while($cat = $cata) {
	//	if ($cat->login = $_SESSION['login']) {
	//		$cats = $cat;
	//	}
	//	$cata = $cat->next();
	//}
	dump($cat);
	add();
?>