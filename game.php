<?php
session_start();

if(!isset($_SESSION['user'])){

    header("Location: /index.php");
    exit;

}

if(empty($_SESSION['bol']) || isset($_POST['reset']))
{
    $_SESSION['bol'] = mt_rand(0, 100);
    $_SESSION['nombre_envoi'] = 0;
    $response = "Entrez un nombre pour commencer<br><br>";

}

$response = null;

if(isset($_POST['guess'])){

    if (!isset($_SESSION['nombre_envoi'])){

        $_SESSION['nombre_envoi'] = 1;

    }
    else{

        $_SESSION['nombre_envoi'] ++;

    }
}

if(!isset($_POST['guess']) || isset($_POST['reset'])){

    $response = "Entrez un nombre pour commencer<br><br>";

}
else{

    $guess = $_POST['guess'];

    if($guess > $_SESSION['bol']){

        $response = "Ce n'est pas ".$guess.", c'est moins !<br><br>";

    }
    elseif($guess < $_SESSION['bol']){

        $response = "Ce n'est pas ".$guess.", c'est plus !<br><br>";

    }
    else{

        $response = "C'est gagné ! c'est bien le nombre ".$guess.", vous l'avez trouver en ".$_SESSION['nombre_envoi']." cliques !<br><br>";

        if($_SESSION['nombre_envoi'] < $_SESSION['coup']){

            $_SESSION['nb'] = $guess;
            $_SESSION['coup'] = $_SESSION['nombre_envoi'];

        }
        else{

            unset($_SESSION['bol']);
            unset($_SESSION['nombre_envoi']);

        }

        unset($_SESSION['bol']);
        unset($_SESSION['nombre_envoi']);

    }

}

?>

<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <title>Des papier dans un bol</title>

    <script>

        window.onload = function(){

            document.getElementById('input').focus();

        }



    </script>

</head>
<body>

<?php echo $response;?>


<form method="POST">

    <input type="text" name="guess" id="input">
    <input type="submit" name="Envoi"><input type="submit" name="reset" value="Reset">

</form>

<br><br>

Meilleur score pour <b><?php echo $_SESSION['user'];?></b>: <?php echo $_SESSION['nb'].' en '.$_SESSION['coup'].' coups !'; ?><br><br>

<form method="POST" action="/index.php">
    <input type="submit" name="logout" value="Logout">
</form>


</body>
</html>
