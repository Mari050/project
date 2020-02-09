<?php
    session_start();
    session_unset();
    session_destroy();
    header("Location: choice.php");// /admin/ - это ссылка на страницу, которая откроется после выхода
    exit;
?>