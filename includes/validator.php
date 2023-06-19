<?php

//import config file
require_once("includes/config.php");

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


/**
 * methode to register conseillers
 * @param string $email
 * @param string $mdp
 * @param string $nom
 * @param string $prenom
 * @param int $rgpd
 * @return string|void
 */
function signupConseiller(string $email, string $mdp, string $nom, string $prenom, int $rgpd)
{
    //try init connexion to data base
    $db = getDb();

    $query = "INSERT INTO conseillers   (nom , prenom ,email , mdp , rgpd  )
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

    //try ton insert new conseiller
    try {
        $req->execute();
        //set session
        $_SESSION['is_connected'] = true;
        $_SESSION['name'] = $nom;
        $_SESSION['id'] = $db->lastInsertId();
        header('Location:https://pip.test/gestions.php?register=true',);
        die();
    } catch (PDOException $error) {
        if ($error->getCode() == '23000') {
            return 'Conseiller déja inscrit';
        } else return 'Une erreur technique est survenue. Veuillez réessayer ultérieurement. Merci de votre compréhension.';
    }
}


/**
 * methode de delete client from table clients
 * @return void
 */
function deleteClient()
{
    if (empty($_GET["id_client"]) || !is_numeric($_GET["id_client"])) {
        header('Location:gestions.php?process=id_client_not_found&from=gestion-client');
        die();
    }

    $idClient = $_GET["id_client"];
    $idConseiller = $_SESSION['id'];

    //get connexion db
    $db = getDb();

    $q = "DELETE  FROM clients WHERE id = :id_client  and id_conseiller = :id_conseiller ";
    $req = $db->prepare($q);
    $req->bindParam(':id_client', $idClient, PDO::PARAM_INT);
    $req->bindParam(':id_conseiller', $idConseiller, PDO::PARAM_INT);

    //execute query
    try {
        $req->execute();
    } catch (PDOException $e) {
        echo "Une erreur est survenue , en temps normal je vous l'affiche pas je la log de un fichier php_error 
                 mais la pour le dev voici l'erreur en question :" . $e->getMessage();
        die;
    }

    //if user deleted
    if ($req->rowCount() > 0) {
        $req->closeCursor();
        header('Location: gestions.php?process=delete-client-success');
    } else {
        $req->closeCursor();
        header('Location: gestions.php?process=delete-client-error');
    }
    die();
}

/**
 * methode delete compte by conseiller
 * @return void
 */
function deleteCompte()
{
    if (empty($_GET["id_compte"]) || !is_numeric($_GET["id_compte"])) {
        header('Location:gestions.php?process=id_compte_not_found&from=gestion-client-delete-compte');
        die();
    }
    if (empty($_GET["id_client"]) || !is_numeric($_GET["id_client"])) {
        header('Location:gestions.php?process=id_client_not_found&from=gestion-client-delete-compte');
        die();
    }

    $idCompte = $_GET["id_compte"];
    $idClient = $_GET['id_client'];
    $idConseiller = $_SESSION['id'];

    //get connexion db
    $db = getDb();

    $q = "DELETE c FROM compte c 
            INNER JOIN clients cl ON c.id_client = cl.id
            WHERE c.id = :id_compte
            AND cl.id_conseiller = :id_conseiller;";


    $req = $db->prepare($q);
    $req->bindParam(':id_compte', $idCompte, PDO::PARAM_INT);
    $req->bindParam(':id_conseiller', $idConseiller, PDO::PARAM_INT);

    //execute query
    try {
        $req->execute();
    } catch (PDOException $e) {
        echo "Une erreur est survenue , en temps normal je vous l'affiche pas je la log de un fichier php_error 
                 mais la pour le dev voici l'erreur en question :" . $e->getMessage();
        die;
    }

    //if user deleted
    if ($req->rowCount() > 0) {
        $req->closeCursor();
        header("Location: comptes.php?id_client=$idClient&process=comptes&msg=delete-compte-success");
    } else {
        $req->closeCursor();
        header("Location: comptes.php?id_client=$idClient&process=comptes&msg=delete-compte-error");
    }
    die();


}


