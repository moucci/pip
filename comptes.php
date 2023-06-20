<?php
session_start();
$_SESSION = (object)$_SESSION;


//check if user is not connected befor to suscription
if (!isset($_SESSION->is_connected)) {
    header('Location:connexion.php?is_not_connected');
    die();
}

if (empty($_GET["id_client"]) or empty($_GET['process'])) {
    header('Location:gestions.php');
    die();
}


$idxCompte = null;
if (isset($_GET["idx_compte"]))
    $idxCompte = (is_numeric($_GET["idx_compte"])) ? $_GET["idx_compte"] : null;


$process_autorise = [
    "comptes", "edit_client", "delete_client", "depot", "retrait", "decouvert", "addcompte"
];

if (!in_array($_GET['process'], $process_autorise)) {
    header('Location:gestions.php?process_not_found');
    die();
}

$id_client = (int)$_GET['id_client'];
if ($id_client === 0) {
    header('Location:gestions.php?bad_id');
    die();
}

require_once("includes/config.php");

$db = getDb();

$query = "SELECT * FROM `compte` WHERE `id_client` = :id_client";

$req = $db->prepare($query);

$req->bindParam(":id_client", $id_client, PDO::PARAM_INT);

$req->execute();

//$req->debugDumpParams();

$datas = $req->fetchAll(PDO::FETCH_ASSOC);


//echo '<pre>';
//print_r($datas);
//echo '</pre>';

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
            <a href="add-compte.php?process=add_compte&id_client=<?= $id_client ?>">Ajouter un compte à un clients</a>
            <a href="login.php?logout">Conseiller : <?= $_SESSION->name ?><span>Déconnexion</span></a>
        </div>

    </nav>
    <h1>Gestion des comptes clients</h1>
</header>

<?php if (!empty($_GET["msg"]) && $_GET["msg"] === "depot-compte-success"): ?>
    <p class="success">Le dépôt a bien été pris en compte </p>
<?php endif; ?>

<?php if (!empty($_GET["msg"]) && $_GET["msg"] === "depot-compte-error"): ?>
    <p class="error">Le dépôt n'a pas pu être pris en compte </p>
<?php endif; ?>

<?php if (!empty($_GET["msg"]) && $_GET["msg"] === "retrait-compte-success"): ?>
    <p class="success">Le retrait a bien été pris en compte </p>
<?php endif; ?>

<?php if (!empty($_GET["msg"]) && $_GET["msg"] === "retrait-compte-error"): ?>
    <p class="error">Le retrait n'a pas pu être pris en compte </p>
<?php endif; ?>

<?php if (!empty($_GET["msg"]) && $_GET["msg"] === "delete-compte-success"): ?>
    <p class="success">Le compte du client a été supprimé</p>
<?php endif; ?>

<?php if (!empty($_GET["msg"]) && $_GET["msg"] === "delete-compte-error"): ?>
    <p class="error">Le compte du client n'a pas pu être supprimé</p>
<?php endif; ?>

<?php if (!empty($_GET["msg"]) && $_GET["msg"] === "add-compte-success"): ?>
    <p class="success">Le compte à été ajouté pour le client</p>
<?php endif; ?>

<?php if (!empty($_GET["msg"]) && $_GET["msg"] === "add-compte-error"): ?>
    <p class="error">Le compte n'a pas pu être ajouté</p>
<?php endif; ?>

<?php if (!empty($_GET["msg"]) && $_GET["msg"] === "decouvert-compte-success"): ?>
    <p class="success">Le découvert a été mis à jour </p>
<?php endif; ?>

<?php if (!empty($_GET["msg"]) && $_GET["msg"] === "decouvert-compte-error"): ?>
    <p class="error">Le découvert n'a pas pu être mis à jour</p>
<?php endif; ?>

<?php
if (empty($datas)) {
    ?>

    <p class="une_alerte_trop_géniale">Actuellement, ce client n'a pas de comptes à sa disposition.</p>

    <div class="addcompte">
        <a href="add-compte.php?process=add_compte&id_client=<?= $id_client ?>">Créer un compte</a>
    </div>

    <?php

}


