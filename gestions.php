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

<?php 

$db = getDb();


$id = $_SESSION->id;

$sql = "SELECT * FROM `clients` WHERE `id_conseiller` = $id";



$requete = $db->query($sql);

$clients = $requete->fetchAll(PDO::FETCH_ASSOC);



foreach($clients as $client):
    ?>
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
