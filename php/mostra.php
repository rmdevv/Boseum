<?php

require_once 'DBAccess.php';
require_once 'DateManager.php';
require_once 'utils.php';

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
    header("location: ../src/500.html");
    exit();
}

if (isset($_POST['book'])) {
    // TODO:INSERT PRENOTATION
} else if (isset($_POST["cancel_book"])) {
    // TODO:DELETE PRENOTATION
}

if (!isset($_GET["id"])) {
	header("location: mostre.php");
	exit();
}

$idArtshow = $_GET["id"];

$infoArtshow = $connection->getArtshow($idArtshow);
$partecipantsArtshow = $connection->getArtshowsPartecipants($idArtshow);

$prenotation = null;
if($isLoggedIn && $idArtshow){
    $prenotation = $connection->getLoggedUserPrenotationArtshow($_SESSION['logged_id'], $idArtshow);
}

$connection->closeConnection();

if(!$infoArtshow || sizeof($infoArtshow) <= 0){
    header("location: ../src/404.html");
}else{
    $title = $infoArtshow[0]['title'];
    $description = $infoArtshow[0]['description'];
    $image = $infoArtshow[0]['image'];
    $startDateReverse = $infoArtshow[0]['start_date'];
    $startDate = DateManager::toDMY($startDateReverse);
    $endDateReverse = $infoArtshow[0]['end_date'];
    $endDate = DateManager::toDMY($endDateReverse);
    $partecipantsArtshowContainer = '<p>Attualmente nessun artista ha deciso di partecipare questa mostra.</p>';
    if($partecipantsArtshow && sizeof($partecipantsArtshow) > 0){
        $partecipantsArtshowContainer = "<h2 id=\"top_gallery\">Artisti partecipanti</h2><div class=\"artist_results_section\" id=\"paginated_section\">";
        foreach($partecipantsArtshow as $partecipantArtshow){
            $partecipantsArtshowContainer .= "<div class=\"gallery_item\">
                    <div class=\"artist_gallery_item\">
                        <div class=\"artist_gallery_item_image\">
                            <a
                                aria-hidden=\"true\"
                                tabindex=\"-1\"
                                href=\"profilo.php?id=".$partecipantArtshow['id']."\">
                                <img
                                    src=\"profilo.php?id=".$partecipantArtshow['image']."\"
                                    alt=\"".$partecipantArtshow['username']."\" />
                            </a>
                        </div>
                        <div class=\"artist_gallery_item_info\">
                            <div class=\"artist_gallery_item_title\">
                                <p>
                                ".$partecipantArtshow['name']." ".$partecipantArtshow['last']."
                                </p>
                            </div>
                            <div class=\"artist_mini_preview_info\">
                                <a href=\"profilo.php?id=".$partecipantArtshow['id']."\"
                                    >".$partecipantArtshow['username']."</a
                                >
                            </div>
                        </div>
                    </div>
                </div>";
        }
        $partecipantsArtshowContainer .= "</div>".addPaginator();
    }

    $prenotationSection = "";
    !$prenotation ? ($isLoggedIn ? $prenotationSection = "
            <form id=\"artshow_prenotation\">
                <h2>Partecipa alla mostra</h2>
                <p>
                    Unisciti all'esposizione con pochi <span lang=\"en\">click</span>!
                    Esponi le tue opere quando vuoi durante i giorni di apertura.
                </p>
                <button class=\"button_reverse\" name=\"book\" id=\"book_button\">
                    Partecipa
                </button>
            </form>" :
            "") :
        $prenotationSection = "
        <form id=\"artshow_prenotation\">
            <h2>Partecipa alla mostra</h2>
            <p>
                Data e orario in cui è stata fatta la prenotazione: <time datetime=\"".$prenotation[0]["time"]."\">".DateManager::toFormattedTimestamp($prenotation[0]["time"])."</time>
            </p>
            <button class=\"button_reverse\" name=\"cancel_book\" id=\"cancel_book_button\">
                Annulla iscrizione
            </button>
        </form>";

    $mostra = file_get_contents("../templates/mostra.html");
    $mostra = str_replace("{{login_or_profile_title}}", $loginOrProfileTitle, $mostra);
    $mostra = str_replace("{{name}}", $title, $mostra);
    $mostra = str_replace("{{start_date}}", $startDate, $mostra);
    $mostra = str_replace("{{start_date_reversed}}", $startDateReverse, $mostra);
    $mostra = str_replace("{{end_date}}", $endDate, $mostra);
    $mostra = str_replace("{{end_date_reversed}}", $endDateReverse, $mostra);
    $mostra = str_replace("{{description}}", $description, $mostra);
    $mostra = str_replace("{{image}}", $image, $mostra);
    $mostra = str_replace("{{prenotation}}", $prenotationSection, $mostra);
    $mostra = str_replace("{{partecipants}}", $partecipantsArtshowContainer, $mostra);

    echo($mostra);
}
?>