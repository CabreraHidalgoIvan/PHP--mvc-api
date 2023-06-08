<?php

require  'vendor/autoload.php';

use App\Controllers\DBContactController;

use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__);

$dotenv->load();

$dbConnection = (new DBContactController())->getConnection();
