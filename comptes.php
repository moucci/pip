<?php
session_start();
$_SESSION = (object)$_SESSION;

//check if user is not connected befor to suscription
if (!isset($_SESSION->is_connected)) {
    header('Location:connexion.php?is_not_connected');
    die();
}


if (empty($_GET["id_client"])  or empty($_GET['process'])){
    header('Location:gestions.php');
    die();
}

$process_autorise = [
    "comptes","edit_client","delete_client"
];


if (!in_array($_GET['process'] , $process_autorise)){
    header('Location:gestions.php?process_not_found');
    die();
}

$id_client = (int) $_GET['id_client'] ; 
if ($id_client === 0){
    header('Location:gestions.php?bad_id');
    die();
}


require_once("class/config.php");

$db = getDb();

$query = "SELECT * FROM `compte` WHERE `id_client` = :id_client";

$req = $db->prepare($query) ;

$req->bindParam(":id_client" , $id_client , PDO::PARAM_INT) ;

$req->execute();

$datas = $req->fetchAll(PDO::FETCH_ASSOC) ;



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

<header class="head_gestions">
    <ul>
        <li>
        <img src="assets\img\pip.svg" alt="logo">
        <h1>Ça coule de source</h1>
        </li>
        <li><a href="gestions.php">Acceuil</a></li>
        <li><a href="add-client.php">Ajouter un client</a></li>
        <li><a href="login.php?logout">Déconnexion</a></li>
    </ul>
    
</header>

<?php
if(empty($datas)){
    ?>

    <p class="une_alerte_trop_géniale">Actuellement, ce client n'a pas de comptes à sa disposition.</p>

    <?php
    
}
    
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
                <p class="comptes"><a href="clients.php?process=depot<?php echo "&id_client=".$client["id"] ?>">dépots</a></p>
                <p class="modifier"><a href="clients.php?process=retrait<?php echo "&id_client=".$client["id"] ?>">retraits</a></p>
                <p class="modifier"><a href="clients.php?process=decouvert<?php echo "&id_client=".$client["id"] ?>">Gestion du découvert</a></p>
                <p class="supprimer"><a href="clients.php?process=delete_compte<?php echo "&id_client=".$client["id"] ?>">Supprimer</a></p>
            </div>

        </div>
    </div>
<?php
endforeach;

?>

</body>
</html>