/**
 * methode to register conseillers
 * @param int $idConseiller
 * @param string $nom
 * @param string $prenom
 * @param string $biday
 * @param string $adresse
 * @param string $complement_adresse
 * @param string $code_postal
 * @param string $ville
 * @param string $tel
 * @param string $email
 * @param int $rgpd
 * @return string|void
 */
function addClient(string $idConseiller, string $nom, string $prenom, string $biday, string $adresse, string $complement_adresse, string $code_postal, string $ville, string $tel, string $email, int $rgpd)
{
    //try init connexion to data base
    $db = getDb();

    $query = "INSERT INTO `clients`  (id_conseiller, nom , prenom , biday , adresse , complement_adresse, code_postal, ville, tel, email, rgpd )
                                    VALUES (:idConseiller, :nom , :prenom , :biday , :adresse , :complement_adresse, :code_postal, :ville, :tel, :email, :rgpd)";

    //prepare query
    $req = $db->prepare($query);

    $req->bindParam(':idConseiller', $idConseiller, PDO::PARAM_INT);
    $req->bindParam(':nom', $nom, PDO::PARAM_STR);
    $req->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $req->bindParam(':biday', $biday, PDO::PARAM_STR);
    $req->bindParam(':adresse', $adresse, PDO::PARAM_STR);
    $req->bindParam(':complement_adresse', $complement_adresse, PDO::PARAM_STR);
    $req->bindParam(':code_postal', $code_postal, PDO::PARAM_STR);
    $req->bindParam(':ville', $ville, PDO::PARAM_STR);
    $req->bindParam(':tel', $tel, PDO::PARAM_STR);
    $req->bindParam(':email', $email, PDO::PARAM_STR);
    $req->bindParam(':rgpd', $rgpd, PDO::PARAM_INT);

    //try ton insert new conseiller
    try {
        $req->execute();
        header('Location:gestions.php?register=true',);
        die;
    } catch (PDOException $error) {
        if ($error->getCode() == '23000') {
            return 'Client déja inscrit';
        } else return 'Une erreur technique est survenue. Veuillez réessayer ultérieurement. Merci de votre compréhension.';
    }
}

function editClient(int $idClient, string $nom, string $prenom, string $biday, string $adresse, string $complement_adresse, string $code_postal, string $ville, string $tel, string $email)
{
    $db = getDb();
    $q = "UPDATE clients SET nom = :nom, prenom = :prenom, biday = :biday, adresse = :adresse, complement_adresse = :complement_adresse, code_postal = :code_postal, ville = :ville, tel = :tel, email = :email WHERE id = :idClient AND id_conseiller = :idConseiller";
    $req = $db->prepare($q);
    $req->bindParam(":nom", $nom);
    $req->bindParam(":prenom", $prenom);
    $req->bindParam(":biday", $biday);
    $req->bindParam(":adresse", $adresse);
    $req->bindParam(":complement_adresse", $complement_adresse);
    $req->bindParam(":code_postal", $code_postal);
    $req->bindParam(":ville", $ville);
    $req->bindParam(":tel", $tel);
    $req->bindParam(":email", $email);
    $req->bindParam(":idClient", $idClient);
    $req->bindParam(":idConseiller", $_SESSION["id"]);

    //try update
    try {
        $req->execute();
        $rowCount = $req->rowCount();
        if ($rowCount === 0) {
            throw new Exception("Aucune ligne mise à jour");
        }
    } catch (Exception $e) {
        header('Location: gestions.php?process=edit-client-error&from=edit-client-db-update');
        die();
    }

    header('Location: gestions.php?process=edit-client-success');
    die();
}


/** methode  add money to account's user
 * @return void
 */
