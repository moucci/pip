<?php
session_start();

//convert session to object


//check if user is not connected befor to suscription
if (isset($_SESSION['is_connected'])) {
    header('Location:gestions.php?is_connected');
    die();
}

if (!empty($_POST)) {
    require_once('includes/validator.php');


    // Récupérer les données du formulaire
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $mdp = $_POST['mdp'] ?? '';
    $rgpd = $_POST['rgpd'] ?? 0;

    // Tableau pour stocker les messages d'erreur
    $erreurs = [];

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

    //check rgpd

    if ($rgpd != 1) {
        $erreurs['rgpd'] = 'Veuillez accepter la collecte des vos informations';
    }

    // Vérifier s'il y a des erreurs
    if (empty($erreurs)) {
        $process = signupConseiller($email, $mdp, $nom, $prenom, $rgpd);
        if ($process !== true) {
            $msgError = $process;
        }
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
        <script src="assets/js/app.js" defer></script>
        <title>Document</title>
    </head>
    <body>
    <main id="inscription">
        <img src="assets\img\pip.svg" alt="logo">
        <h1>La banque qui ne rigole pas</h1>
        <h2>Inscription</h2>
        <h3 style="color: red"><?= (isset($msgError)) ? $msgError : '' ?></h3>
        <form action="inscription.php" method="post" name="inscription">
            <span><?= (isset($erreurs['nom'])) ? $erreurs['nom'] : '' ?></span>
            <input type="text" value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>" name="nom"
                   value="" id="nom" placeholder="Nom">

            <span><?= (isset($erreurs['prenom'])) ? $erreurs['prenom'] : '' ?></span>
            <input type="text" value="<?= isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : '' ?>"
                   name="prenom" id="prenom" placeholder="Prénom">

            <span><?= (isset($erreurs['email'])) ? $erreurs['email'] : '' ?></span>
            <input type="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                   name="email" id="email" placeholder="Email">

            <span><?= (isset($erreurs['mdp'])) ? $erreurs['mdp'] : '' ?></span>
            <div class="pass">
                <input type="password" value="" autocomplete="false" name="mdp" id="mdp"
                       placeholder="Mot de passe">
                <span>
                    <img src="assets/img/eyes.svg" alt="">
                </span>
            </div>


            <div class="remember">
                <input type="checkbox" <?= (isset($rgpd) && $rgpd) ? 'checked' : '' ?> value="1" name="rgpd"
                       id="rgpd">
                <label for="rgpd">J'accepte la collecte de mes données</label>
                <span><?= (isset($erreurs['rgpd'])) ? $erreurs['rgpd'] : '' ?></span>
            </div>

            <span class="margin-20">
                Vous avez déjà un compte, veuillez  <a href="index.php">vous connecter</a>
            </span>

            <button type="submit">S'inscrire</button>
        </form>
    </main>

    </body>
    </html>
<?php
