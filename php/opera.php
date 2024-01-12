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
    header("location: ../php/500.php");
    exit();
}

if (!isset($_GET["id"])) {
	header("location: opere.php");
	exit();
}

$idArtwork = $_GET["id"];


$infoArtworkArtist = $connection->getArtworkWithArtist($idArtwork);
$labels = $connection->getArtworkLabels($idArtwork);
$additionalImages = $connection->getArtworkAdditionalImages($idArtwork);
$similarArtworks = $connection->getSimilarArtworks($idArtwork);

$connection->closeConnection();

if(!$infoArtworkArtist || sizeof($infoArtworkArtist) <= 0){
    header("location: ../php/404.php");
}else{
    $labelsContainer = '';
    if($labels && sizeof($labels) > 0){
        $labelsContainer = "<h3>Labels</h3><ul class=\"label_list\">";
        foreach($labels as $label){
            $labelName = str_replace(" ", "", strtolower($label['label']));
            $labelsContainer .= "<li class=\"label\"><a href=\"opere.php?".$labelName."=".$label['label']."\">".ucfirst($label['label'])."</a></li>";
        }
        $labelsContainer .= "</ul>";
    }

    $title = $infoArtworkArtist[0]['title'];
    $mainImage = $infoArtworkArtist[0]['main_image'];
    $description = $infoArtworkArtist[0]['description'];
    $id_artista = $infoArtworkArtist[0]['id_artist'];
    $username = $infoArtworkArtist[0]['username'];
    $user_name = $infoArtworkArtist[0]['name'];
    $user_lastname = $infoArtworkArtist[0]['lastname'];
    $src_artista = $infoArtworkArtist[0]['image'];
    if(!$src_artista){
        $src_artista = '../assets/images/default_user.svg';
    }
    $height = $infoArtworkArtist[0]['height'];
    $height == 0 ? $height = '-' : $height .= ' cm';
    $width = $infoArtworkArtist[0]['width'];
    $width == 0 ? $width = '-' : $width .= ' cm';
    $length = $infoArtworkArtist[0]['length'];
    $length == 0 ? $length = '-' : $length .= ' cm';

    $startDateReverse = $infoArtworkArtist[0]['start_date'];
    $startDate = DateManager::toDMY($startDateReverse);
    $endDateReverse = $infoArtworkArtist[0]['end_date'];
    $endDate = DateManager::toDMY($endDateReverse);
    $uploadTimeReversed = $infoArtworkArtist[0]['upload_time'];
    $uploadTime = DateManager::toFormattedTimestamp($uploadTimeReversed);

    $additionalImagesContainer = '';
    if($additionalImages && sizeof($additionalImages)> 0){
        $additionalImagesContainer = "<div class=\"additional_images_carousel\"
                        id=\"additional_images_carousel\"
                        aria-label=\"Slider di immagini di dettaglio\">
                        <div class=\"thumbnail_slide is_active\" tabindex=\"0\">
                            <img src=\"".$mainImage."\" alt=\"\" />
                        </div>";
        foreach($additionalImages as $additionalImage){
            $additionalImagesContainer .= "<div class=\"thumbnail_slide\" tabindex=\"0\">
                            <img src=\"".$additionalImage['image']."\" alt=\"\" />
                        </div>";
        }
        $additionalImagesContainer .= "</div>";
    }

    $similarArtworksContainer = '';
    if($similarArtworks && sizeof($similarArtworks) > 0){
        $similarArtworksContainer = "<div class=\"results_section\" id=\"paginated_section\">";
        foreach($similarArtworks as $similarArtwork){
            $similarArtworksContainer .= 
                    "<figure class=\"gallery_item\">
                        <div class=\"artwork_gallery_item_image\">
                            <a
                                aria-hidden=\"true\"
                                tabindex=\"-1\"
                                href=\"opera.php?id=".$similarArtwork['id']."\">
                                <img
                                    src=\"".$similarArtwork['main_image']."\"
                                    alt=\"".$similarArtwork['title']."\" />
                            </a>
                        </div>
                        <figcaption>
                            <div class=\"artwork_gallery_item_title\">
                                <a href=\"opera.php?id=".$similarArtwork['id']."\">"
                                .$similarArtwork['title'].
                                "</a>
                            </div>
                            <div class=\"artist_mini_preview_info\">
                                <a href=\"artista.php?id=".$similarArtwork['artist_id']."\">".$similarArtwork['username']."</a>
                            </div>
                        </figcaption>
                    </figure>";
        }
        $similarArtworksContainer .= "</div>".addPaginator();

        $artworkButtons = "";
        if($isLoggedIn && ($id_artista == $_SESSION['logged_id'] || $_SESSION['is_admin'])) {
            $artworkButtons = "<div class=\"artist_button\">
                        <a href=\"crea_opera.php?id=".$idArtwork."\">Modifica opera</a>
                    </div>";
        }
    }

    $opera = file_get_contents("../templates/opera.html");
    $opera = str_replace("{{login_or_profile_title}}", $loginOrProfileTitle, $opera);
    $opera = str_replace("{{title}}", $title, $opera);
    $opera = str_replace("{{artwork_buttons}}", $artworkButtons, $opera);
    $opera = str_replace("{{id_artista}}", $id_artista, $opera);
    $opera = str_replace("{{src_user}}", $src_artista, $opera);
    $opera = str_replace("{{user_name}}", $user_name, $opera);
    $opera = str_replace("{{user_lastname}}", $user_lastname, $opera);
    $opera = str_replace("{{username}}", $username, $opera);
    $opera = str_replace("{{description}}", $description, $opera);
    $opera = str_replace("{{main_image}}", $mainImage, $opera);
    $opera = str_replace("{{labels}}", $labelsContainer, $opera);
    $opera = str_replace("{{height}}", $height, $opera);
    $opera = str_replace("{{width}}", $width, $opera);
    $opera = str_replace("{{length}}", $length, $opera);
    $opera = str_replace("{{start_date}}", $startDate, $opera);
    $opera = str_replace("{{start_date_reversed}}", $startDateReverse, $opera);
    $opera = str_replace("{{end_date}}", $endDate, $opera);
    $opera = str_replace("{{end_date_reversed}}", $endDateReverse, $opera);
    $opera = str_replace("{{upload_time}}", $uploadTime, $opera);
    $opera = str_replace("{{upload_time_reversed}}", $uploadTimeReversed, $opera);
    $opera = str_replace("{{additional_images}}", $additionalImagesContainer, $opera);
    $opera = str_replace("{{similar_images}}", $similarArtworksContainer, $opera);
    echo($opera);
}
?>