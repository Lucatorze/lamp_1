<?php
require_once("config/dbconf.php");
session_start();

if(isset($_POST['logout'])){

    unset($_SESSION['user']);

}

if(isset($_SESSION['user'])){

    header("Location: /game.php");
    exit;

}

$errormessage = null;

if(isset($_POST['username'])){

    global $config;
    $pdo = new PDO($config['host'], $config['user'], $config['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = :login");

    $stmt->bindParam("login",$_POST['username']);
    $stmt->execute();
    $result = $stmt->fetch();

    $passwordCrypt = sha1($_POST["password"]);

    if($result === false){

        $errormessage = "Wrong username";

    }

    elseif (empty($passwordCrypt)){

        $errormessage = "No Password";

    }

    elseif($passwordCrypt != $result["password"]){

        $errormessage = "Wrong password";

    }

    else{

        $_SESSION['user'] = $result["login"];
        $_SESSION['nb'] = $result["nb"];
        $_SESSION['coup'] = $result["coup"];
        header("Location: /game.php");
        exit;

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


Merci de vous connecter :

<form method="POST">

    Login : <input type="text" name="username"> <em>(Lucas)</em><br>
    Password : <input type="password" name="password"> <em>(j'aimelespommes)</em><br>
    <input type="submit" value="Log in">

</form>

<?php echo $errormessage;?>

</body>
</html>