function depotClient()
{

    //check id compte
    if (empty($_POST["id_compte"]) || !is_numeric($_POST["id_compte"])) {
        header('Location: comptes.php?process=id_compte_not_found&from=gestion-client-depot');
        die();
    }

    //check montant
    if (!isset($_POST["montant"]) || !is_numeric($_POST["montant"])) {
        header('Location: comptes.php?process=montant_not_found&from=gestion-client-depot');
        die();
    }

    //check client id
    if (empty($_POST["id_client"]) || !is_numeric($_POST["id_client"])) {
        header('Location:gestions.php?process=id_client_not_found&from=gestion-client-decouvert');
        die();
    }


    $idCompte = $_POST["id_compte"];
    $idConseiller = $_SESSION['id'];
    $montant = $_POST["montant"];

    //get connexion db
    $db = getDb();

    $q = "UPDATE compte AS c
            LEFT JOIN clients AS cl ON c.id_client = cl.id
                SET c.solde = c.solde + :montant
                WHERE c.id = :id_compte AND cl.id_conseiller = :id_conseiller;";

    $req = $db->prepare($q);
    $req->bindParam(':id_compte', $idCompte, PDO::PARAM_INT);
    $req->bindParam(':id_conseiller', $idConseiller, PDO::PARAM_INT);
    $req->bindParam(':montant', $montant, PDO::PARAM_INT);

    //execute query
    try {
        $req->execute();
    } catch (PDOException $e) {
        echo "Une erreur est survenue , en temps normal je vous l'affiche pas je la log de un fichier php_error 
                 mais la pour le dev voici l'erreur en question :" . $e->getMessage();
        die;
    }

    $req = $db->prepare("SELECT id_client FROM compte  WHERE  id = :id_compte");
    $req->bindParam(':id_compte', $idCompte, PDO::PARAM_INT);
    //execute query
    try {
        $req->execute();
    } catch (PDOException $e) {
        echo "Une erreur est survenue , en temps normal je vous l'affiche pas je la log de un fichier php_error 
                 mais la pour le dev voici l'erreur en question :" . $e->getMessage();
        die;
    }
    //get id client
    $idClient = $req->fetch(PDO::FETCH_OBJ)->id_client;


    //if user deleted
    if ($req->rowCount() > 0) {
        header("Location: comptes.php?process=comptes&msg=depot-compte-success&id_client=$idClient");
    } else {
        header("Location: comptes.php?process=comptes&msg=depot-compte-error&id_client=$idClient");
    }
    die();

}


/** methode  add money to account's user
 * @return void
 */
function retraitClient()
{

    //check id compte
    if (empty($_POST["id_compte"]) || !is_numeric($_POST["id_compte"])) {
        header('Location: comptes.php?process=id_compte_not_found&from=gestion-client-retrait');
        die();
    }

    //check montant
    if (!isset($_POST["montant"]) || !is_numeric($_POST["montant"])) {
        header('Location: comptes.php?process=montant_not_found&from=gestion-client-retrait');
        die();
    }

    //check client id
    if (empty($_POST["id_client"]) || !is_numeric($_POST["id_client"])) {
        header('Location:gestions.php?process=id_client_not_found&from=gestion-client-decouvert');
        die();
    }


    $idCompte = $_POST["id_compte"];
    $idConseiller = $_SESSION['id'];
    $montant = $_POST["montant"];

    //get connexion db
    $db = getDb();

    //update new solde if user had money
    $q = "UPDATE compte AS c
                LEFT JOIN clients AS cl ON c.id_client = cl.id
                SET c.solde = (c.solde - :montant)
                WHERE c.id = :id_compte 
                AND cl.id_conseiller = :id_conseiller
                AND (c.solde - :montant) >= -c.decouvert;";

    $req = $db->prepare($q);
    $req->bindParam(':id_compte', $idCompte, PDO::PARAM_INT);
    $req->bindParam(':id_conseiller', $idConseiller, PDO::PARAM_INT);
    $req->bindParam(':montant', $montant, PDO::PARAM_INT);

    //execute query
    try {
        $req->execute();
    } catch (PDOException $e) {
        echo "Une erreur est survenue , en temps normal je vous l'affiche pas je la log de un fichier php_error 
                 mais la pour le dev voici l'erreur en question :" . $e->getMessage();
        die;
    }

    $update = $req->rowCount() > 0 ? true : false;

    $req = $db->prepare("SELECT id_client FROM compte  WHERE  id = :id_compte");
    $req->bindParam(':id_compte', $idCompte, PDO::PARAM_INT);
    //execute query
    try {
        $req->execute();
    } catch (PDOException $e) {
        echo "Une erreur est survenue , en temps normal je vous l'affiche pas je la log de un fichier php_error 
                 mais la pour le dev voici l'erreur en question :" . $e->getMessage();
        die;
    }
    //get id client
    $idClient = $req->fetch(PDO::FETCH_OBJ)->id_client;


    //if user deleted
    if ($update) {
        header("Location: comptes.php?process=comptes&msg=retrait-compte-success&id_client=$idClient");
    } else {
        header("Location: comptes.php?process=comptes&msg=retrait-compte-error&id_client=$idClient");
    }
    die();

}

