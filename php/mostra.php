<?php

require_once 'DBAccess.php';
require_once 'DateManager.php';
require_once 'utils.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
setlocale(LC_ALL, 'it_IT');

session_start();
$isLoggedIn = isset($_SESSION['logged_id']);
$loginOrProfileTitle = $isLoggedIn ?
    "<a href=\"artista.php?id=" . $_SESSION['logged_id'] . "\"><span lang=\"en\">Account</span></a>"
    : "<a href=\"login.php\">Accedi</a>";

$connection = new DB\DBAccess();
if (!$connection->openDBConnection()) {
    header("location: ../php/500.php");
    exit();
}

if ($isLoggedIn && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['book'])) {
        $connection->insertPrenotation($_SESSION["logged_id"], $_POST["id_artshow"]);
    } else if (isset($_POST["cancel_book"])) {
        $connection->deletePrenotation($_SESSION["logged_id"], $_POST["id_artshow"]);
    }
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit();
}

if (!isset($_GET["id"])) {
    // header("location: mostre.php");
    exit();
}
$idArtshow = $_GET["id"];

$infoArtshow = $connection->getArtshow($idArtshow);
$partecipantsArtshow = $connection->getArtshowsPartecipants($idArtshow);

$prenotation = null;
if ($isLoggedIn && $idArtshow) {
    $prenotation = $connection->getLoggedUserPrenotationArtshow($_SESSION['logged_id'], $idArtshow);
}

$connection->closeConnection();

if (!$infoArtshow || sizeof($infoArtshow) <= 0) {
    header("location: ../php/404.php");
} else {
    $title = $infoArtshow[0]['title'];
    $description = $infoArtshow[0]['description'];
    $image = $infoArtshow[0]['image'];
    $startDateReverse = $infoArtshow[0]['start_date'];
    $startDate = DateManager::toDMY($startDateReverse);
    $endDateReverse = $infoArtshow[0]['end_date'];
    $endDate = DateManager::toDMY($endDateReverse);
    $partecipantsArtshowContainer = '<p>Attualmente nessun artista ha deciso di partecipare questa mostra.</p>';
    if ($partecipantsArtshow && sizeof($partecipantsArtshow) > 0) {
        $partecipantsArtshowContainer = "<h2 id=\"top_gallery\">Artisti partecipanti</h2><div class=\"artist_results_section\" id=\"paginated_section\">";
        foreach ($partecipantsArtshow as $partecipantArtshow) {

            $profileImage = $partecipantArtshow['image'] ? $partecipantArtshow['image'] : '../assets/images/default_user.svg';
            $partecipantsArtshowContainer .= "<div class=\"gallery_item\">
                    <div class=\"artist_gallery_item\">
                        <div class=\"artist_gallery_item_image\">
                            <a
                                href=\"artista.php?id=" . $partecipantArtshow['id'] . "\">
                                <img
                                    src=\"" . $profileImage . "\"
                                    alt=\"" . $partecipantArtshow['username'] . "\" />
                            </a>
                        </div>
                        <div class=\"artist_gallery_item_info\">
                            <div class=\"artist_gallery_item_title\">
                                <p>
                                " . $partecipantArtshow['name'] . " " . $partecipantArtshow['lastname'] . "
                                </p>
                            </div>
                            <div class=\"artist_mini_preview_info\">
                                <a
                                aria-hidden=\"true\"
                                tabindex=\"-1\"
                                href=\"artista.php?id=" . $partecipantArtshow['id'] . "\"
                                    >" . $partecipantArtshow['username'] . "</a
                                >
                            </div>
                        </div>
                    </div>
                </div>";
        }
        $partecipantsArtshowContainer .= "</div>" . addPaginator();
    }

    $prenotationSection = "";
    if ($isLoggedIn) {
        if ($startDateReverse && new DateTime($startDateReverse) > new DateTime()) {
            if ($_SESSION['is_admin']) {
                $prenotationSection = "
                    <div class=\"artist_button\">
                        <form action=\"crea_mostra.php\" method=\"post\">
                            <input type=\"hidden\" name=\"id_artshow\" value=\"$idArtshow\">
                            <button class=\"button_reverse\" type=\"submit\" name=\"modify_artshow\" aria-label=\"modifica mostra\">Modifica mostra</button>
                        </form>
                    </div>
                ";
            } else if (!$prenotation) {
                $prenotationSection = "
                    <form id=\"artshow_prenotation\"  method=\"post\">
                        <h2>Partecipa alla mostra</h2>
                        <p>
                            Unisciti all'esposizione con pochi <span lang=\"en\">click</span>!
                            Esponi le tue opere quando vuoi durante i giorni di apertura.
                        </p>
                        <input type=\"hidden\" name=\"id_artshow\" value=\"" . $idArtshow . "\">
                        <button class=\"button_reverse\" name=\"book\" id=\"book_button\">
                            Partecipa
                        </button>
                    </form>";
            } else {
                $prenotationSection = "
                    <form id=\"artshow_cancel_prenotation\"  method=\"post\">
                        <h2>Parteciperai alla mostra!</h2>
                        <p>
                            Data e orario in cui è stata effettuata la prenotazione: <time datetime=\"" . $prenotation[0]["time"] . "\">" . DateManager::toFormattedTimestamp($prenotation[0]["time"]) . "</time>
                        </p>
                        <input type=\"hidden\" name=\"id_artshow\" value=\"" . $idArtshow . "\">
                        <button class=\"button_reverse\" name=\"cancel_book\" id=\"cancel_book_button\">
                            Annulla iscrizione
                        </button>
                    </form>";
            }
        } else if ($endDateReverse && (new DateTime($endDateReverse))->format('Y-m-d') >= (new DateTime())->format('Y-m-d')) {
            $prenotationSection = "
                        <h2>Mostra in corso!</h2>
                        <p>Questa mostra è in corso ora e pronta ad affascinarti!
                        Non è necessaria alcuna prenotazione né richiesto l'acquisto di un biglietto.</p>";
        } else {
            $prenotationSection = "
                        <h2>Mostra conclusa</h2>
                        <p>Questa mostra è già conclusa. Speriamo tu possa partecipare alle prossime.</p>";
        }
    }

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

    echo ($mostra);
}
