<?php
session_start();

//convert session to object
$_SESSION = (object)$_SESSION;

//check if user is not connected befor to suscription
//if (isset($_SESSION->is_connected)) {
//    echo 'utilisateur deja connecter , déconnecter vous avant de pouvoir vous inscrire';
//    die();
//}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="inscription.css">
    <title>Document</title>
</head>
<body>

<?php
if (isset($_GET['register']) && $_GET['register'] === 'true') {

    echo 'connexion réussie' ;

} else {
    ?>

    <form action="register.php" method="post" name="inscription" id="inscription">
        <input type="text" name="nom" id="nom" placeholder="Nom">
        <input type="text" name="prenom" id="prenom" placeholder="Prénom">
        <input type="email" name="email" id="email" placeholder="Email">
        <input type="password" name="mdp" id="mdp" placeholder="Mot de passe">
        <div>
            <input type="checkbox" name="rgpd" id="rgpd">
            <label for="rgpd">J'accepte la collecte de mes données</label>
        </div>
        <button type="submit">S'inscrire</button>
    </form>

    <?php
}
?>

</body>
</html>