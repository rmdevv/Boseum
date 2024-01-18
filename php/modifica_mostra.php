<?php
require_once 'DBAccess.php';
require_once 'utils.php';
require_once 'DateManager.php';
require_once 'ImageProcessor.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
setlocale(LC_ALL, 'it_IT');

session_start();
$isLoggedIn = isset($_SESSION['logged_id']);
$loginOrProfileTitle = "";
$submitButton = "";
if (!$isLoggedIn || !isset($_POST['id_artshow'])) {
    header('Location: login.php');
    exit();
} else {
    $loginOrProfileTitle = "<a href=\"artista.php?id=" . $_SESSION['logged_id'] . "\"><span lang=\"en\">Account</span></a>";
    $submitButton = "<input type=\"hidden\" name=\"id_artshow\" value=\"" . $_POST['id_artshow'] . "\">
                    <div class=\"form_button\">
                        <button
                            id=\"submit_button\"
                            class=\"btn-primary\"
                            type=\"submit\"
                            name=\"confirm_update\">
                            Conferma modifiche
                        </button>
                    </div>";
}

$connection = new DB\DBAccess();
if (!$connection->openDBConnection()) {
    header("location: 500.php");
    exit();
}

$idArtshow = $_POST['id_artshow'];
$error = '';

if (isset($_POST['confirm_update'])) {

    $title = isset($_POST["tile"]) ? Sanitizer::sanitize($_POST["title"]) : "";
    $startDate = isset($_POST["start_date"]) ? (new DateTime($_POST["start_date"]))->format("Y-m-d") : "";
    $endDate = isset($_POST["end_date"]) ? (new DateTime($_POST["end_date"]))->format("Y-m-d") : "";
    $description = isset($_POST["description"]) ? Sanitizer::sanitize($_POST["description"]) : "";
    $mainImage = null;
    if (isset($_FILES["main_image"]) && $_FILES["main_image"]['size'] !== 0) {
        $mainImage = ImageProcessor::processImage($_FILES["profile_image"], "../uploads/users/");
        $artshowPreview = $connection->getArtshow($idArtshow);
        if ($artshowPreview && sizeof($artshowPreview) > 0) {
            ImageProcessor::deleteImage($artshowPreview[0]['image']);
        }
    }
    if (!$title || $mainImage || !$description || !$startDate || $endDate) {
        $error = "<p class=\"error_message\"><em>Inserire dati nei campi obbligatori</em></p>";
    } else if (!Sanitizer::validateDate($startDate) && !Sanitizer::validateDate($endDate)) {
        $error = "<p class=\"error_message\"><em>Inserire una data corretta</em></p>";
    } else {
        $isModified = $connection->modifyArtshow(
            $idArtshow, 
            $title, 
            $description, 
            $mainImage, 
            $startDate, 
            $endDate
        );

        if ($isModified == 0) {
            $error = "<p class=\"error_message\"><em>Errore nell'aggiornamento del profilo</em></p>";
        } else {
            $connection->closeConnection();
            header("location: mostra.php?id=" . $idArtshow);
            exit();
        }
    }
}

$artshow = $connection->getArtshow($idArtshow);
$connection->closeConnection();

if ($artshow && sizeof($artshow) > 0) {
    $prevPage = "<li>
                <a href=\"mostra.php?id=" . $idArtshow . "\">" . $artshow[0]['title'] . "</a>
            </li>";

    $modificaMostra = file_get_contents("../templates/modifica_mostra.html");
    $modificaMostra = str_replace("{{login_or_profile_title}}", $loginOrProfileTitle, $modificaMostra);
    $modificaMostra = str_replace("{{prev_page}}", $prevPage, $modificaMostra);
    $modificaMostra = str_replace("{{artshow_image}}", $artshow[0]["image"], $modificaMostra);
    $modificaMostra = str_replace("{{title}}", $artshow[0]["title"], $modificaMostra);
    $modificaMostra = str_replace("{{description}}", $artshow[0]["description"], $modificaMostra);
    $modificaMostra = str_replace("{{start_date}}", $artshow[0]["start_date"], $modificaMostra);
    $modificaMostra = str_replace("{{end_date}}", $artshow[0]["end_date"], $modificaMostra);
    $modificaMostra = str_replace("{{submit_button}}", $submitButton, $modificaMostra);
    $modificaMostra = str_replace("{{error_message}}", $error, $modificaMostra);

    echo ($modificaMostra);
} else {
    header("location: 500.php");
    exit();
}
?>
