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
$removeProfileImage = "";
if (!$isLoggedIn || !isset($_POST['id_artist']) || ($_SESSION['logged_id'] != $_POST['id_artist'] && !$_SESSION['is_admin'])) {
    header('Location: login.php');
    exit();
} else {
    $loginOrProfileTitle = "<a href=\"artista.php?id=" . $_SESSION['logged_id'] . "\"><span lang=\"en\">Account</span></a>";
    $submitButton = "<input type=\"hidden\" name=\"id_artist\" value=\"" . $_POST['id_artist'] . "\">
                    <div class=\"form_button\">
                        <button
                            id=\"submit_button\"
                            class=\"btn-primary\"
                            type=\"submit\"
                            name=\"confirm_update\">
                            Conferma modifiche
                        </button>
                    </div>";
    $removeProfileImage = "<div class=\"disable_checkbox\">
                                <label for=\"remove_profile_image\"
                                    >Rimuovi foto profilo</label
                                >
                                <input
                                    type=\"checkbox\"
                                    id=\"remove_profile_image\"
                                    name=\"remove_profile_image\"
                                    />
                            </div>";
}

$connection = new DB\DBAccess();
if (!$connection->openDBConnection()) {
    header("location: 500.php");
    exit();
}

$idArtist = $_POST['id_artist'];
$error = '';

if (isset($_POST['confirm_update'])) {

    $name = isset($_POST["name"]) ? Sanitizer::sanitize($_POST["name"]) : "";
    $lastname = isset($_POST["lastname"]) ? Sanitizer::sanitize($_POST["lastname"]) : "";
    $birthDate = isset($_POST["birth_date"]) ? (new DateTime($_POST["birth_date"]))->format("Y-m-d") : "";
    $birthPlace = isset($_POST["birth_place"]) ? Sanitizer::sanitize($_POST["birth_place"]) : "";
    $biography = isset($_POST["biography"]) ? Sanitizer::sanitize($_POST["biography"]) : "";
    $experience = isset($_POST["experience"]) ? Sanitizer::sanitize($_POST["experience"]) : "";

    if (!$name || !$lastname) {
        $error = "<p class=\"error_message\"><em>Inserire dati nei campi obbligatori</em></p>";
    } else if ($birthDate && !Sanitizer::validateDate($birthDate)) {
        $error = "<p class=\"error_message\"><em>Inserire una data corretta</em></p>";
    } else {
        $mainImage = null;
        if (isset($_POST['remove_profile_image'])) {
            $mainImage = "";
            $artistPreview = $connection->getArtistPreview($idArtist);
            if ($artistPreview && sizeof($artistPreview) > 0) {
                ImageProcessor::deleteImage($artistPreview[0]['image']);
            }
        } else if (isset($_FILES["profile_image"]) && $_FILES["profile_image"]['size'] !== 0) {
            $mainImage = ImageProcessor::processImage($_FILES["profile_image"], "../uploads/users/");
            $artistPreview = $connection->getArtistPreview($idArtist);
            if ($artistPreview && sizeof($artistPreview) > 0) {
                ImageProcessor::deleteImage($artistPreview[0]['image']);
            }
        }

        $isModified = $connection->modifyUser(
            $idArtist,
            $name,
            $lastname,
            $mainImage,
            $birthDate,
            $birthPlace,
            $biography,
            $experience
        );

        if ($isModified == 0) {
            $error = "<p class=\"error_message\"><em>Errore nell'aggiornamento del profilo</em></p>";
        } else {
            $connection->closeConnection();
            header("location: artista.php?id=" . $idArtist);
            exit();
        }
    }
}

$artist = $connection->getArtist($idArtist);
$connection->closeConnection();

if ($artist && sizeof($artist) > 0) {
    $prevPage = "<li>
                <a href=\"artista.php?id=" . $idArtist . "\">" . $artist[0]['username'] . "</a>
            </li>";

    $modificaProfilo = file_get_contents("../templates/modifica_profilo.html");
    $modificaProfilo = str_replace("{{login_or_profile_title}}", $loginOrProfileTitle, $modificaProfilo);
    $modificaProfilo = str_replace("{{prev_page}}", $prevPage, $modificaProfilo);
    $modificaProfilo = str_replace("{{profile_image}}", $artist[0]["image"] ?? '../assets/images/default_user.svg', $modificaProfilo);
    $modificaProfilo = str_replace("{{remove_profile_image}}", $removeProfileImage, $modificaProfilo);
    $modificaProfilo = str_replace("{{nome}}", $artist[0]["name"], $modificaProfilo);
    $modificaProfilo = str_replace("{{lastname}}", $artist[0]["lastname"], $modificaProfilo);
    $modificaProfilo = str_replace("{{birth_date}}", $artist[0]["birth_date"] ?? '', $modificaProfilo);
    $modificaProfilo = str_replace("{{birth_place}}", $artist[0]["birth_place"] ?? '', $modificaProfilo);
    $modificaProfilo = str_replace("{{biography}}", $artist[0]["biography"] ?? '', $modificaProfilo);
    $modificaProfilo = str_replace("{{experience}}", $artist[0]["experience"] ?? '', $modificaProfilo);
    $modificaProfilo = str_replace("{{submit_button}}", $submitButton, $modificaProfilo);
    $modificaProfilo = str_replace("{{error_message}}", $error, $modificaProfilo);

    echo ($modificaProfilo);
} else {
    header("location: 500.php");
    exit();
}
