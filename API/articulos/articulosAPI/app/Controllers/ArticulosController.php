<?php

namespace ArticulosController;

use App\Models\ArticulosModel;

class ArticulosController {

    // --------- ATRIBUTOS --------- //

    private $db;
    private $requestMethod;
    private $articuloId;
    private $articulosModel;

    // --------- CONSTRUCTOR --------- //

    public function __construct($db, $requestMethod, $articuloId) {

        $this->db = $db;

        $this->requestMethod = $requestMethod;

        $this->articuloId = $articuloId;

        $this->articulosModel = new ArticulosModel($db);

    }

    // --------- PROCESAMIENTO --------- //

    # Procesamiento
    public function processRequest() {


        switch ($this->requestMethod) {
            case 'GET':
                if ($this->articuloId) {
                    $response = $this->getArticulo($this->articuloId);
                } else {
                    var_dump($_GET['search']);
                    $response = $this->getAllArticulos();
                };
                break;
            case 'POST':
                $response = $this->uploadImageForArticle();
                break;
            case 'PUT':
                $response = $this->updateArticuloFromRequest($this->articuloId);
                break;
            case 'DELETE':
                $response = $this->deleteArticulo($this->articuloId);
                break;
            case 'AS':
                $response = $this->getArticuloAdvanced();
                break;
            case 'UPLOAD':
                $response = $this->uploadImageForArticle();
                break;
        }

        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }

    }

    // --------- MÃTODOS --------- //

    private function getAllArticulos()
    {
        $result = $this->articulosModel->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result, JSON_THROW_ON_ERROR);
        return $response;
    }

    # Metodo
    public function getArticulo($id)
    {
        $result = $this->articulosModel->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }


    public function getArticuloAdvanced() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        $wheres = array();
        $params = array();

        if (isset($input['codigo'])) {
            $wheres[] = 'codigo LIKE :codigo';
            $params[':codigo'] = "%" . $input['codigo'] . "%";
        }
        if (isset($input['descripcion'])) {
            $wheres[] = 'descripcion LIKE :descripcion';
            $params[':descripcion'] = "%" . $input['descripcion'] . "%";
        }
        if (isset($input['embalaje'])) {
            $wheres[] = 'embalaje LIKE :embalaje';
            $params[':embalaje'] = "%" . $input['embalaje'] . "%";
        }
        if (isset($input['nombre_tecnico'])) {
            $wheres[] = 'nombre_tecnico LIKE :nombre_tecnico';
            $params[':nombre_tecnico'] = "%" . $input['nombre_tecnico'] . "%";
        }

        $sql = "SELECT * FROM articulos";
        if (!empty($wheres)) {
            $sql .= " WHERE " . implode(' OR ', $wheres);
        }

        $result = $this->articulosModel->advancedSearch($sql, $params);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);

        return $response;
    }

    # Metodo
    /**
     * @throws \JsonException
     */
    public function createArticuloFromRequest(): array
    {
        $input = (array)json_decode(file_get_contents('php://input'), TRUE, 512, JSON_THROW_ON_ERROR);
        if (!$this->validateArticulo($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->articulosModel->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode([
            'status' => 'success',
            'message' => 'Articulo creado correctamente'
        ], JSON_THROW_ON_ERROR);
        return $response;
    }

    # Metodo
    /**
     * @throws \JsonException
     */
    private function updateArticuloFromRequest($id) {
        $result = $this->articulosModel->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (!$this->validateArticulo($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->articulosModel->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode([
            'status' => 'success',
            'message' => 'Articulo actualizado correctamente'
        ], JSON_THROW_ON_ERROR);
        return $response;
    }

    # Metodo
    private function deleteArticulo($id) {
        $result = $this->articulosModel->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $this->articulosModel->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode([
            'status' => 'success',
            'message' => 'Articulo borrado correctamente'
        ], JSON_THROW_ON_ERROR);
        return $response;
    }

    // --------- MÉTODOS DE IMAGENES --------- //

    public function uploadImage() {
        // Comprueba si se ha enviado un archivo
        if (isset($_FILES['image'])) {
            // Obtiene la información del archivo subido
            $file = $_FILES['image'];

            // Genera un nombre único para el archivo
            $filename = uniqid('', true) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);

            // Mueve el archivo a la carpeta de imágenes
            $destination = 'images/' . $filename;
            move_uploaded_file($file['tmp_name'], $destination);

            // Aquí puedes almacenar la ruta de la imagen en tu base de datos
            $this->articulosModel->storeImagePath($filename);
            // ...

            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode([
                'status' => 'success',
                'message' => 'Imagen subida correctamente'
            ], JSON_THROW_ON_ERROR);
        } else {
            $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
            $response['body'] = json_encode([
                'status' => 'error',
                'message' => 'No se ha enviado ninguna imagen'
            ], JSON_THROW_ON_ERROR);
        }

        return $response;
    }

    public function uploadImageForArticle()
    {
        if (empty($_FILES['image']) || empty($_POST['articuloId'])) {
            echo "hola esto esta vacio";
            return $this->unprocessableEntityResponse();
        }

        $articuloId = $_POST['articuloId'];
        $image = $_FILES['image'];


        // Guarda la imagen en el servidor y obtén la ruta de la imagen
        $imagePath = $this->saveImageToServer($image);
        echo $imagePath;

        if (!$imagePath) {
            return $this->unprocessableEntityResponse();
        }

        // Almacena la ruta de la imagen en la base de datos para el artículo correspondiente
        $this->articulosModel->updateImagePath($articuloId, $imagePath);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode([
            'status' => 'success',
            'message' => 'Imagen subida y asociada al artículo correctamente'
        ], JSON_THROW_ON_ERROR);

        return $response;
    }

    private function saveImageToServer($image)
    {
        $uploadDir = '/Users/ivancabrera/Downloads/';
        $uploadFile = $uploadDir . basename($image['name']);

        echo $uploadFile;
        echo $image['tmp_name'];

        if (move_uploaded_file($image['tmp_name'], $uploadFile)) {
            return $uploadFile;
        }

        return false;
    }



    // --------- MÉTODOS DE VALIDACIÖN --------- //

    private function validateArticulo($input) {
        if (!isset($input['codigo'])) {
            return false;
        }
        if (!isset($input['descripcion'])) {
            return false;
        }
        if (!isset($input['embalaje'])) {
            return false;
        }
        if (!isset($input['nombre_tecnico'])) {
            return false;
        }
        return true;
    }

    // --------- MÉTODOS DE ERRORES --------- //

    private function notFoundResponse() {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }

    private function unprocessableEntityResponse() {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ], JSON_THROW_ON_ERROR);
        return $response;
    }

}