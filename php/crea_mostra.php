<?php

require_once 'DBAccess.php';
require_once 'ImageProcessor.php';
require_once 'utils.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
setlocale(LC_ALL, 'it_IT');

session_start();
$isLoggedIn = isset($_SESSION['logged_id']);
$loginOrProfileTitle = "";
if ($isLoggedIn && !$_SESSION['is_admin']) {
    header("Location: ../php/index.php");
    exit();
}

if (!$isLoggedIn) {
    header('Location: ../php/login.php');
    exit();
} else {
    $loginOrProfileTitle = "<a href=\"artista.php?id=" . $_SESSION['logged_id'] . "\"><span lang=\"en\">Account</span></a>";
}

$connection = new DB\DBAccess();
if (!$connection->openDBConnection()) {
    header("location: ../php/500.php");
    exit();
}

$prevPage = "<li><a href=\"admin.php\">Amministratore</a></li>";
$pageTitle = "";
$submitButton = "";
$mainImageContainer = "";
$keepMainImage = "";
$title = "";
$description = "";
$startDate = "";
$endDate = "";
$deleteSection = "";

$errorCreateArtshow = "";
$errorModifyArtshow = "";
$errorDeleteArtshow = "";
$errorMessage = "";

if (isset($_POST['save_new_artshow'])) {

    $imagesDir = "../uploads/artshows/";

    $image = null;
    if ($_FILES["main_image"] && sizeof($_FILES["main_image"]) > 0) {
        $image = ImageProcessor::processImage($_FILES["main_image"], $imagesDir);
    }

    $title = isset($_POST['title']) ? Sanitizer::sanitize($_POST['title']) : '';
    $description = isset($_POST['description']) ? Sanitizer::sanitize($_POST['description']) : '';
    $startDate = isset($_POST['start_date']) ? (new DateTime(Sanitizer::sanitize($_POST['start_date'])))->format("Y-m-d") : '';
    $endDate = isset($_POST['end_date']) ? (new DateTime(Sanitizer::sanitize($_POST['end_date'])))->format("Y-m-d") : '';

    if (empty($title) || empty($image) || empty($description) || empty($startDate) || empty($endDate)) {
        $errorCreateArtshow = "Parametri non sufficienti";
    } else if (!Sanitizer::validateDate($startDate) || !Sanitizer::validateDate($endDate) ||
            !validDate($startDate,$endDate,$startDate) || !validDate($startDate,$endDate,$endDate)) {
        $errorCreateArtshow = "Le date inserite non sono corrette";
    } else {

        $addArtshow = $connection->insertNewArtshow($title, $description, $image, $startDate, $endDate);

        if (!$addArtshow) {
            $errorCreateArtshow = "Errore nella creazione dell'opera";
            exit();
        } else {
            $connection->closeConnection();
            header("location: ../php/mostre.php");
            exit();
        }
    }
} else if (isset($_POST['update_artshow'])) {
    $imagesDir = "../uploads/artshow/";
    $idArtshow = $_POST['id_artshow'];

    $mainImage = null;
    if (!isset($_POST['disable_main_image'])) {
        if (isset($_FILES["main_image"]) && $_FILES["main_image"]['size'] !== 0) {
            $mainImage = ImageProcessor::processImage($_FILES["main_image"], $imagesDir);
            $artshowPreview = $connection->getArtshow($idArtshow);
            if ($artshowPreview && sizeof($artshowPreview) > 0) {
                foreach ($artshowPreview as $artshowPreview) {
                    ImageProcessor::deleteImage($artshowPreview['main_image']);
                }
            }
        }
    }

    $title = isset($_POST['title']) ? Sanitizer::sanitize($_POST['title']) : '';
    $description = isset($_POST['description']) ? Sanitizer::sanitize($_POST['description']) : '';
    $startDate = isset($_POST['start_date']) ? (new DateTime(Sanitizer::sanitize($_POST['start_date'])))->format("Y-m-d") : '';
    $endDate = isset($_POST['end_date']) ? (new DateTime(Sanitizer::sanitize($_POST['end_date'])))->format("Y-m-d") : '';


    if (empty($title) || empty($description) || empty($startDate) || empty($endDate)) {
        $errorModifyArtshow = "Parametri non sufficienti";
    } else if (!Sanitizer::validateDate($startDate) && !Sanitizer::validateDate($endDate)) {
        $errorModifyArtshow = "Le date inserite non sono corrette";
    } else {
        $modifiedArtshow = $connection->modifyArtshow($idArtshow, $title, $description, $mainImage, $startDate, $endDate);

        if (!$modifiedArtshow) {
            $errorModifyArtshow = "Errore nell'aggiornamento dell'opera";
        } else {
            $connection->closeConnection();
            header("location: mostra.php?id=" . $idArtshow);
            exit();
        }
    }
} else if (isset($_POST["delete_artshow"])) {
    $idArtshowToDelete = $_POST['id_artshow'];

    $artshow = $connection->getArtshow($idArtshowToDelete);
    $isDeleted = $connection->deleteArtshow($idArtshowToDelete);

    if ($isDeleted) {
        if ($artshow && sizeof($artshow) > 0) {
            ImageProcessor::deleteImage($artshow[0]['image']);
        }

        header("location: login.php");
        exit();
    } else {
        $errorDeleteArtshow = "Errore durante la cancellazione della mostra.";
    }
}

