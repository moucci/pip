<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="connexion.css" rel="stylesheet">
    <title>connexion</title>
</head>
<body>
    
    <form action="action.php" method="post" name="connexion" id="connexion"> 
        <input type="email" name="email" id="email" placeholder="Email">
        <input type="nom" name="nom" id="nom" placeholder="nom">
        <input type="prenom" name="prenom" id="prenom" placeholder="prenom">
        <input type="password" name="mdp" id="mdp" placeholder="Mot de passe">
        <button type="submit" value="OK">Se connecter</button>
    </form>
    
</body>
</html>