<?php

namespace App\Controllers;
class DBContactController
{



    // --------- ATRIBUTOS --------- //
    private $dbContactConnection = null;

    // --------- CONSTRUCTOR --------- //

    public function __construct()
    {

        $host = getenv('DB_HOST');
        $db = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASSWORD');


        try {
            $this->dbContactConnection = new \PDO(
                "mysql:host=$host;charset=utf8mb4;dbname=$db",
                $user,
                $pass
            );
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }

    }

    // --------- MÃTODOS --------- //

    public function getConnection()
    {
        return $this->dbContactConnection;

    }
}