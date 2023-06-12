<?php
session_start();

//convert session to object
$_SESSION = (object)$_SESSION;

//check if user is not connected befor to suscription
if (isset($_SESSION->is_connected)) {
    header('Location:gestions.php?is_connected');
}


if (!empty($_POST)) {
    require_once('class/validator.php');

    //convert session to object
    $_SESSION = (object)$_SESSION;


    //check if user is not connected befor to suscription
    if (isset($_SESSION->is_connected)) {
        header('Location:gestions.php?is_connected');
    }


    // Récupérer les données du formulaire
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $mdp = $_POST['mot_de_passe'] ?? '';
    $rgpd = $_POST['rgpd'] ?? 0;

    // Tableau pour stocker les messages d'erreur
    $erreurs = array();

    // Vérification du champ nom
    $validationNom = checkName('nom', $nom);
    if ($validationNom !== true) {
        $erreurs['nom'] = $validationNom;
    }

    // Vérification du champ prénom
    $validationPrenom = checkName('prénom', $prenom);
    if ($validationPrenom !== true) {
        $erreurs['prenom'] = $validationPrenom;
    }

    // Vérification du champ adresse email
    $validationEmail = checkEmail($email);
    if ($validationEmail !== true) {
        $erreurs['email'] = $validationEmail;
    }

    // Vérification du champ mot de passe
    $validationMotDePasse = checkPass($mdp);
    if ($validationMotDePasse !== true) {
        $erreurs['mdp'] = $validationMotDePasse;
    }

    // Vérifier s'il y a des erreurs
    if (empty($erreurs)) {
        exit('start register');
    }

}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Document</title>
</head>
<body>
<main id="inscription">
    <img src="assets\img\pip.svg" alt="logo">
    <h1>Ça coule de source</h1>
    <h2>Inscription</h2>
    <?php
    if (isset($_GET['register']) && $_GET['register'] === 'true'):
        echo 'connexion réussie';
    else :
        ?>
        <form action="inscription.php" method="post" name="inscription">
            <span><?= (isset($erreurs['nom'])) ? $erreurs['nom'] : '' ?></span>
            <input type="text" name="nom"  value="<?php (isset() ?> " id="nom" placeholder="Nom">
            <span><?= (isset($erreurs['prenom'])) ? $erreurs['prenom'] : '' ?></span>

            <input type="text" name="prenom" id="prenom" placeholder="Prénom">
            <span><?= (isset($erreurs['email'])) ? $erreurs['email'] : '' ?></span>

            <input type="email" name="email" id="email" placeholder="Email">
            <span><?= (isset($erreurs['mdp'])) ? $erreurs['mdp'] : '' ?></span>

            <input type="password" name="mdp" id="mdp" placeholder="Mot de passe">
            <div>
                <input type="checkbox" name="rgpd" id="rgpd">
                <label for="rgpd">J'accepte la collecte de mes données</label>
                <span><?= (isset($erreurs['rgpd'])) ? $erreurs['rgpd'] : '' ?></span>

            </div>
            <button type="submit">S'inscrire</button>
        </form>
    <?php
    endif;
    ?>
</main>

</body>
</html>