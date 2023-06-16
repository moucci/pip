<?php
session_start();

if(!empty($_POST)){
    
    require_once('includes/validator.php') ;
    
    $idConseiller = $_SESSION['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $biday = $_POST['biday'];
    $adresse = $_POST['adresse'];
    $complement_adresse = $_POST['complement_adresse'];
    $code_postal = $_POST['code_postal'];
    $ville = $_POST['ville'];
    $tel = $_POST['tel'];
    $email = $_POST['email'];

    if(!empty($_POST['rgpd'])){
    $rgpd = $_POST['rgpd'];
    }



    $errors = [] ; 

    //nom
    $checkName = checkName('nom' , $_POST['nom']) ;
    if($checkName !== true ){
        $errors['nom'] = $checkName ; 
    }



    //prenom
    $checkPrenom = checkName('prenom' , $_POST['prenom']) ;
    if($checkPrenom !== true ){
        $errors['prenom'] = $checkPrenom ; 
    }



    //biday
    $checkBiday = $_POST['biday'];
    if(!preg_match('/^\d{4}-\d{2}-\d{2}/', $checkBiday)){
        $errors['biday'] = "Le champ date de naissance est invalide."; 
    }


    
    //adresse
    $checkAdresse = $_POST['adresse'];
    if (empty($checkAdresse)) {
        $errors['adresse'] = "Le champ adresse est requis.";
    }


    //code_postal
    $checkCodePostal = $_POST['code_postal'];
    if(!preg_match('/^\d{4,7}/', $checkCodePostal)){
        $errors['code_postal'] = "Le champ code postal est invalide."; 
    }

    

    //ville
    $checkVille = $_POST['ville'];
    if(!preg_match('/^[a-zA-Z]+(?:[\s-][a-zA-Z]+)*$/', $checkVille)){
        $errors['ville'] = "Le champ ville est invalide."; 
    }



    //tel
    $checkTel = $_POST['tel'];
    if(!preg_match('/^[0-9][0-9]{9}$/', $checkTel)){
        $errors['tel'] = "Le champ numéro de téléphone est invalide."; 
    }

    

    //email
    $checkEmail = checkEmail($_POST['email']) ;
    if($checkEmail !== true ){
        $errors['email'] = $checkEmail; 
    }

    
    //rgpd
    if(isset($_POST['rgpd']) === false){
        $errors['rgpd'] = 'Veuillez cocher la case.';
    }


     // Vérifier s'il y a des erreurs
     if (empty($errors)) {
        $process = addClient($idConseiller, $nom, $prenom, $biday, $adresse, $complement_adresse, $code_postal, $ville, $tel, $email, $rgpd);
        if ($process !== true) {
            $msgError = $process;
        }
    }


}




?>
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
        <form action="add-client.php" method="post" name="inscription">
            <span><?= (isset($errors['nom'])) ? $errors['nom'] : '' ?></span>
            <input type="text" value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>" name="nom"
                   value="" id="nom" placeholder="Nom">

            <span><?= (isset($errors['prenom'])) ? $errors['prenom'] : '' ?></span>
            <input type="text" value="<?= isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : '' ?>"
            name="prenom" id="prenom" placeholder="Prénom">

            <span><?= (isset($errors['biday'])) ? $errors['biday'] : '' ?></span>
            <input type="date" name="biday" id="biday">

            <span><?= (isset($errors['adresse'])) ? $errors['adresse'] : '' ?></span>
            <input type="text" name="adresse" id="adresse" placeholder="Adresse">

            <span><?= (isset($errors['complement_adresse'])) ? $errors['complement_adresse'] : '' ?></span>
            <input type="text" name="complement_adresse" id="complement_adresse" placeholder="Complément d'adresse">

            <span><?= (isset($errors['code_postal'])) ? $errors['code_postal'] : '' ?></span>
            <input type="text" name="code_postal" id="code_postal" placeholder="Code postal">

            <span><?= (isset($errors['ville'])) ? $errors['ville'] : '' ?></span>
            <input type="text" name="ville" id="ville" placeholder="Ville">

            <span><?= (isset($errors['tel'])) ? $errors['tel'] : '' ?></span>
            <input type="text" name="tel" id="tel" placeholder="Numéro de téléphone">

            

            
            <span><?= (isset($errors['email'])) ? $errors['email'] : '' ?></span>
            <input type="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                   name="email" id="email" placeholder="Email">

            

            <span><?= (isset($errors['rgpd'])) ? $errors['rgpd'] : '' ?></span>
            <div class="remember">
                <input type="checkbox" <?= (isset($rgpd) && $rgpd) ? 'checked' : '' ?> value="1" name="rgpd"
                       id="rgpd">
                <label for="rgpd">J'accepte la collecte de mes données</label>
            </div>

            <button type="submit">Créer le client</button>
        </form>
    </main>
    
</body>
</html>