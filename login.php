<?php

require_once('class/validator.php');

// Récupérer les données du formulaire
$email = $_POST['email'] ?? '';
$mdp = $_POST['mdp'] ?? '';
// Tableau pour stocker les messages d'erreur
$erreurs = array();
// Vérification du champ adresse email
$validationEmail = checkEmail($email);
if ($validationEmail !== true) {
    $erreurs['email'] = $validationEmail;
}

// Vérification du champ mot de passe
$validationMotDePasse = checkPass($mdp);
if ($validationMotDePasse !== true) {
    $erreurs['mot_de_passe'] = $validationMotDePasse;
}

// Vérifier s'il y a des erreurs
if (!empty($erreurs)) {
    header('Location:connexion.php?process=error');
}

//try to connect user
$process = loginConseiller($email, $mdp);
if ($process !== true) {
    header("Location:connexion.php?process=$process");
} else {
    //if user is connected redirect to gestion.php
    header('Location:gestions.php');
}


