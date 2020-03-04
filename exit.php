<?php
    session_start();
    session_unset();
    session_destroy();
    $url = '/project/choice.php';
	header("Location: " . $url); // это ссылка на страницу, которая откроется после выхода
    exit;
?>