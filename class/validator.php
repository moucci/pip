<?php

//import config file
require_once("class/config.php");


/**
 * Valider le nom ou le prénom
 * @param string $name Le nom du champ
 * @param string $value La valeur à valider
 * @return bool|string True si la valeur est valide, sinon le message d'erreur
 */
function checkName(string $name, string $value): bool|string
{
    $value = trim($value);

    // Vérifier la variable vide
    if (empty($value)) {
        return "Le champ $name est requis.";
    }
    // Vérifier la longueur
    if (strlen($value) < 1 || strlen($value) > 100) {
        return "La longueur du $name est incorrecte. Le $name doit comporter entre 2 et 50 caractères.";
    }
    // Vérifier les caractères spéciaux
    if (!preg_match('/^[a-zA-ZÀ-ÿ\s\'\-]+$/', $value)) {
        return "Le $name contient des caractères spéciaux non autorisés.";
    }

    return true;
}

/**
 * Valider l'adresse email
 * @param string $value La valeur à valider
 * @return bool|string True si la valeur est valide, sinon le message d'erreur
 */
function checkEmail(string $value): bool|string
{
    // Vérifier la variable vide
    if (empty($value)) {
        return "Le champ adresse email est requis.";
    }
    // Vérifier la validité de l'adresse email
    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        return "L'adresse email est invalide.";
    }
    return true;
}

/**
 * Valider le mot de passe
 * @param string $value La valeur à valider
 * @return bool|string True si la valeur est valide, sinon le message d'erreur
 */
function checkPass(string $value): bool|string
{
    // Vérifier la variable vide
    if (empty($value)) {
        return "Le champ mot de passe est requis.";
    }
    // Vérifier la longueur
    if (strlen($value) < 16) {
        return "La longueur du mot de passe est incorrecte. Le mot de passe doit comporter au moins 16 caractères.";
    }
    // Vérifier les caractères requis
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$&_\-^%])[A-Za-z\d@$&_\-^%]{16,}$/', $value)) {
        return "Le mot de passe est invalide. Il doit contenir au moins 16 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial.";
    }
    return true;
}

/**
 * Methode to register new conseiller
 * set session if process success
 * @param string $email
 * @param string $mdp
 * @return string|true error message | bool
 */
function loginConseiller(string $email, string $mdp)
{

    $db = getDb();

    $req = $db->prepare('SELECT id ,  nom as name , email , mdp as mdp_hashed FROM conseillers  where email = :email');
    $req->bindParam(':email', $email, PDO::PARAM_STR);

    ////try ton insert new conseiller
    if (!$req->execute()) return 'failed';


    //if user not existe
    if ($req->rowCount() === 0) return 'error';

    $data = $req->fetch(PDO::FETCH_OBJ);
    //check pass word hash
    if (!password_verify($mdp, $data->mdp_hashed)) {
        return 'error';
    }

    //set session
    $_SESSION['is_connected'] = true;
    $_SESSION['name'] = $data->name;
    $_SESSION['id'] = $data->id;

    return true;


}


function signupConseiller(string $email, string $mdp, string $nom, string $prenom, int $rgpd)
{


    print_r(func_get_args());

}