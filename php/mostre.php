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

if (!isset($_GET["start_date"]) && !isset($_GET["end_date"])) {
	
    $resultsArtshow = $connection->getArtshowsNextMonth();

    $connection->closeConnection();

    $resultsArtshowContainer = '';
    $size = '';
    if($resultsArtshow && sizeof($resultsArtshow) > 0){
        $size = strval(sizeof($resultsArtshow));
        foreach($resultsArtshow as $resultArtshow){
            $start_date_reverse = $resultArtshow['start_date'];
            $end_date_reverse = $resultArtshow['end_date'];
            $start_date = DateManager::toDMY($start_date_reverse);
            $end_date = DateManager::toDMY($end_date_reverse);
            $resultsArtshowContainer .= "<div class=\"gallery_item\">
                        <div class=\"artshow_gallery_item\">
                            <div class=\"artshow_gallery_item_image\">
                                <a
                                    aria-hidden=\"true\"
                                    tabindex=\"-1\"
                                    href=\"mostra.php?id=".$resultArtshow['id']."\">
                                    <img
                                        src=\"".$resultArtshow['image']."\"
                                        alt=\"".$resultArtshow['title']."\" />
                                </a>
                            </div>
                            <div class=\"artshow_gallery_item_info\">
                                <div class=\"artshow_gallery_item_title\">
                                    <a href=\"mostra.php?id=".$resultArtshow['id']."\" title=\"".$resultArtshow['title']."\">
                                        ".$resultArtshow['title']."
                                    </a>
                                </div>
                                <div class=\"artshow_gallery_item_dates\">
                                    <p>
                                        <time datetime=\"".$resultArtshow['start_date']."\">
                                            ".$start_date."
                                        </time>
                                    </p>
                                    <p>
                                        <time datetime=\"".$resultArtshow['start_date']."\">
                                            ".$end_date."
                                        </time>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>";
        }
    } else {
        $size = strval(sizeof($resultsArtshow));
        $resultsArtshowContainer .= "<p>
            Non è stata trovata nessuna mostra.
        </p>";
    }

    $mostre = file_get_contents("../templates/mostre.html");
    $mostre = str_replace("{{count}}", $size, $mostre);
    $mostre = str_replace("{{artshow_items}}", $resultsArtshowContainer, $mostre);
    echo($mostre);
} else {
    $title = $_GET["titolo"];
    $startDate = $_GET["start_date"];
    $endDate = $_GET["end_date"];

    $resultsArtshow = $connection->getArtshowsInPeriod($startDate,$endDate);

    $connection->closeConnection();

    $resultsArtshowContainer = '';
    $size = '';
    if($resultsArtshow && sizeof($resultsArtshow) > 0){
        $size = strval(sizeof($resultsArtshow));
        foreach($resultsArtshow as $resultArtshow){
            $start_date_reverse = $resultArtshow['start_date'];
            $end_date_reverse = $resultArtshow['end_date'];
            $start_date = DateManager::toDMY($start_date_reverse);
            $end_date = DateManager::toDMY($end_date_reverse);
            $resultsArtshowContainer .= "<div class=\"gallery_item\">
                        <div class=\"artshow_gallery_item\">
                            <div class=\"artshow_gallery_item_image\">
                                <a
                                    aria-hidden=\"true\"
                                    tabindex=\"-1\"
                                    href=\"mostra.php?id=".$resultArtshow['id']."\">
                                    <img
                                        src=\"".$resultArtshow['image']."\"
                                        alt=\"".$resultArtshow['title']."\" />
                                </a>
                            </div>
                            <div class=\"artshow_gallery_item_info\">
                                <div class=\"artshow_gallery_item_title\">
                                    <a href=\"mostra.php?id=".$resultArtshow['id']."\" title=\"".$resultArtshow['title']."\">
                                        ".$resultArtshow['title']."
                                    </a>
                                </div>
                                <div class=\"artshow_gallery_item_dates\">
                                    <p>
                                        <time datetime=\"".$resultArtshow['start_date']."\">
                                            ".$start_date."
                                        </time>
                                    </p>
                                    <p>
                                        <time datetime=\"".$resultArtshow['start_date']."\">
                                            ".$end_date."
                                        </time>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>";
        }
    } else {
        $size = '0';
        $resultsArtshowContainer .= "<p>
            Non è stata trovata nessuna mostra.
        </p>";
    }

    $mostre = file_get_contents("../templates/mostre.html");
    $mostre = str_replace("{{count}}", $size, $mostre);
    $mostre = str_replace("{{artshow_items}}", $resultsArtshowContainer, $mostre);
    echo($mostre);
}



?>