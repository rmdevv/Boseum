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

$creaMostra = file_get_contents("../templates/crea_mostra.html");
$creaMostra = str_replace("{{login_or_profile_title}}", $loginOrProfileTitle, $creaMostra);

if (isset($_POST['addArtshow'])) {

    $imagesDir = "../uploads/artshows/";
    if(!getimagesize($_FILES["main_image"]["tmp_name"])) {
        echo "File non è una immagine";
        exit();
    }
    $image = $imagesDir . basename($_FILES["main_image"]["name"]);

    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $startDate = $_POST['start_date'] ?? '';
    $endDate = $_POST['end_date'] ?? '';
    
    if (empty($title) || empty($image) || empty($description) || empty($startDate) || empty($endDate)) {
        // TODO: Messaggio errore (non dovrebbe mai accadere per i campi required)
        echo "Parametri non sufficienti";
        exit();
    }else{
        $connection=new DB\DBAccess();
        if (!$connection->openDBConnection()) {
            header("location: ../php/500.php");
            exit();
        }

        $addArtshow = $connection->insertNewArtshow($title, $description, $image, $startDate, $endDate);
        $connection->closeConnection();

        if(!$addArtshow){
            echo "Errore nella creazione della mostra";
            exit();
        }
        
        if( !move_uploaded_file($_FILES["main_image"]["tmp_name"], $image)){
            echo "Errore nel salvataggio del file";
        }
        
        header("location: ../php/mostre.php");
        exit();
    }
}

    echo($creaMostra);
?>