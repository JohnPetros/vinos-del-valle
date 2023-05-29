<?php

use \App\controllers\WineController;
use \App\core\Response;

$router->get(
  '/dashboard/wine',
  function ($request) {
    return new Response(200, WineController::getDashboardWinePage($request));
  }
);

$router->get(
  '/dashboard/wine/{id}/edit',
  function ($request, $id) {
    return new Response(200, WineController::getWineFormPage($request, $id));
  }
);
