<?php

require __DIR__ . '/vendor/autoload.php';

use App\core\Environment;

// CARREGA VARIÁVEIS DE AMBIENTE
Environment::load(__DIR__);

define('URL', getenv('URL'));


