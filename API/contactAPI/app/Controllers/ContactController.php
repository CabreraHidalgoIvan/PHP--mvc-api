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

    public function processRequest() {


            switch ($this->requestMethod) {
                case 'GET':
                    $response = $this->getAllContacts();

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

}