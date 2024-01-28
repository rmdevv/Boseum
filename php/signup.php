<?php
require_once 'DBAccess.php';
require_once 'utils.php';
require_once 'DateManager.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
setlocale(LC_ALL, 'it_IT');

session_start();
if (isset($_SESSION['logged_id'])) {
    header('Location: artista.php?id=' . $_SESSION['logged_id']);
    exit();
}

$username = "";
$name = "";
$lastname = "";
$birthPlace = "";
$birthDate = "";
$errorMessage = "";

if (isset($_POST['create_account'])) {
    $username = isset($_POST["username"]) ? Sanitizer::sanitize($_POST["username"]) : "";
    $password = isset($_POST["password"]) ? htmlspecialchars($_POST["password"]) : "";
    $name = isset($_POST["name"]) ? Sanitizer::sanitize($_POST["name"]) : "";
    $lastname = isset($_POST["lastname"]) ? Sanitizer::sanitize($_POST["lastname"]) : "";
    $birthPlace = isset($_POST["birth_place"]) ? Sanitizer::sanitize($_POST["birth_place"]) : "";
    $birthDate = isset($_POST["birth_date"]) ? Sanitizer::sanitizeDate($_POST["birth_date"]) : "";

    if (!($username && $password && $name && $lastname)) {
        $errorMessage = "<p class=\"error_message\"><em>Parametri non sufficienti</em></p>";
    } else if ($birthDate && (!Sanitizer::validateDate($birthDate) || !validDate("1900-01-01", date("Y-m-d"), $birthDate))) {
        $errorMessage = "<p class=\"error_message\"><em>Inserire una data valida</em></p>";
    } else {
        $connection = new DB\DBAccess();
        if (!$connection->openDBConnection()) {
            header("location: ../php/500.php");
            exit();
        }

        $isUserCreated = $connection->insertNewUser($username, password_hash($password, PASSWORD_BCRYPT), $name, $lastname, "", $birthDate, $birthPlace, "", "");

        if ($isUserCreated) {
            $_SESSION['logged_id'] = $connection->getUserLogin($username)[0]["id"];
            $_SESSION['is_admin'] = false;

            header("location: artista.php?id=" . $_SESSION['logged_id']);
            $connection->closeConnection();
            exit();
        } else {
            $errorMessage = "<p class=\"error_message\"><em>Errore nella creazione dell'account</em></p>";
        }
    }
}

$signup = file_get_contents("../templates/signup.html");
$signup = str_replace("{{username}}", $username, $signup);
$signup = str_replace("{{name}}", $name, $signup);
$signup = str_replace("{{lastname}}", $lastname, $signup);
$signup = str_replace("{{birth_place}}", $birthPlace, $signup);
$signup = str_replace("{{birth_date}}", $birthDate, $signup);
$signup = str_replace("{{error_message}}", $errorMessage, $signup);
echo ($signup);
