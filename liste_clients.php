<?php

session_start();


if (!isset($_SESSION['is_connected'])) {
    header('Location:connexion.php');
}

print_r($_SESSION);

require_once("class/validator.php");
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/mscss/css/style.css">
    <title>Document</title>
</head>
<body>
    


<?php 

$email = 'email@e.mail';
$mdp = 'mdp';

$db = getDb();

$sql = "SELECT * FROM `conseillers` WHERE `email` = '$email'";

$requete = $db->query($sql);

$conseiller = $requete->fetch(PDO::FETCH_ASSOC);

$id = $conseiller["id"];

$sql = "SELECT * FROM `clients` WHERE `id_conseiller` = $id";

$requete = $db->query($sql);

$clients = $requete->fetchAll(PDO::FETCH_ASSOC);

// var_dump($_SESSION);
// echo $_SESSION['id'];


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
                <p class="modifier"><a href="gestions.php">Modifier</a></p>
                <p class="depot"><a href="gestions.php">Dépôt</a></p>
                <p class="retrait"><a href="gestions.php">Retrait</a></p>
                <p class="supprimer"><a href="gestions.php">Supprimer</a></p>
            </div>

        </div>
    </div>


<?php endforeach;
?>

</body>
</html>