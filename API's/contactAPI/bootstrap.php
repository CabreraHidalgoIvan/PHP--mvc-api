<?php

require  'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

echo getenv('DB_HOST');