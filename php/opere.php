<?php

require_once 'DBAccess.php';
require_once 'DateManager.php';

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
setlocale(LC_ALL,'it_IT');

$connection=new DB\DBAccess();

if (!$connection->openDBConnection()) {
    // redirect to 500.html
    header("location: ../src/500.html");
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
if($labels && sizeof($labels) > 0){
    $labelsContainer = "<ul id=\"label_list\">";
    foreach($labels as $label){
        $labelName = str_replace(" ", "", strtolower($label['label']));
        $labelChecked = isset($_GET[$labelName]) ? "checked" : "";
        $labelsContainer .= "
        <li>
            <input
                type=\"checkbox\"
                class=\"label_checkbox\"
                id=\"".$labelName."\"
                value=\"".$label['label']."\"
                name=\"".$labelName."\" 
                ".$labelChecked.">
            <label for=\"".$labelName."\">".ucfirst($label['label'])."</label>
        </li>";

        if(isset($_GET[$labelName])) array_push( $labelSearch, $_GET[$labelName]);
        
    }
    $labelsContainer .= "</ul>";
}


$artworks = $connection->getArtworksQuery($titleSearch, $dateSearch, $heightSearch, $widthSearch, $depthSearch, $labelSearch);

$connection->closeConnection();

$dateRanges = array("1960-1970", "1970-1980", "1990-2000", "2000-2005", "2005-2010", "2010-2015", "2015-2020", "2020-2024");
$dateOptions = "<option value=\"\">-</option>";
foreach ($dateRanges as $date) {
    $dateSelected = $dateSearch == $date ? " selected": "";
    $dateOptions .= "<option value=\"".$date."\"".$dateSelected.">".$date."</option>";
}

$heightRanges = array("0-20", "20-50", "50-80", "80-150", "150-300");
$heightOptions = "<option value=\"\">-</option>";
foreach ($heightRanges as $height) {
    $heightSelected = $heightSearch == $height ? " selected": "";
    $heightOptions .= "<option value=\"".$height."\"".$heightSelected.">".$height."</option>";
}

$widthRanges = array("0-20", "20-50", "50-80", "80-150", "150-300");
$widthOptions = "<option value=\"\">-</option>";
foreach ($widthRanges as $width) {
    $widthSelected = $widthSearch == $width ? " selected": "";
    $widthOptions .= "<option value=\"".$width."\"".$widthSelected.">".$width."</option>";
}

$depthRanges = array("0-20", "20-50", "50-80", "80-150", "150-300");
$depthOptions = "<option value=\"\">-</option>";
foreach ($depthRanges as $depth) {
    $depthSelected = $depthSearch == $depth ? " selected": "";
    $depthOptions .= "<option value=\"".$depth."\"".$depthSelected.">".$depth."</option>";
}


$opere = file_get_contents("../templates/opere.html");
$opere = str_replace("{{title}}", $titleSearch, $opere);
$opere = str_replace("{{date}}", $dateOptions, $opere);
$opere = str_replace("{{height}}", $heightOptions, $opere);
$opere = str_replace("{{width}}", $widthOptions, $opere);
$opere = str_replace("{{depth}}", $depthOptions, $opere);
$opere = str_replace("{{labels}}", $labelsContainer, $opere);

if(!$artworks|| sizeof($artworks) <= 0){
    $opere = str_replace("{{count}}", "0", $opere);
    $opere = str_replace("{{results}}", "", $opere);
    echo($opere);
}else{
    $artworksCount = sizeof($artworks);
    $figuresContainer = "<div class=\"results_section\" id=\"paginated_section\">";
    foreach($artworks as $artwork){
        $idArtowrk = $artwork['artistID'];
        $title = $artwork['title'];
        $mainImage = $artwork['main_image'];
        $idArtist = $artwork['artworkID'];
        $username = $artwork['username'];

        $figuresContainer .= "
        <figure class=\"gallery_item\">
            <div class=\"artwork_gallery_item_image\">
                <a aria-hidden=\"true\" tabindex=\"-1\" href=\"opera.php?id=".$idArtowrk."\">
                    <img src=\"".$mainImage."\" alt=\"".$title."\" />
                </a>
            </div>
            <figcaption>
                <div class=\"artwork_gallery_item_title\">
                    <a title=\"".$title."\" href=\"opera.php?id=".$idArtowrk."\">".$title."</a>
                </div>
                <div class=\"artist_mini_preview_info\">
                    <a href=\"artista.php?id=".$idArtist."\">".$username."</a>
                </div> 
            </figcaption>
        </figure>";
    }
    $figuresContainer .= "</div>";

    $opere = str_replace("{{count}}", $artworksCount, $opere);
    $opere = str_replace("{{results}}", $figuresContainer, $opere);
    echo($opere);
}
?>