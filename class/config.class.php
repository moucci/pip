<?php

namespace app\class;

use PDO;
use PDOException;

class Config
{
    /**
     * host for database
     * @var string
     */
    private string $dbHost = "localhost";

    /**
     * name of database
     * @var string
     */
    private string $dbName = "pip";

    /**
     * name user for database
     * @var string
     */
    private string $dbUser = "root";

    /**
     * password for database
     * @var string
     */
    private string $dbPass = "";

    public function __construct()
    {
    }

    /**
     * methode te try open pdo connexion or return message error
     * @return PDO|string
     */
    public function getDb()
    {
        try {
            $dsn = "mysql:host=" . $this->dbHost . ";dbname=" . $this->dbName . ";charset=utf8mb4";
            $pdo = new PDO($dsn, $this->dbUser, $this->dbPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            return "Erreur de connexion : " . $e->getMessage();
        }
    }


}