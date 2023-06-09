<?php
//start session
session_start();

require_once('class/validator.php');


/**
 * host for database
 * @var string
 */
const  DB_HOST = "localhost";

/**
 * name of database
 * @var string
 */
const DB_NAME = "pip";

/**
 * name user for database
 * @var string
 */
const  DB_USER = "root";

/**
 * password for database
 * @var string
 */
const  DB_PASS = "";


//convert session to object
$_SESSION = (object)$_SESSION;


////check if user is not connected befor to suscription
//if (isset($_SESSION->is_connected)) {
//    echo 'utilisateur deja connecter , déconnecter vous avant de pouvoir vous inscrire';
//    die();
//}


// Récupérer les données du formulaire
$nom = $_POST['nom'] ?? 'zak';
$prenom = $_POST['prenom'] ?? 'lepauvre';
$email = $_POST['email'] ?? 'il-est-trop-con@paf.fr';
$mdp = $_POST['mot_de_passe'] ?? 'A@8900000000000000000000000u';
$rgpd = $_POST['rgpd'] ?? 0;


// Tableau pour stocker les messages d'erreur
$erreurs = array();


// Vérification du champ nom
$validationNom = checkName('nom', $nom);
if ($validationNom !== true) {
    $erreurs['nom'] = $validationNom;
}

// Vérification du champ prénom
$validationPrenom = checkName('prénom', $prenom);
if ($validationPrenom !== true) {
    $erreurs['prenom'] = $validationPrenom;
}

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
    // Afficher les messages d'erreur
    foreach ($erreurs as $champ => $message) {
        echo ucfirst($champ) . " : " . $message . "<br>";
    }
    die();
}


//try init connexion to data base
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $db = new PDO($dsn, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit("Erreur de connexion : " . $e->getMessage());
}

$query = "INSERT INTO conseillers  (nom , prenom ,email , mdp , rgpd  )
                                    VALUES (:nom , :prenom , :email , :mdp , :rgpd)";

//prepare query
$req = $db->prepare($query);
$req->bindParam(':nom', $nom, PDO::PARAM_STR);
$req->bindParam(':prenom', $prenom, PDO::PARAM_STR);
$req->bindParam(':email', $email, PDO::PARAM_STR);
$req->bindParam(':mdp', $mdp, PDO::PARAM_STR);
$req->bindParam(':rgpd', $rgpd, PDO::PARAM_INT);


////try ton insert new conseiller
//try {
//    $req->execute();
//    header('Location:https://pip.test/inscription.php?register=true',);
//} catch (PDOException $error) {
//    if ($error->getCode() == '23000') {
//        echo 'Conseiller déja inscrit';
//    }
//}



$q = $db->prepare('select * from conseillers where 1 ');

$q->execute() ;

$data = $q->fetchAll(PDO::FETCH_OBJ);









