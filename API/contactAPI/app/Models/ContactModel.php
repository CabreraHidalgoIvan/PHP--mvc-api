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

    public function find($id)
    {
        $statement = "
            SELECT 
                *
            FROM
                contactos
            WHERE id = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(array $input)
    {
        $statement = "
            INSERT INTO contactos 
                (nombre, telefono, email)
            VALUES
                (:nombre, :telefono, :email);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'nombre' => $input['nombre'],
                'telefono'  => $input['telefono'],
                'email' => $input['email'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update($id, array $input)
    {
        $statement = "
            UPDATE contactos
            SET 
                nombre = :nombre,
                telefono  = :telefono,
                email = :email,
                updated_at = :updated_at
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'nombre' => $input['nombre'],
                'telefono'  => $input['telefono'],
                'email' => $input['email'],
                'updated_at' => date("Y-m-d H:i:s"),
                'id' => (int) $id
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }


    public function delete($id)
    {
        $statement = "
            DELETE FROM contactos
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}