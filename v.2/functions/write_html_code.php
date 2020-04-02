<?php
    function writeHeadHTML($pageName) {
        print "<!DOCTYPE html>
        <html lang=\"ru\">
        <head>
            <meta charset=\"utf-8\">
            <link rel=\"stylesheet\" type=\"text/css\" href=\"main.css\">
            <title>$pageName</title>
        </head>
        ";
    }
?>