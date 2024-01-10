<?php       //  manca ancora la logica per {{isBooked}} e {{isBooked_button}} per differenziare la parte di prenotazione alla mostra

require_once 'DBAccess.php';
require_once 'DateManager.php';

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
setlocale(LC_ALL,'it_IT');

$connection=new DB\DBAccess();

if (!$connection->openDBConnection()) {
    header("location: ../src/500.html");
    exit();
}

if (!isset($_GET["id"])) {
	header("location: mostre.php");
	exit();
}

$idArtshow = $_GET["id"];

$infoArtshow = $connection->getArtshow($idArtshow);
$partecipantsArtshow = $connection->getArtshowsPartecipants($idArtshow);

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
    $partecipantsArtshowContainer = '';
    if($partecipantsArtshow && sizeof($partecipantsArtshow) > 0){
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
    }

    $mostra = file_get_contents("../templates/mostra.html");
    $mostra = str_replace("{{name}}", $title, $mostra);
    $mostra = str_replace("{{start_date}}", $startDate, $mostra);
    $mostra = str_replace("{{start_date_reversed}}", $startDateReverse, $mostra);
    $mostra = str_replace("{{end_date}}", $endDate, $mostra);
    $mostra = str_replace("{{end_date_reversed}}", $endDateReverse, $mostra);
    $mostra = str_replace("{{description}}", $description, $mostra);
    $mostra = str_replace("{{image}}", $image, $mostra);
    $mostra = str_replace("{{partecipants}}", $partecipantsArtshowContainer, $mostra);

//  manca ancora la logica per {{isBooked}} e {{isBooked_button}} per differenziare la parte di prenotazione alla mostra

    echo($mostra);
}
?>