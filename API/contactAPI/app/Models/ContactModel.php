<?php

namespace App\Models;


class ContactModel {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll() {
        $statement = "
            SELECT 
                *
            FROM
                contactos;
        ";

        try {

            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}