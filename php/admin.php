<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
setlocale(LC_ALL,'it_IT');

session_start();
$isLoggedIn = isset($_SESSION['logged_id']);

$adminButtons="";
if($isLoggedIn && $_SESSION['is_admin']) {
    $adminButtons = "<div class=\"admin_button\">
                <a href=\"crea_mostra.php\">Crea mostra</a>
                <button id=\"logout_button\">Logout</button>
            </div>";
}else {
    header("location: ../php/index.php");
}

$admin = file_get_contents("../templates/admin.html");
$admin = str_replace("{{admin_buttons}}", $adminButtons, $admin);
echo($admin);
?>