<?php
require_once("config/dbconf.php");

global $config;
$pdo = new PDO($config['host'], $config['user'], $config['password']);

$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute();
$result = $stmt->fetch();

$error = '';
$success = '';

if (isset($_POST['username']) || isset($_POST['password']) || isset($_POST['passwordverif'])) {

    if ($_POST['username'] != $result['login']) {

        if (!empty($_POST['username'])) {

            if (!empty($_POST['password'])) {

                if (!empty($_POST['passwordverif'])) {

                    if ($_POST['password'] == $_POST['passwordverif']) {

                        $passwordCrypt = sha1($_POST["password"]);

                        $stmt = $pdo->prepare("INSERT INTO `users`(`id`, `login`, `password`, `nb`, `coup`, `save_rand`, `save_coup`) VALUES ('','" . $_POST['username'] . "','" . $passwordCrypt . "',NULL,NULL,NULL,NULL)");
                        $stmt->execute();

                        $success = "<div class='success'>Votre inscription s'est bien passé !<br><a href='index.php'>Se connecter</a></div>";

                    } else {

                        $error = "<div class='error'>Les deux mot de passe ne sont pas identique.</div> <br>";

                    }
                } else {

                    $error = "<div class='error'>Merci de vérifier votre mot de passe.</div> <br>";

                }
            } else {

                $error = "<div class='error'>Merci de choisir un mot de passe.</div> <br>";

            }
        } else {

            $error = "<div class='error'>Merci de choisir un pseudo.</div> <br>";

        }
    } else {

        $error = "<div class='error'>Le pseudo entré est déjà utilisé.</div> <br>";

    }

}

?>

<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <title>Plus ou Moins ? - Inscription</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>

<div id="header">

    Plus ou Moins ?

</div>

<div id="content">

    <a href="index.php">Retours à la connexion</a><br><br>

    <?php
    echo $error;
    echo $success;
    ?>

    <form method="POST" action="register.php">

        <label for="username">Login : </label>
        <input type="text" id="username" name="username"><br>

        <label for="password">Password : </label>
        <input type="password" id="password" name="password"><br>

        <label for="passwordverif">Verif Password : </label>
        <input type="password" id="passwordverif" name="passwordverif"><br><br>

        <input type="submit" value="Sign Up"><br><br>

    </form>

</div>


</body>
</html>