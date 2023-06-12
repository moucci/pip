<?php
session_start();

//convert session to object
$_SESSION = (object)$_SESSION;

//check if user is not connected
if (isset($_SESSION->is_connected)) {
    header('Location:https://pip.test/gestion.php');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/css/style.css" rel="stylesheet">
    <title>connexion</title>
</head>
<body>
<main id="connexion">
    <img src="assets\img\pip.svg" alt="logo">
    <h1>Ça coule de source</h1>
    <h2>CONNEXION</h2>
    <form action="login.php" method="POST" name="connexion">
        <input type="email" name="email" id="email" placeholder="Email">
        <input type="password" name="mdp" id="mdp" placeholder="Mot de passe">
        <div class="remember">
            <input type="checkbox" name="remeberme" id="remeberme">
            <label for="rgpd">Rester connecté</label>
        </div>
        <button type="submit" value="OK">Se connecter</button>
    </form>
</main>

</body>
</html>