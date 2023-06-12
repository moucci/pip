<?php
//start session
session_start();

require_once('class/validator.php');


//convert session to object
$_SESSION = (object)$_SESSION;


////check if user is not connected befor to suscription
if (isset($_SESSION->is_connected)) {
    header('Location:gestions.php?is_connected');
}


// Récupérer les données du formulaire
$nom = $_POST['nom'] ?? '';
$prenom = $_POST['prenom'] ?? '';
$email = $_POST['email'] ?? '';
$mdp = $_POST['mot_de_passe'] ?? '';
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

    header('Location:inscription.php?errors=');

}


signupConseiller($email, $mdp, $nom, $prenom, $rgpd);

exit();
//try init connexion to data base
$db = getDb();

$query = "INSERT INTO conseillers  (nom , prenom ,email , mdp , rgpd  )
                                    VALUES (:nom , :prenom , :email , :mdp , :rgpd)";

//prepare query
$req = $db->prepare($query);

$req->bindParam(':nom', $nom, PDO::PARAM_STR);
$req->bindParam(':prenom', $prenom, PDO::PARAM_STR);
$req->bindParam(':email', $email, PDO::PARAM_STR);

//hash mdp
$mdp = password_hash($mdp, PASSWORD_ARGON2ID, ['const' => 10]);
$req->bindParam(':mdp', $mdp, PDO::PARAM_STR);
$req->bindParam(':rgpd', $rgpd, PDO::PARAM_INT);


////try ton insert new conseiller
try {
    $req->execute();
    header('Location:https://pip.test/inscription.php?register=true',);
} catch (PDOException $error) {
    if ($error->getCode() == '23000') {
        echo 'Conseiller déja inscrit';
    }
}






