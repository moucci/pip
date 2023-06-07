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
    <img src="assets\img\pip.svg" alt="logo">
    
</body>
</html>

Bienvenue <?php echo htmlspecialchars($_POST['prenom']); ?> <?php echo htmlspecialchars($_POST['nom']); ?>.
<br>
Vous etes maintenant bien connecté.