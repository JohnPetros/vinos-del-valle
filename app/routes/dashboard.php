<?php

use \App\controllers\DashboardController;
use \App\core\Response;

$router->get('/dashboard', function ($request) {
  return new Response(200, DashboardController::getDashboardPage($request));
});
