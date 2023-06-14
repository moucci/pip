<?php
session_start();

//check if user is not connected befor to suscription
if (!isset($_SESSION['is_connected'])) {
    header('Location:connexion.php?is_not_connected=gestion-client');
    die();
}


//if (empty($_GET["id_client"]) or empty($_GET['process'])) {
//    header('Location:gestions.php?not_found=id_client');
//    die();
//}

$process_autorise = [
    "comptes", "edit_client", "delete_client", "add_client"
];

//check if we have process
if (empty($_GET['process']) || !in_array($_GET['process'], $process_autorise)) {
    header('Location:gestions.php?process=action-not-found&from=gestion-client');
    die();
}

require_once('includes/validator.php');

if (strtolower($_GET['process']) === 'comptes'):
    listCompte();
elseif (strtolower($_GET['process']) === 'edit_client'):
    editClient();
elseif (strtolower($_GET['process']) === 'delete_client'):
    deleteClient();
elseif (strtolower($_GET['process']) === 'add_client'):
    addClient();
endif;