<?php

require  'vendor/autoload.php';

use Controllers\DBArticulosController;
use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

$dbConnection = (new DBArticulosController())->getConnection();