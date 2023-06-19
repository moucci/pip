<?php
session_start();
$_SESSION = (object)$_SESSION;

//check if user is not connected befor to suscription
if (!isset($_SESSION->is_connected)) {
    header('Location:index.php?is_not_connected');
    die();
}

require_once("includes/config.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="stylesheet" href="assets/css/style.css"> -->
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/app.js" defer></script>

    <title>Pip : Bienvenue</title>
</head>
<body>


<header>
    <nav>
        <a href=""><img src="assets\img\pip.svg" alt="logo"></a>
        <div>
            <a href="gestions.php">Acceuil</a>
            <a href="add-client.php">Ajouter un client</a>
            <a href="login.php?logout">Conseiller : <?= $_SESSION->name ?><span>Déconnexion</span></a>
        </div>

    </nav>
    <h1>List de vos clients</h1>
</header>


<?php if (!empty($_GET["process"]) && $_GET["process"] === "delete-client-success"): ?>
    <p class="success">Votre client a bien été supprimé.</p>
<?php endif; ?>

<?php if (!empty($_GET["process"]) && $_GET["process"] === "id-client-not-found"): ?>
    <p class="error">Aucun client sélectionné</p>
<?php endif; ?>



<?php if (!empty($_GET["process"]) && $_GET["process"] === "id_compte_not_found"): ?>
    <p class="error">Aucun compte sélectionné</p>
<?php endif; ?>


<?php if (!empty($_GET["process"]) && $_GET["process"] === "action-not-found"): ?>
    <p class="error">Aucun action sélectionné</p>
<?php endif; ?>

<?php if (!empty($_GET["process"]) && $_GET["process"] === "edit-client-success"): ?>
    <p class="success">La fiche du client à bien était modifier</p>
<?php endif; ?>


<?php if (!empty($_GET["process"]) && $_GET["process"] === "edit-client-error"): ?>
    <p class="error">La fiche du client n'a pu être modifier</p>
<?php endif;

$db = getDb();

$sql = "SELECT * FROM `clients` WHERE `id_conseiller` = :id_conseiller";

// $requete = $db->query($sql);

$requete = $db->prepare($sql);

$requete->bindValue(":id_conseiller", $_SESSION->id, PDO::PARAM_INT);

$requete->execute();

$clients = $requete->fetchAll(PDO::FETCH_ASSOC);


if (empty($clients)):?>

    <p class="une_alerte_trop_géniale">Vous ne gérez actuellement aucun pig... clients. Veuillez, s'il vous plait,
        travaillez un minimum !</p>

<?php endif;

foreach ($clients as $client):?>
    <div class="clients">

        <div class="image">
            <img src="assets/img/img-user.svg" alt="">
        </div>

        <div class="infos">
            <ul>
                <li>Identifiant Client : <?= $client["id"]; ?></li>
                <li>Nom : <?= $client["nom"]; ?></li>
                <li>Prénom : <?= $client["prenom"]; ?></li>
                <li>Email : <?= $client["email"]; ?></li>
                <li>Ville : <?= $client["ville"]; ?></li>
            </ul>
            <div>
                <a class="btn-green" href="comptes.php?process=comptes&id_client=<?= $client["id"] ?>">Compte(s)</a>
                <a class="btn-orange"
                   href="edit-client.php?id_client=<?= $client["id"] ?>">Modifier</a>
                <a class=" btn-red link_delete"
                   href="gestion-client.php?process=delete_client&id_client=<?= $client["id"] ?>">Supprimer</a>

            </div>

        </div>
    </div>
<?php endforeach; ?>

</body>
</html>
