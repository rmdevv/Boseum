<?php
require_once 'DBAccess.php';
require_once 'utils.php';
require_once 'DateManager.php';

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
setlocale(LC_ALL,'it_IT');

$connection=new DB\DBAccess();
if (!$connection->openDBConnection()) {
    header('location: ../src/500.html');
    exit();
}


$nextArtShowQuery=$connection->getNextArtshow();
$connection->closeConnection();

$nextArtShow=
"<div class=\"cardmain\">
    <img
        src=\"".$nextArtShowQuery["image"]."\"
        class=\"cardmainimg\"
        id=\"imgnextartshow\"
        alt=\"".$nextArtShowQuery["title"]."\" />
    <div class=\"cardnextshow\">
        <h4 class=\"card_header\">La prossima mostra</h4>
        <p id=\"nextshow\">".$nextArtShowQuery["title"]."</p>
        <p>
            <time datetime=\"".$nextArtShowQuery["start_date"]."\">". DateManager::toDMY($nextArtShowQuery["start_date"]) ."</time>
            /
            <time datetime=\"".$nextArtShowQuery["end_date"]."\">". DateManager::toDMY($nextArtShowQuery["end_date"]) ."</time>
        </p>
        <a href=\"mostra.php?id=".$nextArtShowQuery["id"]."\"> Scoprila </a>
    </div>
</div>";

$ret = file_get_contents('../templates/index.html');
/*Se non sono presenti mostre al momento o in futuro, viene saltata la card*/
echo str_replace("{{prossimaMostra}}",$nextArtShow == ""? "":$nextArtShow,$ret);
?>