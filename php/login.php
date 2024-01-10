<?php

require_once 'DBAccess.php';

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
setlocale(LC_ALL,'it_IT');

session_start();
if (isset($_SESSION['logged_id'])) {
    header('Location: artista.php?id='.$_SESSION['logged_id']);
    exit();
}

if (isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        // TODO: Messaggio errore (non dovrebbe mai accadere per i campi required)
        echo "Parametri non sufficienti";
        exit();
    }else{
        $connection=new DB\DBAccess();
        if (!$connection->openDBConnection()) {
            header("location: ../src/500.html");
            exit();
        }

        $potentialUser = $connection->getUserPassword($username);

        $connection->closeConnection();

        if ($potentialUser && password_verify($password, $potentialUser[0]['password'])) {
            $_SESSION['logged_id'] = $potentialUser[0]['id'];

            header('Location: artista.php?id='.$_SESSION['logged_id']);
            exit();
        }else {
            // TODO: Messaggio errore
            echo "Credenziali errate";
            exit();
        }
    }
}



    $login = file_get_contents("../templates/login.html");
    echo($login);
?>