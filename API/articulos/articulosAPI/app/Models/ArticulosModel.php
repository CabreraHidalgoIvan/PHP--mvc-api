<?php

namespace App\Models;

use PDO;

class ArticulosModel {

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
                articulos;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    # Metodo
    public function find($id)
    {
        $statement = "
            SELECT 
                *
            FROM
                articulos
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


    public function advancedSearch($sql, $params) {
        var_dump($params);
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        echo "SQL: " . $sql;
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    # Metodo
    public function insert(array $input)
    {
        $statement = "
            INSERT INTO articulos 
                (codigo, descripcion, embalaje, nombre_tecnico)
            VALUES
                (:codigo, :descripcion, :embalaje, :nombre_tecnico)
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'codigo' => $input['codigo'],
                'descripcion'  => $input['descripcion'],
                'embalaje' => $input['embalaje'],
                'nombre_tecnico' => $input['nombre_tecnico'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    # Metodo
    public function update($id, array $input)
    {
        $statement = "
            UPDATE articulos
            SET 
                codigo = :codigo,
                descripcion  = :descripcion,
                embalaje = :embalaje,
                nombre_tecnico = :nombre_tecnico
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'codigo' => $input['codigo'],
                'descripcion'  => $input['descripcion'],
                'embalaje' => $input['embalaje'],
                'nombre_tecnico' => $input['nombre_tecnico'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    # Metodo
    public function delete($id)
    {
        $statement = "
            DELETE FROM articulos
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

    public function storeImagePath($imagePath) {
        // Aquí puedes ejecutar una consulta SQL para insertar la ruta de la imagen en la base de datos
        $sql = "INSERT INTO fotos_articulo (fichero) VALUES (:image_path)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':image_path', $imagePath);
        $stmt->execute();
    }

    public function updateImagePath($id, $imagePath)
    {
        $query = "UPDATE fotos_articulo SET fichero = :image_path WHERE id_articulo = :id";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':id_articulo', $id);
        $statement->bindParam(':fichero', $imagePath);
        $statement->execute();
    }


}
