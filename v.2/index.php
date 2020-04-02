<?php
    session_start();

    require "functions/write_html_code.php";

    function writeBodyHTML() {
        print "<body>
            <div>
                <p><button><a>Вход</a></button></p>
                <p><button><a>Регистрация</a></button></p>
            </div>
        </body>
        </html>";
    }

    writeHeadHTML("Войти/зарегистрироваться");
    writeBodyHTML();
?>