<?php

require_once 'DBAccess.php';
require_once 'DateManager.php';
require_once 'utils.php';

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
setlocale(LC_ALL,'it_IT');

session_start();
$isLoggedIn = isset($_SESSION['logged_id']);

if (!isset($_GET["id"])) {
	header("location: artisti.php");
	exit();
}

$idArtist = $_GET["id"];

$loginOrProfileTitle = $isLoggedIn ?
        ($idArtist == $_SESSION['logged_id'] ?
            '<span lang=\"en\">Account</span>'
            : "<a href=\"artista.php?id=".$_SESSION['logged_id']."\"><span lang=\"en\">Account</span></a>")
        : "<a href=\"login.php\">Accedi</a>";
$artistButtons = $isLoggedIn && $idArtist == $_SESSION['logged_id'] ?
                    "<div class=\"artist_button\">
                        <a href=\"modifica_profilo.php\">Modifica profilo</a>
                        <button id=\"logout_button\">Logout</button>
                    </div>"
                    : "";

$connection=new DB\DBAccess();
if (!$connection->openDBConnection()) {
    header("location: ../src/500.html");
    exit();
}

$infoArtistArtworks = $connection->getArtist($idArtist);
$labels = $connection->getArtistLabels($idArtist);
$artistNextArtshow = $connection->getNextArtshowOfArtist($idArtist);
$artworksPreview = $connection->getArtistArtworksPreview($idArtist);

$connection->closeConnection();

if(!$infoArtistArtworks || sizeof($infoArtistArtworks) <= 0){
    header("location: ../src/404.html");
}else{
    $name = $infoArtistArtworks[0]['name'];
    $lastname = $infoArtistArtworks[0]['lastname'];
    $username = $infoArtistArtworks[0]['username'];
    $image = $infoArtistArtworks[0]['image'];
    if(!$image){
        $image = '../assets/images/default_user.svg';
    }
    $biography = '';
    if ($infoArtistArtworks[0]['biography']) {
        $biography = "<article class=\"artist_description\"><h3>Biografia</h3><p>".$infoArtistArtworks[0]['biography']."</p></article>";
    }
    $experience = '';
    if ($infoArtistArtworks[0]['experience']) {
        $experience = "<article class=\"artist_description\"><h3>Esperienza</h3><p>".$infoArtistArtworks[0]['experience']."</p></article>";
    }

    $details = "<dl>";
    $insertDetails = false;
    if($infoArtistArtworks[0]['birth_date']) {
        $insertDetails = true;
        $details .= "<dt>Data di nascita</dt>
                            <dd>
                                <time datetime=\"".$infoArtistArtworks[0]['birth_date']."\">".DateManager::toDMY($infoArtistArtworks[0]['birth_date'])."</time>
                            </dd>";
    }
    if($infoArtistArtworks[0]['birth_place']) {
        $insertDetails = true;
        $details .= "<dt>Luogo di nascita</dt>
                            <dd>".$infoArtistArtworks[0]['birth_place']."</dd>";
    }
    if($labels && sizeof($labels) > 0){
        $insertDetails = true;
        $labelsContainer = "<dt>Stili artistici</dt><dd><ul class=\"label_list\">";
        foreach($labels as $label){
            $labelName = str_replace(" ", "", strtolower($label['label']));
            $labelsContainer .= "<li class=\"label\"><a href=\"opere.php?".$labelName."=".$label['label']."\">".ucfirst($label['label'])."</a></li>";
        }
        $labelsContainer .= "</ul></dd></dl>";
        $details .= $labelsContainer;
    }

    if(!$insertDetails){
        $details = '';
    }
    

    $nextArtshow = "<p>L'artista non parteciperà a nessuna prossima mostra.</p>";
    if($artistNextArtshow && sizeof($artistNextArtshow) > 0){
        $startDateReverse = DateManager::toDMY($artistNextArtshow[0]['start_date']);
        $endDateReverse = DateManager::toDMY($artistNextArtshow[0]['end_date']);
        $nextArtshow = "<div class=\"gallery_item\">
                            <div class=\"artshow_gallery_item\">
                                <div class=\"artshow_gallery_item_image\">
                                    <a
                                        aria-hidden=\"true\"
                                        tabindex=\"-1\"
                                        href=\"mostra.php?id=".$artistNextArtshow[0]['id']."\">
                                        <img
                                            src=\"".$artistNextArtshow[0]['image']."\"
                                            alt=\"\" />
                                    </a>
                                </div>
                                <div class=\"artshow_gallery_item_info\">
                                    <div class=\"artshow_gallery_item_title\">
                                        <a href=\"".$artistNextArtshow[0]['id']."\">
                                            ".$artistNextArtshow[0]['title']."
                                        </a>
                                    </div>
                                    <div class=\"artshow_gallery_item_dates\">
                                        <p>
                                            <time datetime=\"".$artistNextArtshow[0]['start_date']."\">
                                                ".$startDateReverse."
                                            </time>
                                        </p>
                                        <p>
                                            <time datetime=\"".$artistNextArtshow[0]['end_date']."\">
                                                ".$endDateReverse."
                                            </time>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>";
    }

    $artworks = "<p>L'artista non ha ancora caricato nessuna opera.</p>";
    if($artworksPreview && sizeof($artworksPreview) > 0){
        $artworks = "<div class=\"results_section\" id=\"paginated_section\">";
        foreach($artworksPreview as $artwork){
            $artworks .= "<figure class=\"gallery_item\">
                        <div class=\"artwork_gallery_item_image\">
                            <a
                                aria-hidden=\"true\"
                                tabindex=\"-1\"
                                href=\"opera.php?id=".$artwork['id']."\">
                                <img
                                    src=\"".$artwork['main_image']."\"
                                    alt=\"".$artwork['title']."\" />
                            </a>
                        </div>
                        <figcaption>
                            <div class=\"artwork_gallery_item_title\">
                                <a href=\"opera.php?id=".$artwork['id']."\">"
                                .$artwork['title'].
                                "</a>
                            </div>
                        </figcaption>
                    </figure>";
        }
        $artworks .= "</div>".addPaginator();
    }

    $artista = file_get_contents("../templates/artista.html");
    $artista = str_replace("{{login_or_profile_title}}", $loginOrProfileTitle, $artista);
    $artista = str_replace("{{name}}", $name, $artista);
    $artista = str_replace("{{lastname}}", $lastname, $artista);
    $artista = str_replace("{{username}}", $username, $artista);
    $artista = str_replace("{{artist_buttons}}", $artistButtons, $artista);
    $artista = str_replace("{{image}}", $image, $artista);
    $artista = str_replace("{{details}}", $details, $artista);
    $artista = str_replace("{{biography}}", $biography, $artista);
    $artista = str_replace("{{experience}}", $experience, $artista);
    $artista = str_replace("{{next_artshow}}", $nextArtshow, $artista);
    $artista = str_replace("{{artworks}}", $artworks, $artista);
    echo($artista);
}
?>