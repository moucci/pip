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

$process_autorise = [
    "comptes", "edit_client", "delete_client", "depot", "retrait", "decouvert"
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

$datas = $req->fetchAll(PDO::FETCH_ASSOC);


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
            <img src="assets\img\pip.svg" alt="logo">
            <h1>Ça coule de source</h1>
        </li>
        <li><a href="gestions.php">Acceuil</a></li>
        <li><a href="add-compte.php?process=add_compte&id_client=<?= $id_client ?>">Ajouter un compte</a></li>
        <li><a href="login.php?logout">Déconnexion</a></li>
    </ul>

</header>

<h2>Gestion des comptes clients</h2>


<?php
if (empty($datas)) {
    ?>

    <p class="une_alerte_trop_géniale">Actuellement, ce client n'a pas de comptes à sa disposition.</p>

    <div class="addcompte">
        <a href="comptes.php?process=addcompte">Créer un compte</a>
    </div>

    <?php

}


if ($_GET['process'] === "depot"): ?>


    <form class="gestion compte" action="">
        <p>Solde actuel : <?= $datas[0]["solde"]; ?></p>
        <input type="number" value="" name="montant" id="depot" placeholder="montant du dépot">
        <button class="adddepot" type="submit"
                onclick="if(confirm('Confirmez vous le dépot ?')) window.location.href('gestion-compte.php?process=depot&id_client='.<?= $id_client ?>;')">
            Confirmer le
            dépot
        </button>
    </form>


<?php

endif;

if ($_GET['process'] === "retrait"): ?>

    <form class="gestion compte" action="">
        <p>Solde actuel : <?= $datas[0]["solde"]; ?></p>
        <input type="number" value="" name="montant" id="retrait" placeholder="montant du retrait">
        <button class="adddepot" type="submit" onclick="return confirm('Confirmez vous le retrait ?');">Confirmer le
            retrait
        </button>
    </form>


<?php

endif;

if ($_GET['process'] === "decouvert"): ?>

    <form class="gestion compte" action="">
        <p>Solde actuel : <?= $datas[0]["solde"]; ?></p>
        <input type="number" value="" name="montant" id="decouvert" placeholder="découvert autorisé">
        <button class="adddepot" type="submit"
                onclick="return confirm('Confirmez vous la mise a jour du découvert ?');">Confirmer le découvert
        </button>
    </form>

<?php

endif;


foreach ($datas as $data):
    ?>
    <div class="clients">

        <div class="image">
            <div></div>
        </div>

        <div class="infos">
            <ul>
                <li>Id client : <?= $data["id_client"]; ?></li>
                <li>Type de compte : <?= $data["type_compte"]; ?></li>
                <li>Solde : <?= $data["solde"]; ?></li>
                <li>Découvert autorisé : <?= $data["decouvert"]; ?></li>
            </ul>

            <div class="trait"></div>

            <div>
                <p class="comptes"><a
                            href="comptes.php?process=depot<?php echo "&id_client=" . $data["id_client"] ?>">dépots</a>
                </p>
                <p class="modifier"><a
                            href="comptes.php?process=retrait<?php echo "&id_client=" . $data["id_client"] ?>">retraits</a>
                </p>
                <p class="modifier"><a
                            href="comptes.php?process=decouvert<?php echo "&id_client=" . $data["id_client"] ?>">Gestion
                        du découvert</a></p>
                <p class="supprimer"><a class="link_delete"
                                        href="gestion-client.php?process=delete_compte&<?php echo "&id_compte=" . $data["id"] ?>">Supprimer</a>
                </p>
            </div>

        </div>
    </div>
<?php
endforeach;

?>

</body>
</html>