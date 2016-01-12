<?php

$user = "root";
$pass = "";

$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=plusoumois', $user, $pass);
$q = $pdo->prepare('SELECT login, password WHERE login');



if(isset($_POST['login']) && isset($_POST['mdp'])){

    if($_POST['login'] == $login && $_POST['mdp'] == $mdp){

        header('Location: game.php');
        exit;

    }

    else{

        echo 'EeEeeeRRrrrrrrRoOoOoOoOOOrRrRrR !!!!!!!!!!! Les identifiant ne sont pas correct !<br><br>';

    }

}

?>

<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <title>Des papier dans un bol</title>

</head>
<body>


<form name="form" method="POST">

    <label for="login">Login : </label>
    <input type="text" name="login" id="login"><br>
    <label for="mdp">Mot de passe : </label>
    <input type="password" name="mdp" id="mdp"><br><br>

    <input type="submit">


</form>

</body>
</html>
