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
        echo(str_replace("{{messaggioerrore}}","<p class=\"error_message\"><em>$errmsg</em></p>", $ret));
        die();
    }
}


session_start();

$san= new Sanitizer();
$san->trimValues($_POST);

/*Valori teoricamente presenti, controllo da fare per sicurezza in quanto utenti malintenzionati possono togliere l'attributo required*/
$username="";
if(isset($_POST["username"])){
    $username=$san->sanitizeWord($_POST["username"]);
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
    $name=$san->sanitize($_POST["name"]);
    unset($_POST["name"]);
}

$lastname="";
if(isset($_POST["lastname"])){
    $lastname=$san->sanitize($_POST["lastname"]);
    unset($_POST["lastname"]);
}

$birthplace="";
if(isset($_POST["birthplace"])){
    $birthplace=$san->sanitize($_POST["birthplace"]);
    unset($_POST["birthplace"]);
}

$birthdate="";
if(isset($_POST["birthdate"])){
    $birthdate=$san->sanitizeDate($_POST["birthdate"]);
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

/*validazione data solo se la data non è nulla*/
if($birthdate!="" && $san->validateDate($birthdate) && $birthdate>"1900-01-01" && $birthdate<=date("Y-m-d")){
    $ret = compilaerrore($username,$password,$name,$lastname,$birthdate,$birthplace);
    echo(str_replace("{{messaggioerrore}}","<p><em>Inserire una data valida</em></p>", $ret));
    die();
}



$connection = new DB\DBAccess();
$connection->openDBConnection();
if($connection->insertNewUser($username,password_hash($password, PASSWORD_BCRYPT),$name,$lastname,"","","","","") != false){
    $_SESSION['logged_id']=$connection->getUserLogin($username)[0]["id"];
    $connection->closeConnection();
    $_SESSION['is_admin']=false;
    header("location: artista.php?id=".$_SESSION['logged_id']);
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