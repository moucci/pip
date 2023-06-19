<?php
session_start();

//check if user is not connected befor to suscription
if (!isset($_SESSION['is_connected'])) {
    header('Location:connexion.php?is_not_connected=gestion-client');
    die();
}


//list of valide action
$process_autorise = [
    "comptes",
    "edit_client",
    "delete_client",
    "delete_compte",
    "depot",
    "retraits",
    "add_compte",
    "decouvert"
];

//check if we have process
if (empty($_GET['process']) || !in_array($_GET['process'], $process_autorise)) {
    header('Location:gestions.php?process=action-not-found&from=gestion-client');
    die();
}


//import function page
require_once('includes/validator.php');


//choose a good action and execute it
switch (strtolower($_GET['process'])) {
    case 'depot':
        depotClient();
        break;
    case 'retraits':
        retraitClient();
        break;
    case 'decouvert':
        decouvertClient();
        break;
    case 'edit_client':
        editClient();
        break;
    case 'delete_client':
        deleteClient();
        break;
    case 'delete_compte':
        deleteCompte();
        break;
    case 'add_compte':
        addCompte();
        break;
    default:
        header('Location:gestions.php?process=action-not-found&from=gestion-client-switch');
        break;
}