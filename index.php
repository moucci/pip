<?php
session_start();

//convert session to object
$_SESSION = (object)$_SESSION;

//check if user is not connected
if (isset($_SESSION->is_connected)) {
    header('Location:gestions.php');
    die();
}

//if have process error in url
if (isset($_GET['process']) && $_GET['process'] === 'error') {
    $msgError = 'Email ou mot de passe incorrect. Veuillez réessayer.';
} elseif (isset($_GET['process']) && $_GET['process'] === 'failed') {
    $msgError = "Une erreur technique s'est produite. Veuillez nous excuser pour le désagrément. Réessayez ultérieurement.";
} else {
    $msgError = '';
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="assets/js/app.js" defer></script>
    <title>connexion</title>
</head>
<body>
<main id="connexion">
    <img src="assets\img\pip.svg" alt="logo">
    <h1>La banque qui ne rigole pas</h1>
    <h2>CONNEXION</h2>
    <h3><?= $msgError ?></h3>
    <form action="login.php" method="POST" name="connexion">
        <input type="email" value="" name="email" id="email" placeholder="Email">
        <div class="pass">
            <input type="password" value="" autocomplete="false" name="mdp" id="mdp"
                   placeholder="Mot de passe">
            <span>
                <img src="assets/img/eyes.svg" alt="">
                </span>
        </div>
        <div class="remember">
            <input type="checkbox" name="remeberme" id="remeberme">
            <label for="rgpd">Rester connecté</label>
        </div>
        <span class="margin-20">
                Vous n'avez pas encore de compte,  <a href="inscription.php">veuillez vous inscrire.</a>
            </span>
        <button type="submit" value="OK">Se connecter</button>
    </form>
</main>
</body>
</html>