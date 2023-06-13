<?php
//set start
session_start();

//if user ask for logout , check  variable $_GET['logout'] &&$_SESSION['is_connected'])
if (isset($_GET['logout']) && isset($_SESSION['is_connected'])) {
    session_destroy();
    header('Location: index.php');
}

//check if user is not connected befor to suscription
if (isset($_SESSION['is_connected'])) {
    header('Location:gestions.php?is_connected');
}

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
    header('Location:index.php?process=error');
}

//try to connect user
$process = loginConseiller($email, $mdp);

if ($process !== true) {
    header("Location:index.php?process=$process");
} else {
    //if user is connected redirect to gestion.php
    header('Location:gestions.php');
}


