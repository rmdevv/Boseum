<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
setlocale(LC_ALL, 'it_IT');

session_start();
$isLoggedIn = isset($_SESSION['logged_id']);
$loginOrProfileTitle = $isLoggedIn ?
        "<a href=\"artista.php?id=" . $_SESSION['logged_id'] . "\"><span lang=\"en\">Account</span></a>"
        : "<a href=\"login.php\">Accedi</a>";

$page404 = file_get_contents("../templates/404.html");
$page404 = str_replace("{{login_or_profile_title}}", $loginOrProfileTitle, $page404);
echo ($page404);
