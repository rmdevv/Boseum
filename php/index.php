<?php
require_once 'DBAccess.php';
require_once 'utils.php';
require_once 'DateManager.php';

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
setlocale(LC_ALL,'it_IT');

session_start();
$isLoggedIn = isset($_SESSION['logged_id']);
$loginOrProfileTitle = $isLoggedIn ?
        "<a href=\"artista.php?id=".$_SESSION['logged_id']."\"><span lang=\"en\">Account</span></a>"
        : "<a href=\"login.php\">Accedi</a>";

$connection=new DB\DBAccess();
if (!$connection->openDBConnection()) {
    header('location: ../src/500.html');
    exit();
}

$nextArtshow = $connection->getNextArtshow();
$connection->closeConnection();

$nextArtshowContainer = "";
if ($nextArtshow && sizeof($nextArtshow) > 0) {
    $nextArtshowContainer =
    "<div class=\"cardmain\">
        <img
            src=\"".$nextArtshow[0]["image"]."\"
            class=\"cardmainimg\"
            id=\"imgnextartshow\"
            alt=\"".$nextArtshow[0]["title"]."\" />
        <div class=\"cardnextshow\">
            <h4 class=\"card_header\">La prossima mostra</h4>
            <p id=\"nextshow\">".$nextArtshow[0]["title"]."</p>
            <p>
                <time datetime=\"".$nextArtshow[0]["start_date"]."\">". DateManager::toDMY($nextArtshow[0]["start_date"]) ."</time>
                /
                <time datetime=\"".$nextArtshow[0]["end_date"]."\">". DateManager::toDMY($nextArtshow[0]["end_date"]) ."</time>
            </p>
            <a href=\"mostra.php?id=".$nextArtshow[0]["id"]."\"> Scoprila </a>
        </div>
    </div>";
}

$index = file_get_contents('../templates/index.html');
$index = str_replace("{{login_or_profile_title}}", $loginOrProfileTitle, $index);
$index = str_replace("{{next_artshow}}", $nextArtshowContainer, $index);
echo $index;
?>