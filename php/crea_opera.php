<?php

require_once 'DBAccess.php';
require_once 'ImageProcessor.php';

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
setlocale(LC_ALL,'it_IT');

session_start();
$loginOrProfileTitle = "";
if (!isset($_SESSION['logged_id'])) {
    header('Location: ../php/login.php');
    exit();
}else{
    $loginOrProfileTitle = "<a href=\"artista.php?id=".$_SESSION['logged_id']."\"><span lang=\"en\">Account</span></a>";
}

$connection=new DB\DBAccess();
if (!$connection->openDBConnection()) {
    header("location: ../php/500.php");
    exit();
}

$labels = $connection->getLabels();
$connection->closeConnection();

$labelsContainer = '';
if($labels && sizeof($labels) > 0){
    $labelsContainer = "<ul id=\"labels_list\">";
    foreach($labels as $label){
        $labelName = str_replace(" ", "", strtolower($label['label']));
        $labelsContainer .= "
        <li>
            <input
                type=\"checkbox\"
                class=\"label_checkbox\"
                id=\"".$labelName."\"
                value=\"".$label['label']."\"
                name=\"".$labelName."\">
            <label for=\"".$labelName."\">".ucfirst($label['label'])."</label>
        </li>";
    }
    $labelsContainer .= "</ul>";
}

$creaOpera = file_get_contents("../templates/crea_opera.html");
$creaOpera = str_replace("{{login_or_profile_title}}", $loginOrProfileTitle, $creaOpera);
$creaOpera = str_replace("{{labels}}", $labelsContainer, $creaOpera);

if (isset($_POST['addartwork'])) {

    $imagesDir = "../uploads/artworks/";

    $mainImage = null;
    if ($_FILES["main_image"] && sizeof($_FILES["main_image"]) > 0) {
        $mainImage = ImageProcessor::processImage($_FILES["main_image"]);
    }
    $additionalImagesPath = array();
    if ($_FILES["additional_images"] && sizeof($_FILES["additional_images"]) > 0) {
        $additionalImagesPath = ImageProcessor::processImages($_FILES["additional_images"]);
    }

    $idArtist = $_SESSION['logged_id'];
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $startDate = $_POST['start_date'] ?? '';
    $endDate = $_POST['end_date'] ?? '';
    $height = $_POST['height'] ?? '';
    $width = $_POST['width'] ?? '';
    $depth = $_POST['depth'] ?? '';
    $labelsArtwork = array();

    foreach($labels as $label){
        $labelName = str_replace(" ", "", strtolower($label['label']));
        if(isset($_POST[$labelName])) array_push($labelsArtwork, $label['label']);
    }

    if (empty($title) || empty($mainImage) || empty($description)) {
        // TODO: Messaggio errore (non dovrebbe mai accadere per i campi required)
        echo "Parametri non sufficienti";
        exit();
    }else {
        $connection=new DB\DBAccess();
        if (!$connection->openDBConnection()) {
            header("location: ../php/500.php");
            exit();
        }

        $addArtwork = $connection->insertNewArtwork($title, $mainImage, $description, $height, $width, $depth, $startDate, $endDate, $idArtist, $additionalImagesPath, $labelsArtwork);
        $connection->closeConnection();

        if(!$addArtwork){
            echo "Errore nella creazione dell'opera";
            exit();
        }
        
        header("location: ../php/opera.php?id=".$addArtwork);
        exit();
    }
}

    echo($creaOpera);
?>