if (isset($_POST["create_artshow"]) || $errorCreateArtshow != "") {

    $errorMessage = "<p class=\"error_message\"><em>" . $errorCreateArtshow . "</em></p>";

    $pageTitle = "Crea mostra";
    $submitButton = "
        <div class=\"form_button\">
            <button
                id=\"submit_button\"
                type=\"submit\"
                name=\"save_new_artshow\"
                class=\"btn-primary\">
                Crea mostra
            </button>
        </div>
    ";
} else if (isset($_POST["modify_artshow"]) || $errorModifyArtshow != "" || $errorDeleteArtshow) {
    if ($errorModifyArtshow != "") {
        $errorMessage = "<p class=\"error_message\"><em>" . $errorModifyArtshow . "</em></p>";
    } else {
        $errorMessage = "<p class=\"error_message\"><em>" . $errorDeleteArtshow . "</em></p>";
    }

    $pageTitle = "Modifica mostra";
    $idArtshow = $_POST['id_artshow'];
    $submitButton = "<input type=\"hidden\" name=\"id_artshow\" value=\"$idArtshow\">
                    <div class=\"form_button\">
                        <button
                            id=\"submit_button\"
                            type=\"submit\"
                            name=\"update_artshow\"
                            class=\"btn-primary\">
                            Aggiorna mostra
                        </button>
                    </div>";

    $keepMainImage = "<div class=\"disable_checkbox\">
                            <label for=\"disable_main_image\"
                                >Mantieni l'immagine della mostra
                                già in uso</label
                            >
                            <input
                                type=\"checkbox\"
                                id=\"disable_main_image\"
                                name=\"keep_main_image\"
                                />
                        </div>";

    $deleteSection = "<section class=\"danger_section_form\">
                        <form
                            id=\"delete_artshow\"
                            action=\"../php/crea_mostra.php\"
                            method=\"post\">
                            <fieldset class=\"fieldset_item_danger\">
                                <legend>Zona pericolosa</legend>
                                <p>Attenzione. L'eliminazione di una mostra è un'azione irreversibile.</p>
                                <input type=\"hidden\" name=\"id_artshow\" value=\"$idArtshow\">
                                <div class=\"form_button\">
                                    <button
                                        id=\"delete_artshow_button\"
                                        type=\"submit\"
                                        name=\"delete_artshow\"
                                        class=\"btn-primary\">
                                        Elimina mostra
                                    </button>
                                </div>
                            </fieldset>
                        </form>
                    </section>";

    $infoArtshow = $connection->getArtshow($idArtshow);

    if ($infoArtshow and sizeof($infoArtshow) > 0) {
        $title = $infoArtshow[0]['title'];
        $description = $infoArtshow[0]['description'];
        $startDate = $infoArtshow[0]['start_date'];
        $endDate = $infoArtshow[0]['end_date'];
    } else {
        echo "Errore, l'opera non esiste";
    }

    $prevPage = "
        <li> 
            <a href=\"mostre.php\"> Mostre </a>
        </li>
        <li>
            <a href=\"mostra.php?id=" . $idArtshow . "\">" . $title . "</a>
        </li>
    ";
} else {
    header('Location: index.php');
    exit();
}
$connection->closeConnection();

$creaMostra = file_get_contents("../templates/crea_mostra.html");
$creaMostra = str_replace("{{login_or_profile_title}}", $loginOrProfileTitle, $creaMostra);
$creaMostra = str_replace("{{prev_page}}", $prevPage, $creaMostra);
$creaMostra = str_replace("{{page_title}}", $pageTitle, $creaMostra);
$creaMostra = str_replace("{{keep_main_image}}", $keepMainImage, $creaMostra);
$creaMostra = str_replace("{{main_image}}", $mainImageContainer, $creaMostra);
$creaMostra = str_replace("{{title}}", $title, $creaMostra);
$creaMostra = str_replace("{{description}}", $description, $creaMostra);
$creaMostra = str_replace("{{start_date}}", $startDate, $creaMostra);
$creaMostra = str_replace("{{end_date}}", $endDate, $creaMostra);
$creaMostra = str_replace("{{submit_button}}", $submitButton, $creaMostra);
$creaMostra = str_replace("{{delete_section}}", $deleteSection, $creaMostra);
$creaMostra = str_replace("{{error_message}}", $errorMessage, $creaMostra);

echo ($creaMostra);
