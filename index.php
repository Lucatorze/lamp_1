<?php
require_once("config/dbconf.php");
session_start();

if (isset($_POST['logout'])) {

    unset($_SESSION['user']);
    unset($_SESSION['userid']);

}

if (isset($_SESSION['user'])) {

    header("Location: /game.php");
    exit;

}

$errormessage = null;

if (isset($_POST['username'])) {

    global $config;
    $pdo = new PDO($config['host'], $config['user'], $config['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = :login");

    $stmt->bindParam("login", $_POST['username']);
    $stmt->execute();
    $result = $stmt->fetch();

    $passwordCrypt = sha1($_POST["password"]);

    if ($result === false) {

        $errormessage = "<div class='error'>Pseudo Introuvable !</div>";

    } elseif (empty($passwordCrypt)) {

        $errormessage = "<div class='error'>Merci d'indiquer votre mot de passe !</div>";

    } elseif ($passwordCrypt != $result["password"]) {

        $errormessage = "<div class='error'>Le mot de passe n'est pas correcte ! </div>";

    } else {

        $_SESSION['user'] = $result["login"];
        $_SESSION['userid'] = $result["id"];

        if ($result['save_rand'] === null) {

            $_SESSION['nb'] = $result["nb"];
            $_SESSION['coup'] = $result["coup"];

        } else {

            $_SESSION['nb'] = $result["nb"];
            $_SESSION['coup'] = $result["coup"];
            $_SESSION['bol'] = $result["save_rand"];
            $_SESSION['nombre_envoi'] = $result["save_coup"];

        }

        header("Location: /game.php");
        exit;

    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <title>Plus ou Moins ? - Login</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>

<div id="header">

    Plus ou Moins ?

</div>

<div id="content">

    <?php echo $errormessage; ?>

    <form method="POST">

        <label for="username">Login : </label>
        <input type="text" id="username" name="username"><br>

        <label for="password">Password : </label>
        <input type="password" id="password" name="password"><br><br>
        <input type="submit" value="Log in"><br><br>

    </form>

    <a href="register.php">Se créer un compte</a><br><br>


</div>


</body>
</html>
