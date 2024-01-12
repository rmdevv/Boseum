<?php
function compilaerrore($username,$password,$name,$lastname,$birthdate,$birthplace,int $unpwdt){
    $form = file_get_contents("../templates/signup.html");
    $form = str_replace("{{username}}",$username,$form);
    $form = str_replace("{{password}}",$password,$form);
    $form = str_replace("{{name}}",$name,$form);
    $form = str_replace("{{lastname}}",$lastname,$form);
    $form = str_replace("{{birthdate}}",$birthdate,$form);
    $form = str_replace("{{birthplace}}",$birthplace,$form);

    for($i=0;$i<3;++$i){
        if($unpwdt!=$i) $form = str_replace("{{focus}}","",$form);
        else str_replace("{{focus}}","focus",$form);
    }

    /*errore da stampare*/
    return $form;
}

require_once 'DBAccess.php';
require_once 'utils.php';
require_once 'DateManager.php';

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
setlocale(LC_ALL,'it_IT');

session_start();

$san= new Sanitizer();
$san->trimValues($_POST);

/*Valori teoricamente presenti, controllo da fare per sicurezza in quanto utenti malintenzionati possono togliere l'attributo required*/
$username="";
if(isset($_POST["username"])) $username=$san->Sanitize($_POST["username"]);
$password="";/*password*/

$name="";
if(isset($_POST["name"])) $name=$san->Sanitize($_POST["name"]);
$lastname="";
if(isset($_POST["lastname"])) $lastname=$san->Sanitize($_POST["lastname"]);
$birthplace="";
if(isset($_POST["birthplace"])) $birthplace=$san->Sanitize($_POST["birthplace"]);

if(isset($_POST["birthdate"])) $birthdate=$san->SanitizeDate($_POST["birthdate"]);
if($san->ValidateDate($birthdate) && $birthdate>1900-01-01 && $birthdate<=date("Y-m-d")){
    if(!$username == ""){
        /* Inserire codice in caso di contrrolli sull'input validi (previa verifica passvord)*/
    }
    else{
        $ret = compilaerrore($username,$password,$name,$lastname,$birthdate,$birthplace,0);
        echo(str_replace("{{messaggioerrore}}","
        <br> <em>Inserire un user name<em>
        ", $ret));
        die();        
    }
}
else{
    $ret = compilaerrore($username,$password,$name,$lastname,$birthdate,$birthplace,2);
    echo(str_replace("{{messaggioerrore}}","
    <br> <em>Inserire una data valida<em>
    ", $ret));
    die();
}

/*Da inserire l'id ritornato dalla query, se presente
echo("artista.php?id=$id");*/

?>