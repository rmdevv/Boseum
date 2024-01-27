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

$titleFilter = isset($_GET["title"]) ? $_GET["title"] : "";
$startDateFilter = isset($_GET["start_date"]) ? $_GET["start_date"] : "";
$endDateFilter = isset($_GET["end_date"]) ? $_GET["end_date"] : "";

$artshows = $connection->getArtshowsQuery($titleFilter, $startDateFilter, $endDateFilter);

$connection->closeConnection();

$artshowsContainer = "";
if ($artshows && sizeof($artshows) > 0) {
    $artshowsContainer = '<div class="artshow_results_section" id="paginated_section">';
    foreach ($artshows as $artshow) {
        $start_date_reverse = $artshow['start_date'];
        $end_date_reverse = $artshow['end_date'];
        $start_date = DateManager::toDMY($start_date_reverse);
        $end_date = DateManager::toDMY($end_date_reverse);
        $artshowsContainer .= "
            <div class=\"gallery_item\">
                <a
                href=\"mostra.php?id=" . $artshow['id'] . "\">
                <div class=\"artshow_gallery_item\">
                    <div class=\"artshow_gallery_item_image\">
                        <img
                            src=\"" . $artshow['image'] . "\"
                            alt=\"" . $artshow['title'] . "\" />
                    </div>
                    <div class=\"artshow_gallery_item_info\">
                        <div class=\"artshow_gallery_item_title\">
                            <h3>
                                " . $artshow['title'] . "
                            </h3>
                        </div>
                        <div class=\"artshow_gallery_item_dates\">
                            <p>
                                <time datetime=\"" . $artshow['start_date'] . "\">
                                    " . $start_date . "
                                </time>
                            </p>
                            <p>
                                <time datetime=\"" . $artshow['start_date'] . "\">
                                    " . $end_date . "
                                </time>
                            </p>
                        </div>
                    </div>
                </div>
            </a>
            </div>";
    }
    $artshowsContainer .= '</div>' . addPaginator();
}

$mostre = file_get_contents("../templates/mostre.html");
$mostre = str_replace("{{login_or_profile_title}}", $loginOrProfileTitle, $mostre);
$mostre = str_replace("{{title}}", $titleFilter, $mostre);
$mostre = str_replace("{{start_date}}", $startDateFilter, $mostre);
$mostre = str_replace("{{end_date}}", $endDateFilter, $mostre);
$mostre = str_replace("{{count}}", $artshows ? sizeof($artshows) : 0, $mostre);
$mostre = str_replace("{{artshow_items}}", $artshowsContainer, $mostre);
echo ($mostre);
