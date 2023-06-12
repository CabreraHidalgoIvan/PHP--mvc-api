<?php


use ArticulosController\ArticulosController;

require "../app/Controllers/DBArticulosController.php";
require "../bootstrap.php";
require "../app/Controllers/ArticulosController.php";
require "../app/Models/ArticulosModel.php";


header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');


$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

var_dump($uri);
if ($uri[1] !== 'articulos') {
    echo "articulos";
}


// the user id is, of course, optional and must be a number:
$userId = null;
if (isset($uri[2])) {
    $userId = (int) $uri[2];
    echo "userId: " . $userId;
}

$requestMethod = $_SERVER["REQUEST_METHOD"];
echo "requestMethod: " . $requestMethod;

// pass the request method and user ID to the ContactosController and process the HTTP request:
$controller = new ArticulosController($dbConnection, $requestMethod, $userId);
$controller->processRequest();