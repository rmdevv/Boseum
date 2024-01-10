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
if(isset($_GET["dipinto"])) array_push( $labelSearch, $_GET["dipinto"]);
if(isset($_GET["digitale"])) array_push( $labelSearch, $_GET["digitale"]);
if(isset($_GET["realismo"])) array_push( $labelSearch, $_GET["realismo"]);
if(isset($_GET["astrazione"])) array_push( $labelSearch, $_GET["astrazione"]);
if(isset($_GET["minimalismo"])) array_push( $labelSearch, $_GET["minimalismo"]);
if(isset($_GET["sketch"])) array_push( $labelSearch, $_GET["sketch"]);
if(isset($_GET["scultura"])) array_push( $labelSearch, $_GET["scultura"]);
if(isset($_GET["marmo"])) array_push( $labelSearch, $_GET["marmo"]);
if(isset($_GET["bronzo"])) array_push( $labelSearch, $_GET["bronzo"]);
if(isset($_GET["oggetto"])) array_push( $labelSearch, $_GET["oggetto"]);
if(isset($_GET["architettura"])) array_push( $labelSearch, $_GET["architettura"]);
if(isset($_GET["paesaggio"])) array_push( $labelSearch, $_GET["paesaggio"]);
if(isset($_GET["natura"])) array_push( $labelSearch, $_GET["natura"]);
if(isset($_GET["ritratto"])) array_push( $labelSearch, $_GET["ritratto"]);
if(isset($_GET["movimento"])) array_push( $labelSearch, $_GET["movimento"]);
if(isset($_GET["bianco e nero"])) array_push( $labelSearch, $_GET["bianco e nero"]);
if(isset($_GET["sfumature"])) array_push( $labelSearch, $_GET["sfumature"]);
if(isset($_GET["mare"])) array_push( $labelSearch, $_GET["mare"]);
if(isset($_GET["notte"])) array_push( $labelSearch, $_GET["notte"]);
if(isset($_GET["inverno"])) array_push( $labelSearch, $_GET["inverno"]);
if(isset($_GET["arte contemporanea"])) array_push( $labelSearch, $_GET["arte contemporanea"]);

$artworks = $connection->getArtworksQuery($titleSearch, $dateSearch, $heightSearch, $widthSearch, $depthSearch, $labelSearch);
$connection->closeConnection();

if(!$artworks|| sizeof($artworks) <= 0){
    $opere = file_get_contents("../templates/opere.html");
    $opere = str_replace("{{title}}", $titleSearch, $opere);
    $opere = str_replace("{{count}}", "0", $opere);
    $opere = str_replace("{{results}}", "", $opere);
    echo($opere);
}else{
    $artworksCount = sizeof($artworks);
    $figureContainer = "<div class=\"results_section\" id=\"paginated_section\">";
    foreach($artworks as $artwork){
        $idArtowrk = $artwork['artistID'];
        $title = $artwork['title'];
        $mainImage = $artwork['main_image'];
        $idArtist = $artwork['artworkID'];
        $username = $artwork['username'];

        $figureContainer .= "
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
    $figureContainer .= "</div>";

    

    $opere = file_get_contents("../templates/opere.html");
    $opere = str_replace("{{title}}", $titleSearch, $opere);
    $opere = str_replace("{{count}}", $artworksCount, $opere);
    $opere = str_replace("{{results}}", $figureContainer, $opere);
    echo($opere);
}
?>