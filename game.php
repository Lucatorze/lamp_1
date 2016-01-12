<?php
session_start();

if(empty($_SESSION['bol']))
{
    $_SESSION['bol'] = mt_rand(1, 100);

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

if(!isset($_POST['guess'])){

    $response = "Pas de nombre<br><br>";

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
    <input type="submit">

</form>

<br><br>

<b>Meilleur score :</b> <?php echo $_SESSION['nb'].' en '.$_SESSION['coup'].' coups !'; ?>


</body>
</html>
