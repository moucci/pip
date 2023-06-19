<?php
    session_start();

    //check if user is not connected befor to suscription
    if (!isset($_SESSION['is_connected'])) {
        header('Location:connexion.php?is_not_connected=add-compte');
        die();
    }

    //check params
    if (empty($_GET['id_client']) || !is_numeric($_GET['id_client'])) {
        header('Location:gestions.php?process=add-compte-error-not-found');
        die();
    }

    require_once('includes/validator.php');

    $db = getDb();

    $q = "SELECT id , nom , prenom FROM clients  WHERE id = :id_client limit 1";

    $req = $db->prepare($q);
    $req->bindParam(':id_client', $_GET['id_client'], PDO::PARAM_INT);

    //execute query
    try {
        $req->execute();
    } catch (PDOException $e) {
        echo "Une erreur est survenue , en temps normal je vous l'affiche pas je la log de un fichier php_error
                 mais la pour le dev voici l'erreur en question :" . $e->getMessage();
        die;
    }

    //fetch data and close connexion
    $client = $req->fetch(PDO::FETCH_OBJ);
    $req->closeCursor();

    $qTypeCompte = $db->query("SELECT DISTINCT type_compte FROM compte;");

    $listTypeCompte = $qTypeCompte->fetchAll(PDO::FETCH_OBJ);

    $qTypeCompte->closeCursor();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="stylesheet" href="assets/css/style.css"> -->
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/app.js" defer></script>
    <title>Pip : Ajouter un compte</title>
</head>
<body>

<header>
    <nav>
        <a href=""><img src="assets\img\pip.svg" alt="logo"></a>
        <div>
            <a href="gestions.php">Acceuil</a>
            <a href="login.php?logout">Déconnexion</a>
        </div>

    </nav>
    <h1>
        Ajout d'un compte pour le client <span><?= $client->nom . ' ' . $client->prenom ?></span>
    </h1>
</header>

<main id="section-add-client">
    <h3 style="color: red"><?= (isset($msgError)) ? $msgError : '' ?></h3>
    <form action="gestion-client.php?process=add_compte" method="post" name="addcompte" class="addcompte">
        <select name="type">
            <?php foreach ($listTypeCompte as $Compte): ?>
                <option value="<?= $Compte->type_compte ?>"><?= $Compte->type_compte ?></option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="solde" id="solde" placeholder="Solde a l'ouverture">
        <input type="hidden" name="id_client" value="<?= $client->id ?>">
        <button type="submit" class="comptebtn" onclick="if(confirm('Confirmez vous la création du compte ?'))">Créer le
            compte
        </button>
    </form>
</main>

</body>
</html>