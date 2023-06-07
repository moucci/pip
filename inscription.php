<?php
//start session

use app\class\Config;
use app\class\Validator;

require_once 'class/config.class.php';
require_once 'class/validator.class.php';

$validator = new Validator();

session_start();

//convert session to object
$_SESSION = (object)$_SESSION;

//check if user is not connected befor to suscription
if (isset($_SESSION->is_connected)) {
    echo 'utilisateur deja connecter , déconnecter vous avant de pouvoir vous inscrire';
    die();
}


// Récupérer les données du formulaire
$nom = $_POST['nom'] ?? '';
$prenom = $_POST['prenom'] ?? '';
$email = $_POST['email'] ?? '';
$mdp = $_POST['mot_de_passe'] ?? '';
$rgpd = $_POST['rgpd'] ?? 0 ;

// Tableau pour stocker les messages d'erreur
$erreurs = array();

$validator = new Validator();

// Vérification du champ nom
$validationNom = $validator->checkName('nom', $nom);
if ($validationNom !== true) {
    $erreurs['nom'] = $validationNom;
}

// Vérification du champ prénom
$validationPrenom = $validator->checkName('prénom', $prenom);
if ($validationPrenom !== true) {
    $erreurs['prenom'] = $validationPrenom;
}

// Vérification du champ adresse email
$validationEmail = $validator->checkEmail($email);
if ($validationEmail !== true) {
    $erreurs['email'] = $validationEmail;
}

// Vérification du champ mot de passe
$validationMotDePasse = $validator->checkPass($mdp);
if ($validationMotDePasse !== true) {
    $erreurs['mot_de_passe'] = $validationMotDePasse;
}

// Vérifier s'il y a des erreurs
if (!empty($erreurs)) {
    // Afficher les messages d'erreur
    foreach ($erreurs as $champ => $message) {
        echo ucfirst($champ) . " : " . $message . "<br>";
    }
}

//Instancier la class config
$config = new Config();
$db = $config->getDb();

//if can't connect to database
if (!$db instanceof PDO) {
    echo('impossible de se connecté à la base de données');
    var_dump($db);
    die();
}

$query = "INSERT INTO conseillers  (nom , prenom ,email , mdp , rgpd  )
                                    VALUES (:nom , :prenom , :email , :mdp , :rgpd)";

//prepare query
$req = $db->prepare($query);

$req->bindParam(':nom' , $nom , PDO::PARAM_STR) ;
$req->bindParam(':prenom' , $prenom , PDO::PARAM_STR) ;
$req->bindParam(':email' , $email , PDO::PARAM_STR) ;
$req->bindParam(':mdp' , $mdp , PDO::PARAM_STR) ;
$req->bindParam(':rgpd' , $rgpd , PDO::PARAM_INT) ;


//try ton insert new conseiller
try{
    $req->execute() ;
    echo 'inscription réussite' ;

}catch (PDOException $e){
    if($e->getCode() == '23000'){
        echo 'Conseiller déja inscrit' ;
    }

//    $req->debugDumpParams();
//    print_r($req->errorInfo());
}



