<?php

// ROTAS DE VINHO
include __DIR__ . '/wine.php';

// ROTAS DE REGIÃO
include __DIR__ . '/region.php';

// ROTAS DE UVA
include __DIR__ . '/grape.php';

// ROTAS DE USUÁRIO
include __DIR__ . '/user.php';

use \App\controllers\DashboardController;
use \App\core\Response;

$router->get(
  '/dashboard',
  function ($request) {
    return new Response(200, DashboardController::getDashboardPage($request));
  }
);