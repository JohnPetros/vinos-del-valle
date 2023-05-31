<?php

use \App\controllers\RegionController;
use \App\core\Response;

$router->get(
  '/dashboard/region',
  function ($request) {
    return new Response(200, RegionController::getRegionDashboardPage($request));
  }
);
