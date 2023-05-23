<?php

require __DIR__ . '/vendor/autoload.php';

use App\core\Environment;
use App\core\View;
use App\core\Database;

// CARREGA VARIÁVEIS DE AMBIENTE
Environment::load(__DIR__);

Database::config(
  getenv('DB_HOST'),
  getenv('DB_NAME'),
  getenv('DB_USER'),
  getenv('DB_PASS'),
  getenv('DB_PORT')
);

define('URL', getenv('URL'));

// DEFINE O VALOR DAS VARIÁVEIS COMUNS PARA TODAS AS VIEWS 
View::init([
  'URL' => URL
]);
