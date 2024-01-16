<?php
require_once 'DBAccess.php';
require_once 'utils.php';
require_once 'DateManager.php';
require_once 'ImageProcessor.php';

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
setlocale(LC_ALL,'it_IT');

session_start();
$id=$_SESSION["logged_id"] ?? '';

if($_SESSION['is_admin']){
    $id=$_GET["id"] ?? '';
}

if($id==""){
    /*Se non é admin o non risulta loggato viene reindirizzato alla pagina di login*/
    header("location:  ../php/index.php");
    exit();
}
$error='';
$ret=file_get_contents("../templates/modifica_profilo.html");
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if(!isset($_POST["name"]) || !isset($_POST["lastname"])) $error="</br>Inserire dati nei campi obbligatori";
    else if(isset($_POST["birth_date"])&& !Sanitizer::validateDate($_POST["birth_date"])) $error="</br> Inserire una data corretta";
    else{
        $con=new DB\DBAccess();
        if (!$con->openDBConnection()) {
            header("location: ../php/500.php");
            exit();
        }
        /*TODO Gestione file caricato (se presente)*/
        $userimg='';

        if(isset($_FILES["profile_image"]) && sizeof($_FILES["profile_image"]) > 0){
            $userimg= ImageProcessor::processImage($_FILES["profile_image"],"../uploads/users");
        }

        /*Devo recuperare l'username */
        $data=$con->getArtist($id);
        $con->closeConnection();
        if(!$data | sizeof($data)<=0){
            header("location: ../php/500.php");
            exit();
        }

        $con->openDBConnection();
        $righe=$con->modifyUser(
            $id,
            $data[0]["username"],
            trim(Sanitizer::sanitize($_POST["name"])),
            trim(Sanitizer::sanitize($_POST["lastname"])),
            $userimg,
            /*Lo posso fare perché la data ha un formato valido*/
            isset($_POST["birthdate"])?$_POST["birthdate"]:'',
            isset($_POST["birthplace"])?trim(Sanitizer::sanitize($_POST["birthplace"])):'',
            isset($_POST["biography"])?trim(Sanitizer::sanitize($_POST["biography"])):'',
            isset($_POST["experience"])?trim(Sanitizer::sanitize($_POST["experience"])):''
        );
        $con->closeConnection();
        unset($_POST["name"]);
        unset($_POST["lastname"]);
        unset($_POST["birthdate"]);
        unset($_POST["birthplace"]);
        unset($_POST["biography"]);
        unset($_POST["experience"]);
        unset($_POST["profile_image"]);
        header("location: ../php/artista.php?id=".$id);
        exit();
    }
}

$con=new DB\DBAccess();
if (!$con->openDBConnection()) {
    header("location: ../php/500.php");
    exit();
}
$data=$con->getArtist($id);
$con->closeConnection();
if(!$data | sizeof($data)<=0){
    header("location: ../php/500.php");
    exit();
}

$ret=str_replace("{{immagine profilo}}",$data[0]["image"]??'../assets/images/default_user.svg',$ret);
$ret=str_replace("{{nome}}",$data[0]["name"],$ret);
$ret=str_replace("{{lastname}}",$data[0]["lastname"],$ret);
$ret=str_replace("{{birthdate}}",$data[0]["birth_date"]??'',$ret);
$ret=str_replace("{{birthplace}}",$data[0]["birth_place"]??'',$ret);
$ret=str_replace("{{biography}}",$data[0]["biography"]??'',$ret);
$ret=str_replace("{{experience}}",$data[0]["experience"]??'',$ret);
$ret=str_replace("{{error}}",$error,$ret);

echo($ret);