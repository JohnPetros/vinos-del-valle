<?php

require __DIR__ . '/vendor/autoload.php';

use App\core\Environment;
use App\core\View;
use App\core\Database;

// CARREGA AS VARIÁVEIS DE AMBIENTE
Environment::load(__DIR__);

// CONFIGURA O BANCO DE DADOS
Database::config(
  getenv('DB_HOST'),
  getenv('DB_NAME'),
  getenv('DB_USER'),
  getenv('DB_PASS'),
  getenv('DB_PORT')
);

// DEFINE A URL BASE DA APLICAÇÃO CASO TENHA
define('URL', getenv('URL'));

// DEFINE O VALOR VARIÁVEIS GLOBAIS DA APLICAÇÃO QUE SERÃO COMUNS A TODAS AS VIEWS
View::init([
  'URL' => URL,
  'public_path' => URL . '/public'
]);
