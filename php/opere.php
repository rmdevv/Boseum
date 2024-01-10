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
        $labelsContainer .= "
        <li>
            <input
                type=\"checkbox\"
                class=\"label_checkbox\"
                id=\"".$labelName."\"
                value=\"".$label['label']."\"
                name=\"".$labelName."\" >
            <label for=\"".$labelName."\">".ucfirst($label['label'])."</label>
        </li>";

        if(isset($_GET[$labelName])) array_push( $labelSearch, $_GET[$labelName]);
        
    }
    $labelsContainer .= "</ul>";
}


$artworks = $connection->getArtworksQuery($titleSearch, $dateSearch, $heightSearch, $widthSearch, $depthSearch, $labelSearch);

$connection->closeConnection();




    


if(!$artworks|| sizeof($artworks) <= 0){
    $opere = file_get_contents("../templates/opere.html");
    $opere = str_replace("{{title}}", $titleSearch, $opere);
    $opere = str_replace("{{labels}}", $labelsContainer, $opere);
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

    

    $opere = file_get_contents("../templates/opere.html");
    $opere = str_replace("{{title}}", $titleSearch, $opere);
    $opere = str_replace("{{labels}}", $labelsContainer, $opere);
    $opere = str_replace("{{count}}", $artworksCount, $opere);
    $opere = str_replace("{{results}}", $figuresContainer, $opere);
    echo($opere);
}
?>