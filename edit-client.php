<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Document</title>
</head>
<body>

<main id="inscription">
    <img src="assets\img\pip.svg" alt="logo">
    <h1>Ça coule de source</h1>
    <h2>Inscription</h2>
    <h3 style="color: red"><?= (isset($msgError)) ? $msgError : '' ?></h3>
    <form action="gestion-client.php?process=add_client" method="post" name="inscription">
        <span><?= (isset($erreurs['nom'])) ? $erreurs['nom'] : '' ?></span>
        <input type="text" value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>" name="nom"
               value="" id="nom" placeholder="Nom">

        <span><?= (isset($erreurs['prenom'])) ? $erreurs['prenom'] : '' ?></span>
        <input type="text" value="<?= isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : '' ?>"
               name="prenom" id="prenom" placeholder="Prénom">


        <input type="date" name="biday" id="biday">

        <input type="text" name="adresse" id="adresse" placeholder="Adresse">

        <input type="text" name="complement_adresse" id="complement_adresse" placeholder="Complément d'adresse">

        <input type="text" name="code_postal" id="code_postal" placeholder="Code postal">

        <input type="text" name="ville" id="ville" placeholder="Ville">

        <input type="text" name="tel" id="tel" placeholder="Numéro de téléphone">


        <span><?= (isset($erreurs['email'])) ? $erreurs['email'] : '' ?></span>
        <input type="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
               name="email" id="email" placeholder="Email">

        <div class="remember">
            <input type="checkbox" <?= (isset($rgpd) && $rgpd) ? 'checked' : '' ?> value="1" name="rgpd"
                   id="rgpd">
            <label for="rgpd">J'accepte la collecte de mes données</label>
            <span><?= (isset($erreurs['rgpd'])) ? $erreurs['rgpd'] : '' ?></span>
        </div>

        <button type="submit">Créer le client</button>
    </form>
</main>

</body>
</html>