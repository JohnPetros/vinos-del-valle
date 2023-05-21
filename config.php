<?php

require __DIR__ . '/vendor/autoload.php';

use App\core\Environment;
use App\core\View;

// CARREGA VARIÁVEIS DE AMBIENTE
Environment::load(__DIR__);

define('URL', getenv('URL'));

// DEFINE O VALOR PADRÃO DAS VARIÁVEIS
View::init([
  'URL' => URL
]);
