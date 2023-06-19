<?php

use \App\controllers\WineController;
use \App\core\Response;

$router->get(
  '/dashboard/wine',
  function ($request) {
    return new Response(200, WineController::getWineDashboardPage($request));
  }
);

$router->get(
  '/dashboard/wine/{id}/form',
  function ($request, $id) {
    return new Response(200, WineController::getWineFormPage($request, $id));
  }
);

$router->post(
  '/dashboard/wine/add',
  function ($request, $id) {
    return new Response(201, WineController::addWine($request, $id));
  }
);

$router->post(
  '/dashboard/wine/{id}/edit',
  function ($request, $id) {
    return new Response(200, WineController::editWine($request, $id));
  }
);

$router->get(
  '/dashboard/wine/{id}/delete',
  function ($request, $id) {
    return new Response(200, WineController::deleteWine($request, $id));
  }
);


