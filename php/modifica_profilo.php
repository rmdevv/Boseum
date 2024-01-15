<?php
require_once 'DBAccess.php';
require_once 'utils.php';
require_once 'DateManager.php';

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
setlocale(LC_ALL,'it_IT');

session_start();
$id=$_SESSION["logged_id"] ?? '';

if($_SESSION['is_admin']){
    /*TODO da modificare (forse) codice modifica profilo per admin, per esempio aggiunta opere*/
    $id=$_GET["id"] ?? '';
}

if($id!=""){
    $artistButtons = "
    <div class=\"artist_button\">
        <form action=\"artista.php\" method=\"get\">
            <input type=\"hidden\" name=\"id\" value=\"$id\">
            <button type=\"submit\" aria-label=\"annulla modifica profilo\">Annulla</button>
        </form>
        <a href=\"modifica_dati_profilo.php\">Modifica Dati profilo</a>
    ";
}
else{
    header("location: 404.php");
    exit();
}
$artistButtons=$artistButtons."
<a href=\"crea_opera.php\">Aggiungi opera</a>
</div>
";

$connection=new DB\DBAccess();
if (!$connection->openDBConnection()) {
    header("location: 500.php");
    exit();
}

$infoArtistArtworks = $connection->getArtist($id);
$labels = $connection->getArtistLabels($id);
$artistNextArtshow = $connection->getNextArtshowOfArtist($id);
$artworksPreview = $connection->getArtistArtworksPreview($id);

$connection->closeConnection();

if(!$infoArtistArtworks || sizeof($infoArtistArtworks) <= 0){
    header("location: 404.php");
    exit();
}

$nextArtshow = "<a href=\"mostre.php#top_gallery\">scegli una mostra a cui partecipare</a>";
if($artistNextArtshow && sizeof($artistNextArtshow) > 0){
    $startDateReverse = DateManager::toDMY($artistNextArtshow[0]['start_date']);
    $endDateReverse = DateManager::toDMY($artistNextArtshow[0]['end_date']);
    $nextArtshow = $nextArtshow.
                    "<div class=\"gallery_item\">
                        <div class=\"artshow_gallery_item\">
                            <div class=\"artshow_gallery_item_image\">
                                <a
                                    href=\"mostra.php?id=".$artistNextArtshow[0]['id']."\">
                                    <img
                                        src=\"".$artistNextArtshow[0]['image']."\"
                                        alt=\"\" />
                                </a>
                            </div>
                            <div class=\"artshow_gallery_item_info\">
                                <div class=\"artshow_gallery_item_title\">
                                    <a
                                        aria-hidden=\"true\"
                                        tabindex=\"-1\"
                                        href=\"mostra.php?id=".$artistNextArtshow[0]['id']."\">
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


/*TODO da modificare*/
$artworks = "<p>L'artista non ha ancora caricato nessuna opera.</p>";
if($artworksPreview && sizeof($artworksPreview) > 0){
    $artworks = "<div class=\"results_section\" id=\"paginated_section\">";
    foreach($artworksPreview as $artwork){
        $artworks .= "<figure class=\"gallery_item\">
                    <div class=\"artwork_gallery_item_image\">
                        <a
                            href=\"modifica_opera.php?id=".$artwork['id']."\">
                            <img
                                src=\"".$artwork['main_image']."\"
                                alt=\"".$artwork['title']."\" />
                        </a>
                    </div>
                    <figcaption>
                    <div class=\"artwork_gallery_item_title\">
                    <a
                    aria-hidden=\"true\"
                    tabindex=\"-1\"
                    href=\"modifica_opera.php?id=".$artwork['id']."\">"
                    .$artwork['title'].
                    "</a>
                </div>
                    </figcaption>
                </figure>";
    }
    $artworks .= "</div>".addPaginator();
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
    $labelsContainer = "<dt>Stili artistici</dt><dd id=\"artist_labels\"><ul class=\"label_list\">";
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


$ret = file_get_contents("../templates/artista.html");
/*TODO da definire*/

/*Definiti*/
$ret = str_replace("{{details}}",$details,$ret);
$ret = str_replace("{{artworks}}",
"<p id=\"aiuto_modifica\">Clicca sull'immagine dell'opera che vuoi modificare o eliminare</p>".$artworks
,$ret);
$ret = str_replace("{{next_artshow}}",$nextArtshow,$ret);
$ret = str_replace("{{biography}}",$infoArtistArtworks[0]['biography']==''?
'':"<article class=\"artist_description\"><h3>Biografia</h3><p>".$infoArtistArtworks[0]['biography']."</p></article>"
,$ret);
$ret = str_replace("{{experience}}",$infoArtistArtworks[0]['experience']==''?
'':"<article class=\"artist_description\"><h3>Esperienza</h3><p>".$infoArtistArtworks[0]['experience']."</p></article>"
,$ret);
$ret = str_replace("{{image}}",$infoArtistArtworks[0]['image']??'../assets/images/default_user.svg', $ret);

$ret = str_replace("{{username}}",$infoArtistArtworks[0]['username'],$ret);
$ret = str_replace("{{name}}",$infoArtistArtworks[0]['name'],$ret);
$ret = str_replace("{{lastname}}",$infoArtistArtworks[0]['lastname'],$ret);
$ret = str_replace("{{login_or_profile_title}}","Profilo",$ret);
$ret = str_replace("{{artist_buttons}}",$artistButtons,$ret);
echo($ret);
?>