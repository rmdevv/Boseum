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
    "<a href=\"artista.php?id=" . $_SESSION['logged_id'] . "\"><span lang=\"en\">Account</span></a>" :
    "<a href=\"login.php\">Accedi</a>";

$connection = new DB\DBAccess();
if (!$connection->openDBConnection()) {
    header("location: ../php/500.php");
    exit();
}

$titleSearch = isset($_GET["title"]) ? $_GET["title"] : "";
$dateSearch = isset($_GET["date"]) ? $_GET["date"] : "";
$heightSearch =  isset($_GET["height"]) ? $_GET["height"] : "";
$widthSearch = isset($_GET["width"]) ? $_GET["width"] : "";
$depthSearch = isset($_GET["depth"]) ? $_GET["depth"] : "";
$labelSearch = array();
$labels = $connection->getLabels();

$labelsContainer = '';
if ($labels && sizeof($labels) > 0) {
    $labelsContainer = "<ul id=\"labels_list\">";
    foreach ($labels as $label) {
        $labelChecked = isset($_GET[$label['label']]) ? "checked" : "";
        $labelsContainer .= "
        <li>
            <input
                type=\"checkbox\"
                class=\"label_checkbox\"
                id=\"" . $label['label'] . "\"
                value=\"" . $label['label'] . "\"
                name=\"" . $label['label'] . "\" 
                " . $labelChecked . ">
            <label for=\"" . $label['label'] . "\">" . ucfirst($label['label']) . "</label>
        </li>";

        if (isset($_GET[$label['label']])) array_push($labelSearch, $_GET[$label['label']]);
    }
    $labelsContainer .= "</ul>";
}


$artworks = $connection->getArtworksQuery($titleSearch, $dateSearch, $heightSearch, $widthSearch, $depthSearch, $labelSearch);

$connection->closeConnection();

$dateRanges = array("1960-1970", "1970-1980", "1990-2000", "2000-2005", "2005-2010", "2010-2015", "2015-2020", "2020-2024");
$dateOptions = "<option value=\"\">-</option>";
foreach ($dateRanges as $date) {
    $dateSelected = $dateSearch == $date ? " selected" : "";
    $dateOptions .= "<option value=\"" . $date . "\"" . $dateSelected . ">" . $date . "</option>";
}

$heightRanges = array("0-20", "20-50", "50-80", "80-150", "150-300");
$heightOptions = "<option value=\"\">-</option>";
foreach ($heightRanges as $height) {
    $heightSelected = $heightSearch == $height ? " selected" : "";
    $heightOptions .= "<option value=\"" . $height . "\"" . $heightSelected . ">" . $height . "</option>";
}

$widthRanges = array("0-20", "20-50", "50-80", "80-150", "150-300");
$widthOptions = "<option value=\"\">-</option>";
foreach ($widthRanges as $width) {
    $widthSelected = $widthSearch == $width ? " selected" : "";
    $widthOptions .= "<option value=\"" . $width . "\"" . $widthSelected . ">" . $width . "</option>";
}

$depthRanges = array("0-20", "20-50", "50-80", "80-150", "150-300");
$depthOptions = "<option value=\"\">-</option>";
foreach ($depthRanges as $depth) {
    $depthSelected = $depthSearch == $depth ? " selected" : "";
    $depthOptions .= "<option value=\"" . $depth . "\"" . $depthSelected . ">" . $depth . "</option>";
}

$figuresContainer = "";
if ($artworks && sizeof($artworks) > 0) {
    $figuresContainer = "<div class=\"results_section\" id=\"paginated_section\">";
    foreach ($artworks as $artwork) {
        $idArtowrk = $artwork['artistID'];
        $title = $artwork['title'];
        $mainImage = $artwork['main_image'];
        $idArtist = $artwork['artworkID'];
        $username = $artwork['username'];

        $figuresContainer .= "
        <figure class=\"gallery_item\">
            <div class=\"artwork_gallery_item_image\">
                <a
                    href=\"opera.php?id=" . $idArtowrk . "\">
                    <img src=\"" . $mainImage . "\" />
                </a>
            </div>
            <figcaption>
                <div class=\"artwork_gallery_item_title\">
                    <h3>$title</h3>
                </div>
                <div class=\"artist_mini_preview_info\">
                    <a href=\"artista.php?id=" . $idArtist . "\">" . $username . "</a>
                </div> 
            </figcaption>
        </figure>";
    }
    $figuresContainer .= "</div>" . addPaginator();
}

$opere = file_get_contents("../templates/opere.html");
$opere = str_replace("{{login_or_profile_title}}", $loginOrProfileTitle, $opere);
$opere = str_replace("{{title}}", $titleSearch, $opere);
$opere = str_replace("{{date}}", $dateOptions, $opere);
$opere = str_replace("{{height}}", $heightOptions, $opere);
$opere = str_replace("{{width}}", $widthOptions, $opere);
$opere = str_replace("{{depth}}", $depthOptions, $opere);
$opere = str_replace("{{labels}}", $labelsContainer, $opere);
$opere = str_replace("{{count}}", $artworks ? sizeof($artworks) : 0, $opere);
$opere = str_replace("{{results}}", $figuresContainer, $opere);
echo ($opere);
