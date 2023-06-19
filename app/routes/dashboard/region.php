<?php

use \App\controllers\RegionController;
use \App\core\Response;

$router->get(
  '/dashboard/region',
  function ($request) {
    return new Response(200, RegionController::getRegionDashboardPage($request));
  }
);

$router->get(
  '/dashboard/region/{id}/form',
  function ($request, $id) {
    return new Response(200, RegionController::getRegionFormPage($request, $id));
  }
);

$router->post(
  '/dashboard/region/add',
  function ($request) {
    return new Response(201, RegionController::addRegion($request));
  }
);

$router->post(
  '/dashboard/region/{id}/edit',
  function ($request, $id) {
    return new Response(200, RegionController::editRegion($request, $id));
  }
);

$router->get(
  '/dashboard/region/{id}/delete',
  function ($request, $id) {
    return new Response(200, RegionController::deleteRegion($request, $id));
  }
);

