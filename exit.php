<?php
    session_start();
    session_unset();
    session_destroy();
    $url = '/project/index.php';
	header("Location: " . $url);
    exit;
?>