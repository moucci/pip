<?php
session_start();

//convert session to object
$_SESSION = (object)$_SESSION;

//check if user is not connected
if (isset($_SESSION->is_connected)) {
    echo 'utilisateur deja connecter , déconnecter vous avant de pouvoir vous inscrire';
    die();
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
        <h1></h1>
        <form action="action.php" method="POST"  name="connexion" >
            <input type="email" name="email" id="email" placeholder="Email">
            <input type="password" name="mdp" id="mdp" placeholder="Mot de passe">
            <div>
                <input type="checkbox" name="remeberme" id="remeberme">
                <label for="rgpd">Rester connecté</label>
            </div>
            <button type="submit" value="OK">Se connecter</button>
        </form>
    </main>

</body>
</html>