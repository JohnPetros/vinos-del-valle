<?php

use \App\controllers\GrapeController;
use \App\core\Response;

$router->get(
  '/dashboard/grape',
  function ($request) {
    return new Response(200, GrapeController::getGrapeDashboardPage($request));
  }
);

$router->get(
  '/dashboard/grape/{id}/form',
  function ($request, $id) {
    return new Response(200, GrapeController::getGrapeFormPage($request, $id));
  }
);

$router->post(
  '/dashboard/grape/add',
  function ($request) {
    return new Response(201, GrapeController::addGrape($request));
  }
);

$router->post(
  '/dashboard/grape/{id}/edit',
  function ($request, $id) {
    return new Response(200, GrapeController::editGrape($request, $id));
  }
);

$router->get(
  '/dashboard/grape/{id}/delete',
  function ($request, $id) {
    return new Response(200, GrapeController::deleteGrape($request, $id));
  }
);
