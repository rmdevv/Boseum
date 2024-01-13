<?php

require_once 'DBAccess.php';

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
    if(!getimagesize($_FILES["main_image"]["tmp_name"])) {
        echo "File non è una immagine";
        exit();
    }
    $mainImage = $imagesDir . basename($_FILES["main_image"]["name"]);

    $additionalImages = array();
    $nAdditionalImages = 0;
    if(isset($_FILES["additional_images"]["name"])){
        foreach ($_FILES["additional_images"]["name"] as $additionalImage) {
            if($additionalImage != '') $nAdditionalImages++;
        }
        for($i=0; $i<$nAdditionalImages; $i++) {
            if(!getimagesize($_FILES["additional_images"]["tmp_name"][$i])) {
                echo "File non è una immagine";
                exit();
            }
            array_push( $additionalImages, $imagesDir . basename($_FILES["additional_images"]["name"][$i]));
        }
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
        if(isset($_POST[$labelName])) array_push( $labelsArtwork, $label['label']);
    }

    if (empty($title) || empty($mainImage) || empty($description)) {
        // TODO: Messaggio errore (non dovrebbe mai accadere per i campi required)
        echo "Parametri non sufficienti";
        exit();
    }else{
        $connection=new DB\DBAccess();
        if (!$connection->openDBConnection()) {
            header("location: ../php/500.php");
            exit();
        }

        $addArtwork = $connection->insertNewArtwork($title, $mainImage, $description, $height, $width, $depth, $startDate, $endDate, $idArtist, $additionalImages, $labelsArtwork);
        $connection->closeConnection();

        if(!$addArtwork){
            echo "Errore nella creazione dell'opera";
            exit();
        }
        
        if( !move_uploaded_file($_FILES["main_image"]["tmp_name"], $mainImage)){
            echo "Errore nel salvataggio del file";
        }
        for($i=0; $i<$nAdditionalImages; $i++) {
            if( !move_uploaded_file($_FILES["additional_images"]["tmp_name"][$i], $imagesDir . basename($_FILES["additional_images"]["name"][$i]))){
                echo "Errore nel salvataggio del file";
            }
        }
        
        header("location: ../php/opera.php?id=".$addArtwork);
        exit();
    }
}

    echo($creaOpera);
?>