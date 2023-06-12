<?php

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


/**
 * methode te try open pdo connexion or return message error
 * @return PDO|string
 */
function getDb()
{
//try init connexion to data base
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $db = new PDO($dsn, DB_USER, DB_PASS);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        exit("Erreur de connexion : " . $e->getMessage());
    }
}


