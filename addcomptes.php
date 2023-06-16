<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets\mscss\scss\style.css">
    <link rel="stylesheet" href="assets\mscss\css\style.css">
    <title>Document</title>
</head>
<body>

<main id="inscription">
    <img src="assets\img\pip.svg" alt="logo">
    <h1>Ça coule de source</h1>
    <h2>Ajout d'un compte</h2>
    <h3 style="color: red"><?= (isset($msgError)) ? $msgError : '' ?></h3>
    <form action="comptes.php?process=addcompte" method="post" name="addcompte" class="addcompte">

<p class="typecompte">Type de compte</p>
    <select>
        <option value="livreta" class="livreta">Livret A</option>
        <option value="LEP">LEP</option>
      </select>

        <input type="number" name="solde" id="solde" placeholder="Solde a l'ouverture">

        <button type="submit" class="comptebtn" onclick="if(confirm('Confirmez vous la création du compte ?'))>Créer le compte</button>
    </form>
</main>

</body>
</html>