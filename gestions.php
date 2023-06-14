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
    <link rel="stylesheet" href="assets/mscss/css/style.css">
    <script src="assets/js/app.js" defer></script>

    <title>Pip : Bienvenue</title>
</head>
<body>

<header class="head_gestions">
    <ul>
        <li>
            <img src="assets/img/pip.svg" alt="logo">
            <h1>Ça coule de source</h1>
        </li>
        <li><a href="gestions.php">Acceuil</a></li>
        <li><a href="add-client.php">Ajouter un client</a></li>
        <li><a href="login.php?logout">Déconnexion</a></li>
    </ul>

</header>

<?php if (!empty($_GET["process"]) && $_GET["process"] === "delete-client-success"): ?>
    <p class="success">Votre client a bien été supprimé.</p>
<?php endif; ?>

<?php if (!empty($_GET["process"]) && $_GET["process"] === "id-client-not-found"): ?>
    <p class="error">Aucun client sélectionné</p>
<?php endif; ?>

<?php if (!empty($_GET["process"]) && $_GET["process"] === "action-not-found"): ?>
    <p class="error">Aucun action sélectionné</p>
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
            <div></div>
        </div>

        <div class="infos">
            <ul>
                <li>Prénom : <?= $client["prenom"]; ?></li>
                <li>Nom : <?= $client["nom"]; ?></li>
                <li>Email : <?= $client["email"]; ?></li>
                <li>Ville : <?= $client["ville"]; ?></li>
            </ul>

            <div class="trait"></div>

            <div>
                <p class="comptes"><a href="comptes.php?process=comptes<?php echo "&id_client=" . $client["id"] ?>">Compte(s)</a>
                </p>
                <p class="modifier"><a
                            href="clients.php?process=edit_client<?php echo "&id_client=" . $client["id"] ?>">Modifier</a>
                </p>
                <p class="supprimer"><a class="link_delete"
                                        href="gestion-client.php?process=delete_client<?php echo "&id_client=" . $client["id"] ?>">Supprimer</a>
                </p>
            </div>

        </div>
    </div>
<?php endforeach; ?>

</body>
</html>