if ($_GET['process'] === "depot" && !is_null($idxCompte)): ?>

    <form class="gestion compte" method="post" action="gestion-client.php?process=depot">
        <p>Solde actuel : <?= $datas[$idxCompte]["solde"]; ?> €</p>
        <input type="number" value="" name="montant" id="depot" placeholder="montant du dépot">
        <input type="hidden" value="<?= $datas[$idxCompte]["id"] ?>" name="id_compte" id="depot"
               placeholder="montant du dépot">
        <input type="hidden" value="<?= $datas[$idxCompte]["id_client"] ?>" name="id_client" id="depot">

        <button class="adddepot" type="submit"
                onclick="confirm('Confirmez vous le dépot ?')">
            Confirmer le dépot
        </button>
    </form>


<?php

endif;

if ($_GET['process'] === "retrait" && !is_null($idxCompte)): ?>

    <form class="gestion compte" method="post" action="gestion-client.php?process=retraits">
        <p>Solde actuel : <?= $datas[$idxCompte]["solde"]; ?> €</p>
        <input type="number" value="" name="montant" id="retrait" placeholder="montant du retrait">
        <input type="hidden" value="<?= $datas[$idxCompte]["id"] ?>" name="id_compte" id="depot"
               placeholder="montant du dépot">
        <input type="hidden" value="<?= $datas[$idxCompte]["id_client"] ?>" name="id_client" id="depot">

        <button class="adddepot" type="submit" onclick="return confirm('Confirmez vous le retrait ?');">Confirmer le
            retrait
        </button>
    </form>


<?php

endif;

if ($_GET['process'] === "decouvert" && !is_null($idxCompte)): ?>
    <form class="gestion compte" method="post" action="gestion-client.php?process=decouvert">
        <p>Découvert client autorisé actuellement : <?= $datas[$idxCompte]["decouvert"]; ?> €</p>
        <input type="number" value="" name="montant" id="decouvert" placeholder="découvert autorisé">
        <input type="hidden" value="<?= $datas[$idxCompte]["id"] ?>" name="id_compte" id="depot"
               placeholder="montant du dépot">
        <input type="hidden" value="<?= $datas[$idxCompte]["id_client"] ?>" name="id_client" id="depot">
        <button class="adddepot" type="submit"
                onclick="return confirm('Confirmez vous la mise a jour du découvert ?');">Confirmer le découvert
        </button>
    </form>
<?php

endif;


foreach ($datas as $key => $data):
    if ($data["type_compte"] === 'LIVRET-A'):
        $classStyleCompte = "account-a";
    elseif ($data["type_compte"] === 'PEL'):
        $classStyleCompte = "account-b";
    elseif ($data["type_compte"] === 'COURANT'):
        $classStyleCompte = "account-c";
    endif;
    ?>
    <div class="clients">

        <div class="image">
            <span class="<?= $classStyleCompte ?>"><?= $data["type_compte"] ?></span>
        </div>

        <div class="infos">
            <ul>
                <li>Identifiant client : <?= $data["id_client"]; ?></li>
                <li>Type de compte : <?= $data["type_compte"]; ?></li>
                <li class="<?= ($data["solde"] < 0) ? 'alert' : '' ?>">Solde : <?= $data["solde"]; ?> €</li>
                <li>Découvert autorisé : <?= $data["decouvert"]; ?> €</li>
            </ul>

            <div>
                <a class="btn-green"
                   href="comptes.php?process=depot&id_client=<?= $data["id_client"] ?>&idx_compte=<?= $key ?>">dépots</a>

                <a class="btn-blue"
                   href="comptes.php?process=retrait&id_client=<?= $data["id_client"] ?>&idx_compte=<?= $key ?>">retraits</a>

                <a class="btn-orange"
                   href="comptes.php?process=decouvert&id_client=<?= $data["id_client"] ?>&idx_compte=<?= $key ?>">Gestion
                    du découvert</a>
                <a class=" btn-red link_delete"
                   href="gestion-client.php?process=delete_compte&id_compte=<?= $data["id"] ?>&id_client=<?= $data["id_client"] ?>">Supprimer</a>

            </div>

        </div>
    </div>
<?php
endforeach;

?>

</body>
</html>