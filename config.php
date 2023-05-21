<?php

require __DIR__ . '/vendor/autoload.php';

use App\controllers\LoginController;
use App\core\Environment;

// CARREGA VARIÁVEIS DE AMBIENTE
Environment::load(__DIR__);

echo LoginController::getLoginPage();