/**methode  add Compte to client
 * @return void
 */
function addCompte()
{

    //check id client
    if (empty($_POST["id_client"]) || !is_numeric($_POST["id_client"])) {
        header('Location: add-compte.php?process=id_client_not_foundt&from=gestion-client-add-compte');
        die();
    }
    //check type compte
    if (empty($_POST["type"])) {
        header('Location: comptes.php?process=type_not_found&from=gestion-client-add-compte');
        die();
    }
    //check solde compte
    if (empty($_POST["solde"]) || !is_numeric($_POST["solde"])) {
        header('Location: comptes.php?process=invalid_solde&from=gestion-client-add-compte');
        die();
    }

    $idClient = $_POST["id_client"];
    $idConseiller = $_SESSION['id'];
    $solde = $_POST["solde"];
    $type = strtolower($_POST["type"]);

    //get connexion db
    $db = getDb();

    $q = "INSERT INTO compte (id_client, type_compte, solde) VALUES (:idClient , :type , :solde)";

    $req = $db->prepare($q);
    $req->bindParam(":idClient", $idClient, PDO::PARAM_INT);
    $req->bindParam(":type", $type, PDO::PARAM_STR);
    $req->bindParam(":solde", $solde, PDO::PARAM_INT);

    try {
        $req->execute();
    } catch (PDOException $e) {
        echo "Une erreur est survenue , en temps normal je vous l'affiche pas je la log de un fichier php_error 
                 mais la pour le dev voici l'erreur en question :" . $e->getMessage();
        die;
    }

    //if added account
    if ($req->rowCount() > 0) {
        $req->closeCursor();
        header("Location: comptes.php?id_client=$idClient&process=comptes&msg=add-compte-success");
    } else {
        $req->closeCursor();
        header("Location: comptes.php?id_client=$idClient&process=comptes&msg=add-compte-error");
    }
    die();


}

function decouvertClient()
{


    //check id compte
    if (empty($_POST["id_compte"]) || !is_numeric($_POST["id_compte"])) {
        header('Location: gestion.php?process=id_compte_not_found&from=gestion-client-decouvert-idc');
        die();
    }

    //check montant
    if (!isset($_POST["montant"]) || !is_numeric($_POST["montant"])) {
        header('Location: gestion.php?process=montant_not_found&from=gestion-client-decouvert-m');
        die();
    }

    //check client id
    if (empty($_POST["id_client"]) || !is_numeric($_POST["id_client"])) {
        header('Location:gestions.php?process=id_client_not_found&from=gestion-client-decouvert-idc');
        die();
    }


    $idCompte = $_POST["id_compte"];
    $idClient = $_POST["id_client"];
    $idConseiller = $_SESSION['id'];
    $montant = $_POST["montant"];

    //get connexion db
    $db = getDb();

    //update new solde if user had money
    $q = "UPDATE compte AS c
            LEFT JOIN clients AS cl ON c.id_client = cl.id
            SET c.decouvert = :montant
            WHERE c.id = :id_compte 
            AND cl.id_conseiller = :id_conseiller";

    $req = $db->prepare($q);
    $req->bindParam(':id_compte', $idCompte, PDO::PARAM_INT);
    $req->bindParam(':id_conseiller', $idConseiller, PDO::PARAM_INT);
    $req->bindParam(':montant', $montant, PDO::PARAM_INT);

    //execute query
    try {
        $req->execute();
    } catch (PDOException $e) {
        echo "Une erreur est survenue , en temps normal je vous l'affiche pas je la log de un fichier php_error 
                 mais la pour le dev voici l'erreur en question :" . $e->getMessage();
        die;
    }

    $update = $req->rowCount() > 0 ? true : false;

    //if user deleted
    if ($update) {
        header("Location: comptes.php?process=comptes&msg=decouvert-compte-success&id_client=$idClient");
    } else {
        header("Location: comptes.php?process=comptes&msg=decouvert-compte-error&id_client=$idClient");
    }
    die();


}