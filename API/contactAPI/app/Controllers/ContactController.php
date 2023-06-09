<?php

namespace ContactController;

use App\Models\ContactModel;

class ContactController {

    // --------- ATRIBUTOS --------- //

    private $db;
    private $requestMethod;
    private $contactId;
    private $contactModel;

    // --------- CONSTRUCTOR --------- //

    public function __construct($db, $requestMethod, $contactId) {

        $this->db = $db;

        $this->requestMethod = $requestMethod;

        $this->contactId = $contactId;

        $this->contactModel = new ContactModel($db);

    }

    // --------- PROCESAMIENTO --------- //

    # Procesamiento
    /**
     * @throws \JsonException
     */
    public function processRequest() {

        switch ($this->requestMethod) {
            case 'GET':
                if ($this->contactId) {
                    $response = $this->getContact($this->contactId);
                } else {
                    $response = $this->getAllContacts();
                };
                break;
            case 'POST':
                $response = $this->createContactFromRequest();
                break;
            case 'PUT':
                $response = $this->updateContactFromRequest($this->contactId);
                break;
            case 'DELETE':
                $response = $this->deleteContact($this->contactId);
                break;
        }

        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }

    }

    // --------- MÉTODOS --------- //

    private function getAllContacts() {
        $result = $this->contactModel->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    public function getContact($id) {
        $result = $this->contactModel->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    /**
     * @throws \JsonException
     */
    public function createContactFromRequest(): array
    {
        $input = (array)json_decode(file_get_contents('php://input'), TRUE, 512, JSON_THROW_ON_ERROR);
        if (!$this->validateContact($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->contactModel->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode([
            'status' => 'success',
            'message' => 'Contacto creado correctamente'
        ], JSON_THROW_ON_ERROR);
        return $response;
    }


    /**
     * @throws \JsonException
     */
    private function updateContactFromRequest($id) {
        $result = $this->contactModel->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (!$this->validateContact($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->contactModel->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    # Metodo
    private function deleteContact($id) {
        $result = $this->contactModel->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $this->contactModel->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    // --------- MÉTODOS DE VALIDACIÖN --------- //

    private function validateContact($input) {
        if (!isset($input['nombre'])) {
            return false;
        }
        if (!isset($input['telefono'])) {
            return false;
        }
        if (!isset($input['email'])) {
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