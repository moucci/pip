<?php
session_start();
$_SESSION = (object)$_SESSION;

//check if user is not connected befor to suscription
if (!isset($_SESSION->is_connected)) {
    header('Location:connexion.php?is_not_connected');
}

require_once("class/config.php");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="stylesheet" href="assets/css/style.css"> -->
    <link rel="stylesheet" href="assets/mscss/css/style.css">

    <title>Pip : Bienvenue</title>
</head>
<body>

    <div class="head_gestions">
        <img src="assets\img\pip.svg" alt="logo">
        <h1>Ça coule de source</h1>
    </div>

    <?php

    $db = getDb();

    $id = $_SESSION->id;

    $sql = "SELECT * FROM `compte` WHERE `id_client` = $id";

    $requete = $db->query($sql);

    $comptes = $requete->fetchAll(PDO::FETCH_ASSOC);

    var_dump($requete);


foreach($comptes as $compte):
    ?>
    <div class="clients">

        <div class="image">
            <div></div>
        </div>

        <div class="infos">
            <ul>
                <li>id client : <?= $compte["id_client"]; ?></li>
                <li>type de compte : <?= $compte["type_compte"]; ?></li>
                <li>solde : <?= $compte["solde"]; ?></li>
                <li>découvert : <?= $compte["decouvert"]; ?></li>
            </ul>

            <div class="trait"></div>

            <div>
                <p class="modifier"><a href="gestions.php?process=Comptes">Compte(s)</a></p>
                <p class="modifier"><a href="gestions.php?process=edit_client">Modifier</a></p>
                <p class="supprimer"><a href="gestions.php?process=delete_client">Supprimer</a></p>
            </div>

        </div>
    </div>


<?php endforeach;
?>


</body>
</html>