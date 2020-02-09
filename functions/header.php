<?php
	function head($name) {
		print <<<HEADER
			<!DOCTYPE html>
			<html>
			<head>
				<meta charset="utf-8">
				<link rel="stylesheet" type="text/css" href="style.css">
				<title>{$name}</title>
			</head>
			<body>
		HEADER;
	}
?>