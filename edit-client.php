<?php
session_start();

//check if user is not connected befor to suscription
if (!isset($_SESSION['is_connected'])) {
    header('Location:connexion.php?is_not_connected=edit-client');
    die();
}

//check if we have process
if (empty($_GET['id_client']) || !is_numeric($_GET['id_client'])) {
    header('Location:gestions.php?process=id-client-not-found&from=edit-client');
    die();
}

//define id client
$idClient = (int)$_GET['id_client'];

require_once('includes/validator.php');

//if conseiller post data
if (!empty($_POST)) {
    $idConseiller = $_SESSION['id'];
    $nom = trim($_POST['nom']) ?? '';
    $prenom = trim($_POST['prenom']) ?? '';
    $biday = trim($_POST['biday']) ?? '';
    $adresse = trim($_POST['adresse']) ?? '';
    $complement_adresse = trim($_POST['complement_adresse']) ?? '';
    $code_postal = trim($_POST['code_postal']) ?? '';
    $ville = trim($_POST['ville']) ?? '';
    $tel = trim($_POST['tel']) ?? '';
    $email = trim($_POST['email']) ?? '';

    //init array eroor empty
    $errors = [];

    //check nom
    $checkName = checkName('nom', $nom);
    if ($checkName !== true) $errors['nom'] = $checkName;

    //check prenom
    $checkPrenom = checkName('prenom', $prenom);
    if ($checkPrenom !== true) $errors['prenom'] = $checkPrenom;

    //check biday
    if (!preg_match('/^\d{4}-\d{2}-\d{2}/', $biday))
        $errors['biday'] = "Le champ date de naissance est invalide.";

    //check adresse
    if (empty($adresse)) $errors['adresse'] = "Le champ adresse est requis.";

    // check code_postal
    if (!preg_match('/^\d{4,7}/', $code_postal)) $errors['code_postal'] = "Le champ code postal est invalide.";

    //check ville
    if (!preg_match('/^[a-zA-Z]+(?:[\s-][a-zA-Z]+)*$/', $ville))
        $errors['ville'] = "Le champ ville est invalide.";


    //check tel
    if (!preg_match('/^[0-9][0-9]{9}$/', $tel))
        $errors['tel'] = "Le champ numéro de téléphone est invalide.";


    //check email
    $checkEmail = checkEmail($email);
    if ($checkEmail !== true) $errors['email'] = $checkEmail;


    // Vérifier s'il y a des erreurs
    if (empty($errors)) {
        $process = editClient($idClient, $nom, $prenom, $biday, $adresse, $complement_adresse, $code_postal, $ville, $tel, $email);
        if ($process !== true) {
            $msgError = $process;
        }
    }
} //else  get client from database
else {

    $db = getDb();
    $q = "SELECT * FROM clients  WHERE id = :idClient and id_conseiller = :idConseiller limit  1";
    $req = $db->prepare($q);
    $req->bindParam(":idClient", $idClient, PDO::PARAM_INT);
    $req->bindParam("idConseiller", $_SESSION["id"], PDO::PARAM_INT);
    try {
        $req->execute();
        if ($req->rowCount() === 0) {
            throw new Exception("Aucun utilisateur sélectionné");
        }
    } catch (Exception $e) {
        header('Location: gestions.php?process=id-client-not-found&from=edit-client-db-select');
        die();
    }

    $data = $req->fetch(PDO::FETCH_OBJ);
    //generate data for form
    $_POST['nom'] = $data->nom;
    $_POST['prenom'] = $data->prenom;
    $_POST['biday'] = $data->biday;
    $_POST['adresse'] = $data->adresse;
    $_POST['complement_adresse'] = $data->complement_adresse;
    $_POST['code_postal'] = $data->code_postal;
    $_POST['ville'] = $data->ville;
    $_POST['tel'] = $data->tel;
    $_POST['email'] = $data->email;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Document</title>
</head>
<body>
<header>
    <nav>
        <a href=""><img src="assets\img\pip.svg" alt="logo"></a>
        <div>
            <a href="gestions.php">Accueil</a>
            <a href="add-compte.php">Ajouter un compte</a>
            <a href="login.php?logout">Conseiller : <?= $_SESSION["name"] ?><span>Déconnexion</span></a>
        </div>

    </nav>
    <h1 style="text-align: center">Mettre à jour la fiche de
        : <?= htmlspecialchars($_POST['nom']) . ' ' . htmlspecialchars($_POST['prenom']) ?></h1>
</header>

<main id="inscription">
    <h3 style="color: red"><?= (isset($msgError)) ? $msgError : '' ?></h3>
    <form action="edit-client.php?id_client=<?= $idClient ?>" method="post" name="inscription">
        <span><?= (isset($errors['nom'])) ? $errors['nom'] : '' ?></span>
        <input type="text" value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>" name="nom"
               value="" id="nom" placeholder="Nom">

        <span><?= (isset($errors['prenom'])) ? $errors['prenom'] : '' ?></span>
        <input type="text" value="<?= isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : '' ?>"
               name="prenom" id="prenom" placeholder="Prénom">

        <span><?= (isset($errors['biday'])) ? $errors['biday'] : '' ?></span>
        <input type="date" value="<?= isset($_POST['biday']) ? htmlspecialchars($_POST['biday']) : '' ?>"
               name="biday" id="biday">

        <span><?= (isset($errors['adresse'])) ? $errors['adresse'] : '' ?></span>
        <input type="text" value="<?= isset($_POST['adresse']) ? htmlspecialchars($_POST['adresse']) : '' ?>"
               name="adresse" id="adresse" placeholder="Adresse">

        <span><?= (isset($errors['complement_adresse'])) ? $errors['complement_adresse'] : '' ?></span>
        <input type="text"
               value="<?= isset($_POST['complement_adresse']) ? htmlspecialchars($_POST['complement_adresse']) : '' ?>"
               name="complement_adresse" id="complement_adresse" placeholder="Complément d'adresse">

        <span><?= (isset($errors['code_postal'])) ? $errors['code_postal'] : '' ?></span>
        <input type="text"
               value="<?= isset($_POST['code_postal']) ? htmlspecialchars($_POST['code_postal']) : '' ?>"
               name="code_postal" id="code_postal" placeholder="Code postal">

        <span><?= (isset($errors['ville'])) ? $errors['ville'] : '' ?></span>
        <input type="text"
               value="<?= isset($_POST['ville']) ? htmlspecialchars($_POST['ville']) : '' ?>"
               name="ville" id="ville" placeholder="Ville">

        <span><?= (isset($errors['tel'])) ? $errors['tel'] : '' ?></span>
        <input type="text"
               value="<?= isset($_POST['tel']) ? htmlspecialchars($_POST['tel']) : '' ?>"
               name="tel" id="tel" placeholder="Numéro de téléphone">


        <span><?= (isset($errors['email'])) ? $errors['email'] : '' ?></span>
        <input type="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
               name="email" id="email" placeholder="Email">

        <button type="submit">Créer le client</button>
    </form>
</main>

</body>
</html>