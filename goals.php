<?php
session_start();

require 'db.php';
require 'functions/write_html.php';
require 'functions/php.php';


if (empty($_SESSION['login'])) {
    redirectToAnotherPage('index.php');
}



writeHead('Цели');
nav('goals');
add();
writeFoot();
?>