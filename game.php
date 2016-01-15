<?php
require_once("config/dbconf.php");
session_start();

global $config;
$pdo = new PDO($config['host'], $config['user'], $config['password']);

if (!isset($_SESSION['user'])) {

    header("Location: /index.php");
    exit;

}

if (empty($_SESSION['bol']) || empty($_SESSION['nombre_envoi']) || isset($_POST['reset'])) {
    $_SESSION['bol'] = mt_rand(0, 100);
    $_SESSION['nombre_envoi'] = 0;
    $response = "Entrez un nombre pour commencer<br><br>";

}

$response = null;

if (isset($_POST['resetbest'])) {

    $stmt = $pdo->prepare("update users set nb = NULL ,coup = NULL where id=" . $_SESSION['userid']);
    $stmt->execute();

    unset($_SESSION['nb']);
    unset($_SESSION['coup']);

}

if (isset($_POST['guess'])) {

    if (!isset($_SESSION['nombre_envoi'])) {

        $_SESSION['nombre_envoi'] = 1;

    } else {

        $_SESSION['nombre_envoi']++;

    }
}

if (!isset($_POST['guess']) || isset($_POST['reset'])) {

    $response = "Entrez un nombre pour commencer<br><br>";

} else {

    $guess = $_POST['guess'];

    if ($guess > $_SESSION['bol']) {

        $response = "Ce n'est pas " . $guess . ", c'est moins !<br><br>";

    } elseif ($guess < $_SESSION['bol']) {

        $response = "Ce n'est pas " . $guess . ", c'est plus !<br><br>";

    } else {

        $response = "C'est gagné ! c'est bien le nombre " . $guess . ", vous l'avez trouver en " . $_SESSION['nombre_envoi'] . " propositions !<br><br>";

        $stmt = $pdo->prepare("update users set save_rand=NULL ,save_coup=NULL where id=" . $_SESSION['userid']);
        $stmt->execute();

        $stmt = $pdo->prepare("INSERT INTO histo VALUES('', '" . $_SESSION['userid'] . "', '" . $guess . "', '" . $_SESSION['nombre_envoi'] . "', '" . time() . "')");
        $stmt->execute();

        if ($_SESSION['nombre_envoi'] < $_SESSION['coup'] || empty($_SESSION['coup'])) {

            $_SESSION['nb'] = $guess;
            $_SESSION['coup'] = $_SESSION['nombre_envoi'];

            $stmt = $pdo->prepare("update users set nb=" . $_SESSION['nb'] . ",coup=" . $_SESSION['coup'] . " where id=" . $_SESSION['userid']);
            $stmt->execute();

        } else {

            unset($_SESSION['bol']);
            unset($_SESSION['nombre_envoi']);

        }

        unset($_SESSION['bol']);
        unset($_SESSION['nombre_envoi']);

    }


}

if (isset($_POST['save'])) {

    $response = "Partie sauvegardé !<br><br>";

    $stmt = $pdo->prepare("update users set save_rand =" . $_SESSION['bol'] . ",save_coup = " . $_SESSION['nombre_envoi'] . " where id=" . $_SESSION['userid']);
    $stmt->execute();

}


?>

<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <title>Plus ou Moins ? - Jeux</title>
    <link rel="stylesheet" href="style.css">

    <script>

        window.onload = function () {

            document.getElementById('input').focus();

        }


    </script>

</head>
<body>

<div id="header">

    Plus ou Moins ?

</div>

<div id="content">

    <div class="screen">

        <?php echo $response; ?>

    </div>

    <form method="POST">

        <input type="text" name="guess" id="input"><br><br>
        <input type="submit" name="Envoi">
        <input type="submit" name="reset" value="Reset">
        <input type="submit" name="save" value="Sauvegarder">

    </form>

    <br><br>

    Meilleur score pour <b><?php echo $_SESSION['user']; ?></b>:

    <?php

    $stmt = $pdo->prepare("SELECT nb, coup FROM users where id=" . $_SESSION['userid']);
    $stmt->execute();
    $best = $stmt->fetch();

    if ($best['nb'] == null && $best['coup'] == null) {

        echo 'Pas de meilleur score';

    } else {

        echo $best['nb'] . ' en ' . $best['coup'] . ' coups ! <br><br><form method="POST"><input type="submit" name="resetbest" value="Reset Score"></form>';


    }


    ?><br><br>

    <b><u>LeaderBoard :</u></b><br><br>

    <table>

        <tr>

            <th>Nom</th>
            <th>Nombre Trouvé</th>
            <th>Coup</th>

        </tr>
        <?php

        $stmt = $pdo->prepare("SELECT * FROM users ORDER BY coup");
        $stmt->execute();

        while ($result = $stmt->fetch()) {

            echo '<tr><td>' . $result["login"] . '</td>';
            echo '<td>' . $result["nb"] . '</td>';
            echo '<td>' . $result["coup"] . '</td></tr>';

        }

        ?>

    </table>

    <br><br>

    <form method="POST" action="/index.php">
        <input type="submit" name="logout" value="Logout">
    </form>


</div>

<div id="content2">

    <b><u>Partie précédente :</u></b><br><br>

    <table>

        <tr>

            <th>date</th>
            <th>Nombre Trouvé</th>
            <th>Coup</th>

        </tr>
        <?php

        $stmt = $pdo->prepare("SELECT * FROM histo where userid=" . $_SESSION['userid'] . " ORDER BY date DESC");
        $stmt->execute();

        while ($result = $stmt->fetch()) {

            echo '<tr><td>' . date('d/m/Y à H\hi', $result["date"]) . '</td>';
            echo '<td>' . $result["nb"] . '</td>';
            echo '<td>' . $result["coup"] . '</td></tr>';

        }

        ?>

    </table>


</div>

</body>
</html>
