<?php
    require_once 'DBAccess.php';
    require_once 'utils.php';
    require_once 'DateManager.php';
    
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    setlocale(LC_ALL,'it_IT');
function compilaerrore($username,$password,$name,$lastname,$birthdate,$birthplace){
    $form = file_get_contents("../templates/signup.html");
    $form = str_replace("{{username}}",$username,$form);
    $form = str_replace("{{password}}",$password,$form);
    $form = str_replace("{{name}}",$name,$form);
    $form = str_replace("{{lastname}}",$lastname,$form);
    $form = str_replace("{{birthdate}}",$birthdate,$form);
    $form = str_replace("{{birthplace}}",$birthplace,$form);

    return $form;
}

function validazione($var,string $errmsg){
    if($var == ""){
        $ret = compilaerrore($GLOBALS["username"],$GLOBALS["password"],$GLOBALS["name"],$GLOBALS["lastname"],
        $GLOBALS["birthdate"],$GLOBALS["birthplace"]);
        echo(str_replace("{{messaggioerrore}}","
        <br> <em>$errmsg<em>
        ", $ret));
        die();   
    }
}


session_start();

$san= new Sanitizer();
$san->trimValues($_POST);

/*Valori teoricamente presenti, controllo da fare per sicurezza in quanto utenti malintenzionati possono togliere l'attributo required*/
$username="";
if(isset($_POST["username"])){
    $username=$san->SanitizeWord($_POST["username"]);
    unset($_POST["username"]);
}

$password="";
if(isset($_POST["password"])){
    /*Si potrebbe anche semplicemente lasciare htmlspecialchars*/
    $password=strip_tags($_POST["password"]);
    $pasword=htmlspecialchars($password);
    unset($_POST["password"]);
}

$name="";
if(isset($_POST["name"])){
    $name=$san->Sanitize($_POST["name"]);
    unset($_POST["name"]);
}

$lastname="";
if(isset($_POST["lastname"])){
    $lastname=$san->Sanitize($_POST["lastname"]);
    unset($_POST["lastname"]);
}

$birthplace="";
if(isset($_POST["birthplace"])){
    $birthplace=$san->Sanitize($_POST["birthplace"]);
    unset($_POST["birthplace"]);
}

$birthdate="";
if(isset($_POST["birthdate"])){
    $birthdate=$san->SanitizeDate($_POST["birthdate"]);
    unset($_POST["birthdate"]);
}

/*Validazione username*/
validazione($username,"Inserire uno user name");

/*Validazione password*/
validazione($password,"Inserire una password");

/*Validazione Nome*/
validazione($name,"Inserire un nome");

/*Validazione Cognome*/
validazione($lastname,"Inserire un cognome");

/*validazione data solo se la data non é nulla*/
if($birthdate!="" && $san->ValidateDate($birthdate) && $birthdate>"1900-01-01" && $birthdate<=date("Y-m-d")){
    $ret = compilaerrore($username,$password,$name,$lastname,$birthdate,$birthplace);
    echo(str_replace("{{messaggioerrore}}","
    <br> <em>Inserire una data valida<em>
    ", $ret));
    die();
}



$con=new DB\DBAccess();
$con->openDBConnection();
if($con->insertNewUser($username,$password,$name,$lastname,"","","","","") != false){
    $_SESSION['logged_id']=$con->getUserLogin($username)[0]["id"];
    $con->closeConnection();
    $_POST['logged_id']=$_SESSION['logged_id'];
    header("location: ../php/artista.php");
    exit;
}
else{
    $ret = compilaerrore($username,$password,$name,$lastname,$birthdate,$birthplace);
    echo(str_replace("{{messaggioerrore}}","
    <br> <em>Username non disponibile, prova a cambiarlo<em>
    ", $ret));
    die();  
}
?>